<?php
require_once 'includes/session.php';
require_once 'includes/common_function.php';
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
                                <a href="add_customer.php?menu=414" class="float-sm-left" target="_blank"><button class="btn btn-primary float-sm-left">New Customer</button></a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <?php
                                if (isset($_GET['error'])) {
                                    switch ($_GET['error']) {
                                        case 1:
                                            $msg = "Customer Added Error.";
                                            break;
                                        case 2:
                                            $msg = "Customer Edit Erorr.";
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
                                <?php
                                if (isset($_GET['success'])) {

                                    if ($_GET['success'] == '1') {
                                        $msg = "Customer Added Successfully";
                                    } else if ($_GET['success'] == '2') {
                                        $msg = "Customer Update Successfully";
                                    }
                                    ?>
                                    <div class="alert alert-block alert-success">
                                        <button type="button" class="close" data-dismiss="alert">
                                            <i class="ace-icon fa fa-times"></i>
                                        </button>

                                        <i class="ace-icon fa fa-check green form-error-msg"></i>
                                        <?php echo $msg; ?>
                                    </div>
                                <?php } ?>
                                <h3 class="page-header">Customer Details.</h3>
                                <div class="modal-body">
                                    <div class="row clearfix">
                                        <div class="pull-right tableTools-container"></div>
                                    </div>

                                    <div>
                                        <table id="dynamic-table" class="table table-bordered table-hover">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th>SrNo</th>
                                                    <th>Distributer</th>
                                                    <th>CustomerId</th>
                                                    <th>CustomerName</th>
                                                    <th>FatherName</th>
                                                    <th>DateOfBirth</th>
                                                    <th>AadharNo</th>
                                                    <th>Education</th>
                                                    <th>Children</th>
                                                    <th>MobileNo</th>
                                                    <th>Village</th>
                                                    <th>TotalLand</th>
                                                    <th>TotalCow</th>
                                                    <th>TotalBuffalow</th>
                                                    <th>CropsGrow</th>
                                                    <th>Insurance</th>
                                                    <th>LoanRequired</th>
                                                    <th>Status</th>   
                                                    <th>Date</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $index = 1;
                                                $customer_data = getCustomerDetailsByCompanyId($_SESSION['company_id']);
                                                foreach ($customer_data as $cusdata) {
                                                    if ($cusdata->status == 1) {
                                                        $status = '<span class="badge badge-success">Active</span>';
                                                    } else {
                                                        $status = '<span class="badge badge-danger">In-Active</span>';
                                                    }
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $index; ?></td>
                                                        <td ><?php echo getRetailerById($cusdata->retailer_id)->name; ?></td>
                                                        <td ><?php echo $cusdata->uniq_no; ?></td>
                                                        <td ><?php echo $cusdata->cus_name; ?></td>
                                                        <td ><?php echo $cusdata->father_name; ?></td>
                                                        <td ><?php echo $cusdata->date_of_birth; ?></td>
                                                        <td ><?php echo $cusdata->aadhar_no; ?></td>
                                                        <td ><?php echo $cusdata->education; ?></td>
                                                        <td ><?php echo $cusdata->children; ?></td>
                                                        <td ><?php echo $cusdata->cus_mobile; ?></td>
                                                        <td ><?php echo $cusdata->cus_village; ?></td>
                                                        <td ><?php echo $cusdata->total_land; ?></td>
                                                        <td ><?php echo $cusdata->total_cow; ?></td>
                                                        <td ><?php echo $cusdata->total_buffalow; ?></td>
                                                        <td ><?php echo $cusdata->crops_grow; ?></td>
                                                        <td ><?php echo $cusdata->insurance; ?></td>
                                                        <td ><?php echo $cusdata->loan_req; ?></td>
                                                        <td ><?php echo $status; ?></td>
                                                        <td ><?php echo date('Y-m-d', strtotime($cusdata->add_datetime)); ?></td>
                                                        <td>
                                                            <a href="edit_customer.php?menu=414&cus_id=<?php echo base64_encode($cusdata->id); ?>"><button class="btn btn-primary"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></a>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                    $index++;
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
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
