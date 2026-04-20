<?php
require_once 'includes/session.php';
require_once 'includes/common_function.php';
$status = 1;
$get_product_id = '';
$inventory_item_name = '';
$main_category_id = '';
$general_category = '';
$sub_category_id = '';
$description = '';
$hsn_code = '';
$igst_rate = 18;
$sgst_rate = 9;
$cgst_rate = 9;
$product_uom = '';
$product_brand = '';
$btn_name = "Submit";
$g_category_array = '';
$category_array = '';
$read_only = '';
if (isset($_GET['product_id'])) {
    $read_only = 'readonly="readonly"';
    $get_product_id = base64_decode($_GET['product_id']);
    $productData = getproductDetailsById($get_product_id);
    $inventory_item_name = $productData->item_desc;
    $main_category_id = $productData->main_category_id;
    $sub_category_id = $productData->sub_category_id;
    $description = $productData->description;
    $hsn_code = $productData->hsn_code;
    $igst_rate = $productData->igst_rate;
    $sgst_rate = $productData->sgst_rate;
    $cgst_rate = $productData->cgst_rate;
    $status = $productData->status;
    $product_uom = $productData->uom;
    if (!empty($productData->brand_name)) {
        $product_brand = $productData->brand_name;
    }
    $btn_name = "Update";
}

