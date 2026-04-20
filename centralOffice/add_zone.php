<?php
require_once 'includes/session.php';
require_once 'includes/common_function.php';
$status = 1;
$get_zone_id = '';
$zone_code = '';
$zone_name = '';
$address = '';
$contact_name = '';
$contact_numbar = '';
$status = 1;
$email = '';
$zone_state_id = '';
$zone_company_id = '';
$zone_bdm_id = '';
$password = '';
$pincode = '';
$btn_name = "Submit";

if (isset($_GET['zone_id'])) {
    $get_zone_id = base64_decode($_GET['zone_id']);
    $productData = getZoneById($get_zone_id);
    $zone_name = $productData->name;
    $status = $productData->status;
    $zone_company_id = $productData->company_id;
    $btn_name = "Update";
}

if (isset($_POST['submit'])) {
    $table_name = "zonal_master";
    if (isset($_POST['post_zone_id'])) {
        $post_zone_id = base64_decode($_POST['post_zone_id']);
    }
    $data['company_id'] = $_POST['company_id'];
    $data['name'] = $_POST['zone_name'];
    $data['status'] = $_POST['status'];
    $data['user_id'] = $user_id;

    if (!empty($post_zone_id)) {
        $data['updated_date'] = date('Y-m-d h:i:s');
        $where = "id='$post_zone_id'";
        $zone = update($table_name, $data, $where);
    } else {
        $data['date'] = date('Y-m-d h:i:s');
        $zone = insert($table_name, $data);
    }
    if ($zone) {
        header("Location:zone_master.php" . $menuURL . "&success=1");
        exit;
    } else {
        header("Location:zone_master.php" . $menuURL . "&error=1");
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
                                            $msg = "Store/Zone can not be insert.";
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
                                        <?php echo "Store/Zone Updated Successfully"; ?>
                                    </div>
                                <?php } ?>
                                <h3 class="page-header">Add New Zone</h3>
                                <form class="form-horizontal" id="searchform" name="myForm"  role="form" action="" method="POST" enctype="multipart/form-data">
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Select Company<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <?php
                                            if (isset($_GET['zone_id'])) {
                                                echo "<h4 class='red'>" . getCompanyNameById($zone_company_id) . "</h4>";
                                                ?>
                                                <input type="hidden"  name="company_id" value="<?php echo $zone_company_id; ?>" />
                                                <?php
                                            } else {
                                                ?>
                                                <select class="form-field-select-2 form-control" name="company_id" required="required">
                                                    <option value="">--Select Company--</option>

                                                    <?php
                                                    foreach ($assign_company_array as $assign_company) {
                                                        ?>
                                                        <option value="<?php echo $assign_company; ?>" <?php
                                                        if ($assign_company == $zone_company_id) {
                                                            echo "selected='selected'";
                                                        }
                                                        ?>><?php echo getCompanyNameById($assign_company); ?></option>
                                                            <?php } ?>
                                                </select>
                                            <?php } ?>
                                        </div>
                                    </div>

                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Zone Name<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <input type="hidden" name="post_zone_id" value="<?php echo base64_encode($get_zone_id); ?>"/>
                                            <input type="text" name="zone_name" placeholder="Enter Zone Name" class="form-control" required="required" value="<?php echo $zone_name; ?>"/>
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

