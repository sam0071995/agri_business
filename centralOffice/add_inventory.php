<?php
require_once 'includes/session.php';
require_once 'includes/common_function.php';
$status = 1;
$get_product_id = '';
$product_title = '';
$product_category = '';
$general_category = '';
$short_description = '';
$feature_description = '';
$description = '';
$product_amount = '';
$product_o_amount = '';
$product_client = '';
$feature_image = '';
$latest_product = '';
$popular_product = '';
$product_link = '#';
$btn_name = "Submit";
$g_category_array = array();
$category_array = array();
if (isset($_GET['product_id'])) {
    $get_product_id = base64_decode($_GET['product_id']);
    $productData = getproductDetailsById($get_product_id);
    $product_title = $productData->title;
    $general_category = $productData->g_category_id;
    $product_category = $productData->category_id;
    $short_description = $productData->short_description;
    $feature_description = $productData->feature_description;
    $description = $productData->description;
    $status = $productData->status;
    $latest_product = $productData->latest_product;
    $popular_product = $productData->popular_product;
    $product_amount = $productData->amount;
    $feature_image = $productData->feature_image;
    $product_o_amount = $productData->offered_amount;
    $product_client = $productData->product_client;
    $product_link = $productData->product_link;

    $product_images = getImagesByproductId($get_product_id);
    $categories = getproductCategoryByproductId($get_product_id, 0);
    foreach ($categories as $category) {
        $category_array[] = $category->category_id;
    }
    $g_categories = getproductCategoryByproductId($get_product_id, 1);
    foreach ($g_categories as $g_category) {
        $g_category_array[] = $g_category->category_id;
    }
    $btn_name = "Update";
}

