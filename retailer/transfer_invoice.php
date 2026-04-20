<?php
require_once 'includes/session.php';
require_once 'includes/common_function.php';
if (!isset($_GET['orderNo'])) {
    echo 'Somwthing Wrong.';
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <!-- Latest compiled and minified CSS -->
        <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"> -->

        <!-- jQuery library -->
        <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> -->

        <!-- Latest compiled JavaScript -->
        <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> -->
        <style type="text/css">
            #resultado img {
                height: 200px;
            }

            .mainbody {
                margin: 0.3cm auto;
                min-height: 29.7cm;
                page-break-after: auto;
                position: relative;
                width: 29cm;
            }

            .table {
                border-collapse: collapse;
                margin: 1px;
                padding: 0;
                text-align: center;
                width: 100%;
                font-size: 12px;
                font-family: caption;
            }

            /* .description {
                    width: 850px;
                } */

            .table .left {
                text-align: left;
            }

            .table .right {
                text-align: right;
            }

            .table tr td img {
                display: inline-block;
                height: 58px;
                margin: 0;
                padding: 0;
            }
            /* td {
                    height: 15px;
                    padding-left: 2px;
                } */

            .table .center {
                text-align: center;
            }

            .item {
                width: 30%;
            }

            .uom {
                width: 7%;
            }

            .quantity {
                width: 15%;
            }

            .price {
                width: 15%;
            }

            .price {
                width: 15%;
            }

            /* td {
                    height: 13px;
                } */

            .terms {
                font-weight: bold;
                padding: 0 10px;
                text-align: left;
            }

            .condititon {
                text-align: left;
            }

            .prepared {
                border-right: 1px solid #111;
            }

            .auth {
                border-right: 1px solid #111;
                display: inline-block;
                float: left;
                margin: 0;
                padding: 0;
                width: 33%;
            }

            .auth>small {
                display: inline-block;
                font-weight: bold;
                margin: 47px 6px 0 0;
                padding: 5px;
                width: 100%;
            }

            .signnatory {
                border-right: medium none;
            }

            .backround {
                background-color: #f3f3f3;
            }

            .with-50 {
                width: 50px;
            }

            .with-100 {
                width: 100px;
            }

            b {
                display: inline-block;
                width: 100%;
            }

            .name-des-auth>b {
                padding: 4px;
            }

            .condititon {
                text-align: left;
            }

            .prepared {
                border-right: 1px solid #111;
            }

            .auth {
                border-right: 1px solid #111;
                display: inline-block;
                float: left;
                margin: 0;
                padding: 0;
                width: 33%;
            }

            .auth>small {
                display: inline-block;
                font-weight: bold;
                margin: 47px 6px 0 0;
                padding: 5px;
                width: 100%;
            }

            .signnatory {
                border-right: medium none;
            }

            .backround {
                background-color: #f3f3f3;
            }

            .with-50 {
                width: 10px;
            }

            .with-100 {
                width: 20px;
            }

            .with-200 {
                width: 40px;
            }

            .top {
                vertical-align: top;
            }

            .bottom {
                vertical-align: bottom;
            }

            .hr-line {
                border: none;
                border-top: 1px dotted #f00;
                color: #fff;
                background-color: #fff;
                height: 1px;
            }

            .address b {
                font-size: 15px;
                padding: 4px;
            }

            .address {
                font-size: 17px;
                padding: 6px;
                font-family: icon;
                font-weight: 500;
            }

            .left-div {
                display: inline-block;
                float: left;
                visibility: inherit;
                width: 18%;
            }

            .left-div img {
                float: right;
                display: inline-block;
            }

            .right-div {
                float: left;
                margin: 0;
                padding: 3px;
                text-align: center;
                width: 100%;
            }

            .suply-text {
                font-size: 16px;
                font-weight: 400;
                margin: 2px;
                padding: 0;
            }

            .width100 {
                width: 100%;
            }

            @media print {
                * {
                    margin: 0 !important;
                    padding: 0 !important;
                }

                body {
                    margin: 0 !important;
                    padding: 0 !important;
                }

                .width100 {
                    width: 100% !important;
                }

                tr {
                    width: 100% !important;
                }

                td {
                    /* font-weight: bold; */
                    font-size: 12px;
                }
            }
        </style>
    </head>
    <?php
    error_reporting(0);

    function convert_number_to_words($number) {
        $hyphen = '-';
        $conjunction = ' and ';
        $separator = ', ';
        $negative = 'negative ';
        $decimal = ' point ';
        $dictionary = array(
            0 => 'zero',
            1 => 'one',
            2 => 'two',
            3 => 'three',
            4 => 'four',
            5 => 'five',
            6 => 'six',
            7 => 'seven',
            8 => 'eight',
            9 => 'nine',
            10 => 'ten',
            11 => 'eleven',
            12 => 'twelve',
            13 => 'thirteen',
            14 => 'fourteen',
            15 => 'fifteen',
            16 => 'sixteen',
            17 => 'seventeen',
            18 => 'eighteen',
            19 => 'nineteen',
            20 => 'twenty',
            30 => 'thirty',
            40 => 'forty',
            50 => 'fifty',
            60 => 'sixty',
            70 => 'seventy',
            80 => 'eighty',
            90 => 'ninety',
            100 => 'hundred',
            1000 => 'thousand',
            1000000 => 'million',
            1000000000 => 'billion',
            1000000000000 => 'trillion',
            1000000000000000 => 'quadrillion',
            1000000000000000000 => 'quintillion'
        );

        if (!is_numeric($number)) {
            return false;
        }

        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
            // overflow
            trigger_error(
                    'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX, E_USER_WARNING
            );
            return false;
        }

        if ($number < 0) {
            return $negative . convert_number_to_words(abs($number));
        }

        $string = $fraction = null;

        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }

        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens = ((int) ($number / 10)) * 10;
                $units = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . convert_number_to_words($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= convert_number_to_words($remainder);
                }
                break;
        }

        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = array();
            foreach (str_split((string) $fraction) as $number) {
                $words[] = $dictionary[$number];
            }
            $string .= implode(' ', $words);
        }

        return $string;
    }

    require_once 'includes/common_function.php';
    if (isset($_GET['orderNo'])) {
        $orderNo = base64_decode($_GET['orderNo']);
    }

