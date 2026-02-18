<?php
include "config.php";

$number = rand(1,10);
$conn->query("INSERT INTO draws(result_number) VALUES($number)");

echo "Live Result: ".$number;
?>