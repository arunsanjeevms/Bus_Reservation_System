<?php
session_start();
require '../assets/partials/_functions.php';
$conn = db_connect();

// Check if booking details exist in session
if (!isset($_SESSION['booking_id']) || !isset($_SESSION['amount'])) {
    header("Location: ../customer_booking.php");
    exit();
}

$booking_id = $_SESSION['booking_id'];
$customer_id = $_SESSION['customer_id'];
$amount = $_SESSION['amount'];

// Fetch booking details
$bookingQuery = "SELECT b.*, c.customer_name, c.customer_phone 
                FROM bookings b 
                JOIN customers c ON b.customer_id = c.customer_id 
                WHERE b.booking_id = '$booking_id'";
$bookingResult = mysqli_query($conn, $bookingQuery);
$bookingData = mysqli_fetch_assoc($bookingResult);

// Store additional details in variables
$customer_name = $bookingData['customer_name'];
$customer_phone = $bookingData['customer_phone'];
$route = $bookingData['customer_route'];
$seat_number = $bookingData['booked_seat'];
$booking_date = date('d M Y', strtotime($bookingData['booking_created']));

// Make these variables available to JavaScript
echo "<script>
    var ticketDetails = {
        bookingId: '$booking_id',
        customerId: '$customer_id',
        customerName: '$customer_name',
        customerPhone: '$customer_phone',
        route: '$route',
        seatNumber: '$seat_number',
        amount: '$amount',
        bookingDate: '$booking_date'
    };
</script>";

// Include the HTML content
include 'payment.html';
?> 