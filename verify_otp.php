<?php
session_start(); 
include 'db.php';

// Check if data was actually posted
if (isset($_POST['email']) && isset($_POST['otp'])) {
    $email = $_POST['email'];
    $otp = $_POST['otp'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email=? AND otp=?");
    $stmt->bind_param("ss", $email, $otp);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($row = $res->fetch_assoc()) {
        // Set session variables
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['user_name'] = $row['name'];

        // Optional: Clear the OTP in DB so it can't be used again
        $clear = $conn->prepare("UPDATE users SET otp=NULL WHERE id=?");
        $clear->bind_param("i", $row['id']);
        $clear->execute();

        echo "success"; 
    } else {
        echo "Invalid OTP or Email";
    }
} else {
    echo "Direct access not allowed";
}
?>