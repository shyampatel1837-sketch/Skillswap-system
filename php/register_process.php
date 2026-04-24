<?php

include("db.php");

$name = $_POST['name'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$skill = $_POST['skill'];

$sql = "INSERT INTO users(name,email,password,skill)
VALUES('$name','$email','$password','$skill')";

if(mysqli_query($conn,$sql)){
    echo "Registration Successful";
}else{
    echo "Error: " . mysqli_error($conn);
}

?>