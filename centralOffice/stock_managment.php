<?php

$limit = 1;
$item_code = '';
$retailer_id = '';

if (isset($_GET['item_code'])) {
    $item_code = $_GET['item_code'];
}
if (isset($_GET['retailer_id'])) {
    $retailer_id = $_GET['retailer_id'];
}

if (empty($retailer_id) && empty($item_code)) {
    echo 'Input is Empty';
    exit;
} else {
    require_once 'includes/common_function_management.php';
    $all_items = getAllRetailerByItemCode($limit, $retailer_id, $item_code);
    if (!empty($all_items)) {
        foreach ($all_items as $all_item) {
            $opening_stock = 0;
            $receipt_stock = 0;
            $issued_stock = 0;
            $current_stock = 0;
            if (isset($all_item->item_code)) {
                $item_code = $all_item->item_code;
                $retailer_id = $all_item->retailer_id;

                $oepning_retailer_agros = getRetailerItemOpeningStockById($item_code, $retailer_id);

                $oepning_retailer = getBackendRetailerStockTInward($retailer_id, $item_code);
                $total_oepning_retailer = 0;
                if (isset($oepning_retailer->qty)) {
                    $total_oepning_retailer = $oepning_retailer->qty;
                } 
                $total_oepning_retailer = $total_oepning_retailer + $oepning_retailer_agros;
                $inward_at_retailer = getInwardedItem($item_code, $retailer_id);
                $billed_qty_inwarded = 0;
                if (isset($inward_at_retailer->billed_qty)) {
                    $billed_qty_inwarded = $inward_at_retailer->billed_qty;
                }

                $dispatched_at_retailer = getDispatchedItem($item_code, $retailer_id);
                $billed_qty_dispatched = 0;
                if (isset($dispatched_at_retailer->billed_qty)) {
                    $billed_qty_dispatched = $dispatched_at_retailer->billed_qty;
                }

                $sales_at_retailer = getSalesIemQty($item_code, $retailer_id);
                $sales_qty = 0;
                if (isset($sales_at_retailer->qty)) {
                    $sales_qty = $sales_at_retailer->qty;
                }

                $return_po_at_retailer = getRetailerTransferPurchareonDateMailBetween($retailer_id, $item_code);
                $return_po_qty = 0;
                if (isset($return_po_at_retailer->qty)) {
                    $return_po_qty = $return_po_at_retailer->qty;
                }

                $opening_stock = 0;
                $receipt_stock = 0;
                $issued_stock = 0;
                $current_stock = 0;
                $receipt_stock = $total_oepning_retailer + $billed_qty_inwarded;
                $issued_stock = $sales_qty + $billed_qty_dispatched + $return_po_qty;
                $current_stock = $receipt_stock - $issued_stock;

                echo "Opening:" . $total_oepning_retailer . ' | TransfertoStoreInward:' . $billed_qty_inwarded;
                echo '<hr/>';
                echo "Sales:" . $sales_qty . ' | TransfertoStore:' . $billed_qty_dispatched . ' ReturnPo: ' . $return_po_qty;
                echo '<hr/>';
                echo "OLD: " . $all_item->opening_stock . ' | ' . $all_item->receive_stock . ' | ' . $all_item->issued_stock . ' | ' . $all_item->current_stock;
                echo '<hr/>';
                echo "NEW: " . numberDecimal($opening_stock) . ' | ' . numberDecimal($receipt_stock) . ' | ' . numberDecimal($issued_stock) . ' | ' . numberDecimal($current_stock);
                echo '<hr/>';
                echo "Retailer: " . getRetailerNameById($retailer_id) . " [" . $retailer_id . "]";
                echo ' | ';
                echo "Item : " . getItemNameByItemCode($item_code);
                echo ' | ';
                echo "code : " . $item_code;
                echo ' | ';
                echo "opening : " . $opening_stock;
                echo ' | ';
                echo "receipt : " . $receipt_stock;
                echo ' | ';
                echo "Issues : " . $issued_stock;
                echo ' | ';
                echo "current : " . $current_stock;

                $stock_managment = array();
                $stock_managment['item_code'] = $item_code;
                $stock_managment['`op`'] = $all_item->opening_stock;
                $stock_managment['`rc`'] = $all_item->receive_stock;
                $stock_managment['`is`'] = $all_item->issued_stock;
                $stock_managment['`cl`'] = $all_item->current_stock;
                $stock_managment['`n_op`'] = $opening_stock;
                $stock_managment['`n_rc`'] = $receipt_stock;
                $stock_managment['`n_is`'] = $issued_stock;
                $stock_managment['`n_cl`'] = $current_stock;
                $stock_managment['`status`'] = 1;
                $stock_managment['`retailer_id`'] = $retailer_id;
                $stock_managment['`datetime`'] = date("Y-m-d H:i:s");
                $insert = insert("stock_managment", $stock_managment);
                if ($insert) {
                    $dataUpdate = array();
                    $dataUpdate['opening_stock'] = $opening_stock;
                    $dataUpdate['receive_stock'] = $receipt_stock;
                    $dataUpdate['issued_stock'] = $issued_stock;
                    $dataUpdate['current_stock'] = $current_stock;
                    $where = "id='$all_item->id' and retailer_id='$retailer_id' and item_code='$item_code'";
                    $update = update("retailer_inventory_master", $dataUpdate, $where);
                    if ($update) {
                        echo ' | Updated';
                    } else {
                        echo ' | Error';
                    }
                } else {
                    echo ' | Insert Error';
                }
                echo '<hr/>';
            } else {
                echo 'Invalid Item Request.';
            }
        }
    } else {
        echo 'Empty OR Invalid Reqquest.';
    }
}
?>
