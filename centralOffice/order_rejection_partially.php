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
                                                </form>
                                            </div>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row clearfix">
                                                <div class="pull-right tableTools-container"></div>
                                            </div>
                                            <div>
                                                <form action="order_rejection_partially.php?menu=422" method="POST">
                                                    <table class="table table-bordered table-hover">
                                                        <thead class="thead-dark">
                                                            <tr><tr>
                                                                <th>#</th>
                                                                <th>Retailer Name</th>
                                                                <th>Item Name</th>
                                                                <th></th>
                                                                <th></th>
                                                                <th>RejectQty</th>
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
                                                                            <td>
                                                                                <b>Order No :</b> <?php echo $product->order_no; ?><br/>
                                                                                <b>Buyer :</b> <?php echo $product->cus_name; ?><br/>
                                                                                <b>Item Name :</b> <?php echo $product->item_name; ?>
                                                                            </td>
                                                                            <td>
                                                                                <b>Category : </b><?php echo getCategoryNameById($item_detail->main_category_id); ?><br/>
                                                                                <b>HSN Code : </b><?php echo $item_detail->hsn_code; ?><br/>
                                                                                <b>Qty : </b><b class="red"><?php echo $product->qty; ?></b>
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
                                                                                <input type="hidden" name="reject_item[]" class="reject_item" value="<?php echo $product->tempId; ?>" />
                                                                                <input type="text" name="reject_qty_<?php echo $product->tempId; ?>" class="reject_qty" value="<?php echo 0; ?>" />
                                                                            </td>
                                                                            <td>
                                                                                <?php if ($index == 1) { ?>
                                                                                    <input type="hidden" class="reject_order" name="reject_order" value="<?php echo base64_encode($product->order_no); ?>" />
                                                                                    <input type="text" placeholder="Credit Note Number" name="credit_note" class="credit_note"><br/><br/>
                                                                                    <textarea placeholder="Reason" name="reason" class="reason"></textarea>
                                                                                <?php } ?>
                                                                            </td>
                                                                            <td>
                                                                                <?php if ($index == 1) { ?>
                                                                                    <input class="btn btn-danger" name="credit_note_form" type="submit" value="Reject">
                                                                                <?php } ?>
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
                                                </form>
                                                <?php
                                                if (isset($_POST['credit_note_form'])) {
                                                    $reject_order = base64_decode($_POST['reject_order']);
                                                    $credit_note = $_POST['credit_note'];
                                                    $reason = $_POST['reason'];
                                                    $reject_item_Array = $_POST['reject_item'];
                                                    $order_no = $reject_order;
                                                    $products = checkOrderForPartially($reject_order);
                                                    if (!empty($products->order_no)) {
                                                        $MASTER_update = 0;
                                                        foreach ($reject_item_Array as $reject_item) {
                                                            $reject_order_qty = $_POST['reject_qty_' . $reject_item];
                                                            if ($reject_order_qty > 0) {
                                                                $temporary_dat = checkOrderTemporaryById($reject_item);
                                                                if ($reject_order_qty <= $temporary_dat->qty) {
                                                                    $MASTER_update = 1;
                                                                    $batch_no = $temporary_dat->batch_no;
                                                                    $item_code = $temporary_dat->item_code;
                                                                    $retailer_id = $temporary_dat->retailer_id;
                                                                    $itemMsater = getRetailerItemByItemCodeRetailerId($temporary_dat->item_code, $temporary_dat->retailer_id);
                                                                    $dataUpdate_B = array();
                                                                    $dataUpdate_B['issued_stock'] = $itemMsater->issued_stock - $reject_order_qty;
                                                                    $dataUpdate_B['current_stock'] = $itemMsater->current_stock + $reject_order_qty;
                                                                    $whereData_B = "retailer_id='$temporary_dat->retailer_id' AND item_code='$temporary_dat->item_code'";
                                                                    $update_1 = update("retailer_inventory_master", $dataUpdate_B, $whereData_B);
                                                                    if ($update_1) {
                                                                        $sale_qty_input = numberDecimal($reject_order_qty);
                                                                        $sale_qty_input_array = explode(".", $sale_qty_input);
                                                                        $sale_qty_input_1 = $sale_qty_input_array[0];
                                                                        $sale_qty_input_2 = numberDecimal("0." . $sale_qty_input_array[1]);
                                                                        if ($sale_qty_input_1 > 0) {
                                                                            $data_item_sr_master = array();
                                                                            $data_item_sr_master['status'] = 0;
                                                                            $data_item_sr_master['sale_qty'] = 0;
                                                                            $data_item_sr_master['doc_no'] = $credit_note;
                                                                            $data_item_sr_master['update_datetime'] = date("Y-m-d H:i:s");
                                                                            $whereitem_sr_master = "item_code='$temporary_dat->item_code' AND retailer_id='$temporary_dat->retailer_id' and batch_no='$temporary_dat->batch_no' and order_no='$order_no' and status='1'";
                                                                            $updateIn = updateLimit('item_sr_master', $data_item_sr_master, $whereitem_sr_master, $sale_qty_input_1);
                                                                        }
                                                                        if ($sale_qty_input_2 > 0) {
                                                                            $FreeSr_noBybatch = getFreeSr_noBybatch($temporary_dat->retailer_id, $batch_no, $item_code, $sale_qty_input_2);
                                                                            if (isset($FreeSr_noBybatch->serial_number)) {
                                                                                $serial_number = $FreeSr_noBybatch->serial_number;
                                                                                $serial_qty = $FreeSr_noBybatch->qty;
                                                                                $sale_qty = $FreeSr_noBybatch->sale_qty;
                                                                                $batch_no_ser = $FreeSr_noBybatch->batch_no;
                                                                                $company_id = $FreeSr_noBybatch->company_id;
                                                                                $purchase_basic = $FreeSr_noBybatch->purchase_basic;
                                                                                $company_id = $FreeSr_noBybatch->company_id;
                                                                                $gst = $FreeSr_noBybatch->gst;
                                                                                $total = $FreeSr_noBybatch->total;
                                                                                $expire_date = $FreeSr_noBybatch->expire_date;

                                                                                $srUpdate = array();
                                                                                $srUpdate['sale_qty'] = numberDecimal($sale_qty - $sale_qty_input_2);
                                                                                $srUpdate['qty'] = numberDecimal($serial_qty + $sale_qty_input_2);
                                                                                $srUpdate['update_datetime'] = date("Y-m-d H:i:s");
                                                                                $srUpdate['doc_no'] = $credit_note;
                                                                                $srUpdate['partial'] = 1;
                                                                                $srUpdateWhere = "serial_number='$serial_number' and item_code='$item_code' AND STATUS in('1','0') AND retailer_id='$temporary_dat->retailer_id' AND batch_no='$batch_no_ser'";
                                                                                $updateDecimal = update('item_sr_master', $srUpdate, $srUpdateWhere);
                                                                                if ($updateDecimal) {
                                                                                    $item_sale_master_decimal = array();
                                                                                    $item_sale_master_decimal['retailer_id'] = $retailer_id;
                                                                                    $item_sale_master_decimal['purchase_basic'] = $purchase_basic;
                                                                                    $item_sale_master_decimal['gst'] = $gst;
                                                                                    $item_sale_master_decimal['total'] = $total;
                                                                                    $item_sale_master_decimal['company_id'] = $company_id;
                                                                                    $item_sale_master_decimal['order_no'] = $order_no;
                                                                                    $item_sale_master_decimal['serial_number'] = $serial_number;
                                                                                    $item_sale_master_decimal['sale_qty'] = $sale_qty_input_2;
                                                                                    $item_sale_master_decimal['item_code'] = $item_code;
                                                                                    $item_sale_master_decimal['batch_no'] = $batch_no;
                                                                                    $item_sale_master_decimal['expire_date'] = $expire_date;
                                                                                    $item_sale_master_decimal['sale_date'] = date("Y-m-d H:i:s");
                                                                                    $item_sale_master_decimal['credit_note'] = $credit_note;
                                                                                    $item_sale_master_decimal['status'] = 1;
                                                                                    insert("item_sale_master_decimal", $item_sale_master_decimal);
                                                                                }
                                                                            }
                                                                        }


                                                                        $dataUpdate_a = array();
                                                                        $dataUpdate_a['return_qty'] = $reject_order_qty;
                                                                        $dataUpdate_a['credit_note_no'] = $credit_note;
                                                                        $dataUpdate_a['reject_reason'] = $reason;
                                                                        $whereData_a = "id='$reject_item' AND status not in ('7','8')";
                                                                        $update_1 = update("retailer_order_temporary", $dataUpdate_a, $whereData_a);
                                                                    }

                                                                    $partially_reject_order = array();
                                                                    $partially_reject_order['retailer_id'] = $temporary_dat->retailer_id;
                                                                    $partially_reject_order['item_code'] = $temporary_dat->item_code;
                                                                    $partially_reject_order['ordered_qty'] = $temporary_dat->qty;
                                                                    $partially_reject_order['rejected_qty'] = $reject_order_qty;
                                                                    $partially_reject_order['order_no'] = $order_no;
                                                                    $partially_reject_order['credit_note_no'] = $credit_note;
                                                                    $partially_reject_order['datetime'] = date("Y-m-d H:i:s");
                                                                    $partially_reject_order['remarks'] = $reason;
                                                                    $insert = insert("partially_reject_order", $partially_reject_order);
                                                                } else {
                                                                    echo "<script>alert('Please enter valid qty for batch number :" . $temporary_dat->batch_no . "');</script>";
                                                                }
                                                            }
                                                        }

                                                        $dataUpdate = array();
                                                        $dataUpdate['reject_reason'] = $reason;
                                                        $dataUpdate['credit_note_no'] = $credit_note;
                                                        $whereData = "order_no='$order_no' AND status not in ('7','8')";
                                                        if ($MASTER_update == 1) {
                                                            $updateMaster = update("retailer_order_master", $dataUpdate, $whereData);
                                                        } elsE {
                                                            $updateMaster = true;
                                                        }
                                                        if ($updateMaster) {
                                                            echo "<script>alert('Successfully Rejected.');window.location='order_rejection_partially.php?menu=422';</script>";
                                                            exit;
                                                        } else {
                                                            echo "<script>alert('error for " . $order_no . " ..! contact team IT.');window.location='order_rejection_partially.php?menu=422';</script>";
                                                            exit;
                                                        }
                                                    } else {
                                                        
                                                    }
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

                </div>
            </div>
            <?php require_once 'includes/footer.php'; ?>    
        </div>
    </body>
</html>

