<?php
require_once 'includes/session.php';
require_once 'includes/common_function.php';
$retailer_id = '';
if (isset($_POST['Retailer_id'])) {
    $retailer_id = $_POST['Retailer_id'];
}
if (isset($_POST['show'])) {
    $date_1 = date("Y-m-d", strtotime($_POST['date_1']));
} else {
    $date_1 = date("Y-m-d");
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
                                <?php
                                if (isset($_GET['error'])) {
                                    switch ($_GET['error']) {
                                        case 1:
                                            $msg = "Item can not be insert.";
                                            break;
                                        default:
                                            $msg = "Something Wrong.";
                                            break;
                                    }
                                    ?>
                                    <div class="alert alert-block alert-danger">
                                        <button type="button" class="close" data-dismiss="alert">
                                            <i class="ace-icon fa fa-times"></i>
                                        </button>

                                        <i class="ace-icon fa fa-check red form-error-msg"></i>
                                        <?php echo $msg; ?>
                                    </div>
                                <?php } ?>
                                <?php if (isset($_GET['success'])) { ?>
                                    <div class="alert alert-block alert-success">
                                        <button type="button" class="close" data-dismiss="alert">
                                            <i class="ace-icon fa fa-times"></i>
                                        </button>

                                        <i class="ace-icon fa fa-check green form-error-msg"></i>
                                        <?php echo "Product Updated Successfully"; ?>
                                    </div>
                                <?php } ?>
                                <hr/>
                                <a href="sales_management_approval.php?menu=437"><button class="btn btn-danger">Sales Data</button></a>
                                <a href="price_update_management_approval.php?menu=437"><button class="btn btn-warm">Price Update</button></a>
                                <a href="expense_management_approval.php?menu=437"><button class="btn btn-primary">Expense</button></a>
                                <a href="purchase_management_approval.php?menu=437"><button class="btn btn-success">Purchase</button></a>
                                <hr/>
                                <div class="page-header">
                                    <div class="widget-box">
                                        <div class="widget-header">
                                            <h4 class="widget-title">Purchase - Management Approval.</h4>
                                        </div>
                                        <div class="widget-body">
                                            <div class="widget-main">
                                                <form class="form-inline center" action="" method="POST">
                                                    <div class="row">
                                                        <div class="form-group col-xs-6">
                                                            <input class="form-control date-picker" id="id-" name="date_1" type="text" value="<?php
                                                            if (isset($_POST['date_1'])) {
                                                                echo $_POST['date_1'];
                                                            } else {
                                                                echo date('d-m-Y');
                                                            }
                                                            ?>" data-date-format="dd-mm-yyyy" />
                                                            <button class="btn btn-info" type="submit" name="show" value="show">
                                                                <i class="ace-icon fa fa-check bigger-110"></i>
                                                                Show
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-body">
                                    <?php if (isset($_POST['show'])) { ?>
                                                                                                                                                                                                                                                <!--                                        <h5 class="red">Total sale amount between <?php // echo $date_1;                                                              ?> and <?php // echo $date_2;                                                              ?> is : <b class="blue"><?php // echo IND_money_format(getProductSalesTotalAmtByRetailerTempTable($date_1, $date_2, $retailer_id, $company_id_in));                                                              ?> Rs.</b></h5>-->
                                    <?php } ?>
                                    <div class="row clearfix">
                                        <div class="pull-right tableTools-container"></div>
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
                                                $i = 1;
                                                $purchaseOrder = getPurchaseOrderListByStatusMan($date_1, $company_id_in);
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
                                                    . "<td>" . $row->grand_total . " <b>-/ Rs.</b></td>
                                                            <td width='300'>";
                                                    echo "<a target='_blank' href='inventory_purchase_report.php?menu=11&po_no=" . base64_encode($row->po_no) . "'><button type='button' class='btn-warning' style='cursor:pointer' title='Click to Print'>Print</button></a>";
                                                    ?>
                                                    <?php
                                                    echo "</td>
							</tr>";
                                                    $i++;
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div><!-- /.row -->
                    </div><!-- /.page-content -->
                </div>
            </div><!-- /.main-content -->
            <!--END MAIN WRAPPER -->
            <?php require_once 'includes/footer.php'; ?>    
            <script type="text/javascript">
                $('#Retailer_id').multiselect({

                    columns: 1, // how many columns should be use to show options
                    search: true, // include option search box
                    texts: {
                        placeholder: '-- Select Distributer --', // text to use in dummy input
                    },
                    selectAll: true
                });
                $('#item_code').multiselect({

                    columns: 1, // how many columns should be use to show options
                    search: true, // include option search box
                    texts: {
                        placeholder: '-- Select Item --', // text to use in dummy input
                    },
                    selectAll: true
                });
            </script>
        </div>
    </body>
</html>

