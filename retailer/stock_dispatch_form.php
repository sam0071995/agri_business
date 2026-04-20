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
                                <div class="row">
                                    <div class="col-xs-12">
                                        <h3 class="header">Stock Dispatch Form</h3>

                                        <div class="row">
                                            <div class="col-xs-12">
                                                <form class="form-horizontal" id="searchform" name="myForm" role="form" action="" method="POST" enctype="multipart/form-data">
                                                    <div class="form-group" id="">
                                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Select OrderNo <span style="color:red">*</span> : </label>
                                                        <div class="col-sm-4">
                                                            <select class="form-control" name="orderno" id="orderno" required="required">
                                                                <option value="">-- Select OrderNo --</option>
                                                                <?php foreach (getPendingDispacthOrderNo($_SESSION['id']) as $dataone) { ?>
                                                                    <option value="<?php echo $dataone->order_no; ?>"><?php echo $dataone->order_no; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>


                                                    <div class="clearfix form-actions">
                                                        <div class="col-md-offset-3 col-md-9">
                                                            <button type="submit" name="submit" class="btn btn-info">
                                                                <i class="ace-icon fa fa-check bigger-110"></i>
                                                                Show
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div><!-- /.row -->
                                        <?php
                                        if (isset($_POST['submit'])) {
                                            $orderno = $_POST['orderno'];
                                            ?>
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <div class="row">
                                                        <div class="modal-body">
                                                            <div class="row clearfix">
                                                                <div class="pull-right tableTools-container"></div>
                                                            </div>
                                                        </div>
                                                        <form action="" method="post" enctype="multipart/form-data">
                                                            <div>
                                                                <!--<h3 class="red"> ????? ??????? ???? ?? ???? , ??? ???? ? ????????? ??? ????? ??? ??? ? ????? ????????? ?? ????? ?? ??? ???? ?? ??? ?????</h3>-->
                                                                <h5 class="red">Before Dispatch stock, please check requested Batch number stock available or not!. please Physically dispatch only requested Batch number stock.</h5>
                                                                <table id="dynamic-tabl" class="table table-bordered table-hover">
                                                                    <thead class="thead-dark">
                                                                        <tr>
                                                                            <th width="8%" align="left">#</th>
                                                                            <th width="8%" align="left">Srno</th>
                                                                            <th width="15%" align="left">OrderNo</th>
                                                                            <th width="15%" align="left">ItemName</th>
                                                                            <th width="25%" align="left">RequestRetailer</th>
                                                                            <th width="15%" align="left">Qty</th>
                                                                            <th width="15%" align="left">BatchNo</th>
                                                                            <th width="15%" align="left">ExpiryDate</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                        $i = 1;
                                                                        $purchaseOrder = getInvReqByRetailerDetailsByOrderNo($orderno);
                                                                        if (count($purchaseOrder) > 0 && !empty($purchaseOrder)) {
                                                                            foreach ($purchaseOrder as $row) {
                                                                                ?>
                                                                                <tr>
                                                                                    <td><input type="checkbox" name="orderid[]" value="<?php echo $row->id; ?>" /></td>
                                                                                    <td><?php echo $i; ?></td>
                                                                                    <td><?php echo $row->order_no; ?></td>
                                                                                    <td><b class="blue"><?php echo getInventoryItemNameByCode($row->item_code); ?></b></td>
                                                                                    <td><?php echo getRetailerDataById($row->retailer_id)->name; ?></td>
                                                                                    <td><?php echo $row->req_qty; ?></td>
                                                                                    <td><b class="red"><?php echo $row->batch_no; ?></b></td>
                                                                                    <td><?php echo $row->expire_date; ?></td>

                                                                                </tr>
                                                                                <?php
                                                                                $i++;
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </tbody>
                                                                    <tfoot>
                                                                        <tr>
                                                                            <td colspan="6">
                                                                                Upload Challan Copy:
                                                                                <input type="file" name="image"><br/><br/>
                                                                                Action :
                                                                                <select name="action_request" required="required">
                                                                                    <option value="" style="color: green;">--select--</option>
                                                                                    <option value="1" style="color: green;">Dispatch</option>
                                                                                    <option value="2"  style="color: red;">Reject Request</option>
                                                                                </select>
                                                                                <input type="text" name="name_of_person" placeholder="Name of person" />
                                                                                <input type="text" name="Vehicle_Number" placeholder="Vehicle Number" />
                                                                                <button type='submit' name="dispatch" class='button btn-success' style='cursor:pointer' onclick="dispatch_item('<?php echo $orderno; ?>');" title='Dispatch'>Submit</button>
                                                                            </td>
                                                                        </tr>
                                                                    </tfoot>
                                                                </table>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div><!-- /.row -->
                                            <?php
                                        }

                                        if (isset($_POST['dispatch'])) {
                                            $orderid = $_POST['orderid'];
                                            $action_request = $_POST['action_request'];
                                            if (empty($action_request)) {
                                                echo "<script>alert('Empty Request.!!');window.location = window.location;</script>";
                                                exit;
                                            }

                                            if ($action_request == 1) {
                                                if (empty($_FILES['image']['name'])) {
                                                    echo "<script>alert('Please select a file...!!');window.location = window.location;</script>";
                                                    exit;
                                                }
                                                if ($_FILES['image']['size'] == 0) {
                                                    echo "<script>alert('Uploaded file is empty...!!');window.location = window.location;</script>";
                                                    exit;
                                                }

                                                $fileExtension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);


                                                $targetDir = "challan_copy/";
                                                $newFileName = "challan_" . date("YmdHis") . time() . "." . $fileExtension;

                                                $targetFilePath = $targetDir . $newFileName;

                                                $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

                                                // Allow certain file formats
                                                $allowTypes = array('jpg', 'jpeg', 'png', 'pdf');

                                                if (in_array($fileType, $allowTypes)) {
                                                    if (!move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
                                                        echo "<script>alert('Error uploading file..!!');window.location = window.location;</script>";
                                                        exit;
                                                    }
                                                } else {
                                                    echo "<script>alert('Only JPG, JPEG, PNG & GIF files are allowed..!!');window.location = window.location;</script>";
                                                    exit;
                                                }
                                            }

                                            $name_of_person = $_POST['name_of_person'];
                                            $Vehicle_Number = $_POST['Vehicle_Number'];

                                            foreach ($orderid as $orderidd) {
                                                if ($action_request == 1) {
                                                    $transferPending = getInvReqByById($orderidd);
                                                    $data_item_sr_master = array();
                                                    $data_item_sr_master['status'] = 7;
                                                    $data_item_sr_master['update_datetime'] = date("Y-m-d H:i:s");
                                                    $whereitem_sr_master = "item_code='$transferPending->item_code' AND retailer_id='$transferPending->frm_retailer_id' and batch_no='$transferPending->batch_no' and status='8'";
                                                    $limit_item_sr_master = $transferPending->req_qty;
                                                    $updateIn = updateIn('item_sr_master', $data_item_sr_master, $whereitem_sr_master, $limit_item_sr_master);

                                                    $data = getInvReqByRetailerDetailsByOrderId($orderidd);
                                                    $purchase_price = getBatchNumberPurchasePriceItems($transferPending->frm_retailer_id, $transferPending->batch_no, $transferPending->item_code, date("Y-m-d", strtotime($transferPending->expire_date)));
                                                    $ins_data = array();
                                                    $ins_data['retailer_id'] = $data->retailer_id;
                                                    $ins_data['company_id'] = getRetailerCompanyIdById($_SESSION['id']);
                                                    $ins_data['dispatch_retailer_id'] = $_SESSION['id'];
                                                    $ins_data['po_no'] = $data->order_no;
                                                    $ins_data['ref_po_no'] = $data->po_no;
                                                    $ins_data['vendor_id'] = $data->vendor_id;
                                                    $ins_data['po_basic'] = $purchase_price->purchase_basic;
                                                    $ins_data['po_gst'] = $purchase_price->gst;
                                                    $ins_data['po_total'] = $purchase_price->total;
                                                    $ins_data['po_total_basic_value'] = ($purchase_price->purchase_basic * $data->req_qty);
                                                    $ins_data['po_date'] = date("Y-m-d");
                                                    $ins_data['item_desc'] = $data->item_code;
                                                    $ins_data['billed_qty'] = $data->req_qty;
                                                    if (!empty($name_of_person)) {
                                                        $ins_data['name_of_person'] = $name_of_person;
                                                    }
                                                    if (!empty($Vehicle_Number)) {
                                                        $ins_data['Vehicle_Number'] = $Vehicle_Number;
                                                    }
                                                    if (!empty($transferPending->batch_no)) {
                                                        $ins_data['batch_number'] = $transferPending->batch_no;
                                                    }
                                                    if (empty($transferPending->manufacturing_date)) {
                                                        if (!empty(getBatchNumberManufacturingDate($transferPending->frm_retailer_id, $transferPending->batch_no, $transferPending->item_code))) {
                                                            $ins_data['manufacture_date'] = getBatchNumberManufacturingDate($transferPending->frm_retailer_id, $transferPending->batch_no, $transferPending->item_code);
                                                        }
                                                    } else {
                                                        $ins_data['manufacture_date'] = date("Y-m-d", strtotime($transferPending->manufacturing_date));
                                                    }
                                                    if (!empty($transferPending->expire_date)) {
                                                        $ins_data['expire_date'] = date("Y-m-d", strtotime($transferPending->expire_date));
                                                    }
                                                    $bill_no = getItemBillNoByBatchNoandExpireDate($data->item_code, $transferPending->batch_no, date("Y-m-d", strtotime($transferPending->expire_date)));
                                                    if (!empty($bill_no)) {
                                                        $ins_data['bill_no'] = $bill_no;
                                                    }
                                                    $ins_data['date_time'] = date('Y-m-d H:i:s');
                                                    $ins1 = insert('inventory_grn', $ins_data);
                                                    if ($ins1) {
                                                        $upd = array();
                                                        if (!empty($Vehicle_Number)) {
                                                            $upd['Vehicle_Number'] = $Vehicle_Number;
                                                        }
                                                        if (!empty($name_of_person)) {
                                                            $upd['name_of_person'] = $name_of_person;
                                                        }
                                                        $upd['status'] = '2';
                                                        $upd['challan_copy'] = $newFileName;
                                                        $upd['dispatch_date'] = date('Y-m-d H:i:s');
                                                        $wwhr = "id='$orderidd' and status = '1'";
                                                        $ins = update('retailer_stock_transfer', $upd, $wwhr);
                                                        $msgg = "Order Dispatch Successfully";
                                                    } else {
                                                        $msgg = "Something wrong.";
                                                    }
                                                } else {
                                                    $transferPending = getInvReqByById($orderidd);
                                                    $data_item_sr_master = array();
                                                    $data_item_sr_master['status'] = 0;
                                                    $data_item_sr_master['po_date'] = date("Y-m-d");
                                                    $data_item_sr_master['update_datetime'] = date("Y-m-d H:i:s");
                                                    $whereitem_sr_master = "item_code='$transferPending->item_code' AND retailer_id='$transferPending->frm_retailer_id' and batch_no='$transferPending->batch_no' and status='8'";
                                                    $limit_item_sr_master = $transferPending->req_qty;
                                                    $updateIn = updateIn('item_sr_master', $data_item_sr_master, $whereitem_sr_master, $limit_item_sr_master);

                                                    $upd = array();
                                                    $upd['status'] = '7';
                                                    $upd['ctrl_off_flag'] = '7';
                                                    $upd['dispatch_date'] = date('Y-m-d H:i:s');
                                                    $wwhr = "id='$orderidd' and status = '1'";
                                                    $ins = update('retailer_stock_transfer', $upd, $wwhr);
                                                    $msgg = "Order has been rejected";
                                                }
                                            }

                                            if ($ins) {
                                                echo "<script>alert('" . $msgg . "...!!');window.location = window.location;</script>";
                                                exit;
                                            } else {
                                                echo "<script>alert('" . $msgg . "...!!');window.location = window.location;</script>";
                                                exit;
                                            }
                                        }
                                        ?>
                                    </div>
                                </div><!-- /.row -->
                            </div>
                        </div><!-- /.row -->
                    </div><!-- /.page-content -->
                </div>
            </div><!-- /.main-content -->

            <script type="text/javascript">
                function dispatch_item__(oredrno) {

                    if (confirm("Are you sure you want to Dispatch this?")) {
                        // $.ajax({
                        //     type: 'POST',
                        //     url: '<?php echo $ajax_inward; ?>',
                        //     data: {
                        //         oredrno: oredrno,
                        //         'request_type': 'dispatch_req_stock'
                        //     },
                        //     success: function(result) {
                        //         result = $.trim(result);
                        //         if (result == 0) {
                        //             alert('Your Order Dispatch Successfully...!!');
                        //             window.location = window.location;
                        //         } else {
                        //             alert('Order Dispatch Error...!!');
                        //         }
                        //     }
                        // });
                    }
                }
            </script>
            <!--END MAIN WRAPPER -->
            <?php require_once 'includes/footer.php'; ?>

        </div>
    </body>

</html>