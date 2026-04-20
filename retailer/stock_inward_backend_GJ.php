<?php

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
//    echo '<pre/>';
//    print_r($item_inwarde);
//    exit;
        $inward_qty_input = $item_inwarde->current_stock;
        $batch_number = $item_inwarde->batch_no;
        $expire_date = $item_inwarde->expire_date;
        $manu_date = $item_inwarde->manu_date;

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
            $insertSr_no['manufacturing_date'] = date("Y-m-d", strtotime($manu_date));
            $insertSr_no['item_desc'] = getItemNameByItemCode($item_inwarde->item_code);
            $insertSr_no['item_id'] = getItemIdByItemCode($item_inwarde->item_code);
            $insertSr_no['item_code'] = $item_inwarde->item_code;
            $insertSr_no['retailer_id'] = $retailer_id;
            $insertSr_no['inc_no'] = $inc_no;
            $insertSr_no['fin_year'] = $fin_year;
            $insertSr_no['added_by'] = "IT";
            $insertSr_no['company_id'] = getRetailerCompanyIdById($retailer_id);
            $insertSr_no['date'] = date("Y-m-d");
            $insertSr_no['datetime'] = date("Y-m-d H:i:s");
            $insert = insert("item_sr_master", $insertSr_no);
            $inc_no++;
        }
        // FOR HISTORY========================================================
        if ($insert) {
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
        $update_data['status'] = 108;
        $update_data['update_datetime'] = date('Y-m-d H:i:s');
        $update_data_whr = "id = '$id' and status = '0' and retailer_id = '$retailer_id'";
        $uupd = update('item_inward_backend', $update_data, $update_data_whr);
    }
    $total++;
}
echo 'Total : ' . $total;
echo '<br/>';
echo 'Total Success : ' . $success_count;
echo '<br/>';
echo 'Total Failer : ' . $failure_count;
exit;
?>