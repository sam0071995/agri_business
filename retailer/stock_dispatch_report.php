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
                                        <h3 class="header">Stock Dispatch Report</h3>

                                        <div class="row">
                                            <div class="col-xs-12">

                                                <div class="form-group">
                                                    <div class="col-xs-14">
                                                        <form class="form-inline center" id="searchform" name="myForm" role="form" action="" method="POST" enctype="multipart/form-data">
                                                            <b>From Date :</b>
                                                            <div class="input-group">
                                                                <input class="form-control date-picker" id="id-" name="dateone" type="text" value="<?php
                                                                if (isset($_POST['dateone'])) {
                                                                    echo $_POST['dateone'];
                                                                } else {
                                                                    echo date('d-m-Y');
                                                                }
                                                                ?>" data-date-format="dd-mm-yyyy" />
                                                                <span class="input-group-addon">
                                                                    <i class="fa fa-calendar bigger-110"></i>
                                                                </span>
                                                            </div>
                                                            <b>To Date :</b>
                                                            <div class="input-group">
                                                                <input class="form-control date-picker" id="id-" name="toone" type="text" value="<?php
                                                                if (isset($_POST['toone'])) {
                                                                    echo $_POST['toone'];
                                                                } else {
                                                                    echo date('d-m-Y');
                                                                }
                                                                ?>" data-date-format="dd-mm-yyyy" />
                                                                <span class="input-group-addon">
                                                                    <i class="fa fa-calendar bigger-110"></i>
                                                                </span>
                                                            </div>
                                                            <button type="submit" name="submit" class="btn btn-info">
                                                                <i class="ace-icon fa fa-check bigger-110"></i>
                                                                Show
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>

                                            </div>
                                        </div><!-- /.row -->
                                        <?php
                                        if (isset($_POST['submit'])) {
                                            $dateone = date("Y-m-d", strtotime($_POST['dateone']));
                                            $toone = date("Y-m-d", strtotime($_POST['toone']));
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
                                                                        <th width="15%" align="left">OrderNo</th>
                                                                        <th width="15%" align="left">ItemName</th>
                                                                        <th width="25%" align="left">RequestDistributer</th>
                                                                        <th width="15%" align="left">Qty</th>
                                                                        <th width="15%" align="left">BatchNo</th>
                                                                        <th width="15%" align="left">ExpiryDate</th>
                                                                        <th width="15%" align="left">DispacthDate</th>
                                                                        <th width="15%" align="left">Challan Copy</th>
                                                                        <th width="15%" align="left"></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $i = 1;
                                                                    $purchaseOrder = getDispatchStockReport($_SESSION['id'], $dateone, $toone);
                                                                    if (count($purchaseOrder) > 0 && !empty($purchaseOrder)) {
                                                                        foreach ($purchaseOrder as $row) {
                                                                            ?>
                                                                            <tr>
                                                                                <td><?php echo $i; ?></td>
                                                                                <td><?php echo $row->order_no; ?></td>
                                                                                <td><?php echo getInventoryItemNameByCode($row->item_code); ?></td>
                                                                                <td><?php echo getRetailerDataById($row->retailer_id)->name; ?></td>
                                                                                <td><?php echo $row->req_qty; ?></td>
                                                                                <td><?php echo $row->batch_no; ?></td>
                                                                                <td><?php echo $row->expire_date; ?></td>
                                                                                <td><?php echo date("d-m-Y", strtotime($row->dispatch_date)); ?></td>
                                                                                <td><a href="challan_copy/<?php echo $row->challan_copy; ?>" target="_blank">Download</a></td>
                                                                                <td><a href="transfer_invoice.php?menu=1&orderNo=<?php echo base64_encode($row->order_no); ?>" target="_blank"><button class="btn-primary">Print</button></a></td>
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
                                        <?php } ?>
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
                        $.ajax({
                            type: 'POST',
                            url: '<?php echo $ajax_inward; ?>',
                            data: {
                                oredrno: oredrno,
                                'request_type': 'dispatch_req_stock'
                            },
                            success: function (result) {
                                result = $.trim(result);
                                if (result == 0) {
                                    alert('Your Order Dispatch Successfully...!!');
                                    window.location = window.location;
                                } else {
                                    alert('Order Dispatch Error...!!');
                                }
                            }
                        });
                    }
                }
            </script>
            <!--END MAIN WRAPPER -->
            <?php require_once 'includes/footer.php'; ?>

        </div>
    </body>

</html>