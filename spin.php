<?php
include "config.php";

$user = $_SESSION['user'];

$data = $conn->query("SELECT * FROM users WHERE username='$user'");
$row = $data->fetch_assoc();

if($row['coins'] < 10){
echo "Not enough coins";
exit;
}

$coins = $row['coins'] - 10;
$win = rand(1,10) <= 3;

if($win){
$coins += 50;
$result = "Win +50";
}else{
$result = "Lose -10";
}

$conn->query("UPDATE users SET coins=$coins WHERE username='$user'");
$conn->query("INSERT INTO history(username,result) VALUES('$user','$result')");

echo $result;
?>