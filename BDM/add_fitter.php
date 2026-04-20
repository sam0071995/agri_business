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
                        <div class="row">
                            <div class="col-xs-12">
                                <?php
                                $user_status = 1;
                                $btnValue = "ADD";
                                if (isset($_GET['id'])) {
                                    $user_id = $_GET['id'];
                                    $btnValue = "UPDATE";
                                    $userData = getFitterDataByRunnerId($user_id);
                                    $user_status = $userData->status;
                                }
                                ?>

                                <div class="header">
                                    <h3 class="modal-title pt-1 text-uppercase"> <?php echo $btnValue; ?> EC Fitter.</h3>
                                </div>


                                <div class="body">
                                    <form class="form-horizontal" id="searchform" name="myForm"  role="form" action="" method="POST" enctype="multipart/form-data">


                                        <div class="form-group" id="">
                                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Select Ec<span style="color:red">*</span> : </label>
                                            <div class="col-sm-6">
                                                <select class="form-control col-xs-10 col-sm-5" name="ec_id" id="ec_id"    required="">
                                                    <option value="">-- Select Ec --</option>
                                                    <?php foreach (getUpAllECdetails() as $ecData) { ?>
                                                        <option value="<?php echo $ecData->id; ?>" <?php
                                                        if (isset($_GET['id'])) {
                                                            if ($ecData->id == $userData->ec_id) {
                                                                echo "selected";
                                                            }
                                                        }
                                                        ?>><?php echo $ecData->name; ?></option>

                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group" id="">
                                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> EMP Code<span style="color:red">*</span> : </label>
                                            <div class="col-sm-6">
                                                <input type="text" name="emp_code" placeholder="EMP Code" id="emp_code" class="form-control" required="required" value="<?php
                                                if (isset($_GET['id'])) {
                                                    echo $userData->emp_code;
                                                }
                                                ?>" />

                                                <?php if (isset($_GET['id'])) { ?>
                                                    <input type="hidden" id="user_id" name="user_id" value="<?php
                                                    if (isset($_GET['id'])) {
                                                        echo $userData->id;
                                                    }
                                                    ?>">
                                                       <?php } ?>
                                            </div>
                                        </div>
                                        <div class="form-group" id="">
                                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Name<span style="color:red">*</span> : </label>
                                            <div class="col-sm-6">
                                                <input type="text" name="name" placeholder="Fitter Name" id="name" class="form-control" required="required" value="<?php
                                                if (isset($_GET['id'])) {
                                                    echo $userData->name;
                                                }
                                                ?>"  />
                                            </div>
                                        </div>

                                        <div class="form-group" id="">
                                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Contact No<span style="color:red">*</span> : </label>
                                            <div class="col-sm-6">
                                                <input type="text" name="contact_num" placeholder="Fitter Contact Number" id="contact_num" class="form-control" required="required" value="<?php
                                                if (isset($_GET['id'])) {
                                                    echo $userData->contact_no;
                                                }
                                                ?>" maxlength="10" />
                                            </div>
                                        </div>

                                        <div class="form-group" id="">
                                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Contact Email<span style="color:red">*</span> : </label>
                                            <div class="col-sm-6">
                                                <input type="text" name="contact_email" placeholder="Fitter Email" id="contact_email" class="form-control" required="required" value="<?php
                                                if (isset($_GET['id'])) {
                                                    echo $userData->contact_email;
                                                }
                                                ?>" />
                                            </div>
                                        </div>

                                        <div class="form-group" id="">
                                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Status<span style="color:red">*</span> :  </label>
                                            <div class="col-sm-6">
                                                <select class="chosen-select form-control" id="status" name="status"  required="required">
                                                    <option value="1" <?php
                                                    if (isset($_GET['id'])) {
                                                        if ($userData->status == 1) {
                                                            echo 'selected';
                                                        }
                                                    }
                                                    ?> >Active</option>
                                                    <option value="0"  <?php
                                                    if (isset($_GET['id'])) {
                                                        if ($userData->status == 0) {
                                                            echo 'selected';
                                                        }
                                                    }
                                                    ?>>In-Active</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="clearfix form-actions">
                                            <div class="col-md-offset-3 col-md-9">
                                                <button type="submit" name="submit" class="btn btn-info">
                                                    <i class="ace-icon fa fa-check bigger-110"></i>
                                                    <?php echo $btnValue; ?>
                                                </button>
                                                &nbsp; &nbsp; &nbsp;

                                                <!--                                                <a href="add_fitter.php?menu=1" class="btn">
                                                                                                    <i class="ace-icon fa fa-arrow-left bigger-110"></i>
                                                                                                    BACK
                                                                                                </a>-->
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <?php
                                if (isset($_POST['submit'])) {
                                    $ec_idd = $_POST['ec_id'];
                                    $emp_code = $_POST['emp_code'];
                                    $name = $_POST['name'];
                                    $user_status = $_POST['status'];
                                    $contact_num = $_POST['contact_num'];
                                    $contact_email = $_POST['contact_email'];
                                    $date = date('Y-m-d');
                                    $date_time = date('Y-m-d H:i:s');


                                    $ec_data_id = getECDataById($ec_idd);

                                    $data['emp_code'] = $emp_code;
                                    $data['name'] = $name;
                                    $data['contact_no'] = $contact_num;
                                    $data['contact_email'] = $contact_email;
//                                    if (filter_var($contact_email, FILTER_VALIDATE_EMAIL)) {
//                                        $data['contact_email'] = $contact_email;
//                                    } else {
//                                        $emailErr = "<p style='color:red;font-size:16px;'>Invalid email format</p>";
//                                        exit;
//                                    }
                                    $data['ec_id'] = $ec_idd;
                                    $data['wh_id'] = $ec_data_id->warehouse;
                                    $data['state_id'] = $ec_data_id->state;
                                    $data['status'] = $user_status;


                                    $table_name = 'fitter_master';

                                    if (!empty($_POST['user_id'])) {
                                        $user_id = $_POST['user_id'];
                                        $data['update_date'] = date("Y-m-d H:i:s");
                                        $where = "id='$user_id'";
                                        $update = update($table_name, $data, $where);
                                        if ($update) {
                                            print '<script>window.location="add_fitter.php?menu=1&success=2";</script>';
                                            exit;
                                        } else {
                                            print '<script>window.location="add_fitter.php?menu=1&error=2";</script>';
                                            exit;
                                        }
                                    } else {
                                        $data['added_date'] = $date_time;
                                        $insert = insert($table_name, $data);
                                        if ($insert) {
                                            print '<script>window.location="add_fitter.php?menu=1&success=1";</script>';
                                            exit;
                                        } else {
                                            print '<script>window.location="add_fitter.php?menu=1&error=1";</script>';
                                            exit;
                                        }
                                    }
                                }
                                ?>

                                <div class="row">
                                    <div class="row" >
                                        <div class="col-xs-12 col-md-12 col-lg-12" style="margin-top: 20px !important">
                                            <?php
                                            $userAll = getFitterAllData();
                                            $i = 0;
                                            ?>
                                            <table id="data-table" class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Sr No</th>
                                                        <th>Fitter Id</th>
                                                        <th>Ec</th>
                                                        <th>Name</th>
                                                        <th>Contact No</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    foreach ($userAll as $row) {
                                                        if ($row->status == 0) {
                                                            $disable = "disable";
                                                            $status = "<span style='color:#B22222;'>Deactive</span>";
                                                        } else {
                                                            $disable = "";
                                                            $status = "<span style='color:#228B22;'>Active</span>";
                                                        }

                                                        echo '<tr class="' . $disable . '">
							<td>' . ++$i . '</td>
							<td>' . $row->emp_code . '</td>
							<td>' . getECDataById($row->ec_id)->name . '</td>
							<td>' . $row->name . '</td>
							<td>' . $row->contact_no . '</td>
							<td>' . $status . '</td>
							<td>
                                                        <a class="btn btn-primary btn-xs fa fa-pencil" href="add_fitter.php?menu=' . $menu . '&id=' . $row->id . '"></a> </td>
						      </tr>';
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
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
        <script type="text/javascript">

        </script>
    </body>
</html>
