<?php

session_start();
error_reporting(0);
require_once 'includes/common_function.php';

date_default_timezone_set('Asia/Kolkata');


extract($_POST);

$retailer_id = $_SESSION['id'];
$user_id = $_SESSION['user_id'];
$company_id = $_SESSION['company_id'];
$bdm_id = $_SESSION['bdm_id'];

if (isset($_POST['invoice_no'])) {
    if (empty($_POST['invoice_no'])) {
        echo '1';
        exit;
    }
    if (empty($_POST['invoice_remarks'])) {
        echo '2';
        exit;
    }
    if (!isset($_FILES['invoice_upload'])) {
        echo '3';
        exit;
    }
    // Specify the directory where the file will be moved
    $uploadDirectory = 'invoiceData/';
    $file = $_FILES['invoice_upload'];
    $uploadPath = $uploadDirectory . time() . "_" . basename($file['name']);

    if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
        $po_no = $_POST['po_no'];
        $invoice_no = $_POST['invoice_no'];
        $invoice_remarks = $_POST['invoice_remarks'];
        $data_invoice = array();
        $data_invoice['invoice_flag'] = 1;
        $data_invoice['upload_invoice_no'] = $invoice_no;
        $data_invoice['invoice_upload'] = $uploadPath;
        $data_invoice['invoice_remarks'] = $invoice_remarks;
        $data_invoice['invoice_upload_date'] = date("Y-m-d H:i:s");
        $data_invoice_where = "po_no='$po_no' and invoice_flag='0'";
        $update = update("purchase_order_basic", $data_invoice, $data_invoice_where);
        if ($update) {
            echo '6';
            exit;
        } else {
            echo '5';
            exit;
        }
    } else {
        echo '4';
        exit;
    }
}