<?php
require_once 'includes/session.php';
require_once 'includes/common_function.php';
$curDate = date('d-m-Y');
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
                                    if ($_GET['error'] == 1) {
                                        $msg = "Enter LID Number";
                                    } elseif ($_GET['error'] == 2) {
                                        $msg = "Enter Valid LID Number";
                                    }
                                    ?>
                                    <div class="alert alert-danger">
                                        <button data-dismiss="alert" class="close">
                                            <i class="ace-icon fa fa-times"></i>
                                        </button>
                                        <i class="ace-icon fa fa-hand-o-right"></i>
                                        <?php echo $msg; ?>
                                    </div>
                                    <?php
                                }
                                ?>
                                <?php // print_r($_SESSION); ?>
                                <div class="widget-box">
                                    <div class="widget-header">
                                        <h4 class="widget-title">EC LID Status</h4>
                                    </div>
                                    <div class="widget-body">
                                        <div class="widget-main">
                                            <form class="form-horizontal center" action="" method="POST">
                                                <div class="row">
                                                    <div class="form-group">
                                                        <label class="col-sm-4 control-label no-padding-right" for="form-field-1-1"> LID No <span style="color:red">*</span> : </label>
                                                        <div class="col-sm-4">
                                                            <input type="text" class="form-control" placeholder="Enter LID Number"  id="form-field-tags" name="lid_no">

                                                        </div>
                                                    </div>
                                                    <div class="clearfix form-actions">
                                                        <div class="col-md-offset-3 col-md-5">
                                                            <button class="btn btn-info" type="submit" name="show" value="show">
                                                                <i class="ace-icon fa fa-check bigger-110"></i>
                                                                SHOW
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                if (isset($_POST['show'])) {
                                    $lid_no = $_POST['lid_no'];
                                    $state_id = $_SESSION['state_id'];
                                    $reg_str = "";
                                    if (empty($lid_no)) {
                                        echo '<script>window.location.replace("find_lid_status.php?error=1&menu=' . $menu . '")</script>';
                                        exit;
                                    }
                                    $LID_Details_regular = getLIDNumberStatusDetails($state_id, $lid_no);
                                    $LID_Details_comp = getLIDNumberStatusDetails_Comp($state_id, $lid_no);

                                    if ($LID_Details_regular) {
                                        $LID_Details = $LID_Details_regular;
                                    }
                                    if ($LID_Details_comp) {
                                        $LID_Details = $LID_Details_comp;
                                    }
                                    // print_r($LID_Details);exit;
                                    if (!$LID_Details) {
                                        echo '<script>window.location.replace("find_lid_status.php?error=2&menu=' . $menu . '")</script>';
                                        exit;
                                    }
                                    ?>
                                    <div class="modal-body no-padding" style="margin-top:30px;">
                                        <!-- <div class="row clearfix">
                                            <div class="pull-right tableTools-container"></div>
                                        </div> -->
                                        <div>
                                            <table id="dynamic-table" class="table table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Sr No</th>
                                                        <th>Location</th>
                                                        <th>LID Number</th>
                                                        <th>SIZE</th>
                                                        <th>Out Date</th>
                                                        <th>Reg no</th>
                                                        <th>Embossed Date</th>
                                                        <th>Embosser Name</th>
                                                        <th>OEM</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $index = 1;
                                                    $date_of_req = $LID_Details->req_date;
                                                    $added_date = date('d-M-Y', strtotime($date_of_req));
                                                    $status = "";
                                                    $status = '';
                                                    if ($LID_Details->out_to == 3) {
                                                        if ($LID_Details->blocked == 0 && $LID_Details->inwd_flg_ec == 0) {
                                                            $status = 'Pending to Inward at Embossing Centre';
                                                        }
                                                        if ($LID_Details->blocked == 1) {
                                                            $status = 'Embosseed at Embossing Centre';
                                                        }
                                                        if ($LID_Details->blocked == 5 && $LID_Details->embosser_id == '') {
                                                            $status = 'Blank at superwiser Level.';
                                                        }
                                                        if ($LID_Details->blocked == 0 && $LID_Details->embosser_id != '') {
                                                            $status = 'Blank at Emboser Level';
                                                        }
                                                        if ($LID_Details->rej_status == 1) {
                                                            $status = 'Rejected By Respective Embossing Center <br/> (Reason : ' . $LID_Details->reason . ' )';
                                                        }
                                                    } else {
                                                        $status = "LID Belongs to Warehouse";
                                                    }
                                                    $yamaha_stock = $LID_Details->yamaha_stock;
                                                    if ($yamaha_stock == 0) {
                                                        $oem_flag = "Other";
                                                    } else {
                                                        $oem_flag = "Yamaha";
                                                    }
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $index; ?></td>
                                                        <td><b><?php
                                                                if (empty($LID_Details->ec_id)) {
                                                                    echo "WH " . getWHCentreNameByecId($LID_Details->warehouse_id);
                                                                } else {
                                                                    echo "EC " . getECCentreNameByecId($LID_Details->ec_id);
                                                                }
                                                                ?></b></td>
                                                        <td><?php echo $LID_Details->lid_no; ?></td>
                                                        <td><?php echo getItemByItemCode($LID_Details->item_code)->item_desc; ?></td>
                                                        <td><?php echo date("d M Y", strtotime($LID_Details->out_date_factory)); ?></td>
                                                        <td><?php echo $LID_Details->reg_no; ?></td>
                                                        <?php if (!empty($LID_Details->emboss_date)) { ?>
                                                            <td><?php echo date("d M Y", strtotime($LID_Details->emboss_date)); ?></td>
                                                        <?php } else { ?>
                                                            <td></td>
                                                        <?php } ?>
                                                        <td><?php echo getEmbosserNameByid($LID_Details->embosser_id); ?></td>
                                                        <td><?php echo $oem_flag; ?></td>
                                                        <td><?php echo $status; ?></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>
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