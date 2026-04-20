<?php
//echo 'Now we can not add new batch here. Thank You.';
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

if (isset($_POST['submit'])) {
    $table_name = "item_sr_master";
    $Retailer_id = $_POST['Retailer_id'];
    $item_code = $_POST['item_code'];
    $new_batch_no = $_POST['new_batch_no'];
    $expiry_date = $_POST['expiry_date'];
    $manu_date = $_POST['manu_date'];
    $inward_date = $_POST['inward_date'];
    $new_batch_qty = $_POST['new_batch_qty'];
    $purchas_basic = $_POST['purchas_basic'];
    $purchase_gst_rate = $_POST['purchase_gst_rate'];
    $purchas_total = $purchas_basic * $purchase_gst_rate / 100;
    $purchas_total = $purchas_total + $purchas_basic;

    if (ltrim(date('m')) > 3) {
        $cd = date('y');
        $dd = $cd + 1;
    } else {
        $dd = date('y');
        $cd = $dd - 1;
    }
    $fin_year = $cd . '' . $dd;
    $inc_no = getLastSrIncNo($fin_year, $Retailer_id);

    for ($i = 0; $i < $new_batch_qty; $i++) {
        $retailer_id_sr = sprintf('%02d', $Retailer_id);
        $inc_no_sr = sprintf('%08d', $inc_no);
        $serial_number = $fin_year . $retailer_id_sr . $inc_no_sr;
        $insarr = array();
        $insarr['serial_number'] = $serial_number;
        $insarr['batch_no'] = $new_batch_no;
        $insarr['manufacturing_date'] = date('Y-m-d', strtotime($manu_date));
        $insarr['expire_date'] = date('Y-m-d', strtotime($expiry_date));
        $insarr['item_desc'] = getItemNameByItemCode($item_code);
        $insarr['item_code'] = $item_code;
        $insarr['item_id'] = getItemIdByItemCode($item_code);
        $insarr['retailer_id'] = $Retailer_id;
        $insarr['company_id'] = getRetailerCompanyIdById($Retailer_id);
        $insarr['grn_id'] = 0;
        $insarr['purchase_basic'] = $purchas_basic;
        $insarr['gst'] = $purchase_gst_rate;
        $insarr['total'] = $purchas_total;
        $insarr['inc_no'] = $inc_no;
        $insarr['fin_year'] = $fin_year;
        $insarr['date'] = date('Y-m-d', strtotime($inward_date));
        $insarr['datetime'] = date('Y-m-d', strtotime($inward_date));
        $insarr['added_by'] = $_SESSION['username'];
        $insert = insert('item_sr_master', $insarr);
        $inc_no++;
    }
    if ($insert) {
        header("Location:inventory_batch_add_item.php" . $menuURL . "&success=1");
        exit;
    } else {
        header("Location:inventory_batch_add_item.php" . $menuURL . "&error=1");
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
                                        <?php echo "Batch No & Expiry Date Added Successfully"; ?>
                                    </div>
                                <?php } ?>
                                <h3 class="page-header">Inventory Item | Add BatchNo & Expiry Date.</h3>
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
                                            <select class="form-field-select-2 form-control chosen-select" name="item_code" id="item_code" required="required">
                                                <option value="">--Select Item --</option>
                                                <?php
                                                foreach (getProductsList() as $product) {
                                                    echo "<option value='" . $product->item_code . "'>" . $product->item_desc . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">  Batch No<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <input type="text" name="new_batch_no" id="new_batch_no" placeholder="Enter New Item Batch No" class="form-control" required="required" value="<?php echo ""; ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1">  Expiry Date<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <input class="form-control date-picker" id="expiry_date" name="expiry_date" type="text" value="<?php
                                            if (isset($_POST['expiry_date'])) {
                                                echo $_POST['expiry_date'];
                                            } else {
                                                echo date('d-m-Y');
                                            }
                                            ?>" data-date-format="dd-mm-yyyy" />
                                        </div>
                                    </div>
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Manufacturing Date<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <input class="form-control date-picker" id="manu_date" name="manu_date" type="text" value="<?php
                                            if (isset($_POST['manu_date'])) {
                                                echo $_POST['manu_date'];
                                            } else {
                                                echo date('d-m-Y');
                                            }
                                            ?>" data-date-format="dd-mm-yyyy" />
                                        </div>
                                    </div>
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Inward Date<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <input class="form-control date-picker" id="inward_date" name="inward_date" type="text" value="<?php
                                            if (isset($_POST['inward_date'])) {
                                                echo $_POST['inward_date'];
                                            } else {
                                                echo date('d-m-Y');
                                            }
                                            ?>" data-date-format="dd-mm-yyyy" />
                                        </div>
                                    </div>

                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Qty<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <input type="text" name="new_batch_qty" id="new_batch_qty" placeholder="Enter New Item Batch Qty" class="form-control" required="required" value="<?php echo ""; ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Purchase Basic<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <input type="text" name="purchas_basic" id="purchas_basic" placeholder="Enter Purchase Basic Price" class="form-control" required="required" value="<?php echo ""; ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-group" id="c_n_password_c">
                                        <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> GST Rate<span style="color:red">*</span> : </label>
                                        <div class="col-sm-6">
                                            <input type="text" name="purchase_gst_rate" id="purchase_gst_rate" placeholder="Enter Purchase GST" class="form-control" required="required" value="<?php echo ""; ?>"/>
                                        </div>
                                    </div>
                                    <div class="clearfix form-actions">
                                        <div class="col-md-offset-3 col-md-9">
                                            <button type="submit" name="submit" class="btn btn-info">
                                                <i class="ace-icon fa fa-check bigger-110"></i>
                                                <?php echo "Add"; ?>
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
<!--            <script type="text/javascript">
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
            </script>-->
        </div>
    </body>
</html>

