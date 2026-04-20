 
<?php

error_reporting(0);
require_once 'config.php';
date_default_timezone_set('Asia/Kolkata');
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
$company_id = "";
$b2b_prefix = "";
$batch_wise_sale = "";
if (isset($_SESSION['id'])) {
    $retailer_detail = getRetailerDataById($_SESSION['id']);
    $company_id = $retailer_detail->company_id;
    $b2b_prefix = $retailer_detail->inv_series;
    $batch_wise_sale = $retailer_detail->batch_wise_sale;
    $company_detail = getCompanyDetailsById($company_id);
}

function getDaybookCasInhandEntry($retailer_id, $date) {
    $tbl_fields = "*";
    $table_name = "day_book_entry";
    $where = "retailer_id='$retailer_id' AND STATUS=1 AND DATE(DATE)='" . $date . "'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function dateMinus($date, $days) {
    return date('Y-m-d', strtotime('-' . $days . ' day', strtotime($date)));
}

function datePlus($date, $days) {
    return date('Y-m-d', strtotime('+' . $days . ' day', strtotime($date)));
}

// return po functions========================================
function FiveDigit($num) {
    return str_pad($num, 10, "0", STR_PAD_LEFT);
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

    return $result;
}

function amount($number) {
    return number_format((float) $number, 2, '.', '');
}

function getFinancialYear($date_input = null) {
    // Create a DateTime object from the input.
    // If input is null, it defaults to the current date and time.
    try {
        if ($date_input instanceof DateTime) {
            $date = $date_input;
        } elseif (is_string($date_input)) {
            $date = new DateTime($date_input);
        } else {
            $date = new DateTime(); // Use current date if no input or invalid input
        }
    } catch (Exception $e) {
        // Handle invalid date strings gracefully
        echo "Error: Invalid date input. Using current date instead. " . $e->getMessage() . "\n";
        $date = new DateTime();
    }

    $current_year = (int) $date->format('Y');
    $current_month = (int) $date->format('m');

    // Determine the start year of the financial year
    // If the month is January (1), February (2), or March (3),
    // the financial year started in the previous calendar year.
    if ($current_month < 4) { // Months 1, 2, 3 (Jan, Feb, Mar)
        $fin_year_start = $current_year - 1;
    } else { // Months 4-12 (Apr - Dec)
        $fin_year_start = $current_year;
    }

    // Calculate the end year of the financial year (last two digits)
    $fin_year_end_full = $fin_year_start + 1;
    $fin_year_end_short = substr($fin_year_end_full, -2); // Get last two digits
    $fin_year_start_short = substr($fin_year_start, -2); // Get last two digits
    // Format the financial year as "YYYY-YY"
    return "{$fin_year_start_short}-{$fin_year_end_short}";
}

