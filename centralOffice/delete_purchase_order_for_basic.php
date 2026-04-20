<?php

error_reporting(E_ERROR | E_PARSE);
require_once 'includes/session.php';
require_once 'includes/common_function.php';
$purchase_id = $_POST['purchase_id'];
$table_name = "purchase_order_basic_detail";
$table_name_main = "purchase_order_basic";
$where = "id='$purchase_id'";
$delete = delete($table_name, $where, $conn);
if ($delete) {
    $deleteMain = delete($table_name_main, $where, $conn);
}
if ($deleteMain) {
    echo '1';
} else {
    return FALSE;
}
?>