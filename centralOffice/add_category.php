<?php
require_once 'includes/session.php';
require_once 'includes/common_function.php';
$category_id = "";
$cotegory_name = "";
$btn_name = "Save";
$description = "";
$status = "1";
$cotegory_tagline = "";
$general_id = "1";

$parent_category = "";

if (isset($_GET['category_id'])) {
    $category_id = base64_decode($_GET['category_id']);
    $btn_name = "Update";
    $category = getAdminCategoriesById($category_id);
    $cotegory_name = $category->name;
    $status = $category->status;
    if ($category->parent_category == 0) {
        $parent_category = "111";
    } else {
        $parent_category = $category->parent_category;
    }
}
if (isset($_POST['submit'])) {
    $target_dir = "images/";
    $cotegory_id = $_POST['cotegory_id'];
    $parent_category = $_POST['parent_category'];
    $cotegory_name = $_POST['cotegory_name'];
    $status = $_POST['status'];
    $data['user_id'] = $user_id;
    $data['name'] = $cotegory_name;
    $data['parent_category'] = $parent_category;
    $data['status'] = $status;
    $data['datetime'] = date("Y-m-d h:i:s");
    $data_history_for_categories = array();
    if (!empty($cotegory_id)) {
        $where = "id='$cotegory_id'";
        $data_history_for_categories['remarks'] = "Update";
        $update = update('categories', $data, $where);
        $data_history_for_categories['category_id'] = $cotegory_id;
    } else {
        $data_history_for_categories['remarks'] = "Add";
        $update = insert('categories', $data);
        $data_history_for_categories['category_id'] = getLastAddCategory();
    }
    $data_history_for_categories['user_name'] = $username;
    $data_history_for_categories['user_id'] = $user_id;
    $data_history_for_categories['date'] = date("Y-m-d H:i:s");
    $history_for_categories = insert('history_for_categories', $data_history_for_categories);
    if ($update) {
        header("Location:categories.php" . $menuURL . "&success=1");
        exit;
    } else {
        header("Location:categories.php" . $menuURL . "&error=1");
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
                                        <?php echo "Data Updated Successfully"; ?>
                                    </div>
                                <?php } ?>
                                <h3 class="page-header">Add Categories.</h3>
                                <form class="form-horizontal" id="searchform" name="myForm"  role="form" action="" method="POST" enctype="multipart/form-data">
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Categories Name<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <input type="hidden" name="cotegory_id" value="<?php echo $category_id; ?>"/>
                                            <input type="text" name="cotegory_name" placeholder="Enter Category Name" class="input_class form-control" required="required" value="<?php echo $cotegory_name; ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Select Parent Categories<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <select class="form-field-select-2 form-control" name="parent_category">
                                                <option value="0">--Select Item Parent Category--</option>
                                                <?php foreach (getActiveproductCategories() as $pCategory) { ?>
                                                    <?php if ($pCategory->id != $category_id) { ?>
                                                        <option value="<?php echo $pCategory->id; ?>" <?php
                                                        if ($pCategory->id == $parent_category) {
                                                            echo "selected='selected'";
                                                        }
                                                        ?>><?php echo $pCategory->name; ?></option>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
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
