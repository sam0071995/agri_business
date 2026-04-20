<?php

include './includes/common_function.php';
error_reporting(0);
session_start();
$username = $_SESSION['username'];
update_logout($_SESSION['id']);
session_destroy();
print '<script>window.location="../index.php";</script>';
exit;
?>