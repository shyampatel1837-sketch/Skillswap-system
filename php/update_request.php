<?php
session_start();
include("db.php");

$request_id = $_POST['request_id'];
$action = $_POST['action'];

$query = "UPDATE requests SET status='$action' WHERE id='$request_id'";
mysqli_query($conn, $query);

header("Location: ../requests.php");
?>