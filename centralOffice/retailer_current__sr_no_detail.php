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
$retailer_id = '';
$item_code = '';
$btn_name = "Submit";

if (isset($_POST['retailer_id'])) {
    $retailer_id = $_POST['retailer_id'];
    $item_code = $_POST['item_code'];
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
                                <h3 class="page-header">Retailer | Inventory Batch Details.</h3>

                                <div class="page-header">
                                    <form name="myForm"  role="form" action="" method="POST" enctype="multipart/form-data">
                                        <div class="col-sm-3">
                                            <select class="form-field-select-2 form-control chosen-select" name="retailer_id" required="required">
                                                <option value="">--Select Retailer--</option>
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
                                                <?php foreach (getActiveItemsList() as $active_item) { ?>
                                                    <option value="<?php echo $active_item->item_code; ?>" <?php
                                                    if ($active_item->item_code == $item_code) {
                                                        echo "selected='selected'";
                                                    }
                                                    ?>><?php echo $active_item->item_desc; ?></option>
                                                        <?php } ?>
                                            </select>
                                        </div>
                                        <div class="col-sm-3">
                                            <input class="form-control" placeholder="Enter Batch No" type="text" name="batch_no" />
                                        </div>
                                        <div class="row float-sm-left">
                                            <button name="submit" class="btn btn-primary float-sm-left">Show</button>
                                        </div>
                                    </form>
                                </div>

                                <h3 class="page-header"><?php
                                    if (!empty($retailer_id)) {
                                        echo getRetailerNameById($retailer_id);
                                    }
                                    ?> | Batch Stock Details.</h3>

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
                                                    <th>Purchase Basic</th>
                                                    <th>GST</th>
                                                    <th>Total</th>
                                                    <th>Expired Date</th>
                                                    <th>Inward Date</th>
                                                    <th>Transfer Ref No</th>
                                                    <th>PO No</th>
                                                    <th>PO Date</th>
                                                    <th>Order No</th>
                                                    <th>Blocked For</th>   
                                                    <th>Blocked Date</th>   
                                                    <th>Status</th>   
                                                    <th>Remarks</th>   
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if (isset($_POST['submit'])) {
                                                    $retailer_id = $_POST['retailer_id'];
                                                    $item_code = $_POST['item_code'];
                                                    $batch_no = $_POST['batch_no'];
                                                    $index = 1;
                                                    $products = getAllItemsSrByitemBatch($retailer_id, $item_code, $batch_no);
                                                    foreach ($products as $product) {
                                                        $status = "";
                                                        if ($product->status == 0) {
                                                            $status .= '<span class="badge badge-success">Free</span>';
                                                        } else if ($product->status == 1) {
                                                            $status .= '<span class="badge badge-danger">Ussed</span>';
                                                        } else if ($product->status == 7) {
                                                            $status .= '<span class="badge badge-danger">Rejected OR Transfered Approved OR Inward Pending</span>';
                                                        } else if ($product->status == 8) {
                                                            $status .= '<span class="badge badge-danger">Transfered Approve Pending OR Inward Pending</span>';
                                                        } else {
                                                            $status .= '<span class="badge badge-danger">Hide By IT</span>';
                                                        }
                                                        $grnData = getInwardedOrderSattusDetail($product->retailer_id, $product->grn_id, $product->item_code);
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $index; ?></td>
                                                            <td><?php echo getRetailerNameById($product->retailer_id); ?></td>
                                                            <td><?php echo $product->item_desc; ?></td>
                                                            <td><?php echo $product->serial_number; ?></td>
                                                            <td><?php echo $product->batch_no; ?></td>
                                                            <td><?php echo $product->purchase_basic; ?></td>
                                                            <td><?php echo $product->gst; ?></td>
                                                            <td><?php echo $product->total; ?></td>
                                                            <td><?php echo date("d M Y", strtotime($product->expire_date)); ?></td>
                                                            <td><?php
                                                                if (isset($grnData->retailer_inwd_date)) {
                                                                    echo date("d M Y", strtotime($grnData->retailer_inwd_date));
                                                                }
                                                                ?>
                                                            </td>
                                                            <td><?php echo $product->tran_ref_no; ?></td>
                                                            <td><?php echo $product->po_no; ?></td>
                                                            <td><?php echo $product->po_date; ?></td>
                                                            <td><?php echo $product->order_no; ?></td>
                                                            <td><?php
                                                                if ($product->block_for != '0') {
                                                                    echo getRetailerNameById($product->block_for);
                                                                }
                                                                ?></td>
                                                            <td><?php
                                                                if (!empty($product->block_datetime)) {
                                                                    echo $product->block_datetime;
                                                                }
                                                                ?></td>
                                                            <td><?php echo $status; ?></td>
                                                            <td><?php echo $product->remarks; ?></td>
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

