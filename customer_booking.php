<?php
// customer_booking.php

require 'assets/partials/_functions.php';
$conn = db_connect();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["book"])) {
    $customer_name = $_POST["firstName"] . " " . $_POST["lastName"];
    $customer_phone = $_POST["phone"];
    $route_id = $_POST["route_id"];
    $route_source = $_POST["source"];
    $route_destination = $_POST["destination"];
    $route = $route_source . " &rarr; " . $route_destination;
    $booked_seat = $_POST["seat_selected"];
    $amount = $_POST["booked_amount"];

    // First create the customer
    $customerSql = "INSERT INTO `customers` (`customer_name`, `customer_phone`) VALUES ('$customer_name', '$customer_phone')";
    $customerResult = mysqli_query($conn, $customerSql);
    
    if ($customerResult) {
        $customer_id = mysqli_insert_id($conn);
        
        // Now create the booking
        $bookingSql = "INSERT INTO `bookings` (`customer_id`, `route_id`, `customer_route`, `booked_amount`, `booked_seat`, `booking_created`) VALUES ('$customer_id', '$route_id', '$route', '$amount', '$booked_seat', current_timestamp())";
        $bookingResult = mysqli_query($conn, $bookingSql);
        
        if ($bookingResult) {
            // Update the seats table
            $bus_no = get_from_table($conn, "routes", "route_id", $route_id, "bus_no");
            $seats = get_from_table($conn, "seats", "bus_no", $bus_no, "seat_booked");
            if ($seats) {
                $seats .= "," . $booked_seat;
            } else {
                $seats = $booked_seat;
            }
            $updateSeatSql = "UPDATE `seats` SET `seat_booked` = '$seats' WHERE `seats`.`bus_no` = '$bus_no'";
            mysqli_query($conn, $updateSeatSql);
            
            echo '<div class="my-0 alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> Booking created successfully!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
        }
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
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/styles/styles.css">
    <style>
        #seatsDiagram td {
            border: 1px solid #ccc;
            width: 40px;
            height: 40px;
            text-align: center;
            cursor: pointer;
        }
        #seatsDiagram .space {
            border: none;
            background: transparent;
            cursor: default;
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
                            <option value="Vellore">Vellore</option>
                            <option value="Trichy">Trichy</option>
                            <option value="Tirunelveli">Tirunelveli</option>
                            <option value="Theni">Theni</option>
                            <option value="Madurai">Madurai</option>
                            <option value="Karur">Karur</option>
                            <option value="Erode">Erode</option>
                            <option value="Chennai">Chennai</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="destinationSearch" class="form-label">Destination</label>
                        <select class="form-select" id="destinationSearch" name="destinationSearch" required>
                            <option value="">Select Destination</option>
                            <option value="Tiruvannamalai">Tiruvannamalai</option>
                            <option value="Thanjavur">Thanjavur</option>
                            <option value="Nagercoil">Nagercoil</option>
                            <option value="Dindigul">Dindigul</option>
                            <option value="Salem">Salem</option>
                            <option value="Ariyalur">Ariyalur</option>
                            <option value="Namakkal">Namakkal</option>
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
                    </div>
                    <input type="hidden" id="route_id" name="route_id" value="">
                    <input type="hidden" id="source" name="source" value="">
                    <input type="hidden" id="destination" name="destination" value="">
                    <input type="hidden" id="seat_selected" name="seat_selected" value="">
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
                    <div class="mb-3">
                        <label for="seatInput" class="form-label">Selected Seat</label>
                        <input type="text" class="form-control" id="seatInput" name="seatInput" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="bookAmount" class="form-label">Total Amount</label>
                        <input type="text" class="form-control" id="bookAmount" name="bookAmount" value="" readonly>
                    </div>
                    <script>
                        // When a seat is selected, set the amount to 500
                        document.addEventListener('DOMContentLoaded', function() {
                            const seats = document.querySelectorAll('#seatsDiagram td:not(.space)');
                            const seatInput = document.getElementById('seatInput');
                            const bookAmount = document.getElementById('bookAmount');
                            seats.forEach(seat => {
                                seat.addEventListener('click', function() {
                                    if (!seat.classList.contains('notAvailable')) {
                                        seatInput.value = seat.dataset.name;
                                        bookAmount.value = 500;
                                    }
                                });
                            });
                        });
                    </script>
                    <a href="http://localhost/BusBook/payment/index.html" class="btn btn-primary w-100">Book Ticket</a>
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


