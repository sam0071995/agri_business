<?php
require_once 'includes/session.php';
require_once 'includes/common_function.php';

//$retailer_id = 1;
$status = 1;
$product_id = '';
$product_title = '';
$product_category = '';
$general_category = '';
$short_description = '';
$feature_description = '';
$remarks = '';
$description = '';
$btn_name = "Submit";
$item_code = '';
if (isset($_POST['item_code'])) {
    $item_code = $_POST['item_code'];
}
$filter_item = '';
if (isset($_POST['filter_by'])) {
    $filter_item = $_POST['filter_by'];
}
$retailer_id = "";
if (isset($_POST['Retailer_id'])) {
    $retailer_id = $_POST['Retailer_id'];
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
                                <h3 class="page-header">Retailer| ITEM WISE PROFITIBILITY.</h3>
                                <form name="myForm"  role="form" action="" method="POST" enctype="multipart/form-data">
                                    <div class="form-group" id="c_n_password_c">
                                        <div class="col-sm-3">
                                            <b>From Sale Date :</b>
                                            <div class="input-group">
                                                <input class="form-control date-picker" id="id-" name="date_1" type="text" value="<?php
                                                if (isset($_POST['date_1'])) {
                                                    echo $_POST['date_1'];
                                                } else {
                                                    echo date('d-m-Y');
                                                }
                                                ?>" data-date-format="dd-mm-yyyy" />
                                                <span class="input-group-addon">
                                                    <i class="fa fa-calendar bigger-110"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <b>To Sale Date :</b>
                                            <div class="input-group">
                                                <input class="form-control date-picker" id="id-" name="date_2" type="text" value="<?php
                                                if (isset($_POST['date_2'])) {
                                                    echo $_POST['date_2'];
                                                } else {
                                                    echo date('d-m-Y');
                                                }
                                                ?>" data-date-format="dd-mm-yyyy" />
                                                <span class="input-group-addon">
                                                    <i class="fa fa-calendar bigger-110"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <b>Retailer :</b>

                                            <div class="input-group">
                                                <select class="form-field-select-2 form-control chosen-select" name="Retailer_id" required="required">
                                                    <option value="">--Select Retailer--</option>
                                                    <option value="All" <?php
                                                    if ($retailer_id == "All") {
                                                        echo "selected='selected'";
                                                    }
                                                    ?>>All</option>
                                                            <?php foreach (getActiveRetailerDetails($company_id_in) as $active_sellers) { ?>
                                                        <option value="<?php echo $active_sellers->id; ?>" <?php
                                                        if ($active_sellers->id == $retailer_id) {
                                                            echo "selected='selected'";
                                                        }
                                                        ?>><?php echo $active_sellers->name; ?><?php
                                                                    if ($active_sellers->status == 0) {
                                                                        echo '<b class="red"> [Clossed]</b>';
                                                                    }
                                                                    ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <b></b>
                                        <button type="submit" name="submit" class="btn btn-info">
                                            <i class="ace-icon fa fa-check bigger-110"></i>
                                            Filter
                                        </button>                                        
                                    </div>
                            </div>
                            </form>

                            <?php if (isset($_GET['success'])) { ?>
                                <div class="alert alert-block alert-success">
                                    <button type="button" class="close" data-dismiss="alert">
                                        <i class="ace-icon fa fa-times"></i>
                                    </button>

                                    <i class="ace-icon fa fa-check green form-error-msg"></i>
                                    <?php echo "Product Updated Successfully"; ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">

                            <div class="modal-body">
                                <div class="row clearfix">
                                    <div class="pull-right tableTools-container"></div>
                                </div>
                                <div>
                                    <table id="dynamic-table" class="table table-bordered table-hover">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>#</th>
                                                <th>Distributer Name</th>
                                                <th>Category</th>
                                                <th>Item Name</th>
                                                <th>Batch</th>
                                                <th>Invoice No</th>
                                                <th>UNIT</th>
                                                <th>Qty</th>
                                                <th>Purchase Basic Rate</th>
                                                <th>Sale Basic Rate</th>
                                                <th>Purchase GST Rate</th>
                                                <th>Total Purchase Basic Value</th>
                                                <th>Total Sale Value</th>
                                                <th>Profit/Loss (Rs.)</th>
                                                <th>Profit/Loss (%)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $class_f = "";
                                            $final_sale_price = 0;
                                            $final_purchase_price = 0;
                                            $final_profit_loss = 0;
                                            $final_profit_loss_per = 0;
                                            if (isset($_POST['submit'])) {
                                                $f_date = date("Y-m-d", strtotime($_POST['date_1']));
                                                $l_date = date("Y-m-d", strtotime($_POST['date_2']));
                                                $Retailer_id = $_POST['Retailer_id'];
                                                $products = getProductSalesByRetailerTempTableDayWiseData($f_date, $l_date, $Retailer_id, $company_id_in);
                                                $index = 1;
                                                $final_sale_price = 0;
                                                $final_purchase_price = 0;
                                                foreach ($products as $product) {
                                                    $profit_loss = 0;
                                                    $profit_loss_per = 0;
                                                    $item_detail = getStockCountByItemCodeAndRetailerId($product->retailer_id, $product->item_code);
                                                    $purchase_price_detail = getItemPurchasePriceDetails($product->retailer_id, $product->item_code, $product->batch_no);
                                                    $total_price_sale = $product->basic;
                                                    $sale_gst_rate = $product->sgst + $product->cgst;
                                                    $total_price_purchase = $product->qty * $purchase_price_detail->purchase_basic;
                                                    $final_sale_price = $final_sale_price + $total_price_sale;
                                                    $final_purchase_price = $final_purchase_price + $total_price_purchase;
                                                    $profit_loss = numberDecimal($total_price_sale - $total_price_purchase);
                                                    if ($profit_loss < 0) {
                                                        $class = "red";
                                                    } else {
                                                        $class = "green";
                                                    }
//                                                    echo $profit_loss;
//                                                    echo '<br/>';
//                                                    echo $total_price_purchase;
//                                                    exit;
                                                    if (isset($purchase_price_detail->total)) {
                                                        if ($purchase_price_detail->total > 0) {
                                                            $profit_loss_1 = (numberDecimal($profit_loss) * 100);
                                                            $profit_loss_per = numberDecimal($profit_loss_1 / $total_price_purchase);
//                                                            $profit_loss_per = numberDecimal($profit_loss_div, 2);
                                                        }
                                                    }
//                                                    echo $total_price_sale;
//                                                    echo $total_price_purchase;
//                                                    exit;
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $index; ?></td>
                                                        <td><?php echo getRetailerNameById($product->retailer_id); ?></td>
                                                        <td><?php echo getCategoryNameById($item_detail->main_category_id); ?></td>
                                                        <td><?php echo $product->item_name; ?></td>
                                                        <td><?php echo $product->batch_no; ?></td>
                                                        <td><?php echo $product->order_no; ?></td>
                                                        <td><?php echo getItemUNITByItemCode($product->item_code); ?></td>
                                                        <td><?php echo $product->qty; ?></td>
                                                        <td><?php echo $purchase_price_detail->purchase_basic; ?></td>
                                                        <td><?php echo numberDecimal($product->basic / $product->qty); ?></td>
                                                        <td><?php echo $purchase_price_detail->gst; ?></td>
                                                        <td><?php echo numberDecimal($total_price_purchase); ?></td>
                                                        <td><?php echo numberDecimal($total_price_sale); ?></td>
                                                        <td class="<?php echo $class; ?>"><?php echo numberDecimal($profit_loss); ?></td>
                                                        <td class="<?php echo $class; ?>"><?php echo numberDecimal($profit_loss_per); ?></td>
                                                    </tr>
                                                    <?php
                                                    $index++;
                                                }
                                            }
                                            $final_profit_loss = number_format($final_sale_price - $final_purchase_price);
                                            if ($final_profit_loss < 0) {
                                                $class_f = "red";
                                            } else {
                                                $class_f = "green";
                                            }
                                            if ($final_profit_loss != 0) {
                                                $final_profit_loss_per = numberDecimal(numberDecimal($final_profit_loss) * 100 / $final_purchase_price);
                                            }
                                            ?>
                                        </tbody>
                                        <tr>
                                            <td colspan="11" class="right bolder"></td>
                                            <td colspan="1" class="right bolder">Total</td>
                                            <td><?php echo numberDecimal($final_purchase_price); ?></td>
                                            <td><?php echo numberDecimal($final_sale_price); ?></td>
                                            <td class="<?php echo $class_f; ?>"><?php echo numberDecimal($final_profit_loss); ?></td>
                                            <td class="<?php echo $class_f; ?>"><?php echo numberDecimal($final_profit_loss_per); ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div><!-- /.row -->
                </div><!-- /.page-content -->
            </div>
        </div><!-- /.main-content -->
        <!--END MAIN WRAPPER -->
        <?php require_once 'includes/footer.php'; ?>    
    </div>
</body>
</html>

