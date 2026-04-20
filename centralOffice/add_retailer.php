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
$password = '';
$email = '';
$delivery_center = '';
$retailer_state_id = '';
$retailer_company_id = '';
$retailer_bdm_id = '';
$retailer_zone_id = '';
$password = '';
$latitude = '';
$lognitude = '';
$pincode = '';
$btn_name = "Submit";
$explode_zone_id = array();

if (isset($_GET['retailer_id'])) {
    $get_retailer_id = base64_decode($_GET['retailer_id']);
    $productData = getRetailerById($get_retailer_id);
    $retailer_name = $productData->full_name;
    $address = $productData->address;
    $contact_name = $productData->contact_name;
    $contact_numbar = $productData->contact_number;
    $status = $productData->status;
    $email = $productData->email;
    $delivery_center = $productData->delivery_center;
    $password = $productData->password;
    $latitude = $productData->lat;
    $lognitude = $productData->long;
    $retailer_company_id = $productData->company_id;
    $retailer_bdm_id = $productData->bdm_id;
    $pincode = $productData->pincode;
    $retailer_state_id = $productData->state_id;
    $explode_zone_id = array();
    if ($productData->new_zone_id != 0) {
        $explode_zone_id = explode(',', $productData->new_zone_id);
    } else {
        
    }
    $btn_name = "Update";
}

