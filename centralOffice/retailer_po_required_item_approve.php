<?php
require_once 'includes/session.php';
require_once 'includes/common_function.php';

$ratailer_string = '';
foreach (getActiveRetailerDetails($_SESSION['company_id']) as $data1) {
    $ratailer_string .= "'" . $data1->id . "',";
}

$ratailer_string = rtrim($ratailer_string, ',');
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

                                                    <h3  class="col-sm-6 col-md-6 header-text" style="margin-top:-0.01%;">Retailer PO Order Item</h3>

                                                    <!--<a href="report_for_po_item_list.php?menu=404" target="_blank" class="btn btn-warning btn-sm col-sm-2 col-md-2" style="float:right;">Report</a>-->

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
                                                                                <?php foreach (getPORequestedRetailerByCompanyID($ratailer_string) as $retailers) { ?>
                                                                                    <option value="<?php echo $retailers->retailer_id; ?>"><?php echo getRetailerNameById($retailers->retailer_id); ?></option>
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
                                                                        <th  align="left">Retailer</th>
                                                                        <th  align="left">ItemName</th>
                                                                        <th  align="left">ItemBrand</th>
                                                                        <th  align="left">ItemCategory</th>
                                                                        <th  align="left">RequestDate</th>
                                                                        <th  align="left">LiquidationDays</th>
                                                                        <th  align="left">Remarks</th>
                                                                        <th  align="left">AvailableStock</th>
                                                                        <th  align="left">RequiredQTY</th>
                                                                        <th align="left">ApprovedQTY</th>
                                                                        <th  align="left">Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    if ($Retailer_id == 0) {
                                                                        $where = "status = '1' and company_id in (" . $_SESSION['company_id'] . ")";
                                                                    } else {
                                                                        $where = "status = '1' and retailer_id in ($Retailer_id)";
                                                                    }
                                                                    $purchaseOrder = getRetailerStringPoOrderItemListByRetailerId($where);
                                                                    $i = 1;
                                                                    foreach ($purchaseOrder as $row) {
                                                                        ?>
                                                                        <tr id="tr_<?php echo $row->id; ?>">
                                                                            <td><?php echo $i; ?></td>
                                                                            <td><?php echo getRetailerDataById($row->retailer_id)->name; ?></td>
                                                                            <td><?php echo $row->item_desc; ?></td>
                                                                            <td><?php echo getproductBrandNameById($row->item_code); ?></td>
                                                                            <td><?php echo getCategoryNameById(getItemMainCategoryIdByItemCode($row->item_code)); ?></td>
                                                                            <td><?php echo date('d M Y', strtotime($row->added_time)); ?></td>
                                                                            <td><?php echo $row->Liquidation_Days; ?></td>
                                                                            <td><?php echo $row->remarks; ?></td>
                                                                            <td><?php echo $row->available_stck; ?></td>
                                                                            <td><?php echo $row->qty; ?></td>
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
                        url: 'ajax_agro.php',
                        data: {
                            request_type: 'approve_retailer_po_item',
                            id: id,
                            bdm_qty: bdm_qty
                        },
                        success: function (result) {
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
            <?php require_once 'includes/footer.php'; ?>

        </div>
    </body>

</html>