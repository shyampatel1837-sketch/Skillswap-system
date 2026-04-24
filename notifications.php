<?php
session_start();
include("php/db.php");

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$email = $_SESSION['user'];
$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id FROM users WHERE email='$email'"));
$user_id = $user['id'];

// Fetch all notifications
$query = mysqli_query($conn, "SELECT * FROM notifications 
WHERE user_id='$user_id' ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
<title>Notifications | SkillSwap</title>
<link rel="stylesheet" href="css/style.css">

<style>
.notif-box{
    max-width:600px;
    margin:40px auto;
    background:white;
    padding:20px;
    border-radius:10px;
}

.notif-item{
    padding:10px;
    border-bottom:1px solid #ddd;
}

.unread{
    background:#eef5ff;
    font-weight:bold;
}
</style>

</head>

<body>

<div class="notif-box">

<h2>🔔 Notifications</h2>

<?php
if(mysqli_num_rows($query) > 0){
    while($n = mysqli_fetch_assoc($query)){
        $class = $n['is_read'] == 0 ? 'notif-item unread' : 'notif-item';
        echo "<div class='$class'>".$n['message']."</div>";
    }
}else{
    echo "<p>No notifications</p>";
}
?>


<?php
mysqli_query($conn, "UPDATE notifications SET is_read=1 WHERE user_id='$user_id'");
?>

</div>

</body>
</html>