if (isset($_POST['submit'])) {
    $product_id = base64_decode($_POST['product_id']);
    $target_dir = "product_images/";
    if (!empty($_FILES['product_images']['name'][0])) {
        $product_images = $_FILES['product_images'];
        $product_images_a = $_FILES['product_images']['name'];
        foreach ($product_images_a as $key => $product_image) {
            $file_ext = strtolower(pathinfo($product_images["name"][$key], PATHINFO_EXTENSION));
            $file_tmp = $product_images['tmp_name'][$key];
            $file_size = $product_images['size'][$key];
            $file_type = $product_images['type'][$key];
            $expensions = array("png", "jpg", "jpeg");
            if (in_array($file_ext, $expensions) === false) {
                header("Location:add_product.php" . $menuURL . "&error=1");
                exit;
            }
            if ($file_size > 2097152) {
                header("Location:add_product.php" . $menuURL . "&error=2");
                exit;
            }
        }
    }
    if (!empty($_FILES['feature_image']['name'])) {
        $file_ext = strtolower(pathinfo($_FILES["feature_image"]["name"], PATHINFO_EXTENSION));
        $logo = "logo_" . time() . "." . $file_ext;
        $target_logo = $target_dir . $logo;
        $file_tmp = $_FILES['feature_image']['tmp_name'];
        $file_size = $_FILES['feature_image']['size'];
        $file_type = $_FILES['feature_image']['type'];

        $expensions = array("png", "jpg", "jpeg");
        if (in_array($file_ext, $expensions) === false) {
            header("Location:add_product.php" . $menuURL . "&error=1");
            exit;
        }

        if ($file_size > 2097152) {
            header("Location:add_product.php" . $menuURL . "&error=2");
            exit;
        }

        if (!move_uploaded_file($file_tmp, $target_logo)) {
            header("Location:add_product.php" . $menuURL . "&error=3");
            exit;
        }
        if (!empty($feature_image)) {
            unlink("product_images/" . $feature_image);
        }
        if (!empty($logo)) {
            $data['feature_image'] = $logo;
        }
    }
    $table_name = "products";
    if (isset($_POST['latest_product'])) {
        $data['latest_product'] = $_POST['latest_product'];
    } else {
        $data['latest_product'] = 0;
    }
    if (isset($_POST['popular_product'])) {
        $data['popular_product'] = $_POST['popular_product'];
    } else {
        $data['popular_product'] = 0;
    }
    $data['title'] = $_POST['product_title'];
    $data['short_description'] = $_POST['short_description'];
    $data['description'] = $_POST['description'];
    $data['offered_amount'] = $_POST['product_o_amount'];
    $data['amount'] = $_POST['product_amount'];
    $data['product_client'] = $_POST['product_client'];
    $data['product_link'] = $_POST['product_link'];
    $data['user_id'] = $user_id;
    $data['status'] = $_POST['status'];
    $data['date'] = date('Y-m-d');
    $data_history_for_inventory_master = array();
    if (!empty($product_id)) {
        $data['update_datetime'] = date('Y-m-d h:i:s');
        $where = "id='$product_id'";
        $product = update($table_name, $data, $where);
        $last_product_id = $product_id;
        $data_history_for_inventory_master['item_id'] = $last_product_id;
    } else {
        $data['datetime'] = date('Y-m-d h:i:s');
        $product = insert($table_name, $data);
        $last_product_id = getLastIdByTablName($table_name);
        $data_history_for_inventory_master['item_id'] = $last_product_id;
    }

    if ($product) {
        $data_history_for_inventory_master['user_name'] = $username;
        $data_history_for_inventory_master['user_id'] = $user_id;
        $data_history_for_inventory_master['date'] = date("Y-m-d H:i:s");
        $history_for_inventory_master = insert('history_for_inventory_master', $data_history_for_inventory_master);
        $table_name_delete = "product_categoy";
        $whereDelete = "product_id='$product_id'";
        $delete = delete($table_name_delete, $whereDelete);
        $general_category = $_POST['general_category'];
        foreach ($general_category as $general_cat) {
            $dataGenCategory['product_id'] = $last_product_id;
            $dataGenCategory['category_id'] = $general_cat;
            $dataGenCategory['general'] = 1;
            $dataGenCategory['date'] = date('Y-m-d');
            $dataGenCategory['datetime'] = date('Y-m-d h:i:s');
            insert($table_name_delete, $dataGenCategory);
        }
        if (!empty($_FILES['product_images']['name'][0])) {
            $product_images = $_FILES['product_images'];
            $product_images_a = $_FILES['product_images']['name'];
            $image_count = 1;
            foreach ($product_images_a as $key => $product_image) {
                $file_ext = strtolower(pathinfo($product_images["name"][$key], PATHINFO_EXTENSION));
                $image = "product_" . time() . "_" . $key . "." . $file_ext;
                $target_image = $target_dir . $image;
                $file_tmp = $product_images['tmp_name'][$key];
                $file_size = $product_images['size'][$key];
                $file_type = $product_images['type'][$key];

                if (!move_uploaded_file($file_tmp, $target_image)) {
                    header("Location:add_product.php" . $menuURL . "&error=3");
                    exit;
                }
                $dataproductImage['product_id'] = $last_product_id;
                $dataproductImage['image_name'] = $image;
                $dataproductImage['date'] = date("Y-m-d");
                $dataproductImage['datetime'] = date("Y-m-d h:i:s");
                $table_product_delete = "product_images";
                $whereProDelete = "product_id='$product_id'";
                if ($image_count == 1 && !empty($product_id)) {
                    $product_images_unlink = getImagesByproductId($product_id);
                    foreach ($product_images_unlink as $product_image_unlink) {
                        unlink("product_images/" . $product_image_unlink->image_name);
                    }
                    delete($table_product_delete, $whereProDelete);
                    $image_count++;
                }
                insert($table_product_delete, $dataproductImage);
            }
        }
        header("Location:product.php" . $menuURL . "&success=1");
        exit;
    } else {
        header("Location:product.php" . $menuURL . "&error=4");
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
                                        case 2:
                                            $msg = "Image must be less than 2MB.";
                                            break;
                                        case 1:
                                            $msg = "Sorry, only PNG Image is allowed..";
                                            break;
                                        case 3:
                                            $msg = "Sorry, Image Not Uploaded.";
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
                                <h3 class="page-header">product Details.</h3>
                                <form class="form-horizontal" id="searchform" name="myForm"  role="form" action="" method="POST" enctype="multipart/form-data">
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> product Name<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <input type="hidden" name="product_id" value="<?php echo base64_encode($get_product_id); ?>"/>
                                            <input type="text" name="product_title" placeholder="Enter product Title" class="form-control" required="required" value="<?php echo $product_title; ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> General Category<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <select class="form-field-select-2 form-control" multiple="multiple" name="general_category[]" required="required">
                                                <option value="">--Select General Category--</option>
                                                <?php foreach (getproductCategories(1) as $pCategory) { ?>
                                                    <option value="<?php echo $pCategory->id; ?>" <?php
                                                    if (in_array($pCategory->id, $g_category_array)) {
                                                        echo "selected='selected'";
                                                    }
                                                    ?>><?php echo $pCategory->name; ?></option>
                                                        <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Short Description<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <textarea name="short_description" placeholder="Enter Short Description" class="form-control" required="required" id="editor1" rows="10" cols="80"><?php echo $short_description; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Description<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <textarea name="description" placeholder="Enter Description" class="form-control" required="required" id="editor2"><?php echo $description; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Feature Image : </label>
                                        <div class="col-sm-6">
                                            <input name="feature_image" type="file" id="id-input-file-3" />
                                            <img src="product_images/<?php echo $feature_image; ?>" height="50px" />
                                        </div>
                                    </div>
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> product Images : </label>
                                        <div class="col-sm-6">
                                            <input name="product_images[]" multiple="multiple" type="file" id="id-input-file-4" />
                                            <?php foreach ($product_images as $product_image) { ?>
                                                <img src="product_images/<?php echo $product_image->image_name; ?>" height="50px" />
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> product Link<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <input type="text" name="product_link" placeholder="Enter product Link" class="form-control" required="required" value="<?php echo $product_link; ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Client Name<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <input type="text" name="product_client" placeholder="Enter Client Name" class="form-control" required="required" value="<?php echo $product_client; ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> product Amount<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <input type="text" name="product_amount" placeholder="Enter product Amount" class="form-control" required="required" value="<?php echo $product_amount; ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> product Offered Amount<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <input type="text" name="product_o_amount" placeholder="Enter product Offered Amount" class="form-control" required="required" value="<?php echo $product_o_amount; ?>"/>
                                        </div>
                                    </div>
                                    <!--uom-->
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
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Latest product </label>
                                        <div class="col-sm-6">
                                            <input name="latest_product" value="1" <?php
                                            if ($latest_product == 1) {
                                                echo "checked='checked'";
                                            }
                                            ?> class="ace ace-switch ace-switch-4 btn-empty" type="checkbox" />
                                            <span class="lbl"></span>
                                        </div>
                                    </div>
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Popular product </label>
                                        <div class="col-sm-6">
                                            <input name="popular_product" value="1" <?php
                                            if ($popular_product == 1) {
                                                echo "checked='checked'";
                                            }
                                            ?>  class="ace ace-switch ace-switch-4 btn-empty" type="checkbox" />
                                            <span class="lbl"></span>
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

