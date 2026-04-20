<?php

//echo 'exit laga hai';
//exit;
require_once 'includes/common_function.php';
$success_count = 0;
$failure_count = 0;


$total = 0;
$item_inwarded = getItemInwardBackend();
foreach ($item_inwarded as $item_inwarde) {
    $inventory_data = getInventoryDataByCode($item_inwarde->item_code);
    $id = $item_inwarde->id;
    $retailer_id = $item_inwarde->retailer_id;
    if (isset($inventory_data->item_code)) {
//        echo '<pre/>';
//        print_r($item_inwarde);
//        exit;
        $inward_qty_input = $item_inwarde->current_stock;
        $batch_number = $item_inwarde->batch_no;
        $expire_date = $item_inwarde->expire_date;



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
            $insertSr_no['batch_no'] = $batch_number;
            $insertSr_no['expire_date'] = date("Y-m-d", strtotime($expire_date));
            $insertSr_no['item_desc'] = getItemNameByItemCode($item_inwarde->item_code);
            $insertSr_no['item_id'] = getItemIdByItemCode($item_inwarde->item_code);
            $insertSr_no['item_code'] = $item_inwarde->item_code;
            $insertSr_no['retailer_id'] = $retailer_id;
            $insertSr_no['grn_id'] = $item_inwarde->id;
            $insertSr_no['inc_no'] = $inc_no;
            $insertSr_no['fin_year'] = $fin_year;
            $insertSr_no['added_by'] = "IT";
            $insertSr_no['company_id'] = getRetailerCompanyIdById($retailer_id);
            $insertSr_no['date'] = date("Y-m-d");
            $insertSr_no['datetime'] = date("Y-m-d H:i:s");
            $insert = insert("item_sr_master", $insertSr_no);
            $inc_no++;
        }
        $check_stock_dup = getStockCountByItemCodeAndByRetailerId($retailer_id, $item_inwarde->item_code);
        if (count($check_stock_dup) > 0) {
//            $inv_master_data = array();
//            $inv_master_data['basic_price'] = $item_inwarde->basic_price;
//            $inv_master_data['cgst_rate'] = $item_inwarde->cgst_rate;
//            $inv_master_data['cgst_value'] = $item_inwarde->cgst_value;
//            $inv_master_data['sgst_rate'] = $item_inwarde->sgst_rate;
//            $inv_master_data['sgst_value'] = $item_inwarde->sgst_value;
//            $inv_master_data['igst_rate'] = $item_inwarde->igst_rate;
//            $inv_master_data['igst_value'] = $item_inwarde->igst_value;
//            $inv_master_data['total'] = $item_inwarde->total;
//            $inv_master_data['receive_stock'] = $check_stock_dup->receive_stock + $inward_qty_input;
//            $inv_master_data['current_stock'] = $check_stock_dup->current_stock + $inward_qty_input;
//            $whr = "item_code = '$item_inwarde->item_code' and retailer_id = '$retailer_id'";
//            $update = update('retailer_inventory_master', $inv_master_data, $whr);
        } else {
            $inventory_data = getInventoryDataByCode($item_inwarde->item_code);
//            $inv_master_data = array();
//            $inv_master_data['retailer_id'] = $retailer_id;
//            $inv_master_data['company_id'] = getRetailerCompanyIdById($retailer_id);
//            $inv_master_data['item_id'] = $inventory_data->id;
//            $inv_master_data['item_code'] = $inventory_data->item_code;
//            $inv_master_data['item_desc'] = $inventory_data->item_desc;
//            $inv_master_data['opening_stock'] = '0';
//            $inv_master_data['receive_stock'] = $inward_qty_input;
//            $inv_master_data['issued_stock'] = '0';
//            $inv_master_data['current_stock'] = $inward_qty_input;
//            $inv_master_data['minimum_stock'] = '0';
//            $inv_master_data['main_category_id'] = $inventory_data->main_category_id;
//            $inv_master_data['sub_category_id'] = $inventory_data->sub_category_id;
//            $inv_master_data['hsn_code'] = $inventory_data->hsn_code;
//            $inv_master_data['basic_price'] = $item_inwarde->basic_price;
//            $inv_master_data['cgst_rate'] = $item_inwarde->cgst_rate;
//            $inv_master_data['cgst_value'] = $item_inwarde->cgst_value;
//            $inv_master_data['sgst_rate'] = $item_inwarde->sgst_rate;
//            $inv_master_data['sgst_value'] = $item_inwarde->sgst_value;
//            $inv_master_data['igst_rate'] = $item_inwarde->igst_rate;
//            $inv_master_data['igst_value'] = $item_inwarde->igst_value;
//            $inv_master_data['total'] = $item_inwarde->total;
//            $inv_master_data['status'] = '1';
//            $inv_master_data['uom'] = $inventory_data->uom;
//            $inv_master_data['date'] = date('Y-m-d');
//            $inv_master_data['active'] = '1';
//
//            $update = insert('retailer_inventory_master', $inv_master_data);
        }
        $update = true;
        // FOR HISTORY========================================================
        if ($update) {
            $hiss_arr = array();
            $hiss_arr['grn_id'] = $id;
            $hiss_arr['retailer_id'] = $item_inwarde->retailer_id;
            $hiss_arr['po_no'] = "BACKEND";
            $hiss_arr['item_desc'] = $item_inwarde->item_code;
            $hiss_arr['qty'] = $inward_qty_input;
            $hiss_arr['inwd_datetime'] = date('Y-m-d H:i:s');
            $ins = insert('retailer_inward_history', $hiss_arr);
            if ($ins) {
                $update_data = array();
                $update_data['status'] = 1;
                $update_data['update_datetime'] = date('Y-m-d H:i:s');
                $update_data_whr = "id = '$id' and status = '0' and retailer_id = '$retailer_id'";
                $uupd = update('item_inward_backend', $update_data, $update_data_whr);
                $success_count++;
            } else {
                $failure_count++;
                $update_data = array();
                $update_data['status'] = 2;
                $update_data['update_datetime'] = date('Y-m-d H:i:s');
                $update_data_whr = "id = '$id' and status = '0' and retailer_id = '$retailer_id'";
                $uupd = update('item_inward_backend', $update_data, $update_data_whr);
            }
        } else {
            $failure_count++;
            $update_data = array();
            $update_data['status'] = 3;
            $update_data['update_datetime'] = date('Y-m-d H:i:s');
            $update_data_whr = "id = '$id' and status = '0' and retailer_id = '$retailer_id'";
            $uupd = update('item_inward_backend', $update_data, $update_data_whr);
        }
    } else {
        $failure_count++;
        $update_data = array();
        $update_data['status'] = 108;
        $update_data['update_datetime'] = date('Y-m-d H:i:s');
        $update_data_whr = "id = '$id' and status = '0' and retailer_id = '$retailer_id'";
        $uupd = update('item_inward_backend', $update_data, $update_data_whr);
    }
}
echo 'Total : ' . $total;
echo '<br/>';
echo 'Total Success : ' . $success_count;
echo '<br/>';
echo 'Total Failer : ' . $failure_count;
exit;
$total++;
?>