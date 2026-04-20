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
                                <h3 class="page-header">Retailer Wise | Free Current Batch Stock Valuation Details.</h3>
                                <form name="myForm"  role="form" action="" method="POST" enctype="multipart/form-data">
                                    <div class="form-group" id="c_n_password_c">
                                        <div class="col-sm-3">
                                            <select class="form-field-select-2 form-control chosen-select" name="Retailer_id" required="required">
                                                <option value="">--Select Retailer--</option>
                                                <option value="ALL" <?php
                                                if ($retailer_id == "ALL") {
                                                    echo 'selected="selected"';
                                                }
                                                ?>>All Retailers</option>
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
                                        <div class="col-sm-3">
                                            <select class="form-field-select-2 form-control chosen-select" name="item_code" required="required">
                                                <option value="">--Select item--</option>
                                                <option value="ALL" <?php
                                                if ($item_code == "ALL") {
                                                    echo 'selected="selected"';
                                                }
                                                ?>>All Items</option>
                                                        <?php foreach (getActiveItemsList() as $active_item) { ?>
                                                    <option value="<?php echo $active_item->item_code; ?>" <?php
                                                    if ($active_item->item_code == $item_code) {
                                                        echo "selected='selected'";
                                                    }
                                                    ?>><?php echo $active_item->item_desc; ?></option>
                                                        <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
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
                                                <th>Item Name</th>
                                                <th>Main Cat</th>
                                                <th>Sub Cat</th>
                                                <th>UOM</th>
                                                <th>Purchase Basic</th>
                                                <th>Purchase GST Rate</th>
                                                <th>Purchase Total</th>
                                                <th>Batch No</th>
                                                <th>Qty</th>
                                                <th>Purchase Basic Value</th>
                                                <th>No Of Days in Store</th>
                                                <th>Manufacturing Date</th>
                                                <th class="green">Expired Date</th>
                                                <th></th>   
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (isset($_POST['submit'])) {
                                                $item_code = $_POST['item_code'];
                                                $products = getExpiredItems($retailer_id, $item_code);
                                                $index = 1;
                                                foreach ($products as $product) {
                                                    $status = "";
                                                    if ($product->status == 0) {
                                                        $status .= '<span class="badge badge-success">Free</span>';
                                                    } else {
                                                        $status .= '<span class="badge badge-danger">Ussed</span>';
                                                    }
                                                    $item_detail = getStockCountByItemCodeAndRetailerId($product->retailer_id, $product->item_code);
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $index; ?></td>
                                                        <td><?php echo getRetailerNameById($product->retailer_id); ?></td>
                                                        <td><?php echo $product->item_desc; ?></td>
                                                        <td><?php echo getCategoryNameById($item_detail->main_category_id); ?></td>
                                                        <td><?php echo getCategoryNameById($item_detail->sub_category_id); ?></td>
                                                        <td><?php echo $item_detail->uom; ?></td>
                                                        <td><?php echo $product->purchase_basic; ?></td>
                                                        <td><?php echo $product->gst; ?></td>
                                                        <td><?php echo $product->total; ?></td>
                                                        <td><?php echo $product->batch_no; ?></td>
                                                        <td><?php echo $product->count; ?></td>
                                                        <td><?php echo IND_money_format($product->count * $product->purchase_basic); ?></td>
                                                        <td><?php echo getDaysByDate($product->to_date); ?></td>
                                                        <td><?php
                                                            if (!empty($product->manufacturing_date) && $product->manufacturing_date != '1970-01-01') {
                                                                echo date("d M Y", strtotime($product->manufacturing_date));
                                                            }
                                                            ?></td>
                                                        <td class="green"><?php echo date("d M Y", strtotime($product->expire_date)); ?></td>
                                                        <td><?php echo $status; ?></td>
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

