<?php

require_once 'includes/common_function_management.php';
$limit = 100;
$all_orders = getOrderedBatchDetailsTransfer($limit);

foreach ($all_orders as $all_orders) {
    $item_code = $all_orders->item_code;
    $retailer_id = $all_orders->frm_retailer_id;
    $order_no = $all_orders->order_no;
    $batch_no = $all_orders->batch_no;
    $qty = $all_orders->req_qty;
    $add_date = $all_orders->add_date;
    $m_id = $all_orders->id;

    $blockedBatch = checkBatchItemBlocked_7($item_code, $retailer_id, $add_date, $batch_no);
    if ($blockedBatch == 0) {
        $availableBatch = checkAvailableBatchItem($item_code, $retailer_id, $order_no, $batch_no);
        if ($availableBatch > 0) {
            if ($qty >= 1) {
                $dataUpdateMaster = array();
                $dataUpdateMaster['batch_manage'] = 1;
                $whereMaster = "id='$m_id' AND batch_manage='0'";
                $updateMaster = update("retailer_stock_transfer", $dataUpdateMaster, $whereMaster);
                if ($updateMaster) {
                    $dataUpdate = array();
                    $dataUpdate['status'] = 7;
                    $dataUpdate['order_no'] = $order_no;
                    $dataUpdate['remarks'] = "BlockbyIT for Order";
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
                $dataUpdateMaster['batch_manage'] = 7;
                $dataUpdate['batch_manage_date'] = date("Y-m-d H:i:s");
                $whereMaster = "id='$m_id' AND batch_manage='0'";
                $updateMaster = update("retailer_stock_transfer", $dataUpdateMaster, $whereMaster);
            }
        } else {
            $dataUpdateMaster = array();
            $dataUpdateMaster['batch_manage'] = 108;
            $dataUpdate['batch_manage_date'] = date("Y-m-d H:i:s");
            $whereMaster = "id='$m_id' AND batch_manage='0'";
            $updateMaster = update("retailer_stock_transfer", $dataUpdateMaster, $whereMaster);
            $msg = 'Batch Not Available for Update - 108';
        }
    } else {
        $dataUpdateMaster = array();
        $dataUpdateMaster['batch_manage'] = 8;
        $dataUpdate['batch_manage_date'] = date("Y-m-d H:i:s");
        $whereMaster = "id='$m_id' AND batch_manage='0'";
        $updateMaster = update("retailer_stock_transfer", $dataUpdateMaster, $whereMaster);
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
