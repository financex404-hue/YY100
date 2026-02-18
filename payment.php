<?php
include "config.php";

$user = $_SESSION['user'];
$amount = $_POST['amount'];

$conn->query("UPDATE users SET coins = coins + $amount WHERE username='$user'");
$conn->query("INSERT INTO transactions(username,amount,status)
VALUES('$user','$amount','Success')");

header("Location: index.php");
?>