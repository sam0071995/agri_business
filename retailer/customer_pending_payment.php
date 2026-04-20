<?php
require_once 'includes/session.php';
require_once 'includes/common_function.php';
$get_bank_id = 0;
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
                                <h3 class="header">Customer Payment - Pending Amount.</h3>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <form class="form-horizontal" id="searchform" name="myForm" role="form" action="" method="POST" enctype="multipart/form-data">
                                            <div class="form-group" id="">
                                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">Invoice Number <span style="color:red">*</span> : </label>
                                                <div class="col-sm-4">
                                                    <input type="text" class="form-control" name="invoice_no" id="invoice_no" required="required">
                                                </div>
                                            </div>

                                            <div class="clearfix form-actions">
                                                <div class="col-md-offset-3 col-md-9">
                                                    <button type="submit" name="submit" class="btn btn-info">
                                                        <i class="ace-icon fa fa-check bigger-110"></i>
                                                        Submit
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div><!-- /.row -->
                                <hr/>


                                <?php
                                if (isset($_POST['update'])) {
                                    $invoice_no = $_POST['invoice_no'];
                                    $transaction_amount = $_POST['transaction_amount'];
                                    $transaction_date = $_POST['transaction_date'];
                                    $transaction_no = $_POST['transaction_no'];
                                    $mode = $_POST['mode'];
                                    if (!empty($invoice_no)) {
                                        $invoice_no = base64_decode($invoice_no);
                                        $orderDetail = getBookSaleOrderByOrderId($invoice_no, $_SESSION['id']);
                                        $pendingAmount = amount($orderDetail->pending_amount - $orderDetail->credit_amount);
                                        if ($pendingAmount >= 0) {
                                            $pendingAmount = amount($orderDetail->pending_amount - $orderDetail->credit_amount - $transaction_amount);
                                            if ($pendingAmount >= 0) {
                                                $updateMaster = array();
                                                $updateMaster['credit_amount'] = $orderDetail->credit_amount + $transaction_amount;
                                                $updateMasterWhere = "order_no = '$invoice_no' and retailer_id='" . $_SESSION['id'] . "' and status not in ('7','8')";
                                                $update = update("retailer_order_master", $updateMaster, $updateMasterWhere);
                                                if ($update) {
                                                    $pending_borrowed_transaction = array();
                                                    $pending_borrowed_transaction['retailer_id'] = $_SESSION['id'];
                                                    $pending_borrowed_transaction['order_no'] = $invoice_no;
                                                    $pending_borrowed_transaction['transaction_no'] = $transaction_no;
                                                    $pending_borrowed_transaction['date'] = date("Y-m-d", strtotime($transaction_date));
                                                    $pending_borrowed_transaction['datetime'] = date("Y-m-d H:i:s");
                                                    $pending_borrowed_transaction['amount'] = $transaction_amount;
                                                    $pending_borrowed_transaction['mode'] = $mode;
                                                    $pending_borrowed_transaction['status'] = 1;
                                                    $insert = insert("pending_borrowed_transaction", $pending_borrowed_transaction);
                                                    if ($insert) {
                                                        echo "<script>alert('Successfully Inserted.');window.location='customer_pending_payment.php?menu=29';</script>";
                                                        exit;
                                                    } else {
                                                        echo "<script>alert('Error Inserted.');window.location='customer_pending_payment.php?menu=29';</script>";
                                                        exit;
                                                    }
                                                }
                                            } else {
                                                echo "<script>alert('Please enter valid amount. Entered amount should be less than pending amount.');window.location='customer_pending_payment.php?menu=29';</script>";
                                                exit;
                                            }
                                        } else {
                                            echo "<script>alert('No Pending Amount');window.location='customer_pending_payment.php?menu=29';</script>";
                                            exit;
                                        }
                                    } else {
                                        echo "<script>alert('Please Enter Valid Invoice Number.');window.location='customer_pending_payment.php?menu=29';</script>";
                                        exit;
                                    }
                                }
                                if (isset($_POST['submit'])) {
                                    $invoice_no = $_POST['invoice_no'];
                                    if (empty($invoice_no)) {
                                        echo "<script>alert('Please Enter Valid Invoice Number.');window.location='customer_pending_payment.php?menu=29';</script>";
                                        exit;
                                    }
                                    ?>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="row">
                                                <div>
                                                    <table class="table table-bordered table-hover">
                                                        <tr>
                                                            <td colspan="2">
                                                                <table class="table table-bordered table-hover">
                                                                    <thead class="thead-dark">
                                                                        <tr>
                                                                            <th width="8%" align="left">#</th>
                                                                            <th width="8%" align="left">Retailer Name</th>
                                                                            <th width="8%" align="left">CusName</th>
                                                                            <th width="8%" align="left">CusMobile</th>
                                                                            <th width="8%" align="left">CusAddress</th>
                                                                            <th width="15%" align="left">Date</th>
                                                                            <th width="15%" align="left">Amount</th>
                                                                            <th width="15%" align="left">borrowedAmount</th>
                                                                            <th width="15%" align="left">CreditAmount</th>
                                                                            <th width="15%" align="left">PendingAmount</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                        $index = 1;
                                                                        $orderDetail = getBookSaleOrderByOrderId($invoice_no, $_SESSION['id']);
                                                                        $pendingAmount = amount($orderDetail->total_price - $orderDetail->credit_amount);
                                                                        ?>
                                                                        <tr>
                                                                            <td><?php echo $index; ?></td>
                                                                            <td><?php echo getRetailerNameById($orderDetail->retailer_id); ?></td>
                                                                            <td><?php echo $orderDetail->cus_name; ?></td>
                                                                            <td><?php echo $orderDetail->cus_ph; ?></td>
                                                                            <td><?php echo $orderDetail->cus_add; ?></td>
                                                                            <td><?php echo date("d M Y H:i:s", strtotime($orderDetail->added_datetime)); ?></td>
                                                                            <td><?php echo amount($orderDetail->total_price); ?></td>
                                                                            <td><?php echo amount($orderDetail->pending_amount); ?></td>
                                                                            <td><?php echo $orderDetail->credit_amount; ?></td>
                                                                            <td><?php echo $pendingAmount; ?></td>
                                                                        </tr>
                                                                        <?php
                                                                        $index++;
                                                                        ?>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <table class="table table-bordered table-hover">
                                                                    <thead class="thead-dark">
                                                                        <tr>
                                                                            <td>TransactionNo</td>
                                                                            <td>Mode</td>
                                                                            <td>Amount</td>
                                                                            <td>TransactinDate</td>
                                                                            <td>UpdateDate</td>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                        $pending_borrowed_transactions = get_pending_borrowed_transaction($invoice_no, $_SESSION['id']);
                                                                        foreach ($pending_borrowed_transactions as $pending_borrowed_transaction) {
                                                                            ?>
                                                                            <tr>
                                                                                <td><?php echo $pending_borrowed_transaction->transaction_no; ?></td>
                                                                                <td><?php echo $pending_borrowed_transaction->mode; ?></td>
                                                                                <td><?php echo $pending_borrowed_transaction->amount; ?></td>
                                                                                <td><?php echo $pending_borrowed_transaction->date; ?></td>
                                                                                <td><?php echo $pending_borrowed_transaction->datetime; ?></td>
                                                                            </tr>
                                                                        <?php } ?>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                            <?php if ($pendingAmount > 0) { ?>
                                                                <td>
                                                                    <form action="" method="POST">
                                                                        <table class="table">
                                                                            <tr>
                                                                                <td>TransactionNo: 
                                                                                    <input type="hidden" name="invoice_no" value="<?php echo base64_encode($invoice_no); ?>" />
                                                                                    <input type="text" name="transaction_no" />
                                                                                </td>
                                                                                <td>Amount: <input type="text" name="transaction_amount" /></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>TransactionDate: <input type="date" name="transaction_date" /></td>
                                                                                <td>Mode: 
                                                                                    <select name="mode">
                                                                                        <option value="Online">Online</option>
                                                                                        <option value="Cash">Cash</option>
                                                                                    </select>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td></td>
                                                                                <td><button type="submit" name="update" class="btn btn-primary">Update</button></td>
                                                                            </tr>
                                                                        </table>
                                                                    </form>
                                                                </td>
                                                            <?php } ?>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- /.row -->
                                <?php } ?>
                            </div>
                        </div><!-- /.row -->
                    </div><!-- /.page-content -->
                </div>
            </div><!-- /.main-content -->

            <script type="text/javascript">
                function getSelectionDetails() {
                    var transfer_mode = document.getElementById('transfer_mode').value;
                    if (transfer_mode != '') {
                        $.ajax({
                            type: 'POST',
                            url: '<?php echo $ajax_page; ?>',
                            data: {
                                transfer_mode: transfer_mode,
                                'request_type': 'get_bank_retailer_selection'
                            },
                            success: function (result) {
                                document.getElementById('bank_id').innerHTML = result;
                            }
                        });
                    }
                }

                function getRetailerItem() {
                    var retailer_id = document.getElementById('retailer_id').value;
                    // alert(retailer_id);
                    $.ajax({
                        type: 'POST',
                        url: '<?php echo $ajax_page; ?>',
                        data: {
                            retailer_id: retailer_id,
                            'request_type': 'retailer_item_by_id'
                        },
                        success: function (result) {

                            document.getElementById('item_id').innerHTML = result;

                        }
                    });

                }

                function deletedata(id) {
                    $.ajax({
                        type: 'POST',
                        url: '<?php echo $ajax_page; ?>',
                        data: {
                            id: id,
                            'request_type': 'delete_stock_trans_data'
                        },
                        success: function (result) {
                            if (result == 0) {
                                alert('Data Remove Successfully...');
                                window.location = window.location;
                            } else {
                                alert('Data Remove Error...');
                                window.location = window.location;
                            }
                        }
                    });
                }

                function confirmOrder(retailer_id) {
                    $.ajax({
                        type: 'POST',
                        url: '<?php echo $ajax_page; ?>',
                        data: {
                            retailer_id: retailer_id,
                            'request_type': 'confirm_stock_tras_request'
                        },
                        success: function (result) {
                            if (result == 0) {
                                alert('Request Placed Successfully...');
                                window.location = window.location;
                            } else {
                                alert('Request Placed Error...');
                                window.location = window.location;
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