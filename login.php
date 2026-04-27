<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | SkillSwap</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>


<nav class="navbar">
    <div class="logo">SkillSwap</div>
    <ul class="nav-links">
        <li><a href="index.html">Home</a></li>
        <li><a href="register.php">Register</a></li>
        <li><a href="login.php">Login</a></li>
    </ul>
</nav>

<section class="register-section">

    <div class="register-card">

        <h2>Login to Your Account</h2>

        <form action="php/login_process.php" method="POST">

            <input type="email" name="email" placeholder="Email Address" required>

            <input type="password" name="password" placeholder="Password" required>

            <button type="submit">Login</button>

        </form>

        <p class="login-link">
            
            <a href="forgot-password.php" style="display:block; margin-top:10px;">
    Forgot Password?
</a>
        </p>

    </div>

</section>

</body>
</html>
