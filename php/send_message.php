<?php
session_start();
include("db.php");

$email = $_SESSION['user'];
$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id FROM users WHERE email='$email'"));
$sender_id = $user['id'];

$receiver_id = $_POST['receiver_id'];
$message = $_POST['message'] ?? '';

$file_path = "";

// ✅ FILE UPLOAD
if(isset($_FILES['file']) && $_FILES['file']['error'] == 0){
    $folder = "../uploads/chat/";
    
    if(!is_dir($folder)){
        mkdir($folder, 0777, true);
    }

    $file_name = time() . "_" . $_FILES['file']['name'];
    $file_tmp = $_FILES['file']['tmp_name'];

    $file_path = "uploads/chat/" . $file_name;

    move_uploaded_file($file_tmp, "../" . $file_path);
}

// ✅ SAVE MESSAGE
mysqli_query($conn, "INSERT INTO messages 
(sender_id, receiver_id, message, file) 
VALUES ('$sender_id','$receiver_id','$message','$file_path')");
?>