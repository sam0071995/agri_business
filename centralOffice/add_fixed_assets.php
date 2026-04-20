<?php
require_once 'includes/session.php';
require_once 'includes/common_function.php';
$fixed_assets_id = "";
$fixed_assets_name = "";
$btn_name = "Save";
$description = "";
$status = "1";
$cotegory_tagline = "";
$general_id = "1";

$parent_category = "";

if (isset($_GET['fixed_assets_id'])) {
    $fixed_assets_id = base64_decode($_GET['fixed_assets_id']);
    $btn_name = "Update";
    $category = getAdminFixedAssetsById($fixed_assets_id);
    $fixed_assets_name = $category->item_name;
    $status = $category->status;
    $parent_category = $category->category;
}
if (isset($_POST['submit'])) {
    $fixed_assets_id = $_POST['fixed_assets_id'];
    $parent_category = $_POST['category'];
    $fixed_assets_name = $_POST['fixed_assets_name'];
    $status = $_POST['status'];
    $data['user_id'] = $user_id;
    $data['item_name'] = $fixed_assets_name;
    $data['category'] = $parent_category;
    $data['status'] = $status;
    $data['datetime'] = date("Y-m-d h:i:s");
    if (!empty($fixed_assets_id)) {
        $where = "id='$fixed_assets_id'";
        $update = update('fixed_asset', $data, $where);
    } else {
        $data['added_datetime'] = date("Y-m-d h:i:s");
        $data['item_code'] = "FIXEDASS" . time();
        $update = insert('fixed_asset', $data);
    }
    if ($update) {
        header("Location:add_fixed_assets.php" . $menuURL . "&success=1");
        exit;
    } else {
        header("Location:add_fixed_assets.php" . $menuURL . "&error=1");
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
                                        <?php echo "Fixed Assets Successfully Added/Updated"; ?>
                                    </div>
                                <?php } ?>
                                <h3 class="page-header">Add Fixed Assets.</h3>
                                <form class="form-horizontal" id="searchform" name="myForm"  role="form" action="" method="POST" enctype="multipart/form-data">
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Fixed Assets Name<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <input type="hidden" name="fixed_assets_id" value="<?php echo $fixed_assets_id; ?>"/>
                                            <input type="text" name="fixed_assets_name" placeholder="Enter Fixed Assets" class="input_class form-control" required="required" value="<?php echo $fixed_assets_name; ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Select Fixed Assets Categories<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <select class="form-field-select-2 form-control" name="category">
                                                <option value="0">--Select Fixed Assets Category--</option>
                                                <option value="Fixed Assets" <?php
                                                if ($parent_category == "Fixed Assets") {
                                                    echo 'Selected="selected"';
                                                }
                                                ?>>Fixed Assets</option>
                                                <option value="NO OF LOCKS" <?php
                                                if ($parent_category == "NO OF LOCKS") {
                                                    echo 'Selected="selected"';
                                                }
                                                ?>>NO OF LOCKS</option>
                                                <option value="NO OF DISPLAY RACKS" <?php
                                                if ($parent_category == "NO OF DISPLAY RACKS") {
                                                    echo 'Selected="selected"';
                                                }
                                                ?>>NO OF DISPLAY RACKS</option>
                                                <option value="OTHERS" <?php
                                                if ($parent_category == "OTHERS") {
                                                    echo 'Selected="selected"';
                                                }
                                                ?>>OTHERS</option>
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
