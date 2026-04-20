<?php
require_once 'includes/session.php';
require_once 'includes/common_function.php';
if (isset($_POST['show'])) {
    $date_1 = date("Y-m-d", strtotime($_POST['date_1']));
    $date_2 = date("Y-m-d", strtotime($_POST['date_2']));
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
                                <div class="page-header">
                                    <div class="widget-box">
                                        <div class="widget-header">
                                            <h4 class="widget-title">Retailer | Stock Inward History.</h4>
                                        </div>
                                        <div class="widget-body">
                                            <div class="widget-main">
                                                <form class="form-inline center" action="" method="POST">
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <div class="col-xs-14">
                                                                <b>From Date :</b>
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
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-xs-14">
                                                                <b>To Date :</b>
                                                                <div class="input-group">
                                                                    <input class="form-control date-picker" id="" name="date_2"  type="text" value="<?php
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
                                                        </div>
                                                        <div class="clearfix form-actions">
                                                            <div class="col-md-offset-3 col-md-5">
                                                                <button class="btn btn-info" type="submit" name="show" value="show">
                                                                    <i class="ace-icon fa fa-check bigger-110"></i>
                                                                    Show
                                                                </button>

                                                                &nbsp; &nbsp; &nbsp;
                                                                <button class="btn" type="reset">
                                                                    <i class="ace-icon fa fa-undo bigger-110"></i>
                                                                    Reset
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-12">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="row">
                                            <h3 class="header-text">Inward Report.</h3>
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
                                                                    <th width="15%" align="left">Date</th>
                                                                    <th width="15%" align="left">Retailer Name</th>
                                                                    <th width="15%" align="left">From Retailer Name</th>
                                                                    <th width="15%" align="left">Supplier Name</th>
                                                                    <th width="15%" align="left">Purchase No</th>
                                                                    <th width="15%" align="left">ItemName</th>
                                                                    <th width="15%" align="left">ItemCount</th>
                                                                    <th width="15%" align="left">Batch</th>
                                                                    <th width="15%" align="left">Expiry</th>
                                                                    <th width="15%" align="left">ManufactureDate</th>
                                                                    <th width="15%" align="left">InwardDate</th>
                                                                    <th width="15%" align="left">Status</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                if (isset($_POST['show'])) {
                                                                    $date_1 = date("Y-m-d", strtotime($_POST['date_1']));
                                                                    $date_2 = date("Y-m-d", strtotime($_POST['date_2']));
                                                                    $i = 1;
                                                                    $purchaseOrder = getInwardDataByRetailerId($_SESSION['id'], $date_1, $date_2);
                                                                    foreach ($purchaseOrder as $row) {
                                                                        $status = "";
                                                                        if ($row->status == 0) {
                                                                            $status = "Inward Pending";
                                                                        } else if ($row->status == 1) {
                                                                            $status = "Inwarded";
                                                                        } else if ($row->status == 7) {
                                                                            $status = "Inward Rejected";
                                                                        } else {
                                                                            $status = "No Status";
                                                                        }
                                                                        ?>
                                                                        <tr>
                                                                            <td><?php echo $i; ?></td>
                                                                            <td><?php echo date('d M Y', strtotime($row->date_time)); ?></td>
                                                                            <td><?php echo getRetailerNameById($row->retailer_id); ?></td>
                                                                            <td><?php echo getRetailerNameById($row->dispatch_retailer_id); ?></td>
                                                                            <td><?php echo $row->supplier_name; ?></td>
                                                                            <td><?php echo $row->po_no; ?></td>
                                                                            <td><?php echo getItemNameByItemCode($row->item_desc); ?></td>
                                                                            <td><?php echo $row->billed_qty; ?></td>
                                                                            <td><?php echo $row->batch_number; ?></td>
                                                                            <td><?php echo date('d M Y', strtotime($row->expire_date)); ?></td>
                                                                            <td><?php echo date('d M Y', strtotime($row->manufacture_date)); ?></td>
                                                                            <td><?php echo date('d M Y', strtotime($row->retailer_inwd_date)); ?></td>
                                                                            <td><?php echo $status; ?></td>
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
                function inward_item(id) {
                    alert(id);
                    if (confirm("Are you sure you want to Inward this?")) {
                        $.ajax({
                            type: 'POST',
                            url: '<?php echo $ajax_inward; ?>',
                            data: {
                                'id': id,
                                'request_type': 'inward_grn'
                            },
                            success: function (result) {
                                result = $.trim(result);
                                if (result == 0) {
                                    ;
                                    alert('Your Item Inward Successfully...!!');
                                } else {
                                    alert('Item Inward Error...!!');
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