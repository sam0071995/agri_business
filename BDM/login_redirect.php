<?php

include  './includes/common_function.php';

$username = $_POST['username'];

$get_zom_details = getZomDetailsByUsername($username);

$without_otp = $get_zom_details->without_otp;


if ($without_otp == 1) {
    echo "<script>location.href='login_without_otp.php?username=" . base64_encode($username) . "';</script>";
    exit;
} else {
    echo "<script>location.href='login_with_otp.php?username=" . base64_encode($username) . "';</script>";
    exit;
}
?>
 