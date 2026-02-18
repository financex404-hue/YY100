<?php
session_start();
$otp = rand(1000,9999);
$_SESSION['otp'] = $otp;
echo "Demo OTP: ".$otp;
?>