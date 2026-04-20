<?php

session_start();
if (!isset($_SESSION['username'])) {
    session_destroy();
    print '<script>window.location="login.php";</script>';
    exit;
}
require_once 'common_function.php';
?>