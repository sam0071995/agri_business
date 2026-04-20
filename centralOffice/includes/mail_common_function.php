<?php

error_reporting(0);
date_default_timezone_set('Asia/Kolkata');
$system_start_date = "2021-04-01";

require_once 'config.php';

$company_id = "";
$company_id_in = "";



$date = date('Y-m-d');
$datetime = date('Y-m-d h:m:s');
$yearArray = range(1985, date("Y"));
$expensions = array("jpeg", "jpg", "png");
$uploadFileSize = 2097152;
$monthArray = array(
    "1" => "January",
    "2" => "February",
    "3" => "March",
    "4" => "April",
    "5" => "May",
    "6" => "June",
    "7" => "July",
    "8" => "August",
    "9" => "September",
    "10" => "October",
    "11" => "November",
    "12" => "December",
);

function clean($string) {
    $string = trim($string); // Replaces all spaces with hyphens.
    return $string = str_replace("\t", "", $string);
}

function amount($number) {
    return number_format((float) $number, 2, '.', '');
}

function decimalToINT($decimal) {
    return $number = rtrim(rtrim(number_format($decimal, 2, '.', ''), '0'), '.'); // outputs "100"
}

function IND_money_format($number) {
    $decimal = (string) ($number - floor($number));
    $money = floor($number);
    $length = strlen($money);
    $delimiter = '';
    $money = strrev($money);

    for ($i = 0; $i < $length; $i++) {
        if (($i == 3 || ($i > 3 && ($i - 1) % 2 == 0)) && $i != $length) {
            $delimiter .= ',';
        }
        $delimiter .= $money[$i];
    }

    $result = strrev($delimiter);
    $decimal = preg_replace("/0\./i", ".", $decimal);
    $decimal = substr($decimal, 0, 3);

    if ($decimal != '0') {
        $result = $result . $decimal;
    }
    $result = str_replace("-,", "-", $result);

    return $result;
}

function dateMinus($date, $days) {
    return date('Y-m-d', strtotime('-' . $days . ' day', strtotime($date)));
}

function numberDecimal($number) {
    return number_format((float) $number, 2, '.', '');  // Outputs -> 105.00
}

function datePlus($date, $days) {
    return date('Y-m-d', strtotime('+' . $days . ' day', strtotime($date)));
}

function getFreeSr_noBybatch($retailer_id, $batch_no, $item_code, $sale_qty_input_2) {
    $tbl_fields = "*";
    $table_name = "`item_sr_master`";
    $where = "item_code='$item_code' AND status='0' and retailer_id='$retailer_id' and batch_no='$batch_no' and sale_qty>='$sale_qty_input_2'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'sale_qty', $asc = 0, $desc = 1, $limit = '');
    return $result;
}

function get_data_of_physical_audit_table($fromdate, $todate, $retailerid) {
    $query = '';
    if ($retailerid != 'all') {
        $query = " and retailer_id='$retailerid'";
    }
    $tbl_fields = "*";
    $table_name = "physical_audit_report_tbl";
    $where = "date(confirm_date) between '$fromdate' and '$todate' AND retailer_id!='0' and status = '1'" . $query;
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getPORequestedRetailerByCompanyID($retailer_id) {
    $tbl_fields = "*";
    $table_name = "`retailer_po_generate_item_tbl`";
    $where = "retailer_id in ($retailer_id)  and status = '1'";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = 'retailer_id', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result;
}

function getFromToRetailerDistance($from_retailer_id, $to_retailer_id) {
    $tbl_fields = "km";
    $table_name = "`retaier_km`";
    $where = "from_retailer_id='$from_retailer_id' AND to_retailer_id='$to_retailer_id' AND STATUS='1'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    if (isset($result->km)) {
        return $result->km;
    } else {
        return 0;
    }
}

function getRetailerDataById($retailer_id) {
    $tbl_fields = "*";
    $table_name = "retailer_master";
    $where = " id = '$retailer_id'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result;
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

function getLastReturnpurchaseOrderIncNo($fin_year_latest, $user_id) {
    $tbl_fields = "inc_no";
    $table_name = "purchase_order_return";
    $where = "financial_yr='$fin_year_latest' and retailer_id='$user_id'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'inc_no', $asc = 0, $desc = 1, $limit = '1');
    if ($result) {
        return $result->inc_no;
    } else {
        return 0;
    }
}

function getCurrentStockByRetailerIdAndItemCode($retailerid, $item_code) {
    if (!empty($retailerid)) {
        $tbl_fields = "current_stock";
        $table_name = "`retailer_inventory_master`";
        $where = "retailer_id='$retailerid' and item_code='$item_code'";
        $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
        if (isset($result->current_stock)) {
            return $result->current_stock;
        } else {
            return 0;
        }
    } else {
        return 0;
    }
}

function getIssuedStockByRetailerIdAndItemCode($retailerid, $item_code) {
    $tbl_fields = "issued_stock";
    $table_name = "`retailer_inventory_master`";
    $where = "retailer_id='$retailerid' and item_code='$item_code'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result->issued_stock;
}

function getReturnPurchaseOrderDetailsByretailerId($retailer_id, $user_id) {
    $tbl_fields = "*";
    $table_name = "purchase_order_return_detail";
    $where = "retailer_id = '$retailer_id' and user_id = '$user_id' and status = '0' AND `delete`='0' and retailer_id != '0'";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result;
}

function getReturnPurchaseOrderDetailsById($id) {
    $tbl_fields = "*";
    $table_name = "purchase_order_return_detail";
    $where = "unique_id='$id'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result;
}

function getReturnPurchaseOrderDetailsByIdActive($id) {
    $tbl_fields = "count(id) as count";
    $table_name = "purchase_order_return_detail";
    $where = "unique_id='$id' and status in ('1','0') AND `delete`='0'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result->count;
}

function getFreeSerielNoByRetailerItemUA($item_code, $retailer_id) {
    $tbl_fields = "batch_no,expire_date,sum(qty) AS cf";
    $table_name = "`item_sr_master`";
    $where = "item_code='$item_code' AND retailer_id='$retailer_id' AND STATUS='0'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = 'expire_date,batch_no', $order_by = '', $asc = 1, $desc = '', $limit = '');
}

function getItemSrNoCountByPONo($item_code, $retailer_id, $order_no) {
    $tbl_fields = "count(id) AS qty";
    $table_name = "`item_sr_master`";
    $where = "item_code='$item_code' AND retailer_id='$retailer_id' and po_no='$order_no'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    if (isset($result->qty)) {
        return $result->qty;
    } else {
        return 0;
    }
}

function getItemSrNoCountDateByPONo($item_code, $retailer_id, $order_no, $date) {
    $tbl_fields = "count(id) AS qty";
    $table_name = "`item_sr_master`";
    $where = "item_code='$item_code' AND retailer_id='$retailer_id' and po_no='$order_no' and DATE(DATETIME)='$date'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    if (isset($result->qty)) {
        return $result->qty;
    } else {
        return 0;
    }
}

function getItemSrNoCountByOrderNosUM($item_code, $retailer_id, $order_no) {
    $tbl_fields = "SUM(sale_qty) AS qty";
    $table_name = "`item_sr_master`";
    $where = "item_code='$item_code' AND retailer_id='$retailer_id' and order_no='$order_no'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    if (isset($result->qty)) {
        return $result->qty;
    } else {
        return 0;
    }
}

function getItemSrNoCountByOrderNo($item_code, $retailer_id, $order_no) {
    $tbl_fields = "count(id) AS qty";
    $table_name = "`item_sr_master`";
    $where = "item_code='$item_code' AND retailer_id='$retailer_id' and order_no='$order_no'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    if (isset($result->qty)) {
        return $result->qty;
    } else {
        return 0;
    }
}

function getItemSrNoSalesCountByOrderNo($item_code, $retailer_id, $order_no) {
    $tbl_fields = "sum(sale_qty) AS qty";
    $table_name = "`item_sr_master`";
    $where = "item_code='$item_code' AND retailer_id='$retailer_id' and order_no='$order_no'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    if (isset($result->qty)) {
        return $result->qty;
    } else {
        return 0;
    }
}

function getFreeSerielNoByRetailerItemVerde($item_code, $retailer_id) {
    $tbl_fields = "batch_no,expire_date,sum(qty) AS cf";
    $table_name = "`item_sr_master`";
    $where = "item_code='$item_code' AND retailer_id='$retailer_id' AND STATUS='0'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = 'expire_date,batch_no', $order_by = '', $asc = 1, $desc = '', $limit = '');
}

function getRetailerActiveItemsList() {
    $tbl_fields = "*";
    $table_name = "inventory_master";
    $where = "status='1'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'item_desc', $asc = 1, $desc = 0, $limit = '');
}

function getRetailerStringPoOrderItemListByRetailerId($where) {
    $tbl_fields = "*";
    $table_name = "`retailer_po_generate_item_tbl`";
    $where = $where;

    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'item_desc', $asc = 1, $desc = '', $limit = '');
    return $result;
}

function getInwardDataByRetailerIdFilter($date_1, $date_2, $retailer_id, $company_id_in, $status_filter) {
    $query = "";
    if ($retailer_id != "All") {
        $query .= " AND retailer_id='$retailer_id'";
    }
    $tbl_fields = "*";
    $table_name = "inventory_grn";
    $where = "retailer_inwd_flg='$status_filter' and company_id in ($company_id_in) and date(po_date) between '$date_1' and '$date_2'" . $query;
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'item_desc,batch_number,expire_date,date(retailer_inwd_date)', $asc = 1, $desc = '', $limit = '');
    return $result;
}

function getInwardDataByRetailerIdFilterJoin($date_1, $date_2, $retailer_id, $company_id_in, $status_filter, $item_code) {
    $query = "";
    if ($retailer_id != "All") {
        $query .= " AND g.retailer_id='$retailer_id'";
    }
    if ($item_code != "00") {
        $query .= " AND g.item_desc='$item_code'";
    }
    $tbl_fields = "g.dispatch_retailer_id,h.qty as billed_qty,g.retailer_inwd_flg,g.retailer_id,g.item_desc,g.batch_number AS batch_number,
        g.po_no,g.po_date,g.date_time,g.supplier_name,g.po_no,g.po_no,g.bill_no,g.item_desc,g.expire_date,
        g.manufacture_date AS manufacture_date,g.retailer_inwd_date,g.po_basic,g.po_gst,g.Vehicle_Number,g.inward_Vehicle_Number,
        h.name_of_person,g.inward_name_of_person";
    $table_name = "inventory_grn g,retailer_inward_history h";
    $where = "g.retailer_inwd_flg='$status_filter' and g.company_id in ($company_id_in) and g.retailer_id=h.retailer_id and g.id=h.grn_id and date(g.po_date) between '$date_1' and '$date_2'" . $query;
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'g.item_desc,g.batch_number,g.expire_date,date(g.retailer_inwd_date)', $asc = 1, $desc = '', $limit = '');
    return $result;
}

function getInwardDataByRetailerIdFilterJoinReport($date_1, $date_2, $retailer_id, $company_id_in, $status_filter, $item_code, $status_report) {
    $query = "";
    if ($retailer_id != "All") {
        $query .= " AND g.retailer_id='$retailer_id'";
    }
    if ($item_code != "00") {
        $query .= " AND g.item_desc='$item_code'";
    }

    if ($status_report == "0") {
        $query .= " AND g.dispatch_retailer_id='0'";
    } else if ($status_report == "1") {
        $query .= " AND g.dispatch_retailer_id not in ('0')";
    } else {
        
    }

    $tbl_fields = "g.dispatch_retailer_id,h.qty as billed_qty,g.retailer_inwd_flg,g.retailer_id,g.item_desc,g.batch_number AS batch_number,
        g.po_no,g.po_date,g.date_time,g.supplier_name,g.po_no,g.po_no,g.bill_no,g.item_desc,g.expire_date,
        g.manufacture_date AS manufacture_date,g.retailer_inwd_date,g.po_basic,g.po_gst,g.Vehicle_Number,g.inward_Vehicle_Number,
        h.name_of_person,g.inward_name_of_person";
    $table_name = "inventory_grn g,retailer_inward_history h";
    $where = "g.retailer_inwd_flg='$status_filter' and g.company_id in ($company_id_in) and g.retailer_id=h.retailer_id and g.id=h.grn_id and date(g.retailer_inwd_date) between '$date_1' and '$date_2'" . $query;
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'g.item_desc,g.batch_number,g.expire_date,date(g.retailer_inwd_date)', $asc = 1, $desc = '', $limit = '');
    return $result;
}

function getInwardDataByRetailerIdFilterJoinReportInventoryGRN($date_1, $date_2, $retailer_id, $company_id_in, $status_filter, $item_code, $status_report) {
    $query = "";
    if ($retailer_id != "All") {
        $query .= " AND retailer_id='$retailer_id'";
    }
    if ($item_code != "00") {
        $query .= " AND item_desc='$item_code'";
    }

    if ($status_report == "0") {
        $query .= " AND dispatch_retailer_id='0' and po_type not in ('2')";
    } else if ($status_report == "2") {
        $query .= " AND dispatch_retailer_id='0' and po_type='2'";
    } else if ($status_report == "1") {
        $query .= " AND dispatch_retailer_id not in ('0')";
    } else {
        
    }

    $tbl_fields = "po_type,dispatch_retailer_id,inward_qty as billed_qty,retailer_inwd_flg,retailer_id,item_desc,batch_number AS batch_number,po_no,po_date,date_time,supplier_name,po_no,po_no,bill_no,item_desc,expire_date,manufacture_date AS manufacture_date,retailer_inwd_date,po_basic,po_gst,Vehicle_Number,inward_Vehicle_Number,inward_name_of_person";
    $table_name = "inventory_grn";
    $where = "retailer_inwd_flg='$status_filter' and company_id in ($company_id_in) and date(retailer_inwd_date) between '$date_1' and '$date_2'" . $query;
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'item_desc,batch_number,expire_date,date(retailer_inwd_date)', $asc = 1, $desc = '', $limit = '');
    return $result;
}

function getInwardDataByRetailerId($date_1, $date_2, $retailer_id, $company_id_in, $status_filter, $item_code) {
    $query = "";
    if ($retailer_id != "All") {
        $query .= " AND retailer_id='$retailer_id'";
    }
    if ($item_code != "00") {
        $query .= " AND item_desc='$item_code'";
    }
    $tbl_fields = "*";
    $table_name = "inventory_grn";
    $where = "retailer_inwd_flg='$status_filter' and company_id in ($company_id_in) and date(po_date) between '$date_1' and '$date_2'" . $query;
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'item_desc,batch_number,expire_date', $asc = '0', $desc = '1', $limit = '');
    return $result;
}

function getInwardDataByDates($date_1, $date_2, $company_id_in, $retailer_id) {
    $query = "";
    if ($retailer_id != "All") {
        $query .= " AND retailer_id='$retailer_id'";
    }
    $tbl_fields = "*";
    $table_name = "inventory_grn";
    $where = "company_id in ($company_id_in) and retailer_inwd_flg='1' and date(retailer_inwd_date) between '$date_1' and '$date_2'" . $query;
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'item_desc,batch_number,expire_date', $asc = '0', $desc = '1', $limit = '');
    return $result;
}

function getBankNameById($id) {
    $tbl_fields = "bank_name";
    $table_name = "bank_master_ddm";
    $where = "archive='0' and id='$id'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'bank_name', $asc = 1, $desc = '', $limit = '');
    if (isset($result->bank_name)) {
        return $result->bank_name;
    } else {
        echo '';
    }
}

