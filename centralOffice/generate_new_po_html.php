<?php
error_reporting(0);
require_once 'includes/session.php';
require_once 'includes/common_function.php';
$fin_year = date('y') . '-' . date('y', strtotime('+1Year'));
if (ltrim(date('m')) > 3) {
    $cd = date('y');
    $dd = $cd + 1;
} else {
    $dd = date('y');
    $cd = $dd - 1;
}
$fin_year_latest = $cd . '' . $dd;

$inc_no = getLastpurchaseOrder($fin_year_latest, $_SESSION['id']);
if (isset($inc_no) && !empty($inc_no)) {
    $po_no_int = $inc_no;
} else {
    $po_no_int = 0;
}
$po_no_increase = $po_no_int + 1;
$vendor_id = "";
// print_r($_SESSION);
$comp_details = getCompanyDetailsById($_SESSION['company_id']);

if ($fin_year_latest == '2425') {
    $purchase_noo = "AGRO" . $_SESSION['id'] . "" . $fin_year_latest . "" . $po_no_increase;
} else {
    $purchase_noo = $comp_details->prefix . $_SESSION['id'] . $fin_year_latest . $po_no_increase;
}
?>
<!DOCTYPE html>
<html lang="en">
    <?php require_once 'includes/header.php'; ?>

    <body class="no-skin">

    <style>
        /* :root {
            --primary-color: #2e7d32; 
            --secondary-color: #1976d2; 
            --bg-color: #f4f7f6;
            --card-bg: #ffffff;
            --border-color: #ddd;
            --text-color: #333;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            margin: 0;
            padding: 20px;
        } */

        .container {
            max-width: 1100px;
            margin: 0 auto;
            background: var(--card-bg);
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        h1 {
            color: var(--primary-color);
            text-align: center;
            font-style: italic;
            margin-bottom: 5px;
        }

        .divider {
            border-top: 2px dashed var(--border-color);
            margin: 10px 0 25px 0;
        }

        /* Grid Layout for Header Fields */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group.span-2 { grid-column: span 2; }

        label {
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 5px;
            color: #555;
        }

        input, select, textarea {
            padding: 8px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-size: 0.9rem;
        }

        input:focus, select:focus {
            outline: none;
            border-color: var(--secondary-color);
        }

        .po-no { color: #d32f2f; font-weight: bold; }

        /* Item Entry Section */
        .item-entry-grid {
            display: grid;
            grid-template-columns: 2fr repeat(7, 1fr);
            gap: 10px;
            background: #f9f9f9;
            padding: 15px;
            border-radius: 4px;
            align-items: end;
        }

        .btn-add {
            background-color: var(--secondary-color);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 15px;
            font-weight: bold;
        }

        /* Table Section */
        .list-title {
            color: var(--primary-color);
            text-align: center;
            font-style: italic;
            margin: 30px 0 15px 0;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th {
            background-color: #f1f1f1;
            text-align: left;
            padding: 12px;
            font-size: 0.9rem;
            border-bottom: 2px solid var(--border-color);
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #eee;
            font-size: 0.9rem;
        }

        /* Totals Section */
        .totals-grid {
            display: grid;
            grid-template-columns: repeat(9, 1fr);
            gap: 8px;
            margin-top: 20px;
        }

        .totals-grid input {
            background-color: #eee;
            text-align: center;
            font-weight: bold;
        }

        /* Bottom Section */
        .footer-notes {
            margin-top: 25px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .full-width { grid-column: span 2; }

        .btn-save {
            background-color: var(--secondary-color);
            color: white;
            border: none;
            padding: 12px 40px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: bold;
            margin-top: 30px;
        }

        @media (max-width: 768px) {
            .form-grid, .item-entry-grid, .totals-grid {
                grid-template-columns: 1fr 1fr;
            }
            .footer-notes { grid-template-columns: 1fr; }
        }
    </style>
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
                                        <a href="purchase_order.php?menu=11"><button class="btn btn-success">Purchase Order</button></a>
                                        <a href="purchase_order_clossed.php?menu=11"><button class="btn btn-danger">Closed Order</button></a>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <?php
                                        if (isset($_POST['btn_submit'])) {
                                            $data = array();
                                            $data['po_no'] = $_POST['txt_po_no'];
                                            $data['po_type'] = $_POST['po_type'];
                                            $data['inc_no'] = $po_no_increase;
                                            $data['po_date'] = date('Y-m-d', strtotime($_POST['txt_po_date']));
                                            $data['retailer_id'] = $_POST['Retailer_id'];
                                            $data['vendor_id'] = $_POST['txt_vendor'];
                                            $data['supplier_id'] = getVendorNameById($_POST['txt_vendor']);
                                            $data['supplier_name'] = getVendorNameById($_POST['txt_vendor']);
                                            $data['supplier_contact_person'] = $_POST['txt_person'];
                                            $data['supplier_contact_no'] = $_POST['txt_number'];
                                            $data['supplier_address'] = $_POST['txt_address'];
                                            $data['user_id'] = $_SESSION['id'];
                                            $data['company_id'] = getRetailerCompanyIdById($_POST['Retailer_id']);
                                            $data['quotation_no'] = $_POST['quotation_no'];
                                            $data['po_reference'] = $_POST['po_reference'];
                                            $data['quotation_date'] = date('Y-m-d', strtotime($_POST['quotation_date']));
                                            $data['financial_yr'] = $fin_year_latest;
                                            $data['tot_qty'] = $_POST['total_qty'];
                                            $data['pnf'] = $_POST['txt_pf'];
                                            $data['net_total'] = $_POST['txt_nettotal'];

                                            //                                            $data['cgst_per'] = $_POST['txt_cgst_per'];
                                            $data['cgst_amt'] = $_POST['txt_tot_gst'] / 2;
                                            //                                            $data['sgst_per'] = $_POST['txt_sgst_per'];
                                            $data['sgst_amt'] = $_POST['txt_tot_gst'] / 2;
                                            $data['freight'] = $_POST['txt_freight'];
                                            $data['discount'] = $_POST['txt_discount'];
                                            $data['grand_total'] = $_POST['txt_grandTotal'];
                                            $data['new_po_gst_flag'] = 1;
                                            $data['added_date'] = date('Y-m-d H:i:s');

                                            //                                            echo '<pre/>';
                                            //                                            print_r($data);
                                            //                                            exit;

                                            if (isset($_POST['txt_remarks']) && !empty($_POST['txt_remarks'])) {
                                                $data['remarks'] = $_POST['txt_remarks'];
                                            }

                                            $data['sub_total'] = $_POST['txt_subTotal'];
                                            $data['amount'] = $_POST['txt_amt'];
                                            if (isset($_POST['txt_term_delivery']) && !empty($_POST['txt_term_delivery'])) {
                                                $data['term_delivery'] = $_POST['txt_term_delivery'];
                                            }
                                            if (isset($_POST['txt_term_payment']) && !empty($_POST['txt_term_payment'])) {
                                                $data['term_payment'] = $_POST['txt_term_payment'];
                                            }
                                            $table_name = "purchase_order";

                                            $reslt = insert($table_name, $data);
                                            if ($reslt) {
//                                                if (isset($_POST['po_reference'])) {
//                                                    $upd_purchase_order_basic = array();
//                                                    $upd_purchase_order_basic['status'] = 1;
//                                                    $upd_purchase_order_basic['po_reference'] = $_POST['txt_po_no'];
//                                                    $where_purchase_order_basic = "status='0' and po_no='" . $_POST['po_reference'] . "'";
//                                                    update('purchase_order_basic', $upd_purchase_order_basic, $where_purchase_order_basic);
//                                                }
                                                $last_po_id = getLastpurchaseOrderId();

                                                $table_name_detail = "purchase_order_detail";
                                                $upd_arr = array();
                                                $upd_arr['id'] = $last_po_id;
                                                $upd_arr['status'] = 1;
                                                $whrr = "user_id = '" . $_SESSION['id'] . "' and status = '0'";
                                                update($table_name_detail, $upd_arr, $whrr);

                                                echo '<script>alert("PO Saved");window.location.href="generate_new_po.php?menu=11&success=1";</script>';
                                            } else {
                                                echo '<script>window.location.href="generate_new_po.php?menu=11&error=1";</script>';
                                            }
                                        }
                                        ?>
                                        <div>
                                           
<div class="container">
    <h1>Purchase Goods Order</h1>
    <div class="divider"></div>

    <div class="form-grid">
        <div class="form-group">
            <label>P.O. No.</label>
            <input type="text" class="po-no" value="AGRO1232413" readonly>
        </div>
        <div class="form-group">
            <label>PO Type</label>
            <select>
                <option>Purchase Order</option>
            </select>
        </div>
        <div class="form-group">
            <label>Retailer Name</label>
            <select><option>--Select Retailer--</option></select>
        </div>
        <div class="form-group">
            <label>P.O. Date</label>
            <input type="date" value="2023-04-17">
        </div>
        <div class="form-group span-2">
            <label>Supplier Name</label>
            <select>
                <option>GAYATRI FERTILIZERS</option>
            </select>
        </div>
        <div class="form-group">
            <label>Supplier Contact Person</label>
            <input type="text" placeholder="Alpesh Patel">
        </div>
        <div class="form-group">
            <label>Supplier Contact Number</label>
            <input type="tel" placeholder="9879772417">
        </div>
        <div class="form-group span-2">
            <label>Supplier Address</label>
            <textarea rows="2">12, SHIVASHRAY COMPLEX, PIJ BHAGOL, NADIAD-387001</textarea>
        </div>
    </div>

    <div class="item-entry-grid">
        <div class="form-group">
            <label>Item</label>
            <select><option>-- SELECT ITEM --</option></select>
        </div>
        <div class="form-group">
            <label>SKU</label>
            <input type="text" placeholder="Unit" disabled>
        </div>
        <div class="form-group">
            <label>QTY</label>
            <input type="number" value="0">
        </div>
        <div class="form-group">
            <label>Unit Price</label>
            <input type="number" value="0">
        </div>
        <div class="form-group">
            <label>Discount</label>
            <input type="number" value="0">
        </div>
        <div class="form-group">
            <label>GST %</label>
            <select><option>0</option></select>
        </div>
        <div class="form-group">
            <label>GST Amt</label>
            <input type="number" value="0" readonly>
        </div>
        <div class="form-group">
            <label>Net Amt</label>
            <input type="number" value="0" readonly>
        </div>
    </div>
    <button class="btn-add">Add Item</button>

    <h2 class="list-title">List of Items</h2>
    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th>Unit</th>
                <th>QTY</th>
                <th>Unit Price</th>
                <th>GST Rate</th>
                <th>GST Amount</th>
                <th>Net Amount</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>TEST ITEM</td>
                <td>KG</td>
                <td>20.00</td>
                <td>20.00</td>
                <td>0.00</td>
                <td>0.00</td>
                <td>400.00</td>
                <td><button style="color:red; border:none; background:none; cursor:pointer;">Delete</button></td>
            </tr>
        </tbody>
    </table>

    <div class="totals-grid">
        <div class="form-group"><label>QTY</label><input type="text" value="20"></div>
        <div class="form-group"><label>GST Total</label><input type="text" value="0"></div>
        <div class="form-group"><label>Sub Total</label><input type="text" value="400"></div>
        <div class="form-group"><label>P & F</label><input type="text" value="0"></div>
        <div class="form-group"><label>Net Total</label><input type="text" value="400"></div>
        <div class="form-group"><label>Amount</label><input type="text" value="400"></div>
        <div class="form-group"><label>Freight</label><input type="text" value="0"></div>
        <div class="form-group"><label>Discount</label><input type="text" value="0.00"></div>
        <div class="form-group"><label>Grand Total</label><input type="text" value="400" style="background:#fff9c4"></div>
    </div>

    <div class="footer-notes">
        <div class="form-group">
            <label>Quotation No & Date</label>
            <div style="display:flex; gap:10px;">
                <input type="text" style="flex:2" placeholder="qwert">
                <input type="date" style="flex:1" value="2023-04-17">
            </div>
        </div>
        <div class="form-group">
            <label>Terms of Delivery</label>
            <input type="text" value="FREE DOOR DELIVERY AT OUR FACTORY WITHIN 7 DAYS.">
        </div>
        <div class="form-group">
            <label>Terms of Payment</label>
            <input type="text" value="WITHIN 30 DYS.">
        </div>
        <div class="form-group">
            <label>Remarks</label>
            <textarea rows="2">werty</textarea>
        </div>
    </div>

    <button class="btn-save">Save P.O.</button>
</div>

                                            <div id="vendorData"></div>
                                        </div>
                                    </div>
                                </div><!-- /.row -->
                            </div>
                        </div><!-- /.row -->
                    </div><!-- /.page-content -->
                </div>
            </div><!-- /.main-content -->

            <script type="text/javascript">
                function showCartItems() {
                    $.ajax({
                        url: 'ajax_agro.php?menu=11',
                        method: 'post',
                        data: {
                            request_type: 'get_cart_items_data'
                        },
                        success: function (resp) {
                            //                            console.log(resp);
                            document.getElementById('pur_detail').innerHTML = resp;


                            document.getElementById("total_qty").value = document.getElementById("ttl_qty").value;

                            document.getElementById("txt_subTotal").value = document.getElementById("ttl_amuntt").value;

                            document.getElementById("txt_nettotal").value = ((document.getElementById("txt_pf").value == "0") ? Number(document.getElementById("txt_subTotal").value) : Number(document.getElementById("txt_subTotal").value) + Number(document.getElementById("txt_pf").value));

                            document.getElementById("txt_tot_gst").value = document.getElementById("gst_ttl_valu").value;

                            document.getElementById("txt_amt").value = Number(document.getElementById("txt_nettotal").value);

                            document.getElementById("txt_grandTotal").value = Math.round((document.getElementById("txt_nettotal").value == "") ? Number("0") : Number(document.getElementById("txt_nettotal").value) + Number(document.getElementById("txt_freight").value) - Number(document.getElementById("txt_discount").value));


                        }
                    });
                }
                showCartItems();

                function addCartIteam() {
                    if (document.getElementById("txt_item").value != "") {
                        if (isNaN(document.getElementById("txt_qty").value)) {
                            document.getElementById("txt_qty").focus();
                        } else if (Number(document.getElementById("txt_qty").value) <= 0) {
                            document.getElementById("txt_qty").focus();
                        } else if (isNaN(document.getElementById("txt_price").value)) {
                            document.getElementById("txt_price").focus();
                        } else if (Number(document.getElementById("txt_price").value) < 0) {
                            document.getElementById("txt_price").focus();
                        } else {

                            var table = document.getElementById("pur_detail");
                            var rowcount = table.rows.length;

                            var item_code = document.getElementById("txt_item").value;
                            var item_qty = document.getElementById("txt_qty").value;
                            var unit_price = document.getElementById("txt_price").value;
                            var gst_rate = document.getElementById("txt_gst").value;
                            var gst_amt = document.getElementById("txt_total_gst").value;
                            var net_amt = document.getElementById("txt_total").value;

                            $.ajax({
                                url: 'ajax_agro.php?menu=11',
                                method: 'post',
                                data: {
                                    request_type: 'add_po_cart_item',
                                    item_code: item_code,
                                    item_qty: item_qty,
                                    unit_price: unit_price,
                                    gst_rate: gst_rate,
                                    gst_amt: gst_amt,
                                    net_amt: net_amt
                                },
                                success: function (resp) {
                                    showCartItems();


                                    document.getElementById("txt_item").value = "";
                                    document.getElementById("txt_sku").value = "";
                                    document.getElementById("txt_qty").value = "";
                                    document.getElementById("txt_price").value = "";
                                    document.getElementById('txt_dis_unitprice').value = "0";
                                    document.getElementById("txt_total").value = "";
                                    document.getElementById("txt_gst").value = "";
                                    document.getElementById("txt_total_gst").value = "";

                                    document.getElementById("txt_item").focus();
                                }

                            });

                        }
                    } else {
                        alert('Please select Item....!');
                    }
                }

                function delete_cart_data(idd) {
                    $.ajax({
                        url: 'ajax_agro.php?menu=11',
                        method: 'post',
                        data: {
                            request_type: 'delete_cart_po_data',
                            idd: idd
                        },
                        success: function (resp) {
                            if (resp == 0) {
                                alert('Item Remove Successfully...!');
                                showCartItems();
                            }
                        }
                    });
                }





                show_detail();

                function new_purchase_click() {
                    document.getElementById("purchase_detail").form_type.value = "new";
                    document.getElementById("purchase_detail").purchase_id.value = "";
                    document.purchase_detail.submit();

                }

                function released_purchase_click() {
                    window.location.href = "release_purchase_order_list.php?menu=11";
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
                            url: 'ajax_new.php?menu=11',
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

                function show_detail() {
                    var vendor_id_ajit = document.getElementById("txt_vendor").value;
                    if (vendor_id_ajit != '') {
                        $(".loader").css("display", "block");
                        var xmlhttp;
                        var url = "ajax_agro.php?menu=11&type=vendor&id=" + vendor_id_ajit;
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
                        $(".loader").css("display", "none");
                        xmlhttp.open("GET", url, true);
                        xmlhttp.send();
                    }
                }

                function getVendorDetails() {
                    document.getElementById("txt_person").value = "";
                    document.getElementById("txt_number").value = "";
                    document.getElementById("txt_address").value = "";
                    var vendor_id = document.getElementById('txt_vendor').value;
                    if (vendor_id != '') {
                        $.ajax({
                            type: "POST",
                            url: "ajax.php?menu=1",
                            data: {
                                'types': 'getVendorDetils',
                                'vendor_id': vendor_id
                            },
                            success: function (data) {
                                $("#vendorData").html(data);
                            }
                        });
                    }
                }

                function item_unit() {
                    var str = document.getElementById('txt_item').value;
                    var res = str.split("(^)");

                    var xmlhttp;
                    var url = "ajax_agro.php?menu=11&type=itemunit&id=" + res[0];

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



                function cal_item_detail() {
                    if (document.getElementById("txt_qty").value == "") {
                        document.getElementById("txt_qty").value = "0";
                    } else if (isNaN(document.getElementById("txt_qty").value)) {
                        document.getElementById("txt_qty").value = "0";
                    }

                    if (document.getElementById("txt_price").value == "") {
                        document.getElementById("txt_price").value = "0";
                    } else if (isNaN(document.getElementById("txt_price").value)) {
                        document.getElementById("txt_price").value = "0";
                    }

                    var unit_price = document.getElementById("txt_price").value;
                    //                    var total_price = Number(after_discount_price) + gst_ttl_val;
                    var total_price = document.getElementById("txt_qty").value * Number(unit_price);
                    var txt_dis_unitprice = document.getElementById('txt_dis_unitprice').value;
                    var after_discount_price = Number(total_price) - Number(txt_dis_unitprice);
                    var gst_rate = document.getElementById('txt_gst').value;
                    var gst_ttl_val = (Number(after_discount_price) * gst_rate) / 100;

                    document.getElementById("txt_total_gst").value = gst_ttl_val;
                    document.getElementById("txt_total").value = (after_discount_price + gst_ttl_val).toFixed(2);
                }

                function cal_item_detail_bkp_09102023() {
                    if (document.getElementById("txt_qty").value == "") {
                        document.getElementById("txt_qty").value = "0";
                    } else if (isNaN(document.getElementById("txt_qty").value)) {
                        document.getElementById("txt_qty").value = "0";
                    }

                    if (document.getElementById("txt_price").value == "") {
                        document.getElementById("txt_price").value = "0";
                    } else if (isNaN(document.getElementById("txt_price").value)) {
                        document.getElementById("txt_price").value = "0";
                    }

                    var gst_rate = document.getElementById('txt_gst').value;
                    var gst_ttl_val = (Number(document.getElementById("txt_price").value) * gst_rate) / 100;
                    var total_price = Number(document.getElementById("txt_price").value) + gst_ttl_val;

                    document.getElementById("txt_total_gst").value = Number(document.getElementById("txt_qty").value) * gst_ttl_val;
                    document.getElementById("txt_total").value = (Number(document.getElementById("txt_qty").value) * total_price).toFixed(2);
                }

                function del_purchase(x) {
                    cal_item_detail();
                    var row = document.getElementById(x);
                    if (document.getElementById("txt_pf").value == "")
                        document.getElementById("txt_pf").value = "0";
                    if (document.getElementById("txt_freight").value == "")
                        document.getElementById("txt_freight").value = "0";
                    if (document.getElementById("txt_discount").value == "")
                        document.getElementById("txt_discount").value = "0";

                    document.getElementById("total_qty").value = Number(document.getElementById("total_qty").value) - Number(document.getElementById(x * 1000 + 4).value);
                    document.getElementById("txt_subTotal").value = Number(document.getElementById("txt_subTotal").value) - Number(document.getElementById(x * 1000 + 6).value);


                    document.getElementById("txt_nettotal").value = ((document.getElementById("txt_pf").value == "0") ? Number(document.getElementById("txt_subTotal").value) : Number(document.getElementById("txt_subTotal").value) + Number(document.getElementById("txt_pf").value));


                    document.getElementById("txt_tot_cgst").value = (Number(document.getElementById("txt_nettotal").value) * Number(document.getElementById("txt_cgst_per").value)) / 100;
                    document.getElementById("txt_tot_sgst").value = (Number(document.getElementById("txt_nettotal").value) * Number(document.getElementById("txt_sgst_per").value)) / 100;


                    document.getElementById("txt_amt").value = (Number(document.getElementById("txt_nettotal").value) + Number(document.getElementById("txt_tot_cgst").value) + Number(document.getElementById("txt_tot_sgst").value)).toFixed(2);

                    document.getElementById("txt_grandTotal").value = (Number(document.getElementById("txt_amt").value) + Number(document.getElementById("txt_freight").value) - Number(document.getElementById("txt_discount").value)).toFixed(2);


                    row.parentNode.removeChild(row);

                    var table = document.getElementById("pur_detail");
                    var rowcount = table.rows.length; //get table row count
                    document.getElementById("pur_count").value = rowcount - 1;
                }

                function cal_net_amt() {
                    if (document.getElementById("txt_pf").value == "") {
                        document.getElementById("txt_pf").value = "0";
                    }
                    if (document.getElementById("txt_freight").value == "") {
                        document.getElementById("txt_freight").value = "0";
                    }
                    if (document.getElementById("txt_discount").value == "") {
                        document.getElementById("txt_discount").value = "0";
                    }

                    document.getElementById("txt_nettotal").value = ((document.getElementById("txt_pf").value == "0") ? Number(document.getElementById("txt_subTotal").value) : Number(document.getElementById("txt_subTotal").value) + Number(document.getElementById("txt_pf").value));


                    //                    document.getElementById("txt_tot_cgst").value = (Number(document.getElementById("txt_nettotal").value) * Number(document.getElementById("txt_cgst_per").value)) / 100;
                    //                    document.getElementById("txt_tot_sgst").value = (Number(document.getElementById("txt_nettotal").value) * Number(document.getElementById("txt_cgst_per").value)) / 100;


                    //                    document.getElementById("txt_sgst_per").selectedIndex = document.getElementById("txt_cgst_per").selectedIndex;
                    //                    console.log(document.getElementById("txt_sgst_per").selectedIndex);

                    document.getElementById("txt_amt").value = Number(document.getElementById("txt_nettotal").value);

                    document.getElementById("txt_grandTotal").value = (Number(document.getElementById("txt_amt").value) + Number(document.getElementById("txt_freight").value) - Number(document.getElementById("txt_discount").value)).toFixed(2);

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