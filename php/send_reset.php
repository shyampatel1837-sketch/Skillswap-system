<?php
session_start();
include("db.php");

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST['email'];

    $result = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    if (mysqli_num_rows($result) == 0) {
        die("Email not found");
    }

    $token = bin2hex(random_bytes(50));
    $expire = date("Y-m-d H:i:s", strtotime('+1 hour'));

    mysqli_query($conn, "UPDATE users 
        SET reset_token='$token', token_expire='$expire' 
        WHERE email='$email'");

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;

        $mail->Username = 'sp6453897@gmail.com';
        $mail->Password = 'uxsuwwruiyxugypv';

        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('yourgmail@gmail.com', 'SkillSwap');
        $mail->addAddress($email);

        $mail->Subject = "Password Reset";
       $mail->Body = "Click to reset:
http://172.16.0.53/skillswap/reset-password.php?token=$token";

        $mail->send();

        echo "Reset link sent to your email";

    } catch (Exception $e) {
        echo "Error: " . $mail->ErrorInfo;
    }
}
?>