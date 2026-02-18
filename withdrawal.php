<?php
include "config.php";

$user = $_SESSION['user'];
$upi = $_POST['upi'];
$amount = $_POST['amount'];

$conn->query("INSERT INTO withdrawals(username,upi_id,amount)
VALUES('$user','$upi','$amount')");

header("Location: index.php");
?>