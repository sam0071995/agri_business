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

if (isset($_POST['submit'])) {
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
                                <?php if (isset($_GET['success'])) { ?>
                                    <div class="alert alert-block alert-success">
                                        <button type="button" class="close" data-dismiss="alert">
                                            <i class="ace-icon fa fa-times"></i>
                                        </button>

                                        <i class="ace-icon fa fa-check green form-error-msg"></i>
                                        <?php echo "Product Updated Successfully"; ?>
                                    </div>
                                <?php } ?>
                                <h3 class="page-header"><?php echo getRetailerNameById($retailer_id); ?> | Current Stock Details.</h3>

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
                                                    <th>Serial No</th>
                                                    <th>Batch No</th>
                                                    <th>Manufacturing Date</th>
                                                    <th>Expired Date</th>
                                                    <th>Qty</th>
                                                    <th></th>   
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if (isset($_GET['item'])) {
                                                    $item_code = base64_decode($_GET['item']);
                                                    $index = 1;
                                                    $products = getFreeItemsSrByitemGrp($retailer_id, $item_code);
                                                    foreach ($products as $product) {
                                                        $status = "";
                                                        if ($product->status == 0) {
                                                            $status .= '<span class="badge badge-success">Free</span>';
                                                        } else {
                                                            $status .= '<span class="badge badge-danger">Ussed</span>';
                                                        }
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $index; ?></td>
                                                            <td><?php echo getRetailerNameById($product->retailer_id); ?></td>
                                                            <td><?php echo $product->item_desc; ?></td>
                                                            <td><?php echo $product->serial_number; ?></td>
                                                            <td><?php echo $product->batch_no; ?></td>
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
                                                            <td><?php echo $product->count; ?></td>
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

