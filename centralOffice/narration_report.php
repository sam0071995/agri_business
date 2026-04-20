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
                                            <h3 class="header-text"> Expense Approval Request.</h3>
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
                                                                        <b>Distributer :</b>
                                                                        <div class="input-group">
                                                                            <select class="form-control" name="retailer_id">
                                                                                <!--<option value="0">All</option>-->
                                                                                <?php foreach (getActiveRetailerDetails($company_id_in) as $dataone) { ?>
                                                                                    <option value="<?php echo $dataone->id; ?>" <?php
                                                                                    if (isset($_POST['retailer_id'])) {
                                                                                        if ($_POST['retailer_id'] == $dataone->id) {
                                                                                            echo 'selected';
                                                                                        }
                                                                                    }
                                                                                    ?>><?php echo $dataone->name; ?>
                                                                                    </option>
                                                                                <?php } ?>
                                                                            </select>
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
                                                                    <th width="15%" align="left">Distributer</th>
                                                                    <th width="25%" align="left">Doc Date</th>
                                                                    <th width="15%" align="left">NARRATION</th>
                                                                    <th width="15%" align="left">Opening</th>
                                                                    <th width="15%" align="left">Credit</th>
                                                                    <th width="15%" align="left">Debit</th>
                                                                    <th width="15%" align="left">RUNNING BALANCE</th>
                                                                    <th width="25%" align="left">DAY BOOK DESCRIPTION</th>
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
                                                                if (isset($_POST['filter'])) {
                                                                    $date_2 = date("Y-m-d", strtotime($_POST['date_1']));
                                                                    $date_3 = date("Y-m-d", strtotime($_POST['date_2']));
                                                                    $retailer_id = $_POST['retailer_id'];
                                                                    $for_date = $date_2;
                                                                    $date_2 = dateMinus($date_2, 1);
                                                                    $index = 1;
                                                                    foreach (getRetailerActiveRetailerDetails($company_id_in, $retailer_id) as $active_sellers) {
                                                                        $retailer_id = $active_sellers->id;
                                                                        $date_1 = date(getFirstRetailerOrderByRetailerId($retailer_id));
                                                                        //Sales Credit
                                                                        $sales_count = getProductSalesTotalAmtByRetailerTempTable($date_1, $date_2, $retailer_id, $company_id_in);
//                                                                        $sales_count = getRetailerSalesByDateCountAsOn($retailer_id, $date_1, $date_2);
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

                                                                        $day_sales_count = getRetailerDayWiseSalesByDateCountAsOn($retailer_id, $for_date);
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

                                                                        $start_date = date("Y-m-d", strtotime($_POST['date_1']));
                                                                        $end_date = date("Y-m-d", strtotime($_POST['date_2']));
                                                                        $end_date = date('Y-m-d', strtotime($end_date . ' + 1 days'));
                                                                        $start_date = date_create($start_date);
                                                                        $end_date = date_create($end_date);
                                                                        $interval = new DateInterval('P1D');
                                                                        $date_range = new DatePeriod($start_date, $interval, $end_date);

                                                                        foreach ($date_range as $date) {
                                                                            $this_is_on_date = $date->format('Y-m-d');

//Sales Count
                                                                            $sales_count = getRetailerSalesByDateCountAsOn($retailer_id, $this_is_on_date, $this_is_on_date);
                                                                            $credit = $sales_count;
                                                                            $debit = 0;
                                                                            $CIH_closing = $CIH_op + $credit;
                                                                            ?>
                                                                            <tr>
                                                                                <td><?php echo $index; ?></td>
                                                                                <td><?php echo getRetailerNameById($retailer_id); ?></td>
                                                                                <td><?php echo $this_is_on_date; ?></td>
                                                                                <td><?php echo "Sales on " . date("d M Y", strtotime($this_is_on_date)); ?></td>
                                                                                <td><?php echo $CIH_op; ?></td>
                                                                                <td><?php echo $credit; ?></td>
                                                                                <td><?php echo $debit; ?></td>
                                                                                <td><?php echo $CIH_closing; ?></td>
                                                                                <td><?php echo "Sales"; ?></td>
                                                                            </tr>
                                                                            <?php
                                                                            $CIH_op = $CIH_closing;
                                                                            $index++;

                                                                            $transaction_details = getApprovedTransactionbyDade($retailer_id, $this_is_on_date);
                                                                            foreach ($transaction_details as $transaction_detail) {
                                                                                if ($transaction_detail->mode == 1) {
                                                                                    $narration_tranaction = "Transfer To " . getBankNameById($transaction_detail->bank_id);
                                                                                } else {
                                                                                    $narration_tranaction = "Transfer To " . getRetailerNameById($transaction_detail->bank_id);
                                                                                }
                                                                                $credit = 0;
                                                                                $debit = $transaction_detail->amount;
                                                                                $CIH_closing = $CIH_op - $debit;
                                                                                ?>
                                                                                <tr>
                                                                                    <td><?php echo $index; ?></td>
                                                                                    <td><?php echo getRetailerNameById($retailer_id); ?></td>
                                                                                    <td><?php echo $this_is_on_date; ?></td>
                                                                                    <td><?php echo $narration_tranaction; ?></td>
                                                                                    <td><?php echo $CIH_op; ?></td>
                                                                                    <td><?php echo $credit; ?></td>
                                                                                    <td><?php echo $debit; ?></td>
                                                                                    <td><?php echo $CIH_closing; ?></td>
                                                                                    <td><?php echo $transaction_detail->transaction_remark; ?></td>
                                                                                </tr>
                                                                                <?php
                                                                                $CIH_op = $CIH_closing;
                                                                                $index++;
                                                                            }

                                                                            $transaction_reveived_details = getApprovedReceivedTransactionbyDate($retailer_id, $this_is_on_date);
                                                                            foreach ($transaction_reveived_details as $transaction_reveived_detail) {
                                                                                $narration_tranaction = "Received from " . getRetailerNameById($transaction_reveived_detail->retailer_id);
                                                                                $debit = 0;
                                                                                $credit = $transaction_reveived_detail->amount;
                                                                                $CIH_closing = $CIH_op + $credit;
                                                                                ?>
                                                                                <tr>
                                                                                    <td><?php echo $index; ?></td>
                                                                                    <td><?php echo getRetailerNameById($retailer_id); ?></td>
                                                                                    <td><?php echo $this_is_on_date; ?></td>
                                                                                    <td><?php echo $narration_tranaction; ?></td>
                                                                                    <td><?php echo $CIH_op; ?></td>
                                                                                    <td><?php echo $credit; ?></td>
                                                                                    <td><?php echo $debit; ?></td>
                                                                                    <td><?php echo $CIH_closing; ?></td>
                                                                                    <td><?php echo $transaction_reveived_detail->transaction_remark; ?></td>
                                                                                </tr>
                                                                                <?php
                                                                                $CIH_op = $CIH_closing;
                                                                                $index++;
                                                                            }

                                                                            $expenses_details = getApprovedExpensesByDate($retailer_id, $this_is_on_date);
                                                                            foreach ($expenses_details as $expenses_detail) {
                                                                                $narration_tranaction = "Expense For " . $expenses_detail->expense_title;
                                                                                $credit = 0;
                                                                                $debit = $expenses_detail->amount;
                                                                                $CIH_closing = $CIH_op - $debit;
                                                                                ?>
                                                                                <tr>
                                                                                    <td><?php echo $index; ?></td>
                                                                                    <td><?php echo getRetailerNameById($retailer_id); ?></td>
                                                                                    <td><?php echo $this_is_on_date; ?></td>
                                                                                    <td><?php echo $narration_tranaction; ?></td>
                                                                                    <td><?php echo $CIH_op; ?></td>
                                                                                    <td><?php echo $credit; ?></td>
                                                                                    <td><?php echo $debit; ?></td>
                                                                                    <td><?php echo $CIH_closing; ?></td>
                                                                                    <td><?php echo $expenses_detail->store_remarks; ?></td>
                                                                                </tr>
                                                                                <?php
                                                                                $CIH_op = $CIH_closing;
                                                                                $index++;
                                                                            }
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

            <script type="text/javascript">
                show_detail();
                function new_purchase_click()
                {
                    document.getElementById("purchase_detail").form_type.value = "new";
                    document.getElementById("purchase_detail").purchase_id.value = "";
                    document.purchase_detail.submit();
                }
                function released_purchase_click() {
                    window.location.href = "release_purchase_order.php?menu = 409";
                }

                function edit_purchase(x)
                {
                    document.getElementById("purchase_detail").form_type.value = "edit";
                    document.getElementById("purchase_detail").purchase_id.value = x;
                    document.purchase_detail.submit();
                }
                function deleteItem(key) {
                    if (confirm("Are you sure you want to Delete this?")) {
                        $.ajax({
                            type: 'POST',
                            url: 'ajax_new.php?menu = 409',
                            data: {'key': key, 'type': 'removeSessionItem'},
                            success: function (result) {
                                result = $.trim(result);
                                if (result == 1) {
                                    $(".item_inv_tr_" + key).html('');
                                } else {
                                    alert('Something Wrong Try Again.');
                                }
                            }
                        });
                    }
                }
                function release_purchase(purchase_id) {
                    if (confirm("Are you sure you want to Release this?")) {
                        $.ajax({
                            type: 'POST',
                            url: 'release_purchase_order.php?menu = 409',
                            data: {'purchase_id': purchase_id},
                            success: function (result) {
                                result = $.trim(result);
                                if (result) {
                                    window.location.href = "release_purchase_order.php?menu = 409&success = realese";
                                } else {
                                    alert('Something Wrong Try Again.');
                                }
                            }
                        });
                    }
                }
                function approveRequestTransaction(order_item_id)
                {
                    var remarks = $(".remarks_" + order_item_id).val();
                    if (remarks == '') {
                        alert("Enter Remarks");
                        return false;
                    }
                    var verify_data = $(".verify_" + order_item_id).val();
                    if (verify_data == '') {
                        alert("Select Verification");
                        return false;
                    }
                    if (confirm("Are you sure you want to approve this transaction?")) {
                        $.ajax({
                            type: 'POST',
                            url: 'ajax.php?menu=1',
                            data: {'types': 'approveExpense', 'remarks': remarks, 'order_item_id': order_item_id, 'verify_data': verify_data},
                            success: function (result) {
                                result = $.trim(result);
                                if (result == 1) {
                                    if (verify_data == 1) {
                                        alert('Expense Approved Successfully.');
                                    } else {
                                        alert('Expense Rejected Successfully.');
                                    }
                                    $(".tr_" + order_item_id).css("display", "none");
                                    //                                    window.location.href = "approve_distributer_expense.php?menu = 30";
                                } else {
                                    alert('Something Wrong Try Again.');
                                    //                                    window.location.href = "approve_distributer_expense.php?menu = 30";
                                }
                            }
                        });
                    }
                }
                function copy_purchase(purchase_id)
                {
                    if (confirm("Are you sure you want to copy this?")) {
                        $.ajax({
                            type: 'POST',
                            url: 'copy_purchase_order.php?menu = 409',
                            data: {'purchase_id': purchase_id},
                            success: function (result) {
                                result = $.trim(result);
                                if (result == 1) {
                                    window.location.href = "purchase_order.php?menu = 409&success = copy";
                                } else {
                                    alert('Something Wrong Try Again.');
                                    window.location.href = "purchase_order.php?menu = 409&failure = 1";
                                }
                            }
                        });
                    }
                }
                function delete_purchase(purchase_id)
                {
                    if (confirm("Are you sure you want to Delete this?")) {
                        $.ajax({
                            type: 'POST',
                            url: 'delete_purchase_order.php?menu = 409',
                            data: {'purchase_id': purchase_id},
                            success: function (result) {
                                result = $.trim(result);
                                if (result == 1) {
                                    window.location.href = "purchase_order.php?menu = 409&success = delete";
                                } else {
                                    alert('Something Wrong Try Again.');
                                    window.location.href = "purchase_order.php?menu = 409&failure = 1";
                                }
                            }
                        });
                    }
                }
                function show_detail()
                {
                    var vendor_id_ajit = document.getElementById("txt_vendor").value;
                    if (vendor_id_ajit != '') {
                        var xmlhttp;
                        var url = "ajax_agro.php??menu = 409&type = vendor&id = " + vendor_id_ajit;
                        document.getElementById("div_detail").innerHTML = "";
                        if (window.XMLHttpRequest)
                        {
                            xmlhttp = new XMLHttpRequest();
                        } else
                        {
                            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                        }
                        xmlhttp.onreadystatechange = function ()
                        {
                            if (xmlhttp.readyState == 4 && (xmlhttp.status >= 200 && xmlhttp.status <= 299))
                            {
                                document.getElementById("div_detail").innerHTML = xmlhttp.responseText;
                                document.getElementById("txt_person").value = document.getElementById("ajax_person").value;
                                document.getElementById("txt_number").value = document.getElementById("ajax_number").value;
                                document.getElementById("txt_address").value = document.getElementById("ajax_address").value;
                            }
                        }
                        xmlhttp.open("GET", url, true);
                        xmlhttp.send();
                    }
                }

                function item_unit()
                {
                    var str = document.getElementById('txt_item').value;
                    var res = str.split("(^)");
                    var xmlhttp;
                    var url = "ajax_agro.php??menu = 409&type = itemunit&id = " + res[0];
                    document.getElementById("div_detail").innerHTML = "";
                    if (window.XMLHttpRequest)
                    {
                        xmlhttp = new XMLHttpRequest();
                    } else
                    {
                        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                    }
                    xmlhttp.onreadystatechange = function ()
                    {
                        if (xmlhttp.readyState == 4 && (xmlhttp.status >= 200 && xmlhttp.status <= 299))
                        {
                            document.getElementById("div_detail").innerHTML = xmlhttp.responseText;
                            document.getElementById("txt_sku").value = document.getElementById("ajax_sku").value;
                        }
                    }
                    xmlhttp.open("GET", url, true);
                    xmlhttp.send();
                }

                function addRow()
                {
                    cal_item_detail();
                    if (document.getElementById("txt_item").value != "")
                    {
                        if (isNaN(document.getElementById("txt_qty").value))
                        {
                            document.getElementById("txt_qty").focus();
                        } else if (Number(document.getElementById("txt_qty").value) <= 0)
                        {
                            document.getElementById("txt_qty").focus();
                        } else if (isNaN(document.getElementById("txt_price").value))
                        {
                            document.getElementById("txt_price").focus();
                        } else if (Number(document.getElementById("txt_price").value) < 0)
                        {
                            document.getElementById("txt_price").focus();
                        }

                        //txt_item		txt_sku		txt_qty		txt_price		txt_total

                        else
                        {

                            var str = document.getElementById('txt_item').value;
                            var res = str.split("(^)");
                            var table = document.getElementById("pur_detail");
                            var rowcount = table.rows.length; //get table row count

                            if (table.rows[table.rows.length - 1].id == "")
                                rowid = 1;
                            else
                                rowid = Number(table.rows[table.rows.length - 1].id) + 1;
                            var row = table.insertRow(rowcount);
                            row.id = rowid;
                            //item id
                            var cell1 = row.insertCell(0);
                            cell1.id = (rowid) * 100 + 1;
                            cell1.innerHTML = res[0];
                            var element1 = document.createElement("input");
                            element1.id = (rowid) * 1000 + 1;
                            element1.name = (rowid) * 1000 + 1;
                            element1.size = "1";
                            element1.value = res[0];
                            element1.style.visibility = "hidden";
                            cell1.appendChild(element1);
                            //item nameFTA HSRP Solutions Pvt. Ltd.
                            var cell2 = row.insertCell(1);
                            cell2.id = (rowid) * 100 + 2;
                            cell2.innerHTML = res[1];
                            var element2 = document.createElement("input");
                            element2.id = (rowid) * 1000 + 2;
                            element2.name = (rowid) * 1000 + 2;
                            element2.size = "1";
                            element2.value = res[1];
                            element2.style.visibility = "hidden";
                            cell2.appendChild(element2);
                            //txt_sku
                            var cell3 = row.insertCell(2);
                            cell3.id = (rowid) * 100 + 3;
                            cell3.innerHTML = document.getElementById("txt_sku").value;
                            var element3 = document.createElement("input");
                            element3.id = (rowid) * 1000 + 3;
                            element3.name = (rowid) * 1000 + 3;
                            element3.size = "1";
                            element3.value = document.getElementById("txt_sku").value;
                            element3.style.visibility = "hidden";
                            cell3.appendChild(element3);
                            //txt_qty
                            var cell4 = row.insertCell(3);
                            cell4.id = (rowid) * 100 + 4;
                            cell4.innerHTML = document.getElementById("txt_qty").value;
                            var element4 = document.createElement("input");
                            element4.id = (rowid) * 1000 + 4;
                            element4.name = (rowid) * 1000 + 4;
                            element4.size = "1";
                            element4.value = document.getElementById("txt_qty").value;
                            element4.style.visibility = "hidden";
                            cell4.appendChild(element4);
                            //txt_price
                            var cell5 = row.insertCell(4);
                            cell5.id = (rowid) * 100 + 5;
                            cell5.innerHTML = document.getElementById("txt_price").value;
                            var element5 = document.createElement("input");
                            element5.id = (rowid) * 1000 + 5;
                            element5.name = (rowid) * 1000 + 5;
                            element5.size = "1";
                            element5.value = document.getElementById("txt_price").value;
                            element5.style.visibility = "hidden";
                            cell5.appendChild(element5);
                            //txt_total
                            var cell6 = row.insertCell(5);
                            cell6.id = (rowid) * 100 + 6;
                            cell6.innerHTML = document.getElementById("txt_total").value;
                            var element6 = document.createElement("input");
                            element6.id = (rowid) * 1000 + 6;
                            element6.name = (rowid) * 1000 + 6;
                            element6.size = "1";
                            element6.value = document.getElementById("txt_total").value;
                            element6.style.visibility = "hidden";
                            cell6.appendChild(element6);
                            //txt_total
                            var cell8 = row.insertCell(6);
                            cell8.id = (rowid) * 100 + 7;
                            cell8.innerHTML = document.getElementById("delivry_date").value;
                            var element7 = document.createElement("input");
                            element7.id = (rowid) * 1000 + 7;
                            element7.name = (rowid) * 1000 + 7;
                            element7.size = "1";
                            element7.value = document.getElementById("delivry_date").value;
                            element7.style.visibility = "hidden";
                            cell8.appendChild(element7);
                            var cell7 = row.insertCell(7);
                            cell7.id = (rowid) * 100 + 8;
                            cell7.innerHTML = "<button class = 'btn btn-danger btn-xs' onclick = 'javascript: del_purchase(" + rowid + ")'>Delete</button>";
                            document.getElementById("total_qty").value = ((document.getElementById("total_qty").value == "") ? Number(document.getElementById("txt_qty").value) : Number(document.getElementById("txt_qty").value) + Number(document.getElementById("total_qty").value));
                            document.getElementById("txt_subTotal").value = ((document.getElementById("txt_subTotal").value == "") ? Number(document.getElementById("txt_total").value) : Number(document.getElementById("txt_total").value) + Number(document.getElementById("txt_subTotal").value));
                            document.getElementById("txt_nettotal").value = ((document.getElementById("txt_pf").value == "0") ? Number(document.getElementById("txt_subTotal").value) : Number(document.getElementById("txt_subTotal").value) + Number(document.getElementById("txt_pf").value));
                            document.getElementById("txt_grandTotal").value = Math.round((document.getElementById("txt_nettotal").value == "") ? Number("0") : Number(document.getElementById("txt_nettotal").value) + Number(document.getElementById("txt_tot_cgst").value) + Number(document.getElementById("txt_tot_sgst").value) + Number(document.getElementById("txt_freight").value));
                            //txt_item		txt_sku		txt_qty		txt_price		txt_total
                            //total_qty		txt_pf		txt_nettotal	txt_cgst_per	txt_tot_cgst	txt_sgst_per
                            //txt_tot_sgst		txt_freight		txt_grandTotal	txt_amt

                            //Add to all total
                            //txt_nettotal		txt_tot_cgst		txt_tot_sgst		txt_freight															
                            document.getElementById("txt_item").value = "";
                            document.getElementById("txt_sku").value = "";
                            document.getElementById("txt_qty").value = "";
                            document.getElementById("txt_price").value = "";
                            document.getElementById("txt_total").value = "";
                            document.getElementById("pur_count").value = rowcount;
                            document.getElementById("txt_item").focus();
                        }
                    } else
                        document.getElementById("txt_item").focus();
                }

                function cal_item_detail()
                {
                    if (document.getElementById("txt_qty").value == "")
                        document.getElementById("txt_qty").value = "0";
                    else if (isNaN(document.getElementById("txt_qty").value))
                        document.getElementById("txt_qty").value = "0";
                    if (document.getElementById("txt_price").value == "")
                        document.getElementById("txt_price").value = "0";
                    else if (isNaN(document.getElementById("txt_price").value))
                        document.getElementById("txt_price").value = "0";
                    document.getElementById("txt_total").value = (Number(document.getElementById("txt_qty").value) * Number(document.getElementById("txt_price").value)).toFixed(2);
                }

                function del_purchase(x)
                {
                    cal_item_detail();
                    var row = document.getElementById(x);
                    if (document.getElementById("txt_pf").value == "")
                        document.getElementById("txt_pf").value = "0";
                    if (document.getElementById("txt_freight").value == "")
                        document.getElementById("txt_freight").value = "0";
                    document.getElementById("total_qty").value = Number(document.getElementById("total_qty").value) - Number(document.getElementById(x * 1000 + 4).value);
                    document.getElementById("txt_subTotal").value = Number(document.getElementById("txt_subTotal").value) - Number(document.getElementById(x * 1000 + 6).value);
                    document.getElementById("txt_nettotal").value = ((document.getElementById("txt_pf").value == "0") ? Number(document.getElementById("txt_subTotal").value) : Number(document.getElementById("txt_subTotal").value) + Number(document.getElementById("txt_pf").value));
                    document.getElementById("txt_tot_cgst").value = (Number(document.getElementById("txt_nettotal").value) * Number(document.getElementById("txt_cgst_per").value)) / 100;
                    document.getElementById("txt_tot_sgst").value = (Number(document.getElementById("txt_nettotal").value) * Number(document.getElementById("txt_sgst_per").value)) / 100;
                    document.getElementById("txt_amt").value = (Number(document.getElementById("txt_nettotal").value) + Number(document.getElementById("txt_tot_cgst").value) + Number(document.getElementById("txt_tot_sgst").value)).toFixed(2);
                    document.getElementById("txt_grandTotal").value = (Number(document.getElementById("txt_amt").value) + Number(document.getElementById("txt_freight").value)).toFixed(2);
                    row.parentNode.removeChild(row);
                    var table = document.getElementById("pur_detail");
                    var rowcount = table.rows.length; //get table row count
                    document.getElementById("pur_count").value = rowcount - 1;
                }

                function cal_net_amt()
                {
                    if (document.getElementById("txt_pf").value == "")
                        document.getElementById("txt_pf").value = "0";
                    if (document.getElementById("txt_freight").value == "")
                        document.getElementById("txt_freight").value = "0";
                    document.getElementById("txt_nettotal").value = ((document.getElementById("txt_pf").value == "0") ? Number(document.getElementById("txt_subTotal").value) : Number(document.getElementById("txt_subTotal").value) + Number(document.getElementById("txt_pf").value));
                    document.getElementById("txt_tot_cgst").value = (Number(document.getElementById("txt_nettotal").value) * Number(document.getElementById("txt_cgst_per").value)) / 100;
                    document.getElementById("txt_tot_sgst").value = (Number(document.getElementById("txt_nettotal").value) * Number(document.getElementById("txt_sgst_per").value)) / 100;
                    document.getElementById("txt_amt").value = (Number(document.getElementById("txt_nettotal").value) + Number(document.getElementById("txt_tot_cgst").value) + Number(document.getElementById("txt_tot_sgst").value)).toFixed(2);
                    document.getElementById("txt_grandTotal").value = (Number(document.getElementById("txt_amt").value) + Number(document.getElementById("txt_freight").value)).toFixed(2);
                    //document.getElementById("txt_total").value=(Number(document.getElementById("txt_qty").value)*Number(document.getElementById("txt_price").value)).toFixed(2);
                }

                $('#btn_submit1').click(function ()
                {
                    cal_net_amt();
                    if ($("#pur_count").value == "0")
                    {
                        alert("Select Item");
                        $('#txt_vendor').focus();
                        return false;
                    }

                    if ($('#txt_vendor').val() == '0')
                    {
                        alert("Select vendor");
                        $('#txt_vendor').focus();
                        return false;
                    } else if ($('#txt_item').val() == '0')
                    {
                        alert("Select item");
                        $('#txt_item').focus();
                        return false;
                    } else if ($('#txt_qty').val() == '')
                    {
                        alert("Enter qty");
                        $('#txt_qty').focus();
                        return false;
                    } else if ($('#txt_price').val() == '')
                    {
                        alert("Enter unit price");
                        $('#txt_price').focus();
                        return false;
                    }
                })

                $(document).ready(function () {
                    $(window).keydown(function (event)
                    {
                        if (event.keyCode == 13) {
                            event.preventDefault();
                            return false;
                        }
                    });
                });

            </script>
            <!--END MAIN WRAPPER -->
            <?php require_once 'includes/footer.php'; ?>    

        </div>
    </body>
</html>
