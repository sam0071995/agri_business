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
                                <h3 class="page-header"><?php echo getRetailerNameById($retailer_id); ?> | Batch Wise Current Stock Details.</h3>
                                <form class="form-inline center" action="" method="POST">
                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-xs-14">
                                                <b>For Date :</b>
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
                                        </div>
                                        <div class="form-group">
                                            <div class="col-xs-14">
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
                                <div class="modal-body">
                                    <div class="row clearfix">
                                        <div class="pull-right tableTools-container"></div>
                                    </div>
                                    <div>
                                        <table id="dynamic-table" class="table table-bordered table-hover">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Store Name</th>
                                                    <th>Supplier Name</th>
                                                    <th>Item Name</th>
                                                    <th>Serial No</th>
                                                    <th>Batch No</th>
                                                    <th>Manufacturing Date</th>
                                                    <th>Expired Date</th>
                                                    <th></th>   
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if (isset($_POST['show'])) {
                                                    $date_1 = date("Y-m-d", strtotime($_POST['date_1']));
                                                    $index = 1;
                                                    $products = getFreeRetailerSrByitemAddedDate($retailer_id, $date_1);
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
                                                            <td>
                                                                <?php
                                                                if ($product->vendor_id == 0) {
                                                                    echo 'NA';
                                                                } else {
                                                                    echo getVendorNameById($product->vendor_id);
                                                                }
                                                                ?>
                                                            </td>
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

                                                            <td><?php echo $status; ?></td>
                                                        </tr>
                                                        <?php
                                                        $index++;
                                                    }

                                                    $productsAray = getBlockedRetailerSrByDate($retailer_id, $date_1);
                                                    foreach ($productsAray as $productArray) {
                                                        $status = "";
                                                        if ($productArray->status == 0) {
                                                            $status .= '<span class="badge badge-success">Free</span>';
                                                        } else {
                                                            $status .= '<span class="badge badge-danger">Ussed</span>';
                                                        }
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $index; ?></td>
                                                            <td><?php echo getRetailerNameById($productArray->retailer_id); ?></td>
                                                            <td>
                                                                <?php
                                                                if ($product->vendor_id == 0) {
                                                                    echo 'NA';
                                                                } else {
                                                                    echo getVendorNameById($product->vendor_id);
                                                                }
                                                                ?>
                                                            </td>
                                                            <td><?php echo $productArray->item_desc; ?></td>
                                                            <td><?php echo $productArray->serial_number; ?></td>
                                                            <td><?php echo $productArray->batch_no; ?></td>
                                                            <td><?php
                                                                if (!empty($productArray->manufacturing_date)) {
                                                                    echo date("d M Y", strtotime($productArray->manufacturing_date));
                                                                }
                                                                ?></td>
                                                            <td class=""><?php
                                                                if (!empty($productArray->expire_date)) {
                                                                    echo date("d M Y", strtotime($productArray->expire_date));
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