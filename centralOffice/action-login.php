<?php

include './includes/common_function.php';
if (isset($_POST['submit']) && isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $checkLogin = checkMyLogin($username, $password);
//    print_r($checkLogin);
//    exit;
    if ($checkLogin) {
        $time = time();
        session_start();
        $_SESSION['user_type'] = 'central_office';
        $_SESSION['username'] = $username;
        $_SESSION['id'] = $checkLogin->id;
        $_SESSION['admin_flag'] = $checkLogin->admin_flag;
        $_SESSION['company_id'] = $checkLogin->company_id;
        $_SESSION['time'] = $time;
        $update = update_sesstion($time, $username, $password);
        if ($update) {
            header("location:index.php?menu=1&success=1");
        } else {
            header("location:login.php?failure=1");
        }
    } else {
        header("location:login.php?failure=2");
    }
}
?>