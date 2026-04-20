<?php
error_reporting(0);
require_once 'includes/session.php';

require_once 'includes/common_function.php';
$company_id_s = $_SESSION['company_id'];
$company_id_array = explode(',', $company_id_s);
$company_id = $company_id_array[0];
if ($company_id == 1) {
    $po_pre = getCompanypPrefixById(2);
} else if ($company_id == 2) {
    $po_pre = getCompanypPrefixById($company_id);
} else {
    $po_pre = getCompanypPrefixById($company_id);
}
$fin_year = date('y') . '-' . date('y', strtotime('+1Year'));
if (ltrim(date('m')) > 3) {
    $cd = date('y');
    $dd = $cd + 1;
} else {
    $dd = date('y');
    $cd = $dd - 1;
}
$fin_year_latest = $cd . '' . $dd;

$inc_no = getLastpurchaseOrderBasic($fin_year_latest, $_SESSION['id']);
if (isset($inc_no) && !empty($inc_no)) {
    $po_no_int = $inc_no;
} else {
    $po_no_int = 0;
}
$po_no_increase = $po_no_int + 1;
$vendor_id = "";
?>
<!DOCTYPE html>
<html lang="en">
    <style>
        #ms-list-1{
            width: 300px;
        }
    </style>
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
                                        <a href="purchase_order_for_basic.php?menu=425"><button class="btn btn-success">Pre Purchase Order</button></a>
                                        <!--<a href="purchase_order_clossed.php?menu=425"><button class="btn btn-danger">Closed Order</button></a>-->
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <?php
                                        if (isset($_POST['btn_submit'])) {
                                            $data = array();
                                            $data['po_no'] = $_POST['txt_po_no'];
                                            $data['inc_no'] = $po_no_increase;
                                            $data['po_date'] = date('Y-m-d', strtotime($_POST['txt_po_date']));
//                                            $data['retailer_string'] = implode(',', $_POST['Retailer_id']);
                                            $data['vendor_id'] = $_POST['txt_vendor'];
                                            $data['supplier_id'] = getVendorNameById($_POST['txt_vendor']);
                                            $data['supplier_name'] = getVendorNameById($_POST['txt_vendor']);
                                            $data['supplier_contact_person'] = $_POST['txt_person'];
                                            $data['supplier_contact_no'] = $_POST['txt_number'];
                                            $data['supplier_address'] = $_POST['txt_address'];
                                            $data['user_id'] = $_SESSION['id'];
                                            $data['company_id'] = $_SESSION['company_id'];
                                            $data['financial_yr'] = $fin_year_latest;
                                            $data['tot_qty'] = $_POST['total_qty'];
                                            $data['net_total'] = $_POST['txt_nettotal'];

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
                                            $table_name = "purchase_order_basic";

                                            $reslt = insert($table_name, $data);
                                            if ($reslt) {
                                                $last_po_id = getLastpurchaseOrderIdBasic();

                                                $table_name_detail = "purchase_order_basic_detail";
                                                $upd_arr = array();
                                                $upd_arr['id'] = $last_po_id;
                                                $upd_arr['status'] = 1;
//                                                $upd_arr['retailer_string'] = implode(',', $_POST['Retailer_id']);
                                                $whrr = "user_id = '" . $_SESSION['id'] . "' and status = '0'";
                                                update($table_name_detail, $upd_arr, $whrr);

                                                echo '<script>alert("PO Saved");window.location.href="generate_po_order_print.php?menu=425&success=1";</script>';
                                            } else {
                                                echo '<script>window.location.href="generate_po_order_print.php?menu=425&error=1";</script>';
                                            }
                                        }
                                        ?>
                                        <div>
                                            <form class="form-horizontal" method="post" action="" id="pur_entry">
                                                <table align="center" border="0" width="100%" bgcolor="#E5FFEB" bordercolor="#000000">
                                                    <tr>
                                                        <td colspan="4" align="center"><i>
                                                                <font color="#336633" size="+3">Pre Purchase Goods Order </font>
                                                            </i></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="4" align="center">==============================================================================</td>
                                                    </tr>
                                                    <!--readonly="readonly"-->
                                                    <tr align="center">
                                                        <th align="left">P.O. No. <br>
                                                            <input name='txt_po_no' id="txt_po_no" class="text" type="hidden" style="height:30px" value="<?php
                                                            echo $po_pre . $_SESSION['id'] . "" . $fin_year_latest . "" . $po_no_increase;
                                                            ?>">
                                                            <b class="red"><?php
                                                                echo $po_pre . $_SESSION['id'] . "" . $fin_year_latest . "" . $po_no_increase;
                                                                ?></b>
                                                        </th>
                                                        <th align="left" id="retailer_html_d">Retailer Name <br>
                                                            <select class="text" name="Retailer_id[]" id="Retailer_id" multiple="" >
                                                                <!--<option value="">--Select Retailer--</option>-->
                                                                <?php foreach (getActiveRetailerDetails($company_id_in) as $active_sellers) { ?>
                                                                    <option value="<?php echo $active_sellers->id; ?>" ><?php echo $active_sellers->name; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </th>
                                                        <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                                        <th align="left">P.O. Date <br>
                                                            <input type="text" class="date-picker" id="id-date-picker-1" name="txt_po_date" id="txt_po_date" value="<?php echo date('d-m-Y'); ?>" />
                                                        </th>
                                                        <th align="left">Supplier Name <br>
                                                            <select name="txt_vendor" id="txt_vendor" class="text" onchange="getVendorDetails();">
                                                                <option value="">--Select Vendor--</option>
                                                                <?php foreach (getVendorActiveDetails() as $vendor) { ?>
                                                                    <option value="<?php echo $vendor->vendor_id; ?>" ><?php echo $vendor->vendor_name; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </th>
                                                    </tr>
                                                    <tr id="vendor_details">
                                                    <div id="div_detail"></div>
                                                    <th align="left">Supplier Contact Person<br>
                                                        <input type="text" id="txt_person" name="txt_person" value="" class="text" readonly="readonly" style="width:90%; height:30px" />
                                                    </th>
                                                    <th align="left">Supplier Contact Number<br>
                                                        <input type="text" id="txt_number" name="txt_number" value="" class="text" readonly="readonly" style="width:90%; height:30px" />
                                                    </th>
                                                    <th align="left" colspan="2">Supplier Address<br>
                                                        <textarea name='txt_address' readonly="readonly" id="txt_address" style="width:80%;" rows='3'></textarea>
                                                    </th>
                                                    </tr>
                                                    <tr>
                                                        <td align="center" width="100%" colspan="6">
                                                        </td>
                                                    </tr>
                                                </table>
                                                <br>

                                                <!------------Add Item------------>
                                                <table align="center" border="0" width="100%" bgcolor="#ccc" bordercolor="#000000">
                                                    <tr>
                                                        <th  >Item<br>
                                                            <select class="chosen-select" name="txt_item" id="txt_item" onchange="item_unit()" style="height:30px;width:300px;">
                                                                <option value='0'>-- SELECT ITEM --</option>
                                                                <?php
                                                                foreach (getActiveItemsList() as $row) {
                                                                    echo "<option value='$row->item_code'>$row->item_desc</option>";
                                                                }
                                                                ?>
                                                            </select>
                                                        </th>
                                                        <th>Unit<br>
<!--                                                            <select name="txt_sku" id="txt_sku" style=" height:30px; float:left;">
                                                                <option value="0">--select--</option>
                                                                <option value="CASE">CASE</option>
                                                                <option value="KG">KG</option>
                                                                <option value="PC">PC</option>
                                                                <option value="BAG">BAG</option>
                                                                <option value="DRUM">DRUM</option>
                                                            </select>-->
                                                            <input type="text" id="txt_sku" name="txt_sku" class="text" style=" height:30px; float:left;" size="6" value="" readonly="readonly" placeholder="Unit">
                                                        </th>
<!--                                                        <th>Unit<br>
                                                            <input type="text" id="txt_sku" name="txt_sku" class="text" style=" height:30px; float:left;" size="6" value="" readonly="readonly" placeholder="Unit">
                                                        </th>-->
                                                        <th>QTY<br>
                                                            <input type="text" id="txt_qty" name="txt_qty" class="text" value="0" size="6" onchange="cal_item_detail()" >
                                                        </th>
                                                        <th>Unit Price<br>
                                                            <input type="text" id="txt_price" name="txt_price" size="6" class="text" value="0"  onchange="cal_item_detail()">
                                                        </th>
                                                        <th>Net Amount<br>
                                                            <input type="text" id="txt_total" name="txt_total" class="text" value="0" size="6" readonly="readonly">
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th align="left"><br>
                                                            <input type="button" value="Add Item" onclick="addCartIteam()" id="btn_add" class="button btn btn-primary" style="" />
                                                        </th>
                                                    </tr>
                                                    </tr>
                                                </table>

                                                <!------------List of Items------------>
                                                <table border="0" class="table" bgcolor="#d2b4bc" bordercolor="#000000" style="margin-top:1%;border-collapse:separate;border-spacing:0 15px;border:none;" id="pur_detail">






                                                </table>


                                                <!------------Calculation of items------------>
                                                <table align="center" border="0" width="100%" bgcolor="#E5FFEB" bordercolor="#000000" style="margin-top:1%">
                                                    <tr>
                                                        <th>QTY<br>
                                                            <input type = "text" id = "total_qty" name = "total_qty" value = "<?php echo "0"; ?>" style = "text-align:center; width:50%; height:30px" readonly>
                                                        </th>
                                                        <th>Sub Total<br>
                                                            <input type = "text" style = "text-align:center; width:65%; height:30px" id = "txt_subTotal" name = "txt_subTotal" onchange = "cal_net_amt()" readonly = "readonly" value = "<?php echo "0"; ?>">
                                                        </th>
                                                        <th>Net Total<br>
                                                            <input type = "text" id = "txt_nettotal" name = "txt_nettotal" value = "<?php echo "0"; ?>" readonly = "readonly" style = "text-align:center; width:70%; height:30px">
                                                        </th>


                                                        <th>Amount<br>
                                                            <input type="text" id="txt_amt" name="txt_amt" value="<?php echo "0"; ?>" readonly="readonly" onchange="cal_net_amt()" style="text-align:center; width:65%; height:30px">
                                                        </th>

                                                        <th>Grand Total<br>
                                                            <input type="text" id="txt_grandTotal" name="txt_grandTotal" value="<?php echo "0"; ?>" readonly="readonly" style="text-align:center; width:70%; height:30px">
                                                        </th>
                                                    </tr>
                                                </table>


                                                <!------------Calculation of items------------>
                                                <table align="center" border="0" width="100%" bgcolor="#E5FFEB" bordercolor="#000000">
                                                    <tr>
                                                        <th align="left">
                                                            <br />
                                                            Delivery At : <br />
                                                            <select class="text" name="txt_term_delivery" id="txt_term_delivery" required="required" >
                                                                <option value="">-- Select -- </option>
                                                                <?php foreach (getActiveRetailerDetails($company_id_in) as $active_sellers) { ?>
                                                                    <option value="<?php echo $active_sellers->id; ?>" ><?php echo $active_sellers->name; ?></option>
                                                                <?php } ?>
                                                            </select> <br />
                                                            <!--<input type="text" id="" name="txt_term_delivery" required="required" style="width:100%; height:30px" value=""><br />-->
                                                            Terms of payment : <br />
                                                            <input type="text" id="" name="txt_term_payment" required="required" style="width:100%; height:30px" value="<?php echo "WITHIN 30 DYS."; ?>"><br />
                                                            Remarks<br>
                                                            <textarea class="remark" required="required" id="txt_remarks" name="txt_remarks"><?php echo ""; ?></textarea>
                                                        </th>

                                                    </tr>
                                                    <tr>
                                                        <td align="center" width="100%" colspan="6">
                                                            <hr/>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="">
                                                            <input type="submit" class="button btn btn-primary" value="Save P.O."  name="btn_submit" />
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

            <script type="text/javascript">

                $('#Retailer_id').multiselect({
                    search: true,
//                    selectAll : true
                });

                function getRetalerHtmlRefresh() {
                    $.ajax({
                        url: 'ajax_agro.php?menu=425',
                        method: 'post',
                        data: {request_type: 'get_retailer_refresh_div'},
                        success: function (resp) {
                            document.getElementById('retailer_html_d').innerHTML = resp;
                            $('#Retailer_id').multiselect({
                                search: true,
                            });
                        }
                    });
                }
                function showCartItems() {
                    $.ajax({
                        url: 'ajax_agro.php?menu=425',
                        method: 'post',
                        data: {request_type: 'get_cart_items_data_for_basic'},
                        success: function (resp) {
//                            console.log(resp);
                            document.getElementById('pur_detail').innerHTML = resp;


                            document.getElementById("total_qty").value = document.getElementById("ttl_qty").value;

                            document.getElementById("txt_subTotal").value = document.getElementById("ttl_amuntt").value;

                            document.getElementById("txt_nettotal").value = Number(document.getElementById("txt_subTotal").value);

                            document.getElementById("txt_amt").value = Number(document.getElementById("txt_nettotal").value);

                            document.getElementById("txt_grandTotal").value = Math.round((document.getElementById("txt_nettotal").value == "") ? Number("0") : Number(document.getElementById("txt_nettotal").value));


                        }
                    });
                }
                showCartItems();

                function addCartIteam() {
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
                        } else {

                            var table = document.getElementById("pur_detail");
                            var rowcount = table.rows.length;

                            var item_code = document.getElementById("txt_item").value;
                            var item_qty = document.getElementById("txt_qty").value;
                            var unit_price = document.getElementById("txt_price").value;
                            var net_amt = document.getElementById("txt_total").value;
                            var retailer_string = $('#Retailer_id').val();
                            retailer_string.toString();

                            $.ajax({
                                url: 'ajax_agro.php?menu=425',
                                method: 'post',
                                data: {request_type: 'add_po_cart_item_for_basic', item_code: item_code, item_qty: item_qty, unit_price: unit_price, net_amt: net_amt, retailer_string: retailer_string},
                                success: function (resp) {
                                    showCartItems();
//                                    getRetalerHtmlRefresh();


                                    document.getElementById("txt_item").value = "";
                                    document.getElementById("txt_sku").value = "";
                                    document.getElementById("txt_qty").value = "";
                                    document.getElementById("txt_price").value = "";
                                    document.getElementById("txt_total").value = "";


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
                        url: 'ajax_agro.php?menu=425',
                        method: 'post',
                        data: {request_type: 'delete_cart_po_data_for_basic', idd: idd},
                        success: function (resp) {
                            if (resp == 0) {
                                alert('Item Remove Successfully...!');
                                showCartItems();
                            }
                        }
                    });
                }





                show_detail();
                function new_purchase_click()
                {
                    document.getElementById("purchase_detail").form_type.value = "new";
                    document.getElementById("purchase_detail").purchase_id.value = "";
                    document.purchase_detail.submit();

                }
                function released_purchase_click() {
                    window.location.href = "release_purchase_order_list.php?menu=425";
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
                            url: 'ajax_new.php?menu=425',
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
                function show_detail()
                {
                    var vendor_id_ajit = document.getElementById("txt_vendor").value;
                    if (vendor_id_ajit != '') {
                        $(".loader").css("display", "block");
                        var xmlhttp;
                        var url = "ajax_agro.php?menu=425&type=vendor&id=" + vendor_id_ajit;
                        document.getElementById("div_detail").innerHTML = "";
                        if (window.XMLHttpRequest) {
                            xmlhttp = new XMLHttpRequest();
                        } else {
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
                        $(".loader").css("display", "none");
                        xmlhttp.open("GET", url, true);
                        xmlhttp.send();
                    }
                }

                function getVendorDetails()
                {
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
                function item_unit()
                {
                    var str = document.getElementById('txt_item').value;
                    var res = str.split("(^)");

                    var xmlhttp;
                    var url = "ajax_agro.php?menu=425&type_basic=itemunit_basic&id=" + res[0];

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



                function cal_item_detail()
                {
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
//                    var txt_dis_unitprice = document.getElementById('txt_dis_unitprice').value;
//                    var after_discount_price = Number(total_price) - Number(txt_dis_unitprice);
                    var after_discount_price = Number(total_price);
//                    var gst_rate = document.getElementById('txt_gst').value;
//                    var gst_ttl_val = (Number(after_discount_price) * gst_rate) / 100;

//                    document.getElementById("txt_total_gst").value = gst_ttl_val;
//                    document.getElementById("txt_total").value = (after_discount_price + gst_ttl_val).toFixed(2);
                    document.getElementById("txt_total").value = after_discount_price.toFixed(2);
                }


                function cal_net_amt()
                {
//                    if (document.getElementById("txt_pf").value == "") {
//                        document.getElementById("txt_pf").value = "0";
//                    }
//                    if (document.getElementById("txt_freight").value == "") {
//                        document.getElementById("txt_freight").value = "0";
//                    }
//                    if (document.getElementById("txt_discount").value == "") {
//                        document.getElementById("txt_discount").value = "0";
//                    }

                    document.getElementById("txt_nettotal").value = Number(document.getElementById("txt_subTotal").value);


//                    document.getElementById("txt_tot_cgst").value = (Number(document.getElementById("txt_nettotal").value) * Number(document.getElementById("txt_cgst_per").value)) / 100;
//                    document.getElementById("txt_tot_sgst").value = (Number(document.getElementById("txt_nettotal").value) * Number(document.getElementById("txt_cgst_per").value)) / 100;


//                    document.getElementById("txt_sgst_per").selectedIndex = document.getElementById("txt_cgst_per").selectedIndex;
//                    console.log(document.getElementById("txt_sgst_per").selectedIndex);

                    document.getElementById("txt_amt").value = Number(document.getElementById("txt_nettotal").value);

                    document.getElementById("txt_grandTotal").value = Number(document.getElementById("txt_amt").value).toFixed(2);

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
