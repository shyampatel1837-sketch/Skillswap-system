<?php
session_start();
include("db.php");

if(!isset($_SESSION['user'])){
    exit();
}

$email = $_SESSION['user'];

// Get user ID
$user_query = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");
$user = mysqli_fetch_assoc($user_query);
$user_id = $user['id'];

// Get latest unread notification
$query = mysqli_query($conn, "SELECT * FROM notifications 
WHERE user_id='$user_id' AND is_read=0 
ORDER BY id DESC LIMIT 1");

if(mysqli_num_rows($query) > 0){
    $notif = mysqli_fetch_assoc($query);

    // Mark as read
    mysqli_query($conn, "UPDATE notifications SET is_read=1 WHERE id='".$notif['id']."'");

    echo $notif['message'];
}
?>