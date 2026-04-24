<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include("db.php");

if(!isset($_SESSION['user'])){
    header("Location: ../login.php");
    exit();
}

if($_SERVER["REQUEST_METHOD"] == "POST"){

    if(!isset($_POST['skill_id']) || !isset($_POST['receiver_id'])){
        die("Invalid Request Data");
    }

    $skill_id = (int)$_POST['skill_id'];
    $receiver_id = (int)$_POST['receiver_id'];

    $email = $_SESSION['user'];

    // Get sender id + name
    $user_query = mysqli_query($conn, "SELECT id, name FROM users WHERE email='$email'");
    $user = mysqli_fetch_assoc($user_query);

    if(!$user){
        die("User not found");
    }

    $sender_id = (int)$user['id'];
    $sender_name = $user['name'];

    // ❌ Prevent self request
    if($sender_id == $receiver_id){
        die("You cannot request your own skill");
    }

    // ❌ Prevent duplicate request
    $check = mysqli_query($conn, "SELECT * FROM requests 
        WHERE sender_id='$sender_id' AND skill_id='$skill_id'");

    if(mysqli_num_rows($check) == 0){

        // ✅ INSERT REQUEST
        $query = "INSERT INTO requests (sender_id, receiver_id, skill_id) 
                  VALUES ('$sender_id','$receiver_id','$skill_id')";

        if(mysqli_query($conn, $query)){

            // ✅ ADD NOTIFICATION HERE (THIS IS WHAT YOU WERE ASKING)
            $message = "$sender_name sent you a skill request";

            mysqli_query($conn, "INSERT INTO notifications (user_id, message) 
                                VALUES ('$receiver_id', '$message')");

            header("Location: ../profile.php?id=".$receiver_id);
            exit();

        } else {
            die("Insert Error: " . mysqli_error($conn));
        }

    } else {
        echo "Request already sent";
    }

} else {
    echo "Invalid Request Method";
}
?>