<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include("php/db.php");

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['user'];

// Get user data
$query = "SELECT * FROM users WHERE email='$email'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

// Update profile
if(isset($_POST['update'])){

    $name = $_POST['name'];
    $new_email = $_POST['email'];

    // IMAGE UPLOAD
    $image_name = isset($user['profile_image']) ? $user['profile_image'] : 'default.png';

    if(!empty($_FILES['image']['name'])){
        $image_name = time() . "_" . $_FILES['image']['name'];
        $target = "uploads/" . $image_name;

        move_uploaded_file($_FILES['image']['tmp_name'], $target);
    }

    $update_query = "UPDATE users 
        SET name='$name', email='$new_email', profile_image='$image_name'
        WHERE email='$email'";

    if(mysqli_query($conn, $update_query)){
        $_SESSION['user'] = $new_email; // update session
        header("Location: profile.php");
    } else {
        echo "Update Failed";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Profile</title>
<link rel="stylesheet" href="css/style.css">
</head>

<body>

<div class="edit-container">

<h2>Edit Profile</h2>

<form method="POST" enctype="multipart/form-data">

<input type="text" name="name" value="<?php echo $user['name']; ?>" required>

<input type="email" name="email" value="<?php echo $user['email']; ?>" required>

<input type="file" name="image">

<button type="submit" name="update">Update Profile</button>

</form>

</div>

</body>
</html>