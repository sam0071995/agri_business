<?php

// error_reporting(0);
include './includes/common_function.php';
if (isset($_POST['submit']) && isset($_POST['username']) && isset($_POST['password'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $where = "`email` = '$username' AND password = '$password' AND status = '1'";
//    $where = "`email` = '$username' AND password = '$password' AND status = '1' and company_id='3'";

    $checkLogin = checkMyLogin($where);

    if ($checkLogin) {
        session_start();
        $_SESSION['id'] = $checkLogin->id;
        $_SESSION['zone_id'] = $checkLogin->new_zone_id;
        $_SESSION['logout_krvu'] = 2;
        $_SESSION['user_type'] = "retailer";
        $_SESSION['email'] = $checkLogin->email;
        $_SESSION['name'] = $checkLogin->name;
        $_SESSION['state_id'] = $checkLogin->state_id;
        $_SESSION['company_id'] = $checkLogin->company_id;
        $_SESSION['user_id'] = $checkLogin->user_id;
        $_SESSION['bdm_id'] = $checkLogin->bdm_id;

        $session_timeout = time();
        $_SESSION['timeout'] = $session_timeout;
        update_sesstion($session_timeout, $checkLogin->id);
        header("location:index.php?success=1&menu=1");
    } else {
        header("location:login.php?error=1&failure=1");
    }
}
 