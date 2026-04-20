<?php
include 'includes/session.php';
//include 'includes/common_function.php';
$bdm_id = $_SESSION['id'];
$retailer_string = getAllAssignRetailerIdByZomId($bdm_id);

// $teratory_manager_id = array(10, 24);

// if (!in_array($bdm_id, $teratory_manager_id)) {
//     echo "<script>location.href='retailer_po_required_item_approve.php?menu=404';</script>";
//     exit;
// }
?>
<!DOCTYPE html>
<html lang="en">
<?php include 'includes/header.php'; ?>

<body class="no-skin">
    <?php include 'includes/menu.php'; ?>
    <div class="main-container ace-save-state" id="main-container">
        <?php include 'includes/left_sidebar.php'; ?>
        <div class="main-content">
            <div class="main-content-inner">
                <?php include 'includes/breadcrumbs.php'; ?>
                <div class="page-content">
                    <?php include 'includes/page-header.php'; ?>
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="page-header">
                                        <div class="widget-box">
                                            <div class="widget-header">

                                                <h3 class="col-sm-6 col-md-6 header-text" style="margin-top:-0.01%;">Retailer PO Order Item [ TM ]</h3>

                                                <a href="report_for_po_item_list_ttm.php?menu=442" target="_blank" class="btn btn-warning btn-sm col-sm-2 col-md-2" style="float:right;">Report</a>

                                            </div>
                                            <div class="widget-body">
                                                <div class="widget-main">
                                                    <form class="form-inline center" action="" method="POST">
                                                        <div class="row">

                                                            <div class="form-group">
                                                                <div class="col-xs-14">
                                                                    <b>Select Retailer :</b>
                                                                    <div class="input-group ">
                                                                        <select class="form-control col-xs-8 col-sm-8 col-md-8" name="Retailer_id" id="Retailer_id" required="required">
                                                                            <option value="0">All</option>
                                                                            <?php foreach (getPORequestedRetailerByBDMID($retailer_string) as $retailers) { ?>
                                                                                <option value="<?php echo $retailers->retailer_id; ?>"><?php echo getRetailerNameById($retailers->retailer_id); ?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <br />
                                                            <br />
                                                            <div class="form-group">
                                                                <div class="col-xs-14">
                                                                    <b>From Date :</b>
                                                                    <div class="input-group ">
                                                                        <input type="date" class="form-control" name="from_date" id="from_date" required="required" />

                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="col-xs-14">
                                                                    <b>To Date :</b>
                                                                    <div class="input-group ">
                                                                        <input type="date" class="form-control" name="to_date" id="to_date" required="required" />

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
                                    <?php
                                    if (isset($_POST['show'])) {
                                        $Retailer_id = $_POST['Retailer_id'];
                                    ?>
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="row">
                                                    <div>
                                                        <table id="dynamic-table" class="table table-bordered table-hover">
                                                            <thead class="thead-dark">
                                                                <tr>
                                                                    <th align="left">Srno</th>
                                                                    <th align="left">Retailer</th>
                                                                    <th align="left">ItemName</th>
                                                                    <th align="left">ItemCategory</th>
                                                                    <th align="left">ItemBrand</th>
                                                                    <th align="left">RequestDate</th>
                                                                    <th align="left">LiquidationDays</th>
                                                                    <th align="left">Remarks</th>
                                                                    <th align="left">AvailableStock</th>
                                                                    <th align="left">RequiredQTY</th>
                                                                    <th align="left">BdmApprovedQTY</th>
                                                                    <th align="left">BdmApprovedDate</th>
                                                                    <th align="left">TMApprovedQTY</th>
                                                                    <th align="left">Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $from_date = date('Y-m-d', strtotime($_POST['from_date']));
                                                                $to_date = date('Y-m-d', strtotime($_POST['to_date']));
                                                                $where = "status = '2' and date(added_time) >= '$from_date' and date(added_time) <= '$to_date'";
                                                                if ($Retailer_id == 0) {
                                                                    $where .= " and retailer_id in ($retailer_string)";
                                                                } else {
                                                                    $where .= " and retailer_id = '$Retailer_id'";
                                                                }
                                                                $purchaseOrder = getRetailerStringPoOrderItemListByBDMRetailerId($where);
                                                                $i = 1;
                                                                foreach ($purchaseOrder as $row) {
                                                                    $main_category_id = getItemParentCategoryIdItemcode($row->item_code);
                                                                ?>
                                                                    <tr id="tr_<?php echo $row->id; ?>">
                                                                        <td><?php echo $i; ?></td>
                                                                        <td><?php echo getRetailerDataById($row->retailer_id)->name; ?></td>
                                                                        <td><?php echo $row->item_desc; ?></td>
                                                                        <td><?php echo getCategoryNameById($main_category_id); ?></td>
                                                                        <td><?php echo getproductBrandNameById($row->item_code); ?></td>
                                                                        <td><?php echo date('d M Y', strtotime($row->added_time)); ?></td>
                                                                        <td><?php echo $row->Liquidation_Days; ?></td>
                                                                        <td><?php echo $row->remarks; ?></td>
                                                                        <td><?php echo $row->available_stck; ?></td>
                                                                        <td><?php echo $row->qty; ?></td>
                                                                        <td><?php echo $row->bdm_qty; ?></td>
                                                                        <td><?php echo date('Y-m-d',strtotime($row->bdm_approve_date)); ?></td>
                                                                        <td><input type="text" name="bdm_qty" id="bdm_qty_<?php echo $row->id; ?>" value="<?php echo $row->qty; ?>" size="5" required /></td>
                                                                        <td><button type="button" class="btn btn-success btn-xs" onclick="approve_po_item('<?php echo $row->id; ?>');">Update</button> </td>
                                                                    </tr>
                                                                <?php
                                                                    $i++;
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
            function approve_po_item(id) {
                var bdm_qty = document.getElementById('bdm_qty_' + id).value;
                $.ajax({
                    type: 'POST',
                    url: 'ajax_js.php',
                    data: {
                        request_type: 'approve_retailer_po_item_ttm',
                        id: id,
                        bdm_qty: bdm_qty
                    },
                    success: function(result) {
                        result = $.trim(result);
                        if (result == 0) {
                            document.getElementById('tr_' + id).remove();
                        } else {
                            alert('Quentity Approve Error...!!');
                        }
                    }
                });
            }
        </script>
        <!--END MAIN WRAPPER -->
        <?php include 'includes/footer.php'; ?>

    </div>
</body>

</html>