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
                                        <a href="generate_new_po.php?menu=11"><button class="btn btn-primary">New Purchase</button></a>
                                        <a href="purchase_order_clossed.php?menu=11"><button class="btn btn-danger">Closed Order</button></a>
                                    </div>
                                </div>
                                <div class="row">

                                    <h3 class="header-text"> Closed Purchase Goods Order.</h3>
                                    <div class="col-xs-12">
                                        <div class="widget-body">
                                            <div class="widget-main">
                                                <form class="form-inline " action="" method="POST">
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
                                                                <div class="input-group">
                                                                    <input type="submit" class="btn btn-success" value="Filter" name="filter">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
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
                                                                    <th width="15%" align="left">Purchase No</th>
                                                                    <th width="15%" align="left">Order Type</th>
                                                                    <th width="15%" align="left">Purchase Date</th>
                                                                    <th width="25%" align="left">Supplier</th>
                                                                    <th width="25%" align="left">Retailer</th>
                                                                    <th width="25%" align="left">InvoiceNo</th>
                                                                    <th width="25%" align="left">InvoiceDate</th>
                                                                    <th width="15%" align="left">Net Amount</th>
                                                                    <th width="15%" align="left">Remarks</th>
                                                                    <th width="25%" align="left">Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                if (isset($_POST['filter'])) {
                                                                    $date_1 = date("Y-m-d", strtotime($_POST['date_1']));
                                                                    $date_2 = date("Y-m-d", strtotime($_POST['date_2']));
                                                                    $status = 2;
                                                                    $i = 1;
                                                                    $purchaseOrder = getClosedPurchaseOrderListByStatusDates($status, $company_id_in, $date_1, $date_2);
                                                                    if (count($purchaseOrder) > 0 && !empty($purchaseOrder)) {
                                                                        foreach ($purchaseOrder as $row) {
                                                                            if ($row->po_type == 1) {
                                                                                $d_po_type = "Purchase Order";
                                                                            } else if ($row->po_type == 2) {
                                                                                $d_po_type = "Credit Note";
                                                                            } else {
                                                                                $d_po_type = "NA";
                                                                            }
                                                                            $purchase_invoie = getInwardedInoiceNo($row->retailer_id, $row->po_no);
                                                                            $invoiceNo = "";
                                                                            $invoice_date = "";
                                                                            if (isset($purchase_invoie->invoice_date)) {
                                                                                $invoiceNo = $purchase_invoie->bill_no;
                                                                                $invoice_date = date("d M Y", strtotime($purchase_invoie->invoice_date));
                                                                            }
                                                                            echo "<tr>"
                                                                            . "<td>" . $i . "</td>"
                                                                            . "<td>" . $row->po_no . "</td>"
                                                                            . "<td>" . $d_po_type . "</td>"
                                                                            . "<td>" . date('d-M-Y', strtotime($row->po_date)) . "</td>"
                                                                            . "<td>" . $row->supplier_id . "</td>"
                                                                            . "<td>" . getRetailerNameById($row->retailer_id) . "</td>"
                                                                            . "<td>" . $invoiceNo . "</td>"
                                                                            . "<td>" . $invoice_date . "</td>"
                                                                            . "<td>" . $row->grand_total . " <b>-/ Rs.</b></td>"
                                                                            . "<td>" . $row->remarks . " </td> 
                                                            <td width='300'>";
//                                                                            echo "<a href='generate_new_po_edit.php?menu=11&purchase_id=" . base64_encode($row->id) . "'><button type='button' class='btn-success' style='cursor:pointer' title='Click to Edit'>Edit</button></a>";
                                                                            echo "<a target='_blank' href='inventory_purchase_report.php?menu=1&po_no=" . base64_encode($row->po_no) . "'><button type='button' class='btn-warning' style='cursor:pointer' title='Click to Print'>Print</button></a>";
                                                                            ?>

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
