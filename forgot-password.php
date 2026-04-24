<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
<title>Forgot Password</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body class="forgot-password-body">

<div class="register-card">
    <h2>Forgot Password</h2>

    <form action="php/send_reset.php" method="POST">
        <input type="email" name="email" placeholder="Enter your email" required>
        <button type="submit">Send Reset Link</button>
    </form>
</div>

</body>
</html>