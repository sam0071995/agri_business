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
// $inc_no = getLastIncNo($fin_year, $_SESSION['id']);
// if ($inc_no == 0) {
// $inc_no = 1;
// } else {
// $inc_no = $inc_no + 1;
// }
// echo $_SESSION['company_id'];
// $comp_data = getCompanyDetailById($_SESSION['company_id']);
// if ($fin_year == '2425') {
// $po_number = "AGRO" . $_SESSION['id'] . "" . $fin_year . "" . $inc_no;
// } else {
// $po_number = $comp_data->prefix ."/". $_SESSION['id'] . "/" . $fin_year . "/" . $inc_no;
// }
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
                                    Physical Audit Entry <a href="physical_audit_record_entry_report.php?menu=449"><button class="btn btn-primary">Entry Report</button></a>
                                </h3>

                                <form role="form" method="post" action="">


                                    <div class="col-md-4">
                                        <div class="box box-primary">
                                            <div class="box-header with-border hidden">
                                            </div>

                                            <div class="box-body">
                                                <div class="form-group">

                                                    <div class="col-md-12 marg_tp_one">
                                                        <label>Select Item</label> <span style='color:red'>*</span>
                                                        <input type="hidden" class="form-control input-sm txt_audit_cycle_no" placeholder="Enter audit cycle number" style="text-transform: uppercase;"  name="txt_audit_cycle_no" value="AUDIT001">
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



                                                    <div class="col-md-12 marg_tp_one">
                                                        <label>Batch Number</label> <span class='text-red'></span>

                                                        <input type="text" class="form-control input-sm txt_batch_no" name="txt_batch_no" required="required" value="" placeholder="Batch Number">

                                                    </div>

                                                    <div class="col-md-6 marg_tp_one">
                                                        <label>Batch Wise Qty </label> <span class='text-red'>*</span>
                                                        <input type="text" class="form-control input-sm txt_cls_qty" id="txt_cls_qty" name="txt_cls_qty"  value="" placeholder="Batch Wise Quantity ">
                                                    </div>

                                                    <div class="col-md-6 marg_tp_one">
                                                        <label>Expire Date </label> <span class='text-red'>*</span>
                                                        <input type="date" class="form-control input-sm txt_expire_date" id="txt_expire_date" name="txt_expire_date"  >
                                                    </div>
                                                    <div class="col-md-6 marg_tp_one">
                                                        <label>Qty in Bag/carton</label> <span class='text-red'>*</span>
                                                        <input type="text" class="form-control input-sm packet_no" id="packet_no" name="packet_no"  value="" placeholder="Packet No ">

                                                    </div>                                                    
                                                    <div class="col-md-6 marg_tp_one">
                                                        <label>MRP </label> <span class='text-red'>*</span>
                                                        <input type="text" class="form-control input-sm mrp" id="mrp" name="mrp"  value="" placeholder="Enter MRP">

                                                    </div>

                                                    <div class="col-md-6 marg_tp_one" style="margin-top : 30px;">
                                                        <button type="button" class="btn btn-sm btn-primary btn_cls_add">Add</button>
                                                    </div>
                                                    <div class="col-md-12">&nbsp;</div>
                                                </div>
                                            </div>


                                        </div>
                                    </div>

                                    <div class="col-md-8" id="div_items">
                                        <div class="box box-primary">
                                            <div class="box-header with-border">
                                                <h4 class="pull-left">List of Audit Items</h4>
                                               <!--  <h4 class="pull-right">  Total Amount: <small id="total_amount"></small></h4> -->
                                            </div>

                                            <div class="box-body">
                                                <table class="table table-stripped table-bordered table-hover table-condensed table-responsive tbl_cls_inventory_list">
                                                    <thead>
                                                        <tr class="text-bold bg-gray">
                                                            <td width='5%' align='center'>#</td>
                                                            <td width='15%'>AuditDate</td>
                                                            <td width='15%'>Item</td>
                                                            <td width='10%' align='right'>BatchNo</td>
                                                            <td width='10%' align='center'>Qty</td>
                                                            <td width='10%' align='right'>Qty in Bag/carton</td>
                                                            <td width='10%' align='right'>MRP</td>
                                                            <td width='10%' align='right'>Expirydate</td>
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
                                                            Remark : <input type="text" name="remark" id="remark" class="remark" placeholder="Enter Your Remark Here" />
                                                        </td>
                                                        <td>
                                                            <input type="button" class="btn btn-sm btn-warning btn_cls_confirm" value="Confirm" />
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>




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


                //add outward gooods to temp table
                $('.btn_cls_add').click(function () {
                    $(".loader").css("display", "block");

                    var intReg = /[0-9-()+]+$/;


                    if ($('.sel_cls_item').val() == '') {
                        alert("Please select an item");
                        $('.sel_cls_item').focus();
                        $(".loader").css("display", "none");
                        return false;
                    }

                    if ($('.txt_cls_qty').val() == '' || $('.txt_cls_qty').val() == 0) {
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

                    if ($('.txt_batch_no').val() == '') {
                        alert("Batch Number is required.");
                        $('.txt_batch_no').focus();
                        $(".loader").css("display", "none");
                        return false;
                    }
                    if ($('.packet_no').val() == '') {
                        alert("Packet No is required.");
                        $('.packet_no').focus();
                        $(".loader").css("display", "none");
                        return false;
                    }

                    if ($('.mrp').val() == '') {
                        alert("MRP is  required.");
                        $('.mrp').focus();
                        $(".loader").css("display", "none");
                        return false;
                    }



                    var txt_batch_no = $('.txt_batch_no').val();
                    var packet_no = $('.packet_no').val();
                    var batch_wise_qty = $('.txt_cls_qty').val();
                    var txt_expire_date = $('.txt_expire_date').val();
                    var mrp = $('.mrp').val();

                    var item_code = $('.sel_cls_item').val();
                    var item_desc = $('.sel_cls_item option:selected').text();





                    data = {
                        txt_batch_no: txt_batch_no,
                        packet_no: packet_no,
                        item_code: item_code,
                        item_desc: item_desc,
                        batch_wise_qty: batch_wise_qty,
                        txt_expire_date: txt_expire_date,
                        mrp: mrp,
                        request_type: "insert_to_physicl_audit_tbl_cart"
                    }

                    //console.log(data);
                    $.ajax({
                        url: 'ajax_js.php',
                        type: 'POST',
                        data: data,
                        async: false,
                        success: function (data) {
                            $(".loader").css("display", "none");
                            // alert(data);
                            // reset_fields();
                            // return false;
                            if (data == 0) {
                                alert("Data added successfully.");
                                reset_fields();
                                $('.txt_audit_cycle_no').focus();
                                refresh_small_inventory_item_list();
                            }
                            $(".loader").css("display", "none");
                        }
                    });
                });

                //refresh table if any entry available
                function refresh_small_inventory_item_list() {
                    $.ajax({
                        url: '<?php echo $ajax_page; ?>',
                        type: 'POST',
                        data: {
                            request_type: "get_auto_refresh_list_for_phy_stock_entry"
                        },
                        async: false,
                        success: function (data) {
                            $('.tbl_cls_inventory_list').find('tbody').empty().append(data);
                        }
                    });
                }


                //reset fields
                function reset_fields() {
                    $('.sel_cls_item').val('').trigger('change');
                    $('.txt_audit_cycle_no').val("");
                    $('.txt_cls_qty').val("");
                    $('.txt_batch_no').val("");
                    $('.txt_audit_date').val("");
                }



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
                                request_type: "remove_item_from_list_for_phy_stock_entry"
                            },
                            async: false,
                            success: function (data) {
                                if (data == 1) {
                                    refresh_small_inventory_item_list();

                                } else {
                                    alert("Data Delete Error..!!");
                                }
                            }
                        });
                    }
                });

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



                $(document).on("click", ".btn_cls_confirm", function () {
                    var remark = $('#remark').val();

                    if (remark == '') {
                        alert("Please Enter Remark.");
                        $('#remark').focus();
                        return false;
                    }

                    var data = {
                        remark: remark,
                        request_type: "confirm_physical_audit_record"
                    }


                    if (confirm("Are you sure to confirm submission ?")) {
                        $.ajax({
                            url: '<?php echo $ajax_page; ?>',
                            type: 'POST',
                            data: data,
                            async: false,
                            success: function (data) {
                                if (data == 0) {
                                    alert("Data Confirmed Successfully..!");
                                    window.location = window.location;
                                } else {
                                    alert("Data Confirmed Error..!");
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