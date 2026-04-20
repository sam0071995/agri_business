<?php
error_reporting(0);
require_once 'includes/session.php';
require_once 'includes/common_function.php';
if (ltrim(date('m')) > 3) {
    $cd = date('y');
    $dd = $cd + 1;
} else {
    $dd = date('y');
    $cd = $dd - 1;
}
$fin_year = $cd . '' . $dd;

$bdm_id = $_SESSION['id'];
?>
<!DOCTYPE html>
<html lang="en">
    <?php require_once 'includes/header.php'; ?>

    <body class="no-skin">
        <style>
            .marg_tp_one {
                margin-top: 10px;
            }
        </style>
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
                                <h3 class="header">
                                    Report for po generate item.
                                </h3>


                                <div class="row">
                                    <div class="col-xs-12">
                                        <form class="form-horizontal" id="searchform" name="myForm" role="form" action="" method="POST" enctype="multipart/form-data">

                                            <div class="form-group" id="">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Select From Date : </label>
                                                <div class="col-sm-4">
                                                    <input type="date" name="formdate" class="form-control" required="" value="<?php
                                                    if (isset($_POST['formdate'])) {
                                                        echo $_POST['formdate'];
                                                    } else {
                                                        echo date('Y-m-d');
                                                    }
                                                    ?>" />
                                                </div>
                                            </div>
                                            <div class="form-group" id="">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Select To Date : </label>
                                                <div class="col-sm-4">
                                                    <input type="date" name="todate" class="form-control" required="" value="<?php
                                                    if (isset($_POST['todate'])) {
                                                        echo $_POST['todate'];
                                                    } else {
                                                        echo date('Y-m-d');
                                                    }
                                                    ?>" />
                                                </div>
                                            </div>

                                            <div class="form-group" id="">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Select Retailer : </label>
                                                <div class="col-sm-4">
                                                    <select class="form-control chosen-select" name="retailer_id" id="retailer_id" required="">
                                                        <option value="0">-- Select Retailer --</option>
                                                        <option value="all">--All --</option>
                                                        <?php
                                                        $get_retailer = getActiveRetailerDetails($_SESSION['company_id']);

                                                        foreach ($get_retailer as $retailer) {
                                                            ?>
                                                            <option value="<?php echo $retailer->id; ?>"><?php echo $retailer->name; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>




                                            <div class="clearfix form-actions">
                                                <div class="col-md-offset-3 col-md-9">
                                                    <button type="submit" name="submit" class="btn btn-info">
                                                        <i class="ace-icon fa fa-check bigger-110"></i>
                                                        Show
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div><!-- /.row -->

                                <?php
//                                print_r($_SESSION);
                                if (isset($_POST['submit'])) {
                                    $formdate = date('Y-m-d', strtotime($_POST['formdate']));
                                    $todate = date('Y-m-d', strtotime($_POST['todate']));
                                    $retailer_id = $_POST['retailer_id'];
                                    $company_id = $_SESSION['company_id'];
                                    ?>
                                    <div class="col-sm-12 col-md-12 col-lg-12">
                                        <div class="row clearfix">
                                            <div class="pull-right tableTools-container"></div>
                                        </div>
                                        <table id="dynamic-table" class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>SrNo</th>
                                                    <th>RetailerName</th>
                                                    <th>ItemName</th>
                                                    <th>ItemBrand</th>
                                                    <th>ItemCategory</th>
                                                    <th>CurrentStock</th>
                                                    <th>Req Qty</th>
                                                    <th>BDM Name</th>
                                                    <th>BDM Approve Qty</th>
                                                    <th>BDM Approve Date</th>
                                                    <th>TM Approve Qty</th>
                                                    <th>TM Approve Date</th>
                                                    <th>AddedDate</th>
                                                    <th>LiquidationDays</th>
                                                    <th>PO No</th>
                                                    <th>Remarks</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if ($retailer_id == 'all') {
                                                    $where = "date(added_time) between '$formdate' and '$todate' and company_id = '$company_id'";
                                                } else {
                                                    $where = "date(added_time) between '$formdate' and '$todate' and retailer_id = '$retailer_id'";
                                                }

                                                $getdata = getRetailerPoItemListByretailerIdForReport($where);
                                                $indx = 1;
                                                foreach ($getdata as $raws) {
                                                    $main_category_id = getItemParentCategoryIdItemcode($raws->item_code);
                                                    if ($raws->status == '1') {
                                                        $statuss = " Approval Pending From BDM";
                                                    } else if ($raws->status == '2') {
                                                        $statuss = "TM Approval pending..";
                                                        // $statuss = "PO generate pending..";
//                                                        $action = "Not possible to delete";
                                                    }else if ($raws->status == '4') {
                                                        $statuss = "TM Approved and PO generate pending..";
//                                                        $action = "Not possible to delete";
                                                    } else if ($raws->status == '3') {
                                                        $statuss = "PO generated";
                                                        $action = "PO generated";
                                                    }
                                                    ?>
                                                    <tr id="tr_<?php echo $raws->id; ?>">
                                                        <td><?php echo $indx; ?></td>
                                                        <td><?php echo getRetailerNameById($raws->retailer_id); ?></td>
                                                        <td><?php echo $raws->item_desc; ?></td>
                                                        <td><?php echo getproductBrandNameById($raws->item_code); ?></td>
                                                        <td><?php echo getCategoryNameById($main_category_id); ?></td>
                                                        <td><?php echo getRetailerItemCurrentStockById($raws->item_code, $raws->retailer_id); ?></td>
                                                        <td><?php echo $raws->qty; ?></td>
                                                        <td><?php echo getBDMDetailById($raws->bdm_id)->name; ?></td>
                                                        <td><?php echo $raws->bdm_qty; ?></td>
                                                        <td><?php echo (!empty($raws->bdm_approve_date)) ? date('Y-m-d',strtotime($raws->bdm_approve_date)) : ''; ?></td>
                                                        <td><?php echo $raws->tm_qty; ?></td>
                                                        <td><?php echo (!empty($raws->tm_date)) ? date('Y-m-d',strtotime($raws->tm_date)) : ''; ?></td>
                                                        <td><?php echo date('Y-m-d', strtotime($raws->added_time)); ?></td>
                                                        <td><?php echo $raws->Liquidation_Days; ?></td>
                                                        <td><?php echo $raws->po_no; ?></td>
                                                        <td><?php echo $raws->remarks; ?></td>
                                                        <td><?php echo $statuss; ?></td>

                                                    </tr>
                                                    <?php
                                                    $indx++;
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>

                                <?php } ?>


                            </div>
                        </div><!-- /.row -->
                    </div><!-- /.page-content -->
                </div>
            </div><!-- /.main-content -->

            <script type="text/javascript">

            </script>

            <!--END MAIN WRAPPER -->
            <?php require_once 'includes/footer.php'; ?>
        </div>
    </body>

</html>