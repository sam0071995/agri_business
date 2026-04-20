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
$fin_year = $cd . '-' . $dd;

//print_r($_SESSION);
// $inc_no = getLastIncNo($fin_year, $_SESSION['id']);
// if ($inc_no == 0) {
//     $inc_no = 1;
// } else {
//     $inc_no = $inc_no + 1;
// }
// $po_number = "TS/" . $_SESSION['id'] . "/" . $fin_year . "/" . $inc_no;
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
                                <h3 class="header">
                                    <?php //print_r($_SESSION); 
                                    ?>
                                    Book Order
                                </h3>

                                <form role="form" method="post" action="">


                                    <div class="col-md-4">
                                        <div class="box box-primary">
                                            <div class="box-header with-border hidden">
                                            </div>

                                            <div class="box-body">
                                                <div class="form-group">
                                                    <div class="col-md-12">
                                                        <label>Select Retailer</label> <span style='color:red'>*</span>
                                                        <select class="chosen-select form-control input-sm sel_cls_retailer" onchange="getRetailerPo();">
                                                            <option value="">-- Select Retailer --</option>
                                                            <?php
                                                            foreach (getActiveRetailerByBdmId($bdm_detail->retailer_id) as $row) {
                                                                echo "<option value='" . $row->id . "'>" . $row->name . "</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <label>Purchase Order No</label>
                                                        <input type="text" class="form-control input-sm txt_cls_po_no" placeholder="Purchase Order No" style="text-transform: uppercase;" readonly name="txt_cls_po_no" value="">

                                                    </div>

                                                    <div class="col-md-12">
                                                        <label>Select Item</label> <span style='color:red'>*</span>
                                                        <div class="ittem_hhtml">
                                                            <select class=" form-control input-sm sel_cls_item">
                                                                <option value="">-- Select Item --</option>

                                                            </select>

                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label>Price</label> <span style='color:red'>*</span>
                                                        <input type="text" class="form-control input-sm txt_cls_price" name="txt_cls_price" value="" readonly>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label>UOM</label> <span style='color:red'>*</span>
                                                        <input type="text" class="form-control input-sm txt_cls_uom" name="txt_cls_uom" value="" readonly>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label>Qty Required</label> <span class='text-red'>*</span>
                                                        <input type="text" class="form-control input-sm txt_cls_qty" name="txt_cls_qty" value="" placeholder="Quantity Required">
                                                    </div>
                                                    <div class="col-md-6 marg_tp_one">
                                                        <label>AvailableStock</label> <span class='text-red'></span>
                                                        <input type="text" class="form-control input-sm txt_cls_stock" name="txt_cls_stock" value="" placeholder="Available Stock" readonly>
                                                    </div>
                                                    <div class="col-md-12">&nbsp;</div>
                                                </div>
                                            </div>

                                            <div class="box-footer bg-gray">
                                                <div class="col-md-12">
                                                    <button type="button" class="btn btn-sm btn-primary btn_cls_add">Add</button>


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
                                                            <td width='10%' align='right'>Price</td>
                                                            <td align="center" width='8%'>Action</td>
                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                    </tbody>

                                                </table>
                                            </div>

                                            <div class="box-footer bg-gray">
                                                <br><br>
                                                Customer Name : <input type="text" name="cus_name" id="cus_name" />&nbsp;&nbsp;&nbsp;
                                                Customer Mobile : <input type="text" name="cus_ph" id="cus_ph" /><br><br>
                                                Customer Address : <input type="text" name="cus_add" id="cus_add" /><br>
                                                <br><br>
                                                <input type="button" class="btn btn-sm btn-warning btn_cls_confirm" value="Confirm" />
                                            </div>

                                            <div class="col-md-12" style="border-bottom:1px solid #ccc;
                                                 margin-top: 2%; margin-bottom: 5%;"></div>


                                            <!-- get print of an invoice  -->
                                            <!-- <div class="col-md-12" style="border:1px solid #ccc; padding:3%;
                                                        box-shadow: 2px 3px #ccc;">
                                                        
        
                                                        <div class="col-lg-9" style="display: inline-block;">
                                                            <span class="hkcurrency" style="font-size: 15px; color:#999;">REG-</span>
                                                            <input type="text" class="form-control txt_invoice_no"
                                                            style="padding-left:35%; font-size: 15px; 
                                                            color:orange; border: 1px solid #ccc; width:95%"
                                                            placeholder="Enter DC No" value="18-19-">
                                                        </div>
                                                        
                                                        <div class="col-md-3">
                                                            <button type="button" id="btn_get_print" name="btn_get_print"
                                                                class="btn btn-sm btn-success">Get Print</button>
                                                        </div>
        
                                                        <div id="div_outward_print"></div>
                                                    </div> -->
                                            <!-- get print of an invoice  -->


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



                //reset fields
                function reset_fields() {
                    $('.sel_cls_retailer').val('').trigger('chosen:updated');
                    $('.sel_cls_item').val('').trigger('chosen:updated');
                    $('.txt_cls_price').val("");
                    $('.txt_cls_uom').val("");
                    $('.txt_cls_qty').val("");
                    $('.trn_id').val("");
                }

                //price of products
                function getPrice() {
                    var retailer_id = $('.sel_cls_retailer').val();
                    $.ajax({
                        url: '<?php echo $ajax_page; ?>',
                        type: 'POST',
                        data: {
                            retailer_id: retailer_id,
                            request_type: "get_price_from_list"
                        },
                        async: false,
                        success: function (data) {
                            $('#total_amount').html(data);
                            $('.txt_ordered_amount').val(data);
                        }
                    });
                }

                function getRetailerPo() {
                    var retailer_id = $('.sel_cls_retailer').val();
                    $.ajax({
                        url: '<?php echo $ajax_page; ?>',
                        type: 'POST',
                        data: {
                            retailer_id: retailer_id,
                            request_type: "get_retailer_po"
                        },
                        async: false,
                        success: function (data) {
                            // alert(data);
                            var detail = JSON.parse(data);
                            $('.txt_cls_po_no').val(detail.po_no);
                            $('.ittem_hhtml').html(detail.item_html);
                            $('.chosen-select').chosen();
                        }
                    });
                }

                //refresh table if any entry available
                function refresh_small_inventory_item_list() {
                    var retailer_id = $('.sel_cls_retailer').val();
                    $.ajax({
                        url: '<?php echo $ajax_page; ?>',
                        type: 'POST',
                        data: {
                            retailer_id: retailer_id,
                            request_type: "get_auto_refresh_list"
                        },
                        async: false,
                        success: function (data) {
                            $('.tbl_cls_inventory_list').find('tbody').empty().append(data);
                        }
                    });
                }


                //on change item get qty
                $(document).on("change", ".sel_cls_item", function () {
                    var item_code = $('.sel_cls_item').val();
                    var sel_cls_retailer = $('.sel_cls_retailer').val();

                    data = {
                        item_code: item_code,
                        sel_cls_retailer: sel_cls_retailer,
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
                        }
                    });
                });


                //add outward gooods to temp table
                $('.btn_cls_add').click(function () {
                    //txt_ordered_amount        txt_dealer_balance

                    var intReg = /[0-9-()+]+$/;

                    if ($('.sel_cls_item').val() == '') {
                        alert("Please select an item");
                        $('.sel_cls_item').focus();
                        return false;
                    } else if ($('.txt_cls_price').val() == '' || $('.txt_cls_price').val() <= 0) {
                        alert("Please check product price. Price must be grater then 0.");
                        $('.txt_cls_price').focus();
                        return false;
                    } else if ($('.txt_cls_qty').val() == '' || $('.txt_cls_qty').val() == 0) {
                        alert("Quantity is required");
                        $('.txt_cls_qty').val('');
                        $('.txt_cls_qty').focus();
                        return false;
                    } else if (!intReg.test($("input[name=txt_cls_qty]").val())) {
                        alert("Please Enter a valid Zip Code");
                        $("input[name=txt_cls_qty]").focus();
                        return false;
                    }
                    // else if($('.trn_id').val() == ''){
                    //     alert("Please Enter a Valid Transaction number.");
                    //     $("input[name=trn_id]").focus();
                    //     return false;
                    // }

                    if ($('.txt_cls_stock').val() < $('.txt_cls_qty').val()) {
                        alert("Please Check Available Stock.");
                        $('.txt_cls_qty').focus();
                        return false;
                    }

                    po_no = $('.txt_cls_po_no').val();


                    item_code = $('.sel_cls_item').val();
                    item_desc = $('.sel_cls_item option:selected').text();

                    price = $('.txt_cls_price').val();

                    qty = $('.txt_cls_qty').val();
                    uom = $('.txt_cls_uom').val();
                    retailer_id = $('.sel_cls_retailer').val();
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


                    // alert(dealer_type);
                    if (parseInt(dealer_bal) < parseInt(final_order_total) && dealer_type == '1') {
                        alert("You do not have available eWallet amount to purchase these items. Please recharge your eWallet account.");
                        return false;
                    }


                    data = {
                        po_no: po_no,
                        retailer_id: retailer_id,
                        item_code: item_code,
                        item_desc: item_desc,
                        price: price,
                        qty: qty,
                        uom: uom,
                        request_type: "insert_to_temp_table"
                    }

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
                                // $('.sel_cls_retailer').focus();
                            } else if (data == 1) {
                                refresh_small_inventory_item_list();
                                reset_fields();
                                getPrice();
                            } else {
                                alert(data);
                                // console.log(data);
                            }
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
                    var po_no = $('.txt_cls_po_no').val();
                    var order_no = $('.txt_order_no').val();
                    var amount = $('.txt_ordered_amount').val();
                    var trn_id = $('.trn_id').val();
                    var po_num_1 = $('.po_num_1').val();
                    var retailer_id = $('.sel_cls_retailer').val();
                    var cus_name = $('#cus_name').val();
                    var cus_ph = $('#cus_ph').val();
                    var cus_add = $('#cus_add').val();

                    if (trn_id == '') {
                        alert("Please Enter a Valid Transaction number.");
                        $('.trn_id').focus();
                        return false;

                    }

                    if (po_num_1 !== po_no) {
                        alert("Please Delete Your OLD Order Then Order Again It .");
                        //                location.href="small_inventory_order.php?menu=119";
                        return false;
                    }

                    var data = {
                        cus_name: cus_name,
                        cus_ph: cus_ph,
                        cus_add: cus_add,
                        po_no: po_no,
                        order_no: order_no,
                        amount: amount,
                        trn_id: trn_id,
                        retailer_id: retailer_id,
                        request_type: "confirm_order"
                    }

                    if (confirm("Are you sure to order the list of selected items ?")) {
                        $.ajax({
                            url: '<?php echo $ajax_page; ?>',
                            type: 'POST',
                            data: data,
                            async: false,
                            success: function (data) {
                                // alert(data);
                                // document.write(data);
                                if (data == 0) {
                                    alert("There is no item to place your order");
                                    //                            window.location = window.location;
                                } else if (data == 2) {
                                    alert("You do not have available eWallet amount to purchase these items. Please recharge your eWallet account.");
                                    // window.location = "book_sale.php";
                                    window.location = window.location;
                                } else if (data == 3) {
                                    alert("Your order has been successfully placed.");
                                    window.location = window.location;
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