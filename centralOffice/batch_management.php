<?php

require_once 'includes/common_function_management.php';
$limit = 1000;
$all_orders = getOrderedBatchDetailsTemporary($limit);

foreach ($all_orders as $all_orders) {
    $item_code = $all_orders->item_code;
    $retailer_id = $all_orders->retailer_id;
    $order_no = $all_orders->po_no;
    $batch_no = $all_orders->batch_no;
    $qty = $all_orders->qty;
    $m_id = $all_orders->id;

    $blockedBatch = checkBatchItemUpdate($item_code, $retailer_id, $order_no, $batch_no);
    if ($blockedBatch == 0) {
//    if (1 == 1) {
        $availableBatch = checkAvailableBatchItem($item_code, $retailer_id, $order_no, $batch_no);
        if ($availableBatch > 0) {
            if ($qty >= 1) {
                $dataUpdateMaster = array();
                $dataUpdateMaster['batch_manage'] = 1;
                $whereMaster = "id='$m_id' AND batch_manage='0'";
                $updateMaster = update("retailer_order_temporary", $dataUpdateMaster, $whereMaster);
                if ($updateMaster) {
                    $dataUpdate = array();
                    $dataUpdate['status'] = 1;
                    $dataUpdate['order_no'] = $order_no;
                    $dataUpdate['remarks'] = "BlockedbyIT for Order";
                    $dataUpdate['update_datetime'] = date("Y-m-d H:i:s");
                    $where = "item_code='$item_code' AND status='0' and retailer_id='$retailer_id' and batch_no='$batch_no'";
                    $update = updateLimit("item_sr_master", $dataUpdate, $where, round($qty));
                    if ($update) {
                        $msg = 'Success';
                    } else {
                        $msg = 'Error 1';
                    }
                } else {
                    $msg = 'Error 2';
                }
            } else {
                $msg = 'Error 7';
                $dataUpdateMaster = array();
                $dataUpdateMaster['batch_manage'] = 107;
                $whereMaster = "id='$m_id' AND batch_manage='0'";
                $updateMaster = update("retailer_order_temporary", $dataUpdateMaster, $whereMaster);
            }
        } else {
            $dataUpdateMaster = array();
            $dataUpdateMaster['batch_manage'] = 1008;
            $whereMaster = "id='$m_id' AND batch_manage='0'";
            $updateMaster = update("retailer_order_temporary", $dataUpdateMaster, $whereMaster);
            $msg = 'Batch Not Available for Update - 108';
        }
    } else {
        $dataUpdateMaster = array();
        $dataUpdateMaster['batch_manage'] = 1118;
        $whereMaster = "id='$m_id' AND batch_manage='0'";
        $updateMaster = update("retailer_order_temporary", $dataUpdateMaster, $whereMaster);
        $msg = 'Batch : ' . $batch_no . " Already Blocked. Blockedcount :" . $blockedBatch . " Qty : " . $qty;
    }

    echo "retailer Id: " . $retailer_id;
    echo ' | ';
    echo "ItemCode : " . $item_code;
    echo ' | ';
    echo "Item : " . getItemNameByItemCode($item_code);
    echo ' | ';
    echo "OrderNo : " . $order_no;
    echo ' | ';
    echo "Batch : " . $batch_no;
    echo ' | ';
    echo "Qty : " . $qty;
    echo ' | ';
    echo $msg;
    echo '<hr/>';
}
?>
