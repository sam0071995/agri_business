<?php
echo 'This option is not availble';
exit;

error_reporting(0);
require_once 'includes/session.php';
require_once 'includes/common_function.php';
if (isset($_POST['retailer_id'])) {
    $retailer_id = $_POST['retailer_id'];
} else {
    $retailer_id = "";
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
                                            $msg = "Order Not Has been Rejected.";
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
                                        <?php echo "Successfully Deleted."; ?>
                                    </div>
                                <?php } ?>
                                <div class="page-header">
                                    <div class="widget-box">
                                        <div class="widget-header">
                                            <h4 class="widget-title">Retailer | Delete Inward.</h4>
                                        </div>
                                        <div class="widget-body">
                                            <div class="widget-main">
                                                <form class="form-inline center" action="" method="POST">
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <div class="col-xs-14">
                                                                <b>Select Retailer :</b>
                                                                <div class="input-group">
                                                                    <select class="form-control" name="retailer_id" required="required">
                                                                        <option value="">--select--</option>
                                                                        <?php foreach (getActiveRetailerDetails($company_id_in) as $retailer) { ?>
                                                                            <option value="<?php echo $retailer->id; ?>" <?php
                                                                            if ($retailer_id == $retailer->id) {
                                                                                echo 'selected';
                                                                            }
                                                                            ?>><?php echo $retailer->full_name; ?></option>
                                                                                <?php } ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <button class="btn btn-info" type="submit" name="show" value="show">
                                                            <i class="ace-icon fa fa-check bigger-110"></i>
                                                            Show
                                                        </button>
                                                    </div>
                                            </div>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row clearfix">
                                                <div class="pull-right tableTools-container"></div>
                                            </div>
                                            <div>
                                                <table id="dynamic-table" class="table table-bordered table-hover">
                                                    <thead class="thead-dark">
                                                        <tr><tr>
                                                            <th>#</th>
                                                            <th>Retailer Name</th>
                                                            <th>From Retailer Name</th>
                                                            <th>Supplier Detail</th>
                                                            <th>Purchase Order No</th>
                                                            <th>Item Detail</th>
                                                            <th>Qty</th>
                                                            <th>Inward Date</th>
                                                            <th></th>
                                                        </tr>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        if (isset($_POST['show'])) {
                                                            if (!isset($_POST['retailer_id']) && empty($_POST['retailer_id'])) {
                                                                echo 'Please Select Retailer.';
                                                                exit;
                                                            }
                                                            $retailer_id = $_POST['retailer_id'];
                                                            $products = getInwardedPoHistory($retailer_id);

                                                            $index = 1;
                                                            if (count($products) > 0) {
                                                                foreach ($products as $product) {
                                                                    $grn_detail = getInwardedPoByGrnId($retailer_id, $product->grn_id);
                                                                    ?> 
                                                                    <tr>
                                                                        <td><?php echo $index; ?></td>
                                                                        <td><?php echo getRetailerNameById($grn_detail->retailer_id); ?></td>
                                                                        <td><?php
                                                                            if ($grn_detail->dispatch_retailer_id == 0) {
                                                                                echo 'Against PO';
                                                                            } else {
                                                                                echo getRetailerNameById($grn_detail->dispatch_retailer_id);
                                                                            }
                                                                            ?></td>
                                                                        <td>
                                                                            <b>Name : </b><?php echo $grn_detail->supplier_id; ?><br/>
                                                                            <b>Address :</b> <?php echo $grn_detail->supplier_address; ?>
                                                                        </td>
                                                                        <td><?php echo $grn_detail->po_no; ?></td> 
                                                                        <td>
                                                                            <b>Name : </b><?php echo getItemNameByItemCode($product->item_desc); ?><br/>
                                                                            <b>BatchNo :</b> <?php echo $product->batch_no; ?><br/>
                                                                            <b>Expiry : </b><?php echo $product->expire_date; ?><br/>
                                                                            <b>Manufacturer : </b><?php echo $product->manufacturing_date; ?><br/>
                                                                        </td>
                                                                        <td><?php echo $product->qty; ?></td>
                                                                        <td><?php echo $product->inwd_datetime; ?></td>
                                                                        <td>
                                                                            <?php
                                                                            if ($update_block_date < date('Y-m-d', strtotime($product->inwd_datetime))) {
                                                                                ?>
                                                                                <button class="btn btn-danger delete_inward_no" id="<?php echo base64_encode($product->id); ?>">Delete</button>
                                                                                <?php
                                                                            } else {
                                                                                echo '<b class="red">Inward Can not Delete before Lock Date.</b>';
                                                                            }
                                                                            ?>
                                                                        </td>
                                                                    </tr>
                                                                    <?php
                                                                    $index++;
                                                                }
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
                    <script type="text/javascript">

                    </script> 
                    <?php require_once 'includes/footer.php'; ?>    

                </div>
                </body>
                </html>

