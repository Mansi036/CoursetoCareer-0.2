<?php
include 'db.php';

if (isset($_POST['email'])) {
    $email = $_POST['email'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($row = $res->fetch_assoc()) {
        $otp = rand(100000, 999999);

        $stmt = $conn->prepare("UPDATE users SET otp=? WHERE email=?");
        $stmt->bind_param("is", $otp, $email); // Use "i" for integer OTP
        $stmt->execute();

        // This is the ONLY thing sent back to your JavaScript
        echo "OTP sent: " . $otp; 
    } else {
        echo "Email not found";
    }
}
?>