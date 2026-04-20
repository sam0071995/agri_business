<?php
error_reporting(0);
require_once 'includes/session.php';
require_once 'includes/common_function.php';
$status = 1;
$igst_rate = 0;
$sgst_rate = 0;
$cgst_rate = 0;
$basic_price = 0;
$get_item_id = 0;
$get_retailer_id = 0;
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
                                <?php
                                if (isset($_GET['error'])) {
                                    switch ($_GET['error']) {
                                        case 1:
                                            $msg = "Item can not be insert.";
                                            break;
                                        default:
                                            $msg = "Something Wrong.";
                                            break;
                                    }
                                    ?>
                                    <div class="alert alert-block alert-danger">
                                        <button type="button" class="close" data-dismiss="alert">
                                            <i class="ace-icon fa fa-times"></i>
                                        </button>

                                        <i class="ace-icon fa fa-check red form-error-msg"></i>
                                        <?php echo $msg; ?>
                                    </div>
                                <?php } ?>
                                <?php if (isset($_GET['success'])) { ?>
                                    <div class="alert alert-block alert-success">
                                        <button type="button" class="close" data-dismiss="alert">
                                            <i class="ace-icon fa fa-times"></i>
                                        </button>

                                        <i class="ace-icon fa fa-check green form-error-msg"></i>
                                        <?php echo "Product Price Updated Successfully"; ?>
                                    </div>
                                <?php } ?>
                                <h3 class="page-header">Inventory Item | Price Update.</h3>
                                <form class="form-horizontal" id="searchform" name="myForm"  role="form" action="" method="POST" enctype="multipart/form-data">
                                    <div class="row" >
                                        <div class="form-group" id="c_n_password_c">
                                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Distributer<span style="color:red">*</span> : </label>
                                            <div class="col-sm-5">
                                                <select class="form-field-select-2 form-control" multiple name="Retailer_id[]" id="Retailer_id" required="required">
                                                    <!--<option value="">--Select Distributer--</option>-->
                                                    <!--<option value="All">All Distributer</option>-->
                                                    <?php foreach (getActiveRetailerDetails($company_id_in) as $active_sellers) { ?>
                                                        <option value="<?php echo $active_sellers->id; ?>" <?php
                                                        if ($active_sellers->id == $get_retailer_id) {
                                                            echo "selected='selected'";
                                                        }
                                                        ?>><?php echo $active_sellers->name; ?></option>
                                                            <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group" id="c_n_password_c">
                                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Select Item<span style="color:red">*</span> : </label>
                                            <div class="col-sm-5">
                                                <select class="form-field-select-2 form-control chosen-sele" multiple name="inventory_item[]"  id="inventory_item_tst"  required="required">
                                                    <!--<option value="">--Select Item--</option>-->
                                                    <?php foreach (getActiveItemsList() as $inventiry_item) { ?>
                                                        <option value="<?php echo $inventiry_item->id; ?>" <?php
                                                        if ($inventiry_item->id == $get_item_id) {
                                                            echo "selected='selected'";
                                                        }
                                                        ?>><?php echo $inventiry_item->item_desc; ?></option>
                                                            <?php } ?>
                                                </select>
                                            </div>
                                        </div>


                                    </div>
                                    <div class="clearfix form-actions">
                                        <div class="col-md-offset-3 col-md-9">
                                            <button type="button" name="checkprice" class="btn btn-info" onclick="openPriceModelPopup();">
                                                <i class="ace-icon fa fa-search bigger-110"></i>
                                                PriceDetail
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div><!-- /.row -->





                        <div id="model_html">

                        </div>


                        <?php
                        if (isset($_POST['submit_multiple'])) {
                            $table_name = "retailer_inventory_master";
                            $Retailer_id_array = $_POST['retailer_id_arr'];
                            $Inventory_item_array = $_POST['item_id_arr'];
                            $Basic_val_array = $_POST['basic_val_arr'];
                            $Cgst_val_array = $_POST['cgst_val_arr'];
                            $Sgst_val_array = $_POST['sgst_val_arr'];
                            $Igst_val_array = $_POST['igst_val_arr'];
                            $Total_val_array = $_POST['total_val_arr'];
//                            echo "<pre />";
//                            print_r($_POST);
//                            exit();

                            for ($i = 0; $i <= count($Retailer_id_array); $i++) {
                                $inventory_item_data = getproductDetailsById($Inventory_item_array[$i]);

                                if (!empty($Inventory_item_array[$i])) {
                                    if (isset($inventory_item_data->id)) {

                                        $data['item_id'] = $Inventory_item_array[$i];
                                        $data['sr_no'] = $inventory_item_data->sr_no;
                                        $data['main_category_id'] = $inventory_item_data->main_category_id;
                                        $data['sub_category_id'] = $inventory_item_data->sub_category_id;
                                        $data['brand_name'] = $inventory_item_data->brand_name;
                                        $data['item_code'] = $inventory_item_data->item_code;
                                        $data['item_desc'] = $inventory_item_data->item_desc;
                                        $data['hsn_code'] = $inventory_item_data->hsn_code;
                                        $data['item_desc'] = $inventory_item_data->item_desc;
                                        $data['status'] = $inventory_item_data->status;
                                        $data['uom'] = $inventory_item_data->uom;
                                        $data['inc_no'] = $inventory_item_data->inc_no;
                                        $data['description'] = $inventory_item_data->description;
                                        $data['PC_per_carton'] = $inventory_item_data->PC_per_carton;
                                        $data['shelf_life'] = $inventory_item_data->shelf_life;
                                        $data['pack_size'] = $inventory_item_data->pack_size;
                                        $data['unit'] = $inventory_item_data->unit;

//                                        $count_for_bsic = 100 / (100 + $inventory_item_data->igst_rate);
//                                        $item_basic_price = $item_total_price * $count_for_bsic;
//                                        $cgst_value = ($item_basic_price * $inventory_item_data->cgst_rate) / 100;
//                                        $sgst_value = ($item_basic_price * $inventory_item_data->sgst_rate) / 100;
//                                        $igst_value = ($item_basic_price * $inventory_item_data->igst_rate) / 100;
//                                        $item_total = $igst_value + $item_basic_price;

                                        $data['basic_price'] = $Basic_val_array[$i];
                                        $data['cgst_value'] = $Cgst_val_array[$i];
                                        $data['cgst_rate'] = $inventory_item_data->cgst_rate;
                                        $data['sgst_value'] = $Sgst_val_array[$i];
                                        $data['sgst_rate'] = $inventory_item_data->sgst_rate;
                                        $data['igst_value'] = $Igst_val_array[$i];
                                        $data['igst_rate'] = $inventory_item_data->igst_rate;
                                        $data['total'] = $Total_val_array[$i];


                                        $data['retailer_id'] = $Retailer_id_array[$i];
                                        $data['company_id'] = getRetailerCompanyIdById($Retailer_id_array[$i]);
                                        $retailer_item_data = getRetailerItemById($Inventory_item_array[$i], $Retailer_id_array[$i]);
                                        $old_basic_price = getRetailerItemBasicPriceById($Inventory_item_array[$i], $Retailer_id_array[$i]);
                                        $old_total_price = getRetailerItemTotalPriceById($Inventory_item_array[$i], $Retailer_id_array[$i]);
                                        if (isset($retailer_item_data->id)) {
                                            $data['updated_date'] = date('Y-m-d h:i:s');
                                            $where = "item_id='" . $Inventory_item_array[$i] . "' AND retailer_id='" . $Retailer_id_array[$i] . "'";
                                            $product = update($table_name, $data, $where);
                                            if ($product) {
                                                ?>
                                                <div class="alert alert-block alert-success">
                                                    <button type="button" class="close" data-dismiss="alert">
                                                        <i class="ace-icon fa fa-times"></i>
                                                    </button>

                                                    <i class="ace-icon fa fa-check green form-error-msg"></i>
                                                    <?php echo getRetailerById($Retailer_id_array[$i])->name . " - " . $inventory_item_data->item_desc . " Price Updated Successfully..!!"; ?>
                                                </div>
                                                <?php
                                            } else {
                                                ?>
                                                <div class="alert alert-block alert-danger">
                                                    <button type="button" class="close" data-dismiss="alert">
                                                        <i class="ace-icon fa fa-times"></i>
                                                    </button>

                                                    <i class="ace-icon fa fa-check red form-error-msg"></i>
                                                    <?php echo getRetailerById($Retailer_id_array[$i])->name . " - " . $inventory_item_data->item_desc . " Price Updated Error."; ?>
                                                </div>
                                                <?php
                                            }
                                        } else {
                                            $data['date'] = date('Y-m-d');
                                            $data['datetime'] = date('Y-m-d h:i:s');
                                            $product = insert($table_name, $data);

                                            if ($product) {
                                                ?>
                                                <div class="alert alert-block alert-success">
                                                    <button type="button" class="close" data-dismiss="alert">
                                                        <i class="ace-icon fa fa-times"></i>
                                                    </button>

                                                    <i class="ace-icon fa fa-check green form-error-msg"></i>
                                                    <?php echo getRetailerById($Retailer_id_array[$i])->name . " - " . $inventory_item_data->item_desc . "  Price Insert Successfully..!!"; ?>
                                                </div>
                                                <?php
                                            } else {
                                                ?>
                                                <div class="alert alert-block alert-danger">
                                                    <button type="button" class="close" data-dismiss="alert">
                                                        <i class="ace-icon fa fa-times"></i>
                                                    </button>

                                                    <i class="ace-icon fa fa-check red form-error-msg"></i>
                                                    <?php echo getRetailerById($Retailer_id_array[$i])->name . " - " . $inventory_item_data->item_desc . " Price Insert Error."; ?>
                                                </div>
                                                <?php
                                            }
                                        }

                                        $data_history_for_inventory_master = array();
                                        $data_history_for_inventory_master['item_id'] = $Inventory_item_array[$i];
                                        $data_history_for_inventory_master['retailer_id'] = $Retailer_id_array[$i];
                                        $data_history_for_inventory_master['company_id'] = getRetailerCompanyIdById($Retailer_id_array[$i]);
                                        $data_history_for_inventory_master['old_price'] = $old_basic_price;
                                        $data_history_for_inventory_master['old_total'] = $old_total_price;
                                        $data_history_for_inventory_master['new_price'] = $Basic_val_array[$i];
                                        $data_history_for_inventory_master['new_total'] = $Total_val_array[$i];
                                        $data_history_for_inventory_master['remarks'] = "PriceUpdate";
                                        $data_history_for_inventory_master['user_name'] = $username;
                                        $data_history_for_inventory_master['user_id'] = $user_id;
                                        $data_history_for_inventory_master['date'] = date("Y-m-d H:i:s");
                                        $history_for_inventory_master = insert('history_for_inventory_master', $data_history_for_inventory_master);
                                    } else {
//                                        
                                        ?>

                                        <div class="alert alert-block alert-danger">
                                            <button type="button" class="close" data-dismiss="alert">
                                                <i class="ace-icon fa fa-times"></i>
                                            </button>

                                            <i class="ace-icon fa fa-check red form-error-msg"></i>
                                            <?php echo getRetailerById($Retailer_id_array[$i])->name . " - " . $inventory_item_data->item_desc; ?> Item Not Available.
                                        </div>
                                        <?php
                                    }
                                }
                            }
                        }
                        ?>





                    </div><!-- /.page-content -->
                </div>
            </div><!-- /.main-content -->



            <script type="text/javascript">

                function openPriceModelPopup() {
                    var Retailer_id = $("#Retailer_id").val();
                    var inventory_item = $("#inventory_item_tst").val();
//                    console.log(Retailer_id);
//                    return false;
                    if (Retailer_id == null) {
                        alert('Please select distributer');
                        return false;
                    }
                    if (inventory_item == null) {
                        alert('Please select item');
                        return false;
                    }
                    $.ajax({
                        url: 'page_for_modal_popup.php?menu=1',
                        type: 'POST',
                        data: {types: 'get_inventory_price_popup', inventory_item: inventory_item, Retailer_id: Retailer_id},
                        success: function (resp) {
//                            console.log(resp);
                            $('#model_html').html(resp);
                        }
                    });
                }

                function cal_another_price(iteamid) {
                    var basic_valu = $("#basic_price_" + iteamid).val();
                    var cgst_rate = $("#cgst_rate_" + iteamid).val();
                    var cgst_price = Number(Math.round(basic_valu * cgst_rate) / 100);
                    var sgst_price = Number(Math.round(basic_valu * cgst_rate) / 100);
                    var igst_price = Number((Math.round(basic_valu * cgst_rate) / 100) * 2);
                    var total_price = Number(Math.round(Number(basic_valu) + Number(igst_price)));

                    $("#cgst_price_" + iteamid).val(cgst_price);
                    $("#sgst_price_" + iteamid).val(cgst_price);
                    $("#total_price_" + iteamid).val(total_price);
                }

                function cal_another_price_reverce(iteamid, retailer_id) {
                    var total_price = $("#total_price_" + iteamid + "_" + retailer_id).val();
                    var igst_rate = $("#igst_rate_" + iteamid + "_" + retailer_id).val();
                    var cgst_rate = $("#cgst_rate_" + iteamid + "_" + retailer_id).val();

                    var item_basic_price = ((Number(total_price) * 100) / (100 + Number(igst_rate))).toFixed(2);
//                    alert(item_basic_price);
                    var cgst_price = (Number(item_basic_price * cgst_rate) / 100).toFixed(2);
                    var sgst_price = (Number(item_basic_price * cgst_rate) / 100).toFixed(2);
                    var igst_price = (((item_basic_price * cgst_rate) / 100) * 2).toFixed(2);

                    $("#cgst_price_" + iteamid + "_" + retailer_id).val(cgst_price);
                    $("#sgst_price_" + iteamid + "_" + retailer_id).val(cgst_price);
                    $("#igst_price_" + iteamid + "_" + retailer_id).val(igst_price);
                    $("#basic_price_" + iteamid + "_" + retailer_id).val(item_basic_price);
                }


                $('#inventory_item_tst_').on('change', function () {
                    var inventory_item = $("#inventory_item_tst").val();
                    var Retailer_id = $("#Retailer_id").val();
                    if (inventory_item != '') {
                        $(".loader").css("display", "block");
                        $.ajax({
                            url: 'ajax.php?menu=1',
                            type: 'POST',
                            data: {types: 'get_inventory_gst_rate_test', inventory_item: inventory_item, Retailer_id: Retailer_id},
                            success: function (data) {
//                                console.log(data);
                                $('#item_gst_rate').html(data);
                                $(".loader").css("display", "none");
                            }
                        });
                    }
                });


                $('#inventory_item_tst').multiselect({
                    columns: 1, // how many columns should be use to show options
                    search: true, // include option search box
                    texts: {
                        placeholder: '-- Select Item --', // text to use in dummy input
                    },
                    selectAll: true
                });
                $('#Retailer_id').multiselect({

                    columns: 1, // how many columns should be use to show options
                    search: true, // include option search box
                    texts: {
                        placeholder: '-- Select Distributer --', // text to use in dummy input
                    },
                    selectAll: true
                });
            </script>

            <!--END MAIN WRAPPER -->
            <?php require_once 'includes/footer.php'; ?>    
        </div>
    </body>
</html>

