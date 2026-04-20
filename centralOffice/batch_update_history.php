<?php
require_once 'includes/session.php';
require_once 'includes/common_function.php';

$retailer_id = 'All';
if (isset($_POST['show'])) {
    $date_1 = date("Y-m-d", strtotime($_POST['date_1']));
    $date_2 = date("Y-m-d", strtotime($_POST['date_2']));
    $retailer_id = 'All';
    if (isset($_POST['Retailer_id'])) {
        $retailer_id = $_POST['Retailer_id'];
    }
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
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="page-header">
                                            <div class="widget-box">
                                                <div class="widget-header">
                                                    <h4 class="widget-title">Batch Update History.</h4>
                                                </div>
                                                <div class="widget-body">
                                                    <div class="widget-main">
                                                        <form class="form-inline center" action="" method="POST">
                                                            <div class="row">
                                                                <div class="form-group">
                                                                    <div class="col-xs-14">
                                                                        <b>PO From Date :</b>
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
                                                                        <b>PO To Date :</b>
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

                                                                <div class="form-group">
                                                                    <div class="col-xs-14">
                                                                        <b>Select Item :</b>
                                                                        <div class="input-group">
                                                                            <select class="form-control chosen-select col-xs-3" name="item_code" id="item_code" >
                                                                                <option value="">-- Select Item --</option>
                                                                                <?php foreach (getProductsList() as $itemr) { ?>
                                                                                    <option style="text-align:left;" value="<?= $itemr->item_code; ?>"><?= $itemr->item_desc; ?></option>
                                                                                <?php } ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="clearfix form-actions">
                                                                    <div class="col-md-offset-3 col-md-5">
                                                                        <button class="btn btn-info" type="submit" name="show" value="show">
                                                                            <i class="ace-icon fa fa-check bigger-110"></i>
                                                                            Show
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
                                                                    <th width="15%" align="left">USER</th>
                                                                    <th width="15%" align="left">Retailer</th>
                                                                    <th width="15%" align="left">ITEM</th>
                                                                    <th width="15%" align="left">OLD Batch</th>
                                                                    <th width="15%" align="left">NEW Batch</th>
                                                                    <th width="15%" align="left">OLD Expire</th>
                                                                    <th width="15%" align="left">NEW Expire</th>
                                                                    <th width="15%" align="left">OLD Manufacturing Date</th>
                                                                    <th width="15%" align="left">NEW Manufacturing Date</th>
                                                                    <th width="15%" align="left">QTY</th>
                                                                    <th width="15%" align="left">Date</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                if (isset($_POST['show'])) {
                                                                    $date_1 = date("Y-m-d", strtotime($_POST['date_1']));
                                                                    $date_2 = date("Y-m-d", strtotime($_POST['date_2']));
                                                                    $item_code = $_POST['item_code'];
                                                                    $status = 0;
                                                                    $i = 1;
                                                                    $purchaseOrder = getBatchUpdateHistory($item_code, $date_1, $date_2);
                                                                    foreach ($purchaseOrder as $row) {
                                                                        ?>
                                                                        <tr>
                                                                            <td><?php echo $i; ?></td>
                                                                            <td><?php echo getUserNameById($row->user_id); ?></td>
                                                                            <td><?php echo getRetailerNameById($row->retailer_id); ?></td>
                                                                            <td><?php echo getItemNameByItemCode($row->item_code); ?></td>
                                                                            <td><?php echo $row->old_batch_no; ?></td>
                                                                            <td><?php echo $row->new_batch_no; ?></td>
                                                                            <td><?php
                                                                                if (!empty($row->old_expiry_date) && $row->old_expiry_date != '1970-01-01') {
                                                                                    echo date('d M Y', strtotime($row->old_expiry_date));
                                                                                }
                                                                                ?></td>
                                                                            <td><?php
                                                                                if (!empty($row->new_expiry_date) && $row->new_expiry_date != '1970-01-01') {
                                                                                    echo date('d M Y', strtotime($row->new_expiry_date));
                                                                                }
                                                                                ?></td>
                                                                            <td><?php
                                                                                if (!empty($row->old_manu_date) && $row->old_manu_date != '1970-01-01') {
                                                                                    echo date('d M Y', strtotime($row->old_manu_date));
                                                                                }
                                                                                ?></td>
                                                                            <td><?php
                                                                                if (!empty($row->new_manu_date) && $row->new_manu_date != '1970-01-01') {
                                                                                    echo date('d M Y', strtotime($row->new_manu_date));
                                                                                }
                                                                                ?></td>
                                                                            <td><?php echo $row->qty; ?></td>
                                                                            <td><?php
                                                                                if (!empty($row->datetime) && $row->datetime != '1970-01-01') {
                                                                                    echo date('d M Y', strtotime($row->datetime));
                                                                                }
                                                                                ?></td>
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
            <!--END MAIN WRAPPER -->
            <?php require_once 'includes/footer.php'; ?>

        </div>
    </body>

</html>