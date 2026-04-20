<?php

//ankit

echo 'test';
include './includes/session.php';
if ($_SESSION['email'] == 'asif.ali@softage.net') {
    session_destroy();
    print '<script>window.location="login.php";</script>';
    exit;
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
                            <div class="alert alert-info alert-dismissible">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                <strong>Important Info!</strong> Updation of laser code has been revoked by Vahan. For revised process <a style="color: red;" href="HSRP Affixation date updation process-ppt.pptx" target="_blank">click here</a>&nbsp;<img src="./images/blink.gif" width="40px" height="40px" />
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <?php //print_r($_SESSION);  ?>
                                <?php
                                if ($_SESSION['user_group'] == '2') {
                                    if ($_SESSION['user_group_desc'] == 'ZOM') {
                                        ?>
                                        <h3 class="page-header">Total EC Inventory Requisition Pending For Approval - <?php echo getPenInventoryReq($_SESSION['mobile']); ?></h3>
                                        <?php
                                    }
                                }

                                if ($_SESSION['email'] == 'manoj.patel@hsrp.in') {
                                    ?>
                                    <h3 class="page-header">Total EC Inventory Requisition Pending For Approval - <?php echo getPenInventoryReqSbh(); ?></h3>
                                    <?php
                                }
                                ?>

                                <h3 class="page-header">Dealer's Order Pending For Embossing</h3>
                                <table id="table" class="table table-bordered table-hover col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Sr. No</th>
                                            <th width="250">Dealer Name</th>
                                            <th width="250">Dealer Address</th>
                                            <th>Dealer Make</th>
                                            <th>State Name</th>
                                            <th>EC Name</th>
                                            <th>order Datetime</th>
                                            <th>Total Order Count</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $index = 1;
                                        foreach (getAllOrder($_SESSION['user_group'], $_SESSION['state_id']) as $item) {
                                            ?>
                                            <tr>
                                                <td><?php echo $index; ?></td>
                                                <td><?php echo getDealerDataById($item->dealer_id)->dealer_name; ?></td>
                                                <td><?php echo getDealerDataById($item->dealer_id)->address; ?></td>
                                                <td><?php echo getDealerDataById($item->dealer_id)->make; ?></td>
                                                <td><?php echo getStatenameByid($item->state_id); ?></td>
                                                <td><?php echo getEmbosingCenterDataById($item->ec_id)->name; ?></td>
                                                <td><?php echo date("d M Y H:i", strtotime($item->added_datetime)); ?></td>
                                                <td><?php echo $item->order_count; ?></td>
                                            </tr>
                                            <?php
                                            $index++;
                                        }
                                        ?>	
                                    </tbody>
                                </table>

                                <h3 class="page-header">Maruti Dealer's Order Pending For Approval</h3>
                                <table id="table" class="table table-bordered table-hover col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Sr. No</th>
                                            <th width="250">Dealer Name</th>
                                            <th width="250">Dealer Address</th>
                                            <th>Dealer Make</th>
                                            <th>State Name</th>
                                            <th>EC Name</th>
                                            <th>OrderDatetime</th>
                                            <th>Slip Upload</th>
                                            <th>Total Order Count</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $index1 = 1;

                                        foreach (getAllOrderMaruti($_SESSION['user_group'], $_SESSION['state_id']) as $item1) {
                                            ?>
                                            <tr>

                                                <td><?php echo $index1; ?></td>
                                                <td><?php echo getDealerDataById($item1->dealer_id)->dealer_name; ?></td>
                                                <td><?php echo getDealerDataById($item1->dealer_id)->address; ?></td>
                                                <td><?php echo getDealerDataById($item1->dealer_id)->make; ?></td>
                                                <td><?php echo getStatenameByid($item1->state_id); ?></td>
                                                <td><?php echo getEmbosingCenterDataById($item1->ec_id)->name; ?></td>

                                                <td><?php echo date("d M Y H:i", strtotime($item1->added_datetime)); ?></td>
                                                <td><?php echo date("d M Y H:i", strtotime($item1->image_upload_datetime)); ?></td>
                                                <td><?php echo $item1->order_count; ?></td>



                                            </tr>
                                            <?php
                                            $index1++;
                                        }
                                        ?>	
                                    </tbody>
                                </table>

                                <h3 class="page-header">Old Vehicles Order Pending For Embossing</h3>
                                <table id="table" class="table table-bordered table-hover col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Sr. No</th>
                                            <th width="250">Dealer Name</th>
                                            <th width="250">Dealer Address</th>
                                            <th>Dealer Make</th>
                                            <th>State Name</th>
                                            <th>EC Name</th>
                                            <th>OrderDatetime</th>

                                            <th>Total Order Count</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $index1 = 1;

                                        foreach (getAllOrderOld($_SESSION['user_group'], $_SESSION['state_id']) as $item2) {
                                            ?>
                                            <tr>

                                                <td><?php echo $index1; ?></td>
                                                <td><?php echo getDealerDataById($item2->dealer_id)->dealer_name; ?></td>
                                                <td><?php echo getDealerDataById($item2->dealer_id)->address; ?></td>
                                                <td><?php echo getDealerDataById($item2->dealer_id)->make; ?></td>
                                                <td><?php echo getStatenameByid($item2->state_id); ?></td>
                                                <td><?php echo getEmbosingCenterDataById($item2->ec_id)->name; ?></td>

                                                <td><?php echo date("d M Y H:i", strtotime($item2->added_datetime)); ?></td>

                                                <td><?php echo $item2->order_count; ?></td>



                                            </tr>
                                            <?php
                                            $index1++;
                                        }
                                        ?>	
                                    </tbody>
                                </table>





                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php require_once 'includes/footer.php'; ?>
        </div>
    </body>

</html> 