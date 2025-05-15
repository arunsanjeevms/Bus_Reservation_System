<?php
    require '_functions.php';
    $conn = db_connect();
    
    // Get admin details
    $admin_exists = false;
    $admin_details = array();
    
    // Initialize session if not started
    if(session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    // Check if admin is logged in
    if(isset($_SESSION["admin_id"])) {
        $admin_exists = true;
        
        // Get admin details
        $admin_id = $_SESSION["admin_id"];
        $sql = "SELECT * FROM `admin` WHERE admin_id='$admin_id'";
        $result = mysqli_query($conn, $sql);
        $admin_details = mysqli_fetch_assoc($result);
    }
?> 