function get_temp_item_list_of_physical_audit_table($retailer_id) {
    $tbl_fields = "*";
    $table_name = "physical_audit_report_tbl";
    $where = "retailer_id='$retailer_id' and status = '0'";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function get_data_of_physical_audit_table($fromdate, $todate, $retailerid) {
    $tbl_fields = "*";
    $table_name = "physical_audit_report_tbl";
    $where = "date(confirm_date) between '$fromdate' and '$todate' and retailer_id='$retailerid' and status = '1'";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getVendor($vendor_id) {
    $tbl_fields = "*";
    $table_name = "vendor_master";
    $where = "vendor_id='$vendor_id'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getItemDetails($itemId) {

    $tbl_fields = "*";
    $table_name = "inventory_master";
    $where = "item_code='$itemId'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function purchaseOrderByidCountForBasic($orderId) {

    $tbl_fields = "*";
    $table_name = "purchase_order_basic";
    $where = "po_no='$orderId'";
    $result = num_rows($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function purchaseOrderDetailsForBasic($orderId) {

    $tbl_fields = "*";
    $table_name = "purchase_order_basic_detail";
    //   $where = "id='$orderId' AND `status` ='1' ";
    $where = "id='$orderId' ";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getVendorGstinNoById($vendor_id) {
    $tbl_fields = "gstin_no";
    $table_name = "vendor_master";
    $where = "vendor_id='$vendor_id'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'id', $asc = 0, $desc = 1, $limit = 1);
    if (isset($result->gstin_no)) {
        return $result->gstin_no;
    } else {
        return "NA";
    }
}

function getCompanyDetailById($company_id) {
    $tbl_fields = "*";
    $table_name = "company_master";
    $where = "id='$company_id'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getPurchaseOrderListByStatusForBasic($company_id) {
    $tbl_fields = "*";
    $table_name = "purchase_order_basic";
    $where = " retailer_id = '$company_id'";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getPurchaseOrderListByStatusForBasicDetails($company_id) {
    $tbl_fields = "p.upload_invoice_no,p.invoice_remarks,p.invoice_upload_date,p.invoice_upload,p.invoice_flag,p.status as status,p.po_no as po_no,p.po_date as po_date,p.supplier_id as supplier_id,p.status_remarks as status_remarks,pd.item_id as item,pd.qty as qty,pd.retailer_string as retailer_string";
    $table_name = "`purchase_order_basic` p,`purchase_order_basic_detail` pd";
    $where = "pd.`retailer_string` like '%" . $company_id . "%' AND p.`id`=pd.`id`";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function purchaseOrderByidForBasic($orderId) {

    $tbl_fields = "*";
    $table_name = "purchase_order_basic";
    $where = "po_no='$orderId'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getReturnPurchaseOrderDetailsByretailerId($retailer_id, $user_id) {
    $tbl_fields = "*";
    $table_name = "purchase_order_return_detail";
    $where = "retailer_id = '$retailer_id' and user_id = '$user_id' and status = '0' and retailer_id != '0'";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result;
}

//-------------cash-in-hand start

function getBankDepositApprovedTransactionbyDadeCountAsOn($retailer_id, $date_1) {
    $tbl_fields = "sum(amount) as amount";
    $table_name = "transaction_details";
    $where = "retailer_id='$retailer_id' AND STATUS='1' AND DATE(transaction_date) = '$date_1' and bank_id not in ('145') and mode='1'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result->amount;
}

function getRetailerSalesByDateCountAsOnJoinDate($retailer_id, $date_1, $date_2) {
    $tbl_fields = "SUM(t.price) as total_price";
    $table_name = "retailer_order_master m,`retailer_order_temporary` t";
    $where = "m.po_no=t.po_no AND m.retailer_id = '$retailer_id' AND m.status NOT IN ('7','8') and date(t.order_place_date) between '$date_1' and '$date_2'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result->total_price;
}

function getRetailerSalesDiscountByDateCountAsOnJoinDate($retailer_id, $date_1, $date_2) {
    $tbl_fields = "sum(m.discount_amount) as discount_amountTotal";
    $table_name = "retailer_order_master m,`retailer_order_temporary` t";
    $where = "m.po_no=t.po_no AND m.retailer_id = '$retailer_id' AND m.status NOT IN ('7','8') and date(t.order_place_date) between '$date_1' and '$date_2'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result->discount_amountTotal;
}

function getRetailerSalesDiscountDetailsByDate($retailer_id, $date) {
    $tbl_fields = "po_no,order_no,discount_amount";
    $table_name = "retailer_order_master";
    $where = "STATUS NOT IN ('7','8') AND retailer_id='$retailer_id' AND DATE(added_date)='$date' and discount_amount>0";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = 'po_no', $order_by = '', $asc = 0, $desc = '', $limit = '');
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

function getRetailerSalesByDateCountAsOnJoin($retailer_id, $date_1) {
    $tbl_fields = "SUM(t.price) as total_price,t.po_no as order_no";
    $table_name = "retailer_order_master m,`retailer_order_temporary` t";
    $where = "m.po_no=t.po_no AND m.retailer_id = '$retailer_id' AND m.status NOT IN ('7','8') and date(t.order_place_date) between '$date_1' and '$date_1'";
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

function getApprovedDayWiseExpensesByDateCountAsOn($retailer_id, $date_1) {
    $tbl_fields = "sum(amount) as amount";
    $table_name = "expense_details";
    $where = "retailer_id='$retailer_id' AND STATUS='1' AND DATE(transaction_date) = '$date_1'";
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

function getUPIDepositApprovedTransactionbyDadeCountAsOn($retailer_id, $date_1) {
    $tbl_fields = "sum(amount) as amount";
    $table_name = "transaction_details";
    $where = "retailer_id='$retailer_id' AND STATUS='1' AND DATE(transaction_date) = '$date_1' and bank_id in ('145') and mode='1'";
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

function getApprovedTransactionbyDadeCountAsOn($retailer_id, $date_1, $date_2) {
    $tbl_fields = "sum(amount) as amount";
    $table_name = "transaction_details";
    $where = "retailer_id='$retailer_id' AND STATUS='1' AND DATE(transaction_date) between '$date_1' and '$date_2'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result->amount;
}

function getRetailerSalesByDateCountAsOn($retailer_id, $date_1, $date_2) {
    $tbl_fields = "sum(total_price) as total_price";
    $table_name = "retailer_order_master";
    $where = "STATUS NOT IN ('7','8') AND retailer_id='$retailer_id' and date(added_date) between '$date_1' and '$date_2'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result->total_price;
}

function getTransferByRetailerApprovedTransactionbyDade($retailer_id, $date) {
    $tbl_fields = "transaction_no,slip,amount,remarks,bank_id";
    $table_name = "transaction_details";
    $where = "bank_id='$retailer_id' AND STATUS='1' AND DATE(transaction_date)='$date' and mode='2'";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getApprovedExpensesByDate($retailer_id, $date) {
    $tbl_fields = "expense_title,slip,amount,remarks";
    $table_name = "expense_details";
    $where = "retailer_id='$retailer_id' AND STATUS='1' AND DATE(transaction_date)='$date'";
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

function getRetailerSalesDetailsByDate($retailer_id, $date) {
    $tbl_fields = "po_no as order_no,SUM(price) as total_price";
    $table_name = "retailer_order_temporary";
    $where = "STATUS NOT IN ('7','8') AND retailer_id='$retailer_id' AND DATE(order_place_date)='$date'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = 'po_no', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getRetailerSalesByDateCountAsOnJoinBetween($retailer_id, $date_1) {
    $tbl_fields = "t.po_no as order_no,SUM(t.price) as total_price";
    $table_name = "retailer_order_master m,`retailer_order_temporary` t";
    $where = "m.po_no=t.po_no AND m.retailer_id = '$retailer_id' AND m.status NOT IN ('7','8') and date(added_date) between '$date_1' and '$date_1'";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getRetailerSalesByDate($retailer_id, $date) {
    $tbl_fields = "order_no,total_price";
    $table_name = "retailer_order_master";
    $where = "STATUS NOT IN ('7','8') AND retailer_id='$retailer_id' AND DATE(added_date)='$date'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'id', $asc = 1, $desc = '', $limit = '');
}

function getPatriallyRejectedOrder($retailer_id, $date1, $date2) {
    $tbl_fields = "*";
    $table_name = "partially_reject_order";
    $where = "retailer_id='$retailer_id' AND DATE(datetime) between '$date1' and '$date2' and rejected_qty > 0";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'id', $asc = 1, $desc = '', $limit = '');
}

function getAdminproductNotifications($retailer_id) {
    $tbl_fields = "description,image";
    $table_name = "notifications";
    $where = "retailer_id='$retailer_id' and status='1'";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result;
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

//-------------cash-in-hand end
// return po functions========================================
// $zonalData = getZomDetailsByUsername($_SESSION['email']);
/* ----------------------- PRODUCT CODE ----------------- */
function getCustomerIncNoById($cus_id) {
    $tbl_fields = "inccode";
    $table_name = "customer_details_tbl";
    $where = "retailer_id = '" . $cus_id . "'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'inccode', $asc = '0', $desc = 1, $limit = 1);
    return $result->inccode;
}

function getRetailerInvoiceCodeIdById($retailer_id) {
    $tbl_fields = "inv_series";
    $table_name = "retailer_master";
    $where = "id='$retailer_id'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result->inv_series;
}

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
    $tbl_fields = "batch_no,expire_date,sum(qty) AS cf";
    $table_name = "`item_sr_master`";
    $where = "item_code='$item_code' AND retailer_id='$retailer_id' AND STATUS='0' AND date(expire_date) > '" . date("Y-m-d") . "'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = 'expire_date,batch_no', $order_by = '', $asc = 1, $desc = '', $limit = '');
}

function getFreeSerielNoByRetailerItemUA($item_code, $retailer_id) {
    $tbl_fields = "batch_no,expire_date,sum(qty) AS cf";
    $table_name = "`item_sr_master`";
    $where = "item_code='$item_code' AND retailer_id='$retailer_id' AND STATUS='0'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = 'expire_date,batch_no', $order_by = '', $asc = 1, $desc = '', $limit = '');
}

function getBankList($comany_id) {
    $tbl_fields = "*";
    $table_name = "`bank_master_ddm`";
    $where = "archive='0' and company_id='$comany_id'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'bank_name', $asc = 1, $desc = '', $limit = '');
}

function getCustomerDetailsByRetailerId($retailerid) {
    $tbl_fields = "*";
    $table_name = "`customer_details_tbl`";
    $where = "retailer_id = '$retailerid'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
}

function getCustomerDetailsById($cusid) {
    $tbl_fields = "*";
    $table_name = "`customer_details_tbl`";
    $where = "id = '$cusid'";
    return $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
}

function getRetailerById($retailerid) {
    $tbl_fields = "*";
    $table_name = "`retailer_master`";
    $where = "id = '$retailerid'";
    return $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
}

function getRetailerDataByZoneId($zone_id) {
    $tbl_fields = "*";
    $table_name = "`retailer_master`";
    $where = "new_zone_id in('$zone_id') and status='1'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
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

function getFreeSerielNoByRetailerItemVerde($item_code, $retailer_id) {
    $tbl_fields = "batch_no,expire_date,sum(qty) AS cf";
    $table_name = "`item_sr_master`";
    $where = "item_code='$item_code' AND retailer_id='$retailer_id' AND STATUS='0'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = 'expire_date,batch_no', $order_by = '', $asc = 1, $desc = '', $limit = '');
}

function getBatchNumberFreeItems($retailer_id, $batch_no, $item_code) {
    $tbl_fields = "sum(qty) AS cf";
    $table_name = "`item_sr_master`";
    $where = "item_code='$item_code' AND status='0' and retailer_id='$retailer_id' and batch_no='$batch_no' and expire_date>'" . date("Y-m-d") . "'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result->cf;
}

function getBatchNumberFreeItemsUA($retailer_id, $batch_no, $item_code) {
    $tbl_fields = "sum(qty) AS cf,purchase_basic,gst,total";
    $table_name = "`item_sr_master`";
    $where = "item_code='$item_code' AND status='0' and retailer_id='$retailer_id' and batch_no='$batch_no'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result->cf;
}

function getBatchNumberFreeItemsDetail($retailer_id, $batch_no, $item_code) {
    $tbl_fields = "sum(qty) AS cf,purchase_basic,gst,total,expire_date";
    $table_name = "`item_sr_master`";
    $where = "item_code='$item_code' AND status='0' and retailer_id='$retailer_id' and batch_no='$batch_no'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result;
}

function getFreeSr_noBybatch($retailer_id, $batch_no, $item_code, $sale_qty_input_2) {
    $tbl_fields = "*";
    $table_name = "`item_sr_master`";
    $where = "item_code='$item_code' AND status='0' and retailer_id='$retailer_id' and batch_no='$batch_no' and qty>='$sale_qty_input_2'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'sale_qty', $asc = 0, $desc = 1, $limit = '');
    return $result;
}

function getBatchNumberFreeItemsVerde($retailer_id, $batch_no, $item_code) {
    $tbl_fields = "sum(qty) AS cf";
    $table_name = "`item_sr_master`";
    $where = "item_code='$item_code' AND status='0' and retailer_id='$retailer_id' and batch_no='$batch_no'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result->cf;
}

function getBatchNumberPurchasePriceItems($retailer_id, $batch_no, $item_code, $expire_date) {
    $tbl_fields = "purchase_basic,gst,total";
    $table_name = "`item_sr_master`";
    $where = "item_code='$item_code' and expire_date='$expire_date' and retailer_id='$retailer_id' and batch_no='$batch_no'";
    return $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getActiveFixedAssets() {
    $tbl_fields = "*";
    $table_name = "`fixed_asset`";
    $where = "status='1'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getAdminFixedAssets() {
    $tbl_fields = "*";
    $table_name = "`fixed_asset`";
    $where = "";
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

function getBatchNumberExpiryItems($retailer_id, $batch_no, $item_code) {
    $tbl_fields = "expire_date";
    $table_name = "`item_sr_master`";
    $where = "item_code='$item_code' AND status='0' and retailer_id='$retailer_id' and batch_no='$batch_no' and expire_date>'" . date("Y-m-d") . "'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result->expire_date;
}

function getBatchExpiryDateByBatchNumber($retailer_id, $batch_no, $item_code) {
    $tbl_fields = "expire_date";
    $table_name = "`item_sr_master`";
    $where = "item_code='$item_code' AND status='0' and retailer_id='$retailer_id' and batch_no='$batch_no'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result->expire_date;
}

function getBatchPoNoByBatchNumber($retailer_id, $batch_no, $item_code) {
    $tbl_fields = "po_no";
    $table_name = "`item_sr_master`";
    $where = "item_code='$item_code' AND status='0' and retailer_id='$retailer_id' and batch_no='$batch_no'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result->po_no;
}

function getVendorIdByBatchNumber($retailer_id, $batch_no, $item_code) {
    $tbl_fields = "vendor_id";
    $table_name = "`item_sr_master`";
    $where = "item_code='$item_code' AND status='0' and retailer_id='$retailer_id' and batch_no='$batch_no'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result->vendor_id;
}

function getBatchExpiryDateByBatchNo($retailer_id, $batch_no, $item_code) {
    $tbl_fields = "expire_date";
    $table_name = "`item_sr_master`";
    $where = "item_code='$item_code' AND retailer_id='$retailer_id' and batch_no='$batch_no'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result->expire_date;
}

function getBatchNumberManufacturingDate($retailer_id, $batch_no, $item_code) {
    $tbl_fields = "manufacturing_date";
    $table_name = "`item_sr_master`";
    $where = "item_code='$item_code' AND status='0' and retailer_id='$retailer_id' and batch_no='$batch_no' and expire_date>'" . date("Y-m-d") . "'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result->manufacturing_date;
}

function getBatchManuDateByBatchNo($retailer_id, $batch_no, $item_code) {
    $tbl_fields = "manufacturing_date";
    $table_name = "`item_sr_master`";
    $where = "item_code='$item_code' AND status='0' and retailer_id='$retailer_id' and batch_no='$batch_no'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result->manufacturing_date;
}

function getItemSrInwardNoByBatchNo($retailer_id, $batch_no, $item_code) {
    $tbl_fields = "po_no";
    $table_name = "`item_sr_master`";
    $where = "item_code='$item_code' AND status='0' and retailer_id='$retailer_id' and batch_no='$batch_no'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    if (isset($result->po_no)) {
        return $result->po_no;
    } else {
        return 0;
    }
}

function getFreeBatchyRetailerId($retailer_id, $item_code) {
    $tbl_fields = "batch_no,sum(qty) as count";
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

function getFreeItemsSrByitemGrp($retailer_id, $item_code) {
    $tbl_fields = "sum(qty) as count,l.*";
    $table_name = "`item_sr_master` l";
    $where = "item_code='$item_code' AND status='0' and retailer_id='$retailer_id'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = 'item_code,batch_no,expire_date', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getExpiredItemsSrByDate($retailer_id, $fromDate, $to_date) {
    $tbl_fields = "sum(qty) as count,l.*";
    $table_name = "item_sr_master l";
    $where = "status='0' and retailer_id='$retailer_id' and date(expire_date) between '$fromDate' and '$to_date'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = 'item_code,batch_no,expire_date', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getExpiredItems($retailer_id) {
    $tbl_fields = "sum(qty) as count,l.*";
    $table_name = "`item_sr_master` l";
    $where = "status='0' and retailer_id='$retailer_id'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = 'item_code,batch_no,expire_date', $order_by = '', $asc = '', $desc = '', $limit = '');
}

function getAlredyExpiredItems($retailer_id) {
    $tbl_fields = "sum(qty) as count,l.*";
    $table_name = "`item_sr_master` l";
    $where = "status='0' and retailer_id='$retailer_id' and date(expire_date) < '" . date("Y-m-d") . "'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = 'item_code,batch_no,expire_date', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getCompanyNameById($company_id) {
    $tbl_fields = "name";
    $table_name = "`company_master`";
    $where = "id='$company_id'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result->name;
}

function getCompanyUnitNameById($company_id) {
    $tbl_fields = "unit_name";
    $table_name = "`company_master`";
    $where = "id='$company_id'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result->unit_name;
}

function getVillages($retailer_id) {
    $tbl_fields = "*";
    $table_name = "`villages`";
    $where = "retailer_id='$retailer_id'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getCrops($retailer_id) {
    $tbl_fields = "*";
    $table_name = "`crops`";
    $where = "retailer_id='$retailer_id'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function checkNameCrop($crop_name) {
    $tbl_fields = "*";
    $table_name = "`crops`";
    $where = "name='$crop_name'";
    return $result = num_rows($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getAllCrops() {
    $tbl_fields = "*";
    $table_name = "`crops`";
    $where = "status='1'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getAllRetailerCrops() {
    $tbl_fields = "*";
    $table_name = "`crops`";
    $where = "";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getCouponDataByCouponNumber($coupon_no) {
    $tbl_fields = "*";
    $table_name = "tbl_discount_coupon";
    $where = "discount_code='" . $coupon_no . "' and retailer_id='" . $_SESSION['id'] . "'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getAllCouponDataByCouponNumber() {
    $tbl_fields = "*";
    $table_name = "tbl_discount_coupon";
    $where = "retailer_id='" . $_SESSION['id'] . "' and status in ('0')";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
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
    $tbl_fields = "price";
    $table_name = "`tbl_discount_coupon`";
    $where = "discount_code='$cupon' and retailer_id = '$retailer_id' and status='0' and DATE(valid_till_date)>='" . date("Y-m-d") . "'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result->price;
}

function getCuponeCodeDetail($cupon, $retailer_id) {
    $tbl_fields = "*";
    $table_name = "`tbl_discount_coupon`";
    $where = "discount_code='$cupon' and retailer_id = '$retailer_id' and status='0' and DATE(valid_till_date)>='" . date("Y-m-d") . "'";
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

function getCropDetails($crop_id) {
    $tbl_fields = "*";
    $table_name = "crops";
    $where = "id='$crop_id'";
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

function getExisitngVendorDetailById($vendor_id) {
    $tbl_fields = "vendor_name,address as vendor_address,gstin_no as vendor_gstin,pincode as vendor_pincode,c_number as vendor_mobile";
    $table_name = "`vendor_master`";
    $where = "vendor_status='1' and vendor_id='$vendor_id'";
    return $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
}

function getItemMainCatItemCode($item_code) {
    $tbl_fields = "main_category_id";
    $table_name = "inventory_master";
    $where = "item_code='$item_code'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result->main_category_id;
}

function getItemSubCatItemCode($item_code) {
    $tbl_fields = "sub_category_id";
    $table_name = "inventory_master";
    $where = "item_code='$item_code'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result->sub_category_id;
}

function getItemUOMByItemCode($item_code) {
    $tbl_fields = "uom";
    $table_name = "inventory_master";
    $where = "item_code='$item_code'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result->uom;
}

function getItemUnitByItemCode($item_code) {
    $tbl_fields = "unit";
    $table_name = "inventory_master";
    $where = "item_code='$item_code'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result->unit;
}

function getItemUnitByItemCodeObject($item_code) {
    $tbl_fields = "unit";
    $table_name = "inventory_master";
    $where = "item_code='$item_code'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getItemHsnCodeByItemCode($item_code) {
    $tbl_fields = "hsn_code";
    $table_name = "inventory_master";
    $where = "item_code='$item_code'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result->hsn_code;
}

function getItemNameByItemCode($code) {
    $tbl_fields = "item_desc";
    $table_name = "inventory_master";
    $where = "item_code='$code'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result->item_desc;
}

function getItemNameByItemId($code) {
    $tbl_fields = "item_desc";
    $table_name = "inventory_master";
    $where = "id='$code'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result->item_desc;
}

function getItemCodeByItemId($id) {
    $tbl_fields = "item_code";
    $table_name = "inventory_master";
    $where = "id='$id'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result->item_code;
}

function getItemIdByItemCode($code) {
    $tbl_fields = "id";
    $table_name = "inventory_master";
    $where = "item_code='$code'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result->id;
}

function getCompanypPrefixById($company_id) {
    $tbl_fields = "prefix";
    $table_name = "`company_master`";
    $where = "id='$company_id'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result->prefix;
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

function get_history_for_inventory_master($retailer_id) {
    $tbl_fields = "*";
    $table_name = "history_for_inventory_master";
    $where = "retailer_id='$retailer_id'";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'id', $asc = 0, $desc = 1, $limit = '');
    return $result;
}

function getLastpurchaseOrderIncNo($fin_year_latest, $user_id) {
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
    if (isset($result->current_stock)) {
        return $result->current_stock;
    } else {
        return 0;
    }
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

function getRetailerActiveItemsList($retailer_id) {
    $tbl_fields = "*";
    $table_name = "retailer_inventory_master";
    $where = "status='1' and retailer_id='$retailer_id'";
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

function getBackendRetailerStockTInward($retailer_id, $item_code, $previous_date) {
    $tbl_fields = "SUM(current_stock) AS qty";
    $table_name = "item_inward_backend";
    //    $where = "retailer_id='$retailer_id' AND item_code='$item_code' AND DATE(update_datetime)>='2023-07-01'";
    $where = "retailer_id='$retailer_id' AND item_code='$item_code' and status='1'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result->qty;
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

function getItemBillNoByBatchNoandExpireDate($item_code, $batch_number, $expire_date) {
    $tbl_fields = "bill_no";
    $table_name = "inventory_grn";
    $where = "item_desc='$item_code' AND expire_date='$expire_date' AND batch_number='$batch_number'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    if (isset($result->bill_no)) {
        return $result->bill_no;
    } else {
        return '';
    }
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

function get_pending_borrowed_transaction($id, $retailer_id) {
    $tbl_fields = "*";
    $table_name = "pending_borrowed_transaction";
    $where = " order_no = '$id' and retailer_id='$retailer_id'";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result;
}

function getBookSaleOrderByOrderId($id) {
    $tbl_fields = "retailer_id,total_price,added_datetime,pending_amount,credit_amount,cus_add,cus_ph,cus_name";
    $table_name = "retailer_order_master";
    $where = " po_no = '$id' and status not in ('7','8')";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result;
}

function getBookSaleOrderBetweenDateByOrderId($date_1, $date_2, $id) {
    $tbl_fields = "retailer_id,total_price,added_datetime,pending_amount,credit_amount,cus_add,cus_ph,cus_name";
    $table_name = "retailer_order_master";
    $where = "pending_amount>0 AND date(added_datetime) between '$date_1' and '$date_2' and retailer_id='$id' and status not in ('7','8')";
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
        $query = " and m.status in ('1','0')";
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

function getBookSaleOrdersByRetailerIdOnDate($order_date, $id) {
    $tbl_fields = "sum(t.price) as total,m.added_date";
    $table_name = "retailer_order_master m,`retailer_order_temporary` t";
    $where = "m.po_no=t.po_no AND m.retailer_id = '$id' and date(m.added_date) = '$order_date' and m.status in ('1','0')";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result;
}

function getBookCashSaleOrdersByRetailerIdOnDate($order_date, $id) {
    $tbl_fields = "sum(t.price) as total,m.added_date";
    $table_name = "retailer_order_master m,`retailer_order_temporary` t";
    $where = "m.payment_type='0' and m.po_no=t.po_no AND m.retailer_id = '$id' and date(m.added_date) = '$order_date' and m.status in ('1','0')";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result;
}

function getBookOnlineSaleOrdersByRetailerIdOnDate($order_date, $id) {
    $tbl_fields = "sum(t.price) as total,m.added_date";
    $table_name = "retailer_order_master m,`retailer_order_temporary` t";
    $where = "m.payment_type!='0' and m.po_no=t.po_no AND m.retailer_id = '$id' and date(m.added_date) = '$order_date' and m.status in ('1','0')";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result;
}

function getSumTotalBookSaleOrdersByRetailerIdBetweenDates($from_date, $to_date, $status, $id) {
    $query = "";
    if ($status == "1") {
        $query = " and m.status in ('1','0')";
    } else if ($status == "2") {
        $query = " and m.status='7'";
    } else {
        $query = "";
    }
    $tbl_fields = "sum(t.price) as total,sum(m.discount_amount) as discount_amountTotal,m.added_date,a.`main_category_id`";
    $table_name = "retailer_order_master m,`retailer_order_temporary` t, inventory_master a";
    $where = "m.po_no=t.po_no AND t.item_code = a.item_code AND m.retailer_id = '$id' and date(m.added_date) between '$from_date' and '$to_date' $query";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = 'DATE(m.added_date),a.`main_category_id`', $order_by = 'm.added_date', $asc = '1', $desc = '', $limit = '');
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

function getRetailrFinYearAssetEntry($retailer_id, $item, $finYear) {
    $tbl_fields = "*";
    $table_name = "retailer_fixed_asset";
    $where = " retailer_id = '$retailer_id' and status = '1' and fin_year='$finYear' and item_code='$item'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result;
}

function getRetailrFinYearAssetEntryQtyByItem($retailer_id, $item, $finYear) {
    $tbl_fields = "qty";
    $table_name = "retailer_fixed_asset";
    $where = "retailer_id = '$retailer_id' and fin_year='$finYear' and item_code='$item'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result->qty;
}

function getRetailrFinYearAssetEntryQtyByItemCount($retailer_id, $item, $finYear) {
    $tbl_fields = "qty";
    $table_name = "retailer_fixed_asset";
    $where = "retailer_id = '$retailer_id' and fin_year='$finYear' and item_code='$item'";
    $result = num_rows($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result;
}

function CheckRetailrFinYearAssetEntry($retailer_id, $finYear) {
    $tbl_fields = "*";
    $table_name = "retailer_fixed_asset";
    $where = " retailer_id = '$retailer_id' and status = '1' and fin_year='$finYear'";
    $result = num_rows($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result;
}

function getActiveFixedAssetsDetail() {
    $tbl_fields = "*";
    $table_name = "fixed_asset";
    $where = "status = '1'";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'id,category', $asc = 1, $desc = '', $limit = '');
    return $result;
}

function getActiveFixedAssetsDetailById($id) {
    $tbl_fields = "*";
    $table_name = "fixed_asset";
    $where = "status = '1' and id='$id'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result;
}

function getDuplicateOrderCount($fin_year, $retail_id, $item_code, $batchno) {
    $tbl_fields = "count(retailer_id) as ccount";
    $table_name = "retailer_order_temporary";
    $where = "fin_year = '$fin_year' and retailer_id = '$retail_id' and item_code = '$item_code' and batch_no = '$batchno' and order_status = '0'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result->ccount;
}

function getTransferPendingData($retailer_id) {
    $tbl_fields = "*";
    $table_name = "retailer_stock_transfer";
    $where = "retailer_id = '$retailer_id'  and status = '0' and ctrl_off_flag = '0'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
}

function getTransferPendingRetailerId($retailer_id) {
    $tbl_fields = "frm_retailer_id";
    $table_name = "retailer_stock_transfer";
    $where = "retailer_id = '$retailer_id'  and status = '0' and ctrl_off_flag = '0'";
    return $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
}

function getTransationSlipDetails($retailer_id, $date_1, $date_2, $selection) {
    $tbl_fields = "*";
    $table_name = "transaction_details";
    $where = "retailer_id = '$retailer_id'  and status = '$selection' and date(datetime) between '$date_1' and '$date_2'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
}

function getExpenseSlipDetails($retailer_id, $date_1, $date_2, $selection) {
    $tbl_fields = "*";
    $table_name = "expense_details";
    $where = "retailer_id = '$retailer_id'  and status = '$selection' and date(datetime) between '$date_1' and '$date_2'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
}

function getExpenseSlipDetailOnDate($retailer_id, $date_1) {
    $tbl_fields = "*";
    $table_name = "expense_details";
    $where = "retailer_id = '$retailer_id'  and status = '1' and date(datetime) = '$date_1'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
}

function getCheckDUpDataStockTransfer($retailer_id, $frem_id, $item_id) {
    $tbl_fields = "*";
    $table_name = "retailer_stock_transfer";
    $where = "retailer_id = '$retailer_id' and frm_retailer_id = '$frem_id' and item_id = '$item_id'  and status = '0' and ctrl_off_flag = '0'";
    return $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
}

function getCheckDUpDataBatchStockTransfer($retailer_id = null, $frem_id = null, $item_id = null, $batch_no = null) {
    $tbl_fields = "*";
    $table_name = "retailer_stock_transfer";
    $where = "retailer_id = '$retailer_id' and batch_no='$batch_no' and  frm_retailer_id = '$frem_id' and item_id = '$item_id'  and status = '0' and ctrl_off_flag = '0'";
    return $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
}

function getCheckUploadedSlip($retailer_id, $date, $mode, $bank_id) {
    $tbl_fields = "*";
    $table_name = "transaction_details";
    $where = "retailer_id = '$retailer_id' and mode='$mode' and date(transaction_date) = '$date' and status not in ('2') and bank_id='$bank_id'";
    return $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
}

function getApprovedUploadedSlip($retailer_id, $date) {
    $tbl_fields = "*";
    $table_name = "transaction_details";
    $where = "retailer_id = '$retailer_id' and date(transaction_date) = '$date' and status='1'";
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

function getRetailerPoItemList($retail_id) {
    $tbl_fields = "*";
    $table_name = "retailer_po_generate_item_tbl";
    $where = "retailer_id = '$retail_id' and status = '0'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
}

function getRetailerPoItemListByretailerId($retail_id, $formdate, $todate) {
    $tbl_fields = "*";
    $table_name = "retailer_po_generate_item_tbl";
    $where = "retailer_id = '$retail_id' and date(added_time) between '$formdate' and '$todate' and status = '1'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
}

function getRetailerPoItemListByretailerIdForReport($retail_id, $formdate, $todate) {
    $tbl_fields = "*";
    $table_name = "retailer_po_generate_item_tbl";
    $where = "retailer_id = '$retail_id' and date(added_time) between '$formdate' and '$todate' ";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
}

function get_rateiler_stock_tras_last_no($fin_year, $id) {
    $tbl_fields = "inc_no";
    $table_name = "retailer_stock_transfer";
    $where = "fin_year = '$fin_year' and retailer_id = '$id'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'inc_no', $asc = '', $desc = '1', $limit = '');
    return $result->inc_no;
}

function getLastIncNo($fin_year, $id) {
    $tbl_fields = "inc_no";
    $table_name = "retailer_order_master";
    $where = "fin_year = '$fin_year' and retailer_id = '$id'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'inc_no', $asc = '', $desc = '1', $limit = '');
    return $result->inc_no;
}

function get_phy_audit_tbl_last_inc_no($fin_year, $id) {
    $tbl_fields = "inc_no";
    $table_name = "physical_audit_report_tbl";
    $where = "fin_year = '$fin_year' and retailer_id = '$id'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'inc_no', $asc = '', $desc = '1', $limit = '');
    return $result->inc_no;
}

function getLastIncNob2b($fin_year, $id) {
    $tbl_fields = "inc_no";
    $table_name = "retailer_order_master";
    $where = "fin_year = '$fin_year' and retailer_id = '$id' AND b2b_flg='1'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'inc_no', $asc = '', $desc = '1', $limit = '');
    return $result->inc_no;
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
    $where = "order_no in ('" . $orderno . "') and status = '1' and ctrl_off_flag = '1'";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result;
}

function getInvReqByById($id) {
    $tbl_fields = "*";
    $table_name = "retailer_stock_transfer";
    $where = "id='$id' and status = '1' and ctrl_off_flag = '1'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
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
    $where = "item_id = '$itemid' and current_stock > 0 and company_id = '$compyid' and retailer_id!='" . $_SESSION['id'] . "'";
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

//function getInwardDataByRetailerId($id) {
//    $tbl_fields = "*";
//    $table_name = "retailer_inward_history";
//    $where = "retailer_id='$id' and deleted='0'";
//    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '0', $desc = '0', $limit = '');
//    return $result;
//}

function getInwardDataByRetailerId($retailer_id, $date_1, $date_2) {
    $tbl_fields = "*";
    $table_name = "inventory_grn";
    $where = "retailer_id='$retailer_id' and date(date_time) between '$date_1' and '$date_2'";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'id', $asc = '0', $desc = '1', $limit = '');
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

function update_logout($user_id) {
    $table_name = "retailer_master";
    $data['login_status'] = 0;
    $where = "`id` = '$user_id' AND status = '1'";
    return $result = update($table_name, $data, $where);
}

function update_sesstion($time, $user_id) {
    $table_name = "retailer_master";
    $data['login_status'] = 1;
    $data['login_time'] = $time;
    $data['login_datetime'] = date("Y-m-d H:i:s");
    $where = "`id` = '$user_id' AND status = '1'";
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

function getPurchaseOrderListByStatusData($retaile_id) {
    $tbl_fields = "*";
    $table_name = "purchase_order";
    $where = "retailer_id='$retaile_id'";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getPurchaseOrderListByStatusDataByDate($retaile_id, $date_1, $date_2) {
    $tbl_fields = "*";
    $table_name = "purchase_order";
    $where = "retailer_id='$retaile_id' AND DATE(po_date) between '$date_1' and '$date_2'";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getPurchaseOrderByPoId($po_id) {
    $tbl_fields = "*";
    $table_name = "purchase_order";
    $where = "id='$po_id'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
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
    if ($table_name == 'retailer_order_master m,`retailer_order_temporary` t') {
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
    if ($table_name == 'item_sale_master_decimal') {
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
    if ($table_name == 'retailer_stock_transfer') {
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
