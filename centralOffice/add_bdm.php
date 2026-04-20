<?php
require_once 'includes/session.php';
require_once 'includes/common_function.php';
$status = 1;
$get_retailer_id = '';
$retailer_code = '';
$retailer_name = '';
$address = '';
$contact_name = '';
$contact_numbar = '';
$status = 1;
$email = '';
$retailer_state_id = 0;
$retailer_company_id = '';
$password = '';
$pincode = '';
$get_distributer_id_array = array();
$btn_name = "Submit";

if (isset($_GET['bdm_id'])) {
    $get_retailer_id = base64_decode($_GET['bdm_id']);
    $productData = getBDMById($get_retailer_id);
    $retailer_name = $productData->full_name;
    $contact_numbar = $productData->contact_number;
    $status = $productData->status;
    $email = $productData->email;
    $password = $productData->password;
    $retailer_company_id = $productData->company_id;
    $pincode = $productData->pincode;
    $retailer_state_id = $productData->state_id;
    $get_post_distributer_id_array = $productData->retailer_id;
    $get_distributer_id_array = explode(",", $get_post_distributer_id_array);
    $btn_name = "Update";
}

if (isset($_POST['submit'])) {
    $table_name = "bdm_master";
    if (isset($_POST['post_retailer_id'])) {
        $post_retailer_id = base64_decode($_POST['post_retailer_id']);
    }
    $post_distributer_id_array = $_POST['distributer_id'];
    $distributer_id_string = implode(",", $post_distributer_id_array);
    $max_inc_no = getMaxSellerIncNoBDM();
    $max_inc_no = $max_inc_no + 1;
    $max_inc_no = sprintf('%03s', $max_inc_no);
    $data['company_id'] = $_POST['company_id'];
    $data['retailer_id'] = $distributer_id_string;
    $data['name'] = $_POST['retailer_name'];
    $data['full_name'] = $_POST['retailer_name'];
    $data['contact_number'] = $_POST['contact_number'];
    $data['pincode'] = $_POST['pincode'];
    $data['email'] = $_POST['email'];
    $data['status'] = $_POST['status'];
    $data['state_id'] = $_POST['state_id'];
    $data['zone_id'] = $_POST['state_id'];
    $data['warehouse_id'] = $_POST['state_id'];

    if (!empty($post_retailer_id)) {
        $data['updated_date'] = date('Y-m-d h:i:s');
        $where = "id='$post_retailer_id'";
        $retailer = update($table_name, $data, $where);
    } else {
        $data['password'] = generateRandomString();
        $data['menu'] = "2,3,14,15,16,17,12,404";
        $data['inc_code'] = $max_inc_no;
        $data['bdm_code'] = "BDM" . $max_inc_no;
        $data['date'] = date('Y-m-d');
        $data['added_date'] = date('Y-m-d h:i:s');
        $retailer = insert($table_name, $data);
    }

    if ($retailer) {
        header("Location:bdm_master.php" . $menuURL . "&success=1");
        exit;
    } else {
        header("Location:bdm_master.php" . $menuURL . "&error=1");
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
                                            $msg = "Retailer can not be insert.";
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
                                        <?php echo "Retailer Updated Successfully"; ?>
                                    </div>
                                <?php } ?>
                                <h3 class="page-header">Add New BDM</h3>
                                <form class="form-horizontal" id="searchform" name="myForm"  role="form" action="" method="POST" enctype="multipart/form-data">
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Select Company<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <select class="form-field-select-2 form-control" name="company_id" required="required">
                                                <option value="">--Select Company--</option>
                                                <?php
                                                foreach ($assign_company_array as $assign_company) {
                                                    ?>
                                                    <option value="<?php echo $assign_company; ?>" <?php
                                                    if ($assign_company == $retailer_company_id) {
                                                        echo "selected='selected'";
                                                    }
                                                    ?>><?php echo getCompanyNameById($assign_company); ?></option>
                                                        <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Select State<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <select class="form-field-select-2 form-control" name="state_id" required="required">
                                                <option value="">--Select State--</option>
                                                <?php foreach (getActiveStates() as $state) { ?>
                                                    <option value="<?php echo $state->id; ?>" <?php
                                                    if ($state->id == $retailer_state_id) {
                                                        echo "selected='selected'";
                                                    }
                                                    ?>><?php echo $state->name; ?></option>
                                                        <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> BDM Name<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <input type="hidden" name="post_retailer_id" value="<?php echo base64_encode($get_retailer_id); ?>"/>
                                            <input type="text" name="retailer_name" placeholder="Enter BDM Name" class="form-control" required="required" value="<?php echo $retailer_name; ?>"/>
                                        </div>
                                    </div>

                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Contact Number<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <input type="text" name="contact_number" placeholder="Enter Retailer Contact Number" class="form-control" required="required" value="<?php echo $contact_numbar; ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Contact Email<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <input type="text" name="email" placeholder="Enter Retailer Contact Email" class="form-control" required="required" value="<?php echo $email; ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Select Distributers<span style="color:red">*</span> : </label>
                                        <div class="col-sm-9">
                                            <?php
                                            $srNo = 1;
                                            foreach (getActiveRetailerDetailsByStateId($retailer_state_id, $company_id_in) as $retailer) {
                                                if ($retailer->company_id == 1) {
                                                    $class_color = "red";
                                                } else if ($retailer->company_id == 2) {
                                                    $class_color = "green";
                                                } else if ($retailer->company_id == 3) {
                                                    $class_color = "blue";
                                                } else {
                                                    $class_color = "cyan";
                                                }
                                                if ($retailer->state_id == 4) {
                                                    $s_class_color = "green";
                                                } else if ($retailer->state_id == 9) {
                                                    $s_class_color = "red";
                                                } else if ($retailer->state_id == 1) {
                                                    $s_class_color = "pink";
                                                } else if ($retailer->state_id == 9) {
                                                    $s_class_color = "yellow";
                                                } else if ($retailer->state_id == 5) {
                                                    $s_class_color = "blue";
                                                } else {
                                                    $s_class_color = "cyan";
                                                }
                                                ?>
                                                <b class="red"><?php echo $srNo ?>.</b> <input type="checkbox" name="distributer_id[]"<?php
                                                if (in_array($retailer->id, $get_distributer_id_array, TRUE)) {
                                                    echo 'checked="checked"';
                                                } else {
                                                    
                                                }
                                                ?> value="<?php echo $retailer->id; ?>" /> <b>
                                                    <?php echo $retailer->name . "</b> | <b class='" . $s_class_color . "'>" . getStateNameById($retailer->state_id) . "</b> | <b class='" . $class_color . "'>" . getCompanyNameById($retailer->company_id); ?></b><br/>
                                                <?php
                                                $srNo++;
                                            }
                                            ?>
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

