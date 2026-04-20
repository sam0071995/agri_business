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
                                            <h4 class="widget-title">Retailer | Cash In Hand Summary.</h4>
                                        </div>
                                        <div class="widget-body">
                                            <div class="widget-main">
                                                <form class="form-inline" action="" method="POST">
                                                    <div class="row">
                                                        <div class="col-xs-12">
                                                            <div class="form-group">
                                                                <b>As on Date :</b>
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
                                        <table id="dynamic-table" class="table table-bordered table-hover left">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Retailer Name</th>
                                                    <th>Opening</th>
                                                    <th>Sales</th>
                                                    <th>Bank</th>
                                                    <th>UPI</th>
                                                    <th>Transfer</th>
                                                    <th>Received</th>
                                                    <th>Expenses</th>
                                                    <th>CIH</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $bank_deposit_transactions_count = 0;
                                                $upi_transactions_count = 0;
                                                $transfer_transactions_count = 0;
                                                $final_CIH_op = 0;
                                                $final_day_sales_count = 0;
                                                $final_upi_transactions_count = 0;
                                                $final_transfer_transactions_count = 0;
                                                $final_bank_deposit_transactions_count = 0;
                                                $final_day_wise_transactions_trans_count = 0;
                                                $day_wise_transactions_trans_count = 0;
                                                $final_day_wise_expensess_count = 0;
                                                $final_CIH = 0;
                                                if (isset($_POST['show'])) {
                                                    if (isset($_POST['date_1'])) {
                                                        $date_2 = date("Y-m-d", strtotime($_POST['date_1']));
                                                    } else {
                                                        $date_2 = date("Y-m-d");
                                                    }
                                                    $for_date = $date_2;
                                                    $date_2 = dateMinus($date_2, 1);
                                                    $index = 1;
                                                    foreach (getAllActiveRetailerDetails($company_id_in) as $active_sellers) {
                                                        $retailer_id = $active_sellers->id;
                                                        $date_1 = date(getFirstRetailerOrderByRetailerId($retailer_id));
                                                        //Sales Credit
                                                        $sales_count = getRetailerSalesByDateCountAsOnJoin($retailer_id, $date_1, $date_2);
                                                        //Transactions Debit
                                                        $transactions_count = getApprovedTransactionbyDadeCountAsOn($retailer_id, $date_1, $date_2);

                                                        //Transactions Credit
                                                        $transactions_trans_count = getTransferByRetailerApprovedTransactionbyDadeCountAsOn($retailer_id, $date_1, $date_2);
                                                        $july_opening = getRetailerOpeningById($retailer_id);
                                                        //Expense Debit
                                                        $expensess_count = getApprovedExpensesByDateCountAsOn($retailer_id, $date_1, $date_2);
                                                        $CIH_op = $july_opening + $sales_count - $transactions_count + $transactions_trans_count - $expensess_count;

                                                        //day wise
                                                        $bank_deposit_transactions_count = getBankDepositApprovedTransactionbyDadeCountAsOn($retailer_id, $for_date);
                                                        if (empty($bank_deposit_transactions_count)) {
                                                            $bank_deposit_transactions_count = 0;
                                                        }
                                                        $upi_transactions_count = getUPIDepositApprovedTransactionbyDadeCountAsOn($retailer_id, $for_date);
                                                        if (empty($upi_transactions_count)) {
                                                            $upi_transactions_count = 0;
                                                        }
                                                        $transfer_transactions = getTransferedDepositApprovedTransactionbyDadeCountAsOn($retailer_id, $for_date);
                                                        if (empty($transfer_transactions)) {
                                                            $transfer_transactions = 0;
                                                        }

                                                        $day_sales_count = getRetailerDayWiseSalesByDateCountAsOnJoin($retailer_id, $for_date);
                                                        if (empty($day_sales_count)) {
                                                            $day_sales_count = 0;
                                                        }

                                                        $day_wise_transactions_trans_count = getTransferDayWiseByRetailerApprovedTransactionbyDadeCountAsOn($retailer_id, $for_date);
                                                        $day_wise_expensess_count = getApprovedDayWiseExpensesByDateCountAsOn($retailer_id, $for_date);
                                                        $CIH = $CIH_op + $day_sales_count - $bank_deposit_transactions_count - $upi_transactions_count - $transfer_transactions + $day_wise_transactions_trans_count - $day_wise_expensess_count;

                                                        $final_CIH_op = $final_CIH_op + $CIH_op;
                                                        $final_day_sales_count = $final_day_sales_count + $day_sales_count;
                                                        $final_bank_deposit_transactions_count = $final_bank_deposit_transactions_count + $bank_deposit_transactions_count;
                                                        $final_upi_transactions_count = $final_upi_transactions_count + $upi_transactions_count;
                                                        $transfer_transactions_count = $transfer_transactions_count + $transfer_transactions;
                                                        $final_day_wise_transactions_trans_count = $final_day_wise_transactions_trans_count + $day_wise_transactions_trans_count;
                                                        $final_day_wise_expensess_count = $final_day_wise_expensess_count + $day_wise_expensess_count;
                                                        $final_CIH = $final_CIH + $CIH;
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $index; ?></td>
                                                            <td><?php echo getRetailerNameById($retailer_id); ?></td>
                                                            <td><?php echo IND_money_format($CIH_op); ?></td>
                                                            <td><?php echo IND_money_format($day_sales_count); ?></td>
                                                            <td><?php echo IND_money_format($bank_deposit_transactions_count); ?></td>
                                                            <td><?php echo IND_money_format($upi_transactions_count); ?></td>
                                                            <td><?php echo IND_money_format($transfer_transactions); ?></td>
                                                            <td><?php echo IND_money_format($day_wise_transactions_trans_count); ?></td>
                                                            <td><?php echo IND_money_format($day_wise_expensess_count); ?></td>
                                                            <td><?php echo IND_money_format($CIH); ?></td>
                                                        </tr>
                                                        <?php
                                                        $index++;
                                                    }
                                                }
                                                ?>
                                            </tbody>

                                            <tr>
                                                <td><?php echo ""; ?></td>
                                                <td><?php echo "Total"; ?></td>
                                                <td><?php echo IND_money_format($final_CIH_op); ?></td>
                                                <td><?php echo IND_money_format($final_day_sales_count); ?></td>
                                                <td><?php echo IND_money_format($final_bank_deposit_transactions_count); ?></td>
                                                <td><?php echo IND_money_format($final_upi_transactions_count); ?></td>
                                                <td><?php echo IND_money_format($transfer_transactions_count); ?></td>
                                                <td><?php echo IND_money_format($final_day_wise_transactions_trans_count); ?></td>
                                                <td><?php echo IND_money_format($final_day_wise_expensess_count); ?></td>
                                                <td><?php echo IND_money_format($final_CIH); ?></td>
                                            </tr>
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