if (isset($_POST['submit'])) {
    $table_name = "inventory_master";
    if (isset($_POST['item_id'])) {
        $product_id = base64_decode($_POST['item_id']);
    }
    $max_inc_no = getMaxItemIncNo();
    $max_inc_no = $max_inc_no + 1;
    $data['item_desc'] = "LOOSE " . $_POST['item_name'];
    $data['main_category_id'] = $_POST['main_category'];
    $data['sub_category_id'] = $_POST['sub_category'];
    $data['description'] = $_POST['description'];
    $data['hsn_code'] = $_POST['hsn_code'];
    $data['igst_rate'] = $_POST['igst_rate'];
    $data['sgst_rate'] = $_POST['igst_rate'] / 2;
    $data['cgst_rate'] = $_POST['igst_rate'] / 2;
    $data['status'] = $_POST['status'];
    $data['uom'] = $_POST['item_uom'];
    $data['active'] = $_POST['status'];
    $data['brand_name'] = $_POST['product_brand'];


    $productData = getproductDetailsById($product_id);
    $data['basic_price'] = round(($productData->basic_price / 1000), 3);
    $data['cgst_value'] = round(($productData->cgst_value / 1000), 3);
    $data['sgst_value'] = round(($productData->sgst_value / 1000), 3);
    $data['igst_value'] = round(($productData->igst_value / 1000), 3);
    $data['total'] = round(($productData->total / 1000), 3);
    $data['cgst_rate'] = $productData->cgst_rate;
    $data['sgst_rate'] = $productData->sgst_rate;
    $data['igst_rate'] = $productData->igst_rate;




//    if (!empty($product_id)) {
//        $data['updated_date'] = date('Y-m-d h:i:s');
//        $where = "id='$product_id'";
//        $product = update($table_name, $data, $where);
//
//        $item_basic_price = $productData->basic_price;
//        $cgst_value = ($item_basic_price * ($_POST['igst_rate'] / 2)) / 100;
//        $sgst_value = ($item_basic_price * ($_POST['igst_rate'] / 2)) / 100;
//        $igst_value = ($item_basic_price * $_POST['igst_rate']) / 100;
//        $item_total = $igst_value + $item_basic_price;
//
//
//        $data_history_for_inventory_master['item_id'] = $product_id;
//        $data_history_for_inventory_master['remarks'] = "Update";
//
//        $data_history_for_inventory_master['old_price'] = $productData->basic_price;
//        $data_history_for_inventory_master['old_total'] = $productData->total;
//        $data_history_for_inventory_master['new_price'] = $item_basic_price;
//        $data_history_for_inventory_master['new_total'] = $item_total;
//        $data_history_for_inventory_master['old_igst'] = $productData->igst_value;
//        $data_history_for_inventory_master['new_igst'] = $igst_value;
//
//
//        $dataRetailerMaster = array();
//        $dataRetailerMaster['item_desc'] = $_POST['item_name'];
//        $dataRetailerMaster['updated_date'] = date('Y-m-d h:i:s');
//        $dataRetailerMaster['hsn_code'] = $_POST['hsn_code'];
//        $dataRetailerMaster['main_category_id'] = $_POST['main_category'];
//        $dataRetailerMaster['sub_category_id'] = $_POST['sub_category'];
//        $dataRetailerMaster['status'] = $_POST['status'];
//        $dataRetailerMaster['uom'] = $_POST['item_uom'];
//        $dataRetailerMaster['active'] = $_POST['status'];
//        $whereRetailerMaster = "item_id='$product_id'";
//        update("retailer_inventory_master", $dataRetailerMaster, $whereRetailerMaster);
//    } else {
    $item_code = "AGRO" . $max_inc_no . time();
    $data['inc_no'] = $max_inc_no;
    $data['item_code'] = $item_code;
    $data['sr_no'] = $max_inc_no;
    $data['date'] = date('Y-m-d');
    $data['datetime'] = date('Y-m-d h:i:s');
//        print_r($data);
//        exit();
    $product = insert($table_name, $data);
    $last_product_id = getLastIdByTablName($table_name);
    $data_history_for_inventory_master['item_id'] = $last_product_id;
    $data_history_for_inventory_master['remarks'] = "Add";


//    }

    $data_history_for_inventory_master['user_name'] = $username;
    $data_history_for_inventory_master['user_id'] = $user_id;
    $data_history_for_inventory_master['date'] = date("Y-m-d H:i:s");
    $history_for_inventory_master = insert('history_for_inventory_master', $data_history_for_inventory_master);
    if ($product) {
        header("Location:inventory_master.php" . $menuURL . "&success=1");
        exit;
    } else {
        header("Location:inventory_master.php" . $menuURL . "&error=1");
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
                                <h3 class="page-header">Copy Inventory Loose Item .</h3>
                                <form class="form-horizontal" id="searchform" name="myForm"  role="form" action="" method="POST" enctype="multipart/form-data">

                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Inventory Item Name<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <input type="hidden" name="item_id" value="<?php echo base64_encode($get_product_id); ?>"/>
                                            <input type="text" name="item_name" placeholder="Enter Inventory Item Name" class="form-control item_name" required="required" value="<?php echo $inventory_item_name; ?>"/>
                                        </div>
                                    </div>

                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Description<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <textarea name="description" placeholder="Enter Item Description" class="form-control" required="required" id="editor2"><?php echo $description; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Item Parent Category<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <select class="form-field-select-2 form-control" name="main_category" required="required">
                                                <option value="">--Select Item Parent Category--</option>
                                                <?php foreach (getParentActiveCategories() as $pCategory) { ?>
                                                    <option value="<?php echo $pCategory->id; ?>" <?php
                                                    if ($pCategory->id == $main_category_id) {
                                                        echo "selected='selected'";
                                                    }
                                                    ?>><?php echo $pCategory->name; ?></option>
                                                        <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Item Sub Category<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <select class="form-field-select-2 form-control" name="sub_category" required="required">
                                                <option value="">--Select Item Sub Category--</option>
                                                <?php foreach (getSubActiveCategories() as $pCategory) { ?>
                                                    <option value="<?php echo $pCategory->id; ?>" <?php
                                                    if ($pCategory->id == $sub_category_id) {
                                                        echo "selected='selected'";
                                                    }
                                                    ?>><?php echo $pCategory->name; ?></option>
                                                        <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> HSN Code<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <input type="text" name="hsn_code" placeholder="Enter HSN Code" class="form-control" required="required" value="<?php echo $hsn_code; ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Product Brand<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <input type="text" name="product_brand" placeholder="Enter Brand Name" class="form-control" required="required" value="<?php echo $product_brand; ?>"/>
                                        </div>
                                    </div>
                                    <?php if (!isset($_GET['product_id'])) { ?>
                                        <div class="form-group" id="c_n_password_c">
                                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> GST Rate<span style="color:red">*</span> : </label>
                                            <div class="col-sm-6">
                                                <select name="igst_rate" <?php echo $read_only; ?>>
                                                    <option <?php
                                                    if ($igst_rate == 0) {
                                                        echo 'selected="selectde"';
                                                    }
                                                    ?> value="0">0</option>
                                                    <option <?php
                                                    if ($igst_rate == 5) {
                                                        echo 'selected="selectde"';
                                                    }
                                                    ?> value="5">5</option>
                                                    <option <?php
                                                    if ($igst_rate == 12) {
                                                        echo 'selected="selectde"';
                                                    }
                                                    ?> value="12">12</option>
                                                    <option <?php
                                                    if ($igst_rate == 18) {
                                                        echo 'selected="selectde"';
                                                    }
                                                    ?> value="18">18</option>
                                                    <option <?php
                                                    if ($igst_rate == 28) {
                                                        echo 'selected="selectde"';
                                                    }
                                                    ?> value="28">28</option>
                                                </select>
                                            </div>
                                        </div>
                                    <?php } else { ?>

                                        <div class="form-group" id="c_n_password_c">
                                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> GST Rate<span style="color:red">*</span> : </label>
                                            <div class="col-sm-6">
                                                <?php echo $igst_rate; ?>
                                                <input type="hidden" value="<?php echo $igst_rate; ?>" name="igst_rate"/>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <!--uom-->
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> UOM<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <select class="form-control" name="item_uom" required="required">
                                                <option value="">--Select UOM--</option>
                                                <?php foreach (getActivepUoms() as $uom_master) { ?>
                                                    <option value="<?php echo $uom_master->desc; ?>" <?php
                                                    if ($uom_master->desc == $product_uom) {
                                                        echo "selected='selected'";
                                                    }
                                                    ?>><?php echo $uom_master->desc; ?></option>
                                                        <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Status<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <select class="form-control" name="status" required="required">
                                                <option value="1" <?php
                                                if ($status == 1) {
                                                    echo "selected='selected'";
                                                }
                                                ?>>Active</option>
                                                <option value="0" <?php
                                                if ($status == 0) {
                                                    echo "selected='selected'";
                                                }
                                                ?>>In-Active</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="clearfix form-actions">
                                        <div class="col-md-offset-3 col-md-9">
                                            <button type="submit" name="submit" class="btn btn-info">
                                                <i class="ace-icon fa fa-check bigger-110"></i>
                                                <?php echo $btn_name; ?>
                                            </button>
                                            &nbsp; &nbsp; &nbsp;
                                            <button class="btn" type="reset">
                                                <i class="ace-icon fa fa-undo bigger-110"></i>
                                                Reset
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

