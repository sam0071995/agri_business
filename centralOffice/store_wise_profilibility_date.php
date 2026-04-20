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
                                <h3 class="page-header">STORE WISE PROFITIBILITY - <b class="red">DATE</b></h3>
                                <form name="myForm"  role="form" action="" method="POST" enctype="multipart/form-data">
                                    <div class="form-group" id="c_n_password_c">
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
                                        <div class="col-sm-3">
                                            <b>From Date </b>
                                            <div class="input-group">
                                                <input class="form-control date-picker" id="date_1-" name="date_1" type="text" value="<?php
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
                                            <b>To Date </b>
                                            <div class="input-group">
                                                <input class="form-control date-picker" id="date_2" name="date_2" type="text" value="<?php
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
                                                <th>Opening</th>
                                                <th>Purchase</th>
                                                <th>Return Purchase</th>
                                                <th>Frieght(Expenses)</th>
                                                <th>Sales</th>
                                                <th>Return Sales</th>
                                                <th>Current Stock</th>
                                                <th>Profit/Loss</th>
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
                                                $Retailer_id = $_POST['Retailer_id'];

                                                $date_1 = $_POST['date_1'];
                                                $date_2 = $_POST['date_2'];

                                                $date_1 = date("Y-m-d", strtotime($date_1));
                                                $date_2 = date("Y-m-d", strtotime($date_2));

                                                $previous_date = date('Y-m-d', strtotime('-1 day', strtotime($date_1)));
                                                $next_date = date('Y-m-d', strtotime('+1 day', strtotime($date_2)));

                                                $products = getRetailerPurchaseforProfibilityDate($Retailer_id, $company_id_in, $date_1, $date_2);
                                                $index = 1;
                                                $final_sale_price = 0;
                                                $final_purchase_price = 0;
                                                foreach ($products as $product) {
                                                    $profit_loss = 0;
                                                    $profit_loss_per = 0;
                                                    $store_opening_stock = getBackendRetailerStockTInwardValueRetaikerMasterDate($product->retailer_id);
                                                    echo $previous_purchase = getRetailerPurchaseforProfibilityDateOpening($Retailer_id, $company_id_in, '2020-01-01', $previous_date);
                                                    exit;
                                                    if (!isset($previous_purchase)) {
                                                        $previous_purchase = 0;
                                                    }
                                                    $previous_purchase = $store_opening_stock + $previous_purchase;

                                                    $purchase = numberDecimal($product->purchase);

                                                    $previous_return_purchase = getRetailerReturnPurchaseProfibilityDate($product->retailer_id, '2020-01-01', $previous_date);
                                                    if (!isset($previous_return_purchase)) {
                                                        $previous_return_purchase = 0;
                                                    }

                                                    $return_purchase = getRetailerReturnPurchaseProfibilityDate($product->retailer_id, $date_1, $date_2);
                                                    if (!isset($return_purchase)) {
                                                        $return_purchase = 0;
                                                    }

                                                    $previous_expense = getApprovedExpensesByDateCountAsOn($product->retailer_id, '2020-01-01', $previous_date);
                                                    if (!isset($previous_expense)) {
                                                        $previous_expense = 0;
                                                    }
                                                    $expense = getApprovedExpensesByDateCountAsOn($product->retailer_id, $date_1, $date_2);
                                                    if (!isset($expense)) {
                                                        $expense = 0;
                                                    }

                                                    $previous_sales = getProductSalesBasicAmtByRetailerTempTable('2020-01-01', $previous_date, $product->retailer_id, $company_id_in);
                                                    if (!isset($previous_sales)) {
                                                        $previous_sales = 0;
                                                    }
                                                    $sales = getProductSalesBasicAmtByRetailerTempTable($date_1, $date_2, $product->retailer_id, $company_id_in);
                                                    if (!isset($sales)) {
                                                        $sales = 0;
                                                    }

                                                    $previous_return_sales = getRetailerReturnSalesProfibilityDate($product->retailer_id, '2020-01-01', $previous_date);
                                                    if (!isset($previous_return_sales)) {
                                                        $previous_return_sales = 0;
                                                    }
                                                    $return_sales = getRetailerReturnSalesProfibilityDate($product->retailer_id, $date_1, $date_2);
                                                    if (!isset($return_sales)) {
                                                        $return_sales = 0;
                                                    }

                                                    $opening_stock = $previous_purchase - $previous_return_purchase - $previous_expense - $previous_sales + $previous_return_sales;
                                                    $current_stock = $opening_stock + $purchase - $return_purchase - $expense - $sales + $return_sales;

//                                                    $current_stock = getStoreCurrentFreestockValue($product->retailer_id);
//                                                    $profit = numberDecimal(($sales - $return_sales) - ($purchase - $return_purchase) + ($expense - $freeStock));
                                                    $profit = numberDecimal(
                                                            ($sales - $return_sales)                           // Net Sales
                                                            - (
                                                            ($purchase - $return_purchase) + $expense      // Net Purchase + Freight
                                                            - $current_stock                               // Remove unsold stock value
                                                            + $opening_stock                               // Add opening stock (already used up)
                                                            )
                                                    );
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $index; ?></td>
                                                        <td><?php echo getRetailerNameById($product->retailer_id); ?></td>
                                                        <td><?php echo IND_money_format($opening_stock); ?></td>
                                                        <td><?php echo IND_money_format($purchase); ?></td>
                                                        <td><?php echo IND_money_format($return_purchase); ?></td>
                                                        <td><?php echo IND_money_format($expense); ?></td>
                                                        <td><?php echo IND_money_format($sales); ?></td>
                                                        <td><?php echo IND_money_format($return_sales); ?></td>
                                                        <td><?php echo IND_money_format($current_stock); ?></td>
                                                        <td><?php echo IND_money_format($profit); ?></td>
                                                    </tr>
                                                    <?php
                                                    $index++;
                                                }
                                            }
                                            ?>
                                        </tbody>
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

