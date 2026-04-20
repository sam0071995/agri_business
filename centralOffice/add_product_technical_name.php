<?php
require_once 'includes/session.php';
require_once 'includes/common_function.php';
$technical_name_id = "";
$technical_name = "";
$btn_name = "Save";
$description = "";
$status = "1";
$cotegory_tagline = "";
$general_id = "1";


if (isset($_GET['technical_id'])) {
    $technical_name_id = base64_decode($_GET['technical_id']);
    $btn_name = "Update";
    $technical_name_data = getTechnicalNameById($technical_name_id);
    $technical_name = $technical_name_data->name;
    $status = $technical_name_data->status;
}
if (isset($_POST['submit'])) {
    $target_dir = "images/";
    $technical_id = $_POST['technical_id'];
    $technical_name = $_POST['technical_name'];
    $status = $_POST['status'];
    $data['user_id'] = $user_id;
    $data['name'] = $technical_name;
    $data['status'] = $status;
    $data['datetime'] = date("Y-m-d h:i:s");
    $data_history_for_categories = array();
    if (!empty($technical_id)) {
        $where = "id='$technical_id'";
        $data_history_for_categories['remarks'] = "Update";
        $update = update('product_technical_name', $data, $where);
        $data_history_for_categories['technical_id'] = $technical_id;
    } else {
        $data_history_for_categories['remarks'] = "Add";
        $update = insert('product_technical_name', $data);
        $data_history_for_categories['technical_id'] = getLastAddTechName();
    }
    $data_history_for_categories['user_name'] = $username;
    $data_history_for_categories['user_id'] = $user_id;
    $data_history_for_categories['label'] = "TECHNAME";
    $data_history_for_categories['date'] = date("Y-m-d H:i:s");
    $history_for_categories = insert('history_for_categories', $data_history_for_categories);
    if ($update) {
        header("Location:production_technical_name.php" . $menuURL . "&success=1");
        exit;
    } else {
        header("Location:production_technical_name.php" . $menuURL . "&error=1");
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
                                <h3 class="page-header">Add Technical Name.</h3>
                                <form class="form-horizontal" id="searchform" name="myForm"  role="form" action="" method="POST" enctype="multipart/form-data">
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Product Technical Name<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <input type="hidden" name="technical_id" value="<?php echo $technical_name_id; ?>"/>
                                            <input type="text" name="technical_name" placeholder="Enter Technical Name Name" class="input_class form-control" required="required" value="<?php echo $technical_name; ?>"/>
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
