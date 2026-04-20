<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta charset="UTF-8">
    <title><?php
        if (isset($retailer_detail->company_id)) {
            echo getCompanyNameById($retailer_detail->company_id);
        } else {
            echo 'Login';
        }
        ?></title>
    <meta name="description" content="overview &amp; stats" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

    <!-- bootstrap & fontawesome -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/font-awesome/4.5.0/css/font-awesome.min.css" />

    <!-- page specific plugin styles -->
    <link rel="stylesheet" href="assets/css/jquery-ui.custom.min.css" />
    <link rel="stylesheet" href="assets/css/ui.jqgrid.min.css" />
    <link rel="stylesheet" href="assets/css/chosen.min.css" />
    <link rel="stylesheet" href="assets/css/bootstrap-datepicker3.min.css" />
    <link rel="stylesheet" href="assets/css/bootstrap-timepicker.min.css" />
    <link rel="stylesheet" href="assets/css/daterangepicker.min.css" />
    <link rel="stylesheet" href="assets/css/bootstrap-datetimepicker.min.css" />
    <link rel="stylesheet" href="assets/css/bootstrap-colorpicker.min.css" />
    <link rel="stylesheet" href="assets/css/select2.min.css" />

    <!-- text fonts -->
    <link rel="stylesheet" href="assets/css/fonts.googleapis.com.css" />

    <!-- ace styles -->
    <link rel="stylesheet" href="assets/css/ace.min.css" class="ace-main-stylesheet" id="main-ace-style" />

    <!--[if lte IE 9]>
            <link rel="stylesheet" href="assets/css/ace-part2.min.css" class="ace-main-stylesheet" />
    <![endif]-->
    <link rel="stylesheet" href="assets/css/ace-skins.min.css" />
    <link rel="stylesheet" href="assets/css/ace-rtl.min.css" />
    <link rel="stylesheet" href="assets/css/style.css" />

    <!--[if !IE]> -->
    <script src="assets/js/jquery-2.1.4.min.js"></script>

    <script type="text/javascript">
        if ('ontouchstart' in document.documentElement)
            document.write("<script src='assets/js/jquery.mobile.custom.min.js'>" + "<" + "/script>");
    </script>
    <script src="assets/js/bootstrap.min.js"></script>

    <script src="assets/js/jquery-ui.custom.min.js"></script>
    <script src="assets/js/jquery.ui.touch-punch.min.js"></script>
    <script src="assets/js/chosen.jquery.min.js"></script>
    <script src="assets/js/spinbox.min.js"></script>
    <script src="assets/js/bootstrap-datepicker.min.js"></script>
    <script src="assets/js/bootstrap-timepicker.min.js"></script>
    <script src="assets/js/moment.min.js"></script>
    <script src="assets/js/daterangepicker.min.js"></script>
    <script src="assets/js/bootstrap-datetimepicker.min.js"></script>
    <script src="assets/js/bootstrap-colorpicker.min.js"></script>
    <script src="assets/js/jquery.knob.min.js"></script>
    <script src="assets/js/autosize.min.js"></script>
    <script src="assets/js/jquery.inputlimiter.min.js"></script>
    <script src="assets/js/jquery.maskedinput.min.js"></script>
    <script src="assets/js/bootstrap-tag.min.js"></script>
    <script src="assets/js/jquery.jqGrid.min.js"></script>
    <script src="assets/js/grid.locale-en.js"></script>
    <script src="assets/js/bootstrap-editable.min.js"></script>
    <script src="assets/js/ace-editable.min.js"></script>
    <script src="assets/js/jquery.maskedinput.min.js"></script>


    <!-- page specific plugin scripts -->
    <script src="assets/js/jquery.dataTables.min.js"></script>
    <script src="assets/js/jquery.dataTables.bootstrap.min.js"></script>
    <script src="assets/js/dataTables.buttons.min.js"></script>
    <script src="assets/js/buttons.flash.min.js"></script>
    <script src="assets/js/buttons.html5.min.js"></script>
    <script src="assets/js/buttons.print.min.js"></script>
    <script src="assets/js/buttons.colVis.min.js"></script>
    <script src="assets/js/dataTables.select.min.js"></script>
    <script src="assets/js/select2.min.js"></script>

    <!-- ace scripts -->
    <script src="assets/js/ace-elements.min.js"></script>
    <script src="assets/js/ace.min.js"></script>

    <!-- ace settings handler -->
    <script src="assets/js/ace-extra.min.js"></script>

    <script type="text/javascript">
//        $(window).load(function () {
//            $(".loader").fadeOut("slow");
//        })
    </script>
    <script type="text/javascript">
        $(window).load(function () {
            $(".loader").fadeOut("slow");
        });
        $(document).ready(function () {
            $('.alert-danger').delay(2000).slideUp(2500);
            $('.alert-success').delay(2000).slideUp(4500);
        });

    </script>
    <style>
        .loader {
            position: fixed;
            left: 0px;
            top: 0px;
            width: 100%;
            height: 100%;
            z-index: 9999;
            background: url('images/ajit.gif') 50% 50% no-repeat rgb(249,249,249);
        }
        span.question {
            cursor: pointer;
            display: inline-block;
            width: 16px;
            height: 16px;
            background-color: #89A4CC;
            line-height: 16px;
            color: White;
            font-size: 9px;
            font-weight: bold;
            border-radius: 8px;
            text-align: center;
            position: relative;

        }
        span.question:hover { background-color: #3D6199; }
    </style>
</head>

<?php
$ajax_page = 'ajax_js.php';
$ajax_inward = 'ajax_inward.php';
?>