function getPurchaseOrderListByStatusReturnPo($retailer_id) {
    $tbl_fields = "a.status as status,a.po_no,a.po_date,a.supplier_id,a.retailer_id,a.grand_total,b.item_id,b.qty,b.rate,b.amount,b.batch_no,a.po_type as po_type";
    $table_name = "purchase_order_return a , purchase_order_return_detail b";
    $where = "a.id=b.id and  a.retailer_id = '$retailer_id' AND b.status NOT IN ('7') AND b.delete='0'";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getPONOPurchaseOrderListByStatusReturnPo($company_id, $po_no) {
    $tbl_fields = "b.unique_id as idss,a.po_no,a.po_date,a.supplier_id,a.retailer_id,a.grand_total,b.item_id,b.qty,b.rate,b.amount,b.batch_no,a.po_type as po_type,b.delete as delet";
    $table_name = "purchase_order_return a , purchase_order_return_detail b";
    $where = "a.id=b.id and a.po_no='$po_no'";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getDaysByDate($your_date) {
    $now = time(); // or your date as well
    $your_date = strtotime($your_date);
    $datediff = $now - $your_date;
    return round($datediff / (60 * 60 * 24));
}

function getRetialerPriceDataByIdAndItem($inventory_item_id, $retailer_id) {
    $tbl_fields = "*";
    $table_name = "retailer_inventory_master";
    $where = " item_id = '$inventory_item_id' and retailer_id = '$retailer_id'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '0', $desc = '0', $limit = '');
    return $result;
}

function getPoDetailsByPoNo($poid) {
    $tbl_fields = "*";
    $table_name = "purchase_order_detail";
    $where = " id = '$poid'";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '0', $desc = '0', $limit = '');
    return $result;
}

function getCustomerDetailsById($cus_id) {
    $tbl_fields = "*";
    $table_name = "customer_details_tbl";
    $where = " id = '" . $cus_id . "'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '0', $desc = '0', $limit = '');
    return $result;
}

function getBookSaleOrderBetweenDateByOrderId($date_1, $date_2, $id) {
    $tbl_fields = "retailer_id,total_price,added_datetime,pending_amount,credit_amount,cus_add,cus_ph,cus_name,po_no";
    $table_name = "retailer_order_master";
    $where = "pending_amount>0 AND date(added_datetime) between '$date_1' and '$date_2' and retailer_id in ($id) and status not in ('7','8')";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result;
}

function getCustomerIncNoById($cus_id) {
    $tbl_fields = "inccode";
    $table_name = "customer_details_tbl";
    $where = "retailer_id = '" . $cus_id . "'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'inccode', $asc = '0', $desc = 1, $limit = 1);
    return $result->inccode;
}

function getFirstRetailerOrderByRetailerId($retailer_id) {
    $tbl_fields = "DATE(added_date) as date";
    $table_name = "retailer_order_master";
    $where = "retailer_id='$retailer_id'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'DATE(added_date)', $asc = 1, $desc = '0', $limit = 1);
    if (isset($result->date)) {
        return $result->date;
    } else {
        return 0;
    }
}

function getCustomerDetailsByCompanyId($comp_id) {
    $tbl_fields = "*";
    $table_name = "customer_details_tbl";
    $where = "company_id in ($comp_id)";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '0', $desc = '0', $limit = '');
    return $result;
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

function getBatchNumberByretailerAndItemId($retailer_id, $inventory_item) {
    $tbl_fields = "batch_no,item_desc,item_code,item_id,retailer_id,count(batch_no) as batchcount";
    $table_name = "item_sr_master";
    $where = "retailer_id='$retailer_id' and item_id = '$inventory_item' and status = '0'";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = 'item_id,batch_no', $order_by = '', $asc = '0', $desc = '0', $limit = '');
    return $result;
}

function getItemSrMasterDataByItemIdAndRetailerIdBatchNo($retailer_id, $inventory_item, $item_batch_no) {
    $tbl_fields = "*";
    $table_name = "item_sr_master";
    $where = "retailer_id='$retailer_id' and item_id = '$inventory_item' and batch_no = '$item_batch_no' and status = '0'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '0', $desc = '0', $limit = '');
    return $result;
}

function getItemSrMasterDataByItemIdAndRetailerIdBatchNoCount($retailer_id, $inventory_item, $item_batch_no) {
    $tbl_fields = "sum(qty) as qty";
    $table_name = "item_sr_master";
    $where = "retailer_id='$retailer_id' and item_code = '$inventory_item' and batch_no = '$item_batch_no' and status = '0'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '0', $desc = '0', $limit = '');
    if (isset($result->qty) && !empty($result->qty)) {
        return $result->qty;
    } else {
        return 0;
    }
}

function getItemSrMasterDataByGrnIDCount($grn_id) {
    $tbl_fields = "count(id) as qty";
    $table_name = "item_sr_master";
    $where = "grn_id='$grn_id'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '0', $desc = '0', $limit = '');
    if (isset($result->qty) && !empty($result->qty)) {
        return $result->qty;
    } else {
        return 0;
    }
}

function getPoDataByCondition($where) {
    $tbl_fields = "a.freight,c.po_type,c.bill_no as invoice_v_no,a.po_no,a.retailer_id,date(a.po_date) as po_date,a.supplier_name,a.vendor_id,a.company_id,b.gst_rate,b.item_id,c.billed_qty as qty,b.amount,b.rate,b.gst_amount,c.batch_number,c.expire_date,b.discount_amt,a.discount,c.invoice_date,c.retailer_inwd_date,a.quotation_no as quotation_no,a.quotation_date as quotation_date,a.company_id,a.grand_total";
    $table_name = "purchase_order a, `purchase_order_detail` b, `inventory_grn` c";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '0', $desc = '0', $limit = '');
    return $result;
}

function getAllPONOListByUserId($userid) {
    $tbl_fields = "*";
    $table_name = "purchase_order";
    $where = "user_id='$userid'";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '0', $desc = '0', $limit = '');
    return $result;
}

function getAllAssignMenuByZomId($zonel_id) {
    $tbl_fields = "menu";
    $table_name = "user_master";
    $where = "id='$zonel_id'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '0', $desc = '0', $limit = '');
    return $result->menu;
}

function getUserNameById($user_id) {
    $tbl_fields = "name";
    $table_name = "user_master";
    $where = "id='$user_id'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '0', $desc = '0', $limit = '');
    if (isset($result->name)) {
        return $result->name;
    } else {
        return '';
    }
}

function getUserDetailById($user_id) {
    $tbl_fields = "*";
    $table_name = "user_master";
    $where = "id='$user_id'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '0', $desc = '0', $limit = '');
    return $result;
}

function getUserListforAssignNenu() {
    $tbl_fields = "*";
    $table_name = "user_master";
    $where = "status='1'";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '0', $desc = '0', $limit = '');
    return $result;
}

function getBatchUpdateHistory($item_code, $date1, $date2) {
    $tbl_fields = "*";
    $table_name = "item_sr_update_history";
    $query = '';
    if (!empty($item_code)) {
        $query = "AND item_code='$item_code'";
    }
    $where = "DATE(DATETIME) BETWEEN '$date1' AND '$date2' and STATUS='1' AND user_id IN (SELECT id FROM user_master WHERE company_id in (" . $_SESSION['company_id'] . ")) " . $query;
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '0', $desc = '0', $limit = '');
    return $result;
}

function getRetailerPoItemListByretailerIdForReport($where) {
    $tbl_fields = "*";
    $table_name = "retailer_po_generate_item_tbl";
    $where = $where;
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '0', $desc = '0', $limit = '');
    return $result;
}

function getCompanyDetailsById($company_id) {
    $tbl_fields = "*";
    $table_name = "`company_master`";
    $where = "id='$company_id'";
    return $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getVendorActiveDetails() {
    $tbl_fields = "*";
    $table_name = "`vendor_master`";
    $where = "vendor_status='1'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'vendor_name', $asc = 1, $desc = '', $limit = '');
}

function getFreeItemsSrByitem($retailer_id, $item_code) {
    $tbl_fields = "*";
    $table_name = "`item_sr_master`";
    $where = "item_code='$item_code' AND status='0' and retailer_id='$retailer_id'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getExpiryDateByItemCode($retailer_id, $item_code, $batch_no) {
    $tbl_fields = "expire_date";
    $table_name = "`item_sr_master`";
    $where = "item_code='$item_code' AND retailer_id='$retailer_id' and batch_no='$batch_no'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    if (isset($result->expire_date)) {
        return $result->expire_date;
    } else {
        return '';
    }
}

function getItemPurchasePriceDetails($retailer_id, $item_code, $batch_no) {
    $tbl_fields = "purchase_basic,gst,total";
    $table_name = "`item_sr_master`";
    $where = "item_code='$item_code' AND retailer_id='$retailer_id' and batch_no='$batch_no'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result;
}

function getItemSalesPurchasePriceDetails($retailer_id, $item_code, $batch_no, $order_no) {
    $tbl_fields = "purchase_basic,gst,total";
    $table_name = "`item_sr_master`";
    $where = "item_code='$item_code' AND retailer_id='$retailer_id' and batch_no='$batch_no' and order_no='$order_no'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result;
}

