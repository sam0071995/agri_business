<?php
require_once 'includes/session.php';
require_once 'includes/common_function.php';

//$retailer_id = 1;
$status = 1;
$retailer_id = '';
$product_id = '';
$product_title = '';
$product_category = '';
$general_category = '';
$short_description = '';
$feature_description = '';
$remarks = '';
$item_code = '';
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
                                <h3 class="page-header">Retailer | Inventory Stock Report by Date</h3>

                                <form name="myForm"  role="form" action="" method="POST" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-sm-3">
                                                <select class="form-field-select-2 form-control" multiple name="Retailer_id[]" id="Retailer_id" required="required">
                                                    <?php foreach (getActiveRetailerDetails($company_id_in) as $active_sellers) { ?>
                                                        <option value="<?php echo $active_sellers->id; ?>" <?php
                                                        if ($active_sellers->id == $retailer_id) {
                                                            echo "selected='selected'";
                                                        }
                                                        ?>><?php echo $active_sellers->name; ?></option>
                                                            <?php } ?>
                                                </select>
                                            </div>
                                            <div class="col-sm-3">
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
                                                <select class="form-field-select-2 form-control chosen-select" name="item_code" required="required">
                                                    <option value="">--Select item--</option>
                                                    <option value="ALL">All Items</option>
                                                    <?php foreach (getActiveItemsList() as $active_item) { ?>
                                                        <option value="<?php echo $active_item->item_code; ?>" <?php
                                                        if ($active_item->item_code == $item_code) {
                                                            echo "selected='selected'";
                                                        }
                                                        ?>><?php echo $active_item->item_desc; ?></option>
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
                        </div>
                        <hr/>
                        <div class="row">
                            <?php
                            if (isset($_POST['show'])) {
                                $retailer_id_array = $_POST['Retailer_id'];
                                $item_code = $_POST['item_code'];
                                ?>
                                <div class="col-xs-12">
                                    <h3 class="page-header">Inventory Stock Report For Date : <?php
                                        if (isset($_POST['date_1'])) {
                                            echo " " . date("d M Y", strtotime($_POST['date_1']));
                                        }
                                        ?></h3>
                                    <div class="modal-body">
                                        <div class="row clearfix">
                                            <div class="pull-right tableTools-container"></div>
                                        </div>
                                        <div>
                                            <?php
                                            if (isset($_POST['show'])) {
                                                $date_1 = date("Y-m-d", strtotime($_POST['date_1']));
                                                $date_2 = $date_1;
                                                $previous_date = date('Y-m-d', strtotime('-1 day', strtotime($date_1)));
                                                ?>
                                                <table id="dynamic-table" class="table table-bordered table-hover">
                                                    <thead class="thead-dark">
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Distributer Name</th>
                                                            <th>Item Name</th>
                                                            <th>HSN Code</th>
                                                            <th>UOM</th>
                                                            <th>MainCat</th>   
                                                            <th>SubCat</th>   
                                                            <th>Opening</th>   
                                                            <th>Recieved</th>   
                                                            <th>Issued</th>   
                                                            <th>Clossing</th>   
                                                            <th>Sales GST</th>   
                                                            <th>Sales BasicRate</th>   
                                                            <th>SR Count</th>   
                                                            <th>Purchase GST</th>   
                                                            <th>Purchase Basic</th>   
                                                            <th>Status</th>   
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        foreach ($retailer_id_array as $retailer_id) {
                                                            $index = 1;
                                                            $products = getRetailerItemByOnlyRetailerIdONDAte($retailer_id, $item_code);
                                                            foreach ($products as $product) {
                                                                $InwardCountBackend = 0;
                                                                $Inwardpurchae_basicBackend = 0;
                                                                $Inwardpurchase_gstBackend = 0;
                                                                $Inwardpurchase_totalBackend = 0;
                                                                $status = "";
                                                                if ($product->status == 1) {
                                                                    $status .= '<span class="badge badge-success">Active</span>';
                                                                } else {
                                                                    $status .= '<span class="badge badge-danger">In-Active</span>';
                                                                }
                                                                $item_code = $product->item_code;
                                                                $InwardCountBackendData = getBackendRetailerStockTInward($retailer_id, $item_code, $previous_date);
                                                                $InwardCountBackend = $InwardCountBackendData->qty;
                                                                $Inwardpurchae_basicBackend = $InwardCountBackendData->purchae_basic;
                                                                $Inwardpurchase_gstBackend = $InwardCountBackendData->purchase_gst;
                                                                $Inwardpurchase_totalBackend = $InwardCountBackendData->purchase_total;
                                                                if (empty($InwardCountBackend)) {
                                                                    $InwardCountBackend = 0;
                                                                    $Inwardpurchae_basicBackend = 0;
                                                                    $Inwardpurchase_gstBackend = 0;
                                                                    $Inwardpurchase_totalBackend = 0;
                                                                }
                                                                $InwardCount = getRetailerStockTInward($retailer_id, $item_code, $previous_date);
                                                                if (empty($InwardCount)) {
                                                                    $InwardCount = 0;
                                                                }
                                                                $InwardCount = $InwardCount + $InwardCountBackend;
                                                                $salesCount = getRetailerSalesDetail($retailer_id, $item_code, $previous_date);
                                                                if (empty($salesCount)) {
                                                                    $salesCount = 0;
                                                                }
                                                                $transferCount = getRetailerStockTransfer($retailer_id, $item_code, $previous_date);
                                                                if (empty($transferCount)) {
                                                                    $transferCount = 0;
                                                                }
                                                                $transferPurchaseCount = getRetailerTransferPurchare($retailer_id, $item_code, $previous_date);
                                                                if (empty($transferPurchaseCount)) {
                                                                    $transferPurchaseCount = 0;
                                                                }
                                                                $opening_stock = $product->opening_stock + $InwardCount - $salesCount - $transferCount - $transferPurchaseCount;
//                                                            echo $InwardCount;
//                                                            echo '<br/>';
//                                                            echo $salesCount;
//                                                            echo '<br/>';
//                                                            echo $transferPurchaseCount;
//                                                            echo '<br/>';
//                                                            echo $opening_stock;
//                                                            exit;
                                                                $inwardOnDate = getRetailerStockTInwardForDate($retailer_id, $item_code, $date_1);
                                                                $receive_stock = $inwardOnDate;
                                                                if (empty($receive_stock)) {
                                                                    $receive_stock = 0;
                                                                }

                                                                $salesCountonDate = getRetailerSalesDetailonDate($retailer_id, $item_code, $date_1);
                                                                $transferCountonDate = getRetailerStockTransferonDate($retailer_id, $item_code, $date_1);
                                                                $transferPurchaseCountonDate = getRetailerTransferPurchareonDate($retailer_id, $item_code, $date_1);
                                                                $issued_stock = $salesCountonDate + $transferCountonDate + $transferPurchaseCountonDate;
                                                                if (empty($issued_stock)) {
                                                                    $issued_stock = 0;
                                                                }
                                                                $current_stock = $opening_stock + $receive_stock - $issued_stock;
                                                                if (empty($current_stock)) {
                                                                    $current_stock = 0;
                                                                }
                                                                $closing_batch_gst = 0;
                                                                $closing_batch_purchase_basic = 0;

                                                                $itemFreeSrNoClosing = getFreeItemsSrByitemBatchDetailsBeforeDate($retailer_id, $item_code, $date_1);
                                                                $closing_batch_count = $itemFreeSrNoClosing->count;
                                                                if ($closing_batch_count == 0) {
                                                                    $closing_batch_gst = 0;
                                                                    $closing_batch_purchase_basic = 0;
                                                                } else {
                                                                    $closing_batch_countId = $itemFreeSrNoClosing->countId;
                                                                    $closing_batch_gst = $itemFreeSrNoClosing->gst / $closing_batch_countId;
                                                                    if ($closing_batch_count < 1) {
                                                                        $closing_batch_purchase_basic = $itemFreeSrNoClosing->purchase_basic * $closing_batch_count;
                                                                    } else {
                                                                        $closing_batch_purchase_basic = $itemFreeSrNoClosing->purchase_basic;
                                                                    }
                                                                }

                                                                $next_date = date('Y-m-d', strtotime('+1 day', strtotime($date_2)));
                                                                $itemFreeSrNoClosing_all = getFreeItemsSrByitemBatchDetailsBetweenDate($retailer_id, $item_code, $date_2, $next_date);
                                                                $closing_batch_count_all = $itemFreeSrNoClosing_all->count;
                                                                if ($closing_batch_count_all == 0) {
                                                                    $closing_batch_gst_all = 0;
                                                                    $closing_batch_purchase_basic_all = 0;
                                                                } else {
                                                                    $closing_batch_count_all_Id = $itemFreeSrNoClosing_all->countId;
                                                                    $closing_batch_gst_all = $itemFreeSrNoClosing_all->gst / $closing_batch_count_all_Id;
                                                                    if ($closing_batch_count_all < 1) {
                                                                        $closing_batch_purchase_basic_all = $itemFreeSrNoClosing_all->purchase_basic * $closing_batch_count_all;
                                                                    } else {
                                                                        $closing_batch_purchase_basic_all = $itemFreeSrNoClosing_all->purchase_basic;
                                                                    }
                                                                }

                                                                $next_date = date('Y-m-d', strtotime('+1 day', strtotime($date_2)));
                                                                $itemFreeSrNoClosing_reject = getFreeItemsSrByitemBatchDetailsBetweenDateRejected($retailer_id, $item_code, $date_2, $next_date);
                                                                $closing_batch_count_reject = $itemFreeSrNoClosing_reject->count;
                                                                if ($closing_batch_count_reject == 0) {
                                                                    $closing_batch_gst_reject = 0;
                                                                    $closing_batch_purchase_basic_reject = 0;
                                                                } else {
                                                                    $closing_batch_count_reject_Id = $itemFreeSrNoClosing_reject->countId;
                                                                    $closing_batch_gst_reject = $itemFreeSrNoClosing_reject->gst / $closing_batch_count_reject_Id;
                                                                    if ($closing_batch_count_reject < 1) {
                                                                        $closing_batch_purchase_basic_reject = $itemFreeSrNoClosing_reject->purchase_basic * $closing_batch_count_reject;
                                                                    } else {
                                                                        $closing_batch_purchase_basic_reject = $itemFreeSrNoClosing_reject->purchase_basic;
                                                                    }
                                                                }

                                                                $closing_batch_count = $closing_batch_count + $closing_batch_count_all + $closing_batch_count_reject;
                                                                $closing_batch_gst = $closing_batch_gst + $closing_batch_gst_all + $closing_batch_gst_reject;
                                                                $closing_batch_purchase_basic = $closing_batch_purchase_basic + $closing_batch_purchase_basic_all + $closing_batch_purchase_basic_reject;
                                                                ?>
                                                                <tr>
                                                                    <td><?php echo $index; ?></td>
                                                                    <td><?php echo getRetailerNameById($product->retailer_id); ?></td>
                                                                    <td><?php echo $product->item_desc; ?></td>
                                                                    <td><?php echo $product->hsn_code; ?></td>
                                                                    <td><?php echo $product->uom; ?></td>
                                                                    <td>
                                                                        <b class="green"><?php echo getCategoryNameById($product->main_category_id); ?></b>
                                                                    </td>
                                                                    <td>
                                                                        <b class="blue"><?php echo getCategoryNameById($product->sub_category_id); ?></b>
                                                                    </td>
                                                                    <td>
                                                                        <b class="green"><?php echo numberDecimal($opening_stock); ?></b><br/>
                                                                    </td>
                                                                    <td>
                                                                        <b class="cyan"><?php echo numberDecimal($receive_stock); ?></b><br/>
                                                                    </td>
                                                                    <td>
                                                                        <b class="blue"><?php echo numberDecimal($issued_stock); ?></b><br/>
                                                                    </td>
                                                                    <td>
                                                                        <b class="red"><?php echo numberDecimal($current_stock); ?></b><br/>
                                                                    </td>
                                                                    <td>
                                                                        <b class="green"><?php echo numberDecimal($product->igst_rate); ?></b>
                                                                    </td>
                                                                    <td>
                                                                        <b class="green"><?php echo numberDecimal($product->basic_price); ?></b>
                                                                    </td>
                                                                    <td>
                                                                        <b class="blue"><?php echo numberDecimal($closing_batch_count); ?></b>
                                                                    </td>
                                                                    <td>
                                                                        <b class="green"><?php echo numberDecimal($closing_batch_gst); ?></b>
                                                                    </td>
                                                                    <td>
                                                                        <b class="blue"><?php echo numberDecimal($closing_batch_purchase_basic); ?></b>
                                                                    </td>
                                                                    <td><?php echo $status; ?></td>
                                                                </tr>
                                                                <?php
                                                                $index++;
                                                            }
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                                </table>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div><!-- /.row -->
                    </div><!-- /.page-content -->
                </div>
            </div><!-- /.main-content -->

            <script type="text/javascript">
                $('#Retailer_id').multiselect({

                    columns: 1, // how many columns should be use to show options
                    search: true, // include option search box
                    texts: {
                        placeholder: '-- Select Distributer --', // text to use in dummy input
                    },
                    selectAll: true
                });
            </script>
            <!--END MAIN WRAPPER -->
            <?php require_once 'includes/footer.php'; ?>    
        </div>
    </body>
</html>

