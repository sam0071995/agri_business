<?php
require_once 'includes/session.php';
require_once 'includes/common_function.php';
$item_code = '';
$retailer_id = '';
if (isset($_POST['Retailer_id'])) {
    $retailer_id = $_POST['Retailer_id'];
}
if (isset($_POST['show'])) {
    $date_1 = date("Y-m-d", strtotime($_POST['date_1']));
    $date_2 = date("Y-m-d", strtotime($_POST['date_2']));
    $retailer_id = 'All';
    if (isset($_POST['Retailer_id'])) {
        $retailer_id = $_POST['Retailer_id'];
        $item_code = $_POST['item_code'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <?php require_once 'includes/header.php'; ?>
    <body class="no-skin">
        <?php require_once 'includes/menu.php'; ?>
        <div class="main-container ace-save-state" id="main-container">
            <?php require_once 'includes/left_sidebar.php'; ?>
            <div class="main-content">
                <div class="main-content-inner">
                    <?php require_once 'includes/breadcrumbs.php'; ?>
                    <div class="page-content">
                        <?php require_once 'includes/page-header.php'; ?>

                        <div class="row">
                            <div class="col-xs-12">
                                <?php
                                if (isset($_GET['error'])) {
                                    switch ($_GET['error']) {
                                        case 1:
                                            $msg = "Item can not be insert.";
                                            break;
                                        default:
                                            $msg = "Something Wrong.";
                                            break;
                                    }
                                    ?>
                                    <div class="alert alert-block alert-danger">
                                        <button type="button" class="close" data-dismiss="alert">
                                            <i class="ace-icon fa fa-times"></i>
                                        </button>

                                        <i class="ace-icon fa fa-check red form-error-msg"></i>
                                        <?php echo $msg; ?>
                                    </div>
                                <?php } ?>
                                <?php if (isset($_GET['success'])) { ?>
                                    <div class="alert alert-block alert-success">
                                        <button type="button" class="close" data-dismiss="alert">
                                            <i class="ace-icon fa fa-times"></i>
                                        </button>

                                        <i class="ace-icon fa fa-check green form-error-msg"></i>
                                        <?php echo "Product Updated Successfully"; ?>
                                    </div>
                                <?php } ?>
                                <!--                                <div class="page-header">
                                                                    <div class="widget-box">-->
                                <!--                                        <div class="widget-header">
                                                                            <h4 class="widget-title">Retailer | Product wise Sales Report.</h4>
                                                                        </div>-->
                                <!--                                        <div class="widget-body">
                                                                            <div class="widget-main">-->
                                <!--<form class="form-inline center" action="" method="POST">-->
                                <form name="myForm"  role="form" action="" method="POST" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-sm-2">
                                                <b>From Date :</b>
                                                <input class="form-control date-picker" id="id-" name="date_1" type="text" value="<?php
                                                if (isset($_POST['date_1'])) {
                                                    echo $_POST['date_1'];
                                                } else {
                                                    echo date('d-m-Y');
                                                }
                                                ?>" data-date-format="dd-mm-yyyy" />

                                            </div>
                                            <div class="col-sm-2">
                                                <b>To Date :</b>
                                                <input class="form-control date-picker" id="" name="date_2"  type="text" value="<?php
                                                if (isset($_POST['date_2'])) {
                                                    echo $_POST['date_2'];
                                                } else {
                                                    echo date('d-m-Y');
                                                }
                                                ?>" data-date-format="dd-mm-yyyy" />

                                            </div>
                                            <div class="col-sm-3">
                                                <b>Select Retailer :</b>
                                                <select class="form-control col-xs-3" name="Retailer_id" id="Retailer_id" required="required">
                                                    <option value="All">All Retailers</option>
                                                    <?php foreach (getAllRetailerDetails($company_id_in) as $active_sellers) { ?>
                                                        <option value="<?php echo $active_sellers->id; ?>" <?php
                                                        if ($retailer_id == $active_sellers->id) {
                                                            echo 'selected="selected"';
                                                        }
                                                        ?>><?php echo $active_sellers->name; ?><?php
                                                                    if ($active_sellers->status == 0) {
                                                                        echo '<b class="red"> [Clossed]</b>';
                                                                    }
                                                                    ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="col-sm-3">
                                                <b>Item :</b>
                                                <select class="form-field-select-2 form-control" multiple name="item_code[]" id="item_code" required="required">
                                                    <option value="All">All Items</option>
                                                    <?php foreach (getActiveItemsList() as $active_item) { ?>
                                                        <option value="<?php echo $active_item->item_code; ?>" <?php
                                                        if ($item_code == $active_item->item_code) {
                                                            echo 'selected="selected"';
                                                        }
                                                        ?>><?php echo $active_item->item_desc; ?></option>
                                                            <?php } ?>
                                                </select>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="col-md-offset-3 col-md-5">
                                                    <button class="btn btn-info" type="submit" name="show" value="show">
                                                        <i class="ace-icon fa fa-check bigger-110"></i>
                                                        Show
                                                    </button>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <!--                                            </div>
                                                                        </div>-->
                                <!--                                    </div>
                                                                </div>-->
                                <!--<div class="modal-body">-->
                                <?php if (isset($_POST['show'])) { ?>
                                                                                                                                                                                                                                                                                                                    <!--<h5 class="red">Total sale amount between <?php // echo $date_1;                                                                     ?> and <?php // echo $date_2;                                                                     ?> is : <b class="blue"><?php // echo IND_money_format(getProductSalesTotalAmtByRetailerTempTable($date_1, $date_2, $retailer_id, $company_id_in));                                                                     ?> Rs.</b></h5>-->
                                <?php } ?>

                                <div class="row clearfix">
                                    <div class="pull-right tableTools-container"></div>
                                </div>
                                <?php if (isset($_POST['show'])) { ?>
                                    <a target="_blank" href="download_sale_customer_report.php?menu=1&f_date=<?php echo $date_1; ?>&l_date=<?php echo $date_2; ?>&Retailer_id=<?php echo $retailer_id; ?>&company_id_in=<?php echo $company_id_in; ?>" class="btn btn-success">
                                        Download Excel
                                    </a>
                                <?php } ?>
                                <div>
                                    <table id="dynamic-table" class="table table-bordered table-hover">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>#</th>
                                                <th>Retailer Name</th>
                                                <th>Order No</th>
                                                <th>Item Name</th>
                                                <th>MainCategory</th>   
                                                <th>SubCategory</th>   
                                                <th>PaymentType</th>
                                                <th>Qty</th>
                                                <th>Rate</th>
                                                <th>CGST Rate</th>
                                                <th>SGST Rate</th>
                                                <th>IGST Rate</th>
                                                <th>CGST Value</th>
                                                <th>SGST Value</th>
                                                <th>IGST Value</th>
                                                <th>Total</th>
                                                <th>Taxable Value</th>
                                                <th>DiscountAmount</th>
                                                <th>CouponCode</th>
                                                <th>Batch No</th>
                                                <th>Expiry Date</th>
                                                <th>HSNCODE</th>
                                                <th>UOM</th>
                                                <th>Fincial Year</th>   
                                                <th>BillDate</th>
                                                <th>Cus Name</th>
                                                <th>Cus Address</th>
                                                <th>Cus Mobile</th>
                                                <th>Cus Adhar</th>
                                                <th>Cus Village</th>
                                                <th>Cus Pincode</th>
                                                <th>Return Qty</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $final_total = 0;
                                            $final_qty = 0;
                                            $final__return_qty = 0;
                                            if (isset($_POST['show'])) {
                                                $date_1 = date("Y-m-d", strtotime($_POST['date_1']));
                                                $date_2 = date("Y-m-d", strtotime($_POST['date_2']));
                                                $retailer_id = 'All';
                                                if (isset($_POST['Retailer_id'])) {
                                                    $retailer_id = $_POST['Retailer_id'];
                                                }
                                                $item_code_array = $_POST['item_code'];
                                                $index = 1;

                                                if ($item_code_array[0] != 0) {
                                                    foreach ($item_code_array as $item_code) {
                                                        $products = getProductSalesByRetailerItemCodeTempTable($date_1, $date_2, $retailer_id, $company_id_in, $item_code);
                                                        foreach ($products as $product) {
                                                            if ($product->payment_type == 0) {
                                                                $payment_type = "CASH";
                                                            } else if ($product->payment_type == 1) {
                                                                $payment_type = "ONLINE";
                                                            } else {
                                                                $payment_type = "Cheque/DD";
                                                            }
                                                            $item_detail = getproductDetailsByCode($product->item_code);

                                                            $company_id = $product->company_id;
                                                            $gstin_no = $product->gstin_no;
                                                            if ($gstin_no != '0') {
                                                                $company_gstin = getCompanyGSTINById($company_id);
                                                                $company_gstin_2_dig = substr($company_gstin, 0, 2);
                                                                $cus_gstin_2_dig = substr($gstin_no, 0, 2);
                                                                if ($company_gstin_2_dig != $cus_gstin_2_dig) {
                                                                    $sgst_rate = 0;
                                                                    $cgst_rate = 0;
                                                                    $igst_rate = $product->cgst_rate + $product->sgst_rate;

                                                                    $sgst_value = 0;
                                                                    $cgst_value = 0;
                                                                    $igst_value = $product->cgst + $product->sgst;
                                                                } else {
                                                                    $sgst_rate = $product->sgst_rate;
                                                                    $cgst_rate = $product->cgst_rate;
                                                                    $igst_rate = 0;

                                                                    $sgst_value = $product->sgst;
                                                                    $cgst_value = $product->cgst;
                                                                    $igst_value = 0;
                                                                }
                                                            } else {
                                                                $sgst_rate = $product->sgst_rate;
                                                                $cgst_rate = $product->cgst_rate;
                                                                $igst_rate = 0;

                                                                $sgst_value = $product->sgst;
                                                                $cgst_value = $product->cgst;
                                                                $igst_value = 0;
                                                            }

                                                            $final_total = $final_total + $product->total_price;
                                                            $final_qty = $final_qty + $product->qty;
                                                            $final__return_qty = $final__return_qty + $product->return_qty;
                                                            ?> 
                                                            <tr>
                                                                <td><?php echo $index; ?></td>
                                                                <td><?php echo getRetailerNameById($product->retailer_id); ?></td>
                                                                <td><?php echo $product->order_no; ?></td>
                                                                <td><?php echo getItemNameByItemCode($product->item_code); ?></td> 
                                                                <td><?php echo getCategoryNameById($item_detail->main_category_id); ?></td>
                                                                <td><?php echo getCategoryNameById($item_detail->sub_category_id); ?></td>
                                                                <td><?php echo $payment_type; ?><?php
                                                                    if (!empty($product->transaction_no)) {
                                                                        echo "<hr/>Transaction No : <b class='red'>" . $product->transaction_no . "</b>";
                                                                    }
                                                                    ?>
                                                                </td>
                                                                <td><?php echo $product->qty; ?></td>
                                                                <td><?php echo round($product->total_price / $product->qty, 2); ?></td>
                                                                <td><?php echo $sgst_rate; ?></td>
                                                                <td><?php echo $cgst_rate; ?></td>
                                                                <td><?php echo $igst_rate; ?></td>
                                                                <td><?php echo $sgst_value; ?></td>
                                                                <td><?php echo $cgst_value; ?></td>
                                                                <td><?php echo $igst_value; ?></td>
                                                                <td><?php echo $product->total_price; ?></td>
                                                                <td><?php echo $product->basic; ?></td>
                                                                <td><?php echo $product->discount_amount; ?></td>
                                                                <td><?php echo $product->coupon_code; ?></td>
                                                                <td><?php echo $product->batch_no; ?></td>
                                                                <td><?php echo getExpiryDateByItemCode($product->retailer_id, $product->item_code, $product->batch_no); ?></td>
                                                                <td><?php echo getItemHSNCODEByItemCode($product->item_code); ?></td>
                                                                <td><?php echo $product->uom; ?></td>
                                                                <td><?php echo $product->fin_year; ?></td>
                                                                <td><?php echo date('d M Y', strtotime($product->added_date)); ?></td>
                                                                <td><?php echo $product->cus_name; ?></td>
                                                                <td><?php echo $product->cus_add; ?></td>
                                                                <td><?php echo $product->cus_ph; ?></td>
                                                                <td><?php echo $product->cus_adhar; ?></td>
                                                                <td><?php echo getVillageNameById($product->cus_village); ?></td>
                                                                <td><?php echo $product->cus_pin; ?></td>
                                                                <td><?php echo $product->return_qty; ?></td>
                                                            </tr>
                                                            <?php
                                                            $index++;
                                                        }
                                                    }
                                                } else {
                                                    $products = getProductSalesByRetailerItemCodeTempTableAllInnerJoin($date_1, $date_2, $retailer_id, $company_id_in, $item_code);
//                                                    $products = getProductSalesByRetailerItemCodeTempTableAll($date_1, $date_2, $retailer_id, $company_id_in, $item_code);
                                                    foreach ($products as $product) {
                                                        if ($product->payment_type == 0) {
                                                            $payment_type = "CASH";
                                                        } else if ($product->payment_type == 1) {
                                                            $payment_type = "ONLINE";
                                                        } else {
                                                            $payment_type = "Cheque/DD";
                                                        }
                                                        $item_detail = getproductDetailsByCode($product->item_code);

                                                        $company_id = $product->company_id;
                                                        $gstin_no = $product->gstin_no;
                                                        if ($gstin_no != '0') {
                                                            $company_gstin = getCompanyGSTINById($company_id);
                                                            $company_gstin_2_dig = substr($company_gstin, 0, 2);
                                                            $cus_gstin_2_dig = substr($gstin_no, 0, 2);
                                                            if ($company_gstin_2_dig != $cus_gstin_2_dig) {
                                                                $sgst_rate = 0;
                                                                $cgst_rate = 0;
                                                                $igst_rate = $product->cgst_rate + $product->sgst_rate;

                                                                $sgst_value = 0;
                                                                $cgst_value = 0;
                                                                $igst_value = $product->cgst + $product->sgst;
                                                            } else {
                                                                $sgst_rate = $product->sgst_rate;
                                                                $cgst_rate = $product->cgst_rate;
                                                                $igst_rate = 0;

                                                                $sgst_value = $product->sgst;
                                                                $cgst_value = $product->cgst;
                                                                $igst_value = 0;
                                                            }
                                                        } else {
                                                            $sgst_rate = $product->sgst_rate;
                                                            $cgst_rate = $product->cgst_rate;
                                                            $igst_rate = 0;

                                                            $sgst_value = $product->sgst;
                                                            $cgst_value = $product->cgst;
                                                            $igst_value = 0;
                                                        }

                                                        $final_total = $final_total + $product->total_price;
                                                        $final_qty = $final_qty + $product->qty;
                                                        ?> 
                                                        <tr>
                                                            <td><?php echo $index; ?></td>
                                                            <td><?php echo getRetailerNameById($product->retailer_id); ?></td>
                                                            <td><?php echo $product->order_no; ?></td>
                                                            <!--<td><?php // echo $product->item_name;                                                  ?></td>--> 
                                                            <td><?php echo getItemNameByItemCode($product->item_code); ?></td>
                                                            <td><?php echo getCategoryNameById($item_detail->main_category_id); ?></td>
                                                            <td><?php echo getCategoryNameById($item_detail->sub_category_id); ?></td>
                                                            <td><?php echo $payment_type; ?><?php
                                                                if (!empty($product->transaction_no)) {
                                                                    echo "<hr/>Transaction No : <b class='red'>" . $product->transaction_no . "</b>";
                                                                }
                                                                ?></td>
                                                            <td><?php echo $product->qty; ?></td>
                                                            <td><?php echo round($product->total_price / $product->qty, 2); ?></td>
                                                            <td><?php echo $sgst_rate; ?></td>
                                                            <td><?php echo $cgst_rate; ?></td>
                                                            <td><?php echo $igst_rate; ?></td>
                                                            <td><?php echo $sgst_value; ?></td>
                                                            <td><?php echo $cgst_value; ?></td>
                                                            <td><?php echo $igst_value; ?></td>
                                                            <td><?php echo $product->total_price; ?></td>
                                                            <td><?php echo $product->basic; ?></td>
                                                            <td><?php echo $product->discount_amount; ?></td>
                                                            <td><?php echo $product->coupon_code; ?></td>
                                                            <td><?php echo $product->batch_no; ?></td>
                                                            <td><?php echo getExpiryDateByItemCode($product->retailer_id, $product->item_code, $product->batch_no); ?></td>
                                                            <td><?php echo getItemHSNCODEByItemCode($product->item_code); ?></td>
                                                            <td><?php echo $product->uom; ?></td>
                                                            <td><?php echo $product->fin_year; ?></td>
                                                            <td><?php echo date('d M Y', strtotime($product->added_date)); ?></td>
                                                            <td><?php echo $product->cus_name; ?></td>
                                                            <td><?php echo $product->cus_add; ?></td>
                                                            <td><?php echo $product->cus_ph; ?></td>
                                                            <td><?php echo $product->cus_adhar; ?></td>
                                                            <td><?php echo getVillageNameById($product->cus_village); ?></td>
                                                            <td><?php echo $product->cus_pin; ?></td>
                                                            <td><?php echo $product->return_qty; ?></td>
                                                        </tr>
                                                        <?php
                                                        $index++;
                                                    }
                                                }
                                            }
                                            ?>
                                            <tr>
                                                <th>#</th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>   
                                                <th></th>
                                                <th></th>
                                                <th><?php echo IND_money_format($final_qty); ?></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>   
                                                <th><?php echo IND_money_format($final_total); ?></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th><?php echo IND_money_format($final__return_qty); ?></th>
                                            </tr>
                                        </tbody>
                                    </table>

                                </div>
                                <!--</div>-->
                            </div>
                        </div><!-- /.row -->
                    </div><!-- /.page-content -->
                </div>
            </div><!-- /.main-content -->
            <!--END MAIN WRAPPER -->
            <script type="text/javascript">
                $('#item_code').multiselect({

                    columns: 1, // how many columns should be use to show options
                    search: true, // include option search box
                    texts: {
                        placeholder: '-- Select Distributer --', // text to use in dummy input
                    },
                    selectAll: false
                });
            </script>
            <?php require_once 'includes/footer.php'; ?>    
        </div>
    </body>
</html>

