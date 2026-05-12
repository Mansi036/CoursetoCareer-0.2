<?php 
// This must be the absolute first thing in the file
include 'navbar.php'; 
?>
<?php 
include 'db.php'; 

$show_form = true; 
$message = ""; 

if(isset($_POST['register'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users(name, email, password) VALUES(?,?,?)");
    $stmt->bind_param("sss", $name, $email, $pass);
    
    if($stmt->execute()){
        $message = "Registration Successful!";
        $show_form = false; 
    } else {
        $message = "Error: Email already exists!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | CourseToCareer</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="glass-box">
    <h2>Register</h2>

    <?php if($show_form): ?>
        <form method="post">
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Enter Email" required>
            <input type="password" name="password" placeholder="Create Password" required>
            <button type="submit" name="register">Register Now</button>
        </form>
        <p style="font-weight: normal; font-size: 14px; margin-top: 15px;">
            Already have an account? <a href="login.php" style="color: #4facfe; text-decoration: none; font-weight: bold;">Login</a>
        </p>

    <?php else: ?>
        <div class="success-screen">
            <div style="font-size: 50px; margin-bottom: 10px;">✅</div>
            <p style="color: #12b912; font-size: 20px; font-weight: bold;"><?php echo $message; ?></p>
            <br>
            <a href="login.php" style="color: white; text-decoration: underline;">Go to Login</a>
        </div>
    <?php endif; ?>

    <?php if($show_form && $message != ""): ?>
         <p style="color: #d33131;"><?php echo $message; ?></p>
    <?php endif; ?>
</div>

</body>
</html>