<!DOCTYPE html>
<html lang="en">
    <?php require_once 'includes/header.php'; ?>

    <body class="login-layout">
        <div class="main-container">
            <div class="main-content">
                <div class="row">
                    <div class="col-sm-10 col-sm-offset-1">
                        <div class="login-container">
                            <div class="center">
                                <h4 class="blue" id="id-company-text">&copy; Distributer</h4>
                            </div>
                            <div class="space-6"></div>
                            <div class="position-relative">
                                <div id="login-box" class="login-box visible widget-box no-border">
                                    <div class="widget-body">
                                        <div class="widget-main">
                                            <?php
                                            if (isset($_GET['error'])) {
                                                if ($_GET['error'] == 1) {
                                                    $msg = '<label style="color:red;">Please Enter Correct Password/OTP.</label>';
                                                }
                                                if ($_GET['error'] == 2) {
                                                    $msg = '<label style="color:red;">Please Register Your Mobile Number.</label>';
                                                }
                                                ?>
                                                <h6 class="header" id="err_msg">
                                                    <?php echo $msg; ?>
                                                </h6>
                                            <?php } ?>
                                            <h4 class="header blue lighter bigger">
                                                <i class="ace-icon fa fa-coffee green"></i>
                                                Please Enter Login Details
                                            </h4>
                                            <div class="space-6"></div>
                                            <form action="action-login.php" method="POST">
                                                <fieldset>
                                                    <label class="block clearfix">
                                                        <span class="block input-icon input-icon-right">
                                                            <input type="text" class="form-control" required="required" name="username" id="username" placeholder="Email" />
                                                            <i class="ace-icon fa fa-user"></i>
                                                        </span>
                                                    </label>
                                                    <label class="block clearfix">
                                                        <span class="block input-icon input-icon-right">
                                                            <input type="password" class="form-control" required="required" name="password" id="password" placeholder="Password" />
                                                            <i class="ace-icon fa fa-user"></i>
                                                        </span>
                                                    </label>
                                                    <div class="space"></div>
                                                    <div class="clearfix" id="otp_btn">
                                                        <button type="submit" name="submit" class="width-35 pull-right btn btn-sm btn-primary" id="send_otp_1">
                                                            <i class="ace-icon fa fa-arrow-right"></i>
                                                            <span class="bigger-110">Login</span>
                                                        </button>
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
//            $(document).ready(function () {
//                $('#send_otp_1_').click(function () {
//                    var username = $('#username').val();
//                    var mobile = $('#mobile').val();
//
//                    $.ajax({
//                        url: 'get_ajax_data.php',
//                        method: 'post',
//                        data: {request_type: 'send_otp_ops', mobile: mobile, username: username},
//                        success: function (result) {
//                            alert(result);
//                            if (result == 1) {
//                                $('#otp_btn').css('display', 'none');
//                                $('#otp_number').css('display', 'block');
//                                $('#enter_otp').css('display', 'block');
//                            } else {
//                                $('#otp_btn').css('display', 'none');
//                                $('#refresh').css('display', 'block');
//                                $('#enter_otp').css('display', 'none');
//                                $('#err_msg').css('display', 'block').html('Please Try Again');
//
//                            }
//                        }
//                    });
//                });
//
//            });

        </script>
        <script type="text/javascript">
            if ('ontouchstart' in document.documentElement)
                document.write("<script src='assets/js/jquery.mobile.custom.min.js'>" + "<" + "/script>");
        </script>
        <script type="text/javascript">
            $(document).ready(function () {

                $(document).on('click', '.toolbar a[data-target]', function (e) {
                    e.preventDefault();
                    var target = $(this).data('target');
                    $('.widget-box.visible').removeClass('visible');//hide others
                    $(target).addClass('visible');//show target
                });


            });

            jQuery(function ($) {
                $('#btn-login-dark').on('click', function (e) {
                    $('body').attr('class', 'login-layout');
                    $('#id-text2').attr('class', 'white');
                    $('#id-company-text').attr('class', 'blue');

                    e.preventDefault();
                });
                $('#btn-login-light').on('click', function (e) {
                    $('body').attr('class', 'login-layout light-login');
                    $('#id-text2').attr('class', 'grey');
                    $('#id-company-text').attr('class', 'blue');

                    e.preventDefault();
                });
                $('#btn-login-blur').on('click', function (e) {
                    $('body').attr('class', 'login-layout blur-login');
                    $('#id-text2').attr('class', 'white');
                    $('#id-company-text').attr('class', 'light-blue');
                    e.preventDefault();
                });
            });
        </script>
    </body>
</html>
