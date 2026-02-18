CREATE DATABASE lottery_db;
USE lottery_db;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50),
    coins INT DEFAULT 100,
    phone VARCHAR(15)
);

CREATE TABLE history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50),
    result VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50),
    amount INT,
    status VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE withdrawals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50),
    upi_id VARCHAR(100),
    amount INT,
    status VARCHAR(20) DEFAULT 'Pending'
);

CREATE TABLE draws (
    id INT AUTO_INCREMENT PRIMARY KEY,
    result_number INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
<?php
$conn = new mysqli("localhost","root","","lottery_db");
if($conn->connect_error){
    die("Database Error");
}
session_start();
?>
<?php include "config.php"; ?>
<!DOCTYPE html>
<html>
<head>
<title>Lucky Lottery Pro</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>

<h1>🎰 Lucky Lottery Pro</h1>

<?php if(!isset($_SESSION['user'])){ ?>

<form action="login.php" method="POST">
<input type="text" name="username" placeholder="Enter Username" required>
<input type="text" name="phone" placeholder="Phone Number" required>
<button type="submit">Login / Register</button>
</form>

<?php } else { ?>

<h3>Welcome <?=$_SESSION['user']?></h3>

<?php
$user = $conn->query("SELECT * FROM users WHERE username='".$_SESSION['user']."'");
$row = $user->fetch_assoc();
?>

<p>Coins: <?=$row['coins']?></p>

<button onclick="spin()">Spin (-10 Coins)</button>
<button onclick="dailyDraw()">Live Draw</button>

<h3>Add Money</h3>
<form action="payment.php" method="POST">
<input type="number" name="amount" placeholder="Amount">
<button type="submit">Add Balance</button>
</form>

<h3>Withdraw</h3>
<form action="withdraw.php" method="POST">
<input type="text" name="upi" placeholder="UPI ID">
<input type="number" name="amount" placeholder="Amount">
<button type="submit">Request Withdraw</button>
</form>

<a href="logout.php">Logout</a>

<div id="result"></div>

<?php } ?>

<script src="assets/script.js"></script>
</body>
</html>
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
<?php
include "config.php";

$user = $_SESSION['user'];
$amount = $_POST['amount'];

$conn->query("UPDATE users SET coins = coins + $amount WHERE username='$user'");
$conn->query("INSERT INTO transactions(username,amount,status)
VALUES('$user','$amount','Success')");

header("Location: index.php");
?>
<?php
include "config.php";

$number = rand(1,10);
$conn->query("INSERT INTO draws(result_number) VALUES($number)");

echo "Live Result: ".$number;
<?php
session_start();
$otp = rand(1000,9999);
$_SESSION['otp'] = $otp;
echo "Demo OTP: ".$otp;
?>

?>
<?php include "config.php"; ?>

<h2>Admin Panel</h2>

<h3>Users</h3>
<?php
$res = $conn->query("SELECT * FROM users");
while($row=$res->fetch_assoc()){
echo $row['username']." - Coins: ".$row['coins']."<br>";
}
?>

<h3>Withdraw Requests</h3>
<?php
$res = $conn->query("SELECT * FROM withdrawals WHERE status='Pending'");
while($row=$res->fetch_assoc()){
echo $row['username']." - ".$row['amount']." - ".$row['upi_id']."<br>";
}
?>6<?php
session_start();
session_destroy();
header("Location: index.php");
?>
