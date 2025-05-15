<?php
// customer_booking.php

require 'assets/partials/_functions.php';
$conn = db_connect();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["book"])) {
    // Get all form fields
    $customer_name = $_POST["firstName"] . " " . $_POST["lastName"];
    $customer_phone = $_POST["phone"];
    $customer_email = $_POST["email"];
    $gender = $_POST["gender"];
    $blood_group = $_POST["bloodGroup"];
    $differently_abled = $_POST["differentlyAbled"];
    $route_source = $_POST["sourceSearch"];
    $route_destination = $_POST["destinationSearch"];
    $booked_seat = $_POST["seat_selected"];
    $amount = $_POST["booked_amount"];

    // Get route_id from the database
    $routeQuery = "SELECT route_id, bus_no FROM routes WHERE 
                   FIND_IN_SET('$route_source', REPLACE(route_cities, ' ', '')) > 0 
                   AND FIND_IN_SET('$route_destination', REPLACE(route_cities, ' ', '')) > 0 
                   LIMIT 1";
    $routeResult = mysqli_query($conn, $routeQuery);
    
    if($routeResult && mysqli_num_rows($routeResult) > 0) {
        $routeData = mysqli_fetch_assoc($routeResult);
        $route_id = $routeData['route_id'];
        $bus_no = $routeData['bus_no'];
        
        // Create the route string
        $route = $route_source . " → " . $route_destination;

        // Generate customer ID
        $lastCustomerQuery = "SELECT customer_id FROM customers ORDER BY id DESC LIMIT 1";
        $lastCustomerResult = mysqli_query($conn, $lastCustomerQuery);
        $lastCustomer = mysqli_fetch_assoc($lastCustomerResult);
        
        if ($lastCustomer) {
            $lastNum = intval(substr($lastCustomer['customer_id'], -3)) + 1;
        } else {
            $lastNum = 1;
        }
        
        $customer_id = '927623BCS' . str_pad($lastNum, 3, '0', STR_PAD_LEFT);

        // Create the customer
        $customerSql = "INSERT INTO `customers` (`customer_id`, `customer_name`, `customer_phone`) VALUES ('$customer_id', '$customer_name', '$customer_phone')";
        $customerResult = mysqli_query($conn, $customerSql);
        
        if ($customerResult) {
            // Generate booking ID
            $lastBookingQuery = "SELECT booking_id FROM bookings ORDER BY id DESC LIMIT 1";
            $lastBookingResult = mysqli_query($conn, $lastBookingQuery);
            $lastBooking = mysqli_fetch_assoc($lastBookingResult);
            
            if ($lastBooking) {
                $lastNum = intval(substr($lastBooking['booking_id'], 3)) + 1;
            } else {
                $lastNum = 1;
            }
            
            $booking_id = 'BKG' . str_pad($lastNum, 5, '0', STR_PAD_LEFT);

            // Create the booking
            $bookingSql = "INSERT INTO `bookings` (`booking_id`, `customer_id`, `route_id`, `customer_route`, `booked_amount`, `booked_seat`, `booking_created`) 
                          VALUES ('$booking_id', '$customer_id', '$route_id', '$route', '$amount', '$booked_seat', current_timestamp())";
            $bookingResult = mysqli_query($conn, $bookingSql);
            
            if ($bookingResult) {
                // Update the seats
                $seats = get_from_table($conn, "seats", "bus_no", $bus_no, "seat_booked");
                if ($seats) {
                    $seats .= "," . $booked_seat;
                } else {
                    $seats = $booked_seat;
                }
                $updateSeatSql = "UPDATE `seats` SET `seat_booked` = '$seats' WHERE `seats`.`bus_no` = '$bus_no'";
                mysqli_query($conn, $updateSeatSql);
                
                // Store booking details in session for payment page
                session_start();
                $_SESSION['booking_id'] = $booking_id;
                $_SESSION['customer_id'] = $customer_id;
                $_SESSION['amount'] = $amount;
                
                // Redirect to payment page
                header("Location: payment/index.php");
                exit();
            } else {
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong> Unable to complete booking.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
            }
        } else {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong> Unable to create customer record.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
        }
    } else {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> No route available for selected source and destination.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Ticket Booking</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/styles/styles.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('assets/img/bg.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
            min-height: 100vh;
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
        }

        .container {
            position: relative;
            z-index: 1;
        }

        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.2);
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .card-title {
            color: #2563eb;
            font-weight: 700;
            font-size: 2rem;
            margin-bottom: 2rem;
            position: relative;
        }

        .card-title:after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background: #2563eb;
            border-radius: 2px;
        }

        .form-select, .form-control {
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-select:focus, .form-control:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }

        .seat-layout {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.05);
        }

        #seatsDiagram td {
            border: 2px solid #e5e7eb;
            width: 45px;
            height: 45px;
            text-align: center;
            cursor: pointer;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
            background-color: white;
        }

        #seatsDiagram td.booked {
            background-color: #ef4444;
            color: white;
            border-color: #dc2626;
            cursor: not-allowed;
            opacity: 0.8;
        }

        #seatsDiagram td:not(.space):hover {
            background-color: #e5e7eb;
            transform: scale(1.1);
        }

        .selected-seat {
            background-color: #10b981 !important;
            color: white !important;
            border-color: #059669 !important;
        }

        .btn-primary {
            background: #2563eb;
            border: none;
            padding: 0.75rem 2rem;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: #1e40af;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
        }

        @media (max-width: 768px) {
            #seatsDiagram td {
                width: 35px;
                height: 35px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <main class="container mt-5">
        <div class="card">
            <div class="card-body">
                <h2 class="card-title text-center mb-4">Book Your Bus Ticket</h2>
                <form id="customerBookingForm" method="POST">
                    <div class="mb-3">
                        <label for="sourceSearch" class="form-label">From</label>
                        <select class="form-select" id="sourceSearch" name="sourceSearch" required>
                            <option value="">Select City</option>
                            <option value="Madurai">Madurai</option>
                            <option value="Trichy">Trichy</option>
                            <option value="Erode">Erode</option>
                            <option value="Tirunelveli">Tirunelveli</option>
                            <option value="Vellore">Vellore</option>
                            <option value="Karur">Karur</option>
                            <option value="Chennai">Chennai</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="destinationSearch" class="form-label">Destination</label>
                        <select class="form-select" id="destinationSearch" name="destinationSearch" required>
                            <option value="">Select Destination</option>
                            <option value="Salem">Salem</option>
                            <option value="Thanjavur">Thanjavur</option>
                            <option value="Namakkal">Namakkal</option>
                            <option value="Nagercoil">Nagercoil</option>
                            <option value="Tiruvannamalai">Tiruvannamalai</option>
                            <option value="Namakkal">Namakkal</option>
                            <option value="Ariyalur">Ariyalur</option>
                            <option value="Coimbatore">Coimbatore</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Select Your Seat</label>
                        <div class="seat-layout">
                            <div class="seat-legend mb-3">
                                <div class="d-flex align-items-center me-3">
                                    <div class="seat-example available"></div>
                                    <span class="ms-2">Available</span>
                                </div>
                                <div class="d-flex align-items-center me-3">
                                    <div class="seat-example selected"></div>
                                    <span class="ms-2">Selected</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="seat-example notAvailable"></div>
                                    <span class="ms-2">Booked</span>
                                </div>
                            </div>
                            <table id="seatsDiagram" class="mx-auto">
                                <tr>
                                    <td id="seat-1" data-name="1">1</td>
                                    <td id="seat-2" data-name="2">2</td>
                                    <td id="seat-3" data-name="3">3</td>
                                    <td id="seat-4" data-name="4">4</td>
                                    <td id="seat-5" data-name="5">5</td>
                                    <td id="seat-6" data-name="6">6</td>
                                    <td id="seat-7" data-name="7">7</td>
                                    <td id="seat-8" data-name="8">8</td>
                                    <td id="seat-9" data-name="9">9</td>
                                    <td id="seat-10" data-name="10">10</td>
                                </tr>
                                <tr>
                                    <td id="seat-11" data-name="11">11</td>
                                    <td id="seat-12" data-name="12">12</td>
                                    <td id="seat-13" data-name="13">13</td>
                                    <td id="seat-14" data-name="14">14</td>
                                    <td id="seat-15" data-name="15">15</td>
                                    <td id="seat-16" data-name="16">16</td>
                                    <td id="seat-17" data-name="17">17</td>
                                    <td id="seat-18" data-name="18">18</td>
                                    <td id="seat-19" data-name="19">19</td>
                                    <td id="seat-20" data-name="20">20</td>
                                </tr>
                                <tr>
                                    <td class="space">&nbsp;</td>
                                    <td class="space">&nbsp;</td>
                                    <td class="space">&nbsp;</td>
                                    <td class="space">&nbsp;</td>
                                    <td class="space">&nbsp;</td>
                                    <td class="space">&nbsp;</td>
                                    <td class="space">&nbsp;</td>
                                    <td class="space">&nbsp;</td>
                                    <td class="space">&nbsp;</td>
                                    <td class="space">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td id="seat-21" data-name="21">21</td>
                                    <td id="seat-22" data-name="22">22</td>
                                    <td id="seat-23" data-name="23">23</td>
                                    <td id="seat-24" data-name="24">24</td>
                                    <td id="seat-25" data-name="25">25</td>
                                    <td id="seat-26" data-name="26">26</td>
                                    <td id="seat-27" data-name="27">27</td>
                                    <td id="seat-28" data-name="28">28</td>
                                    <td id="seat-29" data-name="29">29</td>
                                    <td id="seat-30" data-name="30">30</td>
                                </tr>
                                <tr>
                                    <td id="seat-31" data-name="31">31</td>
                                    <td id="seat-32" data-name="32">32</td>
                                    <td id="seat-33" data-name="33">33</td>
                                    <td id="seat-34" data-name="34">34</td>
                                    <td id="seat-35" data-name="35">35</td>
                                    <td id="seat-36" data-name="36">36</td>
                                    <td id="seat-37" data-name="37">37</td>
                                    <td id="seat-38" data-name="38">38</td>
                                    <td class="space">&nbsp;</td>
                                    <td class="space">&nbsp;</td>
                                </tr>
                            </table>
                        </div>
                        <label for="booked_seat" class="form-lab1el">Selected Seat</label>
                        <input type="hidden" id="seat_selected" name="seat_selected">
                        <input type="text" class="form-control mt-2" id="seatInput" readonly placeholder="Selected seat will appear here">
                    </div>
                    <div class="mb-3">
                        <label for="booked_amount" class="form-label">Total Amount</label>
                        <input type="text" class="form-control" id="bookAmount" name="booked_amount" value="500" readonly>
                    </div>
                    <input type="hidden" id="route_id" name="route_id" value="">
                    <input type="hidden" id="source" name="source" value="">
                    <input type="hidden" id="destination" name="destination" value="">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="firstName" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="firstName" name="firstName" required>
                        </div>
                        <div class="col-md-6">
                            <label for="lastName" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="lastName" name="lastName" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="tel" class="form-control" id="phone" name="phone" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gender</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" id="genderMale" value="Male" required>
                                <label class="form-check-label" for="genderMale">Male</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" id="genderFemale" value="Female" required>
                                <label class="form-check-label" for="genderFemale">Female</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" id="genderOther" value="Other" required>
                                <label class="form-check-label" for="genderOther">Other</label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="bloodGroup" class="form-label">Blood Group</label>
                        <select class="form-select" id="bloodGroup" name="bloodGroup" required>
                            <option value="">Select Blood Group</option>
                            <option value="A+">A+</option>
                            <option value="A-">A-</option>
                            <option value="B+">B+</option>
                            <option value="B-">B-</option>
                            <option value="AB+">AB+</option>
                            <option value="AB-">AB-</option>
                            <option value="O+">O+</option>
                            <option value="O-">O-</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="differentlyAbled" class="form-label">Are you differently abled?</label>
                        <select class="form-select" id="differentlyAbled" name="differentlyAbled" required>
                            <option value="">Select Option</option>
                            <option value="No">No</option>
                            <option value="Yes">Yes</option>
                        </select>
                    </div>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const seats = document.querySelectorAll('#seatsDiagram td:not(.space)');
                            const seatInput = document.getElementById('seatInput');
                            const seatSelected = document.getElementById('seat_selected');
                            const bookAmount = document.getElementById('bookAmount');
                            let selectedSeat = null;

                            // Handle seat selection
                            seats.forEach(seat => {
                                seat.addEventListener('click', function() {
                                    // If seat is already booked, don't allow selection
                                    if (seat.classList.contains('notAvailable')) {
                                        return;
                                    }

                                    // Remove previous selection
                                    if (selectedSeat) {
                                        selectedSeat.classList.remove('selected-seat');
                                    }

                                    // Select new seat
                                    selectedSeat = seat;
                                    seat.classList.add('selected-seat');
                                    
                                    // Update form fields
                                    const seatNumber = seat.dataset.name;
                                    console.log('Selected seat:', seatNumber); // Debug log
                                    
                                    if (seatInput) seatInput.value = seatNumber;
                                    if (seatSelected) seatSelected.value = seatNumber;
                                    if (bookAmount) bookAmount.value = '500';
                                });
                            });

                            // Form validation
                            const form = document.getElementById('customerBookingForm');
                            if (form) {
                                form.addEventListener('submit', function(e) {
                                    const selectedSeatValue = seatSelected ? seatSelected.value : '';
                                    console.log('Form submission - Selected seat:', selectedSeatValue); // Debug log
                                    
                                    if (!selectedSeatValue) {
                                        e.preventDefault();
                                        alert('Please select a seat before booking!');
                                        return false;
                                    }
                                });
                            }
                        });
                    </script>
                    <button type="submit" name="book" class="btn btn-primary w-100">Book Ticket</button>
                </form>
            </div>
        </div>
    </main>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <?php require 'assets/partials/_getJSON.php';?>
    <script src="assets/scripts/customer_booking.js"></script>
    
</body>
</html>


