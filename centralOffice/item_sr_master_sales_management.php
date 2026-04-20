<?php

require_once 'includes/common_function_management.php';
$limit = 1;
$all_orders = getSalesData($limit);

foreach ($all_orders as $all_order) {
    $m_id = $all_order->id;
    $retailer_id = $all_order->retailer_id;
    $item_code = $all_order->item_code;
    $batch_number = $all_order->batch_no;
    $expire_date = $all_order->expiry_date;
    $po_no = $all_order->po_no;
    $order_place_date = $all_order->order_place_date;

    $sale_qty_input = numberDecimal($all_order->qty);
    $sale_qty_input_array = explode(".", $sale_qty_input);

    $available_batch = checkAvailableBatchItem($item_code, $retailer_id, $po_no, $batch_number);
    $msg = "";
    $msgA = "";
    if ($available_batch >= $sale_qty_input) {
        $sale_qty_input_1 = $sale_qty_input_array[0];
        $sale_qty_input_2 = $sale_qty_input_array[1];

        $data_item_sr_master = array();
        $data_item_sr_master['update_datetime'] = date("Y-m-d", strtotime($order_place_date));
        $data_item_sr_master['status'] = 1;
        $data_item_sr_master['sale_qty'] = 1;
        $data_item_sr_master['order_no'] = $po_no;
        $data_item_sr_master['added_by'] = "SALES";
        $data_item_sr_master['block_for'] = "blocked for sales : " . $po_no;
        $data_item_sr_master['remarks'] = "blocked for order no : " . $po_no;
        $whereitem_sr_master = "item_code='$item_code' AND retailer_id='$retailer_id' and batch_no='$batch_number' and status='0'";
        $limit_item_sr_master = $sale_qty_input_1;
        $updateIn = updateIn('item_sr_master', $data_item_sr_master, $whereitem_sr_master, $limit_item_sr_master);
        if ($sale_qty_input_2 > 0) {
            $serial_number = getAvailableBatchNumberItem($item_code, $retailer_id, $po_no, $batch_number);
            $data_item_sr_master = array();
            $data_item_sr_master['update_datetime'] = date("Y-m-d H:i:s", strtotime($order_place_date));
            $data_item_sr_master['order_no'] = $po_no;
            $data_item_sr_master['qty'] = (1 - $sale_qty_input_2);
            $data_item_sr_master['sale_qty'] = $sale_qty_input_2;
            $data_item_sr_master['added_by'] = "SALES";
            $data_item_sr_master['block_for'] = "blocked for sales : " . $po_no;
            $data_item_sr_master['remarks'] = "blocked for order no : " . $po_no;
            $whereitem_sr_master = "item_code='$item_code' AND retailer_id='$retailer_id' and batch_no='$batch_number' and status='0' and serial_number='$serial_number'";
            $updateData = update('item_sr_master', $data_item_sr_master, $whereitem_sr_master);
        }
        $update_data = array();
        if ($updateIn) {
            $update_data['batch_manage'] = 1;
        } else {
            $update_data['batch_manage'] = 109;
        }
        $update_data_whr = "id = '$m_id' and status not in ('7','8') AND batch_manage=0";
        $uupd = update('retailer_order_temporary', $update_data, $update_data_whr);
        if ($uupd) {
            $msgA = " Updated";
        } else {
            $msgA = " Update Error";
        }
    } else {
        $update_data = array();
        $update_data['batch_manage'] = 108;
        $update_data_whr = "id = '$m_id' and status not in ('7','8') AND batch_manage=0";
        $uupd = update('retailer_order_temporary', $update_data, $update_data_whr);
        if ($uupd) {
            $msgA = " Updated 108";
        } else {
            $msgA = " Update Error";
        }
    }

    echo "retailer Id: " . $retailer_id;
    echo ' | ';
    echo "ItemCode : " . $item_code;
    echo ' | ';
    echo "Item : " . getItemNameByItemCode($item_code);
    echo ' | ';
    echo "PONo : " . $all_order->po_no;
    echo ' | ';
    echo "Batch : " . $batch_number;
    echo ' | ';
    echo "Qty : " . $sale_qty_input;
    echo ' | ';
    echo "MSG : " . $msg . $msgA;
    echo '<hr/>';
}
?>
