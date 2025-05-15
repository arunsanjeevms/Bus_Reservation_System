<?php
session_start();
require '../assets/partials/_functions.php';
$conn = db_connect();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['booking_id']) || !isset($_SESSION['amount'])) {
        header("Location: ../customer_booking.php");
        exit();
    }

    $booking_id = $_SESSION['booking_id'];
    $amount = $_SESSION['amount'];
    $payment_status = "COMPLETED";
    $payment_method = $_POST['payment_method'] ?? 'UPI';
    $payment_date = date('Y-m-d H:i:s');

    // Update booking with payment information
    $updateSql = "UPDATE bookings SET 
                  payment_status = '$payment_status',
                  payment_method = '$payment_method',
                  payment_date = '$payment_date'
                  WHERE booking_id = '$booking_id'";

    if(mysqli_query($conn, $updateSql)) {
        // Payment successful
        $_SESSION['payment_success'] = true;
        $_SESSION['payment_message'] = "Payment of ₹$amount successfully processed for booking $booking_id";
        
        // Redirect to success page
        header("Location: success.html");
        exit();
    } else {
        // Payment failed
        $_SESSION['payment_success'] = false;
        $_SESSION['payment_message'] = "Payment processing failed. Please try again.";
        
        // Redirect back to payment page
        header("Location: index.php");
        exit();
    }
} else {
    // If accessed directly without POST, redirect to booking page
    header("Location: ../customer_booking.php");
    exit();
}
?> 