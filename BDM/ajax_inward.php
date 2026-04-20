<?php
session_start();
error_reporting(0);
require_once 'includes/common_function.php';

date_default_timezone_set('Asia/Kolkata');

extract($_POST);
if (isset($_POST['request_type'])) {

    if ($_POST['request_type'] == 'inward_grn') {
        $id = $_POST['id'];
        $retailer_id = $_SESSION['id'];
        $grn_data = getGrnDataByGrnid($id);

        $update_data = array();
        $update_data['retailer_inwd_flg'] = 1;
        $update_data['retailer_inwd_date'] = date('Y-m-d');
        $update_data_whr = "id = '$id' and retailer_inwd_flg = '0' and retailer_id = '$retailer_id'";
        $uupd = update('inventory_grn', $update_data, $update_data_whr);

        if ($uupd) {

            $check_stock_dup = getStockCountByItemCodeAndRetailerId($retailer_id, $grn_data->item_desc);

            if (count($check_stock_dup) > 0) {
                $inv_master_data = array();
                $inv_master_data['receive_stock'] = $check_stock_dup->receive_stock + $grn_data->billed_qty;
                $inv_master_data['current_stock'] = $check_stock_dup->current_stock + $grn_data->billed_qty;
                $whr = "item_code = '$grn_data->item_desc' and retailer_id = '$retailer_id'";
                $update = update('retailer_inventory_master', $inv_master_data, $whr);
            } else {
                $inventory_data = getInventoryDataByCode($grn_data->item_desc);
                $inv_master_data = array();
                $inv_master_data['retailer_id'] = $retailer_id;
                $inv_master_data['item_id'] = $inventory_data->id;
                $inv_master_data['item_code'] = $inventory_data->item_code;
                $inv_master_data['item_desc'] = $inventory_data->item_desc;
                $inv_master_data['opening_stock'] = '0';
                $inv_master_data['receive_stock'] = $grn_data->billed_qty;
                $inv_master_data['issued_stock'] = '0';
                $inv_master_data['current_stock'] = $grn_data->billed_qty;
                $inv_master_data['minimum_stock'] = '0';
                $inv_master_data['hsn_code'] = $inventory_data->hsn_code;
                $inv_master_data['basic_price'] = $inventory_data->basic_price;
                $inv_master_data['cgst_rate'] = $inventory_data->cgst_rate;
                $inv_master_data['cgst_value'] = $inventory_data->cgst_value;
                $inv_master_data['sgst_rate'] = $inventory_data->sgst_rate;
                $inv_master_data['sgst_value'] = $inventory_data->sgst_value;
                $inv_master_data['igst_rate'] = $inventory_data->igst_rate;
                $inv_master_data['igst_value'] = $inventory_data->igst_value;
                $inv_master_data['total'] = $inventory_data->total;
                $inv_master_data['status'] = '1';
                $inv_master_data['uom'] = $inventory_data->uom;
                $inv_master_data['date'] = date('Y-m-d');
                $inv_master_data['active'] = '1';

                $ins = insert('retailer_inventory_master', $inv_master_data);
            }


            // FOR HISTORY========================================================
            $hiss_arr = array();
            $hiss_arr['grn_id'] = $id;
            $hiss_arr['retailer_id'] = $grn_data->retailer_id;
            $hiss_arr['po_no'] = $grn_data->po_no;
            $hiss_arr['po_id'] = $grn_data->po_id;
            $hiss_arr['item_desc'] = $grn_data->item_desc;
            $hiss_arr['qty'] = $grn_data->billed_qty;
            $hiss_arr['inwd_datetime'] = date('Y-m-d H:i:s');
            $ins = insert('retailer_inward_history', $hiss_arr);

            echo "0";
        } else {
            echo "1";
        }
    }
}
