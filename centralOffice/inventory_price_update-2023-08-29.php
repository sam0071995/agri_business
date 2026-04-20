<?php
require_once 'includes/session.php';
require_once 'includes/common_function.php';
$status = 1;
$igst_rate = 0;
$sgst_rate = 0;
$cgst_rate = 0;
$basic_price = 0;
$get_item_id = 0;
$get_retailer_id = 0;







if (isset($_POST['submit'])) {
    $table_name = "retailer_inventory_master";
    $Retailer_id_array = $_POST['Retailer_id'];
    $inventory_item_id = trim($_POST['inventory_item']);
    $item_total_price = $_POST['item_basic_price'];
    $inventory_item_data = getproductDetailsById($inventory_item_id);
    if (isset($inventory_item_data->id)) {
        $data['item_id'] = $inventory_item_id;
        $data['sr_no'] = $inventory_item_data->sr_no;
        $data['main_category_id'] = $inventory_item_data->main_category_id;
        $data['sub_category_id'] = $inventory_item_data->sub_category_id;
        $data['item_code'] = $inventory_item_data->item_code;
        $data['item_desc'] = $inventory_item_data->item_desc;
        $data['hsn_code'] = $inventory_item_data->hsn_code;
        $data['item_desc'] = $inventory_item_data->item_desc;
        $data['status'] = $inventory_item_data->status;
        $data['uom'] = $inventory_item_data->uom;
        $data['inc_no'] = $inventory_item_data->inc_no;
        $data['description'] = $inventory_item_data->description;
        $count_for_bsic = 100 / (100 + $inventory_item_data->igst_rate);
        $item_basic_price = $item_total_price * $count_for_bsic;
        $cgst_value = ($item_basic_price * $inventory_item_data->cgst_rate) / 100;
        $sgst_value = ($item_basic_price * $inventory_item_data->sgst_rate) / 100;
        $igst_value = ($item_basic_price * $inventory_item_data->igst_rate) / 100;

        $item_total = $igst_value + $item_basic_price;
        $data['basic_price'] = $item_basic_price;
        $data['cgst_value'] = $cgst_value;
        $data['cgst_rate'] = $inventory_item_data->cgst_rate;
        $data['sgst_value'] = $sgst_value;
        $data['sgst_rate'] = $inventory_item_data->sgst_rate;
        $data['igst_value'] = $igst_value;
        $data['igst_rate'] = $inventory_item_data->igst_rate;
        $data['total'] = $item_total;

        if ($Retailer_id_array == "All") {
            $retailer_masters = getActiveRetailerDetails($company_id_in);
            foreach ($retailer_masters as $retailer_master) {
                $data['retailer_id'] = $retailer_master->id;
                $data['company_id'] = $retailer_master->company_id;
                $retailer_item_data = getRetailerItemById($inventory_item_id, $retailer_master->id);
                $old_basic_price = getRetailerItemBasicPriceById($inventory_item_id, $retailer_master->id);
                $old_total_price = getRetailerItemTotalPriceById($inventory_item_id, $Retailer_id);
                if (isset($retailer_item_data->id)) {
                    $data['updated_date'] = date('Y-m-d h:i:s');
                    $where = "item_id='$inventory_item_id' AND retailer_id='$retailer_master->id'";
                    $product = update($table_name, $data, $where);
                } else {
                    $data['date'] = date('Y-m-d');
                    $data['datetime'] = date('Y-m-d h:i:s');
                    $product = insert($table_name, $data);
                }
                $data_history_for_inventory_master = array();
                $data_history_for_inventory_master['item_id'] = $inventory_item_id;
                $data_history_for_inventory_master['retailer_id'] = $retailer_master->id;
                $data_history_for_inventory_master['old_price'] = $old_basic_price;
                $data_history_for_inventory_master['old_total'] = $old_total_price;
                $data_history_for_inventory_master['new_price'] = $item_basic_price;
                $data_history_for_inventory_master['new_total'] = $item_total;
                $data_history_for_inventory_master['remarks'] = "PriceUpdate";
                $data_history_for_inventory_master['user_name'] = $username;
                $data_history_for_inventory_master['user_id'] = $user_id;
                $data_history_for_inventory_master['date'] = date("Y-m-d H:i:s");
                $history_for_inventory_master = insert('history_for_inventory_master', $data_history_for_inventory_master);
            }
        } else {
            foreach ($Retailer_id_array as $Retailer_id) {
                $data['retailer_id'] = $Retailer_id;
                $data['company_id'] = getRetailerCompanyIdById($Retailer_id);
                $retailer_item_data = getRetailerItemById($inventory_item_id, $Retailer_id);
                $old_basic_price = getRetailerItemBasicPriceById($inventory_item_id, $Retailer_id);
                $old_total_price = getRetailerItemTotalPriceById($inventory_item_id, $Retailer_id);
                if (isset($retailer_item_data->id)) {
                    $data['updated_date'] = date('Y-m-d h:i:s');
                    $where = "item_id='$inventory_item_id' AND retailer_id='$Retailer_id'";
                    $product = update($table_name, $data, $where);
                } else {
                    $data['date'] = date('Y-m-d');
                    $data['datetime'] = date('Y-m-d h:i:s');
                    $product = insert($table_name, $data);
                }
                $data_history_for_inventory_master = array();
                $data_history_for_inventory_master['item_id'] = $inventory_item_id;
                $data_history_for_inventory_master['retailer_id'] = $Retailer_id;
                $data_history_for_inventory_master['company_id'] = getRetailerCompanyIdById($Retailer_id);
                $data_history_for_inventory_master['old_price'] = $old_basic_price;
                $data_history_for_inventory_master['old_total'] = $old_total_price;
                $data_history_for_inventory_master['new_price'] = $item_basic_price;
                $data_history_for_inventory_master['new_total'] = $item_total;
                $data_history_for_inventory_master['remarks'] = "PriceUpdate";
                $data_history_for_inventory_master['user_name'] = $username;
                $data_history_for_inventory_master['user_id'] = $user_id;
                $data_history_for_inventory_master['date'] = date("Y-m-d H:i:s");
                $history_for_inventory_master = insert('history_for_inventory_master', $data_history_for_inventory_master);
            }
        }

        if ($product) {
            header("Location:inventory_price_update.php" . $menuURL . "&success=1&item=" . base64_encode($inventory_item_id) . "&retailer=" . base64_encode($Retailer_id));
            exit;
        } else {
            header("Location:inventory_price_update.php" . $menuURL . "&error=1");
            exit;
        }
    } else {
        header("Location:inventory_price_update.php" . $menuURL . "&error=2");
        exit;
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
                                        <?php echo "product Updated Successfully"; ?>
                                    </div>
                                <?php } ?>
                                <h3 class="page-header">Inventory Item | Price Update.</h3>
                                <form class="form-horizontal" id="searchform" name="myForm"  role="form" action="" method="POST" enctype="multipart/form-data">
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Select Distributer<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <select class="form-field-select-2 form-control" size="10" multiple name="Retailer_id[]" id="Retailer_id" required="required">
                                                <!--<option value="">--Select Distributer--</option>-->
                                                <!--<option value="All">All Distributer</option>-->
                                                <?php foreach (getActiveRetailerDetails($company_id_in) as $active_sellers) { ?>
                                                    <option value="<?php echo $active_sellers->id; ?>" <?php
                                                    if ($active_sellers->id == $get_retailer_id) {
                                                        echo "selected='selected'";
                                                    }
                                                    ?>><?php echo $active_sellers->name . " | " . getCompanyNameById($active_sellers->company_id); ?></option>
                                                        <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Select Item<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <select class="form-field-select-2 form-control chosen-select" name="inventory_item" id="inventory_item" required="required">
                                                <option value="">--Select Item--</option>
                                                <!--<option value="ALL">All Item</option>-->
                                                <?php foreach (getActiveItemsList() as $inventiry_item) { ?>
                                                    <option value="<?php echo $inventiry_item->id; ?>" <?php
                                                    if ($inventiry_item->id == $get_item_id) {
                                                        echo "selected='selected'";
                                                    }
                                                    ?>><?php echo $inventiry_item->item_desc; ?></option>
                                                        <?php } ?>
                                            </select>
                                        </div>
                                    </div>


                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> GST Rate<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6" id="item_gst_rate">
                                            <p>
                                                <b class="red">IGST Rate : <?php echo $igst_rate; ?></b> | 
                                                <b class="green">SGST Rate : <?php echo $sgst_rate; ?></b> | 
                                                <b class="blue">CGST Rate : <?php echo $cgst_rate; ?></b>
                                            </p> 
                                        </div>
                                    </div>
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Item <b class="red">Total</b> Price <b>[Sale Price]</b><span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <input type="text" name="item_basic_price" id="item_basic_price" placeholder="Enter Item Basic Price" class="form-control" required="required" value="<?php echo $basic_price; ?>"/>
                                        </div>
                                    </div>
                                    <div class="clearfix form-actions">
                                        <div class="col-md-offset-3 col-md-9">
                                            <button type="submit" name="submit" class="btn btn-info">
                                                <i class="ace-icon fa fa-check bigger-110"></i>
                                                <?php echo "UPDATE"; ?>
                                            </button>
                                        </div>
                                    </div>
                                </form>
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

