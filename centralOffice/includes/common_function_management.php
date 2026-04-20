<?php

require_once 'config_management.php';
if (isset($_GET['menu'])) {
    $menu = $_GET['menu'];
    $menuURL = "?menu=$menu";
} else {
    $menu = '';
    $menuURL = "?menu=0";
}
if ($menu != 1008) {
//    echo 'Software under maintenance. Please wait some times.';
//    exit;
}
date_default_timezone_set('Asia/Kolkata');
$date = date('Y-m-d');
$datetime = date('Y-m-d h:m:s');
$yearArray = range(1985, date("Y"));
$expensions = array("jpeg", "jpg", "png");
$uploadFileSize = 2097152;
$monthArray = array(
    "1" => "January", "2" => "February", "3" => "March", "4" => "April",
    "5" => "May", "6" => "June", "7" => "July", "8" => "August",
    "9" => "September", "10" => "October", "11" => "November", "12" => "December",
);

function numberDecimal($number) {
    if ($number == 0) {
        return '0.00';
    } else {
        return number_format((float) $number, 2, '.', '');
    }
}

function getproductNameById($product_name) {
    $tbl_fields = "item_desc";
    $table_name = "inventory_master";
    $where = "item_desc='$product_name'";
    return $result = num_rows($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getBalancedQuantityByPoId($purchaseId) {
    $tbl_fields = "id";
    $table_name = "company_inward_po_history";
    $where = "po_id='$purchaseId'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    if (isset($result->id)) {
        return $result->id;
    } else {
        return 0;
    }
}

function getLastpurchaseOrderId() {
    $tbl_fields = "*";
    $table_name = "purchase_order";
    $result = mysql_select($tbl_fields, $table_name, $where = '', $group_by = '', $order_by = 'id', $asc = 0, $desc = 1, $limit = '1');
    if ($result) {
        return $result->id;
    } else {
        return 0;
    }
}

function getStockCountByItemCodeAndRetailerId($retailer_id, $item_code) {
    $tbl_fields = "*";
    $table_name = "retailer_inventory_master";
    $where = "retailer_id='$retailer_id' and item_code = '$item_code'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result;
}

function getDuplicateCouponCount($coupon) {
    $tbl_fields = "discount_code";
    $table_name = "tbl_discount_coupon";
    $where = "discount_code='$coupon'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result->discount_code;
}

function getCouponData() {
    $tbl_fields = "*";
    $table_name = "tbl_discount_coupon";
    $result = mysql_selects($tbl_fields, $table_name, $where = '', $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getItemNameByItemCode($item_code) {
    $tbl_fields = "item_desc";
    $table_name = "inventory_master";
    $where = "item_code='$item_code'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    if (isset($result->item_desc)) {
        return $result->item_desc;
    } else {
        return $item_code;
    }
}

function getItemUOMByItemCode($item_code) {
    $tbl_fields = "uom";
    $table_name = "inventory_master";
    $where = "item_code='$item_code'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result->uom;
}

function getPurchaseOrderDetailsByPurchasId($purchase_id) {

    $tbl_fields = "*";
    $table_name = "purchase_order_detail";
    $where = "id='$purchase_id'";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'id', $asc = 0, $desc = 1, $limit = '');
    return $result;
}

function getItemCount($poId) {

    $tbl_fields = "*";
    $table_name = "purchase_order_detail";
    $where = "id='$poId'";
    $result = num_rows($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getPurchaseOrdergetItemCountById($poId) {

    $tbl_fields = "*";
    $table_name = "purchase_order";
    $where = "id='$poId'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getClosedPurchaseOrderListByStatus($status) {
    $tbl_fields = "*";
    $table_name = "purchase_order";
    $where = "status='$status'";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getProductsList() {
    $tbl_fields = "*";
    $table_name = "inventory_master";
    return $result = mysql_selects($tbl_fields, $table_name, $where = '', $group_by = '', $order_by = 'id', $asc = 0, $desc = 1, $limit = '');
}

function getActiveStates() {
    $tbl_fields = "*";
    $table_name = "state_master";
    $where = "status='1'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'name', $asc = 1, $desc = 0, $limit = '');
}

function generateRandomString($length = 7) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function getActiveItemsList() {
    $tbl_fields = "*";
    $table_name = "inventory_master";
    $where = "status='1'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'id', $asc = 0, $desc = 1, $limit = '');
}

function getActiveItemsListForPriceUpdate() {
    $tbl_fields = "*";
    $table_name = "inventory_master";
    $where = "status='1'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = 'item_code', $order_by = 'id', $asc = 0, $desc = 1, $limit = '');
}

function getMaxItemIncNo() {
    $tbl_fields = "inc_no";
    $table_name = "inventory_master";
    $result = mysql_select($tbl_fields, $table_name, $where = '', $group_by = '', $order_by = 'id', $asc = 0, $desc = 1, $limit = 1);
    if (isset($result->inc_no)) {
        return $result->inc_no;
    } else {
        return 0;
    }
}

function getMaxSellerIncNo() {
    $tbl_fields = "inc_code";
    $table_name = "retailer_master";
    $result = mysql_select($tbl_fields, $table_name, $where = '', $group_by = '', $order_by = 'id', $asc = 0, $desc = 1, $limit = 1);
    if (isset($result->inc_code)) {
        return $result->inc_code;
    } else {
        return 0;
    }
}

function getproductDetailsById($product_id) {
    $tbl_fields = "*";
    $table_name = "inventory_master";
    $where = "id='$product_id'";
    return $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getproductDetailsByCode($product_item_code) {
    $tbl_fields = "*";
    $table_name = "inventory_master";
    $where = "item_code='$product_item_code'";
    return $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getRetailerDetails() {
    $tbl_fields = "*";
    $table_name = "retailer_master";
    $where = "";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getActiveRetailerDetails() {
    $tbl_fields = "*";
    $table_name = "retailer_master";
    $where = "status='1'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getVillageDetails($village_id) {
    $tbl_fields = "*";
    $table_name = "villages";
    $where = "id='$village_id'";
    return $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getRetailerById($retailer_id) {
    $tbl_fields = "*";
    $table_name = "retailer_master";
    $where = "id='$retailer_id'";
    return $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getRetailerNameById($retailer_id) {
    $tbl_fields = "full_name";
    $table_name = "retailer_master";
    $where = "id='$retailer_id'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    if (isset($result->full_name)) {
        return $result->full_name;
    } else {
        return "";
    }
}

function getRetailerItemById($product_id, $Retailer_id) {
    $tbl_fields = "*";
    $table_name = "retailer_inventory_master";
    $where = "item_id='$product_id' and retailer_id='$Retailer_id'";
    return $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getSalesIemQty($item_code, $retailer_id) {
    $tbl_fields = "sum(qty) as qty,item_code,retailer_id";
    $table_name = "retailer_order_temporary";
    $where = "retailer_id='$retailer_id' AND item_code='$item_code' AND status not in('7','8')";
    return $result = mysql_select($tbl_fields, $table_name, $where, $group_by = 'item_code', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getBackendRetailerStockTInward($retailer_id, $item_code) {
    $tbl_fields = "count(id) as count,SUM(current_stock) AS qty,SUM(purchae_basic) AS purchae_basic,SUM(purchase_gst) AS purchase_gst,SUM(purchase_total) AS purchase_total";
    $table_name = "item_inward_backend";
    $where = "retailer_id='$retailer_id' AND item_code='$item_code' and status='1' AND DATE(update_datetime)='2023-07-01'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result;
}

function getRetailerItemOpeningStockById($item_code, $Retailer_id) {
    $tbl_fields = "opening_stock";
    $table_name = "retailer_inventory_master";
    $where = "item_code='$item_code' and retailer_id='$Retailer_id'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    if (isset($result->opening_stock)) {
        return $result->opening_stock;
    } else {
        return 0.00;
    }
}

function getSalesData($limit) {
    $tbl_fields = "id,retailer_id,batch_no,expiry_date,item_code,qty,po_no,order_place_date";
    $table_name = "retailer_order_temporary";
    $where = "STATUS NOT IN ('7','8') AND batch_no!=0 AND batch_no IS NOT NULL AND batch_manage=0";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit);
}

function getProductSalesByRetailer($f_date, $l_date, $Retailer_id) {
    $where_q = "";
    if ($Retailer_id != "All") {
        $where_q = " AND m.`retailer_id`='$Retailer_id'";
    }
    $tbl_fields = "m.`retailer_id` AS retailer_id,m.added_datetime as added_datetime,m.order_no as order_no,t.price as total_price,m.total_count as total_count,m.fin_year AS fin_year,t.item_code as item_code,t.item_name as item_name,t.qty as qty,t.uom as uom ";
    $table_name = "retailer_order_master m, retailer_order_temporary t";
    $where = "DATE(m.added_date) BETWEEN '$f_date' AND '$l_date' $where_q AND m.`order_no`=t.`po_no`";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = 't.`item_code`', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getProductSalesByRetailerTempTableByOrderNo($order_no) {
    $tbl_fields = "m.`retailer_id` AS retailer_id,m.added_datetime as added_datetime,m.order_no as order_no,t.price as total_price,m.total_count as total_count,m.fin_year AS fin_year,t.item_code as item_code,t.item_name as item_name,t.qty as qty,t.uom as uom,m.payment_type,m.transaction_no,t.cgst as cgst,t.sgst as sgst,t.basic as basic ";
    $table_name = "retailer_order_master m, retailer_order_temporary t";
    $where = "m.order_no='$order_no' AND m.status not in ('7','8') AND m.`order_no`=t.`po_no`";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function checkBatchItemUpdate($item_code, $retailer_id, $order_no, $batch_no) {
    $tbl_fields = "id";
    $table_name = "`item_sr_master`";
    $where = "item_code='$item_code' AND status='1' and retailer_id='$retailer_id' and batch_no='$batch_no' and order_no='$order_no'";
    return $result = num_rows($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function checkBatchItemBlocked_7($item_code, $retailer_id, $add_date, $batch_no) {
    $tbl_fields = "id";
    $table_name = "`item_sr_master`";
    $where = "item_code='$item_code' AND status='7' and retailer_id='$retailer_id' and batch_no='$batch_no' AND DATE(block_datetime)='$add_date'";
    return $result = num_rows($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

//function checkAvailableBatchItem($item_code, $retailer_id, $order_no, $batch_no) {
//    $tbl_fields = "id";
//    $table_name = "`item_sr_master`";
//    $where = "item_code='$item_code' AND status='1' and retailer_id='$retailer_id' and batch_no='$batch_no' AND order_no='$order_no'";
//    return $result = num_rows($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
//}

function checkAvailableBatchItem($item_code, $retailer_id, $order_no, $batch_no) {
    $tbl_fields = "id";
    $table_name = "`item_sr_master`";
    $where = "item_code='$item_code' AND status='0' and retailer_id='$retailer_id' and batch_no='$batch_no'";
    return $result = num_rows($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getAvailableBatchNumberItem($item_code, $retailer_id, $order_no, $batch_no) {
    $tbl_fields = "serial_number";
    $table_name = "`item_sr_master`";
    $where = "item_code='$item_code' AND status='0' and retailer_id='$retailer_id' and batch_no='$batch_no'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result->serial_number;
}

function getOrderedBatchDetails($limit) {
    $tbl_fields = "m.id as id,m.`retailer_id` AS retailer_id,t.batch_no,m.added_datetime AS added_datetime,m.order_no AS order_no,t.price AS total_price,m.total_count AS total_count,m.fin_year AS fin_year,t.item_code AS item_code,t.item_name AS item_name,t.qty AS qty,t.uom AS uom,m.payment_type,m.transaction_no,t.cgst AS cgst,t.sgst AS sgst,t.basic AS basic ";
    $table_name = "retailer_order_master m, retailer_order_temporary t";
    $where = "m.status NOT IN ('7','8') AND m.`order_no`=t.`po_no` AND batch_no!='0' and m.batch_manage='0'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit);
}

function getOrderedBatchDetailsTemporary($limit) {
    $tbl_fields = "*";
    $table_name = "retailer_order_temporary";
    $where = "STATUS NOT IN ('7','8') AND batch_no!='0' AND batch_no!='NA' AND batch_manage='0' AND qty>=1";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit);
}

function getOrderedBatchDetailsTransfer($limit) {
    $tbl_fields = "*";
    $table_name = "retailer_stock_transfer";
    $where = "ctrl_off_flag='1' AND batch_no !='' AND batch_no!=0 AND batch_manage='0'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit);
}

function getOrderedBatchDetailsReturnPO($limit) {
    $tbl_fields = "*";
    $table_name = "purchase_order_return_detail";
    $where = "status='1' AND batch_no !='' AND batch_no!=0 AND batch_manage='0'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit);
}

function getRetailerTransferPurchareonDateMailBetween($retail_id, $item_code) {
    $tbl_fields = "sum(total_basic) as total_basic,count(pd.id) as count,SUM(pd.`qty`) AS qty,sum(pd.amount) as amount,sum(pd.rate) as rate,sum(pd.gst_rate) as gst_rate,pd.retailer_id,p.vendor_id as vendor_id,p.po_no";
    $table_name = "`purchase_order_return` p,`purchase_order_return_detail` pd";
    $where = "p.`retailer_id`='$retail_id' AND pd.`item_id`='$item_code' AND p.`id`=pd.`id` and pd.delete='0'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result;
}

function checkOrder($order_no) {
    $tbl_fields = "order_no";
    $table_name = "retailer_order_master";
    $where = "order_no='$order_no' AND status not in ('7','8')";
    return $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getInwardedPo($retailer_id) {
    $tbl_fields = "*";
    $table_name = "inventory_grn";
    $where = "retailer_id='$retailer_id' AND retailer_inwd_flg='1' and status='1'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getInwardedGRN($limit) {
    $tbl_fields = "*";
    $table_name = "inventory_grn";
    $where = "retailer_inwd_flg='1' AND STATUS='1' AND dispatch_retailer_id='0' AND batch_manage=0";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit);
}

function getInwardedTransferGRN($limit) {
    $tbl_fields = "*";
    $table_name = "inventory_grn";
    $where = "retailer_inwd_flg='1' AND STATUS='1' AND dispatch_retailer_id!='0' AND batch_manage=0";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit);
}

function getInwardedTransferGRN_102($limit) {
    $tbl_fields = "*";
    $table_name = "inventory_grn";
    $where = "retailer_inwd_flg='1' AND STATUS='1' AND dispatch_retailer_id!='0' AND batch_manage=102";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit);
}

function getLastSrIncNo($fin_year, $retailer_id) {
    $tbl_fields = "inc_no";
    $table_name = "item_sr_master";
    $where = "fin_year = '$fin_year' and retailer_id = '$retailer_id'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'inc_no', $asc = '', $desc = '1', $limit = 1);
    if (isset($result->inc_no)) {
        return $result->inc_no + 1;
    } else {
        return 1;
    }
}

function getRetailerCompanyIdById($retailer_id) {
    $tbl_fields = "company_id";
    $table_name = "retailer_master";
    $where = "id='$retailer_id'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result->company_id;
}

function getItemIdByItemCode($code) {
    $tbl_fields = "id";
    $table_name = "inventory_master";
    $where = "item_code='$code'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result->id;
}

function getInwardedOrderNoHistory($id) {
    $tbl_fields = "*";
    $table_name = "inventory_grn";
    $where = "id='$id' AND retailer_inwd_flg='1'";
    return $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function checkOrderTemporary($order_no) {
    $tbl_fields = "item_code,qty,retailer_id";
    $table_name = "retailer_order_temporary";
    $where = "po_no='$order_no' AND status not in ('7','8')";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getProductSalesByRetailerTempTable($f_date, $l_date, $Retailer_id) {
    $where_q = "";
    if ($Retailer_id != "All") {
        $where_q = " AND m.`retailer_id`='$Retailer_id'";
    }
    $tbl_fields = "m.`retailer_id` AS retailer_id,m.added_datetime as added_datetime,m.order_no as order_no,t.price as total_price,m.total_count as total_count,m.fin_year AS fin_year,t.item_code as item_code,t.item_name as item_name,t.qty as qty,t.uom as uom,m.payment_type,m.transaction_no,t.cgst as cgst,t.sgst as sgst,t.basic as basic ";
    $table_name = "retailer_order_master m, retailer_order_temporary t";
    $where = "DATE(m.added_date) BETWEEN '$f_date' AND '$l_date' $where_q AND m.`order_no`=t.`po_no` AND m.status not in ('7','8')";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getRetailerItemBasicPriceById($product_id, $Retailer_id) {
    $tbl_fields = "basic_price";
    $table_name = "retailer_inventory_master";
    $where = "item_id='$product_id' and retailer_id='$Retailer_id'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    if (isset($result->basic_price)) {
        return $result->basic_price;
    } else {
        return 0.00;
    }
}

function getRetailerItemByRetailerId($retailer_id) {
    $query = "";
    if ($retailer_id !== "ALL") {
        $query = " and retailer_id='$retailer_id'";
    }
    $tbl_fields = "*";
    $table_name = "retailer_inventory_master";
    $where = "status='1'" . $query;
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
}

function getRetailerItemByItemCodeRetailerId($item_code, $retailer_id) {
    $tbl_fields = "issued_stock,current_stock";
    $table_name = "retailer_inventory_master";
    $where = "status='1' and retailer_id='$retailer_id' and item_code='$item_code'";
    return $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
}

function getAllRetailerItems($limit) {
    $tbl_fields = "item_code,retailer_id,id,opening_stock,issued_stock,receive_stock,current_stock";
    $table_name = "retailer_inventory_master";
    $where = "stock_update='101'";
//    $where = "item_code='AGRO54641662205526' and retailer_id='5' and stock_update='0'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'id', $asc = 1, $desc = 0, $limit);
}

function getAllRetailerByItemCode($limit, $retailer_id, $item_code) {
    $tbl_fields = "item_code,retailer_id,id,opening_stock,issued_stock,receive_stock,current_stock";
    $table_name = "retailer_inventory_master";
    $where = "item_code='$item_code' and retailer_id='$retailer_id'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'id', $asc = 1, $desc = 0, $limit);
}

function getAllDuplicatesItems($limit) {
    $tbl_fields = "*";
    $table_name = "inventory_master";
    $where = "status='1' and active='1'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = 'item_desc', $order_by = '', $asc = 0, $desc = 0, $limit);
}

function getAllDuplicatesByItems($limit, $item) {
    $tbl_fields = "*";
    $table_name = "inventory_master";
    $where = "status='1' and active='1'";
    if ($item != '') {
        $where .= " AND item_code='$item'";
    }
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = 'item_desc', $order_by = '', $asc = 0, $desc = 0, $limit);
}

function getDuplicatesItemByItemDesc($item_desc) {
    $tbl_fields = "*";
    $table_name = "inventory_master";
    $where = "status='1' and active='1' and item_desc='$item_desc'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
}

function getDuplicatesRetailerItemByItemCode($item_code) {
    $tbl_fields = "*";
    $table_name = "retailer_inventory_master";
    $where = "status='1' and active='1' and item_code='$item_code'";
    return $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
}

function getInwardedItem($item_code, $retailer_id) {
    $tbl_fields = "sum(inward_qty) as billed_qty,item_desc,retailer_id";
    $table_name = "inventory_grn";
    $where = "item_desc='$item_code' AND retailer_id='$retailer_id' AND retailer_inwd_flg='1' AND deleted='0'";
    return $result = mysql_select($tbl_fields, $table_name, $where, $group_by = 'item_desc', $order_by = '', $asc = 0, $desc = 0, $limit = '');
}

function getDispatchedItem($item_code, $retailer_id) {
    $tbl_fields = "sum(inward_qty) as billed_qty,item_desc,dispatch_retailer_id";
    $table_name = "inventory_grn";
    $where = "item_desc='$item_code' AND dispatch_retailer_id='$retailer_id' AND retailer_inwd_flg='1' AND deleted='0'";
    return $result = mysql_select($tbl_fields, $table_name, $where, $group_by = 'item_desc', $order_by = '', $asc = 0, $desc = 0, $limit = '');
}

function getActiveRetailer() {
    $tbl_fields = "*";
    $table_name = "retailer_master";
    $where = "status='1'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getproductDetailsByIdData($product_id) {
    $tbl_fields = "*";
    $table_name = "inventory_master";
    $where = "id='$product_id'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getproductImgNameById($product_id) {
    $tbl_fields = "feature_image";
    $table_name = "inventory_master";
    $where = "id='$product_id'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result->feature_image;
}

function getUploadedImagesById($id) {
    $tbl_fields = "*";
    $table_name = "`images`";
    $where = "id='$id'";
    return $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getAdminproductCategories() {
    $tbl_fields = "*";
    $table_name = "`categories`";
    $where = "";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getVillages() {
    $tbl_fields = "*";
    $table_name = "`villages`";
    $where = "";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getActiveproductCategories() {
    $tbl_fields = "*";
    $table_name = "`categories`";
    $where = "status='1'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getParentCategories() {
    $tbl_fields = "*";
    $table_name = "`categories`";
    $where = "parent_category='0'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getParentActiveCategories() {
    $tbl_fields = "*";
    $table_name = "`categories`";
    $where = "parent_category='0' and status='1'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getSubActiveCategories() {
    $tbl_fields = "*";
    $table_name = "`categories`";
    $where = "parent_category!='0' and status='1'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getActivepUoms() {
    $tbl_fields = "*";
    $table_name = "`uom_master`";
    $where = "is_show='1'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getAdminCategoriesById($category_id) {
    $tbl_fields = "*";
    $table_name = "`categories`";
    $where = "id='$category_id'";
    return $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getCategoryNameById($category_id) {
    if ($category_id == 0) {
        return "NA";
    } else {
        $tbl_fields = "name";
        $table_name = "`categories`";
        $where = "id='$category_id'";
        $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
        return $result->name;
    }
}

function getUploadedImages() {
    $tbl_fields = "*";
    $table_name = "`images`";
    return $result = mysql_selects($tbl_fields, $table_name, $where = '', $group_by = '', $order_by = 'added_date', $asc = 0, $desc = 1, $limit = '');
}

function checkMyLogin($username, $password) {
    $tbl_fields = "*";
    $table_name = "user_master";
    $where = "`username`='$username' AND PASSWORD='$password' AND status='1'";
    $count = num_rows($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    if ($count > 0) {
        $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
        if (isset($result->username)) {
            return $result;
        } else {
            return FALSE;
        }
    } else {
        return FALSE;
    }
}

function update_logout($username) {
    $table_name = "user_master";
    $data['login_status'] = 0;
    $data['login_time'] = null;
    $where = "`username`='$username' AND status='1'";
    return $result = update($table_name, $data, $where);
}

function update_sesstion($time, $username, $password) {
    $table_name = "user_master";
    $data['login_status'] = 1;
    $data['login_time'] = $time;
    $where = "`username`='$username' AND PASSWORD='$password' AND status='1'";
    return $result = update($table_name, $data, $where);
}

function getMasterMenuId($menuId) {
    $tbl_fields = "*";
    $table_name = "master_menu";
    $where = 'id="' . $menuId . '" AND status="1"';
    $result = mysql_select($tbl_fields, $table_name, $where);
    return $result;
}

function getSubMenuList($menuId) {
    $tbl_fields = "*";
    $table_name = "master_menu";
    $where = 'master_id="' . $menuId . '" AND status="1"';
    $result = mysql_selects($tbl_fields, $table_name, $where);
    return $result;
}

function getMenuheader() {
    $tbl_fields = "*";
    $table_name = "master_menu";
    $where = 'master_id="0" AND status="1"';
    $result = mysql_selects($tbl_fields, $table_name, $where);
    return $result;
}

function mysql_selects($tbl_fields = '*', $table_name = '', $where = '', $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '') {
    global $conn;
    $row = array();
    $sql = "SELECT ";
    $sql .= $tbl_fields;
    $sql .= " FROM ";
    $sql .= $table_name;
    if ($where != '') {
        $sql .= " WHERE " . $where;
    }
    if ($order_by != '') {
        $sql .= " ORDER BY " . $order_by;
    }
    if ($group_by != '') {
        $sql .= " GROUP BY " . $group_by;
    }
    if ($asc == 1) {
        $sql .= " ASC";
    }
    if ($desc == 1) {
        $sql .= " DESC";
    }
    if ($limit != '') {
        $sql .= " limit " . $limit;
    }
    if ($table_name == 'company_master_inv') {
//        echo $sql;exit;
    }
    $result = mysqli_query($conn, $sql);
    if ($result) {
        while ($res_row = mysqli_fetch_object($result)) {
            $row[] = $res_row;
        }
    }
    return $row;
}

function mysql_select($tbl_fields = '*', $table_name = '', $where = '', $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '') {
    global $conn;
    $row = array();
    $sql = "SELECT ";
    $sql .= $tbl_fields;
    $sql .= " FROM ";
    $sql .= $table_name;
    if ($where != '') {
        $sql .= " WHERE " . $where;
    }
    if ($order_by != '') {
        $sql .= " ORDER BY " . $order_by;
    }
    if ($group_by != '') {
        $sql .= " GROUP BY " . $group_by;
    }
    if ($asc == 1) {
        $sql .= " ASC";
    }
    if ($desc == 1) {
        $sql .= " DESC";
    }
    if ($limit != '') {
        $sql .= " limit " . $limit;
    }
    //echo $sql; exit;
    $row = array();
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $row = mysqli_fetch_object($result);
    }
    return $row;
}

function num_rows($tbl_fields = '*', $table_name = '', $where = '', $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '') {
    global $conn;
    $sql = "SELECT ";
    $sql .= $tbl_fields;
    $sql .= " FROM ";
    $sql .= $table_name;
    if ($where != '') {
        $sql .= " WHERE " . $where;
    }
    if ($order_by != '') {
        $sql .= " ORDER BY " . $order_by;
    }
    if ($group_by != '') {
        $sql .= " GROUP BY " . $group_by;
    }
    if ($asc == 1) {
        $sql .= " ASC";
    }
    if ($desc == 1) {
        $sql .= " DESC";
    }
    if ($limit != '') {
        $sql .= " limit " . $limit;
    }
//    echo $sql;exit;
    $result = mysqli_query($conn, $sql);
    $row = mysqli_num_rows($result);
    return $row;
}

function get_tbl_column($table_name = '') {
    global $conn;
    $row = array();
    $sql = "SHOW COLUMNS FROM " . $table_name . ";";
    $result = mysqli_query($conn, $sql);
    while ($res_row = mysqli_fetch_object($result)) {
        $row[] = $res_row->Field;
    }
    return $row;
}

function insert($table_name = '', $data = '') {
    global $conn;
    $field_q = "";
    $value_q = "'";
    foreach ($data as $key => $value) {
        $field_q .= $key . ", ";
        $value_q .= $value . "', '";
    }
    $field_q = rtrim($field_q, ', ');
    $value_q = rtrim($value_q, ", '");
    $value_q .= "'";
    $query = "INSERT INTO $table_name (" . $field_q . ") VALUES(" . $value_q . ")";
    if ($table_name == "stock_managment") {
//        echo $query;
//        exit;
    }
    if (mysqli_query($conn, $query)) {
        return true;
    } else {
        return false;
    }
}

function delete($table_name = '', $where = '') {
    global $conn;
    if (isset($where)) {
        $where = " WHERE " . $where;
    }
    $query = "delete from $table_name $where";
    if (mysqli_query($conn, $query)) {
        return true;
    } else {
        return false;
    }
}

function getSupplierList() {
    $tbl_fields = "*";
    $table_name = "supplier_master";
    $where = "status='1'";
    $result = mysql_selects($tbl_fields, $table_name, $where);
    return $result;
}

function getPurchaseOrderListByStatus($status, $company_id) {
    $tbl_fields = "*";
    $table_name = "purchase_order";
    $where = "status='$status' and company_id='$company_id'";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getApproveStockRequest() {
    $tbl_fields = "*";
    $table_name = "retailer_stock_transfer";
    $where = "ctrl_off_flag='0' and deleted='0'";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getAllRetailerData() {
    $tbl_fields = "*";
    $table_name = "retailer_master";
    $where = "status = '1'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
}

function getApproveTransactionRequest() {
    $tbl_fields = "*";
    $table_name = "transaction_details";
    $where = "status='0'";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getApproveTransactionRequestSelection($retailer_id, $date_1, $date_2, $selection) {
    $retailerQuery = "";
    if ($retailer_id != 0) {
        $retailerQuery = " AND retailer_id='$retailer_id'";
    }
    $tbl_fields = "*";
    $table_name = "transaction_details";
    $where = "status='$selection' and date(datetime) between '$date_1' and '$date_2' $retailerQuery";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getApprovedStockRequest() {
    $tbl_fields = "*";
    $table_name = "retailer_stock_transfer";
    // $where = "ctrl_off_flag='1'";
    $result = mysql_selects($tbl_fields, $table_name, $where = '', $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getLastpurchaseOrder($fin_year_latest, $company_id) {
    $tbl_fields = "inc_no";
    $table_name = "purchase_order";
    $where = "financial_yr='$fin_year_latest' and company_id='$company_id'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'inc_no', $asc = 0, $desc = 1, $limit = '1');
    if ($result) {
        return $result->inc_no;
    } else {
        return 0;
    }
}

function update($table_name = '', $data = '', $where = '') {
    global $conn;
    $field_q = "";
    foreach ($data as $key => $value) {
        $field_q .= $key . "='" . $value . "',";
    }
    $field_q = rtrim($field_q, ',');
    if (isset($where)) {
        $where = " WHERE " . $where;
    }
    $query = "UPDATE $table_name SET $field_q $where";
    if ($table_name == 'retailer_order_master') {
//        echo $query;
//        exit;
    }
    if (mysqli_query($conn, $query)) {
        return true;
    } else {
        return false;
    }
}

function updateLimit($table_name = '', $data = '', $where = '', $limit = '') {
    global $conn;
    $field_q = "";
    foreach ($data as $key => $value) {
        $field_q .= $key . "='" . $value . "',";
    }
    $field_q = rtrim($field_q, ',');
    if (isset($where)) {
        $where = " WHERE " . $where;
    }
    $query = "UPDATE $table_name SET $field_q $where";
    if (!empty($limit)) {
        $query .= " limit " . $limit;
    }
    if ($table_name == 'item_sr_master') {
//        echo $query;
//        exit;
    }
    if (mysqli_query($conn, $query)) {
        return true;
    } else {
        return false;
    }
}

function updateIn($table_name = '', $data = '', $where = '', $limit = '') {
    global $conn;
    if ($limit > 1) {
        $limit = round($limit);
    } else {
        $limit = 1;
    }
//    $limit = round($limit);
//    if ($limit == 0) {
//        $limit = 1;
//    }
    $field_q = "";
    foreach ($data as $key => $value) {
        $field_q .= $key . "='" . $value . "',";
    }
    $field_q = rtrim($field_q, ', ');
    if (isset($where)) {
        $where = " WHERE " . $where;
        if (!empty($limit)) {
            $where .= " LIMIT " . $limit;
        }
    }
    $query = "UPDATE $table_name SET $field_q $where";
    //   exit;
    if ($table_name == 'item_sr_master') {
//         echo $query;
//         exit;
    }
    if (mysqli_query($conn, $query)) {
        return true;
    } else {
        return false;
    }
}

?>