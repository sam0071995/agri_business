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
$filter_item = '';
if (isset($_GET['filter_by'])) {
    $filter_item = $_GET['filter_by'];
}
if (isset($_POST['filter_by'])) {
    $filter_item = $_POST['filter_by'];
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
                                <form class="form-horizontal" id="searchform" name="myForm" role="form" action="" method="POST" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <div class="col-sm-4">
                                            <select name="filter_by">
                                                <option value="">--Filter By--</option>
                                                <option <?php
                                                if ($filter_item == "all") {
                                                    echo 'selected="selected"';
                                                }
                                                ?> value="all">All</option>
                                                <option <?php
                                                if ($filter_item == "10") {
                                                    echo 'selected="selected"';
                                                }
                                                ?> value="10">Within 10 Days</option>
                                                <option <?php
                                                if ($filter_item == "20") {
                                                    echo 'selected="selected"';
                                                }
                                                ?> value="20">Within 10 to 20 Days</option>
                                                <option <?php
                                                if ($filter_item == "30") {
                                                    echo 'selected="selected"';
                                                }
                                                ?> value="30">Within 30 Days</option>
                                                <option <?php
                                                if ($filter_item == "31") {
                                                    echo 'selected="selected"';
                                                }
                                                ?> value="31">After 30 Days</option>
                                                <option <?php
                                                if ($filter_item == "expired") {
                                                    echo 'selected="selected"';
                                                }
                                                ?> value="expired">Expired Items</option>
                                            </select>
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
                                <h3 class="page-header"><?php echo getRetailerNameById($retailer_detail->id); ?> | Current Stock Details.</h3>

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
                                                    <th>UOM</th>
                                                    <th>Basic Price</th>
                                                    <th>GST Rte</th>
                                                    <th>Total Value</th>
                                                    <th>Batch No</th>
                                                    <th>Qty</th>
                                                    <th>Qty Value</th>
                                                    <th>Manufacturing Date</th>
                                                    <th class="">Expired Date</th>
                                                    <th></th>   
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if (isset($_POST['submit'])) {
                                                    $filter_by = $_POST['filter_by'];
                                                    if ($filter_by == '31') {
                                                        $fromDate = date("Y-m-d");
                                                        $fromDate = date('Y-m-d', strtotime($fromDate . ' + 29 days'));
                                                        $to_date = date('Y-m-d', strtotime($date . ' + 100 years'));
                                                        $products = getExpiredItemsSrByDate($retailer_id, $fromDate, $to_date);
                                                    } else if ($filter_by == '10') {
                                                        $fromDate = date("Y-m-d");
                                                        $to_date = date('Y-m-d', strtotime($fromDate . ' + 9 days'));
                                                        $products = getExpiredItemsSrByDate($retailer_id, $fromDate, $to_date);
                                                    } else if ($filter_by == '20') {
                                                        $fromDate = date("Y-m-d");
                                                        $to_date = date('Y-m-d', strtotime($fromDate . ' + 19 days'));
                                                        $products = getExpiredItemsSrByDate($retailer_id, $fromDate, $to_date);
                                                    } else if ($filter_by == '30') {
                                                        $fromDate = date("Y-m-d");
                                                        $to_date = date('Y-m-d', strtotime($fromDate . ' + 29 days'));
                                                        $products = getExpiredItemsSrByDate($retailer_id, $fromDate, $to_date);
                                                    } else if ($filter_by == 'expired') {
                                                        $products = getAlredyExpiredItems($retailer_id);
                                                    } else {
                                                        $products = getExpiredItems($retailer_id);
                                                    }
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
                                                            <td><?php echo $item_detail->uom; ?></td>
                                                            <td><?php echo $item_detail->basic_price; ?></td>
                                                            <td><?php echo $item_detail->igst_rate; ?></td>
                                                            <td><?php echo $item_detail->total; ?></td>
                                                            <td><?php echo $product->batch_no; ?></td>
                                                            <td><?php echo $product->count; ?></td>
                                                            <td><?php echo IND_money_format($product->count * $item_detail->total); ?></td>
                                                            <td><?php
                                                                if (!empty($product->manufacturing_date)) {
                                                                    echo date("d M Y", strtotime($product->manufacturing_date));
                                                                }
                                                                ?></td>
                                                            <td class=""><?php
                                                                if (!empty($product->expire_date)) {
                                                                    echo date("d M Y", strtotime($product->expire_date));
                                                                }
                                                                ?></td>
                                                            <td><?php echo $status; ?></td>
                                                        </tr>
                                                        <?php
                                                        $index++;
                                                    }
                                                }


                                                if (isset($_GET['notification']) && !isset($_POST['submit'])) {
                                                    $filter_by = $_GET['filter_by'];
                                                    if ($filter_by == '31') {
                                                        $fromDate = date("Y-m-d");
                                                        $fromDate = date('Y-m-d', strtotime($fromDate . ' + 29 days'));
                                                        $to_date = date('Y-m-d', strtotime($date . ' + 5 years'));
                                                        $products = getExpiredItemsSrByDate($retailer_id, $fromDate, $to_date);
                                                    } else if ($filter_by == '10') {
                                                        $fromDate = date("Y-m-d");
                                                        $to_date = date('Y-m-d', strtotime($fromDate . ' + 9 days'));
                                                        $products = getExpiredItemsSrByDate($retailer_id, $fromDate, $to_date);
                                                    } else if ($filter_by == '20') {
                                                        $fromDate = date("Y-m-d");
                                                        $to_date = date('Y-m-d', strtotime($fromDate . ' + 19 days'));
                                                        $products = getExpiredItemsSrByDate($retailer_id, $fromDate, $to_date);
                                                    } else if ($filter_by == '30') {
                                                        $fromDate = date("Y-m-d");
                                                        $to_date = date('Y-m-d', strtotime($fromDate . ' + 29 days'));
                                                        $products = getExpiredItemsSrByDate($retailer_id, $fromDate, $to_date);
                                                    } else if ($filter_by == 'expired') {
                                                        $products = getAlredyExpiredItems($retailer_id);
                                                    } else {
                                                        $products = getExpiredItems($retailer_id);
                                                    }
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
                                                            <td><?php echo $item_detail->uom; ?></td>
                                                            <td><?php echo $item_detail->basic_price; ?></td>
                                                            <td><?php echo $item_detail->igst_rate; ?></td>
                                                            <td><?php echo $item_detail->total; ?></td>
                                                            <td><?php echo $product->batch_no; ?></td>
                                                            <td><?php echo $product->count; ?></td>
                                                            <td><?php echo IND_money_format($product->count * $item_detail->total); ?></td>
                                                            <td><?php
                                                                if (!empty($product->manufacturing_date)) {
                                                                    echo date("d M Y", strtotime($product->manufacturing_date));
                                                                }
                                                                ?></td>
                                                            <td class=""><?php
                                                                if (!empty($product->expire_date)) {
                                                                    echo date("d M Y", strtotime($product->expire_date));
                                                                }
                                                                ?></td>
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

