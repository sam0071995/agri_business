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
                                <h3 class="page-header">STORE WISE PROFITIBILITY.</h3>
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
                                                $products = getRetailerPurchaseforProfibility($Retailer_id, $company_id_in);
                                                $index = 1;
                                                $final_sale_price = 0;
                                                $final_purchase_price = 0;
                                                foreach ($products as $product) {
                                                    $profit_loss = 0;
                                                    $profit_loss_per = 0;
                                                    $purchase = numberDecimal($product->purchase);
                                                    
                                                    $opening_stock = getBackendRetailerStockTInwardValueRetaikerMaster($product->retailer_id);
                                                    $return_purchase = getRetailerReturnPurchaseProfibility($product->retailer_id);
                                                    $expense = getRetailerExpenssProfibility($product->retailer_id);
                                                    $sales = getRetailerSalesProfibility($product->retailer_id);
                                                    $return_sales = getRetailerReturnSalesProfibility($product->retailer_id);
                                                    $current_stock = getStoreCurrentFreestockValue($product->retailer_id);

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

