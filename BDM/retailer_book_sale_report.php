<?php
require_once 'includes/session.php';
require_once 'includes/common_function.php';
$post_Retailer_id = "ALL";
if (isset($_POST['Retailer_id'])) {
    $post_Retailer_id = $_POST['Retailer_id'];
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
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="page-header">
                                            <div class="widget-box">
                                                <div class="widget-header">
                                                    <h3 class="header-text">Book Sale Report.</h3>
                                                </div>
                                                <div class="widget-body">
                                                    <div class="widget-main">
                                                        <form class="form-inline center" action="" method="POST">
                                                            <div class="row">
                                                                <div class="form-group">
                                                                    <div class="col-xs-14">
                                                                        <b>From Date :</b>
                                                                        <div class="input-group">
                                                                            <input class="form-control date-picker" id="id-" name="date_1" type="text" value="<?php
                                                                            if (isset($_POST['date_1'])) {
                                                                                echo $_POST['date_1'];
                                                                            } else {
                                                                                echo date('d-m-Y');
                                                                            }
                                                                            ?>" data-date-format="dd-mm-yyyy" />
                                                                            <span class="input-group-addon">
                                                                                <i class="fa fa-calendar bigger-110"></i>
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="col-xs-14">
                                                                        <b>To Date :</b>
                                                                        <div class="input-group">
                                                                            <input class="form-control date-picker" id="" name="date_2"  type="text" value="<?php
                                                                            if (isset($_POST['date_2'])) {
                                                                                echo $_POST['date_2'];
                                                                            } else {
                                                                                echo date('d-m-Y');
                                                                            }
                                                                            ?>" data-date-format="dd-mm-yyyy" />
                                                                            <span class="input-group-addon">
                                                                                <i class="fa fa-calendar bigger-110"></i>
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="col-xs-14">
                                                                        <b>Select Retailer :</b>
                                                                        <div class="input-group">
                                                                            <select class="form-control col-xs-3" name="Retailer_id" id="Retailer_id" required="required">
                                                                                <option value="ALL">All Retailers</option>
                                                                                <?php foreach (getActiveRetailerByBdmId($bdm_detail->retailer_id) as $active_sellers) { ?>
                                                                                    <option value="<?php echo $active_sellers->id; ?>" <?php
                                                                                    if ($post_Retailer_id == $active_sellers->id) {
                                                                                        echo 'selected="selected"';
                                                                                    }
                                                                                    ?> <?php
                                                                                    ?>><?php echo $active_sellers->name; ?><?php
                                                                                                if ($active_sellers->status == 0) {
                                                                                                    echo '<b class="red"> [Clossed]</b>';
                                                                                                }
                                                                                                ?></option>
                                                                                <?php } ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="clearfix form-actions">
                                                                    <div class="col-md-offset-3 col-md-5">
                                                                        <button class="btn btn-info" type="submit" name="show" value="show">
                                                                            <i class="ace-icon fa fa-check bigger-110"></i>
                                                                            Show
                                                                        </button>

                                                                        &nbsp; &nbsp; &nbsp;
                                                                        <button class="btn" type="reset">
                                                                            <i class="ace-icon fa fa-undo bigger-110"></i>
                                                                            Reset
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="row">
                                                    <div class="modal-body">
                                                        <div class="row clearfix">
                                                            <div class="pull-right tableTools-container"></div>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <table id="dynamic-table" class="table table-bordered table-hover">
                                                            <thead class="thead-dark">
                                                                <tr>
                                                                    <th width="8%" align="left">#</th>
                                                                    <th width="15%" align="left">Retailer</th>
                                                                    <th width="15%" align="left">Purchase Date</th>
                                                                    <th width="15%" align="left">Purchase No</th>
                                                                    <th width="15%" align="left">ItemCount</th>
                                                                    <th width="15%" align="left">Amount</th>
                                                                    <!--<th width="25%" align="left">Action</th>-->
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                if (isset($_POST['show'])) {
                                                                    $status = 0;
                                                                    $i = 1;
                                                                    $date_1 = date("Y-m-d", strtotime($_POST['date_1']));
                                                                    $date_2 = date("Y-m-d", strtotime($_POST['date_2']));
                                                                    $Retailer_id = $_POST['Retailer_id'];
                                                                    if ($Retailer_id == "ALL") {
                                                                        $Retailer_id = $bdm_detail->retailer_id;
                                                                    } else {
                                                                        $Retailer_id = $_POST['Retailer_id'];
                                                                    }
                                                                    $purchaseOrder = getBookSaleByBdmId($date_1, $date_2, $Retailer_id);
                                                                    foreach ($purchaseOrder as $row) {
                                                                        ?>
                                                                        <tr>
                                                                            <td><?php echo $i; ?></td>
                                                                            <td><?php echo getRetailerDataById($row->retailer_id)->name; ?></td>
                                                                            <td><?php echo $row->added_date; ?></td>
                                                                            <td><?php echo $row->po_no; ?></td>
                                                                            <td><?php echo $row->total_count; ?></td>
                                                                            <td><?php echo $row->total_price; ?></td>
                                                                            <!--<td><button class="btn btn-success btn-xs">Print</button> </td>-->
                                                                        </tr>
                                                                        <?php
                                                                        $i++;
                                                                    }
                                                                }
                                                                ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div><!-- /.row -->
                                    </div>
                                </div><!-- /.row -->
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