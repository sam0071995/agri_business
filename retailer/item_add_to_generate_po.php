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
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                    <h3 class="header">
                                        Request Purchase Order Item Entry
                                        <a href="report_for_po_item_list.php?menu=403" class="btn btn-warning btn-sm" style="float:right;" target="_blank">Report</a>
                                        <br />
                                        <br />
                                    </h3>
                                </div>

                                <form role="form" method="post" action="">


                                    <div class="col-md-6">
                                        <div class="box box-primary">
                                            <div class="box-header with-border hidden">
                                            </div>

                                            <div class="box-body">
                                                <div class="form-group">


                                                    <div class="col-md-12 marg_tp_one">
                                                        <label>Select Item</label> <span style='color:red'>*</span>
                                                        <select class="select2 form-control input-sm sel_cls_item" autofocus>
                                                            <option value="">-- Select Item --</option>
                                                            <?php
//                                                            foreach (getInventoryItem($_SESSION['id']) as $row) {
                                                            foreach (getActiveItemsList() as $row) {
                                                                echo "<option value='" . $row->item_code . "'>" . $row->item_desc . "</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <br />
                                                    <br />
                                                    <br />




                                                    <div class="col-md-12 marg_tp_one">
                                                        <label>UOM</label> <span style='color:red'>*</span>
                                                        <input type="text" class="form-control input-sm txt_cls_uom" name="txt_cls_uom" value="" readonly>
                                                    </div>

                                                    <div class="col-md-12 marg_tp_one">
                                                        <label>AvailableStock - <?php echo $_SESSION['name']; ?></label> <span class='text-red'></span>
                                                        <input type="text" class="form-control input-sm txt_cls_stock" name="txt_cls_stock" value="" placeholder="Available Stock" readonly>
                                                    </div>
                                                    <div class="div_AvailableStock"></div>
                                                    <div class="col-md-6 marg_tp_one">
                                                        <label>Qty Required</label> <span class='text-red'>*</span>
                                                        <input type="text" class="form-control input-sm txt_cls_qty" name="txt_cls_qty" value="" placeholder="Quantity Required">
                                                    </div>
                                                    <div class="col-md-6 marg_tp_one">
                                                        <label>Liquidation Days</label> <span class='text-red'>*</span>
                                                        <input type="text" class="form-control input-sm Liquidation_Days" name="Liquidation_Days" value="" placeholder="Liquidation Days">
                                                    </div>
                                                    <div class="col-md-12 marg_tp_one">
                                                        <label>Remarks</label> <span class='text-red'>*</span>
                                                        <textarea name="remarks" class="remarks form-control"></textarea>
                                                    </div>


                                                    <div class="col-md-12">&nbsp;</div>
                                                </div>
                                                <div class="col-md-6 marg_tp_one">
                                                    <button type="button" class="btn btn-sm btn-primary btn_cls_add">Add</button>
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

                                    <div class="col-md-6" id="div_items">
                                        <div class="box box-primary">
                                            <div class="box-header with-border">
                                                <h4 class="pull-left">List of Po Items</h4>

                                            </div>

                                            <div class="box-body">
                                                <table class="table table-stripped table-bordered table-hover table-condensed table-responsive tbl_cls_inventory_list">
                                                    <thead>
                                                        <tr class="text-bold bg-gray">
                                                            <td width='5%' align='center'>#</td>
                                                            <td width='25%'>Item</td>
                                                            <td width='10%' align='center'>Quantity</td>
                                                            <td width='10%' align='right'>Available stock</td>
                                                            <td width='10%' align='right'>Liquidation Days</td>
                                                            <td width='10%' align='right'>Remarks</td>
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
                                                            <input type="button" class="btn btn-sm btn-warning btn_cls_confirm" value="Confirm" />
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>

                                            <!--                                            <div class="col-md-12" style="border-bottom:1px solid #ccc;
                                                                                             margin-top: 2%; margin-bottom: 5%;"></div>-->




                                        </div>
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
                    $('.sel_cls_item').val('').trigger('chosen:updated');
//                    $('.txt_cls_price').val("");
                    $('.txt_cls_uom').val("");
                    $('.txt_cls_qty').val("");
                    $('.txt_cls_stock').val("");
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
                            $('.txt_ordered_amount').val(data);
                        }
                    });
                }

                //refresh table if any entry available
                function refresh_small_inventory_item_list() {
                    $.ajax({
                        url: '<?php echo $ajax_page; ?>',
                        type: 'POST',
                        data: {
                            request_type: "get_retailer_po_item_list"
                        },
                        async: false,
                        success: function (data) {
                            $('.tbl_cls_inventory_list').find('tbody').empty().append(data);
                        }
                    });
                }




                $(document).on("change", ".sel_cls_item", function () {
                    var item_code = $('.sel_cls_item').val();
                    var data = {
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
                            $('.txt_cls_uom').val(obj.unit);
                            $('.txt_cls_stock').val(obj.current_stock);
                            var data_a = {
                                item_code: item_code,
                                request_type: "get_availability_for_all_store"
                            }

                            $.ajax({
                                url: '<?php echo $ajax_page; ?>',
                                type: 'POST',
                                data: data_a,
                                async: false,
                                success: function (data) {
                                    $('.div_AvailableStock').html(data);
                                }
                            });
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
                    } else if ($('.Liquidation_Days').val() == '0') {
                        alert("Liquidation Days is required.");
                        $('.Liquidation_Days').focus();
                        $(".loader").css("display", "none");
                        return false;
                    } else if ($('.remarks').val() == '') {
                        alert("Enter Remarks.");
                        $('.remarks').focus();
                        $(".loader").css("display", "none");
                        return false;
                    }

                    var requiredstck = $('.txt_cls_qty').val();
                    requiredstck = requiredstck.replace(/\D/g, "");


                    var item_code = $('.sel_cls_item').val();
                    var Liquidation_Days = $('.Liquidation_Days').val();
                    var item_desc = $('.sel_cls_item option:selected').text();
//                    var price = $('.txt_cls_price').val();
                    var qty = $('.txt_cls_qty').val();
                    var uom = $('.txt_cls_uom').val();
                    var availablestck = $('.txt_cls_stock').val();
                    var remarks = $('.remarks').val();

//                    var vertual_total = parseInt(price) * parseInt(qty);
//                    var dealer_bal = $('.txt_dealer_balance').val();
//                    var ordered_amt = $('.txt_ordered_amount').val();
//                    var final_order_total = parseInt(vertual_total) + parseInt(ordered_amt);

                    var data = {
                        item_code: item_code,
                        item_desc: item_desc,
                        availablestck: availablestck,
                        Liquidation_Days: Liquidation_Days,
                        qty: qty,
                        uom: uom,
                        remarks: remarks,
                        request_type: "insert_to_retailer_po_table"
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
                    // alert(id);

                    if (confirm("Are you sure to remove this item from the list?")) {
                        $.ajax({
                            url: '<?php echo $ajax_page; ?>',
                            type: 'POST',
                            data: {
                                id: id,
                                request_type: "remove_item_retailer_po_list"
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



                    var data = {
                        request_type: "retailer_po_item_confirm_order"
                    }

                    if (confirm("Are you sure to order the list of selected items ?")) {
                        $.ajax({
                            url: '<?php echo $ajax_page; ?>',
                            type: 'POST',
                            data: data,
                            async: false,
                            success: function (data) {
                                if (data == 0) {
                                    alert("Your order has been successfully placed.");
                                    window.location = window.location;
                                } else if (data == 1) {
                                    alert("Order Place Error");
                                } else {
                                    alert("Order Place Error");
                                }
                            }
                        });
                    }
                });



            </script>

            <!--END MAIN WRAPPER -->
            <?php require_once 'includes/footer.php'; ?>
        </div>
    </body>

</html>