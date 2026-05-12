<?php 
error_reporting(0); 

include 'db.php'; 
include 'navbar.php'; 

// 3. Session start 
if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
}

$error_msg = "";
$step = 1; 

// --- LOGIC: SEND OTP (Step 1) ---
if(isset($_POST['send_otp'])){
    $email = $_POST['email'];
    
    $check = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();

    if($result->num_rows > 0){
        $otp = rand(100000, 999999);
        
        $_SESSION['temp_otp'] = $otp;
        $_SESSION['temp_email'] = $email;
        
        $error_msg = "Success: OTP Sent! (Testing Code: $otp)"; 
        $step = 2;
    } else {
        $error_msg = "⚠️ No account found. Please Sign Up first!";
    }
}

// --- LOGIC: VERIFY & LOGIN (Step 2) ---
if(isset($_POST['verify_login'])){
    $user_otp = $_POST['otp'];
    
    if($user_otp == $_SESSION['temp_otp']){
        $_SESSION['user_logged_in'] = true;
        $_SESSION['user_email'] = $_SESSION['temp_email'];
        
        header("Location: dashboard.php");
        exit();
    } else {
        $error_msg = "❌ Invalid OTP! Please try again.";
        $step = 2; 
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | CourseToCareer</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="card">
    <h2>Login</h2>
    
    <form method="post">
        <?php if($step == 1): ?>
            <input type="email" name="email" placeholder="Enter Registered Email" required>
            <button type="submit" name="send_otp">SEND OTP</button>
        <?php else: ?>
            <p style="font-size: 13px; color: #ddd; margin-bottom: 10px;">
                OTP sent to: <b><?php echo $_SESSION['temp_email']; ?></b>
            </p>
            <input type="text" name="otp" placeholder="Enter 6-Digit OTP" required maxlength="6" autocomplete="off">
            <button type="submit" name="verify_login">VERIFY & LOGIN</button>
            <br>
            <a href="login.php" style="color: #377aad; font-size: 12px; text-decoration: none; margin-top: 10px; display: inline-block;">Try Again</a>
        <?php endif; ?>
    </form>

    <?php if($error_msg != ""): ?>
        <p style="color: #ffcc00; font-size: 13px; margin-top: 15px; background: rgba(0,0,0,0.4); padding: 8px; border-radius: 8px; border: 1px solid rgba(255,204,0,0.3);">
            <?php echo $error_msg; ?>
        </p>
    <?php endif; ?>

    <p style="font-weight: normal; font-size: 14px; margin-top: 20px;">
        Don't have an account? <a href="register.php" style="color: #4facfe; font-weight: bold; text-decoration: none;">Sign Up</a>
    </p>
</div>

</body>
</html>