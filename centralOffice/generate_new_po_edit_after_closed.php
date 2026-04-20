<?php
error_reporting(E_ALL ^ E_NOTICE);
require_once 'includes/session.php';
require_once 'includes/common_function.php';

require_once 'includes/db.class';
$bdd = new db();


$fin_year = date('y') . '-' . date('y', strtotime('+1Year'));
if (ltrim(date('m')) > 3) {
    $cd = date('y');
    $dd = $cd + 1;
} else {
    $dd = date('y');
    $cd = $dd - 1;
}
$fin_year_latest = $cd . '' . $dd;

$vendor_id = "";


$comp_details = getCompanyDetailsById($_SESSION['company_id']);

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
                                    <a href="purchase_order.php?menu=11"><button class="btn btn-success">Purchase Order</button></a>
                                    <a href="purchase_order_clossed.php?menu=11"><button class="btn btn-danger">Closed Order</button></a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <?php
                                    if (isset($_POST['btn_submit'])) {

                                        $table_name = "purchase_order";
                                        $pur_id = $_POST['pur_id'];
                                        $orderData = $bdd->getPurchaseOrderbyId($pur_id);

                                        $data = array();
                                        $data['vendor_id'] = $_POST['txt_vendor'];
                                        $data['supplier_id'] = getVendorNameById($_POST['txt_vendor']);
                                        $data['supplier_name'] = getVendorNameById($_POST['txt_vendor']);
                                        $data['supplier_contact_person'] = $_POST['txt_person'];
                                        $data['supplier_contact_no'] = $_POST['txt_number'];
                                        $data['supplier_address'] = $_POST['txt_address'];


                                        $his_arr = array();
                                        $his_arr['po_no'] =  $orderData->po_no;
                                        $his_arr['po_id'] = $pur_id;
                                        $his_arr['old_vendor_id'] = $orderData->vendor_id;
                                        $his_arr['old_supplier_id'] = $orderData->supplier_id;
                                        $his_arr['old_supplier_name'] = $orderData->supplier_name;
                                        $his_arr['old_supplier_address'] = $orderData->supplier_address;
                                        $his_arr['new_vendor_id'] = $_POST['txt_vendor'];
                                        $his_arr['new_supplier_id'] = getVendorNameById($_POST['txt_vendor']);
                                        $his_arr['new_supplier_name'] = getVendorNameById($_POST['txt_vendor']);
                                        $his_arr['new_supplier_address'] = $_POST['txt_address'];
                                        $his_arr['update_date'] = date('Y-m-d H:i:s');
                                        $his_arr['update_by'] = $_SESSION['username'];
                                        insert('purchase_order_edit_history', $his_arr);
                                        //                                            echo '<pre/>';
                                        //                                            print_r($_POST);
                                        //                                            exit;

                                        if (isset($_POST['txt_remarks']) && !empty($_POST['txt_remarks'])) {
                                            $data['remarks'] = $_POST['txt_remarks'];
                                        }



                                        $where = "id='$pur_id'";

                                        $reslt = update($table_name, $data, $where);
                                        if ($reslt) {

                                            $new_order_data = $bdd->getPurchaseOrderbyId($pur_id);
                                            $insertData = array();
                                            $insertData['vendor_id'] = $new_order_data->vendor_id;
                                            $insertData['supplier_id'] = $new_order_data->supplier_id;
                                            $insertData['supplier_name'] = $new_order_data->supplier_name;
                                            $insertData['supplier_contact_person'] = $new_order_data->supplier_contact_person;
                                            $insertData['supplier_contact_no'] = $new_order_data->supplier_contact_no;
                                            $insertData['supplier_address'] = $new_order_data->supplier_address;
                                            $grnupwhr = "po_no = '" . $new_order_data->po_no . "'";
                                            update('inventory_grn', $insertData, $grnupwhr);

                                            $item_sr_arr = array();
                                            $item_sr_arr['vendor_id'] = $new_order_data->vendor_id;
                                            // $item_sr_whr = "po_no = '" . $orderData->po_no . "' and status = '0'";
                                            $item_sr_whr = "po_no = '" . $new_order_data->po_no . "' ";
                                            update('item_sr_master', $item_sr_arr, $item_sr_whr);

                                            echo '<script>alert("PO Details Updated..");window.location.href="purchase_order_clossed.php?menu=11";</script>';
                                        } else {
                                            echo '<script>alert("PO Details Update Error..");window.location.href="purchase_order_clossed.php?menu=11";</script>';
                                        }
                                    }

                                    if (isset($_GET['purchase_id'])) {
                                        $x_id = base64_decode($_GET['purchase_id']);
                                        $r4 = getPurchaseOrdergetItemCountById($x_id);
                                        $vendor_id = $r4->vendor_id;
                                        $ItemCount = getItemCount($r4->id);
                                    } else {
                                        $pur_count = 0;
                                    }
                                    $get_retailer_id = 0;
                                    $get_po_type = 0;
                                    if (isset($r4->po_no)) {
                                        $get_retailer_id = $r4->retailer_id;
                                        $get_po_type = $r4->po_type;
                                    }
                                    ?>
                                    <div>
                                        <form class="form-horizontal" method="post" action="" id="pur_entry">
                                            <table align="center" border="0" width="100%" bgcolor="#E5FFEB" style="border-collapse: separate; border-spacing: 0 15px;" bordercolor="#000000">
                                                <tbody>
                                                    <tr>
                                                        <td colspan="4" align="center"><i>
                                                                <font color="#336633" size="+3">Purchase Goods Order </font>
                                                            </i></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="4" align="center">==============================================================================</td>
                                                    </tr>
                                                    <!--readonly="readonly"-->
                                                    <tr align="center">
                                                        <th align="left">P.O. No. :
                                                            <input name='txt_po_no' id="txt_po_no" class="text" type="hidden" value="<?php echo $r4->po_no;  ?>">
                                                            <b class="red"><?php echo $r4->po_no; ?></b>
                                                        </th>
                                                        <th align="left">PO Type : <?php
                                                                                    if ($get_po_type == 1) {
                                                                                        echo '<b>Purchase Order</b>';
                                                                                    }
                                                                                    if ($get_po_type == 2) {
                                                                                        echo '<b>Credit Note</b>';
                                                                                    }
                                                                                    ?>
                                                        </th>
                                                        <th align="left">Retailer Name : <?php echo getRetailerById($get_retailer_id)->name; ?> </th>
                                                        <th align="left">P.O. Date : <?php echo date('d-m-Y', strtotime($r4->po_date)); ?> </th>

                                                    </tr>
                                                    <tr id="vendor_details" style="margin-top:15%;">
                                                        <th align="left">Supplier Name <br>
                                                            <select name="txt_vendor" id="txt_vendor" class="text" onchange="getVendorDetails();">
                                                                <option value="">--Select vendor--</option>
                                                                <?php foreach (getVendorActiveDetails() as $vendor) { ?>
                                                                    <option value="<?php echo $vendor->vendor_id; ?>" <?php
                                                                                                                        if ($vendor_id == $vendor->vendor_id) {
                                                                                                                            echo 'selected="elected"';
                                                                                                                        }
                                                                                                                        ?>><?php echo $vendor->vendor_name; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </th>
                                                        <div id="div_detail"></div>
                                                        <th align="left">Supplier Contact Person<br>
                                                            <input type="text" id="txt_person" name="txt_person" value="<?php echo $r4->supplier_contact_person; ?>" class="text" readonly="readonly" style="width:90%; height:30px" />
                                                        </th>
                                                        <th align="left">Supplier Contact Number<br>
                                                            <input type="text" id="txt_number" name="txt_number" value="<?php echo $r4->supplier_contact_no; ?>" class="text" readonly="readonly" style="width:90%; height:30px" />
                                                        </th>
                                                        <th align="left" colspan="2">Supplier Address<br>
                                                            <textarea name='txt_address' readonly="readonly" id="txt_address" style="width:80%;" rows='3'><?php echo $r4->supplier_address; ?></textarea>
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <td align="center" width="100%" colspan="6">
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <br>



                                            <!------------List of Items------------>
                                            <table border="0" class="table" bgcolor="#d2b4bc" bordercolor="#000000" style="margin-top:1%;border-collapse:separate;border-spacing:0 15px;border:none;" id="pur_detail">




                                            </table>


                                            <!------------Calculation of items------------>
                                            <table align="center" border="0" width="100%" bgcolor="#E5FFEB" bordercolor="#000000" style="border-collapse: separate; border-spacing: 0 15px;">
                                                <tbody>
                                                    <tr>
                                                        <th>QTY<br>
                                                            <input type="text" id="total_qty" name="total_qty" value="<?php echo $r4->tot_qty; ?>" style="text-align:center; width:50%; height:30px" readonly>
                                                        </th>

                                                        <th>GSTTotal<br>
                                                            <input type="text" id="txt_tot_gst" readonly="readonly" name="txt_tot_gst" value="<?php echo $r4->sgst_amt; ?>" style="width:50%;text-align:center; height:30px" onchange="cal_net_amt()">
                                                        </th>
                                                        <th>Sub Total<br>
                                                            <input type="text" style="text-align:center; width:65%; height:30px" id="txt_subTotal" name="txt_subTotal" onchange="cal_net_amt()" readonly="readonly" value="<?php echo $r4->sub_total; ?>">
                                                        </th>
                                                        <th>P & F<br>
                                                            <input type="text" style="text-align:center; width:50%; height:30px" id="txt_pf" name="txt_pf" class="text" value="<?php echo $r4->pnf; ?>" readonly onchange="cal_net_amt()">
                                                        </th>

                                                        <th>Net Total<br>
                                                            <input type="text" id="txt_nettotal" name="txt_nettotal" value="<?php echo $r4->net_total; ?>" readonly="readonly" style="text-align:center; width:70%; height:30px">
                                                        </th>


                                                        <th>Amount<br>
                                                            <input type="text" id="txt_amt" name="txt_amt" value="<?php echo $r4->amount; ?>" readonly="readonly" onchange="cal_net_amt()" style="text-align:center; width:65%; height:30px">
                                                        </th>

                                                        <th>Freight<br>
                                                            <input type="text" id="txt_freight" name="txt_freight" value="<?php echo $r4->freight; ?>" onchange="cal_net_amt()" readonly style="text-align:center; width:50%; height:30px">
                                                        </th>
                                                        <th>Discount<br>
                                                            <input type="text" id="txt_discount" name="txt_discount" value="<?php echo $r4->discount; ?>" onchange="cal_net_amt()" readonly style="text-align:center; width:55%; height:30px">
                                                        </th>
                                                        <th>Grand Total<br>
                                                            <input type="text" id="txt_grandTotal" name="txt_grandTotal" value="<?php echo $r4->grand_total; ?>" readonly="readonly" style="text-align:center; width:70%; height:30px">
                                                        </th>
                                                    </tr>
                                                </tbody>
                                            </table>

                                            <!------------Calculation of items------------>
                                            <table align="center" border="0" width="100%" bgcolor="#E5FFEB" bordercolor="#000000" style="border-collapse: separate; border-spacing: 0 15px;">
                                                <tbody>
                                                    <tr>
                                                        <th align="left">
                                                            Quotation No & Date: <br />
                                                            <input type="text" id="" name="quotation_no" required="required" placeholder="Quotation No" style="width:30%; height:30px" value="<?php echo $r4->quotation_no; ?>" readonly>
                                                            <input type="text" id="id-date-picker-1" required="required" name="quotation_date" value="<?php echo date('d-m-Y', strtotime($r4->quotation_date)); ?>" readonly>
                                                            <br />
                                                            Terms of Delivery : <br />
                                                            <input type="text" id="" name="txt_term_delivery" required="required" style="width:100%; height:30px" value="<?php echo $r4->term_delivery; ?>" readonly><br />
                                                            Terms of payment : <br />
                                                            <input type="text" id="" name="txt_term_payment" required="required" style="width:100%; height:30px" value="<?php echo $r4->term_payment; ?>" readonly><br />
                                                            Remarks<br>
                                                            <textarea class="remark" required="required" id="txt_remarks" name="txt_remarks" readonly><?php echo $r4->remarks; ?></textarea>
                                                        </th>
                                                        <th>

                                                            <input name="pur_id" id="pur_id" type="hidden" value="<?php echo $x_id; ?>" />
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <td align="center" width="100%" colspan="6">
                                                            <hr />
                                                            <!--<iframe name="bopg" align="middle" frameborder="0" width='100%' height="10px" src=""></iframe>-->
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>

                                            <!------------Add Item------------>
                                            <table>
                                                <tbody>
                                                    <tr>
                                                        <th align="left"><br>
                                                            <input type="submit" value="Update Details" name="btn_submit" id="btn_submit" class="button btn btn-primary" />
                                                        </th>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </form>
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
                var pur_id = document.getElementById('pur_id').value;
                //                    alert(pur_id);
                //                    return false;
                $.ajax({
                    url: 'ajax_agro.php?menu=11',
                    method: 'post',
                    data: {
                        request_type: 'get_cart_items_data_update',
                        pur_id: pur_id
                    },
                    success: function(resp) {
                        console.log(resp);
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

                var pur_id = document.getElementById('pur_id').value;

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
                                request_type: 'add_po_cart_item_update',
                                pur_id: pur_id,
                                item_code: item_code,
                                item_qty: item_qty,
                                unit_price: unit_price,
                                gst_rate: gst_rate,
                                gst_amt: gst_amt,
                                net_amt: net_amt
                            },
                            success: function(resp) {
                                showCartItems();


                                document.getElementById("txt_item").value = "";
                                document.getElementById("txt_sku").value = "";
                                document.getElementById("txt_qty").value = "";
                                document.getElementById("txt_price").value = "";
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
                //                    alert(idd);
                //                    return false;
                $.ajax({
                    url: 'ajax_agro.php?menu=11',
                    method: 'post',
                    data: {
                        request_type: 'delete_cart_po_data',
                        idd: idd
                    },
                    success: function(resp) {
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
                        success: function(result) {
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
                    xmlhttp.onreadystatechange = function() {
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
                        success: function(data) {
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
                xmlhttp.onreadystatechange = function() {
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
                //txt_discount
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

            $('#btn_submit1').click(function() {
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

            $(document).ready(function() {
                $(window).keydown(function(event) {
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