function getFreeItemsSrByitemBatchGroup($retailer_id, $item_code) {
    $tbl_fields = "sum(qty) as count,retailer_id,item_code,item_desc,serial_number,batch_no,expire_date,manufacturing_date,status,purchase_basic,gst,total,order_no,grn_id";
    $table_name = "`item_sr_master`";
    $where = "item_code='$item_code' AND status='0' and retailer_id='$retailer_id'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = 'batch_no,expire_date,status', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getAllItemsSrByitemBatch($retailer_id, $item_code, $batch_no) {
    $tbl_fields = "tran_ref_no,retailer_id,item_code,item_desc,serial_number,batch_no,expire_date,manufacturing_date,status,purchase_basic,gst,total,order_no,po_no,po_date,status,grn_id,remarks,block_for,block_datetime";
    $table_name = "`item_sr_master`";
    $query_batch = "";
    if (!empty($batch_no)) {
        $query_batch = " and batch_no='$batch_no'";
    }
    $where = "item_code='$item_code' AND retailer_id='$retailer_id'" . $query_batch;
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getFreeItemsSrByitemBatchDetailsBeforeDate($retailer_id, $item_code, $date_2) {
    $tbl_fields = "count(id) as countId,sum(qty) as count,retailer_id,item_desc,serial_number,batch_no,expire_date,manufacturing_date,status,sum(purchase_basic) as purchase_basic,sum(gst) as gst,sum(total) as total";
    $table_name = "`item_sr_master`";
    $where = "item_code='$item_code' AND status='0' and retailer_id='$retailer_id' and date(datetime) <= '$date_2' and (date(update_datetime)>'$date_2' || update_datetime is null)";
    return $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getFreeItemsSrByitemBatchDetailsOpening($retailer_id, $item_code, $previous_date) {
    $tbl_fields = "count(id) as countId,sum(qty) as count,sum(sale_qty) as Salecount,retailer_id,item_desc,serial_number,batch_no,expire_date,manufacturing_date,status,sum(purchase_basic) as purchase_basic,sum(gst) as gst,sum(total) as total";
    $table_name = "`item_sr_master`";
    $where = "item_code='$item_code' AND status in('0','1','7') and retailer_id='$retailer_id' and date(datetime) <= '$previous_date' and (date(update_datetime)>'$previous_date' || update_datetime is null)";
    return $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getFreeItemsSrByitemBatchDetailsBetweenDate($retailer_id, $item_code, $date_1, $date_2) {
    $tbl_fields = "count(id) as countId,sum(sale_qty) as count,retailer_id,item_desc,serial_number,batch_no,expire_date,manufacturing_date,status,sum(purchase_basic) as purchase_basic,sum(gst) as gst,sum(total) as total";
    $table_name = "item_sr_master";
    $where = "item_code='$item_code' AND status='1' and retailer_id='$retailer_id' AND DATE(DATETIME) BETWEEN '2021-04-01' AND '$date_1' and date(update_datetime) between '$date_2' and '" . date("Y-m-d") . "'";
    return $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getFreeItemsSrByitemBatchDetailsBetweenDateRejected($retailer_id, $item_code, $date_1, $date_2) {
    $tbl_fields = "count(id) as countId,sum(qty) as count,retailer_id,item_desc,serial_number,batch_no,expire_date,manufacturing_date,status,sum(purchase_basic) as purchase_basic,sum(gst) as gst,sum(total) as total";
    $table_name = "item_sr_master";
    $where = "item_code='$item_code' AND status='7' and retailer_id='$retailer_id' AND DATE(DATETIME) < '$date_1' and date(block_datetime) > '$date_2'";
    return $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getFreeItemsSrByitemBatchDetails($retailer_id, $item_code) {
    $tbl_fields = "sum(qty) as count,retailer_id,item_desc,serial_number,batch_no,expire_date,manufacturing_date,status,sum(purchase_basic) as purchase_basic,sum(gst) as gst,sum(total) as total";
    $table_name = "`item_sr_master`";
    $where = "item_code='$item_code' AND status='0' and retailer_id='$retailer_id'";
    return $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getFreeItemsSrByitemBatchDetailsOpeningBetweenDate($retailer_id, $item_code, $date_1, $date_2, $previous_date) {
    $tbl_fields = "count(id) as countId,sum(sale_qty) as count,retailer_id,item_desc,serial_number,batch_no,expire_date,manufacturing_date,status,sum(purchase_basic) as purchase_basic,sum(gst) as gst,sum(total) as total";
    $table_name = "`item_sr_master`";
    $where = "item_code='$item_code' AND status in ('1') and retailer_id='$retailer_id' AND DATE(DATETIME) BETWEEN '2021-04-01' AND '$previous_date'  and date(update_datetime) between '$date_1' and '" . date("Y-m-d") . "'";
    return $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getRejectedItemsSrByitemBatchDetailsOpeningBetweenDate($retailer_id, $item_code, $date_1, $date_2, $previous_date) {
    $tbl_fields = "sum(sale_qty) as count,retailer_id,item_desc,serial_number,batch_no,expire_date,manufacturing_date,status,sum(purchase_basic) as purchase_basic,sum(gst) as gst,sum(total) as total";
    $table_name = "`item_sr_master`";
    $where = "item_code='$item_code' AND status='1' and retailer_id='$retailer_id' AND DATE(DATETIME) BETWEEN '2021-04-01' AND '$previous_date' and date(update_datetime) between '$date_1' and '" . date("Y-m-d") . "'";
    return $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getFreeItemsSrByitemBatchDetailsToday($retailer_id, $item_code, $date_1, $date_2) {
    $tbl_fields = "sum(sale_qty) as count,retailer_id,item_desc,serial_number,batch_no,expire_date,manufacturing_date,status,sum(purchase_basic) as purchase_basic,sum(gst) as gst,sum(total) as total";
    $table_name = "`item_sr_master`";
    $where = "item_code='$item_code' AND status='1' and retailer_id='$retailer_id' and date(update_datetime) between '$date_1' and '$date_2'";
    return $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getFreeItemsSrByitemBatchGroupCount($retailer_id, $item_code) {
    $tbl_fields = "id";
    $table_name = "`item_sr_master`";
    $where = "item_code='$item_code' AND status='0' and retailer_id='$retailer_id'";
    return $result = num_rows($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
}

function getFreeItemsSrByitemBatchCount($retailer_id, $item_code) {
    $tbl_fields = "sum(qty) as id";
    $table_name = "`item_sr_master`";
    $where = "item_code='$item_code' AND status='0' and retailer_id='$retailer_id'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result->id;
}

function getFreeBatchQty($retailer_id, $item_code, $batch_no, $expiry_date) {
    $tbl_fields = "sum(qty) as count";
    $table_name = "`item_sr_master`";
    $where = "status='0' and item_code='$item_code' and retailer_id='$retailer_id' and batch_no='$batch_no' and expire_date='$expiry_date'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result->count;
}

function getFreeBatchQtyAddedDate($retailer_id, $item_code, $batch_no, $expiry_date) {
    $tbl_fields = "date(datetime) as date";
    $table_name = "`item_sr_master`";
    $where = "status='0' and item_code='$item_code' and retailer_id='$retailer_id' and batch_no='$batch_no' and expire_date='$expiry_date'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result->date;
}

function getFreeItemListSrByitemBatchGroup($retailer_id) {
    $tbl_fields = "sum(qty) as count,item_code,retailer_id,item_desc,serial_number,batch_no,expire_date,manufacturing_date,status";
    $table_name = "`item_sr_master`";
    $where = "status='0' and retailer_id='$retailer_id'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = 'item_code,batch_no,expire_date,status', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getExpiredItemsSrByDate($retailer_id, $item_code, $fromDate, $to_date) {
    global $company_id_in;
    $query = "";
    if ($retailer_id != "ALL") {
        $query .= " and retailer_id='$retailer_id'";
    }
    if ($item_code != "ALL") {
        $query .= " and item_code='$item_code'";
    }
    $tbl_fields = "sum(qty) as count,l.*";
    $table_name = "item_sr_master l";
    $where = "status in ('0','8') and company_id in ($company_id_in) and date(expire_date) between '$fromDate' and '$to_date' $query";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = 'retailer_id,item_code,batch_no,expire_date', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getExpiredItems($retailer_id, $item_code) {
    global $company_id_in;
    $query = "";
    if ($retailer_id != "ALL") {
        $query .= " and retailer_id='$retailer_id'";
    }
    if ($item_code != "ALL") {
        $query .= " and item_code='$item_code'";
    }
    $tbl_fields = "sum(qty) as count,l.*,l.date as to_date";
    $table_name = "item_sr_master l";
    $where = "status in ('0','8') and company_id in ($company_id_in) $query";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = 'retailer_id,item_code,batch_no,expire_date', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getPurchaseValueDetails($retailer_id, $item_code) {
    global $company_id_in;
    $query = "";
    if ($retailer_id != "ALL") {
        $query .= " and retailer_id='$retailer_id'";
    }
    if ($item_code != "ALL") {
        $query .= " and item_code='$item_code'";
    }
    $tbl_fields = "sum(qty) as count,l.*";
    $table_name = "item_sr_master l";
    $where = "status='0' and company_id in ($company_id_in) $query";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = 'retailer_id,item_code,batch_no,expire_date', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getAlredyExpiredItems($retailer_id, $item_code) {
    global $company_id_in;
    $query = "";
    if ($retailer_id != "ALL") {
        $query .= " and retailer_id='$retailer_id'";
    }
    if ($item_code != "ALL") {
        $query .= " and item_code='$item_code'";
    }
    $tbl_fields = "sum(qty) as count,l.*";
    $table_name = "item_sr_master l";
    $where = "status in ('0','8') and company_id in ($company_id_in) and date(expire_date) < '" . date("Y-m-d") . "' $query";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = 'retailer_id,item_code,batch_no,expire_date', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getVendorDetailById($vendor_id) {
    $tbl_fields = "*";
    $table_name = "`vendor_master`";
    $where = "vendor_status='1' and vendor_id='$vendor_id'";
    return $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getVendorNameById($vendor_id) {
    $tbl_fields = "vendor_name";
    $table_name = "`vendor_master`";
    $where = "vendor_status='1' and vendor_id='$vendor_id'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result->vendor_name;
}

function getCompanyNameById($company_id) {
    $tbl_fields = "name";
    $table_name = "`company_master`";
    $where = "id='$company_id'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result->name;
}

function getCompanypPrefixById($company_id) {
    $tbl_fields = "prefix";
    $table_name = "`company_master`";
    $where = "id='$company_id'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result->prefix;
}

function getCompanyNameINById($company_id) {
    $tbl_fields = "name";
    $table_name = "`company_master`";
    $where = "id in ($company_id)";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'id', $asc = 0, $desc = 1, $limit = 1);
    return $result->name;
}

function getCompanyGSTINById($company_id) {
    $tbl_fields = "gst_no";
    $table_name = "`company_master`";
    $where = "id='$company_id'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result->gst_no;
}

function getActiveCompanies() {
    $tbl_fields = "*";
    $table_name = "company_master";
    $where = "status='1'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'name', $asc = 1, $desc = 0, $limit = '');
}

function getActiveBDM() {
    $tbl_fields = "*";
    $table_name = "bdm_master";
    $where = "status='1'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'name', $asc = 1, $desc = 0, $limit = '');
}

function getActiveZone() {
    $tbl_fields = "*";
    $table_name = "zonal_master";
    $where = "status='1'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'name', $asc = 1, $desc = 0, $limit = '');
}

function getBatchExpiryDateByBatchNo($retailer_id, $batch_no, $item_code) {
    $tbl_fields = "expire_date";
    $table_name = "`item_sr_master`";
    $where = "item_code='$item_code' AND retailer_id='$retailer_id' and batch_no='$batch_no'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result->expire_date;
}

function getBatchAddedDateByBatchNo($retailer_id, $batch_no, $item_code) {
    $tbl_fields = "date(datetime) as date";
    $table_name = "`item_sr_master`";
    $where = "item_code='$item_code' AND retailer_id='$retailer_id' and batch_no='$batch_no'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result->date;
}

function getToalSalesBatchBlockedQty($retailer_id, $batch_no, $item_code, $order_no) {
    $tbl_fields = "SUM(sale_qty) AS sale_qty";
    $table_name = "`item_sr_master`";
    $where = "item_code='$item_code' AND retailer_id='$retailer_id' and batch_no='$batch_no' and status='1' and order_no='$order_no'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result->sale_qty;
}

function getStoreCurrentFreestockValue($retailer_id) {
    $tbl_fields = "SUM(purchase_basic/qty) AS freeStock";
    $table_name = "`item_sr_master`";
    $where = "retailer_id='$retailer_id' and status='0'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    if (isset($result->freeStock)) {
        return $result->freeStock;
    } else {
        return 0;
    }
}

function getToalReturnPOBatchBlockedQty($retailer_id, $batch_no, $item_code, $order_no) {
    $tbl_fields = "count(qty) AS sale_qty";
    $table_name = "`item_sr_master`";
    $where = "item_code='$item_code' AND retailer_id='$retailer_id' and batch_no='$batch_no' and status='7' and order_no='$order_no'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result->sale_qty;
}

function getToalTransRequestBlockerBatchBlockedQty($retailer_id, $from_retailer_id, $batch_no, $item_code, $order_no, $block_date) {
    $tbl_fields = "id,count(qty) AS sale_qty";
    $table_name = "`item_sr_master`";
    $where = "item_code='$item_code' AND batch_no='$batch_no' AND block_for='$from_retailer_id' AND STATUS='7' AND DATE(block_datetime)='$block_date'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    if (count($result->sale_qty) > 0) {
        return $result->sale_qty;
    } else {
        return 0;
    }
}

function getToalTransRequestRate($retailer_id, $from_retailer_id, $batch_no, $item_code, $order_no, $expire_date) {
    $tbl_fields = "purchase_basic,gst";
    $table_name = "`item_sr_master`";
    $where = "item_code='$item_code' AND batch_no='$batch_no' AND block_for='$from_retailer_id' AND STATUS in ('7','8') AND DATE(expire_date)='$expire_date'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result;
}

function getToalInwardedBatchBlockedQty($retailer_id, $batch_no, $item_code, $order_no) {
    $tbl_fields = "count(qty) AS qty";
    $table_name = "`item_sr_master`";
    $where = "item_code='$item_code' AND retailer_id='$retailer_id' and batch_no='$batch_no' and po_no='$order_no'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result->qty;
}

function getBDMDetailById($bdm_id) {
    $tbl_fields = "*";
    $table_name = "bdm_master";
    $where = "status='1' and id='$bdm_id'";
    return $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
}

function getCompanies() {
    $tbl_fields = "*";
    $table_name = "`company_master`";
    $where = "";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getBDMDetails($company_id_in) {
    $tbl_fields = "*";
    $table_name = "`bdm_master`";
    $where = "company_id in ($company_id_in)";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getproductNameById($product_name) {
    $tbl_fields = "item_desc";
    $table_name = "inventory_master";
    $where = "item_desc='$product_name'";
    return $result = num_rows($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getproductDescriptionById($product_name) {
    $tbl_fields = "description";
    $table_name = "inventory_master";
    $where = "item_code='$product_name'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    if (!empty($result->description)) {
        return $result->description;
    } else {
        return "";
    }
}

function getAdminproductNotifications() {
    global $company_id;
    $tbl_fields = "*";
    $table_name = "notifications";
    $where = "company_id in ($company_id)";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = 'notification_code', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result;
}

function getAdminproductNotificationsByCode($notification_code) {
    global $company_id;
    $tbl_fields = "*";
    $table_name = "notifications";
    $where = "notification_code='$notification_code' and company_id in ($company_id)";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = 'notification_code', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result;
}

function getAdminproductNotificationByCode($notification_code) {
    global $company_id;
    $tbl_fields = "retailer_id,description";
    $table_name = "notifications";
    $where = "status='1' and notification_code='$notification_code' and company_id in ($company_id)";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result;
}

function getStoreListNotification($notification_code) {
    global $company_id;
    $tbl_fields = "retailer_id";
    $table_name = "notifications";
    $where = "notification_code='$notification_code' and company_id in ($company_id)";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result;
}

function getproductBrandNameById($product_name) {
    $tbl_fields = "brand_name";
    $table_name = "inventory_master";
    $where = "item_code='$product_name'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    if (!empty($result->brand_name)) {
        return $result->brand_name;
    } else {
        return "";
    }
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

function getPurchaseOrderDetailsCartData($user_id) {
    $tbl_fields = "*";
    $table_name = "purchase_order_detail";
    $where = "user_id = '$user_id' and status = '0'";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    if ($result) {
        return $result;
    } else {
        return 0;
    }
}

function getPurchaseOrderDetailsCartDataForBasic($user_id) {
    $tbl_fields = "*";
    $table_name = "purchase_order_basic_detail";
    $where = "user_id = '$user_id' and status = '0'";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    if ($result) {
        return $result;
    } else {
        return 0;
    }
}

function getLastReturnpurchaseOrderId() {
    $tbl_fields = "*";
    $table_name = "purchase_order_return";
    $result = mysql_select($tbl_fields, $table_name, $where = '', $group_by = '', $order_by = 'id', $asc = 0, $desc = 1, $limit = '1');
    if ($result) {
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

function getLastpurchaseOrderIdBasic() {
    $tbl_fields = "*";
    $table_name = "purchase_order_basic";
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

function getStockCountByIdCodeAndRetailerId($retailer_id, $item_code) {
    $tbl_fields = "*";
    $table_name = "retailer_inventory_master";
    $where = "retailer_id='$retailer_id' and item_id = '$item_code'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result;
}

function getItemDetailByItemCode($item_code) {
    $tbl_fields = "*";
    $table_name = "retailer_inventory_master";
    $where = "item_code = '$item_code'";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
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

function getItemIdByItemCode($code) {
    $tbl_fields = "id";
    $table_name = "inventory_master";
    $where = "item_code='$code'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result->id;
}

function getItemGSTRate($code) {
    $tbl_fields = "(sgst_rate+cgst_rate) as rate";
    $table_name = "inventory_master";
    $where = "item_code='$code'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result->rate;
}

function getItemNameByItemCode($item_code) {
    $tbl_fields = "item_desc";
    $table_name = "inventory_master";
    $where = "item_code='$item_code'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    if (isset($result->item_desc)) {
        return trim($result->item_desc);
    } else {
        return $item_code;
    }
}

function getItemMainCategoryIdByItemCode($item_code) {
    $tbl_fields = "main_category_id";
    $table_name = "inventory_master";
    $where = "item_code='$item_code'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    if (isset($result->main_category_id)) {
        return $result->main_category_id;
    } else {
        return "0";
    }
}

function getItemNameByItemId($item_id) {
    $tbl_fields = "item_desc";
    $table_name = "inventory_master";
    $where = "id='$item_id'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    if (isset($result->item_desc)) {
        return $result->item_desc;
    } else {
        return $item_id;
    }
}

function getItemUOMByItemCode($item_code) {
    $tbl_fields = "uom";
    $table_name = "inventory_master";
    $where = "item_code='$item_code'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result->uom;
}

function getItemUnitByItemCodee($item_code) {
    $tbl_fields = "unit";
    $table_name = "inventory_master";
    $where = "item_code='$item_code'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result->unit;
}

function getItemUNITByItemCode($item_code) {
    $tbl_fields = "unit";
    $table_name = "inventory_master";
    $where = "item_code='$item_code'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result->unit;
}

function getItemUOMTByItemCode($item_code) {
    $tbl_fields = "uom";
    $table_name = "inventory_master";
    $where = "item_code='$item_code'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result->uom;
}

function getItemHSNCODEByItemCode($item_code) {
    $tbl_fields = "hsn_code";
    $table_name = "inventory_master";
    $where = "item_code='$item_code'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result->hsn_code;
}

function getPurchaseOrderDetailsByPurchasId($userid, $purchase_id) {

    $tbl_fields = "*";
    $table_name = "purchase_order_detail";
//    $where = "id='$purchase_id' and user_id = '$userid'";
    $where = "id='$purchase_id'";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'id', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getPurchaseOrderDetailsByPurchasIdForBasic($userid, $purchase_id) {

    $tbl_fields = "*";
    $table_name = "purchase_order_basic_detail";
    $where = "id='$purchase_id' and user_id = '$userid'";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'id', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getPurchaseOrderDetailsByPurchasIdForBasicCentral($purchase_id) {

    $tbl_fields = "*";
    $table_name = "purchase_order_basic_detail";
    $where = "id='$purchase_id'";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'id', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getItemCount($poId) {

    $tbl_fields = "*";
    $table_name = "purchase_order_detail";
    $where = "id='$poId'";
    $result = num_rows($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getItemCountForBasic($poId) {

    $tbl_fields = "*";
    $table_name = "purchase_order_basic_detail";
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

function getPurchaseOrdergetItemCountByIdForBasic($poId) {

    $tbl_fields = "*";
    $table_name = "purchase_order_basic";
    $where = "id='$poId'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getClosedPurchaseOrderListByStatusDates($status, $company_id_in, $date_1, $date_2) {
    $tbl_fields = "*";
    $table_name = "purchase_order";
    $where = "status='$status' AND DATE(po_date) BETWEEN '$date_1' AND '$date_2' and company_id in ($company_id_in)";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getClosedPurchaseOrderListByStatus($status, $company_id_in) {
    $tbl_fields = "*";
    $table_name = "purchase_order";
    $where = "status='$status' and company_id in ($company_id_in)";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getClosedCompanyPurchaseOrderListByStatus($status, $company_id_in) {
    $tbl_fields = "*";
    $table_name = "purchase_order";
    $where = "status='$status' and company_id in ($company_id_in)";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getProductsList() {
    $tbl_fields = "*";
    $table_name = "inventory_master";
    return $result = mysql_selects($tbl_fields, $table_name, $where = '', $group_by = '', $order_by = 'item_desc,id', $asc = 1, $desc = 0, $limit = '');
}

function getActiveStates() {
    $tbl_fields = "*";
    $table_name = "state_master";
    $where = "status='1'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'name', $asc = 1, $desc = 0, $limit = '');
}

function getStateNameById($id) {
    $tbl_fields = "name";
    $table_name = "state_master";
    $where = "status='1' and id='$id'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'name', $asc = 1, $desc = 0, $limit = '');
    return $result->name;
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

function getItemsListUOMWise() {
    $tbl_fields = "*";
    $table_name = "inventory_master";
    $where = "status='1' and uom in ('LT','KG','KGS','LTR')";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'id', $asc = 0, $desc = 1, $limit = '');
}

function getItemsListUOMWiseTwo() {
    $tbl_fields = "*";
    $table_name = "inventory_master";
    $where = "status='1' and uom in ('ML','GM','GRAM')";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'id', $asc = 0, $desc = 1, $limit = '');
}

function getRetailerRequiredItemPoList($retailer_id) {
    $tbl_fields = "*";
    $table_name = "retailer_po_generate_item_tbl";
    $where = "retailer_id = '$retailer_id' and status='2'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'id', $asc = 0, $desc = 0, $limit = '');
}

function getRequiredItemPoListByCompanyId($company_id_in) {
    $tbl_fields = "*";
    $table_name = "retailer_po_generate_item_tbl";
    $where = "company_id in ($company_id_in) and status='2'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = 'item_code', $order_by = 'id', $asc = 0, $desc = 0, $limit = '');
}

function getPoItemPendingRetailerListByItemCodeAndCompanyId($itemcode, $company_id) {
    $tbl_fields = "*";
    $table_name = "retailer_po_generate_item_tbl";
    $where = "item_code = '$itemcode' and company_id in ($company_id) and status='2'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = 'retailer_id', $order_by = 'id', $asc = 0, $desc = 0, $limit = '');
}

function getRetailerOrderPoItemQtyAndUom($itemcode, $company_id) {
    $tbl_fields = "uom,sum(bdm_qty) as bdmqty";
    $table_name = "retailer_po_generate_item_tbl";
    $where = "item_code = '$itemcode' and company_id in ($company_id) and status='2'";
    return $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
}

function getRetailerRequiredItemPoListByItemCode($retailer_id, $itemcode) {
    $tbl_fields = "*";
    $table_name = "retailer_po_generate_item_tbl";
    $where = "retailer_id = '$retailer_id' and item_code ='$itemcode' and status='2'";
    return $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'id', $asc = 0, $desc = 0, $limit = '');
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

function getMaxVendorIncNo() {
    $tbl_fields = "inc_code";
    $table_name = "vendor_master";
    $result = mysql_select($tbl_fields, $table_name, $where = '', $group_by = '', $order_by = 'id', $asc = 0, $desc = 1, $limit = 1);
    if (isset($result->inc_code)) {
        return $result->inc_code;
    } else {
        return 0;
    }
}

function getVendorGstinNoById($vendor_id) {
    $tbl_fields = "gstin_no";
    $table_name = "vendor_master";
    $where = "vendor_id='$vendor_id'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    if (isset($result->gstin_no)) {
        return $result->gstin_no;
    } else {
        return "0";
    }
}

function getMaxSellerIncNoBDM() {
    $tbl_fields = "inc_code";
    $table_name = "bdm_master";
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

function getLastIdByTablName($table_name) {
    $tbl_fields = "*";
    $where = "";
    return $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 1, $limit = '1');
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
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'name', $asc = 1, $desc = '', $limit = '');
}

function getAllZoneDetails() {
    $tbl_fields = "*";
    $table_name = "zonal_master";
    $where = "";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'name', $asc = 1, $desc = '', $limit = '');
}

function getActiveRetailerDetails($company_id_in) {
    $tbl_fields = "*";
    $table_name = "retailer_master";
    $where = "status='1' and company_id in ($company_id_in)";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'name,company_id,state_id', $asc = 1, $desc = '', $limit = '');
}

function getActiveRetailerDetailsByRequiredItem($company_id_in) {
    $tbl_fields = "a.*";
    $table_name = "retailer_master a, retailer_po_generate_item_tbl b";
    $where = "a.status='1' and a.company_id in ($company_id_in) and a.id = b.retailer_id and b.status = '2'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = 'b.retailer_id', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getAllRetailerDetails($company_id_in) {
    $tbl_fields = "*";
    $table_name = "retailer_master";
    $where = "company_id in ($company_id_in)";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'company_id,state_id', $asc = 0, $desc = '', $limit = '');
}

function getAllActiveRetailerDetails($company_id_in) {
    $tbl_fields = "*";
    $table_name = "retailer_master";
    $where = "company_id in ($company_id_in) and status='1'";
//    $where = "company_id in ($company_id_in) and status='1' and id='100'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'name,company_id,state_id', $asc = 1, $desc = '', $limit = '');
}

function getRetailerActiveRetailerDetails($company_id_in, $retailer_id) {
    $tbl_fields = "*";
    $table_name = "retailer_master";
    $query = "";
    if ($retailer_id != "0") {
        $query = " AND id='$retailer_id'";
    }
    $where = "company_id in ($company_id_in) and status='1'" . $query;
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'name,company_id,state_id', $asc = 1, $desc = '', $limit = '');
}

function getVendorDetails() {
    $tbl_fields = "*";
    $table_name = "vendor_master";
    $where = "";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'vendor_name', $asc = 1, $desc = '', $limit = '');
}

function getActiveRetailerDetailsByStateId($state_id, $company_id_in) {
    $query = "";
    if ($state_id != 0) {
        $query = " and state_id='$state_id'";
    }
    $tbl_fields = "*";
    $table_name = "retailer_master";
    $where = "status='1'  and company_id in ($company_id_in) $query";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'name,company_id,state_id', $asc = 1, $desc = '', $limit = '');
}

function getVillageDetails($village_id) {
    $tbl_fields = "*";
    $table_name = "villages";
    $where = "id='$village_id'";
    return $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getVillageNameById($village_id) {
    $tbl_fields = "name";
    $table_name = "villages";
    $where = "id='$village_id'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    if (isset($result->name)) {
        return $result->name;
    } else {
        return 0;
    }
}

function getRetailerById($retailer_id) {
    $tbl_fields = "*";
    $table_name = "retailer_master";
    $where = "id='$retailer_id'";
    return $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getZoneNameById($retailer_id) {
    $tbl_fields = "name";
    $table_name = "zonal_master";
    $where = "id='$retailer_id'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    if (isset($result->name)) {
        echo $result->name;
    } else {
        echo 0;
    }
}

function getZoneById($retailer_id) {
    $tbl_fields = "*";
    $table_name = "zonal_master";
    $where = "id='$retailer_id'";
    return $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getDaybookCasInhandEntry($retailer_id, $date) {
    $tbl_fields = "*";
    $table_name = "day_book_entry";
    $where = "retailer_id='$retailer_id' AND STATUS=1 AND DATE(DATE)='" . $date . "'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getRetailerStatusById($retailer_id) {
    $tbl_fields = "status";
    $table_name = "retailer_master";
    $where = "id='$retailer_id'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result->status;
}

function getRetailerCompanyIdById($retailer_id) {
    $tbl_fields = "company_id";
    $table_name = "retailer_master";
    $where = "id='$retailer_id'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result->company_id;
}

function getRetailerInvoiceCodeIdById($retailer_id) {
    $tbl_fields = "inv_series";
    $table_name = "retailer_master";
    $where = "id='$retailer_id'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result->inv_series;
}

function getBDMById($bdm_id) {
    $tbl_fields = "*";
    $table_name = "bdm_master";
    $where = "id='$bdm_id'";
    return $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getAssignretailerBDMById($bdm_id) {
    $tbl_fields = "*";
    $table_name = "bdm_master";
    $where = "id='$bdm_id'";
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

function getRetailerOpeningById($retailer_id) {
    $tbl_fields = "opening";
    $table_name = "retailer_master";
    $where = "id='$retailer_id'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    if (isset($result->opening)) {
        return $result->opening;
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

function getPatriallyRejectedOrder($retailer_id, $date1, $date2) {
    $tbl_fields = "*";
    $table_name = "partially_reject_order";
    $where = "retailer_id='$retailer_id' AND DATE(datetime) between '$date1' and '$date2' and rejected_qty > 0";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'id', $asc = 1, $desc = '', $limit = '');
}

function getRetailerItemListbyComoanyId($company_id) {
    $tbl_fields = "*";
    $table_name = "retailer_inventory_master";
    $where = "company_id  in ('$company_id')";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = 'item_code', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getSalesIemQty($item_code, $retailer_id) {
    $tbl_fields = "sum(qty) as qty,item_code,retailer_id";
    $table_name = "retailer_order_temporary";
    $where = "retailer_id='$retailer_id' AND item_code='$item_code' AND stock_flg='1' AND status not in ('7','8')";
    return $result = mysql_select($tbl_fields, $table_name, $where, $group_by = 'item_code', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getSalesIemQtyQty($item_code, $retailer_id) {
    $tbl_fields = "sum(qty) as qty,item_code,retailer_id";
    $table_name = "retailer_order_temporary";
    $where = "retailer_id='$retailer_id' AND item_code='$item_code' AND stock_flg='1' AND status not in ('7','8')";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = 'item_code', $order_by = '', $asc = 0, $desc = '', $limit = '');
    if (isset($result->qty)) {
        return $result->qty;
    } else {
        return 0;
    }
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

function getProductSalesByRetailerTempTableByOrderNo($order_no, $company_id_in) {
    $tbl_fields = "t.id as tempId,m.cus_name,m.`retailer_id` AS retailer_id,m.added_datetime as added_datetime,m.order_no as order_no,t.price as total_price,m.total_count as total_count,m.fin_year AS fin_year,t.item_code as item_code,t.item_name as item_name,t.qty as qty,t.uom as uom,m.payment_type,m.transaction_no,t.cgst as cgst,t.sgst as sgst,t.basic as basic,m.b2b_flg,m.gstin_no";
    $table_name = "retailer_order_master m, retailer_order_temporary t";
    $where = "m.order_no='$order_no' AND m.status not in ('7','8') AND m.`order_no`=t.`po_no` and m.company_id in ($company_id_in)";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function checkOrder($order_no) {
    $tbl_fields = "order_no";
    $table_name = "retailer_order_master";
    $where = "order_no='$order_no' AND status not in ('7','8')";
    return $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function B2BBuyerList($company_id_in) {
    $tbl_fields = "cus_name";
    $table_name = "retailer_order_master";
    $where = "status not in ('7','8') and b2b_flg='1' and company_id in ($company_id_in)";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = 'cus_name', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function checkOrderForPartially($order_no) {
    $tbl_fields = "order_no";
    $table_name = "retailer_order_master";
    $where = "order_no='$order_no' AND status not in ('7','8') and credit_note_no='0'";
    return $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function checkOrderCompany($order_no, $company_id_in) {
    $tbl_fields = "*";
    $table_name = "retailer_order_master";
    $where = "order_no='$order_no' AND status not in ('7','8') and company_id in ($company_id_in)";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getRetailerSalesByDate($retailer_id, $date) {
    $tbl_fields = "order_no,total_price";
    $table_name = "retailer_order_master";
    $where = "STATUS NOT IN ('7','8') AND retailer_id='$retailer_id' AND DATE(added_date)='$date'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'id', $asc = 1, $desc = '', $limit = '');
}

function getRetailerSalesByDateCount($retailer_id) {
    $tbl_fields = "sum(total_price) as total_price";
    $table_name = "retailer_order_master";
    $where = "STATUS NOT IN ('7','8') AND retailer_id='$retailer_id'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result->total_price;
}

function getRetailerSalesByDateCountAsOn($retailer_id, $date_1, $date_2) {
    $tbl_fields = "sum(total_price) as total_price";
    $table_name = "retailer_order_master";
    $where = "STATUS NOT IN ('7','8') AND retailer_id='$retailer_id' and date(added_date) between '$date_1' and '$date_2'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result->total_price;
}

function getRetailerSalesDetailsByDate($retailer_id, $date) {
    $tbl_fields = "po_no as order_no,SUM(price) as total_price";
    $table_name = "retailer_order_temporary";
    $where = "STATUS NOT IN ('7','8') AND retailer_id='$retailer_id' AND DATE(order_place_date)='$date'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = 'po_no', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getRetailerSalesDetailsByDateCountAsOnJoin($retailer_id, $date_1) {
    $tbl_fields = "t.po_no as order_no,SUM(t.price) as total_price";
    $table_name = "retailer_order_master m,`retailer_order_temporary` t";
    $where = "m.po_no=t.po_no AND m.retailer_id = '$retailer_id' AND t.status NOT IN ('7','8') and date(t.order_place_date) between '$date_1' and '$date_1'";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getRetailerSalesByDateCountAsOnJoin($retailer_id, $date_1, $date_2) {
    $tbl_fields = "SUM(t.price) as total_price";
    $table_name = "retailer_order_master m,`retailer_order_temporary` t";
    $where = "m.po_no=t.po_no AND m.retailer_id = '$retailer_id' AND m.status NOT IN ('7','8') and date(added_date) between '$date_1' and '$date_2'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result->total_price;
}

function getRetailerDayWiseSalesByDateCountAsOn($retailer_id, $date_1) {
    $tbl_fields = "sum(total_price) as total_price";
    $table_name = "retailer_order_master";
    $where = "STATUS NOT IN ('7','8') AND retailer_id='$retailer_id' and date(added_date) = '$date_1'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result->total_price;
}

function getRetailerDayWiseSalesByDateCountAsOnJoin($retailer_id, $date_1) {
    $tbl_fields = "SUM(t.price) as total_price";
    $table_name = "retailer_order_master m,`retailer_order_temporary` t";
    $where = "m.po_no=t.po_no AND m.retailer_id = '$retailer_id' AND m.added_date='$date_1' AND m.status NOT IN ('7','8')";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result->total_price;
}

function getInwardedPo($retailer_id) {
    $tbl_fields = "*";
    $table_name = "inventory_grn";
    $where = "retailer_id='$retailer_id' AND retailer_inwd_flg='1' and status='1'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getInwardedOrderSattus($retailer_id, $order_no, $item_code) {
    $tbl_fields = "retailer_inwd_flg,retailer_inwd_date";
    $table_name = "inventory_grn";
    $where = "retailer_id='$retailer_id' AND po_no='$order_no' and item_desc='$item_code'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result;
}

function getInwardedOrderSattusDetail($retailer_id, $order_no, $item_code) {
    $tbl_fields = "retailer_inwd_flg,retailer_inwd_date";
    $table_name = "inventory_grn";
    $where = "id='$order_no'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result;
}

function getInwardedOrderDetailOnDate($retailer_id, $item_code, $date) {
    $tbl_fields = "id,retailer_inwd_flg,retailer_id,retailer_inwd_date,inward_qty,po_no,bill_no,dispatch_retailer_id,supplier_name";
    $table_name = "inventory_grn";
    $where = "retailer_id='$retailer_id' AND date(retailer_inwd_date)='$date' and item_desc='$item_code' and retailer_inwd_flg='1'";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result;
}

function getOutwardedOrderDetailOnDate($retailer_id, $item_code, $date) {
    $tbl_fields = "retailer_inwd_flg,retailer_id,retailer_inwd_date,inward_qty,po_no,bill_no,dispatch_retailer_id,supplier_name";
    $table_name = "inventory_grn";
    $where = "dispatch_retailer_id='$retailer_id' AND date(retailer_inwd_date)='$date' and item_desc='$item_code' and retailer_inwd_flg='1'";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result;
}

function getInwardedInoiceNo($retailer_id, $order_no) {
    $tbl_fields = "*";
    $table_name = "inventory_grn";
    $where = "retailer_id='$retailer_id' AND po_no='$order_no'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result;
}

function getInwardedPoByGrnId($retailer_id, $grn_id) {
    $tbl_fields = "*";
    $table_name = "inventory_grn";
    $where = "retailer_id='$retailer_id' AND id='$grn_id'";
    return $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getInwardedPoHistory($retailer_id) {
    $tbl_fields = "*";
    $table_name = "retailer_inward_history";
    $where = "retailer_id='$retailer_id' AND deleted='0'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getInwardedOrderNoHistory($id) {
    $tbl_fields = "*";
    $table_name = "retailer_inward_history";
    $where = "id='$id' AND deleted='0'";
    return $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getInwardedOrderNoHistory1($id) {
    $tbl_fields = "*";
    $table_name = "inventory_grn";
    $where = "id='$id' AND retailer_inwd_flg='1'";
    return $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function checkOrderTemporary($order_no) {
    $tbl_fields = "*";
    $table_name = "retailer_order_temporary";
    $where = "po_no='$order_no' AND status not in ('7','8')";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function checkOrderTemporaryById($id) {
    $tbl_fields = "*";
    $table_name = "retailer_order_temporary";
    $where = "id='$id' AND status not in ('7','8')";
    return $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getProductSalesByRetailerTempTable($f_date, $l_date, $Retailer_id, $company_id_in) {
    $where_q = "";
    if ($Retailer_id != "All") {
        $where_q = " AND m.`retailer_id`='$Retailer_id'";
    }
    $tbl_fields = "m.`retailer_id` AS retailer_id,m.added_datetime as added_datetime,m.order_no as order_no,t.price as total_price,m.total_count as total_count,m.fin_year AS fin_year,t.item_code as item_code,t.item_name as item_name,t.qty as qty,t.uom as uom,m.payment_type,m.transaction_no,t.cgst_rate,t.sgst_rate,t.cgst as cgst,t.sgst as sgst,t.basic as basic ,m.added_date as added_date,t.batch_no,m.cus_name,m.cus_add,m.cus_ph,m.cus_adhar,m.cus_village,m.cus_pin,m.b2b_flg,m.gstin_no";
    $table_name = "retailer_order_master m, retailer_order_temporary t";
    $where = "DATE(m.added_date) BETWEEN '$f_date' AND '$l_date' $where_q AND m.`order_no`=t.`po_no` AND m.status not in ('7','8') and m.company_id in ($company_id_in)";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getProductSalesByRetailerItemCodeTempTable($f_date, $l_date, $Retailer_id, $company_id_in, $item_code) {
    $where_q = "";
    if ($Retailer_id != "All") {
        $where_q = " AND m.`retailer_id`='$Retailer_id'";
    }
    if ($item_code == "All") {
        $where = "DATE(m.added_date) BETWEEN '$f_date' AND '$l_date' $where_q AND m.`order_no`=t.`po_no` AND m.status not in ('7','8') and m.company_id in ($company_id_in)";
    } else {
        $where = "t.item_code='$item_code' and DATE(m.added_date) BETWEEN '$f_date' AND '$l_date' $where_q AND m.`order_no`=t.`po_no` AND m.status not in ('7','8') and m.company_id in ($company_id_in)";
    }
    $tbl_fields = "m.`retailer_id` AS retailer_id,m.added_datetime as added_datetime,m.order_no as order_no,t.price as total_price,t.basic,m.total_count as total_count,m.fin_year AS fin_year,t.item_code as item_code,t.item_name as item_name,t.qty as qty,t.uom as uom,m.payment_type,m.transaction_no,t.cgst_rate,t.sgst_rate,t.cgst as cgst,t.sgst as sgst,t.basic as basic ,m.added_date as added_date,t.batch_no,m.cus_name,m.cus_add,m.cus_ph,m.cus_adhar,m.cus_village,m.cus_pin,m.b2b_flg,m.gstin_no,t.credit_note_no,t.return_qty";
    $table_name = "retailer_order_master m, retailer_order_temporary t";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getProductSalesByRetailerItemCodeTempTableAll($f_date, $l_date, $Retailer_id, $company_id_in, $item_code) {
    $where_q = "";
    if ($Retailer_id != "All") {
        $where_q = " AND m.`retailer_id`='$Retailer_id'";
    }
    $tbl_fields = "m.`retailer_id` AS retailer_id,m.added_datetime as added_datetime,m.order_no as order_no,t.price as total_price,t.basic,m.total_count as total_count,m.fin_year AS fin_year,t.item_code as item_code,t.item_name as item_name,t.qty as qty,t.uom as uom,m.payment_type,m.transaction_no,t.cgst_rate,t.sgst_rate,t.cgst as cgst,t.sgst as sgst,t.basic as basic ,m.added_date as added_date,t.batch_no,m.cus_name,m.cus_add,m.cus_ph,m.cus_adhar,m.cus_village,m.cus_pin,m.b2b_flg,m.gstin_no,t.credit_note_no,t.return_qty";
    $table_name = "retailer_order_master m, retailer_order_temporary t";
    $where = "DATE(m.added_date) BETWEEN '$f_date' AND '$l_date' $where_q AND m.`order_no`=t.`po_no` AND m.status not in ('7','8') and m.company_id in ($company_id_in)";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getProductSalesByRetailerTempTableItem($f_date, $l_date, $retailer_id_string, $company_id_in) {
    $tbl_fields = "m.`retailer_id` AS retailer_id,m.added_datetime as added_datetime,m.order_no as order_no,t.price as total_price,m.total_count as total_count,m.fin_year AS fin_year,t.item_code as item_code,t.item_name as item_name,t.qty as qty,t.uom as uom,m.payment_type,m.transaction_no,t.cgst_rate,t.sgst_rate,t.cgst as cgst,t.sgst as sgst,t.basic as basic ,m.added_date as added_date,t.batch_no,m.cus_name,m.cus_add,m.cus_ph,m.cus_adhar,m.cus_village,m.cus_pin,m.b2b_flg,m.gstin_no";
    $table_name = "retailer_order_master m, retailer_order_temporary t";
    $where = "DATE(m.added_date) BETWEEN '$f_date' AND '$l_date' AND m.`order_no`=t.`po_no` AND m.status not in ('7','8') and m.company_id in ($company_id_in) and t.retailer_id='$retailer_id_string'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getProductSalesByRetailerTempTableIN($f_date, $l_date, $retailer_id_string, $company_id_in) {
    $tbl_fields = "m.`retailer_id` AS retailer_id,m.added_datetime as added_datetime,m.order_no as order_no,t.price as total_price,m.total_count as total_count,m.fin_year AS fin_year,t.item_code as item_code,t.item_name as item_name,t.qty as qty,t.uom as uom,m.payment_type,m.transaction_no,t.cgst_rate,t.sgst_rate,t.cgst as cgst,t.sgst as sgst,t.basic as basic ,m.added_date as added_date,t.batch_no,m.cus_name,m.cus_add,m.cus_ph,m.cus_adhar,m.cus_village,m.cus_pin,m.b2b_flg,m.gstin_no,t.credit_note_no,t.return_qty";
    $table_name = "retailer_order_master m, retailer_order_temporary t";
    $where = "DATE(m.added_date) BETWEEN '$f_date' AND '$l_date' AND m.`order_no`=t.`po_no` AND m.status not in ('7','8') and m.company_id in ($company_id_in) and t.retailer_id in ($retailer_id_string)";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getProductSalesByRetailerTempTableCatIN($f_date, $l_date, $retailer_id_string, $company_id_in, $cat_id) {
    $cat_string = "";
    if (!empty($cat_id)) {
        $cat_string = " AND t.`main_category`='$cat_id'";
    }
    if (!empty($retailer_id_string)) {
        $cat_string .= " AND t.`retailer_id` in ($retailer_id_string)";
    }
    $tbl_fields = "t.main_category,t.sub_category,m.`retailer_id` AS retailer_id,m.added_datetime as added_datetime,m.order_no as order_no,t.price as total_price,m.total_count as total_count,m.fin_year AS fin_year,t.item_code as item_code,t.item_name as item_name,t.qty as qty,t.uom as uom,m.payment_type,m.transaction_no,t.cgst_rate,t.sgst_rate,t.cgst as cgst,t.sgst as sgst,t.basic as basic ,m.added_date as added_date,t.batch_no,m.cus_name,m.cus_add,m.cus_ph,m.cus_adhar,m.cus_village,m.cus_pin,m.b2b_flg,m.gstin_no,t.credit_note_no,t.return_qty";
    $table_name = "retailer_order_master m, retailer_order_temporary t";
    $where = "DATE(m.added_date) BETWEEN '$f_date' AND '$l_date' AND m.`order_no`=t.`po_no` AND m.status not in ('7','8') and m.company_id in ($company_id_in)" . $cat_string;
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getProductSalesByRetailerTempTableCatINOLLD($f_date, $l_date, $retailer_id_string, $company_id_in, $cat_id) {
    $cat_string = "";
    if (!empty($cat_id)) {
        $cat_string = " AND t.`main_category`='$cat_id'";
    }
    $tbl_fields = "m.`retailer_id` AS retailer_id,m.added_datetime as added_datetime,m.order_no as order_no,t.price as total_price,m.total_count as total_count,m.fin_year AS fin_year,t.item_code as item_code,t.item_name as item_name,t.qty as qty,t.uom as uom,m.payment_type,m.transaction_no,t.cgst_rate,t.sgst_rate,t.cgst as cgst,t.sgst as sgst,t.basic as basic ,m.added_date as added_date,t.batch_no,m.cus_name,m.cus_add,m.cus_ph,m.cus_adhar,m.cus_village,m.cus_pin,m.b2b_flg,m.gstin_no,t.credit_note_no,t.return_qty";
    $table_name = "retailer_order_master m, retailer_order_temporary t";
    $where = "DATE(m.added_date) BETWEEN '$f_date' AND '$l_date' AND m.`order_no`=t.`po_no` AND m.status not in ('7','8') and m.company_id in ($company_id_in) and t.retailer_id in ($retailer_id_string)" . $cat_string;
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getProductSalesByRetailerTempTableManagement($f_date, $company_id_in) {
    $tbl_fields = "m.`retailer_id` AS retailer_id,m.added_datetime as added_datetime,m.order_no as order_no,t.price as total_price,m.total_count as total_count,m.fin_year AS fin_year,t.item_code as item_code,t.item_name as item_name,t.qty as qty,t.uom as uom,m.payment_type,m.transaction_no,t.cgst_rate,t.sgst_rate,t.cgst as cgst,t.sgst as sgst,t.basic as basic ,m.added_date as added_date,t.batch_no,m.cus_name,m.cus_add,m.cus_ph,m.cus_adhar,m.cus_village,m.cus_pin,m.b2b_flg,m.gstin_no";
    $table_name = "retailer_order_master m, retailer_order_temporary t";
    $where = "DATE(m.added_date) BETWEEN '$f_date' AND '$f_date' AND m.`order_no`=t.`po_no` AND m.status not in ('7','8') and m.company_id in ($company_id_in) AND b2b_flg='1'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getProductSalesByRetailerTempTableB2B($f_date, $l_date, $Retailer_id, $company_id_in) {
    $where_q = "";
    if ($Retailer_id != "All") {
        $where_q = " AND m.`retailer_id`='$Retailer_id'";
    }
    $tbl_fields = "m.`retailer_id` AS retailer_id,m.added_datetime as added_datetime,m.order_no as order_no,t.price as total_price,m.total_count as total_count,m.fin_year AS fin_year,t.item_code as item_code,t.item_name as item_name,t.qty as qty,t.uom as uom,m.payment_type,m.transaction_no,t.cgst_rate,t.sgst_rate,t.cgst as cgst,t.sgst as sgst,t.basic as basic ,m.added_date as added_date,t.batch_no,m.cus_name,m.cus_add,m.cus_ph,m.cus_adhar,m.cus_village,m.cus_pin,m.b2b_flg,m.gstin_no,m.status";
    $table_name = "retailer_order_master m, retailer_order_temporary t";
    $where = "DATE(m.added_date) BETWEEN '$f_date' AND '$l_date' $where_q AND m.`order_no`=t.`po_no` AND m.status not in ('8') and m.b2b_flg=1 and m.company_id in ($company_id_in)";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getProductSalesByRetailerTempTableB2BBuyer($f_date, $l_date, $Retailer_id, $company_id_in, $buyer_id) {
    $where_q = "";
    if ($Retailer_id != "All") {
        $where_q = " AND m.`retailer_id`='$Retailer_id'";
    }
    $where_B = "";
    if ($buyer_id != "All") {
        $where_B = " AND m.`cus_name`='$buyer_id'";
    }
    $tbl_fields = "m.`retailer_id` AS retailer_id,m.added_datetime as added_datetime,m.order_no as order_no,t.price as total_price,m.total_count as total_count,m.fin_year AS fin_year,t.item_code as item_code,t.item_name as item_name,t.qty as qty,t.uom as uom,m.payment_type,m.transaction_no,t.cgst_rate,t.sgst_rate,t.cgst as cgst,t.sgst as sgst,t.basic as basic ,m.added_date as added_date,t.batch_no,m.cus_name,m.cus_add,m.cus_ph,m.cus_adhar,m.cus_village,m.cus_pin,m.b2b_flg,m.gstin_no,m.status,t.credit_note_no,t.return_qty";
    $table_name = "retailer_order_master m, retailer_order_temporary t";
    $where = "DATE(m.added_date) BETWEEN '$f_date' AND '$l_date' $where_q $where_B AND m.`order_no`=t.`po_no` AND m.status not in ('8') and m.b2b_flg=1 and m.company_id in ($company_id_in)";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getProductSalesByRetailerTempTableB2BBuyerCat($f_date, $l_date, $Retailer_id, $company_id_in, $buyer_id, $category_id) {
    $where_q = "";
    if ($Retailer_id != "All") {
        $where_q = " AND m.`retailer_id`='$Retailer_id'";
    }
    $where_B = "";
    if ($buyer_id != "All") {
        $where_B = " AND m.`cus_name`='$buyer_id'";
    }
    $where_C = "";
    if ($category_id != "All") {
        $where_C = " AND t.`main_category`='$category_id'";
    }
    $tbl_fields = "m.`retailer_id` AS retailer_id,m.added_datetime as added_datetime,m.order_no as order_no,t.price as total_price,m.total_count as total_count,m.fin_year AS fin_year,t.item_code as item_code,t.item_name as item_name,t.qty as qty,t.uom as uom,m.payment_type,m.transaction_no,t.cgst_rate,t.sgst_rate,t.cgst as cgst,t.sgst as sgst,t.basic as basic ,m.added_date as added_date,t.batch_no,m.cus_name,m.cus_add,m.cus_ph,m.cus_adhar,m.cus_village,m.cus_pin,m.b2b_flg,m.gstin_no,m.status,t.credit_note_no,t.return_qty";
    $table_name = "retailer_order_master m, retailer_order_temporary t";
    $where = "DATE(m.added_date) BETWEEN '$f_date' AND '$l_date' $where_q $where_B $where_C AND m.`order_no`=t.`po_no` AND m.status not in ('8') and m.b2b_flg=1 and m.company_id in ($company_id_in)";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getProductPriceUpdateHistory($f_date, $l_date, $Retailer_id, $item_code, $company_id_in) {
    $where_q = "";
    if ($Retailer_id != "All") {
        $where_q .= " AND retailer_id='$Retailer_id'";
    }
    if (!empty($item_code)) {
        $where_q .= " AND item_id='$item_code'";
    }
    $tbl_fields = "*";
    $table_name = "history_for_inventory_master";
    $where = "company_id in ($company_id_in) and date(date) between '$f_date' and '$l_date'" . $where_q;
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getProductPriceUpdateHistoryManagement($f_date, $company_id_in) {
    $tbl_fields = "*";
    $table_name = "history_for_inventory_master";
    $where = "company_id in ($company_id_in) and date(date) between '$f_date' and '$f_date'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getProductPriceUpdateHistoryData($f_date, $app_flag_type) {
    $tbl_fields = "*";
    $table_name = "history_for_inventory_master";
    $where = " date(date) between '$f_date' and '$f_date' and approval_flag = '" . $app_flag_type . "'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getProductSalesByRetailerTempTableDayWiseData($f_date, $l_date, $Retailer_id, $company_id_in) {
    $where_q = "";
    if ($Retailer_id != "All") {
        $where_q = " AND m.`retailer_id`='$Retailer_id'";
    }
    $tbl_fields = "t.batch_no,m.`retailer_id` AS retailer_id,m.added_datetime as added_datetime,m.order_no as order_no,t.price as total_price,m.total_count as total_count,m.fin_year AS fin_year,t.item_code as item_code,t.item_name as item_name,t.qty as qty,t.uom as uom,m.payment_type,m.transaction_no,t.cgst as cgst,t.sgst as sgst,t.basic as basic ,m.added_date as added_date";
    $table_name = "retailer_order_master m, retailer_order_temporary t";
    //    $where = "DATE(m.added_date) BETWEEN '$f_date' AND '$l_date' $where_q AND m.`order_no`=t.`po_no` AND m.status not in ('7','8') and m.company_id in ($company_id_in) and t.item_code='AGRO240'";
    $where = "DATE(m.added_date) BETWEEN '$f_date' AND '$l_date' $where_q AND m.`order_no`=t.`po_no` AND m.status not in ('7','8') and m.company_id in ($company_id_in)";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = 't.po_no,t.batch_no', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getRetailerPurchaseforProfibility($Retailer_id, $company_id_in) {
    $where_q = "";
    if ($Retailer_id != "All") {
        $where_q = " AND `retailer_id`='$Retailer_id'";
    }
    $tbl_fields = "retailer_id,SUM(po_basic * inward_qty) AS purchase";
    $table_name = "inventory_grn";
    $where = "retailer_inwd_flg = '1' and company_id in ($company_id_in)" . $where_q;
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = 'retailer_id', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getRetailerExpenssProfibility($Retailer_id) {
    $tbl_fields = "SUM(amount) AS expense";
    $table_name = "expense_details";
    $where = "status  = '2' and retailer_id='$Retailer_id'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    if (isset($result->expense)) {
        return $result->expense;
    } else {
        return 0;
    }
}

function getRetailerSalesProfibility($Retailer_id) {
    $tbl_fields = "SUM(basic) AS price";
    $table_name = "retailer_order_temporary";
    $where = "STATUS NOT IN ('7','8') and retailer_id='$Retailer_id'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    if (isset($result->price)) {
        return $result->price;
    } else {
        return 0;
    }
}

function getRetailerReturnSalesProfibility($Retailer_id) {
    $tbl_fields = "SUM(basic*return_qty) AS return_item_value";
    $table_name = "retailer_order_temporary";
    $where = "STATUS NOT IN ('7','8') and retailer_id='$Retailer_id' AND return_qty > 0;";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    if (isset($result->return_item_value)) {
        return $result->return_item_value;
    } else {
        return 0;
    }
}

function getRetailerReturnPurchaseProfibility($Retailer_id) {
    $tbl_fields = "SUM(amount) AS amount";
    $table_name = "purchase_order_return_detail";
    $where = "`delete`='0' and retailer_id='$Retailer_id' and status not in ('7','8')";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    if (isset($result->amount)) {
        return $result->amount;
    } else {
        return 0;
    }
}

function getProductSalesByRetailerTempTableDayWise($f_date, $l_date, $Retailer_id, $company_id_in) {
    $where_q = "";
    if ($Retailer_id != "All") {
        $where_q = " AND m.`retailer_id`='$Retailer_id'";
    }
    $tbl_fields = "m.`retailer_id` AS retailer_id,m.added_datetime as added_datetime,m.order_no as order_no,sum(t.price) as total_price,m.total_count as total_count,m.fin_year AS fin_year,t.item_code as item_code,t.item_name as item_name,t.qty as qty,t.uom as uom,m.payment_type,m.transaction_no,t.cgst as cgst,t.sgst as sgst,t.basic as basic ,m.added_date as added_date";
    $table_name = "retailer_order_master m, retailer_order_temporary t";
    $where = "DATE(m.added_date) BETWEEN '$f_date' AND '$l_date' $where_q AND m.`order_no`=t.`po_no` AND m.status not in ('7','8') and m.company_id in ($company_id_in)";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = 'date(m.added_date),m.`retailer_id`', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getProductSalesByRetailerTempTableWise($f_date, $l_date, $Retailer_id, $company_id_in) {
    $where_q = "";
    if ($Retailer_id != "All") {
        $where_q = " AND m.`retailer_id`='$Retailer_id'";
    }
    $tbl_fields = "m.`retailer_id` AS retailer_id,m.added_datetime as added_datetime,m.order_no as order_no,sum(t.price) as total_price,SUM(t.basic) AS basic,SUM(sgst) AS sgst,SUM(cgst) AS cgst,m.total_count as total_count,m.fin_year AS fin_year,t.item_code as item_code,t.item_name as item_name,t.qty as qty,t.uom as uom,m.payment_type,m.transaction_no,m.added_date as added_date";
    $table_name = "retailer_order_master m, retailer_order_temporary t";
    $where = "DATE(m.added_date) BETWEEN '$f_date' AND '$l_date' $where_q AND m.`order_no`=t.`po_no` AND m.status not in ('7','8') and m.company_id in ($company_id_in)";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = 'm.`retailer_id`', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getProductSalesByRetailerTempTableWiseCat($f_date, $l_date, $Retailer_id, $company_id_in, $category_id) {
    $where_q = "";
    if ($Retailer_id != "All") {
        $where_q .= " AND m.`retailer_id`='$Retailer_id'";
    }
    if ($category_id != "All") {
        $where_q .= " AND t.`main_category`='$category_id'";
    }
    $tbl_fields = "m.`retailer_id` AS retailer_id,m.added_datetime as added_datetime,m.order_no as order_no,sum(t.price) as total_price,SUM(t.basic) AS basic,SUM(sgst) AS sgst,SUM(cgst) AS cgst,m.total_count as total_count,m.fin_year AS fin_year,t.item_code as item_code,t.item_name as item_name,t.qty as qty,t.uom as uom,m.payment_type,m.transaction_no,m.added_date as added_date";
    $table_name = "retailer_order_master m, retailer_order_temporary t";
    $where = "DATE(m.added_date) BETWEEN '$f_date' AND '$l_date' $where_q AND m.`order_no`=t.`po_no` AND m.status not in ('7','8') and m.company_id in ($company_id_in)";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = 'm.`retailer_id`', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getProductSalesBy_IN_RetailerTempTableWiseCat($f_date, $l_date, $retailer_id_string, $company_id_in, $category_id) {
    $where_q = "";
    $where_q .= " AND m.`retailer_id` in (" . $retailer_id_string . ")";
    if ($category_id != "All") {
        $where_q .= " AND t.`main_category`='$category_id'";
    }
    $tbl_fields = "m.`retailer_id` AS retailer_id,m.added_datetime as added_datetime,m.order_no as order_no,sum(t.price) as total_price,SUM(t.basic) AS basic,SUM(sgst) AS sgst,SUM(cgst) AS cgst,m.total_count as total_count,m.fin_year AS fin_year,t.item_code as item_code,t.item_name as item_name,t.qty as qty,t.uom as uom,m.payment_type,m.transaction_no,m.added_date as added_date";
    $table_name = "retailer_order_master m, retailer_order_temporary t";
    $where = "DATE(m.added_date) BETWEEN '$f_date' AND '$l_date' $where_q AND m.`order_no`=t.`po_no` AND m.status not in ('7','8') and m.company_id in ($company_id_in)";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = 'm.`retailer_id`', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getProductSalesTotalAmtByRetailerTempTable($f_date, $l_date, $Retailer_id, $company_id_in) {
    $where_q = "";
    if ($Retailer_id != "All") {
        $where_q = " AND m.`retailer_id`='$Retailer_id'";
    }
    $tbl_fields = "sum(t.price) as totalFinal";
    $table_name = "retailer_order_master m, retailer_order_temporary t";
    $where = "DATE(m.added_date) BETWEEN '$f_date' AND '$l_date' $where_q AND m.`order_no`=t.`po_no` AND m.status not in ('7','8') and m.company_id in ($company_id_in)";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result->totalFinal;
}

function getB2BProductSalesTotalAmtByRetailerTempTable($f_date, $l_date, $Retailer_id, $company_id_in) {
    $where_q = "";
    if ($Retailer_id != "All") {
        $where_q = " AND m.`retailer_id`='$Retailer_id'";
    }
    $tbl_fields = "sum(t.price) as totalFinal";
    $table_name = "retailer_order_master m, retailer_order_temporary t";
    $where = "DATE(m.added_date) BETWEEN '$f_date' AND '$l_date' $where_q AND m.`order_no`=t.`po_no` and m.b2b_flg=1 AND m.status not in ('7','8') and m.company_id in ($company_id_in)";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result->totalFinal;
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

function getRetailerItemTotalPriceById($product_id, $Retailer_id) {
    $tbl_fields = "total";
    $table_name = "retailer_inventory_master";
    $where = "item_id='$product_id' and retailer_id='$Retailer_id'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    if (isset($result->total)) {
        return $result->total;
    } else {
        return 0.00;
    }
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

function getRetailerItemByOnlyRetailerId($retailer_id) {
    $tbl_fields = "*";
    $table_name = "retailer_inventory_master";
    $where = "status='1' and retailer_id='$retailer_id'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'item_desc', $asc = 1, $desc = 0, $limit = '');
}

function getRetailerItemByOnlyRetailerIdONDAte($retailer_id, $item_code) {
    $itemQ = "";
    if ($item_code != "ALL") {
        $itemQ = " AND item_code='$item_code'";
    }
    $tbl_fields = "*";
    $table_name = "retailer_inventory_master";
    $where = "status='1' and retailer_id='$retailer_id'" . $itemQ;
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'item_desc', $asc = 1, $desc = 0, $limit = '');
}

function getRetailerActivesItems($retailer_id) {
    $tbl_fields = "retailer_id,item_desc,item_code,main_category_id,sub_category_id,opening_stock,igst_rate,basic_price";
    $table_name = "retailer_inventory_master";
    //    $where = "STATUS='1' AND active='1' AND retailer_id NOT IN ('0','1','60') and retailer_id in ('28') and item_code in ('AGRO841')";
    $where = "STATUS='1' AND active='1' AND retailer_id NOT IN ('0','1','60') and retailer_id='$retailer_id'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'retailer_id,item_desc', $asc = 1, $desc = 0, $limit = '');
}

function getRetailerActivesbYItems($retailer_id, $item_code) {
    $query = '';
    if ($item_code != 'ALL') {
        $query = " and item_code in ('$item_code')";
    }
    $tbl_fields = "retailer_id,item_desc,item_code,main_category_id,sub_category_id,opening_stock,igst_rate,basic_price";
    $table_name = "retailer_inventory_master";
    //    $where = "STATUS='1' AND active='1' AND retailer_id NOT IN ('0','1','60') and retailer_id in ('28') and item_code in ('AGRO841')";
    $where = "STATUS='1' AND active='1' AND retailer_id NOT IN ('0','1','60') and retailer_id='$retailer_id'" . $query;
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'retailer_id,item_desc', $asc = 1, $desc = 0, $limit = '');
}

function getRetailerAllItems($retailer_id, $item_code) {
    $query = '';
    if ($item_code != 'ALL') {
        $query = " and item_code in ('$item_code')";
    }
    $tbl_fields = "retailer_id,item_desc,item_code,main_category_id,sub_category_id,opening_stock,igst_rate,basic_price";
    $table_name = "retailer_inventory_master";
    //    $where = "STATUS='1' AND active='1' AND retailer_id NOT IN ('0','1','60') and retailer_id in ('28') and item_code in ('AGRO841')";
    $where = "retailer_id NOT IN ('0','1','60') and retailer_id='$retailer_id'" . $query;
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'retailer_id,item_desc', $asc = 1, $desc = 0, $limit = '');
}

function getMailRetailerAllItems($retailer_id, $item_code) {
    $query = '';
    if ($item_code != 'ALL') {
        $query = " and item_code in ('$item_code')";
    }
    $tbl_fields = "retailer_id,item_desc,item_code,main_category_id,sub_category_id,opening_stock,igst_rate,basic_price";
    $table_name = "retailer_inventory_master";
    $where = "STATUS='1' AND active='1' AND retailer_id NOT IN ('0','1','60') and retailer_id in ('$retailer_id') and item_code in ('AGRO841')";
//    $where = "retailer_id NOT IN ('0','1','60') and retailer_id='$retailer_id'" . $query;
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'retailer_id,item_desc', $asc = 1, $desc = 0, $limit = '');
}

function getRetailerItemByRetailerId($retailer_id, $company_id_in) {
    $query = "";
    if ($retailer_id !== "ALL") {
        $query = " and retailer_id='$retailer_id'";
    }
    $tbl_fields = "*";
    $table_name = "retailer_inventory_master";
    $where = "company_id in ($company_id_in) and status='1'" . $query;
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
}

function getRetailerStockTInward($retailer_id, $item_code, $previous_date) {
    $tbl_fields = "SUM(inward_qty) AS inward_qty";
    $table_name = "inventory_grn";
    //    $where = "retailer_id='$retailer_id' and retailer_inwd_flg = '1' and item_desc='$item_code'  AND DATE(retailer_inwd_date) between '2021-04-01' and '$previous_date'";
    $where = "retailer_id='$retailer_id' and retailer_inwd_flg = '1' and item_desc='$item_code'  AND DATE(retailer_inwd_date) between '2021-04-01' and '$previous_date'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result->inward_qty;
}

function getRetailerStockTInwardFifoPurchaseGST($retailer_id, $item_code, $previous_date) {
    $tbl_fields = "po_basic,po_gst";
    $table_name = "inventory_grn";
    $where = "retailer_id='$retailer_id' and retailer_inwd_flg = '1' and item_desc='$item_code'  AND DATE(retailer_inwd_date) between '2021-04-01' and '$previous_date'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'id', $asc = '', $desc = '1', $limit = '1');
    return $result;
}

function getBackendRetailerStockTInward($retailer_id, $item_code, $previous_date) {
    $tbl_fields = "count(id) as count,SUM(current_stock) AS qty,SUM(purchae_basic) AS purchae_basic,SUM(purchase_gst) AS purchase_gst,SUM(purchase_total) AS purchase_total";
    $table_name = "item_inward_backend";
    //    $where = "retailer_id='$retailer_id' AND item_code='$item_code' AND DATE(update_datetime)='2023-07-01'";
    $where = "retailer_id='$retailer_id' AND item_code='$item_code' and status='1' AND DATE(update_datetime)='2023-07-01'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result;
}

function getBackendRetailerStockTInwardFifo($retailer_id, $item_code) {
    $tbl_fields = "SUM(current_stock) AS qty,purchae_basic,purchase_gst,purchase_total";
    $table_name = "item_inward_backend";
    //    $where = "retailer_id='$retailer_id' AND item_code='$item_code' AND DATE(update_datetime)='2023-07-01'";
    $where = "retailer_id='$retailer_id' AND item_code='$item_code' and status='1' AND DATE(update_datetime)='2023-07-01'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result;
}

function getBackendRetailerStockTInwardValue($retailer_id) {
    $tbl_fields = "SUM(purchase_total) AS purchase_total";
    $table_name = "item_inward_backend";
    $where = "retailer_id='$retailer_id' and status='1' AND DATE(update_datetime)='2023-07-01'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    if (isset($result->purchase_total)) {
        return $result->purchase_total;
    } else {
        return 0;
    }
}

function getBackendRetailerStockTInwardValueRetaikerMaster($retailer_id) {
    $tbl_fields = "opening";
    $table_name = "retailer_master";
    $where = "id='$retailer_id'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    if (isset($result->opening)) {
        return $result->opening;
    } else {
        return 0;
    }
}

function getRetailerTransferPurchareonDate($retail_id, $item_code, $date_1) {
    $tbl_fields = "SUM(pd.`qty`) AS qty";
    $table_name = "`purchase_order_return` p,`purchase_order_return_detail` pd";
    $where = "p.`retailer_id`='$retail_id' AND pd.`item_id`='$item_code'  AND pd.`delete`='0' AND DATE(p.`po_date`) = '$date_1' AND p.`id`=pd.`id`";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result->qty;
}

function getRetailerTransferPurchareonDateMail($retail_id, $item_code, $date_1) {
    $tbl_fields = "SUM(pd.`qty`) AS qty,pd.amount,pd.gst_rate,pd.retailer_id,p.vendor_id as vendor_id";
    $table_name = "`purchase_order_return` p,`purchase_order_return_detail` pd";
    $where = "p.`retailer_id`='$retail_id' AND pd.`item_id`='$item_code'  AND pd.`delete`='0' AND DATE(p.`po_date`) = '$date_1' AND p.`id`=pd.`id`";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result;
}

function getRetailerTransferPurchareonDateMailBetween($retail_id, $item_code, $date_1, $date_2) {
    $tbl_fields = "sum(total_basic) as total_basic,count(pd.id) as count,SUM(pd.`qty`) AS qty,sum(pd.amount) as amount,sum(pd.rate) as rate,sum(pd.gst_rate) as gst_rate,pd.retailer_id,p.vendor_id as vendor_id,p.po_no";
    $table_name = "`purchase_order_return` p,`purchase_order_return_detail` pd";
    $where = "p.`retailer_id`='$retail_id' AND pd.`item_id`='$item_code' AND DATE(p.`po_date`) between '$date_1' and '$date_2' AND p.`id`=pd.`id` AND pd.delete='0'";
    //    $where = "p.`retailer_id`='$retail_id' AND pd.`item_id`='$item_code' AND DATE(p.`po_date`) between '$date_1' and '$date_2' AND p.`id`=pd.`id` and p.vendor_id not in ('1','6','17','18','0')";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result;
}

function getRetailerTransferPurchareonDateMailBetweenFifo($retail_id, $item_code, $date_1, $date_2) {
    $tbl_fields = "SUM(pd.`qty`) AS qty";
    $table_name = "`purchase_order_return` p,`purchase_order_return_detail` pd";
    $where = "p.`retailer_id`='$retail_id' AND pd.`item_id`='$item_code' AND DATE(p.`po_date`) between '$date_1' and '$date_2' AND p.`id`=pd.`id` AND pd.delete='0' and pd.status not in ('7')";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result;
}

function getRetailerStockTransferonDate($retailer_id, $item_code, $date_1) {
    $tbl_fields = "SUM(inward_qty) AS inward_qty";
    $table_name = "inventory_grn";
    $where = "dispatch_retailer_id='$retailer_id' and retailer_inwd_flg = '1' and item_desc='$item_code'  AND DATE(retailer_inwd_date)='$date_1'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    if (isset($result->inward_qty)) {
        return $result->inward_qty;
    } else {
        return 0;
    }
}

function getRetailerStockTransferonDateMail($retailer_id, $item_code, $date_1) {
    $tbl_fields = "SUM(inward_qty) AS inward_qty,dispatch_retailer_id,retailer_id,po_basic,po_gst";
    $table_name = "inventory_grn";
    $where = "dispatch_retailer_id='$retailer_id' and retailer_inwd_flg = '1' and item_desc='$item_code'  AND DATE(retailer_inwd_date)='$date_1'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result;
}

function getRetailerStockTransferonDateMailBetween($retailer_id, $item_code, $date_1, $date_2) {
    $tbl_fields = "count(id) as count,SUM(inward_qty) AS inward_qty,dispatch_retailer_id,retailer_id,sum(po_basic) as po_basic,sum(po_gst) as po_gst,sum(po_total_basic_value) as po_total_basic_value";
    $table_name = "inventory_grn";
    $where = "dispatch_retailer_id='$retailer_id' and retailer_inwd_flg = '1' and item_desc='$item_code'  AND DATE(retailer_inwd_date) between '$date_1' and '$date_2'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result;
}

function getRetailerStockTransferonDateMailBetweenFifo($retailer_id, $item_code, $date_1, $date_2) {
    $tbl_fields = "SUM(inward_qty) AS inward_qty";
    $table_name = "inventory_grn";
    $where = "dispatch_retailer_id='$retailer_id' and retailer_inwd_flg = '1' and item_desc='$item_code'  AND DATE(retailer_inwd_date) between '$date_1' and '$date_2'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result;
}

function getRetailerReturnPO($item_code, $retailer_id) {
    $tbl_fields = "SUM(qty) AS qty";
    $table_name = "purchase_order_return_detail";
    $where = "retailer_id='$retailer_id' AND item_id='$item_code' AND STATUS='1'  AND `delete`='0'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = 'item_id', $order_by = '', $asc = '', $desc = '', $limit = '');
    if (isset($result->qty)) {
        return $result->qty;
    } else {
        return 0;
    }
}

function getRetailerSalesDetailonDate($retail_id, $item_code, $date_1) {
    $tbl_fields = "SUM(qty) AS qty";
    $table_name = "retailer_order_temporary";
    $where = "retailer_id='$retail_id' AND item_code='$item_code' AND STATUS NOT IN ('7','8')  AND DATE(order_place_date) = '$date_1'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result->qty;
}

function getRetailerSalesDetailByBatchNumber($retail_id, $item_code, $batch_number) {
    $tbl_fields = "SUM(qty) AS qty";
    $table_name = "retailer_order_temporary";
    $where = "retailer_id='$retail_id' AND item_code='$item_code' AND STATUS NOT IN ('7','8')  AND batch_no = '$batch_number'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    if (isset($result->qty)) {
        return $result->qty;
    } else {
        return 0;
    }
}

function getRetailerSalesDetailByBatchNumberInwardNo($retail_id, $item_code, $batch_number, $inward_no, $purchase_qty) {
    $tbl_fields = "SUM(qty) AS qty";
    $table_name = "retailer_order_temporary";
    $where = "retailer_id='$retail_id' AND item_code='$item_code' AND STATUS NOT IN ('7','8')  AND batch_no = '$batch_number' AND purchase_no='$inward_no'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = $purchase_qty);
    if (isset($result->qty)) {
        return $result->qty;
    } else {
        return 0;
    }
}

function getRetailerSalesDetailonDateBetween($retail_id, $item_code, $date_1, $date_2) {
    $tbl_fields = "count(id) as count,SUM(qty) AS qty,sum(basic) as basic,sum(cgst_rate) as cgst_rate,sum(sgst_rate) as sgst_rate";
    $table_name = "retailer_order_temporary";
    $where = "retailer_id='$retail_id' AND item_code='$item_code' AND STATUS NOT IN ('7','8')  AND DATE(order_place_date) between '$date_1' and '$date_2'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result;
}

function getRetailerSalesDetailonDateBetweenFifo($retail_id, $item_code, $date_1, $date_2) {
    $tbl_fields = "count(id) as count,SUM(qty) AS qty,sum(return_qty) as return_qty,sum(basic) as basic,sum(cgst_rate) as cgst_rate,sum(sgst_rate) as sgst_rate";
    $table_name = "retailer_order_temporary";
    $where = "retailer_id='$retail_id' AND item_code='$item_code' AND STATUS NOT IN ('7','8')  AND DATE(order_place_date) between '$date_1' and '$date_2'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result;
}

function getRetailerSalesDetailsonDate($retail_id, $item_code, $On_date) {
    $tbl_fields = "qty,po_no";
    $table_name = "retailer_order_temporary";
    $where = "retailer_id='$retail_id' AND item_code='$item_code' AND STATUS NOT IN ('7','8')  AND DATE(order_place_date) = '$On_date'";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result;
}

function getRetailerStockTInwardForDate($retailer_id, $item_code, $date_1) {
    $tbl_fields = "SUM(inward_qty) AS inward_qty";
    $table_name = "inventory_grn";
    //    $where = "retailer_id='$retailer_id' and retailer_inwd_flg = '1' and item_desc='$item_code'  AND DATE(retailer_inwd_date)='$date_1'";
    $where = "retailer_id='$retailer_id' and retailer_inwd_flg = '1' and item_desc='$item_code'  AND DATE(po_date)='$date_1'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result->inward_qty;
}

function getRetailerStockTInwardForDateMail($retailer_id, $item_code, $date_1) {
    $tbl_fields = "SUM(inward_qty) AS inward_qty,dispatch_retailer_id,po_basic,po_gst";
    $table_name = "inventory_grn";
    //    $where = "retailer_id='$retailer_id' and retailer_inwd_flg = '1' and item_desc='$item_code'  AND DATE(retailer_inwd_date)='$date_1'";
    $where = "retailer_id='$retailer_id' and dispatch_retailer_id!=0 and retailer_inwd_flg = '1' and item_desc='$item_code'  AND DATE(po_date)='$date_1'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result;
}

function getRetailerStockTInwardForDateMailBetween($retailer_id, $item_code, $date_1, $date_2) {
    $tbl_fields = "count(id) as count,SUM(inward_qty) AS inward_qty,dispatch_retailer_id,sum(po_basic) as po_basic,sum(po_gst) as po_gst,SUM(po_total_basic_value) as po_total_basic_value";
    $table_name = "inventory_grn";
    //    $where = "retailer_id='$retailer_id' and retailer_inwd_flg = '1' and item_desc='$item_code'  AND DATE(retailer_inwd_date)='$date_1'";
    $where = "retailer_id='$retailer_id' and dispatch_retailer_id!=0 and retailer_inwd_flg = '1' and item_desc='$item_code'  AND DATE(retailer_inwd_date) between '$date_1' and '$date_2'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result;
}

function getRetailerStockTInwardForDateMailBetweenFifo($retailer_id, $item_code, $date_1, $date_2) {
    $tbl_fields = "SUM(inward_qty) AS inward_qty";
    $table_name = "inventory_grn";
    $where = "retailer_id='$retailer_id' and dispatch_retailer_id!='0' and retailer_inwd_flg = '1' and item_desc='$item_code'  AND DATE(retailer_inwd_date) between '$date_1' and '$date_2'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result;
}

function getRetailerStockTInwardForDatePOMail($retailer_id, $item_code, $date_1) {
    $tbl_fields = "SUM(inward_qty) AS inward_qty,retailer_id,po_basic,po_gst,vendor_id";
    $table_name = "inventory_grn";
    //    $where = "retailer_id='$retailer_id' and retailer_inwd_flg = '1' and item_desc='$item_code'  AND DATE(retailer_inwd_date)='$date_1'";
    $where = "retailer_id='$retailer_id' and dispatch_retailer_id=0 and retailer_inwd_flg = '1' and item_desc='$item_code'  AND DATE(po_date)='$date_1'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result;
}

function getRetailerStockTInwardForDatePOMailBetween($retailer_id, $item_code, $date_1, $date_2) {
    $tbl_fields = "count(id) as cout,SUM(inward_qty) AS inward_qty,retailer_id,sum(po_basic) as po_basic,sum(po_gst) as po_gst,vendor_id,sum(po_total_basic_value) as po_total_basic_value";
    $table_name = "inventory_grn";
    //    $where = "retailer_id='$retailer_id' and retailer_inwd_flg = '1' and item_desc='$item_code'  AND DATE(retailer_inwd_date)='$date_1'";
    $where = "retailer_id='$retailer_id' and dispatch_retailer_id=0 and retailer_inwd_flg = '1' and item_desc='$item_code'  AND DATE(retailer_inwd_date) between '$date_1' and '$date_2'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result;
}

function getRetailerStockTInwardForDatePOMailBetweenFifoPurchase($retailer_id, $item_code, $date_1, $date_2) {
    $tbl_fields = "po_basic,po_gst";
    $table_name = "inventory_grn";
    $where = "retailer_id='$retailer_id' and dispatch_retailer_id='0' and retailer_inwd_flg = '1' and item_desc='$item_code'  AND DATE(retailer_inwd_date) between '$date_1' and '$date_2' and po_basic>0";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'id', $asc = '', $desc = '1', $limit = '1');
    return $result;
}

function getRetailerStockTRANSFERInwardForDatePOMailBetweenFifoPurchase($retailer_id, $item_code, $date_1, $date_2) {
    $tbl_fields = "po_basic,po_gst";
    $table_name = "inventory_grn";
    $where = "retailer_id='$retailer_id' and dispatch_retailer_id!='0' and retailer_inwd_flg = '1' and item_desc='$item_code'  AND DATE(retailer_inwd_date) between '$date_1' and '$date_2' and po_basic>0";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'id', $asc = '', $desc = '1', $limit = '1');
    return $result;
}

function getRetailerStockTInwardForDatePOMailBetweenFifoPurchaseWhatever($retailer_id, $item_code, $date_1, $date_2) {
    $tbl_fields = "po_basic,po_gst";
    $table_name = "inventory_grn";
    $where = "retailer_inwd_flg = '1' and item_desc='$item_code' AND po_basic>0";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'id', $asc = '', $desc = '1', $limit = '1');
    return $result;
}

function getCompanyRetailerStockTInwardForDatePOMailBetweenFifoPurchaseWhatever($retailer_company_id, $item_code, $date_1, $date_2) {
    $tbl_fields = "po_basic,po_gst";
    $table_name = "inventory_grn";
    $where = "company_id='$retailer_company_id' and retailer_inwd_flg = '1' and item_desc='$item_code' AND po_basic>0";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'id', $asc = '', $desc = '1', $limit = '1');
    return $result;
}

function getRetailerStockTInwardForDatePOMailBetweenFifo($retailer_id, $item_code, $date_1, $date_2) {
    $tbl_fields = "SUM(inward_qty) AS inward_qty";
    $table_name = "inventory_grn";
    $where = "retailer_id='$retailer_id' and po_type not in ('2') and dispatch_retailer_id=0 and retailer_inwd_flg = '1' and status not in ('7') and item_desc='$item_code'  AND DATE(retailer_inwd_date) between '$date_1' and '$date_2'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result;
}

function getRetailerStockTInwardForDateCreditNoteMailBetweenFifo($retailer_id, $item_code, $date_1, $date_2) {
    $tbl_fields = "SUM(inward_qty) AS inward_qty";
    $table_name = "inventory_grn";
    $where = "retailer_id='$retailer_id' and po_type='2' and dispatch_retailer_id=0 and retailer_inwd_flg = '1' and status not in ('7') and item_desc='$item_code'  AND DATE(retailer_inwd_date) between '$date_1' and '$date_2'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result;
}

function getRetailerTransferPurchare($retail_id, $item_code, $date_1) {
    $tbl_fields = "SUM(pd.`qty`) AS qty";
    $table_name = "`purchase_order_return` p,`purchase_order_return_detail` pd";
    $where = "p.`retailer_id`='$retail_id' AND pd.`item_id`='$item_code' AND DATE(p.`po_date`) between '2021-04-01' and '$date_1' AND p.`id`=pd.`id` AND pd.delete='0' and pd.status not in ('7')";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result->qty;
}

function getRetailerStockTransfer($retailer_id, $item_code, $date_1) {
    $tbl_fields = "SUM(inward_qty) AS inward_qty";
    $table_name = "inventory_grn";
    $where = "dispatch_retailer_id='$retailer_id' and retailer_inwd_flg = '1' and item_desc='$item_code'  AND DATE(retailer_inwd_date) between '2021-04-01' and '$date_1'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result->inward_qty;
}

function getRetailerSalesDetail($retail_id, $item_code, $date_1) {
    $tbl_fields = "SUM(qty) AS qty,return_qty";
    $table_name = "retailer_order_temporary";
    $where = "retailer_id='$retail_id' AND item_code='$item_code' AND STATUS NOT IN ('7','8') AND DATE(order_place_date) between '2021-04-01' and '$date_1'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return ($result->qty - $result->return_qty);
}

function getRetailerItemByRetailerIdItemIdAll($retailer_id, $item_code, $company_id_in) {
    $query = "";
    if ($retailer_id !== "ALL") {
        $query .= " and retailer_id='$retailer_id'";
    }
    if ($item_code !== "ALL") {
        $query .= " and item_code='$item_code'";
    }
    $tbl_fields = "*";
    $table_name = "retailer_inventory_master";
    $where = "company_id in ($company_id_in) and status='1'" . $query;
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'item_desc,id', $asc = 1, $desc = 0, $limit = '');
}

function getRetailerItemByRetailerIdItemId($retailer_id, $item_code, $company_id_in) {
    $query = "";
    if ($retailer_id !== "ALL") {
        $query .= " and retailer_id='$retailer_id'";
    }
    if ($item_code !== "ALL") {
        $query .= " and item_code='$item_code'";
    }
    $tbl_fields = "*";
    $table_name = "retailer_inventory_master";
    $where = "company_id in ($company_id_in) and current_stock>0 and status='1'" . $query;
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'item_desc,id', $asc = 1, $desc = 0, $limit = '');
}

function getRetailerItemByRetailerIdItemId_for_without_batch($retailer_id, $item_code, $company_id_in) {
    $query = "";
    if ($retailer_id !== "ALL") {
        $query .= " and retailer_id='$retailer_id'";
    }
    if ($item_code !== "ALL") {
        $query .= " and item_code='$item_code'";
    }
    $tbl_fields = "*";
    $table_name = "retailer_inventory_master";
    $where = "company_id in ($company_id_in) and current_stock !=0 and status='1'" . $query;
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'item_desc,id', $asc = 1, $desc = 0, $limit = '');
}

function getRetailerItemByItemCodeRetailerId($item_code, $retailer_id) {
    $tbl_fields = "issued_stock,current_stock";
    $table_name = "retailer_inventory_master";
    $where = "status='1' and retailer_id='$retailer_id' and item_code='$item_code'";
    return $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
}

function getAllRetailerItems($limit) {
    $tbl_fields = "*";
    $table_name = "retailer_inventory_master";
    $where = "stock_update='0'";
    //    $where = "item_code='AGRO54641662205526' and retailer_id='5' and stock_update='0'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'id', $asc = 0, $desc = 1, $limit);
}

function getInwardedItem($item_code, $retailer_id) {
    $tbl_fields = "sum(billed_qty) as billed_qty,item_desc,retailer_id";
    $table_name = "inventory_grn";
    $where = "item_desc='$item_code' AND retailer_id='$retailer_id' AND retailer_inwd_flg='1' AND deleted='0'";
    return $result = mysql_select($tbl_fields, $table_name, $where, $group_by = 'item_desc', $order_by = '', $asc = 0, $desc = 0, $limit = '');
}

function getInwardedItemQty($item_code, $retailer_id) {
    $tbl_fields = "sum(billed_qty) as billed_qty,item_desc,retailer_id";
    $table_name = "inventory_grn";
    $where = "item_desc='$item_code' AND retailer_id='$retailer_id' AND retailer_inwd_flg='1' AND deleted='0'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = 'item_desc', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    if (isset($result->billed_qty)) {
        return $result->billed_qty;
    } else {
        return 0;
    }
}

function getInwardedBackEndItemQty($item_code, $retailer_id) {
    $tbl_fields = "SUM(current_stock) AS current_stock";
    $table_name = "item_inward_backend";
    $where = "item_code='$item_code' AND retailer_id='$retailer_id' AND STATUS='1' AND DATE(update_datetime)>='2023-07-01'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = 'item_code', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    if (isset($result->current_stock)) {
        return $result->current_stock;
    } else {
        return 0;
    }
}

function getDispatchedItem($item_code, $retailer_id) {
    $tbl_fields = "sum(billed_qty) as billed_qty,item_desc,dispatch_retailer_id";
    $table_name = "inventory_grn";
    $where = "item_desc='$item_code' AND dispatch_retailer_id='$retailer_id' AND retailer_inwd_flg='1' AND deleted='0'";
    return $result = mysql_select($tbl_fields, $table_name, $where, $group_by = 'item_desc', $order_by = '', $asc = 0, $desc = 0, $limit = '');
}

function getDispatchedItemQty($item_code, $retailer_id) {
    $tbl_fields = "sum(billed_qty) as billed_qty,item_desc,dispatch_retailer_id";
    $table_name = "inventory_grn";
    $where = "item_desc='$item_code' AND dispatch_retailer_id='$retailer_id' AND retailer_inwd_flg='1' AND deleted='0'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = 'item_desc', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    if (isset($result->billed_qty)) {
        return $result->billed_qty;
    } else {
        return 0;
    }
}

function getActiveRetailer() {
    $tbl_fields = "*";
    $table_name = "retailer_master";
    $where = "status='1'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getActiveRetailerCompny($company_id) {
    $tbl_fields = "*";
    $table_name = "retailer_master";
    $where = "status='1' and company_id='$company_id'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getActiveRetailerCompnyIn() {
    global $company_id;
    $tbl_fields = "*";
    $table_name = "retailer_master";
    $where = "status='1' and company_id in ($company_id)";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getActiveRetailerIN($retailer_in) {
    $tbl_fields = "*";
    $table_name = "retailer_master";
    $query = "";
    if ($retailer_in != "ALL") {
        $query .= "and id in ($retailer_in)";
    }
    $where = "status='1' " . $query;
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

function getItemParentCategoryIdItemcode($item_code) {
    $tbl_fields = "main_category_id";
    $table_name = "inventory_master";
    $where = "item_code='$item_code'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result->main_category_id;
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

function getAdminFixedAssets() {
    $tbl_fields = "*";
    $table_name = "`fixed_asset`";
    $where = "";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getActiveFixedAssets() {
    $tbl_fields = "*";
    $table_name = "`fixed_asset`";
    $where = "status='1'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getActiveFixedAssetsCountByFinYearRetailerIdQty($fin_year, $retailer_id, $item_code) {
    $tbl_fields = "qty";
    $table_name = "`retailer_fixed_asset`";
    $where = "status='1' and retailer_id='$retailer_id' and item_code='$item_code' and fin_year='$fin_year'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    if (isset($result->qty)) {
        return $result->qty;
    } else {
        return '0.00';
    }
}

function getVillages() {
    $tbl_fields = "*";
    $table_name = "`villages`";
    $where = "";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getVillagesByCompanyId() {
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

function getActivepPackSize() {
    $tbl_fields = "name";
    $table_name = "`pack_size_master`";
    $where = "status='1'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getActivepUnit() {
    $tbl_fields = "name";
    $table_name = "`unit_master`";
    $where = "status='1'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getAdminCategoriesById($category_id) {
    $tbl_fields = "*";
    $table_name = "`categories`";
    $where = "id='$category_id'";
    return $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getAdminFixedAssetsById($asset_id) {
    $tbl_fields = "*";
    $table_name = "`fixed_asset`";
    $where = "id='$asset_id'";
    return $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getLastAddCategory() {
    $tbl_fields = "id";
    $table_name = "`categories`";
    $where = "";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'id', $asc = 0, $desc = 1, $limit = 1);
    return $result->id;
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
//    $where = "`username`='$username' AND PASSWORD='$password' AND status='1' AND company_id like '%3%'";
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
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'id', $asc = 1, $desc = 0, $limit = '');
    return $result;
}

function getRetailerSubMenuList($menuId) {
    $tbl_fields = "*";
    $table_name = "master_menu";
    $where = 'retailer_flg="' . $menuId . '" AND status="1"';
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'id', $asc = 1, $desc = 0, $limit = '');
    return $result;
}

function getMenuheader() {
    $tbl_fields = "*";
    $table_name = "master_menu";
    $where = 'master_id="0" AND status="1"';
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'id', $asc = 1, $desc = 0, $limit = '');
    return $result;
}

function getMenuNameById($id) {
    $tbl_fields = "page_title";
    $table_name = "master_menu";
    $where = 'id="' . $id . '" AND status="1"';
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    if (isset($result->page_title)) {
        return $result->page_title;
    } else {
        return "";
    }
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
    if ($table_name == "purchase_order a, `purchase_order_detail` b, `inventory_grn` c") {
//        echo $sql;
//        exit;
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
    if ($table_name == "item_sr_master") {
        //        echo $sql;
        //        exit;
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
    if ($table_name == "purchase_order_return_detail") {
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
    $where = "status='$status' and company_id in ($company_id)";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getPurchaseOrderListByStatusDates($status, $company_id, $date_1, $date_2) {
    $tbl_fields = "*";
    $table_name = "purchase_order";
    $where = "status='$status' and company_id in ($company_id) AND DATE(po_date) BETWEEN '$date_1' AND '$date_2'";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getPurchaseOrderListByStatusMan($date_1, $company_id) {
    $tbl_fields = "*";
    $table_name = "purchase_order";
    $where = "company_id in ($company_id) and po_date='$date_1'";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getPurchaseOrderListByStatusForBasic($where) {
    $tbl_fields = "*";
    $table_name = "purchase_order_basic";
    $where = $where;
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getPurchaseOrderListByStatusForBasicDetailsJoin($company_id) {
    $tbl_fields = "p.added_date,p.grand_total,p.status,p.status_upd_date,pd.item_id,pd.retailer_string,pd.item_id,p.upload_invoice_no,p.invoice_remarks,p.invoice_upload_date,p.invoice_upload,p.invoice_flag,p.status as status,p.po_no as po_no,p.po_date as po_date,p.supplier_id as supplier_id,p.status_remarks as status_remarks,pd.item_id as item,pd.rate,pd.qty as qty,pd.retailer_string as retailer_string";
    $table_name = "`purchase_order_basic` p,`purchase_order_basic_detail` pd";
    $where = "p.company_id='$company_id' AND p.`id`=pd.`id`";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getPurchaseOrderListByStatusForBasicDetailsJoinWithDate($company_id, $date_1, $date_2) {
    $tbl_fields = "p.id,p.added_date,p.grand_total,p.status,p.status_upd_date,pd.item_id,pd.retailer_string,pd.item_id,p.upload_invoice_no,p.invoice_remarks,p.invoice_upload_date,p.invoice_upload,p.invoice_flag,p.status as status,p.po_no as po_no,p.po_date as po_date,p.supplier_id as supplier_id,p.status_remarks as status_remarks,pd.item_id as item,pd.rate,pd.qty as qty,pd.retailer_string as retailer_string";
    $table_name = "`purchase_order_basic` p,`purchase_order_basic_detail` pd";
    $where = "p.company_id='$company_id' and date(p.po_date) between '$date_1' and '$date_2' AND p.`id`=pd.`id`";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getPurchaseOrderListByStatusForBasicDetailsJoinWithDateCounts($company_id, $date_1, $date_2) {
    $tbl_fields = "status,count(id) as count";
    $table_name = "`purchase_order_basic`";
    $where = "company_id='$company_id' and date(po_date) between '$date_1' and '$date_2'";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = 'status', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getPurchaseOrderReferenceList($company_id) {
    $tbl_fields = "p.po_no as po_no,p.supplier_name as supplier_name,pd.retailer_string as retailer_string";
    $table_name = "`purchase_order_basic` p,`purchase_order_basic_detail` pd";
    $where = "p.company_id='$company_id' AND p.status='0' AND p.`id`=pd.`id`";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = 'p.po_no', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getPurchaseOrderListByStatusForBasicDetails($id) {
    $tbl_fields = "*";
    $table_name = "purchase_order_basic_detail";
    $where = " id='$id'";
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

function getStockTransferRequest($date_1, $date_2, $retailer_id, $company_id_in) {
    $queryy = "";
    if ($retailer_id != "All") {
        $queryy .= " and frm_retailer_id ='$retailer_id'";
    }
    $tbl_fields = "*";
    $table_name = "retailer_stock_transfer";
    $where = "date(add_date) between '$date_1' and '$date_2' and company_id in ($company_id_in)" . $queryy;
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'id', $asc = 0, $desc = 1, $limit = '');
    return $result;
}

function getApproveStockRequestById($id) {
    $tbl_fields = "*";
    $table_name = "retailer_stock_transfer";
    $where = "ctrl_off_flag='0' and deleted='0' and id='$id'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getRetailerItemCurrentStockById($product_id, $Retailer_id) {
    $tbl_fields = "current_stock";
    $table_name = "retailer_inventory_master";
    $where = "item_code='$product_id' and retailer_id='$Retailer_id'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    if (isset($result->current_stock)) {
        return $result->current_stock;
    } else {
        return 0;
    }
}

function getApproveStockRequestIN($company_id_in) {
    $tbl_fields = "*";
    $table_name = "retailer_stock_transfer";
    $where = "ctrl_off_flag='0' and deleted='0' and status='1' AND company_id in ($company_id_in)";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getApproveStockRequestINByOrderNo($company_id_in, $order_no) {
    $tbl_fields = "*";
    $table_name = "retailer_stock_transfer";
    $where = "ctrl_off_flag='0' and deleted='0' AND company_id in ($company_id_in) and order_no='$order_no'";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'order_no,qty', $asc = 0, $desc = 1, $limit = '');
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

function getApprovedTransactionbyDade($retailer_id, $date) {
    $tbl_fields = "transaction_no,slip,mode,bank_id,amount,remarks,transaction_remark,retailer_id";
    $table_name = "transaction_details";
    $where = "retailer_id='$retailer_id' AND STATUS='1' AND DATE(transaction_date)='$date'";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getApprovedReceivedTransactionbyDate($retailer_id, $date) {
    $tbl_fields = "transaction_no,slip,mode,bank_id,amount,remarks,transaction_remark,retailer_id";
    $table_name = "transaction_details";
    $where = "bank_id='$retailer_id' AND STATUS='1' and mode='2' AND DATE(transaction_date) = '$date'";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getApprovedTransactionbyDadeCount($retailer_id) {
    $tbl_fields = "sum(amount) as amount";
    $table_name = "transaction_details";
    $where = "retailer_id='$retailer_id' AND STATUS='1'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result->amount;
}

function getApprovedTransactionbyDadeCountAsOn($retailer_id, $date_1, $date_2) {
    $tbl_fields = "sum(amount) as amount";
    $table_name = "transaction_details";
    $where = "retailer_id='$retailer_id' AND STATUS='1' AND DATE(transaction_date) between '$date_1' and '$date_2'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result->amount;
}

function getApprovedTransactionDetailsbyDates($retailer_id, $date_1, $date_2) {
    $tbl_fields = "*";
    $table_name = "transaction_details";
    $where = "retailer_id='$retailer_id' AND STATUS='1' AND DATE(transaction_date) between '$date_1' and '$date_2'";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getApprovedDayWiseTransactionbyDadeCountAsOn($retailer_id, $date_1) {
    $tbl_fields = "sum(amount) as amount";
    $table_name = "transaction_details";
    $where = "retailer_id='$retailer_id' AND STATUS='1' AND DATE(transaction_date) = '$date_1'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result->amount;
}

function getBankDepositApprovedTransactionbyDadeCountAsOn($retailer_id, $date_1) {
    $tbl_fields = "sum(amount) as amount";
    $table_name = "transaction_details";
    $where = "retailer_id='$retailer_id' AND STATUS='1' AND DATE(transaction_date) = '$date_1' and bank_id not in ('145') and mode='1'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result->amount;
}

function getUPIDepositApprovedTransactionbyDadeCountAsOn($retailer_id, $date_1) {
    $tbl_fields = "sum(amount) as amount";
    $table_name = "transaction_details";
    $where = "retailer_id='$retailer_id' AND STATUS='1' AND DATE(transaction_date) = '$date_1' and bank_id in ('145') and mode='1'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result->amount;
}

function getTransferedDepositApprovedTransactionbyDadeCountAsOn($retailer_id, $date_1) {
    $tbl_fields = "sum(amount) as amount";
    $table_name = "transaction_details";
    $where = "retailer_id='$retailer_id' AND STATUS='1' AND DATE(transaction_date) = '$date_1' and mode='2'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result->amount;
}

function getApprovedExpensesByDate($retailer_id, $date) {
    $tbl_fields = "expense_title,slip,amount,remarks,store_remarks,retailer_id";
    $table_name = "expense_details";
    $where = "retailer_id='$retailer_id' AND STATUS='1' AND DATE(transaction_date)='$date'";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getApprovedExpensesByDateCount($retailer_id) {
    $tbl_fields = "sum(amount) as amount";
    $table_name = "expense_details";
    $where = "retailer_id='$retailer_id' AND STATUS='1'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result->amount;
}

function getApprovedExpensesByDateCountAsOn($retailer_id, $date_1, $date_2) {
    $tbl_fields = "sum(amount) as amount";
    $table_name = "expense_details";
    $where = "retailer_id='$retailer_id' AND STATUS='1' AND DATE(transaction_date) between '$date_1' and '$date_2'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result->amount;
}

function getApprovedDayWiseExpensesByDateCountAsOn($retailer_id, $date_1) {
    $tbl_fields = "sum(amount) as amount";
    $table_name = "expense_details";
    $where = "retailer_id='$retailer_id' AND STATUS='1' AND DATE(transaction_date) = '$date_1'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result->amount;
}

function getTransferByRetailerApprovedTransactionbyDade($retailer_id, $date) {
    $tbl_fields = "transaction_no,slip,amount,remarks,bank_id,retailer_id";
    $table_name = "transaction_details";
    $where = "bank_id='$retailer_id' AND STATUS='1' AND DATE(transaction_date)='$date' and mode='2'";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getTransferByRetailerApprovedTransactionbyDadeCount($retailer_id) {
    $tbl_fields = "sum(amount) as amount";
    $table_name = "transaction_details";
    $where = "bank_id='$retailer_id' AND STATUS='1' and mode='2'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    if (isset($result->amount)) {
        return $result->amount;
    } else {
        return 0;
    }
}

function getTransferByRetailerApprovedTransactionbyDadeCountAsOn($retailer_id, $date_1, $date_2) {
    $tbl_fields = "sum(amount) as amount";
    $table_name = "transaction_details";
    $where = "bank_id='$retailer_id' AND STATUS='1' and mode='2' AND DATE(transaction_date) between '$date_1' and '$date_2'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    if (isset($result->amount)) {
        return $result->amount;
    } else {
        return 0;
    }
}

function getTransferDayWiseByRetailerApprovedTransactionbyDadeCountAsOn($retailer_id, $date_1) {
    $tbl_fields = "sum(amount) as amount";
    $table_name = "transaction_details";
    $where = "bank_id='$retailer_id' AND STATUS='1' and mode='2' AND DATE(transaction_date) = '$date_1'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    if (isset($result->amount)) {
        return $result->amount;
    } else {
        return 0;
    }
}

function getApproveTransactionRequestSelection($retailer_id, $date_1, $date_2, $selection, $company_id_in) {
    $retailerQuery = "";
    if ($retailer_id != 0) {
        $retailerQuery = " AND retailer_id='$retailer_id'";
    }
    $tbl_fields = "*";
    $table_name = "transaction_details";
    $where = "company_id in ($company_id_in) and status='$selection' and date(datetime) between '$date_1' and '$date_2' $retailerQuery";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getApproveExpenseRequestSelection($retailer_id, $date_1, $date_2, $selection, $company_id_in) {
    $retailerQuery = "";
    if ($retailer_id != 0) {
        $retailerQuery = " AND retailer_id='$retailer_id'";
    }
    $tbl_fields = "*";
    $table_name = "expense_details";
    $where = "company_id in ($company_id_in) and status='$selection' and date(datetime) between '$date_1' and '$date_2' $retailerQuery";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getApproveExpenseRequestSelectionManagement($date_1, $company_id_in) {
    $tbl_fields = "*";
    $table_name = "expense_details";
    $where = "company_id in ($company_id_in) and date(datetime) between '$date_1' and '$date_1'";
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

function getApprovedStockRequestFilterByDate($from_retailer, $to_retailer, $status, $company_id_in, $date_1, $date_2) {
    $where = "date(add_date) BETWEEN '$date_1' and '$date_2' and company_id in ($company_id_in) and id IS NOT NULL";
    $tbl_fields = "*";
    $table_name = "retailer_stock_transfer";
    if (!empty($from_retailer)) {
        $where .= " AND frm_retailer_id='$from_retailer'";
    }
    if (!empty($to_retailer)) {
        $where .= " AND retailer_id='$to_retailer'";
    }
    if (!empty($status)) {
        if ($status == 8) {
            $where .= " AND ctrl_off_flag='0'";
        } else {
            $where .= " AND ctrl_off_flag='$status'";
        }
    }
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getApprovedStockRequestFilter($from_retailer, $to_retailer, $status, $company_id_in) {
    $where = "company_id in ($company_id_in) and id IS NOT NULL";
    $tbl_fields = "*";
    $table_name = "retailer_stock_transfer";
    if (!empty($from_retailer)) {
        $where .= " AND frm_retailer_id='$from_retailer'";
    }
    if (!empty($to_retailer)) {
        $where .= " AND retailer_id='$to_retailer'";
    }
    if (!empty($status)) {
        if ($status == 8) {
            $where .= " AND ctrl_off_flag='0'";
        } else {
            $where .= " AND ctrl_off_flag='$status'";
        }
    }
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function get_total_price_by_frm_retailer_id_and_itemcode($retailerid, $itemcode) {
    $tbl_fields = "total";
    $table_name = "retailer_inventory_master";
    $where = "retailer_id = '$retailerid' and item_code='$itemcode' ";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '1');
    if ($result) {
        return $result->total;
    } else {
        return 0;
    }
}

function getLastpurchaseOrder($fin_year_latest, $user_id) {
    $tbl_fields = "inc_no";
    $table_name = "purchase_order";
    $where = "financial_yr='$fin_year_latest' and user_id='$user_id'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'inc_no', $asc = 0, $desc = 1, $limit = '1');
    if ($result) {
        return $result->inc_no;
    } else {
        return 0;
    }
}

function getLastpurchaseOrderBasic($fin_year_latest, $user_id) {
    $tbl_fields = "max(inc_no) as inc_no";
    $table_name = "purchase_order_basic";
    $where = "financial_yr='$fin_year_latest' and user_id='$user_id'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'inc_no', $asc = 0, $desc = 1, $limit = '1');
    if ($result) {
        return $result->inc_no;
    } else {
        return 0;
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
    if ($table_name == 'bdm_master') {
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
