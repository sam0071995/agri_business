<?php
require_once 'includes/session.php';
require_once 'includes/common_function.php';


$user_id = $_SESSION['id'];
$ItemCount = 0;
$vendor_id = "";
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
                                        <!--<a href="generate_return_po_report.php?menu=392"><button class="btn btn-success">Report</button></a>-->
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <?php
                                        if (isset($_POST['btn_submit'])) {
                                            $retailer_id = $_POST['txt_vendor'];

                                            $fin_year = date('y') . '-' . date('y', strtotime('+1Year'));
                                            if (ltrim(date('m')) > 3) {
                                                $cd = date('y');
                                                $dd = $cd + 1;
                                            } else {
                                                $dd = date('y');
                                                $cd = $dd - 1;
                                            }
                                            $fin_year_latest = $cd . '' . $dd;

                                            $inc_no = getLastReturnpurchaseOrderIncNo($fin_year_latest, $retailer_id);
                                            if (isset($inc_no) && !empty($inc_no)) {
                                                $po_no_int = $inc_no;
                                            } else {
                                                $po_no_int = 0;
                                            }
                                            $po_no_increase = $po_no_int + 1;

                                            $first_3cahr = substr(getRetailerById($retailer_id)->name, 0, 3);

                                            $user_id = $_SESSION['id'];
                                            $txt_po_no = "PORET" . $first_3cahr . "" . $fin_year_latest . "" . $po_no_increase;
                                            $data = array();
                                            $data['po_no'] = $txt_po_no;
                                            $data['po_type'] = $_POST['po_type'];
                                            $data['inc_no'] = $po_no_increase;
                                            $data['po_date'] = date('Y-m-d', strtotime($_POST['txt_po_date']));
                                            $data['retailer_id'] = $retailer_id;
                                            $data['vendor_id'] = 0;
                                            $data['user_id'] = $_SESSION['id'];
                                            $data['company_id'] = getRetailerCompanyIdById($_POST['txt_vendor']);
                                            $data['quotation_no'] = $_POST['quotation_no'];
                                            $data['quotation_date'] = date('Y-m-d', strtotime($_POST['quotation_date']));
                                            $data['financial_yr'] = $fin_year_latest;
                                            $data['tot_qty'] = $_POST['total_qty'];
                                            $data['pnf'] = $_POST['txt_pf'];
                                            $data['net_total'] = $_POST['txt_nettotal'];
                                            $data['cgst_per'] = $_POST['txt_cgst_per'];
                                            $data['cgst_amt'] = $_POST['txt_tot_cgst'];
                                            $data['sgst_per'] = $_POST['txt_sgst_per'];
                                            $data['sgst_amt'] = $_POST['txt_tot_sgst'];
                                            $data['freight'] = $_POST['txt_freight'];
                                            $data['grand_total'] = $_POST['txt_grandTotal'];

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
                                            $table_name = "purchase_order_return";
                                            $reslt = insert($table_name, $data);
                                            if ($reslt) {
                                                $table_name_detail = "purchase_order_return_detail";
                                                $get_cart_data = getReturnPurchaseOrderDetailsByretailerId($retailer_id, $user_id);
                                                $last_po_id = getLastReturnpurchaseOrderId();

                                                $upd_arr = array();
                                                $upd_arr['id'] = $last_po_id;
                                                $upd_arr['status'] = 1;
                                                $upd_arr['po_no'] = $txt_po_no;
                                                $whrr = "user_id = '$user_id' and status = '0' and retailer_id = '$retailer_id'";
                                                update($table_name_detail, $upd_arr, $whrr);

                                                foreach ($get_cart_data as $cartdata) {
                                                    $current_stock = getCurrentStockByRetailerIdAndItemCode($retailer_id, $cartdata->item_id);
                                                    $issued_stock = getIssuedStockByRetailerIdAndItemCode($retailer_id, $cartdata->item_id);

                                                    $stck_arr = array();
                                                    $stck_arr['issued_stock'] = ($issued_stock + $cartdata->qty);
                                                    $stck_arr['current_stock'] = ($current_stock - $cartdata->qty);
                                                    $stckwhr = "item_code = '" . $cartdata->item_id . "' and retailer_id = '$retailer_id'";
                                                    $updstck = update('retailer_inventory_master', $stck_arr, $stckwhr);

                                                    // for update in item_sr_master table=========================
                                                    $itemsr_arr = array();
                                                    $itemsr_arr['status'] = 7;
                                                    $itemsr_arr['order_no'] = $txt_po_no;
                                                    $itemsr_arr['block_datetime'] = date('Y-m-d H:i:s');
                                                    $itemsr_arr['remarks'] = "BLOCK AS PER RETURN PO AT " . date('Y-m-d H:i:s');
                                                    $whr_blck = "batch_no = '" . $cartdata->batch_no . "' and retailer_id = '$retailer_id' and status = '0' and item_code = '" . $cartdata->item_id . "'";
                                                    $upd_item_srtbl = updateIn('item_sr_master', $itemsr_arr, $whr_blck, $cartdata->qty);

                                                    // for update in item_sr_master table=========================


                                                    $hiss_arr = array();
                                                    $hiss_arr['batch_no'] = $cartdata->batch_no;
                                                    $hiss_arr['item_code'] = $cartdata->item_id;
                                                    $hiss_arr['retailer_id'] = $retailer_id;
                                                    $hiss_arr['po_id'] = $last_po_id;
                                                    $hiss_arr['po_no'] = $txt_po_no;
                                                    $hiss_arr['qty'] = $cartdata->qty;
                                                    $hiss_arr['current_stock'] = $current_stock;
                                                    $hiss_arr['issued_stock'] = $issued_stock;
                                                    $hiss_arr['update_date'] = date('Y-m-d H:i:s');
                                                    $hiss_arr['status'] = 0;
                                                    insert('purchase_order_return_stock_history', $hiss_arr);
                                                }

                                                echo '<script>alert("Return PO Saved");window.location.href="generate_return_po_new.php?menu=392&success=1";</script>';
                                            } else {
                                                echo '<script>window.location.href="generate_return_po_new.php?menu=392&error=1";</script>';
                                            }
                                        }

                                        $pur_count = 0;

                                        $get_retailer_id = 0;
                                        ?>
                                        <div>
                                            <form class="form-horizontal" method="post" action="" id="pur_entry">
                                                <table align="center" border="0" width="100%" bgcolor="#E5FFEB" bordercolor="#000000">
                                                    <tr>
                                                        <td colspan="4" align="center"><i>
                                                                <font color="#336633" size="+3">Return Purchase Goods Order </font>
                                                            </i></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="4" align="center">==============================================================================</td>
                                                    </tr>
                                                    <!--readonly="readonly"-->
                                                    <tr align="center">
                                                        <th align="left">P.O. Date <br>
                                                            <input type="text" class="date-picker" id="id-date-picker-1" name="txt_po_date" value="<?php echo date('d-m-Y'); ?>" />
                                                        </th>
                                                        <th align="left">Po Type <br>
                                                            <select name="po_type" id="po_type" class="text">
                                                                <option value="">--Select PO Type--</option>
                                                                <option value="1">Damage Return</option>
                                                                <option value="2">Outward to Agriculture Officer</option>
                                                                <option value="3">DEMO Given</option>
                                                            </select>

                                                        </th>
                                                        <th align="left">Retailer Name <br>
                                                            <select name="txt_vendor" id="txt_vendor" class="text">
                                                                <option value="">--Select retailer--</option>
                                                                <?php foreach (getActiveRetailerDetails($company_id_in) as $retailer) { ?>
                                                                    <option value="<?php echo $retailer->id; ?>" <?php
                                                                    if ($vendor_id == $retailer->id) {
                                                                        echo 'selected="selected"';
                                                                    }
                                                                    ?>><?php echo $retailer->name; ?></option>
                                                                        <?php } ?>
                                                            </select>

                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <td align="center" width="100%" colspan="6">
                                                        </td>
                                                    </tr>
                                                </table>

                                                <?php //print_r(getRetailerActiveItemsList($_SESSION['id'])); 
                                                ?>

                                                <!------------Add Item------------>
                                                <table align="center" border="0" width="100%" bgcolor="#ccc" bordercolor="#000000">
                                                    <tr>
                                                        <th>Item<br>
                                                            <select class="chosen-select" size="8" name="txt_item" id="txt_item" onchange="item_unit();" style="height:30px;width:200px;">
                                                                <option value='0'>-- SELECT ITEM --</option>
                                                                <?php
                                                                foreach (getRetailerActiveItemsList($_SESSION['id']) as $row) {
                                                                    echo "<option value='$row->item_code'>$row->item_desc</option>";
                                                                }
                                                                ?>
                                                            </select>
                                                        </th>
                                                        <th>BatchNo<br>
                                                            <select class="chosen" name="txt_batch_no" id="txt_batch_no" style="height:30px;width:200px;">
                                                                <option value='0'>-- SELECT BATCH NO --</option>
                                                            </select>
                                                        </th>
                                                        <th>SKU<br>
                                                            <input type="text" id="txt_sku" size="5" name="txt_sku" class="text" style="text-align:center; height:30px" value="" readonly="readonly" placeholder="Unit">
                                                        </th>
                                                        <th>QTY<br>
                                                            <input type="hidden" id="current_total_qty" name="current_total_qty" class="current_total_qty" style="text-align:center; width:50%; height:30px" readonly>
                                                            <input type="text" id="txt_qty" size="5" name="txt_qty" class="text txt_cls_qty" value="0" onchange="cal_item_detail()">
                                                        </th>
                                                        <th>Unit Price<br>
                                                            <input type="text" id="txt_price" size="5" name="txt_price" class="text" value="0" onchange="cal_item_detail()">
                                                        </th>
                                                        <th>Net Amount<br>
                                                            <input type="text" id="txt_total" size="5" name="txt_total" class="text" value="0" readonly="readonly">
                                                        </th>
                                                        <th>Return Date<br>
                                                            <input type="date" id="delivry_date" size="5" name="delivry_date" class="text" value="<?php echo date('Y-m-d'); ?>">
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th align="left"><br>
                                                            <button type="button" onclick="addRow_new();" id="btn_add" class="button btn btn-primary">Add Item</button>
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <td align="center" width="100%" colspan="6">
                                                            <hr />
                                                        </td>
                                                    </tr>
                                                </table>

                                                <!------------List of Items------------>
                                                <table border="0" class="table table-bordered" width="100%" bgcolor="#d2b4bc" bordercolor="#000000" style="margin-top:3%" id="pur_detail">



                                                </table>

                                                <!------------Calculation of items------------>
                                                <table align="center" border="0" width="100%" bgcolor="#E5FFEB" bordercolor="#000000" style="margin-top:1%">
                                                    <tr>
                                                        <th>QTY<br>


                                                            <input type="text" id="total_qty" name="total_qty" value="<?php
                                                            echo "0";
                                                            ?>" style="text-align:center; width:50%; height:30px" readonly>
                                                        </th>
                                                        <th>Sub Total<br>
                                                            <input type="text" style="text-align:center; width:65%; height:30px" id="txt_subTotal" name="txt_subTotal" onchange="cal_net_amt()" readonly="readonly" value="<?php
                                                            echo "0";
                                                            ?>">
                                                        </th>
                                                        <th>P & F<br>
                                                            <input type="text" style="text-align:center; width:50%; height:30px" id="txt_pf" name="txt_pf" class="text" value="<?php
                                                            echo "0";
                                                            ?>" onchange="cal_net_amt()">
                                                        </th>
                                                        <th>Net Total<br>
                                                            <input type="text" id="txt_nettotal" name="txt_nettotal" value="<?php
                                                            echo "0";
                                                            ?>" readonly="readonly" style="text-align:center; width:70%; height:30px">
                                                        </th>
                                                        <th>CGST (%)<br>
                                                            <select name="txt_cgst_per" id="txt_cgst_per" style="width:35%; height:30px" onchange="cal_net_amt()">

                                                                <option value='0'>0</option>
                                                                <option value='2.5'>2.5</option>
                                                                <option value='6'>6</option>
                                                                <option value='9'>9</option>
                                                                <option value='14'>14</option>

                                                            </select>
                                                            <input type="text" id="txt_tot_cgst" readonly="readonly" name="txt_tot_cgst" value="<?php
                                                            echo "0";
                                                            ?>" style="width:50%;text-align:center; height:30px" onchange="cal_net_amt()">
                                                        </th>
                                                        <th>SGST (%)<br>
                                                            <select name="txt_sgst_per" id="txt_sgst_per" style="width:35%; height:30px" onchange="cal_net_amt()">

                                                                <option value='0'>0</option>
                                                                <option value='2.5'>2.5</option>
                                                                <option value='6'>6</option>
                                                                <option value='9'>9</option>
                                                                <option value='14'>14</option>

                                                            </select>
                                                            <input type="text" id="txt_tot_sgst" readonly="readonly" name="txt_tot_sgst" value="<?php
                                                            echo "0";
                                                            ?>" style="width:50%;text-align:center; height:30px" onchange="cal_net_amt()">
                                                        </th>

                                                        <th>Amount<br>
                                                            <input type="text" id="txt_amt" name="txt_amt" value="<?php
                                                            echo "0";
                                                            ?>" readonly="readonly" onchange="cal_net_amt()" style="text-align:center; width:65%; height:30px">
                                                        </th>

                                                        <th>Freight<br>
                                                            <input type="text" id="txt_freight" name="txt_freight" value="<?php
                                                            echo "0";
                                                            ?>" onchange="cal_net_amt()" style="text-align:center; width:50%; height:30px">
                                                        </th>
                                                        <th>Grand Total<br>
                                                            <input type="text" id="txt_grandTotal" name="txt_grandTotal" value="<?php
                                                            echo "0";
                                                            ?>" readonly="readonly" style="text-align:center; width:70%; height:30px">
                                                        </th>
                                                    </tr>
                                                </table>
                                                <input name="pur_count" id="pur_count" type="hidden" value="<?php echo $ItemCount; ?>" />

                                                <!------------Calculation of items------------>
                                                <table align="center" border="0" width="100%" bgcolor="#E5FFEB" bordercolor="#000000">
                                                    <tr>
                                                        <th align="left">
                                                            Quotation No & Date: <br />
                                                            <input type="text" id="" name="quotation_no" required="required" placeholder="Quotation No" style="width:30%; height:30px" value="<?php
                                                            echo "";
                                                            ?>">
                                                            <input type="text" class="date-picker" id="id-date-picker-1" required="required" name="quotation_date" value="<?php
                                                            echo date('d-m-Y');
                                                            ?>">
                                                            <br />
                                                            Terms of Delivery : <br />
                                                            <input type="text" id="" name="txt_term_delivery" required="required" style="width:100%; height:30px" value="<?php
                                                            echo "FREE DOOR DELIVERY AT OUR FACTORY WITHIN 7 DAYS.";
                                                            ?>"><br />
                                                            Terms of payment : <br />
                                                            <input type="text" id="" name="txt_term_payment" required="required" style="width:100%; height:30px" value="<?php
                                                            echo "WITHIN 30 DYS.";
                                                            ?>"><br />
                                                            Remarks<br>
                                                            <textarea class="remark" required="required" id="txt_remarks" name="txt_remarks"></textarea>
                                                        </th>
                                                        <th>
                                                            <input name="frm_type" id="frm_type" type="hidden" value="<?php
                                                            if (!isset($x_id)) {
                                                                echo "new";
                                                            } else {
                                                                echo 'edit';
                                                            }
                                                            ?>" />
                                                            <input name="pur_id" type="hidden" value="<?php
                                                            if (isset($x_id)) {
                                                                echo $x_id;
                                                            }
                                                            ?>" />
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <td align="center" width="100%" colspan="6">
                                                            <hr />
                                                            <!--<iframe name="bopg" align="middle" frameborder="0" width='100%' height="10px" src=""></iframe>-->
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="">
                                                            <input type="submit" class="button btn btn-primary" value="Save." id="btn_submit" name="btn_submit" />
                                                        </td>
                                                    </tr>
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

            <?php require_once 'includes/footer.php'; ?>
            <script type="text/javascript">
                //                show_detail();
                function show_return_po_cart_data() {
                    var txt_retailer = $("#txt_vendor").val();
                    if (txt_retailer == '') {
                        alert("Select Retailer.");
                        $("#txt_vendor").focus();
                        return false;
                    }
                    $.ajax({

                        url: 'ajax_for_po.php?menu=1',
                        method: 'post',
                        data: {
                            type: 'show_return_po_cart_items',
                            txt_retailer: txt_retailer
                        },
                        success: function (reslt) {
                            document.getElementById('pur_detail').innerHTML = reslt;
                            document.getElementById("total_qty").value = document.getElementById("ttl_qty").value;
                            document.getElementById("txt_subTotal").value = document.getElementById("ttl_amt").value;
                            document.getElementById("txt_nettotal").value = document.getElementById("txt_subTotal").value;
                            document.getElementById("txt_grandTotal").value = Math.round((document.getElementById("txt_nettotal").value == "") ? Number("0") : Number(document.getElementById("txt_nettotal").value) + Number(document.getElementById("txt_tot_cgst").value) + Number(document.getElementById("txt_tot_sgst").value) + Number(document.getElementById("txt_freight").value));
                        }
                    });
                }

                show_return_po_cart_data();
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

                function item_unit() {
                    var str = $("#txt_item").val();
                    var txt_vendor = $("#txt_vendor").val();
                    if (txt_vendor == '') {
                        alert("Select Retailer.");
                        $("#txt_vendor").focus();
                        return false;
                    }
                    var res = str.split("(^)");
                    $("#txt_batch_no").html('');
                    //                    document.getElementById("div_detail").innerHTML = "";
                    $("#div_detail").html();
                    $.ajax({
                        type: "POST",
                        url: "ajax_js.php?menu=1",
                        data: {
                            'request_type': 'getSkuDetails',
                            id: res[0]
                        },
                        success: function (data) {
                            $("#txt_sku").val(data);
                        }
                    });
                    $.ajax({
                        type: "POST",
                        url: "ajax_js.php?menu=1",
                        data: {
                            'request_type': 'getCurrentItemStock',
                            id: res[0],
                            retailer_id: txt_vendor,
                        },
                        success: function (data) {
                            $("#current_total_qty").val(data);
                        }
                    });
                    $.ajax({
                        type: "POST",
                        url: "ajax_for_po.php?menu=1",
                        data: {
                            type: 'get_item_wise_batchno_details',
                            txt_vendor: txt_vendor,
                            item_code: str
                        },
                        success: function (reslt) {
                            var obj = JSON.parse(reslt);
                            var option = "<option value=''>-- Select Batch No --</option>";
                            $.each(obj.sr_no, function (key, value) {
                                option += "<option value='" + value.batch_no + "'> <b class='red'>BatchNo : </b>" + value.batch_no + " | <b class='green'>ExpiryDate :</b> " + value.expire_date + " | <b class='blue'>Qty :</b> " + value.cf + "</option>"
                            });
                            //                            console.log(option);
                            $("#txt_batch_no").html(option);
                            //                            $("#txt_batch_no").chosen();
                        }
                    });
                    // document.getElementById("txt_sku").value = document.getElementById("ajax_sku").value;
                }

                function checkQty() {

                }

                function addRow_new() {
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
                        } else {

                            var str = document.getElementById('txt_item').value;
                            var item_name = document.getElementById('txt_item').options[document.getElementById('txt_item').selectedIndex].text;
                            var res = str.split("(^)");
                            // console.log(item_name);
                            var table = document.getElementById("pur_detail");
                            var rowcount = table.rows.length; //get table row count

                            var txt_vendor = document.getElementById("txt_vendor").value;
                            var po_type = document.getElementById("po_type").value;
                            var item_code = document.getElementById("txt_item").value;
                            var item_qty = document.getElementById("txt_qty").value;
                            var unit_price = document.getElementById("txt_price").value;
                            var net_amt = document.getElementById("txt_total").value;
                            var delivry_date = document.getElementById("delivry_date").value;
                            var txt_batch_no = document.getElementById("txt_batch_no").value;
                            $.ajax({
                                url: 'ajax_for_po.php?menu=1',
                                method: 'post',
                                data: {
                                    type: 'add_return_po_item_into_cart',
                                    txt_vendor: txt_vendor,
                                    po_type: po_type,
                                    item_code: item_code,
                                    item_qty: item_qty,
                                    unit_price: unit_price,
                                    net_amt: net_amt,
                                    delivry_date: delivry_date,
                                    txt_batch_no: txt_batch_no
                                },
                                success: function (reslt) {

                                    show_return_po_cart_data();
                                    //                                    document.getElementById("txt_item").removeClass('chosen-select');
                                    //                                    document.getElementById("txt_item").value = "";
                                    //                                    document.getElementById("txt_item").addClass('chosen-select');
                                    document.getElementById("txt_sku").value = "";
                                    document.getElementById("txt_qty").value = "";
                                    document.getElementById("txt_price").value = "";
                                    document.getElementById("txt_total").value = "";
                                    document.getElementById("txt_batch_no").value = "";
                                    document.getElementById("txt_item").focus();
                                }
                            });
                        }
                    } else {
                        document.getElementById("txt_item").focus();
                    }
                }

                function delete_item(rawid) {

                    $.ajax({
                        url: "ajax_for_po.php?menu=1",
                        method: 'post',
                        data: {
                            type: 'delete_return_po_item',
                            raw_id: rawid
                        },
                        success: function (reslt) {
                            if (reslt == 0) {
                                show_return_po_cart_data();
                            } else {
                                alert("Error Due To Delete Item..");
                                show_return_po_cart_data();
                            }

                        }
                    });
                    //                   
                }

                function cal_item_detail() {
                    if (document.getElementById("txt_qty").value == "") {
                        document.getElementById("txt_qty").value = "0";
                    } else if (isNaN(document.getElementById("txt_qty").value)) {
                        document.getElementById("txt_qty").value = "0";
                    }

                    if (Number((document.getElementById("txt_qty").value)) > Number((document.getElementById("current_total_qty").value))) {
                        alert("The quantity entered is greater than the on-hand inventory. Checck your Current Stock.");
                        document.getElementById("txt_qty").value = "0";
                        return false;
                    }

                    if (document.getElementById("txt_price").value == "") {
                        document.getElementById("txt_price").value = "0";
                    } else if (isNaN(document.getElementById("txt_price").value)) {
                        document.getElementById("txt_price").value = "0";
                    }

                    document.getElementById("txt_total").value = (Number(document.getElementById("txt_qty").value) * Number(document.getElementById("txt_price").value)).toFixed(2);
                    //                    $.ajax({
                    //                        url: "ajax_for_po.php?menu=1",
                    //                        method: 'post',
                    //                        data: {type: 'delete_return_po_item', raw_id: rawid},
                    //                        success: function (reslt) {
                    //                            if (reslt == 0) {
                    //                                show_return_po_cart_data();
                    //                            } else {
                    //                                alert("Error Due To Delete Item..");
                    //                                show_return_po_cart_data();
                    //                            }
                    //
                    //                        }
                    //                    });
                }



                function del_purchase(x) {
                    cal_item_detail();
                    var row = document.getElementById(x);
                    if (document.getElementById("txt_pf").value == "") {
                        document.getElementById("txt_pf").value = "0";
                    }
                    if (document.getElementById("txt_freight").value == "") {
                        document.getElementById("txt_freight").value = "0";
                    }

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
                $(document).on("keyup", ".txt_cls_qty", function () {
                    this.value = this.value.replace(/[^0-9]/g, '');
                });
            </script>
            <!--END MAIN WRAPPER -->

        </div>
    </body>

</html>