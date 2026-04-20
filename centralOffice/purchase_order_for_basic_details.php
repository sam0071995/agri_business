<?php
error_reporting(0);
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
                                    <h3 class="header-text">Purchase Order Report</h3>

                                    <form name="myForm" role="form" action="" method="POST" enctype="multipart/form-data">
                                        <div class="row">
                                            <div class="form-group">

                                                <div class="col-sm-3">
                                                    <label>FromDate : </label>
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
                                            <div class="col-sm-3">
                                                <label>ToDate : </label>
                                                <div class="input-group">
                                                    <input class="form-control date-picker" id="id-" name="date_2" type="text" value="<?php
                                                    if (isset($_POST['date_2'])) {
                                                        echo $_POST['date_2'];
                                                    } else {
                                                        echo date('d-m-Y');
                                                    }
                                                ?>" data-date-format="dd-mm-yyyy" />
                                                <span class="input-group-addon">
                                                    <i class="fa fa-calendar bigger-110"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <br />


                                        <button class="btn btn-info" type="submit" name="show" value="show">
                                            <i class="ace-icon fa fa-check bigger-110"></i>
                                            Show
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <?php
                            if (isset($_POST['show'])) {
                                $date_1 = date('Y-m-d', strtotime($_POST['date_1']));
                                $date_2 = date('Y-m-d', strtotime($_POST['date_2']));

                                $companyidd = $_SESSION['company_id'];
                                $purchaseOrderCounts = getPurchaseOrderListByStatusForBasicDetailsJoinWithDateCounts($companyidd, $date_1, $date_2);
                                $index = 1;
                                foreach ($purchaseOrderCounts as $purchaseOrderCount) {
                                    if ($purchaseOrderCount->status == 0) {
                                        $status = "Po Genereted";
                                    } else if ($purchaseOrderCount->status == 1) {
                                        $status = "Order Approved & Placed";
                                    } else if ($purchaseOrderCount->status == 2) {
                                        $status = "Goods In Transit";
                                    } else if ($purchaseOrderCount->status == 3) {
                                        $status = "Goods Recieved";
                                    } else if ($purchaseOrderCount->status == 4) {
                                        $status = "Material Pending";
                                    } else if ($purchaseOrderCount->status == 5) {
                                        $status = "Po Cancelled";
                                    }
                                    if ($index != 1) {
                                        echo ' | ';
                                    }
                                    echo "<b class='red'>" . $status . '</b> : <b class="blue">' . $purchaseOrderCount->count . "</b>";

                                    $index++;
                                }
                                ?>

                                <div class="row">
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
                                                            <th width="10%" align="left">Item</th>
                                                            <th width="10%" align="left">Unit</th>
                                                            <th width="10%" align="left">Quantity</th>
                                                            <th width="10%" align="left">Rate</th>
                                                            <th width="10%" align="left">Supplier</th>
                                                            <th width="10%" align="left">Store</th>
                                                            <th width="5%" align="left">Status</th>
                                                            <th width="10%" align="left">Last Update</th>
                                                            <th width="10%" align="left">Remarks</th>
                                                            <th width="10%" align="left">Print</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $status = 0;
                                                        $i = 1;
                                                        $dateD = '';

                                                        $where = '';
                                                        $companyidd = $_SESSION['company_id'];
                                                        $useridd = $_SESSION['id'];
                                                        if ($_SESSION['admin_flag'] == 1) {
                                                            $where .= "company_id in ($companyidd)";
                                                        } else {
                                                            $where .= "user_id ='$useridd'";
                                                        }
                                                        $purchaseOrder = getPurchaseOrderListByStatusForBasicDetailsJoinWithDate($companyidd, $date_1, $date_2);
                                                        if (count($purchaseOrder) > 0 && !empty($purchaseOrder)) {
                                                            foreach ($purchaseOrder as $row) {
                                                                $dateD = '';
                                                                if ($row->status == 0) {
                                                                    $dateD = $row->added_date;
                                                                    $status = "Po Genereted";
                                                                } else if ($row->status == 1) {
                                                                    $status = "Order Approved & Placed";
                                                                    $dateD = $row->status_upd_date;
                                                                } else if ($row->status == 2) {
                                                                    $dateD = $row->status_upd_date;
                                                                    $status = "Goods In Transit";
                                                                } else if ($row->status == 3) {
                                                                    $dateD = $row->status_upd_date;
                                                                    $status = "Goods Recieved";
                                                                }else if ($row->status == '4') {
                                                                    $dateD = $row->status_upd_date;
                                                                    $status = "Material Pending";
                                                                } else if ($row->status == '5') {
                                                                    $dateD = $row->status_upd_date;
                                                                    $status = "Po Cancelled";
                                                                }
                                                                ?>

                                                                <tr>
                                                                    <td><?php echo $i; ?></td>
                                                                    <td><?php echo $row->po_no; ?></td>
                                                                    <td><?php echo date('d-M-Y', strtotime($row->po_date)); ?></td>
                                                                    <!--<td><?php // echo $row->upload_invoice_no; ?></td>-->
                                                                    <td><?php echo getItemNameByItemCode($row->item_id); ?></td>
                                                                    <td><?php echo getItemUNITByItemCode($row->item_id); ?></td>
                                                                    <td><?php echo $row->qty; ?></td>
                                                                    <td><?php echo $row->rate; ?></td>
                                                                    <td><?php echo $row->supplier_id; ?></td>
                                                                    <td><?php echo getRetailerNameById($row->retailer_string); ?></td>
                                                                    <td width='25%'>
                                                                        <b><?php echo $status; ?> </b>
                                                                    </td>
                                                                    <td>
                                                                        <b><?php echo date('Y-m-d H:i:s', strtotime($dateD)); ?> </b>
                                                                    </td>
                                                                    <td><b><?php echo $row->status_remarks; ?> </b></td>
                                                                    <td width='30%'>
                                                                        <a target='_blank' href='inventory_purchase_report_for_basic.php?menu=425&po_no="<?php echo base64_encode($row->po_no); ?>&pid=<?php echo base64_encode($row->id); ?>"'><button type='button' class='btn-warning' style='cursor:pointer' title='Click to Print'>Print</button></a>&nbsp;&nbsp;
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
                                <?php
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
    function delete_purchase(purchase_id) {
        if (confirm("Are you sure you want to Delete this?")) {
            $.ajax({
                type: 'POST',
                url: 'delete_purchase_order_for_basic.php?menu=425',
                data: {
                    'purchase_id': purchase_id
                },
                success: function(result) {
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
            data: {
                types: 'update_status_for_basic_po',
                ponoo: ponoo,
                statusval: statusval,
                status_remark: status_remark
            },
            success: function(resp) {
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




    $(document).ready(function() {
        $(window).keydown(function(event) {
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