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
                                                    <h4 class="widget-title">Retailer | Stock Inward History.</h4>
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
                                                                                <option value="00">-- Select Item --</option>
                                                                                <?php foreach (getProductsList() as $itemr) { ?>
                                                                                    <option style="text-align:left;" value="<?= $itemr->item_code; ?>"><?= $itemr->item_desc; ?></option>
                                                                                <?php } ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group">
                                                                    <div class="col-xs-14">
                                                                        <b>Select Retailer :</b>
                                                                        <div class="input-group">
                                                                            <select class="form-control col-xs-3" name="Retailer_id" id="Retailer_id" required="required">
                                                                                <option value="All">All Retailers</option>
                                                                                <?php foreach (getAllRetailerDetails($company_id_in) as $active_sellers) { ?>
                                                                                    <option value="<?php echo $active_sellers->id; ?>" <?php
                                                                                    if ($retailer_id == $active_sellers->id) {
                                                                                        echo 'selected="selected"';
                                                                                    }
                                                                                    ?>><?php echo $active_sellers->name; ?><?php
                                                                                                if ($active_sellers->status == 0) {
                                                                                                    echo '<b class="red"> [Clossed]</b>';
                                                                                                }
                                                                                                ?></option>
                                                                                <?php } ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group">
                                                                    <div class="col-xs-14">
                                                                        <b>Select Status :</b>
                                                                        <div class="input-group">
                                                                            <select class="form-control col-xs-3" name="status_filter" id="status_filter" required="required">
                                                                                <option value="1">Success</option>
                                                                                <option value="0">Pending</option>
                                                                                <option value="7">Rejected</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="col-xs-14">
                                                                        <b>Filter :</b>
                                                                        <div class="input-group">
                                                                            <select class="form-control col-xs-3" name="status_report" id="status_report" required="required">
                                                                                <option value="All">All</option>
                                                                                <option value="0">Purchase</option>
                                                                                <option value="1">Transfer</option>
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
                                                                    <th width="15%" align="left">PO Date</th>
                                                                    <th width="15%" align="left">Invoice Date</th>
                                                                    <th width="15%" align="left">Supplier Name</th>
                                                                    <th width="15%" align="left">From Retailer Name</th>
                                                                    <th width="15%" align="left">From Retailer Current Stock</th>
                                                                    <th width="15%" align="left">Retailer Name</th>
                                                                    <th width="15%" align="left">Purchase No</th>
                                                                    <th width="15%" align="left">Invoice No</th>
                                                                    <th width="15%" align="left">ItemName</th>
                                                                    <th width="15%" align="left">PurchQty</th>
                                                                    <th width="15%" align="left">TranQty</th>
                                                                    <th width="15%" align="left">SaleCount</th>
                                                                    <th width="15%" align="left">Current Stock</th>
                                                                    <th width="15%" align="left">Batch</th>
                                                                    <th width="15%" align="left">Expiry</th>
                                                                    <th width="15%" align="left">ManufactureDate</th>
                                                                    <th width="15%" align="left">InwardDate</th>
                                                                    <th width="15%" align="left">Po Basic</th>
                                                                    <th width="15%" align="left">Po Gst Rate</th>
                                                                    <th width="15%" align="left">Transfered VehicleNo</th>
                                                                    <th width="15%" align="left">inwarded VehicleNo</th>
                                                                    <th width="15%" align="left">transfered name-of-person</th>
                                                                    <th width="15%" align="left">inwarded name-of-person</th>
                                                                    <th width="15%" align="left">Status</th>
                                                                    <th width="15%" align="left">Batch qty</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                if (isset($_POST['show'])) {
                                                                    $date_1 = date("Y-m-d", strtotime($_POST['date_1']));
                                                                    $date_2 = date("Y-m-d", strtotime($_POST['date_2']));
                                                                    $retailer_id = 'All';
                                                                    if (isset($_POST['Retailer_id'])) {
                                                                        $retailer_id = $_POST['Retailer_id'];
                                                                    }
                                                                    $item_code = $_POST['item_code'];
                                                                    $status_filter = $_POST['status_filter'];
                                                                    $status_report = $_POST['status_report'];
                                                                    $status = 0;
                                                                    $i = 1;
//                                                                    $purchaseOrder = getInwardDataByRetailerId($date_1, $date_2, $retailer_id, $company_id_in, $status_filter, $item_code);
                                                                    $purchaseOrder = getInwardDataByRetailerIdFilterJoinReport($date_1, $date_2, $retailer_id, $company_id_in, $status_filter, $item_code, $status_report);
                                                                    foreach ($purchaseOrder as $row) {
                                                                        $transfered_qty = 0;
                                                                        $purchase_qty = 0;
                                                                        if ($row->dispatch_retailer_id == 0) {
                                                                            $purchase_qty = $row->billed_qty;
                                                                        } else {
                                                                            $transfered_qty = $row->billed_qty;
                                                                        }
                                                                        $status = "";
                                                                        if ($row->retailer_inwd_flg == 0) {
                                                                            $status = "Inward Pending";
                                                                        } else if ($row->retailer_inwd_flg == 1) {
                                                                            $status = "Inwarded";
                                                                        } else if ($row->retailer_inwd_flg == 7) {
                                                                            $status = "Inward Rejected";
                                                                        } else {
                                                                            $status = "No Status";
                                                                        }

                                                                        $sale_countt = getRetailerSalesDetailByBatchNumberInwardNo($row->retailer_id, $row->item_desc, $row->batch_number, $row->po_no, round($purchase_qty));
//                                                                        if ($sale_countt > $purchase_qty) {
//                                                                            $sale_countt = $purchase_qty;
//                                                                        }
                                                                        ?>
                                                                        <tr>
                                                                            <td><?php echo $i; ?></td>
                                                                            <td><?php echo date('d M Y', strtotime($row->po_date)); ?></td>
                                                                            <td><?php echo date('d M Y', strtotime($row->date_time)); ?></td>
                                                                            <td><?php echo $row->supplier_name; ?></td>
                                                                            <td><?php echo getRetailerNameById($row->dispatch_retailer_id); ?></td>
                                                                            <td><?php echo getItemSrMasterDataByItemIdAndRetailerIdBatchNoCount($row->dispatch_retailer_id, $row->item_desc, $row->batch_number); ?></td>
                                                                            <td><?php echo getRetailerNameById($row->retailer_id); ?></td>
                                                                            <td><?php echo $row->po_no; ?></td>
                                                                            <td><?php echo $row->bill_no; ?></td>
                                                                            <td><?php echo getItemNameByItemCode($row->item_desc); ?></td>
                                                                            <td><?php echo amount($purchase_qty); ?></td>
                                                                            <td><?php echo amount($transfered_qty); ?></td>
                                                                            <td><?php echo amount($sale_countt); ?></td>
                                                                            <td><?php echo getItemSrMasterDataByItemIdAndRetailerIdBatchNoCount($row->retailer_id, $row->item_desc, $row->batch_number); ?></td>
                                                                            <td><?php echo $row->batch_number; ?></td>
                                                                            <td><?php echo date('d M Y', strtotime($row->expire_date)); ?></td>
                                                                            <td><?php echo date('d M Y', strtotime($row->manufacture_date)); ?></td>
                                                                            <td><?php echo date('d M Y', strtotime($row->retailer_inwd_date)); ?></td>
                                                                            <td><?php echo $row->po_basic; ?></td>
                                                                            <td><?php echo $row->po_gst; ?></td>
                                                                            <td><?php echo $row->Vehicle_Number; ?></td>
                                                                            <td><?php echo $row->inward_Vehicle_Number; ?></td>
                                                                            <td><?php echo $row->name_of_person; ?></td>
                                                                            <td><?php echo $row->inward_name_of_person; ?></td>
                                                                            <td><?php echo $status; ?></td>
                                                                            <td><?php echo getToalInwardedBatchBlockedQty($row->retailer_id, $row->batch_number, $row->item_desc, $row->po_no); ?></td>
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