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
                                    <div class="align-right">
                                        <a href="generate_return_po_new.php?menu=392"><button class="btn btn-primary">BACK</button></a>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12">

                                        <div class="row">
                                            <h3 class="header-text">Return - Purchase Goods Order.</h3>
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
                                                                    <th width="15%" align="left">Purchase No</th>
                                                                    <th width="15%" align="left">Purchase Type</th>
                                                                    <th width="15%" align="left">Purchase Date</th>
                                                                    <th width="25%" align="left">Supplier</th>
                                                                    <th width="25%" align="left">Retailer</th>
                                                                    <th width="15%" align="left">Net Amount</th>
                                                                    <th width="25%" align="left">Last Modified</th>
                                                                    <th width="25%" align="left">Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $status = 0;
                                                                $i = 1;
                                                                // print_r($_SESSION);
                                                                $purchaseOrder = getPurchaseOrderListByStatusReturnPo($_SESSION['id']);
                                                                if (count($purchaseOrder) > 0 && !empty($purchaseOrder)) {
                                                                    foreach ($purchaseOrder as $row) {
                                                                        $po_type = $row->po_type;
                                                                        if ($po_type == 1) {
                                                                            $po_type_desc = "Damage Return";
                                                                        } else if ($po_type == 2) {
                                                                            $po_type_desc = "Agriculture Officer";
                                                                        } else if ($po_type == 3) {
                                                                            $po_type_desc = "Demo Given";
                                                                        } else {
                                                                            $po_type_desc = "Return PO";
                                                                        }
                                                                        echo "<tr>"
                                                                        . "<td>" . $i . "</td>"
                                                                        . "<td>" . $row->po_no . "</td>"
                                                                        . "<td>" . $po_type_desc . "</td>"
                                                                        . "<td>" . date('d-M-Y', strtotime($row->po_date)) . "</td>"
                                                                        . "<td>" . $row->supplier_id . "</td>"
                                                                        . "<td>" . getRetailerNameById($row->retailer_id) . "</td>"
                                                                        . "<td>" . $row->grand_total . " <b>-/ Rs.</b></td>"
                                                                        . "<td>" . date("Y-m-d H:i:s", strtotime($row->generate_datetime)) . "</td>"
                                                                        . "<td width='300'><a target='_blank' href='po_return_print.php?menu=11&po_no=" . base64_encode($row->po_no) . "'><button type='button' class='btn-warning' style='cursor:pointer' title='Click to Print'>Print</button></a>";

                                                                        echo "</td>
							</tr>";
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