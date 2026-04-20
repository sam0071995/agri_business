<?php
require_once 'includes/session.php';
require_once 'includes/common_function.php';
$get_retailer_id = 0;
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
                                        <?php // print_r($company_id_in); ?>
                                        <div class="row">
                                            <h3 class="header">Return - Purchase Goods Order Report.</h3>

                                            <form class="form-horizontal" id="searchform" name="myForm"  role="form" action="" method="POST" enctype="multipart/form-data">
                                                <div class="row" >
                                                    <div class="form-group" id="c_n_password_c">
                                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Distributer<span style="color:red">*</span> : </label>
                                                        <div class="col-sm-5">
                                                            <select class="form-field-select-2 form-control" multiple name="Retailer_id[]" id="Retailer_id" required="required">

                                                                <?php foreach (getActiveRetailerDetails($company_id_in) as $active_sellers) { ?>
                                                                    <option value="<?php echo $active_sellers->id; ?>" <?php
                                                                    if ($active_sellers->id == $get_retailer_id) {
                                                                        echo "selected='selected'";
                                                                    }
                                                                    ?>><?php echo $active_sellers->name; ?></option>
                                                                        <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>


                                                    <div class="form-group">
                                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> From PO Date<span style="color:red">*</span> : </label>
                                                        <div class="col-sm-5">
                                                            <input class="form-control date-picker" id="id-" name="date_1" type="text" value="<?php
                                                            if (isset($_POST['date_1'])) {
                                                                echo $_POST['date_1'];
                                                            } else {
                                                                echo date('d-m-Y');
                                                            }
                                                            ?>" data-date-format="dd-mm-yyyy" />
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> To PO Date<span style="color:red">*</span> : </label>
                                                        <div class="col-sm-5">
                                                            <input class="form-control date-picker" id="id-" name="date_2" type="text" value="<?php
                                                            if (isset($_POST['date_2'])) {
                                                                echo $_POST['date_2'];
                                                            } else {
                                                                echo date('d-m-Y');
                                                            }
                                                            ?>" data-date-format="dd-mm-yyyy" />
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="clearfix form-actions">
                                                    <div class="col-md-offset-3 col-md-9">
                                                        <button type="submit" name="checkprice" class="btn btn-info" >
                                                            <i class="ace-icon fa fa-search bigger-110"></i>
                                                            Show
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>

                                            <?php
                                            if (isset($_POST['checkprice'])) {

                                                $retailer_id = $_POST['Retailer_id'];
                                                $retailer_in = implode(",", $retailer_id);
                                                ?>
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
                                                                        <th align="left">SrNo</th>
                                                                        <th align="left">PO Type</th>
                                                                        <th align="left">PurchaseNo</th>
                                                                        <th align="left">PurchaseDate</th>
                                                                        <th align="left">Supplier</th>
                                                                        <th align="left">Retailer</th>
                                                                        <th align="left">ItemName</th>
                                                                        <th align="left">ItemQty</th>
                                                                        <th align="left">BatchNo</th>
                                                                        <th align="left">ItemRate</th>
                                                                        <th align="left">NetAmount</th>
                                                                        <th align="left">BatchQty</th>
                                                                        <th align="left">Status</th>
                                                                        <th align="left">Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $status = 0;
                                                                    $i = 1;

                                                                    $date_1 = date("Y-m-d", strtotime($_POST['date_1']));
                                                                    $date_2 = date("Y-m-d", strtotime($_POST['date_2']));

                                                                    $purchaseOrder = getPurchaseOrderListByStatusReturnPoDate($retailer_in, $date_1, $date_2);
                                                                    if (count($purchaseOrder) > 0 && !empty($purchaseOrder)) {
                                                                        $oldpo = "";
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

                                                                            $po_status = $row->status;
                                                                            if ($po_status == 0) {
                                                                                $po_status_desc = "Active";
                                                                            } else if ($po_status == 1) {
                                                                                $po_status_desc = "Active";
                                                                            } else if ($po_status == 2) {
                                                                                $po_status_desc = "Active";
                                                                            } else if ($po_status == 3) {
                                                                                $po_status_desc = "Active";
                                                                            } else {
                                                                                $po_status_desc = "Rejected";
                                                                            }
                                                                            echo "<tr>"
                                                                            . "<td>" . $i . "</td>"
                                                                            . "<td>" . $po_type_desc . "</td>"
                                                                            . "<td>" . $row->po_no . "</td>"
                                                                            . "<td>" . date('d-M-Y', strtotime($row->po_date)) . "</td>"
                                                                            . "<td>" . $row->supplier_id . "</td>"
                                                                            . "<td>" . getRetailerNameById($row->retailer_id) . "</td>"
                                                                            . "<td>" . getItemNameByItemCode($row->item_id) . "</td>"
                                                                            . "<td>" . $row->qty . "</td>"
                                                                            . "<td>" . $row->batch_no . "</td>"
                                                                            . "<td>" . $row->rate . " <b>-/ Rs.</b></td>"
                                                                            . "<td>" . $row->amount . " <b>-/ Rs.</b></td>"
                                                                            . "<td>" . getToalReturnPOBatchBlockedQty($row->retailer_id, $row->batch_no, $row->item_id, $row->po_no) . "</td>"
                                                                            . "<td>" . $po_status_desc . "</td>"
                                                                            . "<td width='300'>";
                                                                            if ($oldpo != $row->po_no) {
                                                                                echo "<a target='_blank' href='po_return_print.php?menu=11&po_no=" . base64_encode($row->po_no) . "'><button type='button' class='btn-warning' style='cursor:pointer' title='Click to Print'>Print</button>"
                                                                                . "</a>";
                                                                                echo ' | ';
                                                                                echo "<a target='_blank' href='delete_return_po.php?menu=415&po_no=" . base64_encode($row->po_no) . "'><button type='button' class='btn-danger' style='cursor:pointer' title='Click to Print'>Delete</button>"
                                                                                . "</a>";
                                                                            }
                                                                            echo "</td>
                                                                            </tr>";
                                                                            $i++;
                                                                            $oldpo = $row->po_no;
                                                                        }
                                                                    }
                                                                    ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div><!-- /.row -->
                                        <?php } ?>
                                    </div>
                                </div><!-- /.row -->
                            </div>
                        </div><!-- /.row -->
                    </div><!-- /.page-content -->
                </div>
            </div><!-- /.main-content -->


            <script type="text/javascript">
                $('#Retailer_id').multiselect({

                    columns: 1, // how many columns should be use to show options
                    search: true, // include option search box
                    texts: {
                        placeholder: '-- Select Distributer --', // text to use in dummy input
                    },
                    selectAll: true
                });
            </script>


            <!--END MAIN WRAPPER -->
            <?php require_once 'includes/footer.php'; ?>

        </div>
    </body>

</html>