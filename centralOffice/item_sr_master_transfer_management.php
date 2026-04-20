<?php

require_once 'includes/common_function_management.php';
$limit = 500;
$all_orders = getInwardedTransferGRN($limit);
//echo '<pre/>';
//print_r($all_orders);
//exit;
foreach ($all_orders as $all_order) {
    $item_code = $all_order->item_desc;
    $retailer_id = $all_order->retailer_id;
    $dispatch_retailer_id = $all_order->dispatch_retailer_id;
    $order_no = $all_order->po_no;
    $batch_number = $all_order->batch_number;
    $expire_date = $all_order->expire_date;
    $manufacture_date = $all_order->manufacture_date;
    $m_id = $all_order->id;
    $msg = "";
    $msgA = "";
    $inward_qty_input = round($all_order->billed_qty);
    $available_batch = checkAvailableBatchItem($item_code, $dispatch_retailer_id, $order_no, $batch_number);
    if ($available_batch >= $inward_qty_input) {
        $data_item_sr_master = array();
        $data_item_sr_master['update_datetime'] = date("Y-m-d H:i:s");
        $data_item_sr_master['status'] = 7;
        $data_item_sr_master['added_by'] = 108;
        $data_item_sr_master['remarks'] = "blocked for order no : " . $order_no;
        $whereitem_sr_master = "item_code='$item_code' AND retailer_id='$dispatch_retailer_id' and batch_no='$batch_number' and status='0'";
        $limit_item_sr_master = $inward_qty_input;
        $updateIn = updateIn('item_sr_master', $data_item_sr_master, $whereitem_sr_master, $limit_item_sr_master);
        if ($updateIn) {
            if (ltrim(date('m')) > 3) {
                $cd = date('y');
                $dd = $cd + 1;
            } else {
                $dd = date('y');
                $cd = $dd - 1;
            }
            $fin_year = $cd . '' . $dd;
            $inc_no = getLastSrIncNo($fin_year, $retailer_id);
            $inward_qty_input = round($all_order->billed_qty);
            $error = 0;
            $succes = 0;
            for ($i = 0; $i < $inward_qty_input; $i++) {
                $retailer_id_sr = sprintf('%02d', $retailer_id);
                $inc_no_sr = sprintf('%08d', $inc_no);
                $serial_number = $fin_year . $retailer_id_sr . $inc_no_sr;
                $insertSr_no = array();
                $insertSr_no['serial_number'] = $serial_number;
                $insertSr_no['batch_no'] = $batch_number;
                $insertSr_no['manufacturing_date'] = date("Y-m-d", strtotime($manufacture_date));
                $insertSr_no['expire_date'] = date("Y-m-d", strtotime($expire_date));
                $insertSr_no['item_desc'] = getItemNameByItemCode($all_order->item_desc);
                $insertSr_no['item_id'] = getItemIdByItemCode($all_order->item_desc);
                $insertSr_no['item_code'] = $all_order->item_desc;
                $insertSr_no['po_date'] = $all_order->po_date;
                $insertSr_no['vendor_id'] = $all_order->vendor_id;
                $insertSr_no['po_no'] = $all_order->po_no;
                $insertSr_no['retailer_id'] = $retailer_id;
                $insertSr_no['purchase_basic'] = $all_order->po_basic;
                $insertSr_no['gst'] = $all_order->po_gst;
                $insertSr_no['total'] = $all_order->po_total;
                $insertSr_no['grn_id'] = $all_order->id;
                $insertSr_no['inc_no'] = $inc_no;
                $insertSr_no['added_by'] = 108;
                $insertSr_no['fin_year'] = $fin_year;
                $insertSr_no['company_id'] = getRetailerCompanyIdById($retailer_id);
                $insertSr_no['date'] = date("Y-m-d", strtotime($all_order->retailer_inwd_date));
                $insertSr_no['datetime'] = date("Y-m-d", strtotime($all_order->retailer_inwd_date));
                $insert = insert("item_sr_master", $insertSr_no);
                if (!$insert && $error == 0) {
                    $error = 1;
                    $msg = "Problem in insert. GRN Id:" . $all_order->id;
                    exit;
                } else {
                    $succes = 1;
                    $msg = "Success. GRN Id:" . $all_order->id;
                }
                $inc_no++;
            }

            $update_data = array();
            $update_data['batch_manage'] = 1;
            $update_data_whr = "id = '$m_id' and batch_manage = '0'";
            $uupd = update('inventory_grn', $update_data, $update_data_whr);
            if ($uupd) {
                $msgA = " Updated";
            } else {
                $msgA = " Update Error";
            }
        } else {
            $update_data = array();
            $update_data['batch_manage'] = 101;
            $update_data_whr = "id = '$m_id' and batch_manage = '0'";
            $uupd = update('inventory_grn', $update_data, $update_data_whr);
        }
    } else {
        $update_data = array();
        $update_data['batch_manage'] = 102;
        $update_data_whr = "id = '$m_id' and batch_manage = '0'";
        $uupd = update('inventory_grn', $update_data, $update_data_whr);
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
    echo "Qty : " . $inward_qty_input;
    echo ' | ';
    echo "MSG : " . $msg . $msgA;
    echo '<hr/>';
}
?>
