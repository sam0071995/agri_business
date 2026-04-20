<!DOCTYPE html>
<html lang="en">
<?php

error_reporting(0);
require_once 'includes/header.php';
require_once 'includes/common_function.php';
?>

<body class="login-layout">
    <div class="main-container">
        <div class="main-content">
            <div class="row">
                <div class="col-sm-10 col-sm-offset-1">
                    <div class="login-container">
                        <div class="center">
                            <img class="nav-user-photo" src="assets/images/avatars/fta_main.png" alt="HSRP Photo" height="80" width="150" style="margin-top: 20px;" />
                            <h1>
                                <span class="red">FTA HSRP Solutions Pvt Ltd</span>
                            </h1>
                            <h4 class="blue" id="id-company-text">&copy; Retail Seller</h4>
                        </div>
                        <div class="space-6"></div>
                        <div class="position-relative">
                            <div id="login-box" class="login-box visible widget-box no-border">
                                <div class="widget-body">
                                    <div class="widget-main">
                                        <h6 class="header otp-msg text-danger" style="display: none;">Re-Otp Sent To Your Number.. </h6>
                                        <?php
                                        $username = base64_decode($_GET['username']);

                                        $get_zom_details = getZomDetailsByUsername($username);
                                        $mobile = $get_zom_details->mobile_no;
                                        $without_otp = $get_zom_details->without_otp;

                                        $get_otp_interval = getOtpIntervalByUsername($username);

                                        if ($get_otp_interval == 0) {
                                            //generate otp
                                            $otp = rand(100000, 999999);

                                            //for update otp
                                            $upd_arr = array();
                                            $upd_arr['otp_num'] = $otp;
                                            $upd_arr['otp_time'] = date('Y-m-d H:i:s');
                                            $where = "email = '$username' and mobile_no = '$mobile'";
                                            $update = update('zonal_master', $upd_arr, $where);

                                            if ($update) {
                                                $message = "Your Otp is : " . $otp;

                                                $send_otp_1 = api_send_sms_ops($mobile, $message);
                                                echo '<h6 class="header text-danger" >New Otp Sent To Your Number.. </h6>';
                                            } else {
                                                echo '<h6 class="header text-danger" >Please Try Again.. </h6>';
                                            }
                                        } else {
                                            echo '<h6 class="header text-danger" >Please Enter Your Existing Otp.. </h6>';
                                        }
                                        ?>
                                        <h4 class="header blue lighter bigger">
                                            <i class="ace-icon fa fa-coffee green"></i>
                                            Please Enter Login Details
                                        </h4>
                                        <div class="space-6"></div>
                                        <form action="action-login.php" method="POST">
                                            <input type="hidden" class="form-control" name="otp_req" id="otp_req" value="<?php echo $without_otp; ?>" />
                                            <fieldset>
                                                <label class="block clearfix">
                                                    <span class="block input-icon input-icon-right">
                                                        <input type="text" class="form-control" required="required" name="username" id="username" placeholder="Email" value="<?php echo $username; ?>" />
                                                        <i class="ace-icon fa fa-user"></i>
                                                    </span>
                                                </label>
                                                <label class="block clearfix">
                                                    <span class="block input-icon input-icon-right">
                                                        <input type="text" class="form-control" required="required" name="mobile" id="mobile" placeholder="Mobile Number" value="<?php echo $mobile; ?>" />
                                                        <i class="ace-icon fa fa-phone"></i>
                                                    </span>
                                                </label>
                                                <label class="block clearfix">
                                                    <span class="block input-icon input-icon-right">
                                                        <input type="password" class="form-control" required="required" name="password" id="password" placeholder="Enter Your Password" value="" />
                                                        <i class="ace-icon fa fa-lock"></i>
                                                    </span>
                                                </label>
                                                <div class=" clearfix" id="enter_otp">
                                                    <span class="block input-icon input-icon-right">
                                                        <input type="text" class="form-control" required="required" name="otp_number" id="otp_number" placeholder="Otp Number" />
                                                        <i class="ace-icon fa fa-mobile"></i>
                                                    </span>
                                                </div>
                                                <div class="space"></div>
                                                <div class="col-sm-12">
                                                    <div class="col-sm-3" id="otp_btn">
                                                        <button type="submit" name="submit" class=" pull-right btn btn-sm btn-primary" id="send_otp_1">
                                                            <i class="ace-icon fa fa-key"></i>
                                                            <span class="bigger-110">Login</span>
                                                        </button>
                                                    </div>
                                                    <div class="col-sm-1"></div>
                                                    <div class=" col-sm-3" id="refresh">
                                                        <a href="login.php" class=" pull-right btn btn-sm btn-warning" id="refresh_1">
                                                            <i class="ace-icon fa fa-refresh"></i>
                                                            <span class="bigger-110">Refresh</span>
                                                        </a>
                                                    </div>
                                                    <div class="col-sm-1"></div>
                                                    <div class=" col-sm-3" id="ref">
                                                        <a href="" class=" pull-right btn btn-sm btn-success" id="re_send_otp">
                                                            <i class="ace-icon fa fa-refresh"></i>
                                                            <span class="bigger-110">Re Otp</span>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="space-4"></div>
                                            </fieldset>
                                        </form>
                                        <div class="space-6"></div>
                                    </div>

                                    <div class="toolbar clearfix">
                                        <div>
                                            <a href="#" data-target="#forgot-box" class="forgot-password-link"></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="navbar-fixed-top align-right">
                            <br />
                            &nbsp;
                            <a id="btn-login-dark" href="#">Dark</a>
                            &nbsp;
                            <span class="blue">/</span>
                            &nbsp;
                            <a id="btn-login-blur" href="#">Blur</a>
                            &nbsp;
                            <span class="blue">/</span>
                            &nbsp;
                            <a id="btn-login-light" href="#">Light</a>
                            &nbsp; &nbsp; &nbsp;
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/js/jquery-2.1.4.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {

            $(document).on('click', '.toolbar a[data-target]', function(e) {
                e.preventDefault();
                var target = $(this).data('target');
                $('.widget-box.visible').removeClass('visible'); //hide others
                $(target).addClass('visible'); //show target
            });

            $('#re_send_otp').click(function(e) {
                //                    alert('hii');
                e.preventDefault();
                var username = $('#username').val();
                var mobile_no = $('#mobile').val();

                $.ajax({
                    url: 'ajax_js.php',
                    type: 'post',
                    data: {
                        type: 'resend_otp',
                        username: username,
                        mobile_no: mobile_no
                    },
                    success: function(data) {
                        alert(data);
                        //                            if (data == 1) {
                        //                                $('.otp-msg').css('display', 'block');
                        //                            }
                    }
                });
            });


        });

        jQuery(function($) {
            $('#btn-login-dark').on('click', function(e) {
                $('body').attr('class', 'login-layout');
                $('#id-text2').attr('class', 'white');
                $('#id-company-text').attr('class', 'blue');

                e.preventDefault();
            });
            $('#btn-login-light').on('click', function(e) {
                $('body').attr('class', 'login-layout light-login');
                $('#id-text2').attr('class', 'grey');
                $('#id-company-text').attr('class', 'blue');

                e.preventDefault();
            });
            $('#btn-login-blur').on('click', function(e) {
                $('body').attr('class', 'login-layout blur-login');
                $('#id-text2').attr('class', 'white');
                $('#id-company-text').attr('class', 'light-blue');
                e.preventDefault();
            });
        });
    </script>
</body>

</html>