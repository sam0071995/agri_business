<?php

session_start();
if (isset($_SERVER['HTTPS'])) {
    if ($_SERVER['HTTPS'] == 'on') {
        $url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        header('Location: ' . $url, true, 301);
        exit();
    }
}

if (!isset($_SESSION['username'])) {
    session_destroy();
    print '<script>window.location="login.php";</script>';
    exit;
}
if (isset($_SESSION['user_type'])) {
    if ($_SESSION['user_type'] != 'central_office') {
        session_destroy();
        print '<script>window.location="login.php";</script>';
        exit;
    }
} else {
    session_destroy();
    print '<script>window.location="login.php";</script>';
    exit;
}
$user_id = $_SESSION['id'];
$username = $_SESSION['username'];

include 'common_function.php';

$company_id_array = $parts = explode(',', $_SESSION['company_id']);   // ["apple","banana","cherry"]
//print_r($company_id_array);
//exit;
//if (!in_array('3', $company_id_array)) {
//    session_destroy();
//    print '<script>window.location="login.php";</script>';
//    exit;
//}


$update_block_date = $company_data->update_block_date;
?>