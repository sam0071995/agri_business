<?php
require_once 'includes/session.php';
require_once 'includes/common_function.php';
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
                                        <?php echo "Successfully Rejected."; ?>
                                    </div>
                                <?php } ?>
                                <div class="page-header">
                                    <div class="widget-box">
                                        <div class="widget-header">
                                            <h4 class="widget-title">Retailer | Reject Order.</h4>
                                        </div>
                                        <div class="widget-body">
                                            <div class="widget-main">
                                                <form class="form-inline center" action="" method="POST">
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <div class="col-xs-14">
                                                                <b>Order Number :</b>
                                                                <div class="input-group">
                                                                    <input class="form-control" name="order_no" required="required" type="text" value="<?php
                                                                    if (isset($_POST['order_no'])) {
                                                                        echo $_POST['order_no'];
                                                                    }
                                                                    ?>" />
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
                                                            <th>Order No</th>
                                                            <th>Item Name</th>
                                                            <th></th>
                                                            <th></th>
                                                            <th>Remarks</th>
                                                            <th></th>
                                                        </tr>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        if (isset($_POST['show'])) {
                                                            if (!isset($_POST['order_no']) && empty($_POST['order_no'])) {
                                                                echo 'Enter Order Number.';
                                                                exit;
                                                            }
                                                            $order_no = $_POST['order_no'];
                                                            $products = getProductSalesByRetailerTempTableByOrderNo($order_no, $company_id_in);
                                                            $index = 1;
                                                            if (count($products) > 0) {
                                                                foreach ($products as $product) {
                                                                    if ($product->payment_type == 0) {
                                                                        $payment_type = "CASH";
                                                                    } else if ($product->payment_type == 1) {
                                                                        $payment_type = "ONLINE";
                                                                    } else {
                                                                        $payment_type = "Cheque/DD";
                                                                    }
                                                                    $item_detail = getproductDetailsByCode($product->item_code);
                                                                    ?> 
                                                                    <tr>
                                                                        <td><?php echo $index; ?></td>
                                                                        <td><?php echo getRetailerNameById($product->retailer_id); ?></td>
                                                                        <td><?php echo $product->order_no; ?></td>
                                                                        <td><?php echo $product->item_name; ?></td>
                                                                        <td>
                                                                            <b>Category : </b><?php echo getCategoryNameById($item_detail->main_category_id); ?><br/>
                                                                            <b>HSN Code : </b><?php echo $item_detail->hsn_code; ?><br/>
                                                                            <b>Qty : </b><?php echo $product->qty; ?>
                                                                        </td>
                                                                        <td>
                                                                            <b>Type : </b><?php echo $payment_type; ?><br/>
                                                                            <?php if (!empty($product->transaction_no)) { ?>
                                                                                <b>Transaction No : </b><?php echo $product->transaction_no; ?><br/>
                                                                            <?php } ?>
                                                                            <b>Total : </b><?php echo $product->total_price; ?><br/>
                                                                            <b>Date :</b><?php echo date('d M Y H:i', strtotime($product->added_datetime)); ?>
                                                                        </td>
                                                                        <td>
                                                                            <?php if ($index == 1) { ?>
                                                                                <input type="hidden" class="reject_order" value="<?php echo base64_encode($product->order_no); ?>" />
                                                                                <textarea placeholder="Reason" name="reason" class="reason"></textarea>
                                                                            <?php } ?>
                                                                        </td>
                                                                        <td>
                                                                            <?php
                                                                            if ($index == 1) {
                                                                                if ($update_block_date < date('Y-m-d', strtotime($product->added_datetime))) {
                                                                                    ?>
                                                                                    <button class="btn btn-danger reject_order_btn" id="<?php echo base64_encode($product->order_no); ?>">Reject</button>
                                                                                    <?php
                                                                                } else {
                                                                                    echo '<b class="red">Order Can not reject before Lock Date.</b>';
                                                                                }
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

