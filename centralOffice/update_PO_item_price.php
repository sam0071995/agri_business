<?php
require_once 'includes/session.php';
require_once 'includes/common_function.php';
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
                        <?php require_once 'includes/page-header.php'; ?> <div class="page-header">

                            <div class="row">
                                <div class="col-xs-12">
                                    <?php
                                    if (isset($_GET['error'])) {
                                        switch ($_GET['error']) {
                                            case 1:
                                                $msg = "Item can not be insert.";
                                                break;
                                            case 101:
                                                $msg = "Image can not uploaded.";
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
                                            <?php echo "product Updated Successfully"; ?>
                                        </div>
                                    <?php } ?>
                                    <h3 class="page-header">Update PO Details.</h3>
                                    <form class="form-horizontal" id="searchform" name="myForm"  role="form" action="" method="POST" enctype="multipart/form-data">
                                        <div class="form-group" id="c_n_password_c">
                                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Search PO<span style="color:red">*</span> : </label>
                                            <div class="col-sm-9">
                                                <select class="form-field-select-2 form-control chosen-select" name="po_no" required="required">
                                                    <option value="">--search PO--</option>
                                                    <?php foreach (getGroupPONOListByUserId() as $po_detail) { ?>
                                                        <option value="<?php echo $po_detail->id; ?>" <?php
                                                        ?>>
                                                            PO No : <b><?php echo $po_detail->po_no; ?></b> | PO Date : <?php echo $po_detail->po_date; ?> | Store : <?php echo getRetailerNameById($po_detail->retailer_id); ?> | Vendor : <?php echo getVendorNameById($po_detail->vendor_id); ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="clearfix form-actions">
                                            <div class="col-md-offset-3 col-md-9">
                                                <button type="submit" name="submit" class="btn btn-info">
                                                    <i class="ace-icon fa fa-check bigger-110"></i>
                                                    <?php echo "Update"; ?>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div><!-- /.row -->
                            <?php if (isset($_POST['submit'])) { ?>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <h3>Purchase Order Details</h3>
                                        <hr/>
                                        <?php
                                        if (isset($_POST['po_no'])) {
                                            $po_no = $_POST['po_no'];
                                            $purchase_details = getPoDetailsByPoNo($po_no);
                                            $index = 1;
                                            $po_master_detail = getPurchaseOrdergetItemCountById($po_no);
                                            $vendor_detail = getVendorDetailById($po_master_detail->vendor_id);
                                            ?>

                                            PO No : <b><?php echo $po_master_detail->po_no; ?></b> | 
                                            PO Date : <b><?php echo $po_master_detail->po_date; ?></b> | 
                                            Invoice No : <b><?php echo $po_master_detail->invoie_no; ?></b> | 
                                            Store : <b><?php echo getRetailerNameById($po_master_detail->retailer_id); ?></b><br/><hr/>
                                            Vendor : <b><?php echo $vendor_detail->vendor_name; ?></b> | 
                                            Vendor Address : <b><?php echo $vendor_detail->address; ?></b><hr/>
                                            <table class="table table-responsive">
                                                <thead>
                                                    <tr>
                                                        <td>SrNo.</td>
                                                        <td>Item Name</td>
                                                        <td>Qty</td>
                                                        <td>Rate</td>
                                                        <td>GST Rate</td>
                                                        <td>Amount</td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    foreach ($purchase_details as $purchase_detail) {
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $index; ?></td>
                                                            <td><?php echo getItemNameByItemCode($purchase_detail->item_id); ?></td>
                                                            <td><?php echo $purchase_detail->qty; ?></td>
                                                            <td><?php echo $purchase_detail->rate; ?></td>
                                                            <td><?php echo $purchase_detail->gst_rate; ?></td>
                                                            <td><?php echo $purchase_detail->amount; ?></td>
                                                            <td>Rate : <input type="text" name="rate" class="rate_<?php echo $purchase_detail->unique_id; ?>" /></td>
                                                            <td>GST Rate : 
                                                                <select name="gst_rate" class="gst_rate_<?php echo $purchase_detail->unique_id; ?>">
                                                                    <option <?php
                                                                    if ($purchase_detail->gst_rate == '0.00') {
                                                                        echo 'selected="selected"';
                                                                    }
                                                                    ?> value="0">0</option>
                                                                    <option <?php
                                                                    if ($purchase_detail->gst_rate == '5.00') {
                                                                        echo 'selected="selected"';
                                                                    }
                                                                    ?> value="5">5</option>
                                                                    <option <?php
                                                                    if ($purchase_detail->gst_rate == '12.00') {
                                                                        echo 'selected="selected"';
                                                                    }
                                                                    ?> value="12">12</option>
                                                                    <option <?php
                                                                    if ($purchase_detail->gst_rate == '18.00') {
                                                                        echo 'selected="selected"';
                                                                    }
                                                                    ?> value="18">18</option>
                                                                    <option <?php
                                                                    if ($purchase_detail->gst_rate == '28.00') {
                                                                        echo 'selected="selected"';
                                                                    }
                                                                    ?> value="28">28</option>
                                                                </select>
                                                            </td>
                                                            <td class="Update_button_<?php echo $purchase_detail->unique_id; ?>">
                                                                <button class="btn btn-danger Update_Price" id="<?php echo $purchase_detail->unique_id; ?>">Update</button>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                        $index++;
                                                    }
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            <?php } ?>
                        </div><!-- /.page-content -->
                    </div>
                </div><!-- /.main-content -->

                <!--END MAIN WRAPPER -->
                <?php require_once 'includes/footer.php'; ?>    

                <script>

                    $(document).on("click", ".Update_Price", function () {

                        var id = $(this).attr("id");

                        var rate = $(".rate_" + id).val();
                        var gst_rate = $(".gst_rate_" + id).val();

                        $.ajax({
                            url: "ajax.php?menu=1",
                            type: "POST",
                            data: {
                                types: "Update_PO_rate",
                                unique_id: id,
                                rate: rate,
                                gst_rate: gst_rate
                            },
                            success: function (response) {
                                response = response.trim();

                                if (response == '100') {
                                    alert("Updated Successfully");
                                    $(".Update_button_" + id).html("Updated");
                                } else {
                                    alert("Rate Not Updated!");
                                }
                            }
                        });

                    });

                </script>
            </div>
    </body>
</html>

