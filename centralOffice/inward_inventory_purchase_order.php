<?php
require_once 'includes/session.php';
require_once 'includes/common_function.php';
require_once 'includes/db.class';
$bdd = new db();
$inward_no = '';
$bill_no = '';
$veh_reg_no = '';
$trnasport_name = '';
$remarks = '';
//echo '<pre/>';
//print_r($_SESSION);
//exit;
if ($_SESSION['id'] == 3) {
    $bill_no = 'NA';
    $inward_no = 'NA';
    $veh_reg_no = 'NA';
    $trnasport_name = 'NA';
    $remarks = 'NA';
}
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
                                <a href="generate_new_po.php?menu=11"><button class="btn btn-primary">New Purchase</button></a>
                                <a href="purchase_order.php?menu=11"><button class="btn btn-success">Purchase Order</button></a>
                                <a href="close_purchase_order.php?menu=11"><button class="btn btn-danger">Closed Order</button></a>
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
                                        $poDate = str_replace('/', '-', $orderData->po_date);
                                        $poDate = date('d-M-Y', strtotime($poDate));
                                        ?>
                                        <div class="container_ajit">
                                            <div class="table-area">
                                                <div class="form-group" id="c_n_password_c">
                                                    <label class="col-sm-3 control-label no-padding-left" for="form-field-1-1"> Supplier Name : <b><?php echo $orderData->supplier_name; ?></b></label>
                                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Supplier Address : <b><?php echo $orderData->supplier_address; ?></b></label>
                                                    <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Store Name : <b><?php echo getRetailerNameById($orderData->retailer_id); ?></b></label>
                                                </div>
                                                <div class="form-group" id="c_n_password_c">
                                                    <label class="col-sm-3 control-label no-padding-left" for="form-field-1-1"> P.O NO : <b><?php echo $orderData->po_no; ?></b></label>
                                                    <label class="col-sm-3 control-label no-padding-left" for="form-field-1-1"> P.O DATE : <b><?php echo $poDate; ?></b></label>
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
                                                                <td><b><?php echo $balanced_quantity; ?></b></td>
                                                                <td>
                                                                    <input type="hidden" name="rate_<?php echo $item_id; ?>" value="<?php echo $orderDetail->rate; ?>">
                                                                    <input type="hidden" name="gst_rate_<?php echo $item_id; ?>" value="<?php echo $orderDetail->gst_rate; ?>">
                                                                    <input type="hidden" name="inwradAllow_<?php echo $item_id; ?>" value="<?php echo $read; ?>">
                                                                    <input type="hidden" name="po_id" value="<?php echo $orderId; ?>" />
                                                                    <input type="hidden" id="item_id_<?php echo $sr_NO; ?>" name="item[]" value="<?php echo $item_id; ?>" />
                                                                    <input type="text" id="item_qty_<?php echo $sr_NO; ?>" name="item_<?php echo $item_id; ?>" value="<?php echo $balance_qty; ?>" <?php echo $readonly; ?> />
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
                                                <div class="form-group" id="c_n_password_c">
                                                    <label class="col-sm-2 control-label no-padding-right" for="form-field-1-1"> Inward Number : </label>
                                                    <div class="col-sm-2">
                                                        <input class="form-control" id="id-date-picker-1" name="inward_no" required="required" type="text" value="<?php echo $inward_no; ?>" data-date-format="dd-mm-yyyy" />
                                                    </div>

                                                    <label class="col-sm-2 control-label no-padding-right" for="form-field-1-1"> Bill Number : </label>
                                                    <div class="col-sm-2">
                                                        <input class="form-control" id="id-date-picker-1" name="bill_no" required="required" type="text" value="<?php echo $bill_no; ?>" data-date-format="dd-mm-yyyy" />
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
                                                    <label class="col-sm-2 control-label no-padding-right" for="form-field-1-1"> LR/Vehicle Reg No : </label>
                                                    <div class="col-sm-2">
                                                        <input class="form-control" id="id-date-picker-1" name="veh_reg_no" required="required" type="text" value="<?php echo $veh_reg_no; ?>" data-date-format="dd-mm-yyyy" />
                                                    </div>
                                                    <label class="col-sm-2 control-label no-padding-right" for="form-field-1-1"> Transport Name : </label>
                                                    <div class="col-sm-2">
                                                        <input class="form-control" id="id-date-picker-1" name="trnasport_name" required="required" type="text" value="<?php echo $trnasport_name; ?>" data-date-format="dd-mm-yyyy" />
                                                    </div>
                                                    <label class="col-sm-2 control-label no-padding-right" for="form-field-1-1"> LR Date : </label>
                                                    <div class="col-sm-2">
                                                        <input class="form-control date-picker" id="id-date-picker-1" name="lr_date" required="required" type="text" value="<?php
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
                                                        <textarea class="form-control" name="remarks"><?php echo $remarks; ?></textarea>
                                                    </div>

                                                    <label class="col-sm-2 control-label no-padding-right" for="form-field-1-1"> Invoice Date : </label>
                                                    <div class="col-sm-2">
                                                        <input class="form-control date-picker" id="id-date-picker-1" name="invoice_date" required="required" type="text" value="<?php
                                                        if (isset($_POST['invoice_date'])) {
                                                            echo $_POST['invoice_date'];
                                                        } else {
                                                            echo date('d-m-Y');
                                                        }
                                                        ?>" data-date-format="dd-mm-yyyy" />
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
                                        $item_rate = $_POST['rate_' . $item];
                                        $item_gst_rate = $_POST['gst_rate_' . $item];
                                        $item_quantity = $_POST['item_' . $item];
                                        $item_remarks = $_POST['remarks_' . $item];
                                        $invAllow = $_POST['inwradAllow_' . $item];
                                        if ($invAllow == 0) {
                                            if ($item_quantity > 0) {
                                                $data['company_id'] = $orderData->company_id;
                                                $data['retailer_id'] = $orderData->retailer_id;
                                                $data['supplier_id'] = $orderData->supplier_id;
                                                $data['supplier_name'] = $orderData->supplier_name;
                                                $data['supplier_contact_person'] = $orderData->supplier_contact_person;
                                                $data['supplier_contact_no'] = $orderData->supplier_contact_no;
                                                $data['supplier_address'] = $orderData->supplier_address;
                                                $data['item_code'] = $bdd->getItemCodeById($item);
                                                $data['quantity'] = $item_quantity;
                                                $data['remarks'] = $item_remarks;
                                                $data['po_id'] = $_POST['po_id'];
                                                $data['po_no'] = $orderData->po_no;
                                                $data['grn_no'] = trim($_POST['inward_no']);
                                                $data['date'] = date('Y-m-d');
                                                $data['datetime'] = date('Y-m-d h:i:s');
                                                $insert = insert($table_hist, $data);
                                                if ($insert) {
                                                    $remarks_a = ($_POST['remarks'] == '') ? $item_remarks : $_POST['remarks'];
                                                    $insertData['ref_no'] = trim($_POST['inward_no']);
                                                    $insertData['bill_no'] = $_POST['bill_no'];
                                                    $insertData['po_no'] = $orderData->po_no;
                                                    $insertData['po_type'] = $orderData->po_type;
                                                    $insertData['po_id'] = $orderData->id;
                                                    $insertData['po_date'] = $orderData->po_date;
                                                    $insertData['company_id'] = $orderData->company_id;
                                                    $insertData['retailer_id'] = $orderData->retailer_id;
                                                    $insertData['vendor_id'] = $orderData->vendor_id;
                                                    $insertData['supplier_id'] = $orderData->supplier_id;
                                                    $insertData['supplier_name'] = $orderData->supplier_name;
                                                    $insertData['supplier_contact_person'] = $orderData->supplier_contact_person;
                                                    $insertData['supplier_contact_no'] = $orderData->supplier_contact_no;
                                                    $insertData['supplier_address'] = $orderData->supplier_address;
                                                    $insertData['item_desc'] = $bdd->getItemCodeById($item);
                                                    $insertData['billed_qty'] = $item_quantity;
                                                    $insertData['po_basic'] = $item_rate;
                                                    $insertData['po_total_basic_value'] = numberDecimal($item_rate * $item_quantity);
                                                    $insertData['po_gst'] = $item_gst_rate;
                                                    $insertData['po_total'] = $item_rate + ($item_rate * $item_gst_rate / 100);
                                                    $insertData['date_time'] = date('Y-m-d', strtotime($_POST['inward_date']));
                                                    $insertData['lr_vehicle_no'] = $_POST['veh_reg_no'];
                                                    $insertData['transporter_name'] = $_POST['trnasport_name'];
                                                    $insertData['remark'] = $item_remarks;
                                                    $insertData['lr_date'] = date('Y-m-d', strtotime($_POST['lr_date']));
                                                    $insertData['invoice_date'] = date('Y-m-d', strtotime($_POST['invoice_date']));
                                                    $insert_grn = insert("inventory_grn", $insertData);
                                                    if ($insert_grn) {
                                                        $message = 1;
                                                    } else {
                                                        $message = 0;
                                                    }
                                                }
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
                                        print '<script>window.location="inward_inventory_purchase_order.php?menu=11&success=1&close=' . $closeOrder_status . '&po_no=' . base64_encode($po_id) . '";</script>';
                                        exit;
                                    } else {
                                        print '<script>window.location="inward_inventory_purchase_order.php?menu=11&error=1&close=' . $closeOrder_status . '&po_no=' . base64_encode($po_id) . '";</script>';
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
            $(".close_btn").click(function () {
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
                        url: "po_manual_closing_action.php?menu=11&close=" + ids,
                        data: {
                            remark: remarks,
                            qtys: qty,
                            item_ids: item_id,
                            vendor_ids: vendor_id,
                            po_ids: po_id,
                            po_no: po_nos,
                            unique_id: unique_ids
                        },
                        success: function (response) {
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