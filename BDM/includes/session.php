<?php

error_reporting(E_ALL & ~E_NOTICE);
set_time_limit(1200);
session_start();
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

if ($_SESSION['logout_krvu'] != 2) {
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

$bdm_detail = getBdmDetailById($u_id);

$company_id_in = $bdm_detail->company_id;
/* $menu_by_o_id = getAllAssignMenuByOemId($u_id);
  $menu_by_o_id_1 = rtrim($menu_by_o_id, ',');
  $menu_by_o_id_2 = explode(',',$menu_by_o_id);

  if (!in_array($_GET['menu'], $menu_by_o_id_2) && $_GET['menu'] !== '101' ) {
  print '<script>window.location="error-404.php?menu=1";</script>';
  exit;
  } */
?>