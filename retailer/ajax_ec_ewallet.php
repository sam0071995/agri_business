<?php

session_start();
require_once 'includes/common_function.php';
require_once 'includes/db_guj.class';
require_once 'includes/db_oem.class';
$conn_guj = new db_guj();
$conn_oem = new db_oem();

if (isset($_POST['ajax'])) {
    $ajax = $_POST['ajax'];
    
    if ($ajax == 'approve' && isset($_POST['id'])) {
        if (!empty($_POST['id'])) {
            $id = $_POST['id'];

            $sbh_approve_status = 1;
            $sbh_app_date = date('Y-m-d');
            $sbh_app_datetime = date('Y-m-d h:i:s');
            $table = 'ec_passbook';
            $data = array('sbh_approve_status'=>$sbh_approve_status,
                'sbh_app_date'=>$sbh_app_date,
                'sbh_app_datetime'=>$sbh_app_datetime);
            $where = " id = $id ";
            $up = update($table, $data, $where);
            if($up){
                echo 1; exit;        
            }
        } else {
            echo '0';
        }
        exit;
    }

    if ($ajax == 'reject' && isset($_POST['id'])) {
        if (!empty($_POST['id'])) {
            $id = $_POST['id'];

            $sbh_approve_status = 2;
            $sbh_app_date = date('Y-m-d');
            $sbh_app_datetime = date('Y-m-d h:i:s');
            $table = 'ec_passbook';
            $data = array('sbh_approve_status'=>$sbh_approve_status,
                'sbh_app_date'=>$sbh_app_date,
                'sbh_app_datetime'=>$sbh_app_datetime);
            $where = " id = $id ";
            $up = update($table, $data, $where);
            if($up){
                echo 1; exit;        
            }
        } else {
            echo '0';
        }
        exit;
    }
    
}

?>