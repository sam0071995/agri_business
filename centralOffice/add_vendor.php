<?php
require_once 'includes/session.php';
require_once 'includes/common_function.php';
$status = 1;
$get_vendor_id = '';
$vendor_code = '';
$vendor_name = '';
$address = '';
$opening_balances = '';
$contact_name = '';
$gstin_number = '';
$contact_numbar = '';
$status = 1;
$email = '';
$vendor_state_id = '';
$vendor_company_id = '';
$vendor_bdm_id = '';
$password = '';
$pincode = '';
$party_type = '';
$btn_name = "Submit";

if (isset($_GET['vendor_id'])) {
    $get_vendor_id = base64_decode($_GET['vendor_id']);
    $productData = getVendorDetailById($get_vendor_id);
    $vendor_name = $productData->vendor_name;
    $party_type = $productData->party_type;
    $address = $productData->address;
    $gstin_no = $productData->gstin_no;
    $contact_name = $productData->c_person;
    $contact_numbar = $productData->c_number;
    $gstin_number = $productData->gstin_no;
    $opening_balances = $productData->opening_balances;
    $status = $productData->vendor_status;
    $email = $productData->c_email;
    $pincode = $productData->pincode;
    $btn_name = "Update";
}

if (isset($_POST['submit'])) {
    $table_name = "vendor_master";
    if (isset($_POST['post_vendor_id'])) {
        $post_vendor_id = base64_decode($_POST['post_vendor_id']);
    }
    $max_inc_no = getMaxVendorIncNo();
    $max_inc_no = $max_inc_no + 1;
    $max_inc_no = sprintf('%04s', $max_inc_no);

    $data['vendor_name'] = $_POST['vendor_name'];
    $data['party_type'] = $_POST['party_type'];
    $data['address'] = $_POST['address'];
    $data['c_person'] = $_POST['contact_name'];
    $data['c_number'] = $_POST['contact_number'];
    $data['gstin_no'] = $_POST['gstin_number'];
    $data['c_email'] = $_POST['email'];
    $data['opening_balances'] = $_POST['opening_balances'];
    $data['pincode'] = $_POST['pincode'];
    $data['vendor_status'] = $_POST['status'];

    if (!empty($post_vendor_id)) {
        $data['update_time'] = date('Y-m-d h:i:s');
        $where = "vendor_id='$post_vendor_id'";
        $vendor = update($table_name, $data, $where);
    } else {
        $data['inc_code'] = $max_inc_no;
        $data['vendor_code'] = "VENDOR" . $max_inc_no;
        $data['DATE'] = date('Y-m-d');
        $vendor = insert($table_name, $data);
    }
    if ($vendor) {
        header("Location:vendor_master.php" . $menuURL . "&success=1");
        exit;
    } else {
        header("Location:vendor_master.php" . $menuURL . "&error=1");
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
                                            $msg = "Vendor can not be insert.";
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
                                        <?php echo "Vendor Updated Successfully"; ?>
                                    </div>
                                <?php } ?>
                                <h3 class="page-header">Add New Vendor</h3>
                                <form class="form-horizontal" id="searchform" name="myForm"  role="form" action="" method="POST" enctype="multipart/form-data">
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Vendor Name<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <input type="hidden" name="post_vendor_id" value="<?php echo base64_encode($get_vendor_id); ?>"/>
                                            <input type="text" name="vendor_name" placeholder="Enter Vendor Name" class="form-control" required="required" value="<?php echo $vendor_name; ?>"/>
                                        </div>
                                    </div>

                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Address<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <textarea name="address" placeholder="Enter Vendor Address" class="form-control" required="required" id="editor2"><?php echo $address; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Pincode<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <input type="text" name="pincode" placeholder="Enter Vendor Pincode" class="form-control" required="required" value="<?php echo $pincode; ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Contact Name<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <input type="text" name="contact_name" placeholder="Enter Vendor Contact Name" class="form-control" required="required" value="<?php echo $contact_name; ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Contact Number<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <input type="text" name="contact_number" placeholder="Enter Vendor Contact Number" class="form-control" value="<?php echo $contact_numbar; ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> GSTIN Number<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <input type="text" name="gstin_number" placeholder="Enter Vendor GSTIN Number" class="form-control" required="required" value="<?php echo $gstin_number; ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Contact Email<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <input type="text" name="email" placeholder="Enter Vendor Contact Email" class="form-control" value="<?php echo $email; ?>"/>
                                        </div>
                                    </div>

                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Party Type<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <select class="form-control" name="party_type" required="required">
                                                <option value="Debtor" <?php
                                                if ($status == "Debtor") {
                                                    echo "selected='selected'";
                                                }
                                                ?>>Debtor</option>
                                                <option value="Creditor" <?php
                                                if ($status == "Creditor") {
                                                    echo "selected='selected'";
                                                }
                                                ?>>Creditor</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Opening Balances<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <select class="form-control" name="opening_balances" required="required">
                                                <option value="Credit" <?php
                                                if ($status == "Credit") {
                                                    echo "selected='selected'";
                                                }
                                                ?>>Credit</option>
                                                <option value="Debit" <?php
                                                if ($status == "Debit") {
                                                    echo "selected='selected'";
                                                }
                                                ?>>Debit</option>
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

