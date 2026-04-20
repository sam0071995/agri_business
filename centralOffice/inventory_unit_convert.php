<?php
error_reporting(0);
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
                        <?php require_once 'includes/page-header.php'; ?>
                        <div class="row">
                            <div class="col-xs-12">

                                <h3 class="page-header">Retailer Inventory Loose Item Converter.</h3>
                                <form class="form-horizontal" id="searchform" name="myForm"  role="form" action="" method="POST" enctype="multipart/form-data">
                                    <div class="row" >
                                        <div class="form-group" id="c_n_password_c">
                                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Distributer<span style="color:red">*</span> : </label>
                                            <div class="col-sm-5">
                                                <select class="form-field-select-2 form-control"  name="Retailer_id" id="Retailer_id" required="required">
                                                    <option value="">-- Select Distributer --</option>
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
                                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Select Item Convert From<span style="color:red">*</span> : </label>
                                            <div class="col-sm-5">
                                                <select class="form-field-select-2 form-control chosen-select"  name="inventory_item"  id="inventory_item" onchange="getItemBatchNumber();"  required="required">
                                                    <option value="">-- Select Item --</option>
                                                    <?php foreach (getItemsListUOMWise() as $inventiry_item) { ?>
                                                        <option value="<?php echo $inventiry_item->id; ?>" <?php
                                                        if ($inventiry_item->id == $get_item_id) {
                                                            echo "selected='selected'";
                                                        }
                                                        ?>><?php echo $inventiry_item->item_desc; ?></option>
                                                            <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group" id="c_n_password_c">
                                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Select BatchNumber<span style="color:red">*</span> : </label>
                                            <div class="col-sm-5">
                                                <select class="form-field-select-2 form-control chosen-sel"  name="item_batch_no"  id="item_batch_no"   required="required">
                                                    <option value="">-- Select BatchNumber --</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group" id="c_n_password_c">
                                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Select  Item Convert To<span style="color:red">*</span> : </label>
                                            <div class="col-sm-5">
                                                <select class="form-field-select-2 form-control chosen-select"  name="inv_item_convert_to"  id="inv_item_convert_to"   required="required">
                                                    <option value="">-- Select Item --</option>
                                                    <?php foreach (getItemsListUOMWiseTwo() as $inventiry_item) { ?>
                                                        <option value="<?php echo $inventiry_item->id; ?>" <?php
                                                        if ($inventiry_item->id == $get_item_id) {
                                                            echo "selected='selected'";
                                                        }
                                                        ?>><?php echo $inventiry_item->item_desc; ?></option>
                                                            <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group" id="c_n_password_c">
                                            <label class="col-sm-3 control-label no-padding-right" for="form-field-1-1"> Enter Quantity<span style="color:red">*</span> : </label>
                                            <div class="col-sm-5">
                                                <input type="text" class="form-control" name="item_qty" id="item_qty" pattern="[0-9]" placeholder="aplease enter item  quantity " required="" />
                                            </div>
                                        </div>


                                    </div>
                                    <div class="clearfix form-actions">
                                        <div class="col-md-offset-3 col-md-9">
                                            <button type="submit" name="convertitem" class="btn btn-info" >
                                                <i class="ace-icon fa fa-search bigger-110"></i>
                                                Convert
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div><!-- /.row -->





                        <?php
                        if (isset($_POST['convertitem'])) {
                            $retailer_id = $_POST['Retailer_id'];
                            $item_qty = $_POST['item_qty'];
                            $inventory_item = $_POST['inventory_item'];
                            $item_batch_no = $_POST['item_batch_no'];
                            $inv_item_convert_to = $_POST['inv_item_convert_to'];

                            $inward_qty_input = ($item_qty * 1000);

                            $itemsrdata = getItemSrMasterDataByItemIdAndRetailerIdBatchNo($retailer_id, $inventory_item, $item_batch_no);

                            $inventory_master_data = getproductDetailsById($inventory_item);
                            $inventory_master_data_to_item = getproductDetailsById($inv_item_convert_to);

                            // for stock update  for convert from item ====================================
                            $getretailerinvdata = getRetialerPriceDataByIdAndItem($inventory_item, $retailer_id);
                            $inv_master_data_f = array();
                            $inv_master_data_f['issued_stock'] = ($getretailerinvdata->issued_stock + $item_qty);
                            $inv_master_data_f['current_stock'] = ($getretailerinvdata->current_stock - $item_qty);
//                            print_r($inv_master_data_f);
//                            exit();
                            $whr_f = "item_code = '" . $itemsrdata->item_code . "' and retailer_id = '" . $retailer_id . "'";
                            $update_f = update('retailer_inventory_master', $inv_master_data_f, $whr_f);

                            $itmarr = array();
                            $itmarr['status'] = 7;
                            $itmarr['remarks'] = "BLOCK AS PER CONVERT ITEM PROCESS AT " . date('Y-m-d H:i:s');
                            $itmarr['update_datetime'] = date('Y-m-d H:i:s');
                            $whr_for_upd = "retailer_id = '" . $retailer_id . "' and item_id='" . $inventory_item . "' and batch_no='" . $item_batch_no . "'";
                            $limitt = $item_qty;
                            updateLimit('item_sr_master', $itmarr, $whr_for_upd, $limitt);

                            // for stock update  for convert from item ====================================
                            // 
                            // 
                            // 
                            // for stock update  for convert to item ====================================
                            $item_convert_to_data = getRetialerPriceDataByIdAndItem($inv_item_convert_to, $retailer_id);

                            if (count($item_convert_to_data) == 0) {

                                $dataRetailerMaster = array();
                                $dataRetailerMaster['item_id'] = $inventory_master_data_to_item->id;
                                $dataRetailerMaster['item_desc'] = $inventory_master_data_to_item->item_desc;
                                $dataRetailerMaster['item_code'] = $inventory_master_data_to_item->item_code;
                                $dataRetailerMaster['company_id'] = $getretailerinvdata->company_id;
                                $dataRetailerMaster['updated_date'] = date('Y-m-d h:i:s');
                                $dataRetailerMaster['datetime'] = date('Y-m-d h:i:s');
                                $dataRetailerMaster['date'] = date('Y-m-d');
                                $dataRetailerMaster['hsn_code'] = $inventory_master_data_to_item->hsn_code;
                                $dataRetailerMaster['main_category_id'] = $inventory_master_data_to_item->main_category_id;
                                $dataRetailerMaster['sub_category_id'] = $inventory_master_data_to_item->sub_category_id;
                                $dataRetailerMaster['status'] = 1;
                                $dataRetailerMaster['uom'] = $inventory_master_data_to_item->uom;
                                $dataRetailerMaster['active'] = 1;
                                $dataRetailerMaster['retailer_id'] = $retailer_id;
                                $dataRetailerMaster['basic_price'] = round(($getretailerinvdata->basic_price / 1000), 3);
                                $dataRetailerMaster['cgst_value'] = round(($getretailerinvdata->cgst_value / 1000), 3);
                                $dataRetailerMaster['sgst_value'] = round(($getretailerinvdata->sgst_value / 1000), 3);
                                $dataRetailerMaster['igst_value'] = round(($getretailerinvdata->igst_value / 1000), 3);
                                $dataRetailerMaster['total'] = round(($getretailerinvdata->total / 1000), 3);
                                $product_retailer = insert('retailer_inventory_master', $dataRetailerMaster);
                            }
                            $item_convert_to_data = getRetialerPriceDataByIdAndItem($inv_item_convert_to, $retailer_id);

                            $inv_master_data_t = array();
                            $inv_master_data_t['receive_stock'] = $item_convert_to_data->receive_stock + $inward_qty_input;
                            $inv_master_data_t['current_stock'] = $item_convert_to_data->current_stock + $inward_qty_input;
                            $whr_t = "item_code = '" . $item_convert_to_data->item_code . "' and retailer_id = '" . $retailer_id . "'";
                            $update_t = update('retailer_inventory_master', $inv_master_data_t, $whr_t);


                            if (ltrim(date('m')) > 3) {
                                $cd = date('y');
                                $dd = $cd + 1;
                            } else {
                                $dd = date('y');
                                $cd = $dd - 1;
                            }
                            $fin_year = $cd . '' . $dd;
                            $inc_no = getLastSrIncNo($fin_year, $retailer_id);
                            for ($i = 0; $i < $inward_qty_input; $i++) {
                                $retailer_id_sr = sprintf('%02d', $retailer_id);
                                $inc_no_sr = sprintf('%08d', $inc_no);
                                $serial_number = $fin_year . $retailer_id_sr . $inc_no_sr;
                                $insertSr_no = array();
                                $insertSr_no['serial_number'] = $serial_number;
                                $insertSr_no['batch_no'] = $item_batch_no;
                                $insertSr_no['manufacturing_date'] = date("Y-m-d", strtotime($itemsrdata->manufacturing_date));
                                $insertSr_no['expire_date'] = date("Y-m-d", strtotime($itemsrdata->expire_date));
                                $insertSr_no['item_desc'] = $item_convert_to_data->item_desc;
                                $insertSr_no['item_id'] = $item_convert_to_data->item_id;
                                $insertSr_no['item_code'] = $item_convert_to_data->item_code;
                                $insertSr_no['vendor_id'] = $itemsrdata->vendor_id;
                                $insertSr_no['retailer_id'] = $retailer_id;
                                $insertSr_no['po_no'] = $itemsrdata->po_no;
                                $insertSr_no['purchase_basic'] = round(($itemsrdata->purchase_basic / 1000), 3);
                                $insertSr_no['gst'] = $itemsrdata->gst;
                                $insertSr_no['total'] = round(($itemsrdata->total / 1000), 3);
                                $insertSr_no['grn_id'] = $itemsrdata->grn_id;
                                $insertSr_no['inc_no'] = $inc_no;
                                $insertSr_no['fin_year'] = $fin_year;
                                $insertSr_no['company_id'] = $itemsrdata->company_id;
                                $insertSr_no['date'] = date("Y-m-d");
                                $insertSr_no['datetime'] = date("Y-m-d H:i:s");
                                $insert = insert("item_sr_master", $insertSr_no);
                                $inc_no++;
                            }


                            // for stock update  for convert to item ====================================


                            $hiss_arr = array();
                            $hiss_arr['retailer_id'] = $retailer_id;
                            $hiss_arr['item_from'] = $inventory_item;
                            $hiss_arr['item_to'] = $inv_item_convert_to;
                            $hiss_arr['item_from_batch'] = $item_batch_no;
                            $hiss_arr['item_qty'] = $item_qty;
                            $hiss_arr['item_from_issue_stock'] = $getretailerinvdata->issued_stock;
                            $hiss_arr['item_from_current_stock'] = $getretailerinvdata->current_stock;
                            $hiss_arr['item_to_receive_stock'] = $item_convert_to_data->receive_stock;
                            $hiss_arr['item_to_current_stock'] = $item_convert_to_data->current_stock;
                            $hiss_arr['update_date'] = date("Y-m-d H:i:s");
                            $hiss_arr['update_by'] = $_SESSION['username'];
                            insert('intem_convert_history', $hiss_arr);

                            if ($update_f && $update_t) {
                                ?>
                                <div class="alert alert-block alert-success">
                                    <button type="button" class="close" data-dismiss="alert">
                                        <i class="ace-icon fa fa-times"></i>
                                    </button>

                                    <i class="ace-icon fa fa-check green form-error-msg"></i>
                                    <?php echo " Item Convert Successfully..!!"; ?>
                                </div>
                                <?php
                            } else {
                                ?>
                                <div class="alert alert-block alert-danger">
                                    <button type="button" class="close" data-dismiss="alert">
                                        <i class="ace-icon fa fa-times"></i>
                                    </button>

                                    <i class="ace-icon fa fa-check red form-error-msg"></i>
                                    <?php echo " Item Convert Error."; ?>
                                </div>
                                <?php
                            }
                        }
                        ?>





                    </div><!-- /.page-content -->
                </div>
            </div><!-- /.main-content -->



            <script type="text/javascript">

                function getItemBatchNumber() {
                    var retailer_id = document.getElementById('Retailer_id').value;
                    var inventory_item = document.getElementById('inventory_item').value;

                    if (retailer_id == '') {
                        alert('Please select Retailer...!!');
                        document.getElementById('Retailer_id').focus();
                        return false;
                    }
                    if (inventory_item == '') {
                        alert('Please select Item...!!');
                        document.getElementById('inventory_item').focus();
                        return false;
                    }

                    $.ajax({
                        url: 'ajax_agro.php?menu=1',
                        method: 'post',
                        data: {types: 'get_batch_number_by_itemid_retaielr_id', retailer_id: retailer_id, inventory_item: inventory_item},
                        success: function (reslt) {
                            if (reslt == '') {
                                alert('No BatchNumber Available..!');
                            }
                            document.getElementById('item_batch_no').innerHTML = reslt;
                        }
                    });
                }
            </script>

            <!--END MAIN WRAPPER -->
            <?php require_once 'includes/footer.php'; ?>    
        </div>
    </body>
</html>

