<?php
session_start();
include("php/db.php");

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['user'];


$user_query = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");
$user = mysqli_fetch_assoc($user_query);
$user_id = $user['id'];

// Fetch incoming requests
$query = "
SELECT requests.*, users.name, skills.skill_name 
FROM requests 
JOIN users ON requests.sender_id = users.id
JOIN skills ON requests.skill_id = skills.id
WHERE requests.receiver_id = '$user_id'
ORDER BY requests.id DESC
";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
<title>Requests | SkillSwap</title>
<link rel="stylesheet" href="css/style.css">
</head>

<body>

<div class="profile-page">

<h2>Incoming Requests</h2>

<div class="skills-grid">

<?php

if(mysqli_num_rows($result) > 0){

while($row = mysqli_fetch_assoc($result)){

echo "

<div class='skill-card'>

<h3>".$row['name']."</h3>

<p>Requested: <b>".$row['skill_name']."</b></p>

<p>Status: <b>".$row['status']."</b></p>
";

// Show Accept/Reject only if pending
if($row['status'] == 'pending'){
    echo "
    <form method='POST' action='php/update_request.php'>
        <input type='hidden' name='request_id' value='".$row['id']."'>
        <button name='action' value='accepted' class='accept-btn'>Accept</button>
        <button name='action' value='rejected' class='reject-btn'>Reject</button>
    </form>
    ";
}

if($row['status'] == 'accepted'){
    echo "
    <a href='chat.php?user_id=".$row['sender_id']."' class='btn'>
        Start Chat
    </a>
    ";
}

echo "</div>";
}

}else{
    echo "<p>No requests yet.</p>";
}
?>

</div>

</div>

</body>
</html>
