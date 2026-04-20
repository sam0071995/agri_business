<?php
require_once 'includes/session.php';
require_once 'includes/common_function.php';
$retailer_id = '';
if (isset($_POST['Retailer_id'])) {
    $retailer_id = $_POST['Retailer_id'];
}
$item_code = '';
if (isset($_POST['item_code'])) {
    $item_code = $_POST['item_code'];
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
                                            <h4 class="widget-title">Retailer | Product wise Price Update History.</h4>
                                        </div>
                                        <div class="widget-body">
                                            <div class="widget-main">
                                                <form class="form-inline left" action="" method="POST">
                                                    <div class="row">
                                                        <div class="col-xs-2">
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
                                                        <div class="col-xs-2">
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
                                                        <div class="col-xs-3">
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
                                                        <div class="col-xs-3">
                                                            <b>Select Item :</b>
                                                            <div class="input-group">
                                                                <select class="form-control col-xs-3 form-field-select-2 chosen-select" name="item_code" id="item_code">
                                                                    <option value="">--select--</option>
                                                                    <?php foreach (getRetailerItemListbyComoanyId($company_id_in) as $active_item) { ?>
                                                                        <option value="<?php echo $active_item->item_id; ?>" <?php
                                                                        if ($item_code == $active_item->item_id) {
                                                                            echo 'selected="selected"';
                                                                        }
                                                                        ?>><?php echo $active_item->item_desc; ?></option>
                                                                            <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-2">
                                                            <b></b>
                                                            <div class="input-group">
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
                                <div class="modal-body">
                                    <div class="row clearfix">
                                        <div class="pull-right tableTools-container"></div>
                                    </div>
                                    <div>
                                        <table id="dynamic-table" class="table table-bordered table-hover">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Retailer Name</th>
                                                    <th>Item Name</th>
                                                    <th>Last PO Basic</th>   
                                                    <th>Last PO GST</th>   
                                                    <th>Old Basic</th>   
                                                    <th>Old Total</th>   
                                                    <th>New Basic</th>   
                                                    <th>New Total</th>   
                                                    <th>Update Date</th>
                                                    <th>Update By</th>
                                                    <th>Remarks</th>
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
                                                    if (isset($_POST['item_code'])) {
                                                        $item_code = $_POST['item_code'];
                                                    }

                                                    $index = 1;
                                                    $products = getProductPriceUpdateHistory($date_1, $date_2, $retailer_id, $item_code, $company_id_in);
                                                    foreach ($products as $product) {
                                                        $get_retailer_price_data = getRetialerPriceDataByIdAndItem($product->item_id, $product->retailer_id);
                                                        $last_po_basic = 0;
                                                        $last_po_gst = 0;
                                                        if (isset($get_retailer_price_data->last_po_basic)) {
                                                            $last_po_basic = $get_retailer_price_data->last_po_basic;
                                                            $last_po_gst = $get_retailer_price_data->last_po_gst;
                                                        }
                                                        ?> 
                                                        <tr>
                                                            <td><?php echo $index; ?></td>
                                                            <td><?php echo getRetailerNameById($product->retailer_id); ?></td>
                                                            <td><?php echo getItemNameByItemId($product->item_id); ?></td>
                                                            <td><?php echo $last_po_basic; ?></td>
                                                            <td><?php echo $last_po_gst; ?></td>
                                                            <td><?php echo $product->old_price; ?></td>
                                                            <td><?php echo $product->old_total; ?></td>
                                                            <td><?php echo $product->new_price; ?></td>
                                                            <td><?php echo $product->new_total; ?></td>
                                                            <td><?php echo date('Y M d H:i:s', strtotime($product->date)); ?></td>
                                                            <td><?php echo $product->user_name; ?></td>
                                                            <td><?php echo $product->remarks; ?></td>
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

