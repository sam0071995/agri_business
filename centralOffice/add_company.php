<?php
require_once 'includes/session.php';
require_once 'includes/common_function.php';
$company_id = "";
$company_name = "";
$conmany_unit_name = "";
$gstin_no = "";
$pan_no = "";
$address = "";
$cin_no = "";
$btn_name = "Save";
$description = "";
$status = "1";
$cotegory_tagline = "";
$general_id = "1";

$parent_category = "";

if (isset($_GET['company_id'])) {
    $company_id = base64_decode($_GET['company_id']);
    $btn_name = "Update";
    $company = getCompanyDetailsById($company_id);
    $company_name = $company->name;
    $conmany_unit_name = $company->unit_name;
    $gstin_no = $company->gst_no;
    $address = $company->address;
    $pan_no = $company->pan_no;
    $cin_no = $company->cin_no;
    $status = $company->status;
}
if (isset($_POST['submit'])) {
    $target_dir = "images/";
    $company_id = $_POST['company_id'];
    $company_name = $_POST['company_name'];
    $company_prefix = substr($company_name, 0, 5);
    $company_prefix = preg_replace('/\s+/', '', $company_prefix);
    $company_unit_name = $_POST['company_unit_name'];
    $gstin_no = $_POST['gstin_no'];
    $cin_no = $_POST['cin_no'];
    $address = $_POST['address'];
    $pan_no = $_POST['pan_no'];
    $status = $_POST['status'];
    $data = array();
    $data['prefix'] = $company_prefix;
    $data['unit_name'] = $company_unit_name;
    $data['address'] = $address;
    $data['gst_no'] = $gstin_no;
    $data['pan_no'] = $pan_no;
    $data['cin_no'] = $cin_no;
    $data['status'] = $status;
    $data['date'] = date("Y-m-d");
    $data['datetime'] = date("Y-m-d h:i:s");
    if (!empty($company_id)) {
        $where = "id='$company_id'";
        $update = update('company_master', $data, $where);
    } else {
        $data['name'] = $company_name;
        $update = insert('company_master', $data);
    }
    if ($update) {
        header("Location:companies.php" . $menuURL . "&success=1");
        exit;
    } else {
        header("Location:companies.php" . $menuURL . "&error=1");
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
                                <h3 class="page-header">Add Companies.</h3>
                                <form class="form-horizontal" id="searchform" name="myForm"  role="form" action="" method="POST" enctype="multipart/form-data">
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Company Name<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <input type="hidden" name="company_id" value="<?php echo $company_id; ?>"/>
                                            <input type="text" name="company_name" placeholder="Enter Company Name" class="input_class form-control" required="required" value="<?php echo $company_name; ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Company Unit Name<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <input type="text" name="company_unit_name" placeholder="Enter Unit Company Name" class="form-control" required="required" value="<?php echo $conmany_unit_name; ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Company GSTIN No<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <input type="text" name="gstin_no" placeholder="Enter GSTIN No" class="form-control" required="required" value="<?php echo $gstin_no; ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Company PAN No<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <input type="text" name="pan_no" placeholder="Enter PAN No" class="form-control" required="required" value="<?php echo $pan_no; ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Company CIN No<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <input type="text" name="cin_no" placeholder="Enter CIN No" class="form-control" required="required" value="<?php echo $cin_no; ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Company Address<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <textarea name="address" placeholder="Enter Address" class="form-control" required="required"><?php echo $address; ?></textarea>
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
