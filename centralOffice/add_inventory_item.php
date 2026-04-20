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
$pack_size = '';
$unit = '';
$product_brand = '';
$technical_name = '';
$PC_per_carton = '';
$moq = '';
$shelf_life = '';
$btn_name = "Submit";
$g_category_array = '';
$category_array = '';
$read_only = '';
$product_image = '';
if (isset($_GET['product_id'])) {
    $read_only = 'readonly="readonly"';
    $get_product_id = base64_decode($_GET['product_id']);
    $productData = getproductDetailsById($get_product_id);
    $inventory_item_name = clean($productData->item_desc);
    $main_category_id = $productData->main_category_id;
    $sub_category_id = $productData->sub_category_id;
    $description = $productData->description;
    $hsn_code = $productData->hsn_code;
    $igst_rate = $productData->igst_rate;
    $sgst_rate = $productData->sgst_rate;
    $cgst_rate = $productData->cgst_rate;
    $status = $productData->status;
    $product_uom = $productData->uom;
    $pack_size = $productData->pack_size;
    $unit = $productData->unit;
    $moq = $productData->moq;
    $product_image = $productData->product_image;
    if (!empty($productData->brand_name)) {
        $product_brand = $productData->brand_name;
    }
    if (!empty($productData->technical_name)) {
        $technical_name = $productData->technical_name;
    }
    if (!empty($productData->PC_per_carton)) {
        $PC_per_carton = $productData->PC_per_carton;
    }
    if (!empty($productData->shelf_life)) {
        $shelf_life = $productData->shelf_life;
    }
    $btn_name = "Update";
}

