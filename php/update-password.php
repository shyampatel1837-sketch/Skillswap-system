<?php
include("db.php");

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $token = $_POST['token'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $result = mysqli_query($conn, "SELECT * FROM users WHERE reset_token='$token'");
    $user = mysqli_fetch_assoc($result);

    if(!$user){
        echo "<h2 style='text-align:center;color:red;'>Invalid token</h2>";
        exit();
    }

    // ✅ CHECK EXPIRY
    if(strtotime($user['token_expire']) < time()){
        echo "<h2 style='text-align:center;color:red;'>Token expired. Please request again.</h2>";
        exit();
    }

    // ✅ Update password
    mysqli_query($conn, "UPDATE users 
        SET password='$password', reset_token=NULL, token_expire=NULL 
        WHERE reset_token='$token'");

    echo "<h2 style='text-align:center;color:green;'>Password updated successfully</h2>
          <p style='text-align:center;'><a href='../login.php'>Login</a></p>";
}
?>