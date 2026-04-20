<?php
error_reporting(0);
require_once 'includes/session.php';
require_once 'includes/common_function.php';

extract($_POST);

if ($_POST['types'] == 'get_inventory_price_popup') {
    ?>

    <?php
    $table_name = "retailer_inventory_master";
    $Retailer_id_array = $_POST['Retailer_id'];
    $inventory_item_array = $_POST['inventory_item'];
    ?>
    <form action="" method="POST" enctype="multipart/form-data" >
        <table  class="table table-bordered">
            <thead>
                <tr>
                    <th>Retailer</th>
                    <th>Item</th>
                    <th>Last PO Basic</th>
                    <th>Last PO GST Rate(%)</th>
                    <th>Basic</th>
                    <th>Cgst</th>
                    <th>Sgst</th>
                    <th>Igst</th>
                    <th>Total</th>
                </tr>
            </thead>

            <tbody>
                <?php
                foreach ($Retailer_id_array as $retailer_id) {
                    foreach ($inventory_item_array as $inventory_item_id) {
                        $retailer_item_data = getRetailerItemById($inventory_item_id, $retailer_id);
                        $get_retailer_price_data = getRetialerPriceDataByIdAndItem($inventory_item_id, $retailer_id);
                        $old_basic_price = $get_retailer_price_data->basic_price;
                        $old_total_price = $get_retailer_price_data->total;
                        $old_cgst = $get_retailer_price_data->cgst_value;
                        $old_sgst = $get_retailer_price_data->sgst_value;
                        $old_igst = $get_retailer_price_data->igst_value;
                        $last_po_basic = 0;
                        $last_po_gst = 0;
                        if (isset($get_retailer_price_data->last_po_basic)) {
                            $last_po_basic = $get_retailer_price_data->last_po_basic;
                            $last_po_gst = $get_retailer_price_data->last_po_gst;
                        }

                        $inventory_item_data = getproductDetailsById($inventory_item_id);
//                    if (count($retailer_item_data) > 0) {
                        ?>
                        <tr>
                            <td>
                                <?php echo getRetailerById($retailer_id)->name; ?>
                                <input type="hidden" name="retailer_id_arr[]" value="<?php echo $retailer_id; ?>" />
                            </td>
                            <td>
                                <?php echo $inventory_item_data->item_desc; ?>
                                <input type="hidden" name="item_id_arr[]" value="<?php echo $inventory_item_id; ?>" />
                            </td>
                            <td>
                                <?php echo $last_po_basic; ?>
                            </td>
                            <td>
                                <?php echo $last_po_gst; ?>
                            </td>
                            <td><input type="text" size="4" name="basic_val_arr[]" id="basic_price_<?php echo $inventory_item_id . "_" . $retailer_id; ?>" value="<?php echo (!empty($old_basic_price)) ? $old_basic_price : '0'; ?>" readonly="" /></td>
                            <td>
                                <input type="text" size="4" name="cgst_val_arr[]" id="cgst_price_<?php echo $inventory_item_id . "_" . $retailer_id; ?>" value="<?php echo $old_cgst; ?>" readonly="" /><br /><br />
                                CgstRate : <?php echo $inventory_item_data->cgst_rate; ?>
                            </td>
                            <td>
                                <input type="text" size="4" name="sgst_val_arr[]" id="sgst_price_<?php echo $inventory_item_id . "_" . $retailer_id; ?>" value="<?php echo $old_sgst; ?>" readonly=""/><br /><br />
                                SgstRate : <?php echo $inventory_item_data->sgst_rate; ?>
                            </td>
                            <td>
                                <input type="text" size="4" name="igst_val_arr[]" id="igst_price_<?php echo $inventory_item_id . "_" . $retailer_id; ?>" value="<?php echo $old_igst; ?>" readonly=""/><br /><br />
                                IgstRate : <?php echo $inventory_item_data->igst_rate; ?>
                            </td>
                            <td>
                                <input type="text" size="4" name="total_val_arr[]" id="total_price_<?php echo $inventory_item_id . "_" . $retailer_id; ?>" value="<?php echo $old_total_price; ?>" onchange="cal_another_price_reverce('<?php echo $inventory_item_id; ?>', '<?php echo $retailer_id; ?>');" />

                                <input type="hidden" id="cgst_rate_<?php echo $inventory_item_id . "_" . $retailer_id; ?>" value="<?php echo $inventory_item_data->cgst_rate; ?>" />
                                <input type="hidden" id="igst_rate_<?php echo $inventory_item_id . "_" . $retailer_id; ?>" value="<?php echo $inventory_item_data->igst_rate; ?>" />
                            </td>
                        </tr>

                        <?php
//                    }
                    }
                }
                ?>
                <tr>
                    <td>
                        <div class="col-md-offset-3 col-md-9">
                            <button type="submit" name="submit_multiple" class="btn btn-info">
                                <i class="ace-icon fa fa-check bigger-110"></i>
                                <?php echo "UPDATE"; ?>
                            </button>

                        </div>
                    </td>
                </tr>
            </tbody>

        </table>
    </form>


    <?php
}
?>

