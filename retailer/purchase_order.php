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
                                <div class="row">
                                    <div class="col-xs-12">

                                        <div class="row">
                                            <h3 class="header-text"> Purchase Order.</h3>
                                            <div class="col-xs-12">

                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <form class="form-inline center" action="" method="POST">
                                                            <div class="row">
                                                                <div class="form-group">
                                                                    <div class="col-xs-14">
                                                                        <b>From PO Date :</b>
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
                                                                        <b>To PO Date :</b>
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
                                                                <div class="clearfix form-actions">
                                                                    <div class="col-md-offset-3 col-md-5">
                                                                        <button class="btn btn-info" type="submit" name="show" value="show">
                                                                            <i class="ace-icon fa fa-check bigger-110"></i>
                                                                            Show
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div><!-- /.row -->
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
                                                                    <th width="15%" align="left">Purchase No</th>
                                                                    <th width="15%" align="left">Order Type</th>
                                                                    <th width="15%" align="left">Purchase Date</th>
                                                                    <th width="25%" align="left">Supplier</th>
                                                                    <th width="25%" align="left">Store</th>
                                                                    <th width="15%" align="left">Net Amount</th>
                                                                    <th width="25%" align="left">Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $status = 0;
                                                                $i = 1;

                                                                if (isset($_POST['show'])) {
                                                                    $date_1 = date("Y-m-d", strtotime($_POST['date_1']));
                                                                    $date_2 = date("Y-m-d", strtotime($_POST['date_2']));

                                                                    $purchaseOrder = getPurchaseOrderListByStatusDataByDate($_SESSION['id'], $date_1, $date_2);
                                                                    if (count($purchaseOrder) > 0 && !empty($purchaseOrder)) {
                                                                        foreach ($purchaseOrder as $row) {
                                                                            if ($row->po_type == 1) {
                                                                                $d_po_type = "Purchase Order";
                                                                            } else if ($row->po_type == 2) {
                                                                                $d_po_type = "Credit Note";
                                                                            } else {
                                                                                $d_po_type = "NA";
                                                                            }
                                                                            echo "<tr>"
                                                                            . "<td>" . $i . "</td>"
                                                                            . "<td>" . $row->po_no . "</td>"
                                                                            . "<td>" . $d_po_type . "</td>"
                                                                            . "<td>" . date('d-M-Y', strtotime($row->po_date)) . "</td>"
                                                                            . "<td>" . $row->supplier_id . "</td>"
                                                                            . "<td>" . getRetailerNameById($row->retailer_id) . "</td>"
                                                                            . "<td>" . $row->grand_total . "</b></td>"
                                                                            ?>
                                                                        <td>
                                                                            <?php if ($row->invoice_copy != '0') { ?>
                                                                                <a href="challan_copy/<?php echo $row->invoice_copy; ?>" target="_blank">
                                                                                    View File
                                                                                </a>
                                                                            <?php } else { ?>
                                                                                <a target="_blank" href='upload_po_challan.php?menu=458&po_id=<?php echo base64_encode($row->id); ?>' style='margin-left:5%;' >
                                                                                    <button type='button' class='button btn-success' style='cursor:pointer' title='Upload'>Upload Invoice Copy</button>
                                                                                </a>
                                                                            <?php } ?>
                                                                            <?php
                                                                            echo "</td>
							</tr>";
                                                                            $i++;
                                                                        }
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