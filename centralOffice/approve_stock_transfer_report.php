<?php
require_once 'includes/session.php';
require_once 'includes/common_function.php';
$from_retailer = "";
$status = "";
$to_retailer = "";
if (isset($_POST['from_retailer'])) {
    $from_retailer = $_POST['from_retailer'];
}
if (isset($_POST['to_retailer'])) {
    $to_retailer = $_POST['to_retailer'];
}
if (isset($_POST['status'])) {
    $status = $_POST['status'];
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

                                        <div class="row">
                                            <h3 class="header-text"> Stock Transfer Approval Report.</h3>
                                            <hr />
                                            <form class="form-inline" action="" method="POST">
                                                <div class="form-group">
                                                    <label for="text">From Date:</label>
                                                    <input class="form-control date-picker" id="id-" name="date_1" type="text" value="<?php
                                                    if (isset($_POST['date_1'])) {
                                                        echo $_POST['date_1'];
                                                    } else {
                                                        echo date('d-m-Y');
                                                    }
                                                    ?>" data-date-format="dd-mm-yyyy" />
                                                </div>
                                                <div class="form-group">
                                                    <label for="text">To Date:</label>
                                                    <input class="form-control date-picker" id="" name="date_2"  type="text" value="<?php
                                                    if (isset($_POST['date_2'])) {
                                                        echo $_POST['date_2'];
                                                    } else {
                                                        echo date('d-m-Y');
                                                    }
                                                    ?>" data-date-format="dd-mm-yyyy" />
                                                </div>
                                                <div class="form-group">
                                                    <label for="text">From Retailer:</label>
                                                    <select name="from_retailer" class="form-control">
                                                        <option value="">--all--</option>
                                                        <?php foreach (getActiveRetailerDetails($company_id_in) as $retailer) { ?>
                                                            <option value="<?php echo $retailer->id; ?>" <?php
                                                            if ($from_retailer == $retailer->id) {
                                                                echo 'selected="selected"';
                                                            }
                                                            ?>><?php echo $retailer->name; ?></option>
                                                                <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="text">To Retailer:</label>
                                                    <select name="to_retailer" class="form-control">
                                                        <option value="">--all--</option>
                                                        <?php foreach (getActiveRetailerDetails($company_id_in) as $retailer) { ?>
                                                            <option value="<?php echo $retailer->id; ?>" <?php
                                                            if ($to_retailer == $retailer->id) {
                                                                echo 'selected="selected"';
                                                            }
                                                            ?>><?php echo $retailer->name; ?></option>
                                                                <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="text">Status:</label>
                                                    <select name="status" class="form-control">
                                                        <option value="">--all--</option>
                                                        <option value="8" <?php
                                                        if ($status == 8) {
                                                            echo 'selected="selected"';
                                                        }
                                                        ?>>Pending</option>
                                                        <option value="1" <?php
                                                        if ($status == 1) {
                                                            echo 'selected="selected"';
                                                        }
                                                        ?>>Approved</option>
                                                        <option value="7" <?php
                                                        if ($status == 7) {
                                                            echo 'selected="selected"';
                                                        }
                                                        ?>>Rejected</option>
                                                    </select>
                                                </div>
                                                <button type="submit" name="submit" class="btn btn-default">Filter</button>
                                            </form>
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
                                                                    <th width="" align="left">#</th>
                                                                    <th width="" align="left">From Retailer</th>
                                                                    <th width="" align="left">To Retailer</th>
                                                                    <th width="" align="left">Distance (km)</th>
                                                                    <th width="" align="left">Item</th>
                                                                    <th width="" align="left">Order No</th>
                                                                    <th width="" align="left">Qty</th>
                                                                    <th width="" align="left">PO Basic Price</th>
                                                                    <th width="" align="left">Date</th>
                                                                    <th width="" align="left">ChallanNo</th>
                                                                    <th width="" align="left">BatchNo</th>
                                                                    <th width="" align="left">ExpireDate</th>
                                                                    <th width="" align="left">Status</th>
                                                                    <th width="" align="left">Approve User</th>
                                                                    <th width="" align="left">Challan Copy</th>
                                                                    <th width="" align="left">Approv/Reject Date</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $status = 0;
                                                                $i = 1;

                                                                if (isset($_POST['submit'])) {
                                                                    $date_1 = date("Y-m-d", strtotime($_POST['date_1']));
                                                                    $date_2 = date("Y-m-d", strtotime($_POST['date_2']));
                                                                    $from_retailer = "";
                                                                    $status = "";
                                                                    $to_retailer = "";
                                                                    if (isset($_POST['from_retailer'])) {
                                                                        $from_retailer = $_POST['from_retailer'];
                                                                    }
                                                                    if (isset($_POST['to_retailer'])) {
                                                                        $to_retailer = $_POST['to_retailer'];
                                                                    }
                                                                    if (isset($_POST['status'])) {
                                                                        $status = $_POST['status'];
                                                                    }
                                                                    $getOrderRequest = getApprovedStockRequestFilterByDate($from_retailer, $to_retailer, $status, $company_id_in, $date_1, $date_2);
                                                                    if (count($getOrderRequest) > 0 && !empty($getOrderRequest)) {
                                                                        foreach ($getOrderRequest as $row) {
                                                                            $retailer_inwd_data = getInwardedOrderSattus($row->retailer_id, $row->order_no, $row->item_code);
                                                                            if (isset($retailer_inwd_data->retailer_inwd_flg)) {
                                                                                $retailer_inwd_flg = $retailer_inwd_data->retailer_inwd_flg;
                                                                            } else {
                                                                                $retailer_inwd_flg = "";
                                                                            }
                                                                            $user_id = $row->user_id;
                                                                            $status = '';
                                                                            $approve_datetime = '';
                                                                            if ($row->deleted == 1) {
                                                                                $status = '<b style="color:red;">Deleted By Central Team. user ' . getUserNameById($user_id) . '</b>';
                                                                                $approve_datetime = date('d-M-Y H:i', strtotime($row->approve_datetime));
                                                                            } else if ($retailer_inwd_flg == 1) {
                                                                                $status = '<b style="color:blue;">Inwarded</b>';
                                                                                $approve_datetime = date('d-M-Y H:i', strtotime($retailer_inwd_data->retailer_inwd_date));
                                                                            } else if ($retailer_inwd_flg == 7) {
                                                                                $status = '<b style="color:red;">Rejected By Store.</b>';
                                                                                $approve_datetime = date('d-M-Y H:i', strtotime($retailer_inwd_data->retailer_inwd_date));
                                                                            } else if ($row->ctrl_off_flag == 0) {
                                                                                $status = '<b style="color:blue;">Approval Pending</b>';
                                                                            } else if ($row->ctrl_off_flag == 1 && $row->status == 1) {
                                                                                $status = '<b style="color:green;">Approved by ' . getUserNameById($user_id) . ' & Pending for dispatch</b>';
                                                                                $approve_datetime = date('d-M-Y H:i', strtotime($row->approve_datetime));
                                                                            } else if ($row->ctrl_off_flag == 1 && $row->status == 2) {
                                                                                $status = '<b style="color:green;">Dispatched & Inwarded</b>';
                                                                                $approve_datetime = date('d-M-Y H:i', strtotime($row->approve_datetime));
                                                                            } else if ($row->ctrl_off_flag == 7) {
                                                                                $status = '<b style="color:red;">Rejected by Store in-charge</b>';
                                                                                $approve_datetime = date('d-M-Y H:i', strtotime($row->approve_datetime));
                                                                            } else if ($row->status == 7) {
                                                                                $status = '<b style="color:red;">Rejected by Store in-charge</b>';
                                                                                $approve_datetime = date('d-M-Y H:i', strtotime($row->approve_datetime));
                                                                            }

//                                                                            $total_price = get_total_price_by_frm_retailer_id_and_itemcode($row->frm_retailer_id, $row->item_code);
//                                                                            $total_price = getItemPurchasePriceDetails($row->frm_retailer_id, $row->item_code, $row->batch_no);
                                                                            $total_price = getInwardedOrderSattus($row->retailer_id, $row->order_no, $row->item_code);


                                                                            echo "<tr>"
                                                                            . "<td>" . $i . "</td>"
                                                                            . "<td>" . getRetailerNameById($row->frm_retailer_id) . "</td>"
                                                                            . "<td>" . getRetailerNameById($row->retailer_id) . "</td>"
                                                                            . "<td>" . getFromToRetailerDistance($row->frm_retailer_id, $row->retailer_id) . "</td>"
                                                                            . "<td>" . getItemNameByItemCode($row->item_code) . "</td>"
                                                                            . "<td>" . $row->order_no . "</td>"
                                                                            . "<td>" . $row->req_qty . "</td>"
                                                                            . "<td>" . $total_price->po_basic . "</td>"
                                                                            . "<td>" . date('d-M-Y H:i', strtotime($row->add_datetime)) . "</td>"
                                                                            . "<td>" . $row->challan_no . "</td>"
                                                                            . "<td>" . $row->batch_no . "</td>"
                                                                            . "<td>" . $row->expire_date . "</td>"
                                                                            . "<td>" . getUserNameById($user_id) . "</td>"
                                                                            . "<td>" . $status . "</td>";
                                                                            if ($row->challan_copy != '0') {
                                                                                echo "<td><a href='http://agrosindia.in/retailer/challan_copy/" . $row->challan_copy . "' target='_blank'>Download</a></td>";
                                                                            } else {
                                                                                echo '<td>No File</td>';
                                                                            }
                                                                            echo "<td>" . $approve_datetime . "</td>"
                                                                            . "</tr>";
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

            <script type="text/javascript">
                show_detail();

                function new_purchase_click() {
                    document.getElementById("purchase_detail").form_type.value = "new";
                    document.getElementById("purchase_detail").purchase_id.value = "";
                    document.purchase_detail.submit();

                }

                function released_purchase_click() {
                    window.location.href = "release_purchase_order.php";
                }

                function edit_purchase(x) {
                    document.getElementById("purchase_detail").form_type.value = "edit";
                    document.getElementById("purchase_detail").purchase_id.value = x;
                    document.purchase_detail.submit();
                }

                function deleteItem(key) {
                    if (confirm("Are you sure you want to Delete this?")) {
                        $.ajax({
                            type: 'POST',
                            url: 'ajax_new.php',
                            data: {
                                'key': key,
                                'type': 'removeSessionItem'
                            },
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
                            url: 'release_purchase_order.php',
                            data: {
                                'purchase_id': purchase_id
                            },
                            success: function (result) {
                                result = $.trim(result);
                                if (result) {
                                    window.location.href = "release_purchase_order.php?menu=11&success=realese";
                                } else {
                                    alert('Something Wrong Try Again.');
                                }
                            }
                        });
                    }
                }

                function approveRequestOrder(order_item_id) {
                    if (confirm("Are you sure you want to approve this item?")) {
                        $.ajax({
                            type: 'POST',
                            url: 'ajax.php',
                            data: {
                                'types': 'approveRequest',
                                'order_item_id': order_item_id
                            },
                            success: function (result) {
                                result = $.trim(result);
                                if (result == 1) {
                                    alert('Stock Approve Successfully.');
                                    window.location.href = "approve_stock_transfer.php?menu=21";
                                } else {
                                    alert('Something Wrong Try Again.');
                                    window.location.href = "approve_stock_transfer.php?menu=21";
                                }
                            }
                        });
                    }
                }

                function copy_purchase(purchase_id) {
                    if (confirm("Are you sure you want to copy this?")) {
                        $.ajax({
                            type: 'POST',
                            url: 'copy_purchase_order.php',
                            data: {
                                'purchase_id': purchase_id
                            },
                            success: function (result) {
                                result = $.trim(result);
                                if (result == 1) {
                                    window.location.href = "purchase_order.php?menu=11&success=copy";
                                } else {
                                    alert('Something Wrong Try Again.');
                                    window.location.href = "purchase_order.php?menu=11&failure=1";
                                }
                            }
                        });
                    }
                }

                function delete_purchase(purchase_id) {
                    if (confirm("Are you sure you want to Delete this?")) {
                        $.ajax({
                            type: 'POST',
                            url: 'delete_purchase_order.php',
                            data: {
                                'purchase_id': purchase_id
                            },
                            success: function (result) {
                                result = $.trim(result);
                                if (result == 1) {
                                    window.location.href = "purchase_order.php?menu=11&success=delete";
                                } else {
                                    alert('Something Wrong Try Again.');
                                    window.location.href = "purchase_order.php?menu=11&failure=1";
                                }
                            }
                        });
                    }
                }

                function show_detail() {
                    var vendor_id_ajit = document.getElementById("txt_vendor").value;
                    if (vendor_id_ajit != '') {
                        var xmlhttp;
                        var url = "ajax_agro.php?type=vendor&id=" + vendor_id_ajit;

                        document.getElementById("div_detail").innerHTML = "";

                        if (window.XMLHttpRequest) {
                            xmlhttp = new XMLHttpRequest();
                        } else {
                            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                        }
                        xmlhttp.onreadystatechange = function () {
                            if (xmlhttp.readyState == 4 && (xmlhttp.status >= 200 && xmlhttp.status <= 299)) {
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

                function item_unit() {
                    var str = document.getElementById('txt_item').value;
                    var res = str.split("(^)");

                    var xmlhttp;
                    var url = "ajax_agro.php?type=itemunit&id=" + res[0];

                    document.getElementById("div_detail").innerHTML = "";

                    if (window.XMLHttpRequest) {
                        xmlhttp = new XMLHttpRequest();
                    } else {
                        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                    }
                    xmlhttp.onreadystatechange = function () {
                        if (xmlhttp.readyState == 4 && (xmlhttp.status >= 200 && xmlhttp.status <= 299)) {
                            document.getElementById("div_detail").innerHTML = xmlhttp.responseText;
                            document.getElementById("txt_sku").value = document.getElementById("ajax_sku").value;
                        }
                    }
                    xmlhttp.open("GET", url, true);
                    xmlhttp.send();
                }

                function addRow() {
                    cal_item_detail();

                    if (document.getElementById("txt_item").value != "") {
                        if (isNaN(document.getElementById("txt_qty").value)) {
                            document.getElementById("txt_qty").focus();
                        } else if (Number(document.getElementById("txt_qty").value) <= 0) {
                            document.getElementById("txt_qty").focus();
                        } else if (isNaN(document.getElementById("txt_price").value)) {
                            document.getElementById("txt_price").focus();
                        } else if (Number(document.getElementById("txt_price").value) < 0) {
                            document.getElementById("txt_price").focus();
                        }

                        //txt_item		txt_sku		txt_qty		txt_price		txt_total
                        else {

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
                            cell7.innerHTML = "<button class='btn btn-danger btn-xs' onclick='javascript: del_purchase(" + rowid + ")'>Delete</button>";


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

                function cal_item_detail() {
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

                function del_purchase(x) {
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

                function cal_net_amt() {
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

                $('#btn_submit1').click(function () {
                    cal_net_amt();

                    if ($("#pur_count").value == "0") {
                        alert("Select Item");
                        $('#txt_vendor').focus();
                        return false;
                    }

                    if ($('#txt_vendor').val() == '0') {
                        alert("Select vendor");
                        $('#txt_vendor').focus();
                        return false;
                    } else if ($('#txt_item').val() == '0') {
                        alert("Select item");
                        $('#txt_item').focus();
                        return false;
                    } else if ($('#txt_qty').val() == '') {
                        alert("Enter qty");
                        $('#txt_qty').focus();
                        return false;
                    } else if ($('#txt_price').val() == '') {
                        alert("Enter unit price");
                        $('#txt_price').focus();
                        return false;
                    }
                })

                $(document).ready(function () {
                    $(window).keydown(function (event) {
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