<?php
require_once 'includes/session.php';
require_once 'includes/common_function.php';

if (isset($_GET['po_no'])) {
    $get_po_no = base64_decode($_GET['po_no']);
} else {
    echo 'wrong Request. try again.';
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
                            <div class="col-xs-12">

                                <div class="row">
                                    <div class="col-xs-12">
                                        <?php // print_r($company_id_in); ?>
                                        <div class="row">
                                            <h3 class="header">Return - Purchase Goods Order (<b class="red">Delete</b>).</h3>

                                            <?php
                                            if (1 == 1) {
                                                ?>
                                                <div class="col-xs-12">
                                                    <div class="row">
                                                        <div class="modal-body">
                                                            <div class="row clearfix">
                                                                <div class="pull-right tableTools-container"></div>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <table class="table table-bordered table-hover">
                                                                <thead class="thead-dark">
                                                                    <tr>
                                                                        <th align="left">SrNo</th>
                                                                        <th align="left">PurchaseNo</th>
                                                                        <th align="left">PurchaseDate</th>
                                                                        <th align="left">PO Type</th>
                                                                        <th align="left">Retailer</th>
                                                                        <th align="left">ItemName</th>
                                                                        <th align="left">ItemQty</th>
                                                                        <th align="left">BatchNo</th>
                                                                        <th align="left">ItemRate</th>
                                                                        <th align="left">NetAmount</th>
                                                                        <th align="left">Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $status = 0;
                                                                    $i = 1;
                                                                    $purchaseOrder = getPONOPurchaseOrderListByStatusReturnPo($company_id, $get_po_no);
                                                                    if (count($purchaseOrder) > 0 && !empty($purchaseOrder)) {
                                                                        $oldpo = "";
                                                                        foreach ($purchaseOrder as $row) {
                                                                            $po_type = $row->po_type;
                                                                            if ($po_type == 1) {
                                                                                $po_type_desc = "Damage Return";
                                                                            } else if ($po_type == 2) {
                                                                                $po_type_desc = "Agriculture Officer";
                                                                            } else {
                                                                                $po_type_desc = "Return PO";
                                                                            }
                                                                            
                                                                            echo "<tr>"
                                                                            . "<td>" . $i . "</td>"
                                                                            . "<td>" . $row->po_no . "</td>"
                                                                            . "<td>" . date('d-M-Y', strtotime($row->po_date)) . "</td>"
                                                                            . "<td>" . $po_type_desc . "</td>"
                                                                            . "<td>" . getRetailerNameById($row->retailer_id) . "</td>"
                                                                            . "<td>" . getItemNameByItemCode($row->item_id) . "</td>"
                                                                            . "<td>" . $row->qty . "</td>"
                                                                            . "<td>" . $row->batch_no . "</td>"
                                                                            . "<td>" . $row->rate . " <b>-/ Rs.</b></td>"
                                                                            . "<td>" . $row->amount . " <b>-/ Rs.</b></td>"
                                                                            . "<td width='300'>";
                                                                            if ($row->delet == 0) {
                                                                                ?>
                                                                            <button class="btn btn-danger" onclick="deleteRoes(<?php echo $row->idss; ?>);" id="delete_<?php echo base64_encode("'" . $row->idss . "'"); ?>">delete</button>
                                                                            <?php
                                                                        }else{
                                                                            echo '<b class="red">Deleted</b>';
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
                function deleteRoes(id) {
                    $.ajax({
                        type: "POST",
                        url: "ajax_js.php?menu=1",
                        data: {
                            'request_type': 'delete_return_po',
                            id: id
                        },
                        success: function (data) {
                            if (data == 1) {
                                alert("Successfully deleted");
                                location.reload(true);
                            } else {
                                alert("Error for delete return PO.");
                            }
                        }
                    });
                }
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