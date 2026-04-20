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
    if (isset($_GET['btn_no'])) {
        $btn_no = base64_decode($_GET['btn_no']);
    }

//    $btn_no = 'GJDC/1819/4483';

    $s_sql_master = "SELECT * FROM retailer_order_master WHERE po_no='$btn_no' ";
    $res_master = mysqli_query($conn, $s_sql_master);
    $a_row_master = mysqli_fetch_object($res_master);

    $retailer_data = getRetailerDataById($a_row_master->retailer_id);
    $state_data = getStateDataById($retailer_data->state_id);
    $compnay_data = getCompanyDetailsById($a_row_master->company_id);
    ?>

    <body onload="window.print();">
        <div class="row">
            <div class="left-div col-sm-2">
                <!-- <img src="images/logo.png" style="height: 80px;width :150px;"> -->
                <!-- <img src="images/fta.jpg" style=""> -->
            </div>

            <div class="right-div col-sm-8">
                <b style="font-size: 22px;"><?php echo $compnay_data->name; ?></b>
                <font><b style="font-size: 16px;">Unit :- <?php echo $compnay_data->unit_name; ?></b></font>
                <font><b style="font-size: 20px;">Tax Invoice</b></font>
                <font style="font-size: 14px;"><?php echo $compnay_data->address; ?></font><br><br>
                <font style="font-size: 14px;text-align:left;">GSTIN Number : <?php echo $compnay_data->gst_no; ?></font><br><br>
            </div>
            <div class="col-xs-12 col-sm-12">
                <table style="width: 100% !important;">

                    <tr>
                        <td colspan="2" style="text-align: left;">Invoice Number </td>
                        <td colspan="4" style="text-align: left;"><?php echo $btn_no; ?></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: left;">Invoice Date </td>
                        <td colspan="4" style="text-align: left;"><?php echo date('d-M-Y', strtotime($a_row_master->added_date)); ?></td>
                    </tr>


                </table>
                <table style="margin-top: 5% !important;">
                    <tr>
                        <td style="text-align: left;">Name And Address of Buyer : </td>
                    </tr>
                    <tr>
                        <td style="text-align: left;">
                            <?php echo $retailer_data->name; ?><br />
                            <?php echo $retailer_data->address; ?> <br>
                            GSTIN Number : <?php echo $compnay_data->gst_no; ?>
                        </td>
                    </tr>
                </table>
                <?php if ($a_row_master->b2b_flg == '0') { ?>
                    <table style="margin-top: 5% !important;">
                        <tr>
                            <td style="text-align: left;">Name and Address of Cosignee : </td>
                        </tr>
                        <tr>
                            <td style="text-align: left;"><?php echo $a_row_master->cus_name; ?><br /><?php echo $a_row_master->cus_add; ?><br>

                            </td>

                        </tr>
                    </table>
                <?php } ?>
                <?php if ($a_row_master->b2b_flg == 1) { ?>
                    <table style="margin-top: 5% !important;">
                        <tr>
                            <td style="text-align: left;">Name and Address of Bill to Company : </td>
                        </tr>
                        <tr>
                            <td style="text-align: left;"><?php echo $a_row_master->cus_name; ?><br /><?php echo $a_row_master->cus_add; ?><br>

                            </td>

                        </tr>
                    </table>
                <?php } ?>

                <table style="margin-top: 3% !important; width: 100% !important;">
                    <tr>
                        <td colspan="2" style="text-align: left;">State : <?php echo $state_data->name; ?></td>
                        <td colspan="4" style="text-align: left;">State Code: <?php echo substr($state_data->gstin_no, 0, 2); ?></td>
                    </tr>
                </table>
                <table class="table" style="margin-top: 5% !important; width: 100% !important;" border="1">
                    <tr class="backround" style="font-size: 10px !important;">
                        <!-- <td class="center" rowspan="2">Sr. No.</td> -->
                        <td class="center description" rowspan="2">Description</td>
                        <td class="center with-100" rowspan="2">HSN Code</td>
                        <td class="center with-100" rowspan="2" colspan="">Total Qty of goods</td>
                        <td class="center with-100" rowspan="2" colspan="">Taxable Value<br>(In Rs.)</td>
                        <?php
                        if ($dealer_state_id == $ec_state_id) {
                            ?>
                            <td class="center" colspan="">GST</td>
                            <?php
                        } else {
                            ?>
                            <td class="center" colspan="">GST</td>
                        <?php } ?>
                        <td class="center with-200" colspan="" rowspan="2">Total<br>(In Rs.)</td>
                    </tr>
                    <tr class="backround" style="font-size: 12px !important;">
                        <?php
                        if ($dealer_state_id == $ec_state_id) {
                            ?>
                                                    <!-- <td class="center with-50">Rate<br>(In %)</td> -->
                            <td class="center with-100">Amt.<br>(In Rs.)</td>
                            <!-- <td class="center with-50">Rate<br>(In %)</td> -->
                            <!-- <td class="center with-100">Amt.<br>(In Rs.)</td> -->
                            <?php
                        } else {
                            ?>
                            <!-- <td class="center with-50" colspan="">Rate<br>(In %)</td> -->
                            <td class="center with-100" colspan="">Amt.<br>(In Rs.)</td>
                        <?php } ?>
                    </tr>

                    <?php
                    $s_sql_1_a = "SELECT * FROM retailer_order_temporary WHERE  po_no = '$btn_no' ";
                    $res_1_a = mysqli_query($conn, $s_sql_1_a);
                    $no_pos = mysqli_num_rows($res) + 0;
                    $sr_no = 1;
                    $no_box = 0;
                    $a7 = 0;
                    $a8 = 0;
                    $a9 = 0;
                    $a10 = 0;
                    $a21 = 0;

                    $s_sql_1 = "SELECT * FROM retailer_order_temporary WHERE  po_no = '$btn_no' ";
                    $res_1 = mysqli_query($conn, $s_sql_1);
                    while ($a_row = mysqli_fetch_object($res_1)) {
                        $s_sql_alter_desc = "SELECT * FROM retailer_inventory_master  WHERE item_code='" . $a_row->item_code . "' and retailer_id = '$a_row_master->retailer_id'";
                        $res_alter_desc = mysqli_query($conn, $s_sql_alter_desc);
                        $a_row_alter_desc = mysqli_fetch_object($res_alter_desc);

                        $f8 = $a_row_alter_desc->item_desc;
                        $f2 = $a_row->qty + 0;
                        $uom = $a_row_alter_desc->uom;
                        $bill_item = $a_row_alter_desc->bill_item;

                        $a1 = 0;
                        $a2 = 0;
                        $a3 = 0;
                        $a4 = 0;
                        $a5 = 0;
                        $a6 = 0;
                        $a19 = 0;
                        $a20 = 0;



                        $a1 = round(($f2 * $a_row_alter_desc->basic_price), 2);
                        $a2 = $a_row_alter_desc->cgst_rate;
                        $a3 = round(($f2 * $a_row_alter_desc->cgst_value), 2);
                        $a4 = $a_row_alter_desc->sgst_rate;
                        $a5 = round(($f2 * $a_row_alter_desc->sgst_value), 2);
                        $a6 = round(($f2 * $a_row_alter_desc->total), 2);
                        $a19 = $a2 + $a4;
                        $a20 = round(($a3 + $a5), 2);
                        $a21 = $a20;

                        $no_box = 0;
                        $f15 = '';


                        $f15 = $f8;

                        $no_box = round($no_box);
                        if ($no_box == 0) {
                            $no_box = '-';
                        }

                        print '<tr style="font-size: 12px !important;">
                
                <td class="center">' . $f15 . '</td>
                <td>' . $a_row_alter_desc->hsn_code . '</td>
                <td colspan="">' . $f2 . '</td>
                <td colspan="">' . $a1 . '</td>';
                        if ($dealer_state_id == $ec_state_id) {
                            print '
                <td>' . $a20 . '</td>';
                        } else {
                            print '  <td colspan="">' . $a20 . '</td>';
                        }
                        print '<td>' . $a6 . '</td>
            </tr>';
                        $total_qty = $total_qty + $f2;
                        $a7 = $a7 + $a1;
                        $a8 = $a8 + $a3;
                        $a9 = $a9 + $a5;
                        $a10 = $a10 + $a6;
                        $sr_no++;
                    }
                    print '<tr style="font-size: 12px !important;">
                
                <td>Total </td>
                <td></td>
                <td colspan="">' . $total_qty . '</td>
                <td colspan="">' . $a7 . '</td>';
                    if ($dealer_state_id == $ec_state_id) {

                        print '
                <td>' . $a21 . '</td>';
                    } else {
                        print '
                <td colspan="2">' . $a21 . '</td>';
                    }
                    print '<td>' . $a10 . '</td>
            </tr>';
                    ?>
                </table>
                <table style="margin-top: 5% !important; width: 100% !important;">
                    <tr>
                        <td class="left top">Total Amount In Words : <?php echo ucwords(convert_number_to_words($a10)); ?></td>
                    </tr>
                </table>
                <table style="margin-top: 5% !important; width: 100% !important;">
                    <tr>
                        <td class="left top">
                            CIN : <?php echo $compnay_data->cin_no; ?><br />
                            PAN No : <?php echo $compnay_data->pan_no; ?><br />
                        </td>

                    </tr>
                </table>
                <table style="margin-top: 5% !important; width: 100% !important;">
                    <tr>

                        <td class="left top">* These items are accessories for sale of principal item i.e. Registration plate. Their costs are included in the principal item and hence need not be disclosed separately.</td>
                    </tr>
                    <tr>

                        <td class="left top name-des-auth">
                            Declaration:<br />
                            We declare that this DC/Invoice shows the actual price of the goods described and that all particulars are true and correct.

                            <br />Authorized Signatory:-


                        </td>
                    </tr>

                </table>
                <table style="margin-top: 10% !important; width: 100% !important;">
                    <tr>
                        <td>-----------------------------------------------------</td>
                    </tr>
                </table>
            </div>

        </div>
    </body>

</html>