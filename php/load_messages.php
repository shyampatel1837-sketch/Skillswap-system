<?php
session_start();
include("db.php");

$email = $_SESSION['user'];
$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id FROM users WHERE email='$email'"));
$user_id = $user['id'];

$receiver_id = $_GET['receiver_id'];

$query = mysqli_query($conn, "
SELECT * FROM messages 
WHERE (sender_id='$user_id' AND receiver_id='$receiver_id') 
OR (sender_id='$receiver_id' AND receiver_id='$user_id')
ORDER BY id ASC
");

while($row = mysqli_fetch_assoc($query)){

    $class = ($row['sender_id'] == $user_id) ? 'sent' : 'received';

    echo "<div class='msg $class'>";

    // ✅ TEXT MESSAGE
    if(!empty($row['message'])){
        echo "<p>".$row['message']."</p>";
    }

    // ✅ FILE MESSAGE
    if(!empty($row['file'])){
        $file = $row['file'];

        if(preg_match('/\.(jpg|jpeg|png|gif)$/i', $file)){
            echo "<img src='$file' width='150'>";
        }
        elseif(preg_match('/\.(mp4|webm)$/i', $file)){
            echo "<video width='200' controls src='$file'></video>";
        }
        elseif(preg_match('/\.(mp3|wav)$/i', $file)){
            echo "<audio controls src='$file'></audio>";
        }
    }

    echo "</div>";
}
?>