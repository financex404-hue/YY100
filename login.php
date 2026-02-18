<?php
include "config.php";

$username = $_POST['username'];
$phone = $_POST['phone'];

$check = $conn->query("SELECT * FROM users WHERE username='$username'");

if($check->num_rows == 0){
$conn->query("INSERT INTO users(username,phone) VALUES('$username','$phone')");
}

$_SESSION['user'] = $username;
header("Location: index.php");
?>