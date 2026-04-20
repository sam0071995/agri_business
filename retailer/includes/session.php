<?php

error_reporting(E_ALL & ~E_NOTICE);
set_time_limit(1200);
session_start();
$retailer_id = $_SESSION['id'];


if (isset($_SERVER['HTTPS'])) {
    if ($_SERVER['HTTPS'] == 'on') {
        $url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        header('Location: ' . $url, true, 301);
        exit();
    }
}

if (!isset($_SESSION['email'])) {
    session_destroy();
    print '<script>window.location="login.php";</script>';
    exit;
}
if (!isset($_GET['menu'])) {
    print '<script>window.location="error-404.php?menu=1";</script>';
    exit;
}
if (!isset($_SESSION['logout_krvu'])) {
    print '<script>window.location="logout.php?menu=1";</script>';
    exit;
}
if (!isset($_SESSION['user_type'])) {
    print '<script>window.location="logout.php?menu=1";</script>';
    exit;
} else {
    if ($_SESSION['user_type'] != "retailer") {
        print '<script>window.location="logout.php?menu=1";</script>';
        exit;
    }
}

if ($_SESSION['logout_krvu'] != 2) {
    print '<script>window.location="logout.php?menu=1";</script>';
    exit;
}

if (!isset($_SESSION['timeout'])) {
    print '<script>window.location="logout.php?menu=1";</script>';
    exit;
}

date_default_timezone_set('Asia/Kolkata');
$aajnidate = date('d');
$aajnotime = date('H');



$state_id = $_SESSION['state_id'];
$u_id = $_SESSION['id'];
$menu = $_GET['menu'];
require_once 'common_function.php';

$inactive = 600000;
$session_life = time() - $_SESSION['timeout'];
if ($session_life > $inactive) {
    session_destroy();
    print '<script>window.location="logout.php?menu=1";</script>';
    exit;
}
$session_timeout = time();
$_SESSION['timeout'] = $session_timeout;
update_sesstion($session_timeout, $u_id);
$retailer_details = getRetailerDataById($u_id);
if (!isset($retailer_details->status)) {
    session_destroy();
    print '<script>window.location="login.php";</script>';
    exit;
}
if ($retailer_details->temp_closed == 1) {
    if ($_GET['menu'] != '449') {
        print '<script>window.location="physical_audit_record_entry.php?menu=449";</script>';
        exit;
    }
//    if ($_GET['menu'] != '449' || $_GET['menu'] != '450') {
//        print '<script>window.location="portal_closed.php?menu=449";</script>';
//        exit;
//    }
}
if ($retailer_details->status == 0) {
    session_destroy();
    print '<script>window.location="login.php";</script>';
    exit;
}

//if (isset($retailer_details->company_id) && $retailer_details->company_id != '3') {
//    session_destroy();
//    print '<script>window.location="login.php";</script>';
//    exit;
//}

/* $menu_by_o_id = getAllAssignMenuByOemId($u_id);
  $menu_by_o_id_1 = rtrim($menu_by_o_id, ',');
  $menu_by_o_id_2 = explode(',',$menu_by_o_id);

  if (!in_array($_GET['menu'], $menu_by_o_id_2) && $_GET['menu'] !== '101' ) {
  print '<script>window.location="error-404.php?menu=1";</script>';
  exit;
  } */
?>