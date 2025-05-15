// Get form elements
const bookingForm = document.getElementById("customerBookingForm");
const seatsDiagram = document.getElementById("seatsDiagram");
const seatInput = document.getElementById("seatInput");
const bookAmount = document.getElementById("bookAmount");
const sourceSelect = document.getElementById("sourceSelect");
const destinationSelect = document.getElementById("destinationSelect");
const routeSelect = document.getElementById("routeSelect");
const departureTime = document.getElementById("departureTime");

// Add event listeners for search functionality
document.body.addEventListener("click", listenForSearches);

// Handle seat selection
let selected_id;
seatsDiagram.addEventListener("click", (evt) => {
    if (evt.target.nodeName == "TD" && !evt.target.className.includes("space") && !evt.target.className.includes("notAvailable")) {
        // Remove previous selection
        if (selected_id && selected_id !== evt.target.dataset.name) {
            const prevSelected = document.querySelector(`#seat-${selected_id}`);
            if (prevSelected) {
                prevSelected.classList.remove("selected");
            }
        }

        // Toggle current selection
        selected_id = evt.target.dataset.name;
        evt.target.classList.toggle("selected");

        if (!evt.target.className.includes("selected")) {
            selected_id = "";
        }

        // Update inputs
        seatInput.value = selected_id;
        document.getElementById("seat_selected").value = selected_id;
    }
});

// Function to format date
function formatDate(dateStr) {
    const date = new Date(dateStr);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
}

// Function to format time
function formatTime(timeStr) {
    const [hours, minutes] = timeStr.split(':');
    const date = new Date();
    date.setHours(hours);
    date.setMinutes(minutes);
    return date.toLocaleTimeString('en-US', {
        hour: '2-digit',
        minute: '2-digit',
        hour12: true
    });
}

// Function to update available routes based on selected source and destination
function updateAvailableRoutes() {
    const source = sourceSelect.value;
    const destination = destinationSelect.value;

    // Clear current options
    routeSelect.innerHTML = '<option value="">Select Bus Route</option>';
    departureTime.value = '';
    bookAmount.value = '';

    if (source && destination) {
        // Filter routes that match the selected source and destination
        const availableRoutes = routesData.filter(route => {
            const cities = route.route_cities.split(",").map(city => city.trim());
            return cities[0] === source && cities[1] === destination;
        });

        // Sort routes by date and time
        availableRoutes.sort((a, b) => {
            const dateA = new Date(a.route_dep_date + ' ' + a.route_dep_time);
            const dateB = new Date(b.route_dep_date + ' ' + b.route_dep_time);
            return dateA - dateB;
        });

        if (availableRoutes.length === 0) {
            routeSelect.innerHTML = '<option value="">No buses available for this route</option>';
        } else {
            routeSelect.innerHTML = '<option value="">Select Bus</option>';
            // Add available routes to the dropdown
            availableRoutes.forEach(route => {
                const option = document.createElement('option');
                option.value = route.route_id;
                option.textContent = `Bus ${route.bus_no} - ${formatDate(route.route_dep_date)} ${formatTime(route.route_dep_time)} - ₹${route.route_step_cost}`;
                option.dataset.busNo = route.bus_no;
                option.dataset.depDate = formatDate(route.route_dep_date);
                option.dataset.depTime = formatTime(route.route_dep_time);
                option.dataset.cost = route.route_step_cost;
                option.dataset.bookedSeats = route.seat_booked || '';
                routeSelect.appendChild(option);
            });
        }
    }

    // Clear seat selection
    clearSeatSelection();
}

// Function to clear seat selection
function clearSeatSelection() {
    if (selected_id) {
        const prevSelected = document.querySelector(`#seat-${selected_id}`);
        if (prevSelected) {
            prevSelected.classList.remove("selected");
        }
    }
    selected_id = "";
    seatInput.value = "";
    document.getElementById("seat_selected").value = "";
}

