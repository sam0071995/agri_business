 
<?php

require_once 'config.php';
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
$company_id = "";
$batch_wise_sale = "";
if (isset($_SESSION['id'])) {
    $retailer_detail = getRetailerDataById($_SESSION['id']);
    $company_id = $retailer_detail->company_id;
    $batch_wise_sale = $retailer_detail->batch_wise_sale;
    $company_detail = getCompanyDetailsById($company_id);
}

// $zonalData = getZomDetailsByUsername($_SESSION['email']);
/* ----------------------- PRODUCT CODE ----------------- */

function getCompanyDetailsById($company_id) {
    $tbl_fields = "*";
    $table_name = "`company_master`";
    $where = "id='$company_id'";
    return $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getItemInwardBackend() {
    $tbl_fields = "*";
    $table_name = "`item_inward_backend`";
    $where = "status='0'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '500');
}

function getFreeSerielNoByRetailerItem($item_code, $retailer_id) {
    $tbl_fields = "batch_no,expire_date,COUNT(id) AS cf";
    $table_name = "`item_sr_master`";
    $where = "item_code='$item_code' AND retailer_id='$retailer_id' AND STATUS='0' AND date(expire_date) > '" . date("Y-m-d") . "'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = 'expire_date,batch_no', $order_by = '', $asc = 1, $desc = '', $limit = '');
}

function getBatchNumberFreeItems($retailer_id, $batch_no, $item_code) {
    $tbl_fields = "*";
    $table_name = "`item_sr_master`";
    $where = "item_code='$item_code' AND status='0' and retailer_id='$retailer_id' and batch_no='$batch_no' and expire_date>'" . date("Y-m-d") . "'";
    return $result = num_rows($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getFreeBatchyRetailerId($retailer_id, $item_code) {
    $tbl_fields = "batch_no,count(id) as count";
    $table_name = "`item_sr_master`";
    $where = "item_code='$item_code' AND status='0' and retailer_id='$retailer_id' and expire_date>'" . date("Y-m-d") . "'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = 'batch_no', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getFreeRetailerSrByitem($retailer_id) {
    $tbl_fields = "*";
    $table_name = "`item_sr_master`";
    $where = "status='0' and retailer_id='$retailer_id'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getFreeItemsSrByitem($retailer_id, $item_code) {
    $tbl_fields = "*";
    $table_name = "`item_sr_master`";
    $where = "item_code='$item_code' AND status='0' and retailer_id='$retailer_id'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getExpiredItemsSrByDate($retailer_id, $fromDate, $to_date) {
    $tbl_fields = "*";
    $table_name = "`item_sr_master`";
    $where = "status='0' and retailer_id='$retailer_id' and date(expire_date) between '$fromDate' and '$to_date'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'expire_date', $asc = 1, $desc = '', $limit = '');
}

function getExpiredItems($retailer_id) {
    $tbl_fields = "*";
    $table_name = "`item_sr_master`";
    $where = "status='0' and retailer_id='$retailer_id'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'expire_date', $asc = 1, $desc = '', $limit = '');
}

function getAlredyExpiredItems($retailer_id) {
    $tbl_fields = "*";
    $table_name = "`item_sr_master`";
    $where = "status='0' and retailer_id='$retailer_id' and date(expire_date) < '" . date("Y-m-d") . "'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'expire_date', $asc = 1, $desc = '', $limit = '');
}

function getCompanyNameById($company_id) {
    $tbl_fields = "name";
    $table_name = "`company_master`";
    $where = "id='$company_id'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result->name;
}

function getVillages($retailer_id) {
    $tbl_fields = "*";
    $table_name = "`villages`";
    $where = "retailer_id='$retailer_id'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getVillageNameById($village_id) {
    $tbl_fields = "name";
    $table_name = "`villages`";
    $where = "id='$village_id'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result->name;
}

function getCouponData($retailer_id) {
    $tbl_fields = "*";
    $table_name = "`tbl_discount_coupon`";
    $where = "retailer_id='$retailer_id'";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result;
}

function getCouponDataByCoupon($cupon) {
    $tbl_fields = "*";
    $table_name = "`tbl_discount_coupon`";
    $where = "discount_code='$cupon'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result;
}

function getCuponeCodeStatus($cupon, $retailer_id) {
    $tbl_fields = "*";
    $table_name = "`tbl_discount_coupon`";
    $where = "discount_code='$cupon' and retailer_id = '$retailer_id'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result;
}

function getActivesVillages($retailer_id) {
    $tbl_fields = "*";
    $table_name = "`villages`";
    $where = "status='1' and retailer_id='$retailer_id'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getBlockedRetailerSrByDate($retailer_id, $date_1) {
    $tbl_fields = "*";
    $table_name = "`item_sr_master`";
    $where = "status='1' and retailer_id='$retailer_id' and date(update_datetime) > '$date_1'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getFreeRetailerSrByitemAddedDate($retailer_id, $date_1) {
    $tbl_fields = "*";
    $table_name = "`item_sr_master`";
    $where = "status='0' and retailer_id='$retailer_id' and date <= '$date_1'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getVillageDetails($village_id) {
    $tbl_fields = "*";
    $table_name = "villages";
    $where = "id='$village_id'";
    return $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getActiveRetailer() {
    $tbl_fields = "*";
    $table_name = "retailer_master";
    $where = "status='1'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
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

function getVendorDetailById($vendor_id) {
    $tbl_fields = "*";
    $table_name = "`vendor_master`";
    $where = "vendor_status='1' and vendor_id='$vendor_id'";
    return $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getItemUOMByItemCode($item_code) {
    $tbl_fields = "uom";
    $table_name = "inventory_master";
    $where = "item_code='$item_code'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result->uom;
}

function getItemNameByItemCode($code) {
    $tbl_fields = "item_desc";
    $table_name = "inventory_master";
    $where = "item_code='$code'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result->item_desc;
}

function getItemIdByItemCode($code) {
    $tbl_fields = "id";
    $table_name = "inventory_master";
    $where = "item_code='$code'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result->id;
}

function getRetailerItemNameByItemCode($code) {
    $tbl_fields = "item_desc";
    $table_name = "retailer_inventory_master";
    $where = "item_code='$code'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result->item_desc;
}

function getRetailerNameById($retailer_id) {
    $tbl_fields = "full_name";
    $table_name = "retailer_master";
    $where = "id='$retailer_id'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result->full_name;
}

function getLastpurchaseOrderIncNo($fin_year_latest, $user_id) {
    $tbl_fields = "inc_no";
    $table_name = "purchase_order_return";
    $where = "financial_yr='$fin_year_latest' and user_id='$user_id'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'inc_no', $asc = 0, $desc = 1, $limit = '1');
    if ($result) {
        return $result->inc_no;
    } else {
        return 0;
    }
}

function getCurrentStockByRetailerIdAndItemId($retailerid, $item_id) {
    $tbl_fields = "current_stock";
    $table_name = "`retailer_inventory_master`";
    $where = "retailer_id='$retailerid' and item_id='$item_id'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result->current_stock;
}

function getCurrentStockByRetailerIdAndItemCode($retailerid, $item_code) {
    $tbl_fields = "current_stock";
    $table_name = "`retailer_inventory_master`";
    $where = "retailer_id='$retailerid' and item_code='$item_code'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result->current_stock;
}

function getReceivStockByRetailerIdAndItemCode($retailerid, $item_code) {
    $tbl_fields = "receive_stock";
    $table_name = "`retailer_inventory_master`";
    $where = "retailer_id='$retailerid' and item_code='$item_code'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result->receive_stock;
}

function getIssuedStockByRetailerIdAndItemCode($retailerid, $item_code) {
    $tbl_fields = "issued_stock";
    $table_name = "`retailer_inventory_master`";
    $where = "retailer_id='$retailerid' and item_code='$item_code'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result->issued_stock;
}

function getVendorNameById($vendor_id) {
    $tbl_fields = "vendor_name";
    $table_name = "`vendor_master`";
    $where = "vendor_status='1' and vendor_id='$vendor_id'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result->vendor_name;
}

function getLastpurchaseOrderId() {
    $tbl_fields = "*";
    $table_name = "purchase_order_return";
    $result = mysql_select($tbl_fields, $table_name, $where = '', $group_by = '', $order_by = 'id', $asc = 0, $desc = 1, $limit = '1');
    if ($result) {
        return $result->id;
    } else {
        return 0;
    }
}

function getPurchaseOrderDetailsByPurchasId($purchase_id) {

    $tbl_fields = "*";
    $table_name = "purchase_order_return_detail";
    $where = "id='$purchase_id'";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'id', $asc = 0, $desc = 1, $limit = '');
    return $result;
}

function getVendorActiveDetails() {
    $tbl_fields = "*";
    $table_name = "`vendor_master`";
    $where = "vendor_status='1'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getActiveItemsList() {
    $tbl_fields = "*";
    $table_name = "inventory_master";
    $where = "status='1'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'id', $asc = 0, $desc = 1, $limit = '');
}

function getItemCount($poId) {

    $tbl_fields = "*";
    $table_name = "purchase_order_return_detail";
    $where = "id='$poId'";
    $result = num_rows($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getPurchaseOrdergetItemCountById($poId) {

    $tbl_fields = "*";
    $table_name = "purchase_order_return";
    $where = "id='$poId'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getRetailerCompanyIdById($retailer_id) {
    $tbl_fields = "company_id";
    $table_name = "retailer_master";
    $where = "id='$retailer_id'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result->company_id;
}

function getRetailerItemByRetailerId($retailer_id) {
    $tbl_fields = "*";
    $table_name = "retailer_inventory_master";
    $where = "status='1' and retailer_id='$retailer_id'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
}

function getRetailerStockTInward($retailer_id, $item_code, $date_1) {
    $tbl_fields = "SUM(inward_qty) AS inward_qty";
    $table_name = "inventory_grn";
    $where = "retailer_id='$retailer_id' and retailer_inwd_flg = '1' and item_desc='$item_code'  AND DATE(retailer_inwd_date)<'$date_1'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result->inward_qty;
}

function getRetailerStockTInwardForDate($retailer_id, $item_code, $date_1) {
    $tbl_fields = "SUM(inward_qty) AS inward_qty";
    $table_name = "inventory_grn";
    $where = "retailer_id='$retailer_id' and retailer_inwd_flg = '1' and item_desc='$item_code'  AND DATE(retailer_inwd_date)='$date_1'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result->inward_qty;
}

function getRetailerSalesDetail($retail_id, $item_code, $date_1) {
    $tbl_fields = "SUM(qty) AS qty";
    $table_name = "retailer_order_temporary";
    $where = "retailer_id='$retail_id' AND item_code='$item_code' AND STATUS NOT IN ('7','8')  AND DATE(order_place_date) < '$date_1'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result->qty;
}

function getRetailerSalesDetailonDate($retail_id, $item_code, $date_1) {
    $tbl_fields = "SUM(qty) AS qty";
    $table_name = "retailer_order_temporary";
    $where = "retailer_id='$retail_id' AND item_code='$item_code' AND STATUS NOT IN ('7','8')  AND DATE(order_place_date) = '$date_1'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result->qty;
}

function getRetailerStockTransfer($retailer_id, $item_code, $date_1) {
    $tbl_fields = "SUM(inward_qty) AS inward_qty";
    $table_name = "inventory_grn";
    $where = "dispatch_retailer_id='$retailer_id' and retailer_inwd_flg = '1' and item_desc='$item_code'  AND DATE(retailer_inwd_date)<'$date_1'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result->inward_qty;
}

function getRetailerStockTransferonDate($retailer_id, $item_code, $date_1) {
    $tbl_fields = "SUM(inward_qty) AS inward_qty";
    $table_name = "inventory_grn";
    $where = "dispatch_retailer_id='$retailer_id' and retailer_inwd_flg = '1' and item_desc='$item_code'  AND DATE(retailer_inwd_date)='$date_1'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result->inward_qty;
}

function getRetailerTransferPurchare($retail_id, $item_code, $date_1) {
    $tbl_fields = "SUM(pd.`qty`) AS qty";
    $table_name = "`purchase_order_return` p,`purchase_order_return_detail` pd";
    $where = "p.`retailer_id`='$retail_id' AND pd.`item_id`='$item_code' AND DATE(p.`po_date`) > '$date_1' AND p.`id`=pd.`id`";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result->qty;
}

function getRetailerTransferPurchareonDate($retail_id, $item_code, $date_1) {
    $tbl_fields = "SUM(pd.`qty`) AS qty";
    $table_name = "`purchase_order_return` p,`purchase_order_return_detail` pd";
    $where = "p.`retailer_id`='$retail_id' AND pd.`item_id`='$item_code' AND DATE(p.`po_date`) = '$date_1' AND p.`id`=pd.`id`";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result->qty;
}

function removeSpecialCharacters($string) {
    return preg_replace('/[^A-Za-z0-9\/\\-\(\)]/', ' ', $string);
}

function randomNumber($length) {
    $result = '';
    for ($i = 0; $i < $length; $i++) {
        $result .= mt_rand(0, 9);
    }
    return $result;
}

function numberDecimal($number) {
    return number_format((float) $number, 2, '.', '');
}

function getBDMdataById($bdm_id) {
    $tbl_fields = "*";
    $table_name = "bdm_master";
    $where = " id = '$bdm_id'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result;
}

function getBookSaleByRetailerId($id) {
    $tbl_fields = "*";
    $table_name = "retailer_order_master";
    $where = " retailer_id = '$id'";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result;
}

function getBookSaleByMobileNo($mobile_no) {
    $tbl_fields = "cus_add,cus_name,cus_adhar,cus_village";
    $table_name = "retailer_order_master";
    $where = " cus_ph = '$mobile_no'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'id', $asc = '', $desc = 1, $limit = '');
    return $result;
}

function getBookSaleOrdersByRetailerId($id) {
    $tbl_fields = "*";
    $table_name = "retailer_order_master m,`retailer_order_temporary` t";
    $where = "m.po_no=t.po_no AND m.retailer_id = '$id'";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result;
}

function getBookSaleOrdersByRetailerIdBetweenDates($from_date, $to_date, $status, $id) {
    $query = "";
    if ($status == "1") {
        $query = " and m.status='1'";
    } else if ($status == "2") {
        $query = " and m.status='7'";
    } else {
        $query = "";
    }
    $tbl_fields = "*";
    $table_name = "retailer_order_master m,`retailer_order_temporary` t";
    $where = "m.po_no=t.po_no AND m.retailer_id = '$id' and date(m.added_date) between '$from_date' and '$to_date' $query";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result;
}

function getTempTableDetailsByRetailerIdAndPoNo($retail_id, $po_no) {
    $tbl_fields = "count(*) as count, sum(qty) as qty,inc_no";
    $table_name = "retailer_order_temporary";
    $where = " retailer_id = '$retail_id' and po_no = '$po_no'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result;
}

function getOredrDetailsByRetailerIdAndPoNo($retail_id, $po_no) {
    $tbl_fields = "*";
    $table_name = "retailer_order_temporary";
    $where = " retailer_id = '$retail_id' and po_no = '$po_no' and stock_flg = '0'";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result;
}

function getRetailerItemCurentQty($retail_id, $item_code) {
    $tbl_fields = "*";
    $table_name = "retailer_inventory_master";
    $where = " retailer_id = '$retail_id' and item_code = '$item_code'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result;
}

function getTotalPriceFrmTempTbl($retail_id) {
    $tbl_fields = "sum(price) as total_price";
    $table_name = "retailer_order_temporary";
    $where = " retailer_id = '$retail_id' and order_status = '0'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result->total_price;
}

function getTodayDayBookEntry($retail_id, $date) {
    $tbl_fields = "*";
    $table_name = "day_book_entry";
    $where = " retailer_id = '$retail_id' and status = '1' and date='$date'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result;
}

function getDuplicateOrderCount($fin_year, $retail_id, $item_code) {
    $tbl_fields = "count(retailer_id) as ccount";
    $table_name = "retailer_order_temporary";
    $where = "fin_year = '$fin_year' and retailer_id = '$retail_id' and item_code = '$item_code' and order_status = '0'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result->ccount;
}

function getTransferPendingData($retailer_id) {
    $tbl_fields = "*";
    $table_name = "retailer_stock_transfer";
    $where = "retailer_id = '$retailer_id'  and status = '0' and ctrl_off_flag = '0'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
}

function getTransationSlipDetails($retailer_id, $date_1, $date_2, $selection) {
    $tbl_fields = "*";
    $table_name = "transaction_details";
    $where = "retailer_id = '$retailer_id'  and status = '$selection' and date(datetime) between '$date_1' and '$date_2'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
}

function getCheckDUpDataStockTransfer($retailer_id, $frem_id, $item_id) {
    $tbl_fields = "*";
    $table_name = "retailer_stock_transfer";
    $where = "retailer_id = '$retailer_id' and frm_retailer_id = '$frem_id' and item_id = '$item_id'  and status = '0' and ctrl_off_flag = '0'";
    return $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
}

function getCheckUploadedSlip($retailer_id, $date) {
    $tbl_fields = "*";
    $table_name = "transaction_details";
    $where = "retailer_id = '$retailer_id' and date(transaction_date) = '$date' and status not in ('2')";
    return $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
}

function getRetailerMasterDataById($retailer_id) {
    $tbl_fields = "*";
    $table_name = "retailer_inventory_master";
    $where = "retailer_id = '$retailer_id' and current_stock != '0'  and status = '1'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
}

function getAllRetailerData($retailer_id) {
    global $company_id;
    $tbl_fields = "*";
    $table_name = "retailer_master";
    $where = "status = '1' and id != '$retailer_id' and company_id in ($company_id)";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
}

function getTempItemList($retail_id) {
    $tbl_fields = "*";
    $table_name = "retailer_order_temporary";
    $where = "retailer_id = '$retail_id' and order_status = '0'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
}

function getLastIncNo($fin_year, $id) {
    $tbl_fields = "inc_no";
    $table_name = "retailer_order_master";
    $where = "fin_year = '$fin_year' and retailer_id = '$id'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'id', $asc = '', $desc = '1', $limit = '');
    return $result->inc_no;
}

function getLastSrIncNo($fin_year, $retailer_id) {
    $tbl_fields = "inc_no";
    $table_name = "item_sr_master";
    $where = "fin_year = '$fin_year' and retailer_id = '$retailer_id'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'id', $asc = '', $desc = '1', $limit = 1);
    if (isset($result->inc_no)) {
        return $result->inc_no + 1;
    } else {
        return 1;
    }
}

function getItemDetailByCode($item_code, $retailer_id) {
    $tbl_fields = "total,uom,current_stock";
    $table_name = "retailer_inventory_master";
    $where = "item_code = '$item_code' and retailer_id = '$retailer_id'";
    return $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
}

function getInventoryItem($id) {
    $tbl_fields = "*";
    $table_name = "retailer_inventory_master";
    $where = "retailer_id in ($id) and active = '1'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
}

function getInventoryDataByCode($item_code) {
    $tbl_fields = "*";
    $table_name = "inventory_master";
    $where = "item_code = '$item_code'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    if (isset($result->item_code)) {
        return $result;
    } else {
        return "";
    }
}

function getInventoryMasterDataById($id) {
    $tbl_fields = "*";
    $table_name = "inventory_master";
    $where = "id = '$id'";
    return $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
}

function getInventoryItemNameByCode($item_code) {
    $tbl_fields = "item_desc";
    $table_name = "inventory_master";
    $where = "item_code = '$item_code'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result->item_desc;
}

function getStockCountByItemCodeAndRetailerId($retailer_id, $item_code) {
    $tbl_fields = "*";
    $table_name = "retailer_inventory_master";
    $where = "retailer_id in ($retailer_id) and item_code = '$item_code'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result;
}

function getStockCountByItemCodeAndByRetailerId($retailer_id, $item_code) {
    $tbl_fields = "*";
    $table_name = "retailer_inventory_master";
    $where = "retailer_id ='$retailer_id' and item_code = '$item_code'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result;
}

function getPendingDispacthOrderNo($retailer_id) {
    $tbl_fields = "*";
    $table_name = "retailer_stock_transfer";
    $where = "frm_retailer_id = '$retailer_id' and status = '1' and ctrl_off_flag = '1'";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = 'order_no', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result;
}

function getPendingDispacthOrderNoa($retailer_id) {
    $tbl_fields = "*";
    $table_name = "retailer_stock_transfer";
    $where = "frm_retailer_id='$retailer_id' and status = '1' and ctrl_off_flag = '1'";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = 'order_no', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result;
}

function getApprovedStockRequest($id) {
    $tbl_fields = "*";
    $table_name = "retailer_stock_transfer";
    $where = "retailer_id='$id'";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getDispatchStockReport($retailer_id, $dateone, $datetwo) {
    $tbl_fields = "*";
    $table_name = "retailer_stock_transfer";
    $where = "frm_retailer_id in ($retailer_id) and date(dispatch_date) between '$dateone' and '$datetwo' and status = '2'";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result;
}

function getInvReqByRetailerDetailsByOrderNo($orderno) {
    $tbl_fields = "*";
    $table_name = "retailer_stock_transfer";
    $where = "order_no in ($orderno) and status = '1' and ctrl_off_flag = '1'";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result;
}

function getInvReqByRetailerDetailsByOrderId($orderid) {
    $tbl_fields = "*";
    $table_name = "retailer_stock_transfer";
    $where = "id = '$orderid' and status = '1' and ctrl_off_flag = '1'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result;
}

function getInventoryGrnDetailsById($status, $id) {
    $tbl_fields = "*";
    $table_name = "inventory_grn";
    $where = "retailer_id in ($id) and retailer_inwd_flg = '$status'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
}

function getRetailerStockByItemId($itemid, $compyid) {
    $tbl_fields = "retailer_id";
    $table_name = "retailer_inventory_master";
    $where = "item_id = '$itemid' and current_stock > 0 and company_id = '$compyid'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = 'retailer_id', $order_by = '', $asc = '', $desc = '', $limit = '');
}

function getGrnDataByGrnid($grnid) {
    $tbl_fields = "*";
    $table_name = "inventory_grn";
    $where = "id = '$grnid' and retailer_inwd_flg = '0'";
    return $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
}

function getRetailerDataById($id) {
    $tbl_fields = "*";
    $table_name = "retailer_master";
    $where = "id='$id' and status = '1'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '0', $desc = '0', $limit = '');
    return $result;
}

function getInwardDataByRetailerId($id) {
    $tbl_fields = "*";
    $table_name = "retailer_inward_history";
    $where = "retailer_id='$id' and deleted='0'";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '0', $desc = '0', $limit = '');
    return $result;
}

function getStateDataById($id) {
    $tbl_fields = "*";
    $table_name = "state_master";
    $where = "id='$id'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '0', $desc = '0', $limit = '');
    return $result;
}

function getAllAssignMenuByZomId($zonel_id) {
    $tbl_fields = "menu";
    $table_name = "retailer_master";
    $where = "id='$zonel_id'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '0', $desc = '0', $limit = '');
    return $result->menu;
}

function checkMyLogin($where) {
    $tbl_fields = "*";
    $table_name = "retailer_master";
    // $where = "`email` = '$username' AND password = '$password' and otp_num = '$otp_number' AND status = '1'";
    // $where = "`email` = '$username' AND password = '$password' and otp_num = '$otp_number' AND status = '1'";
    $count = num_rows($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    if ($count > 0) {
        $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
        if (isset($result->email)) {
            return $result;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function update_logout($email) {
    $table_name = "zonal_master";
    $data['otp_num'] = '';
    $data['otp_expire'] = '1';
    $data['otp_time'] = '';
    $where = "email = '$email' AND status = '1'";
    return $result = update($table_name, $data, $where);
}

function update_sesstion($time, $username, $password) {
    $table_name = "user_master";
    $data['login_status'] = 1;
    $data['login_time'] = $time;
    $where = "`user_name` = '$username' AND password = '$password' AND status = '1'";
    return $result = update($table_name, $data, $where);
}

function getMasterMenuId($menuId) {
    $tbl_fields = "retailer_flg";
    $table_name = "master_menu";
    $where = 'id="' . $menuId . '" AND status="1"';
    $result = mysql_select($tbl_fields, $table_name, $where);
    return $result->retailer_flg;
}

function getPurchaseOrderListByStatusReturnPo($company_id) {
    $tbl_fields = "*";
    $table_name = "purchase_order_return";
    $where = " retailer_id = '$company_id'";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getPurchaseOrderListByStatus($retailer_id) {
    $tbl_fields = "*";
    $table_name = "retailer_inventory_master";
    $where = 'master_id="' . $retailer_id . '" AND status="1" and retail_seller="1"';
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getSubMenuList($menuId) {
    $tbl_fields = "*";
    $table_name = "master_menu";
    $where = 'retailer_flg="' . $menuId . '" AND status="1"';
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'id', $asc = 1, $desc = 0, $limit = '');
    return $result;
}

function getMenuheader() {
    $tbl_fields = "*";
    $table_name = "master_menu";
    $where = 'retailer_flg="0" AND status="1"';
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'id', $asc = 1, $desc = 0, $limit = '');
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
    if ($group_by != '') {
        $sql .= " GROUP BY " . $group_by;
    }
    if ($order_by != '') {
        $sql .= " ORDER BY " . $order_by;
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
    //echo $sql;exit;
    if ($table_name == 'retailer_inventory_master') {
//            echo $sql;
//            exit;
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
    if ($table_name == 'btn_details_factory') {
        // echo $sql;
        // exit;
    }
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
    if ($table_name == 'old_requisition') {
        //            echo $sql;
    }
    $result = mysqli_query($conn, $sql);
    $row = mysqli_num_rows($result);
    return $row;
}

function get_tbl_column($table_name = '') {
    global $conn;
    $row = array();
    $sql = "SHOW COLUMNS FROM " . $table_name . ";
    ";
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
    if ($table_name == 'retailer_order_master') {
//         echo $query;
//         exit;
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

    if ($table_name == 'roko_stock_entry') {
        //echo $query;
        // exit;
    }
    if (mysqli_query($conn, $query)) {
        return true;
    } else {
        return false;
    }
}

function getAllApprovedUtilityList($from_date, $to_date, $state_id) {
    $tbl_fields = "*";
    $table_name = "utility_bill";
    $where = " utility_status = '1' and date(ops_datetime) between '$from_date' and '$to_date' and ec_state in ($state_id)";
    $result = mysql_selects($tbl_fields, $table_name, $where);
    return $result;
}

function getAllApprovedUtilityListOps($from_date, $to_date, $state_id, $query_string) {
    $tbl_fields = "*";
    $table_name = "utility_bill";
    $where = " $query_string date(ops_datetime) between '$from_date' and '$to_date' and ec_state in ($state_id)";
    $result = mysql_selects($tbl_fields, $table_name, $where);
    return $result;
}

function update($table_name = '', $data = '', $where = '') {
    global $conn;
    $field_q = "";
    foreach ($data as $key => $value) {
        $field_q .= $key . "='" . $value . "',";
    }
    $field_q = rtrim($field_q, ', ');
    if (isset($where)) {
        $where = " WHERE " . $where;
    }
    $query = "UPDATE $table_name SET $field_q $where";
    //   exit;
    if ($table_name == 'inventory_grn') {
//         echo $query;
//         exit;
    }
    if (mysqli_query($conn, $query)) {
        return true;
    } else {
        return false;
    }
}

function updateIn($table_name = '', $data = '', $where = '', $limit = '') {
    global $conn;
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
    if ($table_name == 'inventory_grn') {
//         echo $query;
//         exit;
    }
    if (mysqli_query($conn, $query)) {
        return true;
    } else {
        return false;
    }
}
