<?php
require_once 'includes/session.php';
require_once 'includes/common_function.php';

//$retailer_id = 1;
$status = 1;
$retailer_id = '';
$item_code = '';
$product_id = '';
$product_title = '';
$product_category = '';
$general_category = '';
$short_description = '';
$feature_description = '';
$remarks = '';
$description = '';
$btn_name = "Submit";

if (isset($_POST['show'])) {
    $retailer_id = $_POST['Retailer_id'];
    $item_code = $_POST['item_code'];
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
                                <?php
                                if (isset($_GET['error'])) {
                                    switch ($_GET['error']) {
                                        case 1:
                                            $msg = "Item can not be insert.";
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
                                <?php if (isset($_GET['success'])) { ?>
                                    <div class="alert alert-block alert-success">
                                        <button type="button" class="close" data-dismiss="alert">
                                            <i class="ace-icon fa fa-times"></i>
                                        </button>

                                        <i class="ace-icon fa fa-check green form-error-msg"></i>
                                        <?php echo "Product Updated Successfully"; ?>
                                    </div>
                                <?php } ?>
                                <h3 class="page-header">Retailer | Track Item</h3>

                                <form name="myForm"  role="form" action="" method="POST" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-sm-4">
                                                <b>Retailer </b>
                                                <select class="form-field-select-2 form-control chosen-select" name="Retailer_id" id="Retailer_id" required="required">
                                                    <option value="">--select--</option>
                                                    <?php foreach (getActiveRetailerDetails($company_id_in) as $active_sellers) { ?>
                                                        <option value="<?php echo $active_sellers->id; ?>" <?php
                                                        if ($active_sellers->id == $retailer_id) {
                                                            echo "selected='selected'";
                                                        }
                                                        ?>><?php echo $active_sellers->name; ?> [<?php echo $active_sellers->id; ?>]</option>
                                                            <?php } ?>
                                                </select>
                                            </div>
                                            <div class="col-sm-4">
                                                <b>Item </b>
                                                <select class="form-field-select-2 form-control chosen-select" name="item_code" id="item_code" required="required">
                                                    <option value="">--select--</option>
                                                    <?php foreach (getActiveItemsList() as $active_item) { ?>
                                                        <option value="<?php echo $active_item->item_code; ?>" <?php
                                                        if ($active_item->item_code == $item_code) {
                                                            echo "selected='selected'";
                                                        }
                                                        ?>><?php echo $active_item->item_desc; ?> [<?php echo $active_item->item_code; ?>]</option>
                                                            <?php } ?>
                                                </select>
                                            </div>

                                            <button class="btn btn-info" type="submit" name="show" value="show">
                                                <i class="ace-icon fa fa-check bigger-110"></i>
                                                Show
                                            </button>
                                        </div>
                                    </div>
                                </form>
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
                                                            <th width="15%" align="left">Store Name</th>
                                                            <th width="15%" align="left">Item Name</th>
                                                            <th width="15%" align="left">Date</th>
                                                            <th width="15%" align="left">Opening</th>
                                                            <th width="15%" align="left">Inwrad</th>
                                                            <th width="15%" align="left">Outward</th>
                                                            <th width="15%" align="left">Current Stock</th>
                                                            <th width="15%" align="left">PO Basic</th>
                                                            <th width="15%" align="left">PO GST</th>
                                                            <th width="15%" align="left">Remarks</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        if (isset($_POST['show'])) {

                                                            $retailer_id = $_POST['Retailer_id'];
                                                            $item_code = $_POST['item_code'];
                                                            $date_1 = date("2021-07-01");
                                                            $date_2 = date('Y-m-d', strtotime($date . ' +1 day'));

                                                            $opening = 0;
                                                            $inward = 0;
                                                            $outward = 0;
                                                            $stock = 0;
                                                            $remarks = "";
                                                            $i = 1;
                                                            $retailer_company_id = getRetailerCompanyIdById($retailer_id);
                                                            if ($retailer_company_id == 3) {
                                                                $opening = getBackendRetailerStockTInward($retailer_id, $item_code, $date_2)->qty;
                                                            } else {
                                                                $opening = getRetailerItemOpeningStockById($item_code, $retailer_id);
                                                            }
                                                            if (empty($opening)) {
                                                                $opening = 0;
                                                            }
                                                            $po_basic = 0;
                                                            $po_gst = 0;
                                                            $opening = amount($opening);
                                                            $period = new DatePeriod(new DateTime($date_1), new DateInterval('P1D'), new DateTime($date_2));
                                                            foreach ($period as $key => $value) {
                                                                $On_date = $value->format('Y-m-d');
//                                                                if ($On_date == "2024-05-22") {
                                                                if (1 == 1) {
//inwardCounts
                                                                    $inwarded_details = getInwardedOrderDetailOnDate($retailer_id, $item_code, $On_date);
                                                                    foreach ($inwarded_details as $inwarded_detail) {
                                                                        if (isset($inwarded_detail->inward_qty)) {
                                                                            $btn_name = 1;
                                                                            $inward = $inwarded_detail->inward_qty;
                                                                            $po_basic = $inwarded_detail->po_basic;
                                                                            $po_gst = $inwarded_detail->po_gst;
                                                                            $stock = $opening + $inward;
                                                                            $outward = 0;
                                                                            $qty = 0;
                                                                            if ($inwarded_detail->dispatch_retailer_id == 0) {
                                                                                $remarks = "Inward PO : " . $inwarded_detail->po_no . " From: " . $inwarded_detail->supplier_name . " BillNo:" . $inwarded_detail->bill_no;
                                                                            } else {
                                                                                $remarks = "Transfer refNo : " . $inwarded_detail->po_no . " From: " . getRetailerNameById($inwarded_detail->dispatch_retailer_id);
                                                                            }

                                                                            $qty = getItemSrNoCountDateByPONo($item_code, $retailer_id, $inwarded_detail->po_no, $On_date);
                                                                            ?>
                                                                            <tr>
                                                                                <td><?php echo $i; ?></td>
                                                                                <td><?php echo getRetailerNameById($retailer_id) . " [" . $retailer_id . "]"; ?></td> 
                                                                                <td><?php echo getItemNameByItemCode($item_code) . " [" . $item_code . "]"; ?></td>
                                                                                <td><?php echo date('d M Y', strtotime($On_date)); ?></td>
                                                                                <td><?php echo $opening; ?></td>
                                                                                <td><?php echo $inward; ?> | <?php echo $qty; ?></td>
                                                                                <td><?php echo $outward; ?></td>
                                                                                <td><?php echo $stock; ?></td>
                                                                                <td><?php echo $po_basic; ?></td>
                                                                                <td><?php echo $po_gst; ?></td>
                                                                                <td><?php echo $remarks; ?></td>
                                                                            </tr>
                                                                            <?php
                                                                            $opening = $stock;
                                                                            $opening = amount($opening);
                                                                            $i++;
                                                                        }
                                                                    }

//Outward
                                                                    $outwrded_details = getOutwardedOrderDetailOnDate($retailer_id, $item_code, $On_date);
                                                                    foreach ($outwrded_details as $outwrded_detail) {
                                                                        if (isset($outwrded_detail->inward_qty)) {
                                                                            $btn_name = 1;
                                                                            $outward = $outwrded_detail->inward_qty;
                                                                            $po_basic = $outwrded_detail->po_basic;
                                                                            $po_gst = $outwrded_detail->po_gst;
                                                                            $stock = $opening - $outward;
                                                                            $inward = 0;
                                                                            $qty = 0;
                                                                            $remarks = "Transfer refNo : " . $outwrded_detail->po_no . " To: " . getRetailerNameById($outwrded_detail->retailer_id);
                                                                            $qty = getItemSrNoCountDateByPONo($item_code, $outwrded_detail->retailer_id, $outwrded_detail->po_no, $On_date);
                                                                            ?>
                                                                            <tr>
                                                                                <td><?php echo $i; ?></td>
                                                                                <td><?php echo getRetailerNameById($retailer_id) . " [" . $retailer_id . "]"; ?></td> 
                                                                                <td><?php echo getItemNameByItemCode($item_code) . " [" . $item_code . "]"; ?></td>
                                                                                <td><?php echo date('d M Y', strtotime($On_date)); ?></td>
                                                                                <td><?php echo $opening; ?></td>
                                                                                <td><?php echo $inward; ?></td>
                                                                                <td><?php echo $outward; ?> | <?php echo $qty; ?></td>
                                                                                <td><?php echo $stock; ?></td>
                                                                                <td><?php echo $po_basic; ?></td>
                                                                                <td><?php echo $po_gst; ?></td>
                                                                                <td><?php echo $remarks; ?></td>
                                                                            </tr>
                                                                            <?php
                                                                            $opening = $stock;
                                                                            $opening = amount($opening);
                                                                            $i++;
                                                                        }
                                                                    }
//Sales
                                                                    $sales_details = getRetailerSalesDetailsonDate($retailer_id, $item_code, $On_date);
                                                                    foreach ($sales_details as $sales_detail) {
                                                                        $btn_name = 1;
                                                                        $outward = $sales_detail->qty;
                                                                        $po_basic = 0;
                                                                        $po_gst = 0;
                                                                        $stock = $opening - $outward;
                                                                        $inward = 0;
                                                                        $remarks = "Sales OrderNo : " . $sales_detail->po_no;
                                                                        $qty = getItemSrNoCountByOrderNosUM($item_code, $retailer_id, $sales_detail->po_no);
                                                                        ?>
                                                                        <tr>
                                                                            <td><?php echo $i; ?></td>
                                                                            <td><?php echo getRetailerNameById($retailer_id) . " [" . $retailer_id . "]"; ?></td> 
                                                                            <td><?php echo getItemNameByItemCode($item_code) . " [" . $item_code . "]"; ?></td>
                                                                            <td><?php echo date('d M Y', strtotime($On_date)); ?></td>
                                                                            <td><?php echo $opening; ?></td>
                                                                            <td><?php echo $inward; ?></td>
                                                                            <td><?php echo $outward; ?> | <?php echo $qty; ?></td>
                                                                            <td><?php echo $stock; ?></td>
                                                                            <td><?php echo $po_basic; ?></td>
                                                                            <td><?php echo $po_gst; ?></td>
                                                                            <td><?php echo $remarks; ?></td>
                                                                        </tr>
                                                                        <?php
                                                                        $opening = $stock;
                                                                        $opening = amount($opening);
                                                                        $i++;
                                                                    }
//Return PO
                                                                    $return_po_detail = getRetailerTransferPurchareonDateMailBetween($retailer_id, $item_code, $On_date, $On_date);
                                                                    if (isset($return_po_detail->po_no)) {
                                                                        $btn_name = 1;
                                                                        $outward = $return_po_detail->qty;
                                                                        $po_basic = $return_po_detail->po_rate;
                                                                        $po_gst = $return_po_detail->po_gst_rate;
                                                                        $stock = $opening - $outward;
                                                                        $inward = 0;
                                                                        $remarks = "Return PO : " . $return_po_detail->po_no;
                                                                        $qty = getItemSrNoCountByOrderNo($item_code, $retailer_id, $return_po_detail->po_no);
                                                                        ?>
                                                                        <tr>
                                                                            <td><?php echo $i; ?></td>
                                                                            <td><?php echo getRetailerNameById($retailer_id) . " [" . $retailer_id . "]"; ?></td> 
                                                                            <td><?php echo getItemNameByItemCode($item_code) . " [" . $item_code . "]"; ?></td>
                                                                            <td><?php echo date('d M Y', strtotime($On_date)); ?></td>
                                                                            <td><?php echo $opening; ?></td>
                                                                            <td><?php echo $inward; ?></td>
                                                                            <td><?php echo $outward; ?> | <?php echo $qty; ?></td>
                                                                            <td><?php echo $stock; ?></td>
                                                                            <td><?php echo $po_basic; ?></td>
                                                                            <td><?php echo $po_gst; ?></td>
                                                                            <td><?php echo $remarks; ?></td>
                                                                        </tr>
                                                                        <?php
                                                                        $opening = $stock;
                                                                        $opening = amount($opening);
                                                                        $i++;
                                                                    }
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                                <?php if ($btn_name == 1 && ($_SESSION['username'] == "UAAG_ADMIN" || $_SESSION['username'] == "dhiraj" || $_SESSION['username'] == "admin")) { ?>
                                                    <a href="stock_managment.php?menu=1&item_code=<?php echo $item_code; ?>&retailer_id=<?php echo $retailer_id; ?>" target="_blank" ><button class="btn btn-danger">Update Item Stock</button></a>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- /.row -->
                            </div>
                        </div>
                    </div><!-- /.page-content -->
                </div>
            </div><!-- /.main-content -->

            <!--END MAIN WRAPPER -->
            <?php require_once 'includes/footer.php'; ?>    
        </div>
    </body>
</html>

