<?php

// error_reporting(0);
include './includes/common_function.php';
if (isset($_POST['submit']) && isset($_POST['username']) && isset($_POST['password'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $where = "`email` = '$username' AND password = '$password' AND status = '1'";
    $checkLogin = checkMyLogin($where);

    if ($checkLogin) {
        session_start();
        $_SESSION['id'] = $checkLogin->id;
        $_SESSION['logout_krvu'] = 2;
        $_SESSION['email'] = $checkLogin->email;
        $_SESSION['name'] = $checkLogin->name;
        $_SESSION['state_id'] = $checkLogin->state_id;
        $_SESSION['ec_id'] = $checkLogin->ec_id;
        $_SESSION['mobile'] = $checkLogin->mobile_no;
        $_SESSION['emp_code'] = $checkLogin->emp_code;
        $_SESSION['company_id'] = $checkLogin->company_id;
        header("location:index.php?success=1&menu=1");
    } else {
        header("location:login.php?error=1&failure=1");
    }
}
 