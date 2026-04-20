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
                                <h3 class="page-header">Inventory Stock Report by Date</h3>

                                <form class="form-inline center" action="" method="POST">
                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-xs-14">
                                                <b>Date :</b>
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
                                                <button class="btn btn-info" type="submit" name="show" value="show">
                                                    <i class="ace-icon fa fa-check bigger-110"></i>
                                                    Show
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <hr/>
                        <div class="row">
                            <div class="col-xs-12">
                                <h3 class="page-header">Inventory Stock Report For Date : <?php
                                    if (isset($_POST['date_1'])) {
                                        echo " " . date("d M Y", strtotime($_POST['date_1']));
                                    }
                                    ?></h3>
                                <div class="modal-body">
                                    <div class="row clearfix">
                                        <div class="pull-right tableTools-container"></div>
                                    </div>
                                    <div>
                                        <?php
                                        if (isset($_POST['show'])) {
                                            $date_1 = date("Y-m-d", strtotime($_POST['date_1']));
                                            ?>
                                            <table id="dynamic-table" class="table table-bordered table-hover">
                                                <thead class="thead-dark">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Distributer Name</th>
                                                        <th>Item Name</th>
                                                        <th>HSN Code</th>
                                                        <th>UOM</th>
                                                        <th>MainCat</th>   
                                                        <th>SubCat</th>   
                                                        <th>Opening</th>   
                                                        <th>Recieved</th>   
                                                        <th>Issued</th>   
                                                        <th>Clossing</th>   
                                                        <th>GST</th>   
                                                        <th>BasicRate</th>   
                                                        <th>Status</th>   
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $index = 1;
                                                    $products = getRetailerItemByRetailerId($retailer_id);
                                                    foreach ($products as $product) {
                                                        $status = "";
                                                        if ($product->status == 1) {
                                                            $status .= '<span class="badge badge-success">Active</span>';
                                                        } else {
                                                            $status .= '<span class="badge badge-danger">In-Active</span>';
                                                        }
                                                        $item_code = $product->item_code;
                                                        $InwardCountBackend = getBackendRetailerStockTInward($retailer_id, $item_code, $date_1);
                                                        if (empty($InwardCountBackend)) {
                                                            $InwardCountBackend = 0;
                                                        }

                                                        $InwardCount = getRetailerStockTInward($retailer_id, $item_code, $date_1);
                                                        if (empty($InwardCount)) {
                                                            $InwardCount = 0;
                                                        }
                                                        $InwardCount = $InwardCount + $InwardCountBackend;
                                                        $salesCount = getRetailerSalesDetail($retailer_id, $item_code, $date_1);
                                                        if (empty($salesCount)) {
                                                            $salesCount = 0;
                                                        }

                                                        $transferCount = getRetailerStockTransfer($retailer_id, $item_code, $date_1);
                                                        if (empty($transferCount)) {
                                                            $transferCount = 0;
                                                        }

                                                        $transferPurchaseCount = getRetailerTransferPurchare($retailer_id, $item_code, $date_1);
                                                        if (empty($transferPurchaseCount)) {
                                                            $transferPurchaseCount = 0;
                                                        }
                                                        $opening_stock = $product->opening_stock + $InwardCount - $salesCount - $transferCount - $transferPurchaseCount;

                                                        $inwardOnDate = getRetailerStockTInwardForDate($retailer_id, $item_code, $date_1);
                                                        $receive_stock = $inwardOnDate;
                                                        if (empty($receive_stock)) {
                                                            $receive_stock = 0;
                                                        }

                                                        $salesCountonDate = getRetailerSalesDetailonDate($retailer_id, $item_code, $date_1);
                                                        $transferCountonDate = getRetailerStockTransferonDate($retailer_id, $item_code, $date_1);
                                                        $transferPurchaseCountonDate = getRetailerTransferPurchareonDate($retailer_id, $item_code, $date_1);
                                                        $issued_stock = $salesCountonDate + $transferCountonDate + $transferPurchaseCountonDate;
                                                        if (empty($issued_stock)) {
                                                            $issued_stock = 0;
                                                        }
                                                        $current_stock = $opening_stock + $receive_stock - $issued_stock;
                                                        if (empty($current_stock)) {
                                                            $current_stock = 0;
                                                        }
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $index; ?></td>
                                                            <td><?php echo getRetailerNameById($product->retailer_id); ?></td>
                                                            <td><?php echo $product->item_desc; ?></td>
                                                            <td><?php echo $product->hsn_code; ?></td>
                                                            <td><?php echo $product->uom; ?></td>
                                                            <td><b class="green"><?php echo getCategoryNameById($product->main_category_id); ?></b></td>
                                                            <td><b class="blue"><?php echo getCategoryNameById($product->sub_category_id); ?></b></td>
                                                            <td>
                                                                <b class="green"><?php echo $opening_stock; ?></b><br/>
                                                            </td>
                                                            <td>
                                                                <b class="cyan"><?php echo $receive_stock; ?></b><br/>
                                                            </td>
                                                            <td>
                                                                <b class="blue"><?php echo $issued_stock; ?></b><br/>
                                                            </td>
                                                            <td>
                                                                <b class="red"><?php echo $current_stock; ?></b><br/>
                                                            </td>
                                                            <td>
                                                                <b class="green"><?php echo $product->igst_rate; ?></b>
                                                            </td>
                                                            <td>
                                                                <b class="green"><?php echo $product->basic_price; ?></b>
                                                            </td>
                                                            <td><?php echo $status; ?></td>
                                                        </tr>
                                                        <?php
                                                        $index++;
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                            </table>
                                            <?php
                                        }
                                        ?>
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

