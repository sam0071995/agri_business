<?php

include './includes/common_function.php';
if (isset($_POST['register'])) {
    $table_name = 'user';
    $data['email'] = $_POST['email'];
    $data['mobile'] = $_POST['mobile'];
    $data['username'] = $_POST['username'];
    $data['password'] = $_POST['password'];
    $data['added_date'] = date('Y-m-d h:i:s');
    $data['date'] = date('Y-m-d');
    $data['status'] = 1;
    $insert = insert($table_name, $data);
    if ($insert) {
        header("location:login.php?success=1");
    } else {
        header("location:login.php?failure=1");
    }
}
?>