//    $btn_no = 'GJDC/1819/4483';

    $s_sql_master = "SELECT * FROM retailer_stock_transfer WHERE order_no='$orderNo'";
    $res_master = mysqli_query($conn, $s_sql_master);
    $a_row_master = mysqli_fetch_object($res_master);

    $retailer_data = getRetailerDataById($a_row_master->frm_retailer_id);
    $to_retailer_data = getRetailerDataById($a_row_master->retailer_id);
    $state_data = getStateDataById($retailer_data->state_id);
    $compnay_data = getCompanyDetailsById($retailer_data->company_id);
    ?>

    <body>
        <!--<body onload="window.print();">-->
        <div class="row">
            <div class="left-div col-sm-2">
                <!-- <img src="images/logo.png" style="height: 80px;width :150px;"> -->
                <!-- <img src="images/fta.jpg" style=""> -->
            </div>

            <div class="right-div col-sm-8">
                <b style="font-size: 22px;"><?php echo $compnay_data->name; ?></b>
                <font><b style="font-size: 16px;">Unit :- <?php echo $compnay_data->unit_name; ?></b></font>
                <font><b style="font-size: 20px;">Dispatch Challan</b></font>
                <font style="font-size: 14px;"><?php echo $retailer_data->address; ?></font><br><br>
                <font style="font-size: 12px;text-align:left;">GSTIN Number : <?php echo $compnay_data->gst_no; ?></font><br><br>
            </div>
            <div class="col-xs-12 col-sm-12">
                <table style="width: 100% !important;">

                    <tr>
                        <td colspan="2" style="text-align: left;">Docket Number : </td>
                        <td colspan="4" style="text-align: left;"><?php echo $orderNo; ?></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: left;">Dispatch Date : </td>
                        <td colspan="4" style="text-align: left;"><?php echo date('d-M-Y', strtotime($a_row_master->add_date)); ?></td>
                    </tr>


                </table>
                <table style="margin-top: 5% !important;">
                    <tr>
                        <td colspan="2" style="text-align: left;font-weight: bold;">Name And Address of Store : </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: left;">
                            <?php echo $to_retailer_data->name; ?><br />
                            <?php echo $to_retailer_data->address; ?> <br>
                            GSTIN Number : <?php echo $compnay_data->gst_no; ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="" style="text-align: left;">State : <?php echo $state_data->name; ?></td>
                        <td colspan="" style="text-align: left;">State Code: <?php echo substr($state_data->gstin_no, 0, 2); ?></td>
                    </tr>
                </table>
                <table class="table" style="margin-top: 5% !important; width: 100% !important;border: none;" border="1">

                    <?php
                    $s_sql_1_a = "SELECT * FROM retailer_stock_transfer WHERE order_no='$orderNo'";
                    $res_1_a = mysqli_query($conn, $s_sql_1_a);
                    $no_pos = mysqli_num_rows($res) + 0;
                    $sr_no = 1;
                    $no_box = 0;
                    $a7 = 0;
                    $a8 = 0;
                    $a9 = 0;
                    $a10 = 0;
                    $total_gst_value = 0;
                    $a21 = 0;

                    $s_sql_1 = "SELECT * FROM retailer_stock_transfer WHERE order_no='$orderNo'";
                    $res_1 = mysqli_query($conn, $s_sql_1);
                    $index = 1;
                    while ($a_row = mysqli_fetch_object($res_1)) {

                        print '<tr class="left" style="font-size: 12px !important;"><td class="left" colspan="2"><hr/></td>';

                        print '<tr class="left" style="font-size: 12px !important;">
                <td class="left"><b>Product Sr No : </b></td>
                <td class="left"><b>' . $index . '</b></td>
                    </tr><tr class="left" style="font-size: 12px !important;">
                <td class="left">Product Name : </td>
                <td class="left">' . getItemNameByItemCode($a_row->item_code) . '</td>
                    </tr>
                    <tr class="left" style="font-size: 12px !important;">
                <td class="left">HSN Code : </td>
                <td>' . getItemHsnCodeByItemCode($a_row->item_code) . '</td>
                    </tr>
                    <tr class="left" style="font-size: 12px !important;">
                <td class="left">Batch No : </td>
                <td>' . $a_row->batch_no . '</td>
                    </tr>
                    <tr class="left" style="font-size: 12px !important;">
                <td class="left">ExpireDate : </td>
                <td></td>
                    </tr>
                    <tr class="left" style="font-size: 12px !important;">
                <td class="left">ManufacturingDate : </td>
                <td></td>
<tr class="left" style="font-size: 12px !important;"><td class="left" colspan="2"><hr/></td></tr>';

                        $sr_no++;
                        $index++;
                    }
                    ?>
                </table>
                <table style="margin-top: 2% !important; width: 100% !important;">
                    <tr>
                        <td class="left top">
                            CIN : <?php echo $compnay_data->cin_no; ?><br />
                            PAN No : <?php echo $compnay_data->pan_no; ?>
                            <?php
                            echo '<br/>';
                            echo '<br/>';
                            if (!empty($retailer_data->lic_no_PESTICIDE)) {
                                echo '<br/>';
                                echo 'PESTICIDE Licence No : ' . $retailer_data->lic_no_PESTICIDE;
                            }
                            if (!empty($retailer_data->lic_no_FERTILIZER)) {
                                echo '<br/>';
                                echo 'FERTILIZER Licence No : ' . $retailer_data->lic_no_FERTILIZER;
                            }
                            if (!empty($retailer_data->lic_no_SEEDS)) {
                                echo '<br/>';
                                echo 'SEEDS Licence No : ' . $retailer_data->lic_no_SEEDS;
                            }
                            ?>

                        </td>
                    </tr>
                    <tr>
                        <td class="left top name-des-auth">
                            Declaration:<br />
                            * We are responsible for the quality of agri products, not for the results, as the usage is not in our control.<br/>
                            * The material sold will not be taken back or replaced.<br/>
                            * All disputes are subject to state jurisdiction.
                            <br /><br /><br />
                            Authorized Signatory:-
                        </td>
                    </tr>
                </table>
                <table style="margin-top: 0% !important; width: 100% !important;">
                    <tr>
                        <td>-----------------------------------------------------</td>
                    </tr>
                </table>
            </div>

        </div>
    </body>

</html>