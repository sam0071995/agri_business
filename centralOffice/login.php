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
                                <h4 class="blue" id="id-company-text">&copy; centralOffice</h4>
                            </div>
                            <div class="space-6"></div>
                            <div class="position-relative">
                                <div id="login-box" class="login-box visible widget-box no-border">
                                    <div class="widget-body">
                                        <div class="widget-main">
                                            <?php if (isset($_GET['success'])) { ?>
                                                <h6 class="header green">
                                                    <i class="ace-icon fa fa-user green"></i>
                                                    Thanks For Registration. We have sent Loin Detail on your Email Acoount.
                                                </h6>
                                            <?php } ?>
                                            <h4 class="header blue lighter bigger">
                                                <i class="ace-icon fa fa-coffee green"></i>
                                                Please Enter Login Details
                                            </h4>
                                            <div class="space-6"></div>
                                            <form action="action-login.php?menu=1" method="POST">
                                                <fieldset>
                                                    <label class="block clearfix">
                                                        <span class="block input-icon input-icon-right">
                                                            <input type="text" class="form-control" required="required" name="username" placeholder="Username" />
                                                            <i class="ace-icon fa fa-user"></i>
                                                        </span>
                                                    </label>
                                                    <label class="block clearfix">
                                                        <span class="block input-icon input-icon-right">
                                                            <input type="password" class="form-control" required="required" name="password" placeholder="Password" />
                                                            <i class="ace-icon fa fa-lock"></i>
                                                        </span>
                                                    </label>
                                                    <div class="space"></div>
                                                    <div class="clearfix">
                                                        <button type="submit" name="submit" class="width-35 pull-right btn btn-sm btn-primary">
                                                            <i class="ace-icon fa fa-key"></i>
                                                            <span class="bigger-110">Login</span>
                                                        </button>
                                                    </div>
                                                    <div class="space-4"></div>
                                                </fieldset>
                                            </form>
                                            <div class="space-6"></div>
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
            if ('ontouchstart' in document.documentElement)
                document.write("<script src='assets/js/jquery.mobile.custom.min.js'>" + "<" + "/script>");
        </script>
        <script type="text/javascript">
            jQuery(function ($) {
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
