<?php
require_once 'includes/session.php';
require_once 'includes/common_function.php';
$retailer_id = '';
if (isset($_POST['Retailer_id'])) {
    $retailer_id = $_POST['Retailer_id'];
}
if (isset($_POST['show'])) {
    if (isset($_POST['Retailer_id'])) {
        $retailer_id = $_POST['Retailer_id'];
    }
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
                                <div class="page-header">
                                    <div class="widget-box">
                                        <div class="widget-header">
                                            <h4 class="widget-title">Retailer | Day Book Details.</h4>
                                        </div>
                                        <div class="widget-body">
                                            <div class="widget-main">
                                                <form class="form-inline" action="" method="POST">
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <b>From Date :</b>
                                                            <div class="input-group">
                                                                <input class="form-control date-picker" id="id-" name="date_2" type="text" value="<?php
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
                                                        <div class="form-group">
                                                            <b>To Date :</b>
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
                                                        <div class="form-group">
                                                            <b>Select Retailer :</b>
                                                            <div class="input-group">
                                                                <select class="form-control col-xs-3" name="Retailer_id" id="Retailer_id" required="required">
                                                                    <option value="">--select--</option>
                                                                    <?php foreach (getAllRetailerDetails($company_id_in) as $active_sellers) { ?>
                                                                        <option value="<?php echo $active_sellers->id; ?>" <?php
                                                                        if ($retailer_id == $active_sellers->id) {
                                                                            echo 'selected="selected"';
                                                                        }
                                                                        ?>><?php echo $active_sellers->name; ?><?php
                                                                                    if ($active_sellers->status == 0) {
                                                                                        echo '<b class="red"> [Clossed]</b>';
                                                                                    }
                                                                                    ?></option>
                                                                    <?php } ?>
                                                                </select>
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
                                <div class="modal-body">
                                    <div class="row clearfix">
                                        <div class="pull-right tableTools-container"></div>
                                    </div>
                                    <div>
                                        <table id="dynamic-table" class="table table-bordered table-hover">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Retailer Name</th>
                                                    <th>Transaction Type</th>
                                                    <th>Transaction Date</th>
                                                    <th>Opening</th>
                                                    <th>Debit</th>
                                                    <th>Credit</th>
                                                    <th>closing</th>   
                                                    <th>Remarks</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if (isset($_POST['show'])) {
                                                    if (isset($_POST['date_1'])) {
                                                        $date_2 = date("Y-m-d", strtotime($_POST['date_1']));
                                                        $date_2 = date('Y-m-d', strtotime($date_2 . ' + 1 days'));
                                                    } else {
                                                        $date_2 = date("Y-m-d");
                                                        $date_2 = date('Y-m-d', strtotime($date_2 . ' + 1 days'));
                                                    }
                                                    if (isset($_POST['Retailer_id'])) {
                                                        $retailer_id = $_POST['Retailer_id'];
                                                    } else {
                                                        print '<script>alert("Please Select Retailer.");window.location="retailer_cash_in_hand.phpmenu=406";</script>';
                                                        exit;
                                                    }
                                                    $f_start_date = date(getFirstRetailerOrderByRetailerId($retailer_id));
                                                    $date_1 = date("Y-m-d", strtotime($_POST['date_2']));
                                                    $f_end_date = dateMinus($date_1, 1);
                                                    
                                                    $index = 1;
                                                    $start_date = date_create($date_1);
                                                    $end_date = date_create($date_2);
                                                    $interval = new DateInterval('P1D');
                                                    $date_range = new DatePeriod($start_date, $interval, $end_date);
                                                    $opening = 0;
                                                    $debit = 0;
                                                    $credit = 0;
                                                    $closing = 0;
                                                    $remarks = "NA";

                                                    $july_opening = getRetailerOpeningById($retailer_id);
                                                    $sale_total = getRetailerSalesByDateCountAsOnJoin($retailer_id, $f_start_date, $f_end_date);
                                                    $approve_tra = getApprovedTransactionbyDadeCountAsOn($retailer_id, $f_start_date, $f_end_date);
                                                    $received_tra = getTransferByRetailerApprovedTransactionbyDadeCountAsOn($retailer_id, $f_start_date, $f_end_date);
                                                    $received_expense = getApprovedExpensesByDateCountAsOn($retailer_id, $f_start_date, $f_end_date);
                                                    $opening = $july_opening + $sale_total - $approve_tra + $received_tra - $received_expense;

                                                    $ttl_credit = 0;
                                                    $ttl_dedit = 0;


                                                    foreach ($date_range as $date) {
                                                        $for_date = $date->format('Y-m-d');
                                                        //Sales Credit

                                                        $sales_data = getRetailerSalesDetailsByDateCountAsOnJoin($retailer_id, $for_date);
//                                                        $sales_data = getRetailerSalesDetailsByDate($retailer_id, $for_date);
                                                        if (count($sales_data) > 0) {
                                                            foreach ($sales_data as $sale) {
                                                                $transaction_no = $sale->order_no;
                                                                $remarks = "" . $transaction_no;
                                                                $credit = $sale->total_price;
                                                                $debit = 0;
                                                                $closing = $opening + $credit;
                                                                $ttl_credit = $ttl_credit + $credit;
                                                                $ttl_dedit = $ttl_dedit + $debit;
                                                                ?>
                                                                <tr>
                                                                    <td><?php echo $index; ?></td>
                                                                    <td><?php echo getRetailerNameById($retailer_id); ?></td>
                                                                    <td>SALE BILL - <?php echo $transaction_no; ?></td>
                                                                    <td><?php echo $for_date; ?></td>
                                                                    <td><?php echo IND_money_format($opening); ?></td>
                                                                    <td><?php echo IND_money_format($debit); ?></td>
                                                                    <td><?php echo IND_money_format($credit); ?></td>
                                                                    <td><?php echo IND_money_format($closing); ?></td>
                                                                    <td><?php echo $remarks; ?></td>
                                                                </tr>
                                                                <?php
                                                                $opening = $closing;
                                                                $index++;
                                                            }
                                                        }

//Transactions Debit
                                                        $transactions_data = getApprovedTransactionbyDade($retailer_id, $for_date);
                                                        if (count($transactions_data) > 0) {
                                                            foreach ($transactions_data as $transaction) {
                                                                if (!empty($transaction->transaction_no)) {
                                                                    $transaction_no = $transaction->transaction_no;
                                                                } else {
                                                                    $transaction_no = "";
//                                                                    $transaction_no = $transaction->slip;
//                                                                    $transaction_no = " <a target='_blank' href='../retailer/slip" . $transaction->slip . "' />click</a>";
                                                                }
                                                                if ($transaction->mode == 1) {
                                                                    $type_name = getBankNameById($transaction->bank_id);
                                                                } else {
                                                                    $type_name = getRetailerNameById($transaction->bank_id);
                                                                }
                                                                $remarks = "";
                                                                $credit = 0;
                                                                $debit = $transaction->amount;
                                                                $closing = $opening - $debit;
                                                                $ttl_credit = $ttl_credit + $credit;
                                                                $ttl_dedit = $ttl_dedit + $debit;
                                                                ?>
                                                                <tr>
                                                                    <td><?php echo $index; ?></td>
                                                                    <td><?php echo getRetailerNameById($retailer_id); ?></td>
                                                                    <td><?php echo $type_name; ?></td>
                                                                    <td><?php echo $for_date; ?></td>
                                                                    <td><?php echo IND_money_format($opening); ?></td>
                                                                    <td><?php echo IND_money_format($debit); ?></td>
                                                                    <td><?php echo IND_money_format($credit); ?></td>
                                                                    <td><?php echo IND_money_format($closing); ?></td>
                                                                    <td><?php echo $remarks; ?></td>
                                                                </tr>
                                                                <?php
                                                                $opening = $closing;
                                                                $index++;
                                                            }
                                                        }
//Transactions Credit
                                                        $transactions_data = getTransferByRetailerApprovedTransactionbyDade($retailer_id, $for_date);
                                                        if (count($transactions_data) > 0) {
                                                            foreach ($transactions_data as $transaction) {
                                                                if (!empty($transaction->transaction_no)) {
                                                                    $transaction_no = $transaction->transaction_no;
                                                                } else {
                                                                    $transaction_no = $transaction->slip;
                                                                }
                                                                $remarks = "";
                                                                $debit = 0;
                                                                $credit = $transaction->amount;
                                                                $closing = $opening + $credit;
                                                                $ttl_credit = $ttl_credit + $credit;
                                                                $ttl_dedit = $ttl_dedit + $debit;
                                                                ?>
                                                                <tr>
                                                                    <td><?php echo $index; ?></td>
                                                                    <td><?php echo getRetailerNameById($retailer_id); ?></td>
                                                                    <td>CASH RECEIVED FROM [<?php echo getRetailerNameById($transaction->retailer_id); ?>]<?php // echo $transaction_no;                                                                              ?></td>
                                                                    <td><?php echo $for_date; ?></td>
                                                                    <td><?php echo IND_money_format($opening); ?></td>
                                                                    <td><?php echo IND_money_format($debit); ?></td>
                                                                    <td><?php echo IND_money_format($credit); ?></td>
                                                                    <td><?php echo IND_money_format($closing); ?></td>
                                                                    <td><?php echo $remarks; ?></td>
                                                                </tr>
                                                                <?php
                                                                $opening = $closing;
                                                                $index++;
                                                            }
                                                        }
                                                        //Expense Debit
                                                        $expensess_data = getApprovedExpensesByDate($retailer_id, $for_date);
                                                        if (count($expensess_data) > 0) {
                                                            foreach ($expensess_data as $expense) {
                                                                if (!empty($expense->transaction_no)) {
                                                                    $transaction_no = $expense->expense_title;
                                                                } else {
                                                                    $transaction_no = $expense->slip;
                                                                }
                                                                $remarks = "";
                                                                $credit = 0;
                                                                $debit = $expense->amount;
                                                                $closing = $opening - $debit;
                                                                $ttl_credit = $ttl_credit + $credit;
                                                                $ttl_dedit = $ttl_dedit + $debit;
                                                                ?>
                                                                <tr>
                                                                    <td><?php echo $index; ?></td>
                                                                    <td><?php echo getRetailerNameById($retailer_id); ?></td>
                                                                    <td>EXPENSE MADE - <?php echo $transaction_no; ?></td>
                                                                    <td><?php echo $for_date; ?></td>
                                                                    <td><?php echo IND_money_format($opening); ?></td>
                                                                    <td><?php echo IND_money_format($debit); ?></td>
                                                                    <td><?php echo IND_money_format($credit); ?></td>
                                                                    <td><?php echo IND_money_format($closing); ?></td>
                                                                    <td><?php echo $remarks; ?></td>
                                                                </tr>
                                                                <?php
                                                                $opening = $closing;
                                                                $index++;
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="5"></td>
                                                        <td ><?php echo $ttl_dedit; ?></td>
                                                        <td><?php echo $ttl_credit; ?></td>
                                                        <td colspan="2"></td>
                                                    </tr>
                                                </tfoot>
                                                <?php
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
        </div>
    </body>
</html>

