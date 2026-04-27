<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include("php/db.php");

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$email = $_SESSION['user'];


$user_query = "SELECT * FROM users WHERE email='$email'";
$user_result = mysqli_query($conn, $user_query);
$user = mysqli_fetch_assoc($user_result);

$user_id = $user['id'];

$username = $user['name'] ?? "User";
$profile_pic = $user['profile_image'] ?? "default.png";

//skill count
$skill_query = "SELECT COUNT(*) as total FROM skills WHERE user_id='$user_id'";
$skill_result = mysqli_query($conn, $skill_query);
$skills = mysqli_fetch_assoc($skill_result)['total'] ?? 0;

// Count Requests
$request_query = "SELECT COUNT(*) as total FROM requests WHERE receiver_id='$user_id'";
$request_result = mysqli_query($conn, $request_query);
$requests = mysqli_fetch_assoc($request_result)['total'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard | SkillSwap</title>

<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
.user-profile img{
    width:35px;
    height:35px;
    border-radius:50%;
    object-fit:cover;
    margin-right:8px;
    cursor:pointer;
}

#popup{
    position:fixed;
    top:20px;
    right:20px;
    background:#4CAF50;
    color:white;
    padding:15px;
    border-radius:8px;
    display:none;
    z-index:1000;
}
</style>

</head>

<body>

<div class="dashboard-container">

<!-- SIDEBAR -->
<div class="sidebar">

<h2 class="logo">SkillSwap</h2>

<ul>
<li><a href="dashboard.php"><i class="fa-solid fa-chart-line"></i> Dashboard</a></li>
<li><a href="browse-skills.php"><i class="fa-solid fa-search"></i> Browse Skills</a></li>
<li><a href="php/add_skill.php"><i class="fa-solid fa-book"></i> My Skills</a></li>
<li><a href="profile.php"><i class="fa-solid fa-user"></i> Profile</a></li>
<li><a href="requests.php"><i class="fa-solid fa-envelope"></i> Requests</a></li>

<!-- CHAT -->
<li>
    <a href="chat.php">
        <i class="fa-solid fa-comments"></i> Chat
    </a>
</li>

<!-- NOTIFICATIONS PAGE -->
<li>
    <a href="notifications.php">
        <i class="fa-solid fa-bell"></i> Notifications
    </a>
</li>

<li><a href="php/logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
</ul>

</div>

<!-- MAIN CONTENT -->
<div class="main">

<!-- TOPBAR -->
<div class="topbar">

<h1>Dashboard</h1>

<div class="user-profile">
<a href="profile.php">
    <img src="uploads/<?php echo $profile_pic; ?>" alt="Profile">
</a>
<span>Welcome, <?php echo $username; ?></span>
</div>

</div>

<!-- STATS -->
<div class="stats">

<div class="stat-card">
<i class="fa-solid fa-code"></i>
<h3><?php echo $skills; ?></h3>
<p>Skills Added</p>
</div>

<div class="stat-card">
<i class="fa-solid fa-envelope"></i>
<h3><a href="requests.php"><?php echo $requests; ?></a></h3>
<p>Requests</p>
</div>

</div>

<!-- ADD SKILL -->
<div class="add-skill-box">

<h2>Add Your Skill</h2>

<form action="php/add_skill.php" method="POST">
<input type="text" name="skill_name" placeholder="Skill Name" required>
<textarea name="description" placeholder="Skill Description"></textarea>
<button class="btn" type="submit">Add Skill</button>
</form>

</div>

</div>

</div>

<!--POPUP -->
<div id="popup"></div>

<!--JAVASCRIPT -->
<script>
function checkNotification(){
    fetch('php/fetch_notification.php')
    .then(res => res.text())
    .then(data => {
        if(data.trim() !== ""){
            let popup = document.getElementById("popup");
            popup.innerText = data;
            popup.style.display = "block";

            setTimeout(() => {
                popup.style.display = "none";
            }, 4000);
        }
    });
}

// Run every 5 sec
setInterval(checkNotification, 5000);
</script>

</body>
</html>
