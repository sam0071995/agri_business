<?php
error_reporting(0);
require_once 'includes/session.php';
require_once 'includes/common_function.php';
?>
<!DOCTYPE html>
<html lang="en">
    <style>
        #ms-list-1{
            width: 300px;
        }
    </style>
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
                                    <div class="align-right">
                                        <a href="generate_po_order_print.php?menu=425"><button class="btn btn-primary">New Pre Purchase Order </button></a>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12">

                                        <div class="row">
                                            <h3 class="header-text">Pre Purchase Goods Order</h3>
                                            <div class="col-xs-12">
                                                <div class="row">
                                                    <div class="modal-body">
                                                        <div class="row clearfix">
                                                            <div class="pull-right tableTools-container"></div>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <table id="dynamic-table" class="table table-bordered table-hover">
                                                            <thead class="thead-dark">
                                                                <tr>
                                                                    <th width="8%" align="left">#</th>
                                                                    <th width="10%" align="left">Purchase No</th>
                                                                    <th width="10%" align="left">Purchase Date</th>
                                                                    <th width="40%" align="left">Supplier</th>
                                                                    <th width="10%" align="left">Store And Item</th>
                                                                    <!--<th width="40%" align="left">Item</th>-->
                                                                    <th width="10%" align="left">Net Amount</th>
                                                                    <th width="10%" align="left">ActionStatus</th>
                                                                    <th width="10%" align="left">Status</th>
                                                                    <th width="40%" align="left">Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $status = 0;
                                                                $i = 1;
                                                                $where = '';
                                                                $companyidd = $_SESSION['company_id'];
                                                                $useridd = $_SESSION['id'];
                                                                if ($_SESSION['admin_flag'] == 1) {
                                                                    $where .= "company_id in ($companyidd)";
                                                                } else {
                                                                    $where .= "user_id ='$useridd'";
                                                                }
                                                                $purchaseOrder = getPurchaseOrderListByStatusForBasic($where);
                                                                if (count($purchaseOrder) > 0 && !empty($purchaseOrder)) {
                                                                    foreach ($purchaseOrder as $row) {
                                                                        if ($row->status == 0) {
                                                                            $status = "Po Genereted";
                                                                        } else if ($row->status == 1) {
                                                                            $status = "Order Approved & Placed";
                                                                        } else if ($row->status == 2) {
                                                                            $status = "Goods In Transit";
                                                                        } else if ($row->status == 3) {
                                                                            $status = "Goods Recieved";
                                                                        }
                                                                        ?>

                                                                        <tr>
                                                                            <td><?php echo $i; ?></td>
                                                                            <td><?php echo $row->po_no; ?></td>
                                                                            <td><?php echo date('d-M-Y', strtotime($row->po_date)); ?></td>
                                                                            <td><?php echo $row->supplier_id; ?></td>
                                                                            <td width="40%" style="white-space: nowrap;">

                                                                                <?php
                                                                                $Pdetails = getPurchaseOrderListByStatusForBasicDetails($row->id);
                                                                                foreach ($Pdetails as $Pdetail) {
                                                                                    $rateilar_string = explode(',', $Pdetail->retailer_string);
                                                                                    for ($l = 0; $l < count($rateilar_string); $l++) {
                                                                                        echo $l + 1 . ". " . getRetailerNameById($rateilar_string[$l]) . ' <br/>';
                                                                                    }
                                                                                    echo " <br/>";
                                                                                    echo " ItemName : " . getItemNameByItemCode($Pdetail->item_id) . " | Qty : " . $Pdetail->qty . " | UnitPrice : " . $Pdetail->rate;
                                                                                    echo '<hr/>';
                                                                                }
                                                                                ?>
                                                                            </td>
                                                                            <td><?php echo $row->grand_total; ?> <b>-/ Rs.</b></td>
                                                                            <td >
                                                                                <select name="status_val" id="status_val_<?php echo $i; ?>" >
                                                                                    <option value="1" <?php echo ($row->status == 1) ? "selected='selected'" : ''; ?>>Order Approved & Placed</option>
                                                                                    <option value="2" <?php echo ($row->status == 2) ? "selected ='selected'" : ''; ?>>Goods In Transit</option>
                                                                                    <option value="3" <?php echo ($row->status == 3) ? "selected='selected'" : ''; ?>>Goods Received</option>
                                                                                </select>
                                                                                <br>
                                                                                <br>
                                                                                <input type="text" id="status_remark_<?php echo $i; ?>" placeholder="Enter Remark Here.." name="status_remark_<?php echo $i; ?>" />
                                                                            </td>
                                                                            <td width='25%'>
                                                                                <b>Status : <?php echo $status; ?> </b>
                                                                                <br>
                                                                                <br>
                                                                                <b>Last Update Status : <?php echo (!empty($row->status_upd_date)) ? date('Y-m-d H:i:s', strtotime($row->status_upd_date)) : ''; ?> </b>
                                                                                <br>
                                                                                <br>
                                                                                <b>Remark : <?php echo $row->status_remarks; ?> </b>


                                                                            </td>
                                                                            <td width='40%' style="white-space: nowrap;">
                                                                                <button class="btn btn-xs btn-default" onclick="UpdateCurrentStatus('<?php echo $i; ?>', '<?php echo $row->po_no; ?>');return false;">StatusUpdate</button><br><br>

                                                                                <a href='generate_new_po_edit_for_basic.php?menu=425&purchase_id="<?php echo base64_encode($row->id); ?>'><button type='button' class='btn-success' style='cursor:pointer' title='Click to Edit'>Edit</button></a>

                                                                                <button type='button' class='btn-danger' style='cursor:pointer' title='Click to Delete' onclick='delete_purchase("<?php echo $row->id; ?>")'>Delete</button>

                                                                                <a target='_blank' href='inventory_purchase_report_for_basic.php?menu=425&po_no="<?php echo base64_encode($row->po_no); ?>"'><button type='button' class='btn-warning' style='cursor:pointer' title='Click to Print'>Print</button></a>


                                                                            </td>
                                                                        </tr>
                                                                        <?php
                                                                        $i++;
                                                                    }
                                                                }
                                                                ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- /.row -->
                                    </div>
                                </div><!-- /.row -->
                            </div>
                        </div><!-- /.row -->
                    </div><!-- /.page-content -->
                </div>
            </div><!-- /.main-content -->

            <script type="text/javascript">

                function delete_purchase(purchase_id)
                {
                    if (confirm("Are you sure you want to Delete this?")) {
                        $.ajax({
                            type: 'POST',
                            url: 'delete_purchase_order_for_basic.php?menu=425',
                            data: {'purchase_id': purchase_id},
                            success: function (result) {
                                result = $.trim(result);
                                if (result == 1) {
                                    window.location.href = "purchase_order_for_basic.php?menu=425&success=delete";
                                } else {
                                    alert('Something Wrong Try Again.');
                                    window.location.href = "purchase_order_for_basic.php?menu=425&failure=1";
                                }
                            }
                        });
                    }
                }

                function UpdateCurrentStatus(idd, ponoo) {
                    var statusval = document.getElementById('status_val_' + idd).value;
                    var status_remark = document.getElementById('status_remark_' + idd).value;

                    $.ajax({
                        url: 'ajax_agro.php?menu=425',
                        method: 'post',
                        data: {types: 'update_status_for_basic_po', ponoo: ponoo, statusval: statusval, status_remark: status_remark},
                        success: function (resp) {
                            console.log(resp);
                            if (resp == 1) {
                                alert("Status Updated Successfully...!!!");
                            } else {
                                alert("Status Update Error...!!!");

                            }
                            window.location = window.location;
                        }
                    });
                }




                $(document).ready(function () {
                    $(window).keydown(function (event)
                    {
                        if (event.keyCode == 13) {
                            event.preventDefault();
                            return false;
                        }
                    });

                });

            </script>
            <!--END MAIN WRAPPER -->
            <?php require_once 'includes/footer.php'; ?>    

        </div>
    </body>
</html>
