<?php
//echo 'This screen is closed.';
//exit;
require_once 'includes/session.php';
require_once 'includes/common_function.php';
$status = 1;
$igst_rate = 0;
$sgst_rate = 0;
$cgst_rate = 0;
$basic_price = 0;
$get_item_id = 0;
$get_retailer_id = 0;
//if (isset($_GET['item']) && isset($_GET['retailer'])) {
//    $get_item_id = base64_decode($_GET['item']);
//    $get_retailer_id = base64_decode($_GET['retailer']);
//    if ($get_retailer_id != "All") {
//        $productData = getRetailerItemById($get_item_id, $get_retailer_id);
//
//        $basic_price = $productData->basic_price;
//        $igst_rate = $productData->igst_rate;
//        $sgst_rate = $productData->sgst_rate;
//        $cgst_rate = $productData->cgst_rate;
//    }
//}

if (isset($_POST['submit'])) {
    $table_name = "retailer_inventory_master";
    $Retailer_id = $_POST['Retailer_id'];
    $inventory_item_sr_no = trim($_POST['inventory_item_sr_no']);
    $inventory_item_sr_no_array = explode("||", $inventory_item_sr_no);
    $item_code = $inventory_item_sr_no_array[0];
    $old_batch_no = $inventory_item_sr_no_array[1];
    $old_expiry_date = $inventory_item_sr_no_array[2];
    $old_manufacturing_date = $inventory_item_sr_no_array[3];
    $new_batch_qty = round($_POST['new_batch_qty']);
    $system_qty = getFreeBatchQty($Retailer_id, $item_code, $old_batch_no, $old_expiry_date);
    if ($system_qty < $new_batch_qty) {
        header("Location:delete_extra_batch_update.php" . $menuURL . "&error=3");
        exit;
    }

    $item_sr_update_history = array();
    $item_sr_update_history['user_id'] = $user_id;
    $item_sr_update_history['retailer_id'] = $Retailer_id;
    $item_sr_update_history['item_code'] = $item_code;
    $item_sr_update_history['old_batch_no'] = $old_batch_no;
    $item_sr_update_history['old_expiry_date'] = $old_expiry_date;
    $item_sr_update_history['old_manu_date'] = $old_manufacturing_date;
    $item_sr_update_history['qty'] = $new_batch_qty;
    $item_sr_update_history['status'] = 2;
    $item_sr_update_history['datetime'] = date("Y-m-d H:i:s");
    $insert = insert("item_sr_update_history", $item_sr_update_history);
    if ($insert) {
        $item_sr_master_data = array();
        $item_sr_master_data['status'] = 11;
        $item_sr_master_data['added_by'] = $_SESSION['username'];
        $item_sr_master_data['remarks'] = "Deleted By : " . $username . "(" . $user_id . ")";
        $item_sr_master_data['update_datetime'] = date("Y-m-d H:i:s");
        $item_sr_master_where = "status='0' and item_code='$item_code' and retailer_id='$Retailer_id' and batch_no='$old_batch_no' and expire_date='$old_expiry_date'";
        $update = updateLimit("item_sr_master", $item_sr_master_data, $item_sr_master_where, $new_batch_qty);
        if ($update) {
            header("Location:delete_extra_batch_update.php" . $menuURL . "&success=1&item=" . base64_encode($inventory_item_id) . "&retailer=" . base64_encode($Retailer_id));
            exit;
        } else {
            header("Location:delete_extra_batch_update.php" . $menuURL . "&error=1");
            exit;
        }
    } else {
        header("Location:delete_extra_batch_update.php" . $menuURL . "&error=2");
        exit;
    }
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
                                <?php
                                if (isset($_GET['error'])) {
                                    switch ($_GET['error']) {
                                        case 1:
                                            $msg = "Item can not be insert.";
                                            break;
                                        case 3:
                                            $msg = "Please enter valid Batch qty.";
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
                                        <?php echo "Batch Deleted Successfully"; ?>
                                    </div>
                                <?php } ?>
                                <h3 class="page-header">Inventory Item | <b class="red">Delete</b> Batch.</h3>
                                <form class="form-horizontal" id="searchform" name="myForm"  role="form" action="" method="POST" enctype="multipart/form-data">
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Select Distributer<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <select class="Retailer_id form-field-select-2 form-control chosen-select" name="Retailer_id" id="Retailer_id" required="required">
                                                <option value="">--Select Distributer--</option>
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
                                        <div class="col-sm-6 inventory_item_free_sr">
                                            <select class="form-field-select-2 form-control chosen-select" name="inventory_item_sr_no" id="inventory_item_free_sr_select" required="required">
                                                <option value="">--Select Item | BatchNo | ExpiryDate--</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Delete Qty<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <input type="text" name="new_batch_qty" id="new_batch_qty" placeholder="Enter New Item Batch Qty" class="form-control" required="required" value="<?php echo ""; ?>"/>
                                        </div>
                                    </div>
                                    <div class="clearfix form-actions">
                                        <div class="col-md-offset-3 col-md-9">
                                            <button type="submit" name="submit" class="btn btn-danger">
                                                <i class="ace-icon fa fa-check bigger-110"></i>
                                                <?php echo "DELETE"; ?>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div><!-- /.row -->
                    </div><!-- /.page-content -->
                </div>
            </div><!-- /.main-content -->

            <!--END MAIN WRAPPER -->
            <?php require_once 'includes/footer.php'; ?>    
            <script type="text/javascript">

                $(document).on("keyup", "#new_batch_qty", function () {
                    this.value = this.value.replace(/[^0-9]/g, '');

                });

                $(".Retailer_id").change(function () {
                    var Retailer_id = $(".Retailer_id").val();
                    $.ajax({
                        type: "POST",
                        url: "ajax.php?menu=1",
                        data: {
                            'types': 'get_retailer_free_item_sr_no',
                            'Retailer_id': Retailer_id
                        },
                        success: function (data) {
                            $(".inventory_item_free_sr").html(data);
                            $("#inventory_item_free_sr_select").attr("class", "form-field-select-2 form-control chosen-select");
                            $('.chosen-select').chosen();
                        }
                    });
                });
            </script>
        </div>
    </body>
</html>

