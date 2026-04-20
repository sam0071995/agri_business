<?php
require_once 'includes/session.php';
require_once 'includes/common_function.php';
require_once 'includes/db.class';
$bdd = new db();
?>
<!DOCTYPE html>
<html lang="en">
<?php require_once 'includes/header.php'; ?>

<style>
    .header-title h3 {
        text-transform: uppercase;
    }

    .container_ajit .left {
        width: 55%;
        float: left;
        margin: 0;
        padding: 4px;
        display: inline;
    }

    .container_ajit .right {
        width: 38%;
        float: left;
        margin: 0;
        padding: 4px;
        display: inline;
    }

    .order-details {
        display: inline-block;
        width: 100%;
    }

    .order-details p {
        padding: 4px;
    }

    .table {
        border-collapse: collapse;
    }

    .width_450px {
        width: 320px;
    }

    .table .left {
        text-align: left;
    }

    .table .right {
        text-align: right;
    }
</style>

<body class="no-skin">
    <?php require_once 'includes/menu.php'; ?>
    <div class="main-container ace-save-state" id="main-container">
        <?php require_once 'includes/left_sidebar.php'; ?>
        <div class="main-content">
            <div class="main-content-inner">
                <?php require_once 'includes/breadcrumbs.php'; ?>
                <div class="page-content">
                    <?php require_once 'includes/page-header.php'; ?>
                    <div class="page-header">

                        <h1>Inward Inventory Purchase Order.</h1>
                    </div><!-- /.page-header close-->
                    <div class="row">
                        <div class="align-right">
                            <a href="new_purchase_order.php?menu=7"><button class="btn btn-primary">New Purchase</button></a>
                            <a href="purchase_goods_order.php?menu=7"><button class="btn btn-success">Purchase Order</button></a>
                            <a href="close_purchase_goods_order.php?menu=7"><button class="btn btn-danger">Closed Order</button></a>
                        </div>
                        <div class="col-xs-12">
                            <?php if (isset($_GET['success'])) { ?>
                                <div class="alert alert-block alert-success">
                                    <button type="button" class="close" data-dismiss="alert">
                                        <i class="ace-icon fa fa-times"></i>
                                    </button>

                                    <i class="ace-icon fa fa-check green form-error-msg"></i>
                                    Item Successfully Inward.
                                </div>
                            <?php } ?>

                            <?php if (isset($_GET['close'])) { ?>
                                <?php if ($_GET['close'] == 1) { ?>
                                    <div class="alert alert-block alert-success">
                                        <button type="button" class="close" data-dismiss="alert">
                                            <i class="ace-icon fa fa-times"></i>
                                        </button>

                                        <i class="ace-icon fa fa-check green form-error-msg"></i>
                                        Purchase Order Complete and Closed.
                                    </div>
                                <?php } ?>
                            <?php } ?>
                            <?php
                            if (isset($_GET['error'])) {
                                switch ($_GET['error']) {
                                    case 1:
                                        $msg = "Problem Occured to  Inward Items.";
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
                                <?php
                                if (isset($_GET['po_no'])) {
                                    $orderId = trim(base64_decode($_GET['po_no']));
                                    $orderData = $bdd->getPurchaseOrderbyId($orderId);
                                    $supplierData = $bdd->getSupplierdById($orderData->supplier_id);
                                    $poDate = str_replace('/', '-', $orderData->po_date);
                                    $poDate = date('d-M-Y', strtotime($poDate));
                                ?>
                                    <div class="container_ajit">
                                        <div class="table-area">
                                            <div class="form-group" id="c_n_password_c">
                                                <label class="col-sm-4 control-label no-padding-left" for="form-field-1-1"> Supplier Name : <b><?php echo $supplierData->full_name; ?></b></label>
                                                <label class="col-sm-4 control-label no-padding-right" for="form-field-1-1"> Supplier Address : <b><?php echo $supplierData->address . " " . $supplierData->pincode; ?></b></label>
                                            </div>
                                            <div class="form-group" id="c_n_password_c">
                                                <label class="col-sm-4 control-label no-padding-left" for="form-field-1-1"> P.O NO : <b><?php echo $orderData->po_no; ?></b></label>
                                                <label class="col-sm-4 control-label no-padding-left" for="form-field-1-1"> P.O DATE : <b><?php echo $poDate; ?></b></label>
                                            </div>
                                            <input type="hidden" name="vendor_ids" id="vendor_ids" value="<?php echo $orderData->vendor_id; ?>">
                                            <input type="hidden" name="po_ids" id="po_ids" value="<?php echo $orderId; ?>">
                                            <input type="hidden" name="po_nos" id="po_nos" value="<?php echo $orderData->po_no; ?>">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <td>Sr No.</td>
                                                        <td>Item Description</td>
                                                        <td>UOM</td>
                                                        <td>Purchase Quantity</td>
                                                        <td>Balanced Quantity</td>
                                                        <td>Inward Quantity</td>
                                                        <td>Remarks</td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $orderDetails = $bdd->purchaseOrderDetails($orderId);
                                                    $sr_NO = 1;
                                                    $rowspan = count($orderDetails);
                                                    foreach ($orderDetails as $orderDetail) {
                                                        $item_id = $orderDetail->item_id;
                                                        $itemDetails = $bdd->getItemDetails($orderDetail->item_id);
                                                        $balanced_quantity = $bdd->getBalancedQuantityByPoAndItemId($item_id, $orderId);
                                                        if (empty($balanced_quantity) && $balanced_quantity == 0) {
                                                            $balanced_quantity = 0;
                                                        }
                                                        if ($orderDetail->qty <= $balanced_quantity) {
                                                            $readonly = "readonly='readonly'";
                                                            $read = '1';
                                                        } else {
                                                            $readonly = "";
                                                            $read = '0';
                                                        }
                                                    ?>
                                                        <?php
                                                        $balance_qty = $orderDetail->qty - $balanced_quantity;
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $sr_NO; ?></td>
                                                            <td class="width_450px"><?php echo $itemDetails->item_desc; ?></td>
                                                            <td><?php echo $itemDetails->uom; ?></td>
                                                            <td><?php echo $orderDetail->qty; ?></td>
                                                            <td>
                                                                <b><?php echo $balance_qty; ?></b>
                                                                <input type="hidden" id="item_qtyb_<?php echo $sr_NO; ?>" name="item_qtyb_<?php echo $item_id; ?>" value="<?php echo $balance_qty; ?>" />
                                                            </td>
                                                            <td>
                                                                <input type="hidden" name="inwradAllow_<?php echo $item_id; ?>" value="<?php echo $read; ?>">
                                                                <input type="hidden" name="po_id" value="<?php echo $orderId; ?>" />
                                                                <input type="hidden" id="item_id_<?php echo $sr_NO; ?>" name="item[]" value="<?php echo $item_id; ?>" />
                                                                <input type="text" onchange="check_balance_qty(<?php echo $sr_NO; ?>);return false;" id="item_qty_<?php echo $sr_NO; ?>" name="item_<?php echo $item_id; ?>" value="<?php echo $balance_qty; ?>" <?php echo $readonly; ?> />
                                                            </td>
                                                            <td><textarea name="remarks_<?php echo $item_id; ?>" id="top_remark_<?php echo $sr_NO; ?>"></textarea></td>
                                                            <input type="hidden" name="uniq_id" id="uniqId_<?php echo $sr_NO; ?>" value="<?php echo $orderDetail->unique_id; ?>">
                                                        </tr>
                                                    <?php
                                                        $sr_NO++;
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                            <hr />
                                            <?php
                                            $max_inwd_inc_no = getMaxInwdIncNoFrmInventoryGRN();
                                            if ($max_inwd_inc_no == 0 || empty($max_inwd_inc_no) || $max_inwd_inc_no == '') {
                                                $max_inwd_inc_no = 1;
                                            } else {
                                                $max_inwd_inc_no = $max_inwd_inc_no + 1;
                                            }

                                            if (ltrim(date('m')) > 3) {
                                                $cd = date('y');
                                                $dd = $cd + 1;
                                            } else {
                                                $dd = date('y');
                                                $cd = $dd - 1;
                                            }
                                            $fin_year_latest = $cd . '' . $dd;

                                            $auto_inwd_no = 'POINWD/' . $fin_year_latest . '/' . $max_inwd_inc_no;
                                            ?>
                                            <div class="form-group" id="c_n_password_c">
                                                <label class="col-sm-2 control-label no-padding-right" for="form-field-1-1"> Inward Number : </label>
                                                <div class="col-sm-2">
                                                    <input class="form-control" id="inward_no" name="inward_no" required="required" type="text" value="<?php echo $auto_inwd_no; ?>" readonly="" />
                                                    <input class="form-control" id="inward_inc_no" name="inward_inc_no" type="hidden" value="<?php echo $max_inwd_inc_no; ?>" />
                                                </div>

                                                <label class="col-sm-2 control-label no-padding-right" for="form-field-1-1"> Invoice Number : </label>
                                                <div class="col-sm-2">
                                                    <input class="form-control" id="id-date-picker-1" name="bill_no" required="required" type="text" value="" data-date-format="dd-mm-yyyy" />
                                                </div>
                                                <label class="col-sm-2 control-label no-padding-right" for="form-field-1-1"> Inward Date : </label>
                                                <div class="col-sm-2">
                                                    <input class="form-control date-picker" id="id-date-picker-1" name="inward_date" required="required" type="text" value="<?php
                                                                                                                                                                            if (isset($_POST['date_1'])) {
                                                                                                                                                                                echo $_POST['date_1'];
                                                                                                                                                                            } else {
                                                                                                                                                                                echo date('d-m-Y');
                                                                                                                                                                            }
                                                                                                                                                                            ?>" data-date-format="dd-mm-yyyy" />
                                                </div>

                                            </div>

                                            <div class="form-group" id="c_n_password_c">
                                                <label class="col-sm-2 control-label no-padding-right" for="form-field-1-1"> Remarks : </label>
                                                <div class="col-sm-6">
                                                    <textarea class="form-control" name="remarks"></textarea>
                                                </div>

                                            </div>

                                            <div class="clearfix form-actions">
                                                <div class="col-md-offset-4 col-md-9">
                                                    <button type="submit" name="submit" class="btn btn-info">
                                                        <i class="ace-icon fa fa-check bigger-110"></i>
                                                        Inward
                                                    </button>
                                                    &nbsp; &nbsp; &nbsp;
                                                    <button class="btn" type="reset">
                                                        <i class="ace-icon fa fa-undo bigger-110"></i>
                                                        Reset
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </form>
                        <?php } ?>
                        </div>
                        <?php
                        if (isset($_POST['submit'])) {
                            $po_id = $_POST['po_id'];
                            $orderData = $bdd->getPurchaseOrderbyId($_POST['po_id']);
                            $orderDetails = $bdd->purchaseOrderDetails($po_id);
                            $items = $_POST['item'];

                            $countItems = count($items);



                            if (count($items) > 0) {
                                $closeOrder = 0;
                                $table_hist = "company_inward_po_history";
                                foreach ($items as $item) {
                                    $data = array();
                                    $item_quantity = $_POST['item_' . $item];
                                    $item_quy_balance = $_POST['item_qtyb_' . $item];
                                    $item_remarks = $_POST['remarks_' . $item];
                                    $invAllow = $_POST['inwradAllow_' . $item];
                                    if ($invAllow == 0) {
                                        if ($item_quantity > 0) {
                                            $data['company_id'] = $_SESSION['id'];
                                            $data['supplier_id'] = $orderData->supplier_id;
                                            $data['item_code'] = $item;
                                            $data['quantity'] = $item_quantity;
                                            $data['remarks'] = $item_remarks;
                                            $data['po_id'] = $_POST['po_id'];
                                            $data['po_no'] = $orderData->po_no;
                                            $data['grn_no'] = trim($_POST['inward_no']);
                                            $data['date'] = date('Y-m-d');
                                            $data['datetime'] = date('Y-m-d h:i:s');

                                            $insert = insert($table_hist, $data);
                                            if ($insert) {
                                                $dataSum['total_stock'] = 'total_stock+' . $item_quantity;
                                                $dataSum['current_stock'] = 'current_stock+' . $item_quantity;
                                                $where = "item_code='$item'";
                                                $updateSum = $bdd->updateSum('inventory_master_' . $_SESSION['id'], $dataSum, $where);
                                                if ($updateSum) {
                                                    $remarks_a = ($_POST['remarks'] == '') ? $item_remarks : $_POST['remarks'];
                                                    $insertData['ref_no'] = trim($_POST['inward_no']);
                                                    $insertData['bill_no'] = $_POST['bill_no'];
                                                    $insertData['po_no'] = $orderData->po_no;
                                                    $insertData['po_id'] = $orderData->id;
                                                    $insertData['company_id'] = $_SESSION['id'];
                                                    $insertData['supplier_id'] = $orderData->supplier_id;
                                                    $insertData['item_desc'] = $item;
                                                    $insertData['billed_qty'] = $item_quantity;
                                                    $insertData['date_time'] = date('Y-m-d', strtotime($_POST['inward_date']));
                                                    $insertData['remark'] = $item_remarks;
                                                    $insertData['inwd_inc_no'] = $_POST['inward_inc_no'];
                                                    $insertData['inwd_no'] = $_POST['inward_no'];
                                                    $insert_grn = insert("inventory_grn", $insertData);
                                                    if ($insert_grn) {
                                                        $message = 1;
                                                    } else {
                                                        $message = 0;
                                                    }
                                                }
                                            }

                                            // for generate serial number=========================

                                            for ($i = 0; $i < $item_quantity; $i++) {
                                                // $max_num = getMaxNoFromSerialStore();
                                                // if ($max_num == 0) {
                                                //     $max_num = 1;
                                                // } else {
                                                //     $max_num = $max_num + 1;
                                                // }

                                                // $serial_no = "TP" . sprintf("%08d", $max_num);

                                                // $ins_data['inc_no'] = $max_num;
                                                $fix_code = $bdd->getItemsByItemCode($item)->fix_code;
                                                $serial_no = "TP" . $fix_code . sprintf("%06d", rand(1, 1000000));

                                                $ins_data['serial_no'] = $serial_no;
                                                $ins_data['company_id'] = $_SESSION['id'];
                                                $ins_data['supplier_id'] = $orderData->supplier_id;
                                                $ins_data['item_code'] = $item;
                                                // $ins_data['serial_qrcode'] = $item;
                                                $ins_data['add_datetime'] = date('Y-m-d H:i:s');
                                                $ins_data['status'] = 0;
                                                $ins_data['po_no'] = $orderData->po_no;
                                                $ins = insert('serial_number_store', $ins_data);
                                            }



                                            // for generate serial number=========================
                                        }
                                    }

                                    $TotalItemOrder = $bdd->getItemDetailsByPoIdAndItemId($orderData->id, $item);
                                    $inward_items = $bdd->getBalancedQuantityByPoAndItemId($item, $orderData->id);
                                    if ($TotalItemOrder <= $inward_items) {
                                        $closeOrder = $closeOrder + 1;
                                    } else {
                                        $closeOrder = 0;
                                    }
                                }

                                $closeOrder_status = 0;
                                if ($countItems == $closeOrder) {
                                    $closeOrder_status = 1;
                                    $data_inserted['status'] = 2;
                                    $bdd->update("purchase_order", $data_inserted, "id='$orderData->id'");
                                }
                                if ($message == 1) {
                                    print '<script>window.location="inward_inventory_purchase_order.php?menu=7&success=1&close=' . $closeOrder_status . '&po_no=' . base64_encode($po_id) . '";</script>';
                                    exit;
                                } else {
                                    print '<script>window.location="inward_inventory_purchase_order.php?menu=7&error=1&close=' . $closeOrder_status . '&po_no=' . base64_encode($po_id) . '";</script>';
                                    exit;
                                }
                            }
                        }
                        ?>
                    </div>
                </div><!-- /.row -->
            </div><!-- /.page-content -->
        </div>
    </div><!-- /.main-content -->

    <!--END MAIN WRAPPER -->
    <script type="text/javascript">
        function check_balance_qty(indx) {
            var balance_qty = document.getElementById('item_qtyb_' + indx).value;
            var item_qty = document.getElementById('item_qty_' + indx).value;

            //                alert(item_qty);

            if (parseInt(item_qty) > parseInt(balance_qty)) {
                alert('Please Check Balance Quentity..');
                document.getElementById('item_qty_' + indx).value = '';
                document.getElementById('item_qty_' + indx).focus();
                return flase;
            }
        }

        $(".close_btn").click(function() {
            var id = $(this).attr('id');
            var paur_id = id.split('_');
            var split_id = paur_id[2];
            var remarks = $("#top_remark_" + split_id).val();
            if (remarks == '') {
                alert('Enter Remark');
                return false;
            }
            if (confirm('Are you sure to delete')) {
                var qty = $("#item_qty_" + split_id).val();
                var item_id = $("#item_id_" + split_id).val();
                var unique_ids = $("#uniqId_" + split_id).val();
                var vendor_id = $("#vendor_ids").val();
                var po_id = $("#po_ids").val();
                var po_nos = $("#po_nos").val();
                var ids;
                $.ajax({
                    type: 'POST',
                    url: "po_manual_closing_action.php?close=" + ids,
                    data: {
                        remark: remarks,
                        qtys: qty,
                        item_ids: item_id,
                        vendor_ids: vendor_id,
                        po_ids: po_id,
                        po_no: po_nos,
                        unique_id: unique_ids
                    },
                    success: function(response) {
                        //alert(response); 
                        if (response == 1) {
                            alert('PO of Item close');
                            location.reload();
                        }
                        if (response == '00') {
                            alert('Something Wrong00')
                        }

                        if (response == '0') {
                            alert('Something Wrong0')
                        }
                    }
                });
            }

        });
    </script>
    <?php require_once 'includes/footer.php'; ?>
    </div>
</body>

</html>