<?php

error_reporting(E_ALL & ~E_NOTICE);
set_time_limit(1200);
session_start();
if (isset($_SESSION['id'])) {
    $u_id = $_SESSION['id'];
    require_once 'includes/common_function.php';
 
    $retailer_details = getRetailerDataById($u_id);
    if ($retailer_details->temp_closed == 0) {
        print '<script>window.location="login.php";</script>';
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Portal Closed</title>
        <style>
            body {
                background-color: #f8f8f8;
                font-family: Arial, sans-serif;
                text-align: center;
                padding-top: 100px;
            }
            .message-box {
                background-color: #ffffff;
                border: 1px solid #cccccc;
                display: inline-block;
                padding: 30px 50px;
                box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
                border-radius: 12px;
            }
            h1 {
                color: #e53935;
            }
            p {
                font-size: 18px;
                color: #555555;
            }
        </style>
    </head>
    <body>
        <div class="message-box">
            <h1>Portal Closed</h1>
            <p>The temporary portal is currently closed.</p>
            <p>It will reopen after the next update.</p>
        </div>
    </body>
</html>
