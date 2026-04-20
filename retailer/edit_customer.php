<?php
require_once 'includes/session.php';
require_once 'includes/common_function.php';

$customer_details = getCustomerDetailsById(base64_decode($_GET['cus_id']));

if (isset($_POST['submit'])) {
    $customerid = $_POST['cus_id'];
    $customer_details = getCustomerDetailsById($customerid);
    $cus_name = $_POST['cus_name'];
    $cus_mobile = $_POST['cus_mobile'];
    $cus_village = $_POST['cus_village'];
    $cus_ttl_land = $_POST['cus_ttl_land'];
    $cus_crops_grow = $_POST['cus_crops_grow'];
    $cus_insurance = $_POST['cus_insurance'];
    $cus_loan = $_POST['cus_loan'];
    $status = $_POST['status'];


    $cus_father_name = $_POST['cus_father_name'];
    $cus_aadhar_num = $_POST['cus_aadhar_num'];
    $cus_education = $_POST['cus_education'];
    $cus_child = $_POST['cus_child'];
    $cus_total_cow = $_POST['cus_total_cow'];
    $cus_total_buffalow = $_POST['cus_total_buffalow'];
    $cus_birthdate = date('Y-m-d', strtotime($_POST['cus_birthdate']));


    // for history ===========
    $json_arr = array();
    $json_arr['id'] = $customer_details->id;
    $json_arr['cus_name'] = $customer_details->cus_name;
    $json_arr['cus_mobile'] = $customer_details->cus_mobile;
    $json_arr['cus_village'] = $customer_details->cus_village;
    $json_arr['total_land'] = $customer_details->total_land;
    $json_arr['crops_grow'] = $customer_details->crops_grow;
    $json_arr['insurance'] = $customer_details->insurance;
    $json_arr['loan_req'] = $customer_details->loan_req;
    $json_arr['status'] = $customer_details->status;

    $json_arr['father_name'] = $customer_details->father_name;
    $json_arr['date_of_birth'] = $customer_details->date_of_birth;
    $json_arr['aadhar_no'] = $customer_details->aadhar_no;
    $json_arr['education'] = $customer_details->education;
    $json_arr['children'] = $customer_details->children;
    $json_arr['total_cow'] = $customer_details->total_cow;
    $json_arr['total_buffalow'] = $customer_details->total_buffalow;
    $json_arr['update_datetime'] = date('Y-m-d H:i:s');
    $his_arr_json = $customer_details->update_json . "," . json_encode($json_arr);
    // for history ===========

    $data['cus_name'] = $cus_name;
    $data['cus_mobile'] = $cus_mobile;
    $data['cus_village'] = $cus_village;
    $data['total_land'] = $cus_ttl_land;
    $data['crops_grow'] = $cus_crops_grow;
    $data['insurance'] = $cus_insurance;
    $data['loan_req'] = $cus_loan;
    $data['status'] = $status;

    $data['father_name'] = $cus_father_name;
    $data['date_of_birth'] = $cus_birthdate;
    $data['aadhar_no'] = $cus_aadhar_num;
    $data['education'] = $cus_education;
    $data['children'] = $cus_child;
    $data['total_cow'] = $cus_total_cow;
    $data['total_buffalow'] = $cus_total_buffalow;
    $data['add_datetime'] = date("Y-m-d h:i:s");
    $data['update_json'] = $his_arr_json;
    $where = "id = '" . $customerid . "'";
    $update = update('customer_details_tbl', $data, $where);
    if ($update) {
        header("Location:customers.php?success=2&menu=414");
        exit;
    } else {
        header("Location:customers.php?error=2&menu=414");
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
                        <div class="page-header">
                            <div class="row float-sm-left">
                                <a href="customers.php?menu=4" class="float-sm-left"><button class="btn btn-primary float-sm-left">Back</button></a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">

                                <h3 class="page-header">Update Customer Details.</h3>
                                <form class="form-horizontal" id="searchform" name="myForm"  role="form" action="" method="POST" enctype="multipart/form-data">
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Customer Name<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <input type="text" name="cus_name" placeholder="Enter Customer Name" class="input_class form-control" required="required" value="<?php echo $customer_details->cus_name; ?>"/>
                                            <input type="hidden" name="cus_id"  class="input_class form-control"  value="<?php echo $customer_details->id; ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Customer Father Name<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <input type="text" name="cus_father_name" placeholder="Enter Customer Father Name" class="input_class form-control" value="<?php echo $customer_details->father_name; ?>" required="required" />
                                        </div>
                                    </div>
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Customer Aadhar Number<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <input type="text" name="cus_aadhar_num" placeholder="Enter Customer Aadhar Number" class="input_class form-control" value="<?php echo $customer_details->aadhar_no; ?>" required="required" />
                                        </div>
                                    </div>
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Customer Education<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <input type="text" name="cus_education" placeholder="Enter Customer Education" class="input_class form-control" value="<?php echo $customer_details->education; ?>" required="required" />
                                        </div>
                                    </div>
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Customer Children<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <input type="text" name="cus_child" placeholder="Enter Customer Children" class="input_class form-control" value="<?php echo $customer_details->children; ?>" required="required" />
                                        </div>
                                    </div>
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Customer Birthdate<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <input type="date" name="cus_birthdate"  class="input_class form-control" value="<?php echo $customer_details->date_of_birth; ?>" required="required" />
                                        </div>
                                    </div>
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">MobileNo<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <input type="text" name="cus_mobile" placeholder="Enter Mobile Number" class="input_class form-control" required="required" value="<?php echo $customer_details->cus_mobile; ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Village<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <input type="text" name="cus_village" placeholder="Enter Village Name" class="input_class form-control" required="required" value="<?php echo $customer_details->cus_village; ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">TotalLand<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <input type="text" name="cus_ttl_land" placeholder="Enter Total Land" class="input_class form-control" required="required" value="<?php echo $customer_details->total_land; ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">CropsGrow Name<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <input type="text" name="cus_crops_grow" placeholder="Enter Crops Grow By Farmer" class="input_class form-control" required="required" value="<?php echo $customer_details->crops_grow; ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Insurance<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <input type="text" name="cus_insurance" placeholder="Enter Insurance Name" class="input_class form-control" required="required" value="<?php echo $customer_details->insurance; ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">LoanRequired<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <input type="text" name="cus_loan" placeholder="Enter Loan Required " class="input_class form-control" required="required" value="<?php echo $customer_details->loan_req; ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Total Cow<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <input type="text" name="cus_total_cow" placeholder="Enter Total Cow " class="input_class form-control" required="required" value="<?php echo $customer_details->total_cow; ?>" />
                                        </div>
                                    </div>
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Total Buffalow<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <input type="text" name="cus_total_buffalow" placeholder="Enter Total Buffalow " class="input_class form-control" required="required" value="<?php echo $customer_details->total_buffalow; ?>" />
                                        </div>
                                    </div>
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Status<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <select class="form-control" name="status" required="required">
                                                <option value="1" <?php echo ($customer_details->status == '1') ? 'selected' : ''; ?> >Active</option>
                                                <option value="0" <?php echo ($customer_details->status == '0') ? 'selected' : ''; ?>>In-Active</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="clearfix form-actions">
                                        <div class="col-md-offset-3 col-md-9">
                                            <button type="submit" name="submit" class="btn btn-info">
                                                <i class="ace-icon fa fa-check bigger-110"></i>
                                                Save
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