// Function to mark booked seats
function markBookedSeats(bookedSeatsStr) {
    // Clear all seat markings
    const seats = seatsDiagram.querySelectorAll("td[data-name]");
    seats.forEach(seat => {
        seat.classList.remove("notAvailable");
    });

    // Mark booked seats
    if (bookedSeatsStr) {
        const bookedSeats = bookedSeatsStr.split(",");
        bookedSeats.forEach(seatNo => {
            const seatElement = document.querySelector(`#seat-${seatNo}`);
            if (seatElement) {
                seatElement.classList.add("notAvailable");
            }
        });
    }
}

// Event listeners for dropdowns
sourceSelect.addEventListener("change", () => {
    // Clear destination if source is selected as destination
    if (sourceSelect.value === destinationSelect.value) {
        destinationSelect.value = "";
    }
    updateAvailableRoutes();
});

destinationSelect.addEventListener("change", () => {
    // Clear source if destination is selected as source
    if (sourceSelect.value === destinationSelect.value) {
        sourceSelect.value = "";
    }
    updateAvailableRoutes();
});

routeSelect.addEventListener("change", () => {
    const selectedOption = routeSelect.options[routeSelect.selectedIndex];
    if (selectedOption.value) {
        departureTime.value = `${selectedOption.dataset.depDate} ${selectedOption.dataset.depTime}`;
        bookAmount.value = `₹${selectedOption.dataset.cost}`;
        markBookedSeats(selectedOption.dataset.bookedSeats);
    } else {
        departureTime.value = '';
        bookAmount.value = '';
        markBookedSeats('');
    }
    clearSeatSelection();
});

// Search functionality
function listenForSearches(evt) {
    // Check if the element is a search input
    if (evt.target.classList.contains("searchInput")) {
        // Get the search query div
        const searchQuery = evt.target.parentElement;
        const suggBox = searchQuery.querySelector(".sugg");

        // Add a keyup event listener to the search input
        evt.target.addEventListener("keyup", (e) => {
            let searchTerm = e.target.value;
            if (searchTerm.length >= 1) {
                suggBox.style.display = "block";
                // Get matching cities from the routes
                const matches = searchCities(searchTerm);
                if (matches.length === 0) {
                    suggBox.innerHTML = `<div>No matches found</div>`;
                } else {
                    const html = matches.map(match => {
                        return `<div class="sugg-item">${match}</div>`;
                    }).join("");
                    suggBox.innerHTML = html;
                }
            } else {
                suggBox.style.display = "none";
            }
        });
    }

    // Handle suggestion click
    if (evt.target.classList.contains("sugg-item")) {
        const text = evt.target.textContent;
        const searchInput = evt.target.parentElement.parentElement.querySelector(".searchInput");
        searchInput.value = text;
        evt.target.parentElement.style.display = "none";

        // Update booking amount when source or destination is selected
        updateBookingAmount();
    }
}

// Helper function to search cities
function searchCities(searchTerm) {
    const cities = [];
    routeJson.forEach(route => {
        const sourceCity = route.route_cities.split(",")[0];
        const destCity = route.route_cities.split(",")[1];
        if (!cities.includes(sourceCity)) cities.push(sourceCity);
        if (!cities.includes(destCity)) cities.push(destCity);
    });

    return cities.filter(city => 
        city.toLowerCase().includes(searchTerm.toLowerCase())
    );
}

// Form submission
bookingForm.addEventListener("submit", (e) => {
    e.preventDefault();

    // Validate form
    const source = sourceSelect.value;
    const destination = destinationSelect.value;
    const route = routeSelect.value;
    const firstName = document.querySelector("#firstName").value;
    const lastName = document.querySelector("#lastName").value;
    const phone = document.querySelector("#phone").value;
    const seat = seatInput.value;
    const amount = bookAmount.value;

    if (!source || !destination || !route || !firstName || !lastName || !phone || !seat || !amount) {
        alert("Please fill in all fields");
        return;
    }

    if (source === destination) {
        alert("Source and destination cannot be the same");
        return;
    }

    // Submit the form
    bookingForm.submit();
}); 