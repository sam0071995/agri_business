<?php
error_reporting(E_ERROR | E_PARSE);
include 'includes/session.php';
require_once 'includes/db.class';
require_once 'includes/common_function.php';
$bdd = new db();
if (isset($_POST['po_no'])) {
    $orderId = trim($_POST['po_no']);
} elseif (isset($_GET['po_no'])) {
    $orderId = trim(base64_decode($_GET['po_no']));
} else {
    echo '<b style="color : red;">Something Wrong.</b>';
    exit;
}

$orderData = $bdd->purchaseOrderByidForBasic($orderId);
if($_GET['pid']){
	$pooidd = base64_decode($_GET['pid']);
	$orderData = $bdd->purchaseOrderByidForBasicWithIdd($orderId,$pooidd);
}
$vendorData = $bdd->getVendor($orderData->vendor_id);
$company_detail = $bdd->getCompanyDetailById($orderData->company_id);
$poDate = str_replace('/', '-', $orderData->po_date);
$poDate = date('d-M-Y', strtotime($poDate));


if ($bdd->purchaseOrderByidCountForBasic($orderId) > 0) {
    ?>
    <html>
    <head>
        <style>
            .container {
                border: 2px solid #111;
                display: inline-block;
                font-family: caption;
                font-size: 16px;
                margin: 0 1%;
                padding: 0px;
                vertical-align: middle;
                width: 50%;
                margin: 5px 330px;
            }

            .header-title {
                text-align: center;
                width: 100%;
            }

            .header-title h3 {
                text-transform: uppercase;
            }

            .container .left {
                width: 55%;
                float: left;
                margin: 0;
                padding: 4px;
                display: inline;
            }

            .container .right {
                width: 38%;
                float: left;
                margin: 0;
                padding: 4px;
                display: inline;
            }

            .order-details {
                border-top: 1px solid #111;
                display: inline-block;
                width: 100%;
            }

            .order-details p {
                padding: 4px;
            }

            .table {
                border: 1px solid #111;
                margin: 0;
                padding: 0;
                text-align: center;
                width: 100%;
                border-collapse: collapse;
            }

            .width_450px {
                width: 320px;
            }

            .table .left {
                align: left;
            }

            .sign {
                display: inline-block;
                margin: 0;
                padding-top: 20px;
                width: 100%;
            }

            .sign-left {
                display: inline-block;
                float: left;
                margin: 0;
                padding: 2px;
            }

            .term_condition {
                font-family: Times New Roman;
                font-size: 12px;
                padding: 2px;
            }

            .print-div {
                text-align: center;
                display: block;
            }

            .page-header h1 {
                float: left;
                margin: 5px 0 0 330px;
                padding: 1px;
                width: 36%;
            }

            .page-header h1 {
                display: inline;
            }

            ader-logo {
                float: left;
            }

            .header-logo>img {
                margin: 0 15px;
                width: 150px;
            }

            .footer-txt {
                border-top: 1px solid #111;
                margin: 40px 0 0 330px;
                padding: 0;
                width: 51%;
            }

            .footer-txt>p {
                margin: 0;
                padding: 0;
                text-align: center;
                width: 100%;
            }

            .header-logo {
                float: left;
            }

            .header-logo>img {
                margin: 0 15px;
                width: 100px;
            }

            .footer-txt {
                border-top: 1px solid #111;
                margin: 40px 0 0 330px;
                padding: 0;
                width: 51%;
            }

            .footer-txt>p {
                margin: 0;
                padding: 0;
                text-align: center;
                width: 100%;
            }

            @media print {
                .container {
                    border: 2px solid #111;
                    display: inline-block;
                    font-family: caption;
                    font-size: 16px;
                    margin: 3px 1%;
                    padding: 0px;
                    vertical-align: middle;
                    width: 100%;
                    margin: 3px;
                }

                .print-div {
                    text-align: center;
                    display: none;
                }

                .page-header {
                    width: 100%;
                }

                .page-header h1 {
                    float: left;
                    padding: 1px;
                    width: 73%;
                    margin: 0 0 0 20px;
                    display: inline-block;
                }

                .header-logo {
                    /*float: left;*/
                    display: inline-block;
                    width: 20%;
                }

                .header-logo>img {
                    margin: 0 15px;
                    width: 150px;
                    display: inline-block;
                }

                .footer-txt {
                    border-top: 1px solid #111;
                    margin: 20px 0 0px 0;
                    padding: 0;
                    width: 100%;
                }

                .footer-txt>p {
                    margin: 0;
                    padding: 0;
                    text-align: center;
                    width: 100%;
                }
            }
        </style>
        <script>
            function myFunction() {
                window.print();
            }
        </script>
    </head>
    <body>
        <div class="container">
            <div class="header-title">
                <h2><?php echo $company_detail->name; ?></h2>
                <p style="font-size:13px; margin: -18px auto auto auto;"><?php echo $company_detail->address; ?></p>
                <p style="font-size:13px; margin: 0px auto auto auto;">Email : <?php echo $company_detail->email; ?>, Contact Number : <?php echo $company_detail->contact_numbar; ?></p>
                <p style="font-size:13px; margin: 0px auto auto auto;">GSTIN No : <?php echo $company_detail->gst_no; ?></p>
                <h3><u>PURCHASE ORDER</u></h3>
            </div>
            <?php if (empty($orderData->vendor_id) && $orderData->vendor_id == 0) { ?>
                <div class="left">
                    To, <br />
                    <b> Name : <?php echo $orderData->supplier_name; ?></b> <br />
                    Address : <?php echo $orderData->supplier_address; ?><br />
                    <b> Supplier Contact Person : <?php echo $orderData->supplier_contact_person; ?> </b> <br />
                    <b> Supplier Contact No : <?php echo $orderData->supplier_contact_no; ?> </b> <br />
                    <b> Supplier GSTIN No : <?php
                    if (!empty($orderData->vendor_id)) {
                        echo $vendor_gstin = $bdd->getVendorGstinNoById($orderData->vendor_id);
                        $vendor_gstin_code = substr($vendor_gstin, 0, 2);
                        $our_gstin_code = substr($company_detail->gst_no, 0, 2);
                    }
                ?> </b> <br />
            </div>
        <?php } else { ?>
            <div class="left">
                To, <br />
                <b> Name : <?php echo $vendorData->vendor_name; ?></b> <br />
                Address : <?php echo $vendorData->address; ?><br />
                <b> Supplier Contact Person : <?php echo $vendorData->c_person; ?> </b> <br />
                <b> Supplier Contact No : <?php echo $vendorData->c_number; ?> </b> <br />
                <b> Supplier GSTIN No : <?php
                if (!empty($orderData->vendor_id)) {
                    echo $vendor_gstin = $vendorData->gstin_no;
                    $vendor_gstin_code = substr($vendor_gstin, 0, 2);
                    $our_gstin_code = substr($company_detail->gst_no, 0, 2);
                }
            ?> </b> <br />
        </div>
    <?php } ?>
    <div class="right">
        <b>P.O NO : <?php echo $orderData->po_no; ?></b><br />
        <b>P.O DATE : <?php echo $poDate; ?></b> <br />
        <b>OUR GST NO : <?php echo $company_detail->gst_no; ?></b> <br />
        <b>Quotation / Performa No. :           </b> <br />
        <b>Quotation / Performa Date. :         </b> <br />
    </div>
    <div class="order-details">
        <p>
            With reference to your quotation we take pleasure in placing this order. Please supply the following items subject
            to the terms & conditions mentioned below:
        </p>
    </div>
    <div class="table-area">
        <table class="table" border="1">
            <thead>
                <tr>
                    <td>Sr No.</td>
                    <?php if ($orderData->new_po_gst_flag == '1') { ?>
                        <td>Item Description</td>
                    <?php } else { ?>
                        <td colspan="3">Item Description</td>
                    <?php } ?>
                    <td>UNIT</td>
                    <td>HSN Code</td>
                    <td>Quantity</td>
                    <td>Unit Price (IN INR)</td>
                    <td>Total Amount</td>
                </tr>
            </thead>
            <tbody>
                <?php
                $orderDetails = $bdd->purchaseOrderDetailsForBasic($orderData->id);
                $sr_NO = 1;
                $rowspan = count($orderDetails);
                foreach ($orderDetails as $orderDetail) {
                    $itemDetails = $bdd->getItemDetails($orderDetail->item_id);
                    $retailer_string = $orderDetail->retailer_string;
                    ?>
                    <tr>
                        <td><?php echo $sr_NO; ?></td>
                        <?php if ($orderData->new_po_gst_flag == '1') { ?>
                            <td class="width_450px"><?php echo $itemDetails->item_desc; ?></td>
                        <?php } else { ?>
                            <td class="width_450px" colspan="3"><?php echo $itemDetails->item_desc; ?></td>
                        <?php } ?>
                        <td><?php echo $itemDetails->unit; ?></td>
                        <td><?php echo $itemDetails->hsn_code; ?></td>
                        <td><?php echo $orderDetail->qty; ?></td>
                        <td><?php echo $orderDetail->rate; ?></td>
                        <td><?php echo $orderDetail->amount; ?></td>
                    </tr>
                    <?php
                    $sr_NO++;
                }

                $retailer_data = $bdd->getRetailerById($retailer_string);
                ?>
                <tr>
                    <td colspan="8" height="20px"></td>
                </tr>
                <tr>
                    <td colspan="6" style="text-align:right;padding-right: 20px;"><b>SUB TOTAL</b></td>
                    <td><b><?php echo $bdd->amount($orderData->sub_total); ?></b></td>
                </tr>
                <tr>
                    <td  colspan="7" align="left" style="padding: 3px; margin: 0;vertical-align: top;"><b>Remarks : </b> <?php echo $orderData->remarks; ?></td>

                </tr>
                <tr>
                    <td colspan="9" align="left"><b>Terms & Conditions</b></td>
                </tr>
                <tr>
                    <td colspan="1">1</td>
                    <td colspan="8" align="left" class="term_condition">
                        <b>
                            Order Placed For M/s <?php echo $retailer_data->full_name; ?>,<?php // echo $retailer_data->address;  ?> Contact Person : <?php echo $retailer_data->contact_name; ?> (<?php echo $retailer_data->contact_number; ?>)
                        </b>
                    </td>
                </tr>
                <tr>
                    <td colspan="1">2</td>
                    <td colspan="8" align="left" class="term_condition">
                        <b>
                            All correspondence/Relavant documents to be sent to our works along with the material.
                        </b>
                    </td>
                </tr>
                <tr>
                    <td colspan="1">3</td>
                    <td colspan="8" align="left" class="term_condition">
                        <b>
                            Our Purchase Order No. should be mentioned in all the related documents with each dispatch.
                        </b>
                    </td>
                </tr>
                <tr>
                    <td colspan="1">4</td>
                    <td colspan="8" align="left" class="term_condition">
                        <b>
                            Material Test report/Technical Specification Data should accompany each consignment
                        </b>
                    </td>
                </tr>
                <tr>
                    <td colspan="1">5</td>
                    <td colspan="8" align="left" class="term_condition">
                        <b>
                            All material is subject to inspection & approval at our factory. Material not meeting our
                            required specification will be returned at your risk & cost.
                        </b>
                    </td>
                </tr>
                <tr>
                    <td colspan="1">6</td>
                    <td colspan="8" align="left" class="term_condition">
                        <b>
                            We reserve the right to amend or cancel the order due to the delay in delivery of material as per PO Schedule
                        </b>
                    </td>
                </tr>
                <tr>
                    <td colspan="1">7</td>
                    <td colspan="8" align="left" class="term_condition">

                        <b>
                            <?php if ($orderData->company_id == 3) { ?>
                                All disputs are subject to Lucknow jurisdiction only.
                                <?php
                            } else {
                                ?>
                                All disputs are subject to Gujarat jurisdiction only.

                            <?php } ?>
                        </b>
                    </td>
                </tr>
                <tr>
                    <td>8</td>
                    <td colspan="8" align="left"><b> Delivery At : <?php echo $bdd->getRetailerById($orderData->term_delivery)->address; ?></b></td>
                </tr>
                <tr>
                    <td>9</td>
                    <td colspan="8" align="left"><b>Terms of payment: <?php echo $orderData->term_payment; ?></b></td>
                </tr>
                <tr>
                    <td colspan="2" align="left" style="vertical-align: bottom;"><b>Prepared by <?php echo getUserNameById($orderData->user_id); ?></b></td>
                    <td colspan="7" align="right">
                        <div class="sign">
                            <label class="sign-left">E&OE</label>
                            <label class="sign-right">Authorised signatory</label>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<div class="footer-txt">
    <p><b>Registered Office :</b><?php echo $company_detail->address; ?> </p>
    <p><b>Phone : </b><?php echo $company_detail->contact_numbar; ?> <b>E-mail :</b><?php echo $company_detail->email; ?></p>
</div>
</body>

</html>
<?php
} else {
    echo '<b style="color : red;">Something Wrong.</b>';
    exit;
}
?>