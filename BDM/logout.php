<?php

include './includes/common_function.php';
error_reporting(0);
session_start();
$username = $_SESSION['username'];
// $get_otp_interval = getOtpIntervalByUsername($username);
// if ($get_otp_interval == 0) {
//     update_logout($username);
// }
session_destroy();
print '<script>window.location="../index.php";</script>';
exit;
?>