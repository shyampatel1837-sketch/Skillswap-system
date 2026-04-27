<?php
session_start();
include("php/db.php");

if(!isset($_GET['token'])){
    die("Invalid token");
}

$token = $_GET['token'];


$result = mysqli_query($conn, "SELECT * FROM users WHERE reset_token='$token'");
$user = mysqli_fetch_assoc($result);

if(!$user){
    echo "<h2 style='text-align:center;color:red;'>Token expired. Please request again.</h2>";
exit();
}

// CHECK EXPIRY
if(strtotime($user['token_expire']) < time()){
    echo "<h2 style='text-align:center;color:red;'>Token expired. Please request again.</h2>";
exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <style>
        body{
            display:flex;
            justify-content:center;
            align-items:center;
            height:100vh;
            background:#f3f4f6;
            font-family:Arial;
        }
        .box{
            background:white;
            padding:30px;
            border-radius:10px;
            box-shadow:0 0 10px rgba(0,0,0,0.1);
            text-align:center;
        }
        input{
            padding:10px;
            margin:10px;
            width:100%;
        }
        button{
            padding:10px;
            width:100%;
            background:#4f46e5;
            color:white;
            border:none;
            border-radius:5px;
        }
    </style>
</head>
<body>

<div class="box">
    <h2>Reset Password</h2>

    <form method="POST" action="php/update-password.php">
        <input type="hidden" name="token" value="<?php echo $token; ?>">
        <input type="password" name="password" placeholder="Enter new password" required>
        <button type="submit">Update Password</button>
    </form>
</div>

</body>
</html>
