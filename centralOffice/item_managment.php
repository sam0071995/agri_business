<?php

require_once 'includes/common_function_management.php';
$limit = "10";
$item = '';
$all_items = getAllDuplicatesByItems($limit, $item);
//echo '<pre/>';
//print_r($all_items);
//exit;
foreach ($all_items as $all_item) {
    $success = 1;
    $itemDetails = getDuplicatesItemByItemDesc($all_item->item_desc);
//    echo '<pre/>';
//    print_r($itemDetails);
//    exit;
    foreach ($itemDetails as $itemDetail) {
//        echo '<pre/>';
//        print_r($itemDetail);
//        exit;
        $retailerItem = getDuplicatesRetailerItemByItemCode($itemDetail->item_code);
        if (isset($retailerItem->id)) {
            echo $success . " | No Data | " . $itemDetail->item_code . " | " . $itemDetail->item_desc;
        } else {
            $dataUpdate = array();
            $dataUpdate['status'] = 0;
            $dataUpdate['active'] = 0;
            $dataUpdate['updated_date'] = date("Y-m-d H:i:s");
            $where = "item_code='$itemDetail->item_code' and status='1' and active='1'";
            update("inventory_master", $dataUpdate, $where);
            echo $success . " | Success | " . $itemDetail->item_code . " | " . $itemDetail->item_desc;
        }
        echo '<br/>';
        $success++;
    }
    echo '<hr/>';
}
?>