if (isset($_POST['submit'])) {
    $table_name = "retailer_master";
    if (isset($_POST['post_retailer_id'])) {
        $post_retailer_id = base64_decode($_POST['post_retailer_id']);
    }
    $max_inc_no = getMaxSellerIncNo();
    $max_inc_no = $max_inc_no + 1;
    $max_inc_no = sprintf('%04s', $max_inc_no);
    $data['company_id'] = $_POST['company_id'];
    $data['bdm_id'] = $_POST['bdm_id'];

    $post_bdm_id = $_POST['bdm_id'];
    $bdm_detail = getBDMDetailById($post_bdm_id);
    $menu_retailer_id = $bdm_detail->retailer_id;

//    echo $menu_retailer_id;
//    exit;
    $data['name'] = $_POST['retailer_name'];
    $data['full_name'] = $_POST['retailer_name'];
    $data['address'] = $_POST['address'];
    $data['dc_address'] = $_POST['address'];
    $data['contact_name'] = $_POST['contact_name'];
    $data['contact_number'] = $_POST['contact_number'];
    $data['pincode'] = $_POST['pincode'];
    $data['email'] = $_POST['email'];
    $data['delivery_center'] = $_POST['delivery_center'];
    $data['status'] = $_POST['status'];
    $data['state_id'] = $_POST['state_id'];
    $data['zone_id'] = $_POST['state_id'];
    $implode_zone_id = 0;
    if (isset($_POST['zone_id'])) {
        $implode_zone_id = implode(",", $_POST['zone_id']);
        $data['new_zone_id'] = $implode_zone_id;
    }

    $data['user_id'] = $user_id;
    $data['warehouse_id'] = $_POST['state_id'];

    if (!empty($post_retailer_id)) {
        if (isset($_POST['password']) && !empty($_POST['password'])) {
            $data['password'] = trim($_POST['password']);
        }
        $data['updated_date'] = date('Y-m-d h:i:s');
        $where = "id='$post_retailer_id'";
        $retailer = update($table_name, $data, $where);
    } else {
        $data['batch_wise_sale'] = 1;
        $data['password'] = generateRandomString();
        $data['menu'] = "13,2,3,14,15,16,18,12,20,23,24,25,26,28,29,22,38,39,392,393";
        $data['inc_code'] = $max_inc_no;
        $data['retailer_code'] = "AGRO" . $max_inc_no;
        $data['date'] = date('Y-m-d');
        $data['added_date'] = date('Y-m-d h:i:s');
        $retailer = insert($table_name, $data);
    }
    if ($retailer) {
        $dataBDM = array();
        $dataBDM['retailer_id'] = $menu_retailer_id;
        $dataBDM['updated_date'] = date('Y-m-d h:i:s');
        $whereBDM = "id='$post_bdm_id'";
        $BDM = update("bdm_master", $dataBDM, $whereBDM);

        header("Location:retailer_master.php" . $menuURL . "&success=1");
        exit;
    } else {
        header("Location:retailer_master.php" . $menuURL . "&error=1");
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
                                            $msg = "Store/Retailer can not be insert.";
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
                                        <?php echo "Store/Retailer Updated Successfully"; ?>
                                    </div>
                                <?php } ?>
                                <h3 class="page-header">Add New Store/Retailer</h3>
                                <form class="form-horizontal" id="searchform" name="myForm"  role="form" action="" method="POST" enctype="multipart/form-data">
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Select Company<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <?php
                                            if (isset($_GET['retailer_id'])) {
                                                echo "<h4 class='red'>" . getCompanyNameById($retailer_company_id) . "</h4>";
                                                ?>
                                                <input type="hidden"  name="company_id" value="<?php echo $retailer_company_id; ?>" />
                                                <?php
                                            } else {
                                                ?>
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
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Select Zone<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <select class="form-field-select-2 form-control" multiple name="zone_id[]" required="required">
                                                <option value="">--Select Zone--</option>
                                                <?php foreach (getActiveZone() as $ZONE) { ?>
                                                    <option value="<?php echo $ZONE->id; ?>" <?php
                                                    if (in_array($ZONE->id, $explode_zone_id)) {
                                                        echo "selected='selected'";
                                                    }
                                                    ?>><?php echo $ZONE->name; ?></option>
                                                        <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Select BDM<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <select class="form-field-select-2 form-control" name="bdm_id" required="required">
                                                <option value="">--Select BDM--</option>
                                                <?php foreach (getActiveBDM() as $BDM) { ?>
                                                    <option value="<?php echo $BDM->id; ?>" <?php
                                                    if ($BDM->id == $retailer_bdm_id) {
                                                        echo "selected='selected'";
                                                    }
                                                    ?>><?php echo $BDM->name; ?></option>
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
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Store/Retailer Name<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <input type="hidden" name="post_retailer_id" value="<?php echo base64_encode($get_retailer_id); ?>"/>
                                            <input type="text" name="retailer_name" placeholder="Enter Retailer Name" class="form-control" required="required" value="<?php echo $retailer_name; ?>"/>
                                        </div>
                                    </div>

                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Address<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <textarea name="address" placeholder="Enter Retailer Address" class="form-control" required="required" id="editor2"><?php echo $address; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Pincode<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <input type="text" name="pincode" placeholder="Enter Retailer Pincode" class="form-control" required="required" value="<?php echo $pincode; ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Contact Name<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <input type="text" name="contact_name" placeholder="Enter Retailer Contact Name" class="form-control" required="required" value="<?php echo $contact_name; ?>"/>
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
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Delivery Center<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <input type="text" name="delivery_center" placeholder="Enter Delivery Center" class="form-control" required="required" value="<?php echo $delivery_center; ?>"/>
                                        </div>
                                    </div>
                                    <?php if (!empty($password)) { ?>
                                        <div class="form-group" id="c_n_password_c">
                                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Password<span style="color:red">*</span> : </label>
                                            <div class="col-sm-6">
                                                <input type="text" name="password" placeholder="Enter Retailer Password" class="form-control" value="<?php echo $password; ?>"/>
                                            </div>
                                        </div>
                                    <?php } ?>
                                     <div class="form-group" id="c_n_password_c">
                                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Latitude<span style="color:red">*</span> : </label>
                                            <div class="col-sm-6">
                                                <input type="text" name="latitude" placeholder="Enter latitude here" class="form-control" value="<?php echo $latitude; ?>"/>
                                            </div>
                                    </div>
                                     <div class="form-group" id="c_n_password_c">
                                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Longitude<span style="color:red">*</span> : </label>
                                            <div class="col-sm-6">
                                                <input type="text" name="longitude" placeholder="Enter longitude here" class="form-control" value="<?php echo $lognitude; ?>"/>
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

