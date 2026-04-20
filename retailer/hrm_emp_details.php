<?php
require_once 'includes/session.php';
require_once 'includes/dm_hrm.php';
$hrm_obj = new dbHrm();
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
                    <?php //require_once 'includes/page-header.php';  
                    ?>
                    <div class="row">
                        <div class="col-xs-12">

                            <div class="row clearfix">
                                <div class="pull-right tableTools-container"></div>
                            </div>
                            <div>

                                <table id="dynamic-table" class="table table-bordered table-hover">
                                    <thead class="thead-dark">
                                        <tr>

                                            <th>SrNo</th>
                                            <th>EmpCode</th>
                                            <th>EmpName</th>
                                            <th>Address</th>
                                            <th>Pincode</th>
                                            <th>MobileNo</th>
                                            <th>AadharNo</th>
                                            <th>PanNo</th>
                                            <th>AadharImage</th>
                                            <th>PanImage</th>
                                            <th>EmployeeImage</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sr = 1;
                                        foreach ($hrm_obj->getEmpDataByEmpCode($_SESSION['emp_code']) as $row) {
                                        ?>
                                            <form name="<?php echo $row->emp_code; ?>" id="<?php echo  $row->emp_code; ?>" enctype="multipart/form-data">
                                                <tr>
                                                    <td><?php echo $sr; ?></td>
                                                    <td><?php echo $row->emp_code; ?></td>
                                                    <td><?php echo $row->name . ' ' . $row->last_name; ?></td>
                                                    <td><input type="text" value="<?php echo  $row->address1; ?>" name="address" id="<?php echo $row->emp_code . '_address'; ?>" /></td>
                                                    <td><input type="text" value="<?php echo $row->address3; ?>" name="pincode" id="<?php echo $row->emp_code . '_pincode'; ?>" /></td>
                                                    <td><input type="text" value="<?php echo $row->mobile_no; ?>" name="mobile" id="<?php echo $row->emp_code . '_mobile'; ?>" /></td>
                                                    <td><input type="text" value="<?php echo $row->aadhar_no; ?>" name="aadhar_no" id="<?php echo $row->emp_code . '_aadhar'; ?>" /></td>
                                                    <td><input type="text" value="<?php echo $row->pan_no; ?>" name="pan_no" id="<?php echo $row->emp_code . '_pan'; ?>" /></td>
                                                    <td><input type="file" accept=".pdf" value="<?php echo $row->aadhar_image; ?>" name="adarimg" id="<?php echo $row->emp_code . '_adarimg'; ?>" /></td>
                                                    <td><input type="file" accept=".pdf" value="<?php echo $row->pan_image; ?>" name="panimg" id="<?php echo $row->emp_code . '_panimg'; ?>" /></td>
                                                    <td><input type="file" accept=".jpg,.jpeg" value="<?php echo $row->employee_image; ?>" name="empimg" id="<?php echo $row->emp_code . '_empimg'; ?>" /></td>
                                                    <td><input class="btn btn-warning btn-xs" value="Update" type="button" onclick="updateRecord(<?php echo $row->emp_code; ?>)" /></td>
                                                </tr>
                                            </form><?php
                                                    $sr++;
                                                }
                                                    ?>
                                    </tbody>
                                </table>

                            </div>




                        </div>
                    </div><!-- /.row -->
                </div><!-- /.page-content -->
            </div>
        </div><!-- /.main-content -->

        <script type="text/javascript">
            function updateRecord(emp_code) {
                var _adarimg = document.getElementById(emp_code + '_adarimg').files[0];
                var _panimg = document.getElementById(emp_code + '_panimg').files[0];
                var _empimg = document.getElementById(emp_code + '_empimg').files[0];
                var _address = document.getElementById(emp_code + '_address').value;
                var _pincode = document.getElementById(emp_code + '_pincode').value;
                var _mobile = document.getElementById(emp_code + '_mobile').value;
                var _aadhar = document.getElementById(emp_code + '_aadhar').value;
                var _pan = document.getElementById(emp_code + '_pan').value;

                var form_data = new FormData();
                form_data.append("type", 'hrm_emp_data_update');
                form_data.append("_adarimg", _adarimg);
                form_data.append("_panimg", _panimg);
                form_data.append("_empimg", _empimg);
                form_data.append("_address", _address);
                form_data.append("_pincode", _pincode);
                form_data.append("_mobile", _mobile);
                form_data.append("_aadhar", _aadhar);
                form_data.append("_pan", _pan);
                form_data.append("emp_code", emp_code);

                $.ajax({
                    url: 'get_ajax_data.php',
                    method: 'post',
                    data: form_data,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(resu) {
                        // console.log(resu);
                        if (resu == 0) {
                            alert('Data Update Successfully..!');
                            window.location.reload();
                        } else {
                            alert('Data Update Error..!');
                            window.location.reload();
                        }
                    }
                });
            }
        </script>


        <!--END MAIN WRAPPER -->
        <?php require_once 'includes/footer.php'; ?>

    </div>
</body>

</html>