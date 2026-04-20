<?php
require_once 'includes/session.php';
require_once 'includes/common_function.php';
$retailer_id = '';
if (isset($_POST['Retailer_id'])) {
    $retailer_id = $_POST['Retailer_id'];
}
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
                                <div class="page-header">
                                    <div class="widget-box">
                                        <div class="widget-header">
                                            <h4 class="widget-title">Retailer | Stock Transfer History.</h4>
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
                                <div class="modal-body">
                                    <div class="row clearfix">
                                        <div class="pull-right tableTools-container"></div>
                                    </div>
                                    <div>
                                        <table id="dynamic-table" class="table table-bordered table-hover">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Ref Number</th>
                                                    <th>Date</th>
                                                    <th>From Retailer Name</th>
                                                    <th>To Retailer Name</th>
                                                    <th>Item Code</th>
                                                    <th>Item Name</th>
                                                    <th>Manufacturing Date</th>
                                                    <th>Batch No</th>
                                                    <th>Expiry Date</th>
                                                    <th>ChallanNo</th>
                                                    <th>Qty</th>
                                                    <th>Batch Qty</th>
                                                    <th>Purchase Price</th>
                                                    <th>Purchase GST</th>
                                                    <th>Request Date</th>
                                                    <th>Approve Date</th>
                                                    <th>Dispatch Date</th>
                                                    <th>Grn Date</th>
                                                    <th></th>
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

                                                    $index = 1;
                                                    $products = getStockTransferRequest($date_1, $date_2, $retailer_id, $company_id_in);
                                                    foreach ($products as $product) {
                                                        $grn_detail = getInwardedOrderSattus($product->retailer_id, $product->order_no, $product->item_code);
                                                        $status = "";
                                                        if (isset($grn_detail->retailer_inwd_flg) && $grn_detail->retailer_inwd_flg == 1) {
                                                            $status = "Grn Done";
                                                        } else if ($product->status == 1 && $product->ctrl_off_flag == 0) {
                                                            $status = "Requested & Approval Pending";
                                                        } else if ($product->status == 1 && $product->ctrl_off_flag == 1) {
                                                            $status = "Approval Done & Pending for Dispatch";
                                                        } else if ($product->status == 2 && $product->ctrl_off_flag == 1) {
                                                            $status = "Dispatched";
                                                        } else if ($product->status == 7 && $product->ctrl_off_flag == 7) {
                                                            $status = "Rejected By Admin";
                                                        } else if ($product->ctrl_off_flag == 7) {
                                                            $status = "Rejected By Store";
                                                        } else {
                                                            $status = "Status not found.";
                                                        }
                                                        if (isset($grn_detail->retailer_inwd_flg)) {
                                                            if ($grn_detail->retailer_inwd_flg == 1) {
                                                                $inwrad_done = $retailer_inwd_date = date('d M Y H:i:s', strtotime($grn_detail->retailer_inwd_date));
                                                            } elsE {
                                                                $retailer_inwd_date = "";
                                                            }
                                                        } else {
                                                            $retailer_inwd_date = "";
                                                        }
                                                        $purchase_rate = getToalTransRequestRate($product->retailer_id, $product->frm_retailer_id, $product->batch_no, $product->item_code, $product->order_no, $product->expire_date);
                                                        ?> 
                                                        <tr>
                                                            <td><?php echo $index; ?></td>
                                                            <td><?php echo $product->order_no; ?></td>
                                                            <td><?php echo date('d M Y', strtotime($product->add_date)); ?></td>
                                                            <td><?php echo getRetailerNameById($product->frm_retailer_id); ?></td>
                                                            <td><?php echo getRetailerNameById($product->retailer_id); ?></td>
                                                            <td><?php echo $product->item_code; ?></td>
                                                            <td><?php echo getItemNameByItemCode($product->item_code); ?></td>
                                                            <td><?php echo $product->manufacturing_date; ?></td>
                                                            <td><?php echo $product->batch_no; ?></td>
                                                            <td><?php echo $product->expire_date; ?></td>
                                                            <td><?php echo $product->challan_no; ?></td>
                                                            <td><?php echo $product->req_qty; ?></td>  
                                                            <td><?php echo getToalTransRequestBlockerBatchBlockedQty($product->retailer_id, $product->frm_retailer_id, $product->batch_no, $product->item_code, $product->order_no, $product->add_date); ?></td>
                                                            <td><?php
                                                                if (isset($purchase_rate->purchase_basic)) {
                                                                    echo $purchase_rate->purchase_basic;
                                                                } else {
                                                                    echo 0;
                                                                }
                                                                ?></td>
                                                            <td>
                                                                <?php
                                                                if (isset($purchase_rate->gst)) {
                                                                    echo $purchase_rate->gst;
                                                                } else {
                                                                    echo 0;
                                                                }
                                                                ?>
                                                            </td> 
                                                            <td><?php
                                                                if (isset($product->add_datetime)) {
                                                                    echo date('d M Y H:i:s', strtotime($product->add_datetime));
                                                                }
                                                                ?>
                                                            </td>
                                                            <td><?php
                                                                if ($product->ctrl_off_flag == 1 || $product->ctrl_off_flag == 7) {
                                                                    echo date('d M Y H:i:s', strtotime($product->approve_datetime));
                                                                }
                                                                ?></td>
                                                            <td><?php
                                                                if ($product->status == 2 && $product->ctrl_off_flag == 1) {
                                                                    echo date('d M Y H:i:s', strtotime($product->dispatch_date));
                                                                }
                                                                ?>
                                                            </td>
                                                            <td><?php
                                                                if (isset($grn_detail->retailer_inwd_flg)) {
                                                                    echo $retailer_inwd_date;
                                                                }
                                                                ?>
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
                                    </div>
                                </div>
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

