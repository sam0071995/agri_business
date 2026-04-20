<?php
error_reporting(0);
require_once 'includes/session.php';
require_once 'includes/common_function.php';
if (ltrim(date('m')) > 3) {
    $cd = date('y');
    $dd = $cd + 1;
} else {
    $dd = date('y');
    $cd = $dd - 1;
}
$fin_year = $cd . '' . $dd;

//print_r($_SESSION);
$inc_no = getLastIncNo($fin_year, $_SESSION['id']);
if ($inc_no == 0) {
    $inc_no = 1;
} else {
    $inc_no = $inc_no + 1;
}

// echo $_SESSION['company_id'];

$comp_data = getCompanyDetailById($_SESSION['company_id']);

if ($fin_year == '2425') {
    $po_number = "AGRO" . $_SESSION['id'] . "" . $fin_year . "" . $inc_no;
} else {
    $po_number = $comp_data->prefix . "/" . $_SESSION['id'] . "/" . $fin_year . "/" . $inc_no;
}
?>
<!DOCTYPE html>
<html lang="en">
    <?php require_once 'includes/header.php'; ?>

    <body class="no-skin">
        <style>
            .marg_tp_one {
                margin-top: 10px;
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
                                <h3 class="header">
                                    <?php
                                    // print_r($_SESSION); 
                                    ?>
                                    Book Sell Entry
                                </h3>

                                <form role="form" method="post" action="">


                                    <div class="col-md-4">
                                        <div class="box box-primary">
                                            <div class="box-header with-border hidden">
                                            </div>

                                            <div class="box-body">
                                                <div class="form-group">
                                                    <?php if ($company_id == 3) { ?>
                                                        <input type="hidden" readonly="readonly" class="form-control invoice_date" name="invoice_date" value="<?php echo date("Y-m-d"); ?>">
                                                    <?php } else { ?>
                                                        <input type="hidden" class="form-control invoice_date" name="invoice_date" value="1">
                                                    <?php } ?>
                                                    <div class="col-md-12">
                                                        <label>Invoice No</label>
                                                        <input type="text" class="form-control input-sm txt_cls_po_no" placeholder="Purchase Order No" style="text-transform: uppercase;" readonly name="txt_cls_po_no" value="<?php echo $po_number; ?>">
                                                    </div>

                                                    <div class="col-md-12 marg_tp_one">
                                                        <label>Select Item</label> <span style='color:red'>*</span>
                                                        <select class="select2 form-control input-sm sel_cls_item" autofocus>
                                                            <option value="">-- Select Item --</option>
                                                            <?php
                                                            foreach (getInventoryItem($_SESSION['id']) as $row) {
                                                                echo "<option value='" . $row->item_code . "'>" . $row->item_desc . "</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <br />
                                                    <br />
                                                    <br />
                                                    <br />
                                                    <br />
                                                    <br />
                                                    <br />
                                                    <br />


                                                    <div class="col-md-6 marg_tp_one">
                                                        <label>Price</label> <span style='color:red'>*</span>
                                                        <input type="text" class="form-control input-sm txt_cls_price" name="txt_cls_price" value="" readonly>
                                                    </div>

                                                    <div class="col-md-6 marg_tp_one">
                                                        <label>UOM</label> <span style='color:red'>*</span>
                                                        <input type="text" class="form-control input-sm txt_cls_uom" name="txt_cls_uom" value="" readonly>
                                                    </div>

                                                    <div class="col-md-6 marg_tp_one">
                                                        <label>Qty Required</label> <span class='text-red'>*</span>
                                                        <!--<input type="text" class="form-control input-sm txt_cls_qty" id="txt_cls_qty" name="txt_cls_qty" onchange="return req_qty_validate();" value="" placeholder="Quantity Required">-->
                                                        <input type="text" class="form-control input-sm txt_cls_qty" id="txt_cls_qty" name="txt_cls_qty" onchange="return req_qty_validate();" value="" placeholder="Quantity Required">

                                                    </div>
                                                    <div class="col-md-6 marg_tp_one">
                                                        <label>AvailableStock</label> <span class='text-red'></span>
                                                        <input type="text" class="form-control input-sm txt_cls_stock" name="txt_cls_stock" value="" placeholder="Available Stock" readonly>
                                                    </div>
                                                    <div class="col-md-12 marg_tp_one">
                                                        <label>Batch Number</label> <span class='text-red'></span>
                                                        <?php if ($batch_wise_sale == 1) { ?>
                                                            <select class="form-control input-sm txt_batch_no txt_batch_no_options" name="txt_batch_no" required="required">
                                                                <option value="">--select--</option>
                                                            </select>
                                                        <?php } else { ?>
                                                            <input type="text" class="form-control input-sm txt_batch_no" name="txt_batch_no" required="required" value="" placeholder="Batch Number">
                                                        <?php } ?>
                                                    </div>
                                                    <div class="col-md-12 marg_tp_one">
                                                        Use for
                                                        <select name="crop" class="form-control crop" required="required" id="crop">
                                                            <option value="">--select crop--</option>
                                                            <?php foreach (getAllCrops() as $crop) { ?>
                                                                <option value="<?php echo $crop->name; ?>"><?php echo $crop->name; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                        <p class="red">(Crop not avalable in list Add from <a target="_blank" href="add_crops.php?menu=438">here</a>)</p>
                                                    </div>
                                                    <div class="col-md-6 marg_tp_one">
                                                        <button type="button" class="btn btn-sm btn-primary btn_cls_add">Add</button>
                                                    </div>
                                                    <div class="col-md-12">&nbsp;</div>
                                                </div>
                                            </div>

                                            <div class="box-footer bg-gray">
                                                <div class="col-md-12">
                                                    <input type="hidden" value="<?php echo $po_number; ?>" class="txt_order_no">
                                                    <input type="hidden" value="" class="txt_ordered_amount">
                                                    <input type="hidden" value="<?php echo $_SESSION['dealer_type']; ?>" id="dealer_type">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-8" id="div_items">
                                        <div class="box box-primary">
                                            <div class="box-header with-border">
                                                <h4 class="pull-left">List of Ordered Items</h4>
                                                <h4 class="pull-right">
                                                    Total Amount: <small id="total_amount"></small></h4>



                                            </div>

                                            <div class="box-body">
                                                <table class="table table-stripped table-bordered table-hover table-condensed table-responsive tbl_cls_inventory_list">
                                                    <thead>
                                                        <tr class="text-bold bg-gray">
                                                            <td width='5%' align='center'>#</td>
                                                            <td width='25%'>Item</td>
                                                            <td width='10%' align='center'>Quantity</td>
                                                            <td width='10%' align='right'>Sale Price</td>
                                                            <td width='10%' align='right'>Amount</td>
                                                            <td width='10%' align='right'>BatchNo</td>
                                                            <td width='10%' align='right'>UseFor</td>
                                                            <td align="center" width='8%'>Action</td>
                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                    </tbody>

                                                </table>
                                            </div>

                                            <div class="box-footer bg-gray">
                                                <table class="table">
                                                    <tr>
                                                        <td>
                                                            Customer Village :
                                                            <select name="cus_village" id="cus_village">
                                                                <option value="">--select--</option>
                                                                <?php foreach (getActivesVillages($retailer_id) as $village) { ?>
                                                                    <option value="<?php echo $village->id; ?>"><?php echo $village->name; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            Customer Mobile : <input type="text" name="cus_ph" id="cus_ph" class="cus_ph" maxlength="10" placeholder="Customer Mobile" /><br><br>
                                                            <input type="checkbox" name="whatsapp_no" id="whatsapp_no" class="whatsapp_no" value="1" /> <b class="red">Is Whatsapp Number?</b>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Customer Name : <input type="text" name="cus_name" id="cus_name" placeholder="Customer Name" />&nbsp;&nbsp;&nbsp;
                                                        </td>
                                                        <td>
                                                            Customer Address : <textarea name="cus_add" id="cus_add" placeholder="Customer Address Here.."></textarea>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Customer Aadhar No : <input type="text" name="cus_adhar" id="cus_adhar" placeholder="Customer Aadhar Number" />
                                                        </td>
                                                        <td>
                                                            Payment Method :
                                                            <select name="cus_payment_method" class="cus_payment_method" id="cus_payment_method">
                                                                <option value="0">Cash</option>
                                                                <option value="1">Online</option>
                                                                <option value="2">Cheque/DD</option>
                                                            </select>
                                                            <!--<input type="button" class="btn btn-sm btn-warning btn_cls_confirm" value="Confirm" />-->
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td>
                                                            <input type="checkbox" name="discount_check" id="discount_check" onclick="giveDiscount();" value="0" /> Give Discount
                                                        </td>
                                                        <td id="cuponcode_td">
                                                            Apply Cupon : <input type='text' name='couponcode' class="couponcode" id='couponcode' placeholder='Enter Code here..' />
                                                            <button type="button" id="check_cupon" class="btn btn-success btn-xs" onclick="checkCuponCode();">Apply</button>
                                                            <input type="hidden" id="cupon_status"  />
                                                            <p class="coupon_message"></p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="transaction_no_block" style="display:none;">
                                                            Trnsaction/Chequq/DD number : <input type="text" name="transaction_no" id="transaction_no" placeholder="Transaction Number" />
                                                        </td>
                                                        <?php
                                                        if ($retailer_detail->pending_amount == 1) {
                                                            $styale = "display:block;";
                                                        } else {
                                                            $styale = "display:none;";
                                                        }
                                                        ?>
                                                        <td class="transaction_no_block" style="<?php echo $styale; ?>">
                                                            Customer Pending Amount : <input type="text" name="pending_amt" id="pending_amt" class="pending_amt" placeholder="Enter Pending Amount" value="0" />
                                                        </td>
                                                        <td>
                                                            Remark : <input type="text" name="remark" id="remark" class="remark" placeholder="Enter Your Remark Here" />
                                                        </td>
                                                        <td>
                                                            <input type="button" class="btn btn-sm btn-warning btn_cls_confirm" value="Confirm" />
                                                        </td>
                                                    </tr>

                                                </table>
                                            </div>

                                            <div class="col-md-12" style="border-bottom:1px solid #ccc;
                                                 margin-top: 2%; margin-bottom: 5%;"></div>




                                        </div>
                                    </div>

                                    <div class="col-lg-8 pull-right">
                                        <div id="div_print"></div>
                                    </div>

                                </form>

                            </div>
                        </div><!-- /.row -->
                    </div><!-- /.page-content -->
                </div>
            </div><!-- /.main-content -->
            <script>
                refresh_small_inventory_item_list();
                getPrice();
                document.getElementById('cuponcode_td').style.display = 'none';
                // for discount check box================
                function giveDiscount() {
                    var check = document.getElementById('discount_check').checked;

                    if (check == true) {
                        document.getElementById('cuponcode_td').style.display = 'block';
                        document.querySelector('.btn_cls_confirm').style.display = 'none';
                        document.querySelector('#check_cupon').style.display = 'block';
                        document.getElementById('discount_check').value = 1;
                    } else {
                        document.getElementById('cuponcode_td').style.display = 'none';
                        document.querySelector('.btn_cls_confirm').style.display = 'block';
                        document.querySelector('#check_cupon').style.display = 'none';
                        document.getElementById('discount_check').value = 0;
                    }
                }
                function checkCuponCode() {
                    var cupon = document.getElementById('couponcode').value;
                    if (cupon == '') {
                        alert('Enter Coupon Code..!');
                        document.getElementById('couponcode').focus();
                        return false;
                    }

                    $.ajax({
                        url: '<?php echo $ajax_page; ?>',
                        type: 'post',
                        dataType: 'json',
                        data: {
                            'request_type': 'check_cupon_code',
                            cupon: cupon
                        },
                        success: function (res) {
                            if (res > 0) {
                                alert("Cupone code validate successfully!, You may get " + res + " Rs Discount on Total Purchase Value.");
                                document.querySelector('#check_cupon').style.display = 'none';
                                document.getElementById('cupon_status').value = res;
                                document.querySelector('.btn_cls_confirm').style.display = 'block';
                            } else {
                                alert("Cupone code not valid OR Expired, please check status of this code..!");
                                document.querySelector('.btn_cls_confirm').style.display = 'none';
                                document.getElementById('couponcode').value = '';
                                document.getElementById('couponcode').focus();
                                document.getElementById('cupon_status').value = res;
                            }
                        }
                    });
                }


                //qty validate=========
                //                function req_qty_validate() {
                //                    var qtyval = document.getElementById('txt_cls_qty').value;
                //                    console.log(qtyval);
                //                    qtyval.replace(/[^0-9]/g, '');
                //                    document.getElementById('txt_cls_qty').value = qtyval;
                //                }

                //reset fields
                function reset_fields() {
                    $('.sel_cls_item').val('').trigger('chosen:updated');
                    $('.txt_cls_price').val("");
                    $('.txt_cls_uom').val("");
                    $('.txt_cls_qty').val("");
                    $('.txt_cls_stock').val("");
                }

                function req_qty_validate() {
                    const inputField = document.getElementById("txt_cls_qty");
                    const value = inputField.value;

                    // Regular expression to allow integers or decimal numbers
                    const numReg = /^[0-9]+(\.[0-9]+)?$/;

                    // Check if the value matches the number pattern
                    if (!numReg.test(value)) {
                        alert("Please enter a valid number (integer or decimal).");
                        inputField.value = ""; // Clear the invalid input
                        return false;
                    }

                    return true; // Input is valid
                }


                function req_qty_validate1() {
                    const inputField = document.getElementById("txt_cls_qty");
                    const value = inputField.value;

                    // Regular expression to allow only integers
                    const intReg = /^[0-9]+$/;

                    // Check if the value matches the integer pattern
                    if (!intReg.test(value)) {
                        alert("Please enter a valid integer value (no decimals allowed).");
                        inputField.value = ""; // Clear the invalid input
                        return false;
                    }

                    return true; // Input is valid
                }

                //price of products
                function getPrice() {

                    $.ajax({
                        url: '<?php echo $ajax_page; ?>',
                        type: 'POST',
                        data: {
                            request_type: "get_price_from_list"
                        },
                        async: false,
                        success: function (data) {
                            $('#total_amount').html(data);
                            $('.txt_ordered_amount').val($.trim(data));
                        }
                    });
                }

                //refresh table if any entry available
                function refresh_small_inventory_item_list() {
                    $.ajax({
                        url: '<?php echo $ajax_page; ?>',
                        type: 'POST',
                        data: {
                            request_type: "get_auto_refresh_list"
                        },
                        async: false,
                        success: function (data) {
                            $('.tbl_cls_inventory_list').find('tbody').empty().append(data);
                        }
                    });
                }
                //                $(document).on("keyup", ".txt_cls_qty", function () {
                //                    this.value = this.value.replace(/[^0-9]/g, '');
                //
                //                });
                //on change item get qty
                $(document).on("change", ".cus_ph", function () {
                    var cus_ph = $(".cus_ph").val();
                    if (cus_ph != '') {
                        var filter = /^\d*(?:\.\d{1,2})?$/;
                        if (filter.test(cus_ph)) {
                            if (cus_ph.length == 10) {
                                $.ajax({
                                    url: '<?php echo $ajax_page; ?>',
                                    type: 'POST',
                                    data: {
                                        cus_ph: cus_ph,
                                        request_type: "get_mobiledetails"
                                    },
                                    async: false,
                                    success: function (data) {
                                        var obj = JSON.parse(data);
                                        if (data == null) {
                                            $('#cus_adhar').val("");
                                            $('#cus_name').val("");
                                            $('#cus_add').val("");
                                        } else {
                                            $('#cus_adhar').val(obj.cus_adhar);
                                            $('#cus_name').val(obj.cus_name);
                                            $('#cus_add').val(obj.cus_add);
                                        }
                                    }
                                });
                            } else {
                                alert('Please Enter 10 digit mobile number.');
                                $(".cus_ph").val("");
                                $(".cus_ph").focus();
                                $('#cus_adhar').val("");
                                $('#cus_name').val("");
                                $('#cus_add').val("");
                                return false;
                            }
                        } else {
                            alert('Please Enter valid mobile number.');
                            $('#cus_adhar').val("");
                            $('#cus_name').val("");
                            $('#cus_add').val("");
                            $(".cus_ph").val("");
                            $(".cus_ph").focus();
                            return false;
                        }
                    }
                });

                $(document).on("change", ".cus_payment_method", function () {
                    var cus_payment_method = $('.cus_payment_method').val();
                    if (cus_payment_method == 0) {
                        $(".transaction_no_block").hide();
                    } else {
                        $(".transaction_no_block").show();
                    }
                });
                $(document).on("change", ".sel_cls_item", function () {
                    var item_code = $('.sel_cls_item').val();
                    data = {
                        item_code: item_code,
                        request_type: "get_availability"
                    }

                    $.ajax({
                        url: '<?php echo $ajax_page; ?>',
                        type: 'POST',
                        data: data,
                        async: false,
                        success: function (data) {
                            var obj = JSON.parse(data);
                            $('.txt_cls_price').val(obj.total);
                            $('.txt_cls_uom').val(obj.uom);
                            $('.txt_cls_stock').val(obj.current_stock);
                            var sr_no_array = obj.sr_no;
                            var option = "<option value=''>--select--</option>";
                            $.each(obj.sr_no, function (key, value) {
                                option += "<option value='" + value.batch_no + "'> <b class='red'>BatchNo : </b>" + value.batch_no + " | <b class='green'>ExpiryDate :</b> " + value.expire_date + " | <b class='blue'>Qty :</b> " + value.cf + "</option>"
                            });
                            $('.txt_batch_no_options').html(option);

                        }
                    });
                });
                //add outward gooods to temp table
                $('.btn_cls_add').click(function () {
                    //txt_ordered_amount        txt_dealer_balance

                    $(".loader").css("display", "block");
                    // alert($('.sel_cls_item').val());

                    var intReg = /[0-9-()+]+$/;
                    if ($('.sel_cls_item').val() == '') {
                        alert("Please select an item");
                        $('.sel_cls_item').focus();
                        $(".loader").css("display", "none");
                        return false;
                    } else if ($('.txt_cls_price').val() == '' || $('.txt_cls_price').val() <= 0) {
                        alert("Please check product price.");
                        $('.txt_cls_price').focus();
                        $(".loader").css("display", "none");
                        return false;
                    } else if ($('.txt_cls_qty').val() == '' || $('.txt_cls_qty').val() == 0) {
                        alert("Quantity is required");
                        $('.txt_cls_qty').focus();
                        $(".loader").css("display", "none");
                        return false;
                    } else if (!intReg.test($("input[name=txt_cls_qty]").val())) {
                        alert("Please Enter a valid Quentity");
                        $("input[name=txt_cls_qty]").focus();
                        $(".loader").css("display", "none");
                        return false;
                    } else if ($('.txt_cls_qty').val() == '0') {
                        alert("Quantity is required");
                        $('.txt_cls_qty').focus();
                        $(".loader").css("display", "none");
                        return false;
                    }
                    if ($('.txt_batch_no').val() == '') {
                        alert("Batch Number is required.");
                        $('.txt_batch_no').focus();
                        $(".loader").css("display", "none");
                        return false;
                    }
                    if ($('.crop').val() == '') {
                        alert("Select Used in Crop.");
                        $('.crop').focus();
                        $(".loader").css("display", "none");
                        return false;
                    }
                    var invoice_date = $('.invoice_date').val();
                    var availablestck = $('.txt_cls_stock').val();
                    var requiredstck = $('.txt_cls_qty').val();
                    var txt_batch_no = $('.txt_batch_no').val();
                    var crop = $('.crop').val();
                    availablestck = availablestck.replace(/\D/g, "");
                    requiredstck = requiredstck.replace(/\D/g, "");
                    // console.log(availablestck);
                    // console.log(requiredstck);
                    if (Number(availablestck) < Number(requiredstck)) {
                        alert("Please Check Available Stock.");
                        $('.txt_cls_qty').focus();
                        $(".loader").css("display", "none");
                        return false;
                    }

                    var po_no = $('.txt_cls_po_no').val();
                    var item_code = $('.sel_cls_item').val();
                    var item_desc = $('.sel_cls_item option:selected').text();
                    var price = $('.txt_cls_price').val();
                    var qty = $('.txt_cls_qty').val();
                    var uom = $('.txt_cls_uom').val();
                    // tr_id = $('.trn_id').val();

                    var vertual_total = parseInt(price) * parseInt(qty);
                    var dealer_bal = $('.txt_dealer_balance').val();
                    var ordered_amt = $('.txt_ordered_amount').val();
                    var final_order_total = parseInt(vertual_total) + parseInt(ordered_amt);
                    var dealer_type = $('#dealer_type').val();
                    /// ==============================================================
                    // var multipl_qty = (qty % 1000);
                    // if (item_code == 'SMI-RIVET-BIG-0008' && multipl_qty != 0) {
                    //     alert("Quantity should be in multiple of 1000 only");
                    //     $(".txt_cls_qty").val(0);
                    //     return false;
                    // }
                    // if (item_code == 'SMI-RIVET-SMALL-0007' && multipl_qty != 0) {
                    //     alert("Quantity should be in multiple of 1000 only");
                    //     $(".txt_cls_qty").val(0);
                    //     return false;
                    // }
                    // var multipl_qty_for_hex = (qty % 100);
                    // if (item_code == 'H_BM6X15' && multipl_qty_for_hex != 0) {
                    //     alert("Quantity should be in multiple of 100 only");
                    //     $(".txt_cls_qty").val(0);
                    //     return false;
                    // }
                    ///==============================================================



                    data = {
                        invoice_date: invoice_date,
                        po_no: po_no,
                        item_code: item_code,
                        item_desc: item_desc,
                        price: price,
                        qty: qty,
                        uom: uom,
                        crop: crop,
                        txt_batch_no: txt_batch_no,
                        request_type: "insert_to_temp_table"
                    }

                    console.log(data);
                    $.ajax({
                        url: '<?php echo $ajax_page; ?>',
                        type: 'POST',
                        data: data,
                        async: false,
                        success: function (data) {
                            if (data == 0) {
                                alert("You have already added this item in the list.");
                                reset_fields();
                                $('.sel_cls_item').focus();
                            } else if (data == 3) {
                                alert("Item with added batch number has expired OR wrong batch number OR Qty should be less than current batch stock.");
                                $('.sel_cls_item').focus();
                            } else if (data == 33) {
                                alert("Decimal input not allow.");
                                $('.sel_cls_item').focus();
                            } else if (data == 1) {
                                refresh_small_inventory_item_list();
                                reset_fields();
                                getPrice();
                            } else {
                                alert(data);
                                // console.log(data);
                            }
                            $(".loader").css("display", "none");
                        }
                    });
                });
                //remove from item list detail
                $(document).on("click", ".i_remove_cls_small_inv", function () {

                    var id = $(this).attr("id");
                    var po_no = $(".po_num_1").val();
                    // alert(id);

                    if (confirm("Are you sure to remove this item from the list?")) {
                        $.ajax({
                            url: '<?php echo $ajax_page; ?>',
                            type: 'POST',
                            data: {
                                id: id,
                                po_no: po_no,
                                request_type: "remove_item_from_list"
                            },
                            async: false,
                            success: function (data) {
                                if (data == 1) {
                                    refresh_small_inventory_item_list();
                                    getPrice();
                                } else {
                                    alert(data);
                                }
                            }
                        });
                    }
                });
                $(document).on("click", ".btn_cls_confirm", function () {
                    var pending_amt = $('.pending_amt').val();
                    var po_no = $('.txt_cls_po_no').val();
                    var order_no = $('.txt_order_no').val();
                    var amount = $('.txt_ordered_amount').val();
                    var trn_id = $('.trn_id').val();
                    var po_num_1 = $('.po_num_1').val();
                    var cus_name = $('#cus_name').val();
                    var cus_village = $('#cus_village').val();
                    var cus_add = $('#cus_add').val();
                    var cus_ph = $('#cus_ph').val();
                    var cus_adhar = $('#cus_adhar').val();
                    var transaction_no = $('#transaction_no').val();
                    var cus_payment_method = $('#cus_payment_method').val();
                    var remark = $('#remark').val();
                    if (trn_id == '') {
                        alert("Please Enter a Valid Transaction number.");
                        $('.trn_id').focus();
                        return false;
                    }
                    if (cus_village == '' || cus_village == 0) {
                        alert("Please Select Village.");
                        $('#cus_village').focus();
                        return false;
                    }

                    if (cus_ph == '' || cus_ph == 0) {
                        alert("Please Enter Customer Mobile.");
                        $('#cus_ph').focus();
                        return false;
                    }
                    if (!/^[0-9]+$/.test(cus_ph)) {
                        alert("Mobile number must contain digits only.");
                        $('#cus_ph').focus();
                        return false;
                    }
                    if (cus_ph.length !== 10) {
                        alert("Mobile number must be exactly 10 digits.");
                        $('#cus_ph').focus();
                        return false;
                    }

                    if (cus_adhar == '' || cus_adhar == 0) {
                        alert("Please Enter Aadhar number.");
                        $('#cus_adhar').focus();
                        return false;
                    }
                    if (cus_payment_method == '') {
                        alert("Select Payment Method.");
                        $('#cus_adhar').focus();
                        return false;
                    }
                    if (remark == '') {
                        alert("Please Enter Remark.");
                        $('#remark').focus();
                        return false;
                    }
                    if ((transaction_no == '' || transaction_no == 0) && cus_payment_method != 0) {
                        alert("Enter Transaction Number");
                        $('#cus_adhar').focus();
                        return false;
                    }
                    if ($('#whatsapp_no').is(":checked")) {
                        var whatsapp_no = 1;
                    } else {
                        var whatsapp_no = 0;
                    }
                    if ($('#discount_check').is(":checked")) {
                        var discount_check = 1;
                        var couponcode = $('#couponcode').val();
                        if (couponcode == '') {
                            alert("Please Enter Coupon Code.");
                            $('#couponcode').focus();
                            return false;
                        }
                    } else {
                        var discount_check = 0;
                        var couponcode = 0;
                    }
                    if (po_num_1 !== po_no) {
                        alert("Please Delete Your OLD Order Then Order Again It .");
                        //                location.href="small_inventory_order.php?menu=119";
                        return false;
                    }

                    var invoice_date = $('.invoice_date').val();
                    var data = {
                        cus_adhar: cus_adhar,
                        invoice_date: invoice_date,
                        cus_name: cus_name,
                        cus_village: cus_village,
                        cus_add: cus_add,
                        cus_ph: cus_ph,
                        po_no: po_no,
                        cus_payment_method: cus_payment_method,
                        transaction_no: transaction_no,
                        order_no: order_no,
                        amount: amount,
                        pending_amt: pending_amt,
                        trn_id: trn_id,
                        whatsapp_no: whatsapp_no,
                        discount_check: discount_check,
                        couponcode: couponcode,
                        remark: remark,
                        request_type: "confirm_order"
                    }

                    if (confirm("Are you sure to order the list of selected items ?")) {
                        $.ajax({
                            url: '<?php echo $ajax_page; ?>',
                            type: 'POST',
                            data: data,
                            async: false,
                            success: function (data) {
                                if (data == 0) {
                                    alert("There is no item to place your order");
                                } else if (data == 2) {
                                    alert("You do not have available eWallet amount to purchase these items. Please recharge your eWallet account.");
                                    window.location = window.location;
                                } else if (data == 4) {
                                    alert("Item with added batch number has expired OR wrong batch number OR Qty should be less than current batch stock.");
                                } else if (data == 3) {
                                    alert("Your order has been successfully placed.");
                                    window.location = window.location;
                                    window.open('book_sale_invoice.php?menu=1&btn_no=' + btoa(po_no), '_blank');
                                } else {
                                    alert("Order Place Error");
                                }
                            }
                        });
                    }
                });


                // //get print
                // $('#btn_get_print').click(function()
                // {
                //     $('#div_outward_print').html('<img src="images/spinner.gif" width="50">');

                //     invoice_no = $('.txt_invoice_no').val();

                //     data = {invoice_no: invoice_no, request_type: "get_outward_print_detail"}

                //     $.ajax({
                //         url: 'small_inventory_order_controller.php',
                //         type: 'POST',
                //         data: data,
                //         async: false,
                //         success: function (data)
                //         {
                //             $('#div_outward_print').html(data);
                //         }
                //     });
                // });
            </script>

            <!--END MAIN WRAPPER -->
            <?php require_once 'includes/footer.php'; ?>
        </div>
    </body>

</html>