if (isset($_POST['submit'])) {
    $data = array();
    if (isset($_FILES['product_image'])) {
        if (!empty($_FILES['product_image']) && $_FILES['product_image']['error'] === 0) {
            $uploadDir = 'product_image/';
            $fileName = basename($_FILES['product_image']['name']);
            $targetFilePath = $uploadDir . $fileName;

            // Create directory if not exists
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Validate file type (optional)
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array(strtolower($fileType), $allowedTypes)) {
                if (move_uploaded_file($_FILES['product_image']['tmp_name'], $targetFilePath)) {
                    $data['product_image'] = $targetFilePath;
                } else {
                    header("Location:inventory_master.php" . $menuURL . "&error=101");
                    exit;
                }
            } else {
                header("Location:inventory_master.php" . $menuURL . "&error=102");
                exit;
            }
        }
    }


    $table_name = "inventory_master";
    if (isset($_POST['item_id'])) {
        $product_id = base64_decode($_POST['item_id']);
    }
    $max_inc_no = getMaxItemIncNo();
    $max_inc_no = $max_inc_no + 1;
    $data['item_desc'] = clean($_POST['item_name']);
    $data['main_category_id'] = $_POST['main_category'];
    $data['sub_category_id'] = $_POST['sub_category'];
    $data['description'] = $_POST['description'];
    $data['hsn_code'] = $_POST['hsn_code'];
    $data['igst_rate'] = $_POST['igst_rate'];
    $data['sgst_rate'] = $_POST['igst_rate'] / 2;
    $data['cgst_rate'] = $_POST['igst_rate'] / 2;
    $data['status'] = $_POST['status'];
    $data['pack_size'] = $_POST['item_packsize'];
    $data['uom'] = $_POST['item_uom'];
    $data['unit'] = $_POST['item_unit'];
    $data['active'] = $_POST['status'];
    $data['brand_name'] = $_POST['product_brand'];
    $data['technical_name'] = $_POST['technical_name'];
    $data['PC_per_carton'] = $_POST['PC_per_carton'];
    $data['moq'] = $_POST['moq'];
    $data['shelf_life'] = $_POST['shelf_life'];

    if (!empty($product_id)) {
        $productData = getproductDetailsById($product_id);
        $data['updated_date'] = date('Y-m-d h:i:s');
        $where = "id='$product_id'";
        $product = update($table_name, $data, $where);

        $retailer_items = getItemDetailByItemCode($productData->item_code);
        foreach ($retailer_items as $retailer_item) {
            $retailer_id = $retailer_item->retailer_id;
            $item_total_price = $retailer_item->total;
            $retailer_item_code = $retailer_item->item_code;

            $item_basic_price = $item_total_price * 100 / (100 + $_POST['igst_rate']);
            $cgst_value = ($item_basic_price * ($_POST['igst_rate'] / 2)) / 100;
            $sgst_value = ($item_basic_price * ($_POST['igst_rate'] / 2)) / 100;
            $igst_value = ($item_basic_price * $_POST['igst_rate']) / 100;

            $item_total = $item_total_price;


            $data_history_for_inventory_master['item_id'] = $product_id;
            $data_history_for_inventory_master['retailer_id'] = $retailer_id;
            $data_history_for_inventory_master['remarks'] = "Update";

            $data_history_for_inventory_master['old_gst_rate'] = $retailer_item->igst_rate;
            $data_history_for_inventory_master['new_gst_rate'] = $_POST['igst_rate'];
            $data_history_for_inventory_master['old_price'] = $retailer_item->basic_price;
            $data_history_for_inventory_master['old_total'] = $retailer_item->total;
            $data_history_for_inventory_master['new_price'] = $item_basic_price;
            $data_history_for_inventory_master['new_total'] = $item_total;
            $data_history_for_inventory_master['old_igst'] = $retailer_item->igst_value;
            $data_history_for_inventory_master['new_igst'] = $igst_value;


            $dataRetailerMaster = array();
            $dataRetailerMaster['item_desc'] = clean($_POST['item_name']);
            $dataRetailerMaster['updated_date'] = date('Y-m-d h:i:s');
            $dataRetailerMaster['hsn_code'] = $_POST['hsn_code'];
            $dataRetailerMaster['main_category_id'] = $_POST['main_category'];
            $dataRetailerMaster['sub_category_id'] = $_POST['sub_category'];

            $dataRetailerMaster['brand_name'] = $_POST['product_brand'];
            $dataRetailerMaster['technical_name'] = $_POST['technical_name'];
            $dataRetailerMaster['basic_price'] = $item_basic_price;
            $dataRetailerMaster['cgst_rate'] = ($_POST['igst_rate'] / 2);
            $dataRetailerMaster['cgst_value'] = $cgst_value;
            $dataRetailerMaster['sgst_rate'] = ($_POST['igst_rate'] / 2);
            $dataRetailerMaster['sgst_value'] = $sgst_value;
            $dataRetailerMaster['igst_rate'] = $_POST['igst_rate'];
            $dataRetailerMaster['PC_per_carton'] = $_POST['PC_per_carton'];
            $dataRetailerMaster['shelf_life'] = $_POST['shelf_life'];
            $dataRetailerMaster['igst_value'] = $igst_value;
            $dataRetailerMaster['total'] = $item_total;

            $dataRetailerMaster['pack_size'] = $_POST['item_packsize'];
            $dataRetailerMaster['unit'] = $_POST['item_unit'];
            $dataRetailerMaster['status'] = $_POST['status'];
            $dataRetailerMaster['uom'] = $_POST['item_uom'];
            $dataRetailerMaster['active'] = $_POST['status'];
            $whereRetailerMaster = "retailer_id='$retailer_id' and item_code='$retailer_item_code'";
            update("retailer_inventory_master", $dataRetailerMaster, $whereRetailerMaster);
        }
    } else {
        $data['inc_no'] = $max_inc_no;
        $data['item_code'] = "AGRO" . $max_inc_no . time();
        $data['sr_no'] = $max_inc_no;
        $data['date'] = date('Y-m-d');
        $data['datetime'] = date('Y-m-d h:i:s');
        $product = insert($table_name, $data);
        $last_product_id = getLastIdByTablName($table_name);
        $data_history_for_inventory_master['item_id'] = $last_product_id;
        $data_history_for_inventory_master['remarks'] = "Add";
    }

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
                                        case 101:
                                            $msg = "Image can not uploaded.";
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
                                <h3 class="page-header">Add Inventory Item.</h3>
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
                                            <select class="form-field-select-2 form-control chosen-select" name="main_category" required="required">
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
                                            <select class="form-field-select-2 form-control chosen-select" name="sub_category" required="required">
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
                                            <select class="form-field-select-2 form-control chosen-select" name="product_brand" required="required">
                                                <option value="">--Select Item Brand--</option>
                                                <?php foreach (getActiveproductProductBrand() as $brand_name) { ?>
                                                    <option value="<?php echo $brand_name->name; ?>" <?php
                                                    if ($brand_name->name == $product_brand) {
                                                        echo "selected='selected'";
                                                    }
                                                    ?>><?php echo $brand_name->name; ?></option>
                                                        <?php } ?>
                                            </select>
                                            <!--<input type="text" name="product_brand" placeholder="Enter Brand Name" class="form-control" required="required" value="<?php echo $product_brand; ?>"/>-->
                                        </div>
                                    </div>
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Product Technical Name<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <select class="form-field-select-2 form-control chosen-select" name="technical_name" required="required">
                                                <option value="">--Select Technical Name--</option>
                                                <?php foreach (getActiveproductTechnicalName() as $technical_name_data) { ?>
                                                    <option value="<?php echo $technical_name_data->name; ?>" <?php
                                                    if ($technical_name_data->name == $technical_name) {
                                                        echo "selected='selected'";
                                                    }
                                                    ?>><?php echo $technical_name_data->name; ?></option>
                                                        <?php } ?>
                                            </select>
                                            <!--<input type="text" name="product_brand" placeholder="Enter Brand Name" class="form-control" required="required" value="<?php echo $technical_name; ?>"/>-->
                                        </div>
                                    </div>
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> PC Per Carton<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <input type="text" name="PC_per_carton" placeholder="Enter PC Per Carton" class="form-control" required="required" value="<?php echo $PC_per_carton; ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">MOQ<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <input type="text" name="moq" placeholder="Enter Minimum Order Qty" class="form-control" required="required" value="<?php echo $moq; ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Shelf Life<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <input type="text" name="shelf_life" placeholder="Enter Shelf Life" class="form-control" required="required" value="<?php echo $shelf_life; ?>"/>
                                            <p class="red">1 Month to 12 Month / 1 Year to 5 Year" (e.g., 1 Year 2 Month ? 1.2)</p>
                                        </div>
                                    </div>
                                    <?php if (!isset($_GET['product_id'])) { ?>
                                        <div class="form-group" id="c_n_password_c">
                                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> GST Rate<span style="color:red">*</span> : </label>
                                            <div class="col-sm-6">
                                                <select name="igst_rate" <?php echo $read_only; ?>>
                                                    <option <?php
                                                    if ($igst_rate == 0) {
                                                        echo 'selected="selected"';
                                                    }
                                                    ?> value="0">0</option>
                                                    <option <?php
                                                    if ($igst_rate == 5) {
                                                        echo 'selected="selected"';
                                                    }
                                                    ?> value="5">5</option>
                                                    <option <?php
                                                    if ($igst_rate == 12) {
                                                        echo 'selected="selected"';
                                                    }
                                                    ?> value="12">12</option>
                                                    <option <?php
                                                    if ($igst_rate == 18) {
                                                        echo 'selected="selected"';
                                                    }
                                                    ?> value="18">18</option>
                                                    <option <?php
                                                    if ($igst_rate == 28) {
                                                        echo 'selected="selected"';
                                                    }
                                                    ?> value="28">28</option>
                                                </select>
                                            </div>
                                        </div>
                                    <?php } else { ?>

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
                                                <?php // echo $igst_rate;  ?>
                                                <!--<input type="hidden" value="<?php // echo $igst_rate;                                                                  ?>" name="igst_rate"/>-->
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <!--uom-->
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> UOM<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <select class="form-control chosen-select" name="item_uom" required="required">
                                                <option value="">--Select UOM--</option>
                                                <?php foreach (getActivepUoms() as $uom_master) { ?>
                                                    <option value="<?php echo $uom_master->desc; ?>" <?php
                                                    if (strtoupper($uom_master->desc) == strtoupper($product_uom)) {
                                                        echo "selected='selected'";
                                                    }
                                                    ?>><?php echo $uom_master->desc; ?></option>
                                                        <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> PACK SIZE<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <select class="form-control chosen-select" name="item_packsize" required="required">
                                                <option value="">--Select PACKSIZE--</option>
                                                <?php foreach (getActivepPackSize() as $pack_size_master) { ?>
                                                    <option value="<?php echo $pack_size_master->name; ?>" <?php
                                                    if (strtoupper($pack_size_master->name) == strtoupper($pack_size)) {
                                                        echo "selected='selected'";
                                                    }
                                                    ?>><?php echo $pack_size_master->name; ?></option>
                                                        <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Unit<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <select class="form-control" name="item_unit" required="required">
                                                <option value="">--Select Unit--</option>
                                                <?php foreach (getActivepUnit() as $unit_master) { ?>
                                                    <option value="<?php echo $unit_master->name; ?>" <?php
                                                    if (strtoupper($unit_master->name) == strtoupper($unit)) {
                                                        echo "selected='selected'";
                                                    }
                                                    ?>><?php echo $unit_master->name; ?></option>
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

                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">
                                            Product Image<span style="color:red">*</span> :
                                        </label>
                                        <div class="col-sm-6">
                                            <input type="file" name="product_image" class="form-control" />
                                            <p><a href="<?php echo $product_image; ?>" target="_blank">Uploaded Image</a></p>                         
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

