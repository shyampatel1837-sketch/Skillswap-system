<?php
session_start();

include("db.php");

if(!isset($_SESSION['user'])){
    header("Location: ../login.php");
    exit();
}

$skill_name = $_POST['skill_name'];
$description = $_POST['description'];

$email = $_SESSION['user'];

$user_query = "SELECT id FROM users WHERE email='$email'";
$result = mysqli_query($conn,$user_query);
$user = mysqli_fetch_assoc($result);

$user_id = $user['id'];

$sql = "INSERT INTO skills(user_id,skill_name,description)
VALUES('$user_id','$skill_name','$description')";

if(mysqli_query($conn,$sql)){
    header("Location: ../dashboard.php");
}else{
    echo "Error: " . mysqli_error($conn);
}

?>