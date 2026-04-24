<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | SkillSwap</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<!-- Navigation Bar -->

<nav class="navbar">
    <div class="logo">SkillSwap</div>
    <ul class="nav-links">
        <li><a href="index.html">Home</a></li>
        <li><a href="register.php">Register</a></li>
        <li><a href="login.php">Login</a></li>
    </ul>
</nav>

<!-- Register Section -->

<section class="register-section">


<div class="register-card">

    <h2>Create Your Account</h2>

   <form action="php/register_process.php" method="POST">

        <input type="text" name="name" placeholder="Full Name" required>

        <input type="email" name="email" placeholder="Email Address" required>

        <input type="password" name="password" placeholder="Password" required>

        <input type="text" name="skill" placeholder="Your Skill" required>

        <button type="submit">Register</button>

    </form>

    <p class="login-link">
        Already have an account?
        <a href="login.php">Login</a>
    </p>

</div>


</section>

</body>
</html>
