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
                                if (isset($_POST['submit'])) {
                                    $formdate = date('Y-m-d', strtotime($_POST['formdate']));
                                    $todate = date('Y-m-d', strtotime($_POST['todate']));
                                    ?>
                                    <div class="col-sm-12 col-md-12 col-lg-12">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>SrNo</th>
                                                    <th>ItemName</th>
                                                    <th>Qty</th>
                                                    <th>BDMQty</th>
                                                    <th>AddedDate</th>
                                                    <th>LiquidationDays</th>
                                                    <th>Remarks</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $retailrid = $_SESSION['id'];
                                                $getdata = getRetailerPoItemListByretailerIdForReport($retailrid, $formdate, $todate);
                                                $indx = 1;
                                                foreach ($getdata as $raws) {
                                                    if ($raws->status == '1') {
                                                        $statuss = "BDM Approval Pending..";
                                                    } else if ($raws->status == '2') {
                                                        $statuss = "PO generate pending..";
//                                                        $action = "Not possible to delete";
                                                    } else if ($raws->status == '3') {
                                                        $statuss = "PO generated";
                                                        $action = "Not possible to delete";
                                                    }
                                                    ?>
                                                    <tr id="tr_<?php echo $raws->id; ?>">
                                                        <td><?php echo $indx; ?></td>
                                                        <td><?php echo $raws->item_desc; ?></td>
                                                        <td><?php echo $raws->qty; ?></td>
                                                        <td><?php echo $raws->bdm_qty; ?></td>
                                                        <td><?php echo date('Y-m-d', strtotime($raws->added_time)); ?></td>
                                                        <td><?php echo $raws->Liquidation_Days; ?></td>
                                                        <td><?php echo $raws->remarks; ?></td>
                                                        <td><?php echo $statuss; ?></td>
                                                        <td>
                                                            <?php if ($raws->status != '3') { ?>
                                                                <button type="button" class="btn btn-denger btn-xs" onclick="deleteItem('<?php echo $raws->id; ?>');">Delete</button>
                                                                <?php
                                                            } else {
                                                                echo $action;
                                                            }
                                                            ?>
                                                        </td>
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
                function deleteItem(itmid) {
                    $.ajax({
                        url: 'ajax_js.php?menu=1',
                        method: 'post',
                        data: {request_type: 'delete_retailer_po_item_after_confirm', itmid: itmid},
                        success: function (reslt) {
                            if (reslt == 0) {
                                alert('Item delete error..!!');
                                return false;
                            } else {
                                alert('Item delete successfully..!!');
                                document.getElementById('tr_' + itmid).remove();
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