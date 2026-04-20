 <?php

define("SECRETKEY", "mysecretkeyhsrp");
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

// $zonalData = getZomDetailsByUsername($_SESSION['email']);
/* ----------------------- PRODUCT CODE ----------------- */

function removeSpecialCharacters($string) {
    return preg_replace('/[^A-Za-z0-9\/\\-\(\)]/', ' ', $string);
}

function numberDecimal($number) {
    return number_format((float) $number, 2, '.', '');
}

function get_bdm_sale_data_by_whr($where){
	$tbl_fields = "*";
    $table_name = "bdm_sale_plan_tbl";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');

}

function getActiveItemsList() {
    $tbl_fields = "*";
    $table_name = "inventory_master";
    $where = "status='1'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'id', $asc = 0, $desc = 1, $limit = '');
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

function getActiveRetailerDetails($company_id_in) {
    $tbl_fields = "*";
    $table_name = "retailer_master";
    $where = "status='1' and company_id in ($company_id_in)";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'name,company_id,state_id', $asc = 1, $desc = '', $limit = '');
}

function getRetailerByBdmIdd($bdm_id) {
    $tbl_fields = "*";
    $table_name = "`retailer_master`";
    $where = "bdm_id='$bdm_id' and status = '1'";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result;
}

function getRetailerPoItemListByretailerIdForReport($retailer_id, $formdate, $todate, $retailer_string) {
    $tbl_fields = "*";
    $table_name = "`retailer_po_generate_item_tbl`";
    if ($retailer_id == 0) {
        $where = "date(added_time) between '$formdate' and '$todate' and retailer_id in ($retailer_string)";
    } else {
        $where = "retailer_id='$retailer_id' and date(added_time) between '$formdate' and '$todate' and bdm_id='" . $_SESSION['id'] . "'";
    }
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result;
}

function getRetailerPoItemListByretailerIdForReport_ttm($retailer_id, $formdate, $todate, $retailer_string) {
    $tbl_fields = "*";
    $table_name = "`retailer_po_generate_item_tbl`";
    if ($retailer_id == 0) {
        $where = "date(added_time) between '$formdate' and '$todate' and retailer_id in ($retailer_string)";
    } else {
        $where = "retailer_id='$retailer_id' and date(added_time) between '$formdate' and '$todate'";
    }
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result;
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

function getPORequestedRetailerByBDMID($bdmid) {
    $tbl_fields = "*";
    $table_name = "`retailer_po_generate_item_tbl`";
    $where = "retailer_id in ($bdmid) and status = '1'";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = 'retailer_id', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result;
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

function getRetailerReturnPO($item_code, $retailer_id) {
    $tbl_fields = "SUM(qty) AS qty";
    $table_name = "purchase_order_return_detail";
    $where = "retailer_id='$retailer_id' AND item_id='$item_code' AND STATUS='1'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = 'item_id', $order_by = '', $asc = '', $desc = '', $limit = '');
    if (isset($result->qty)) {
        return $result->qty;
    } else {
        return 0;
    }
}

function getRetailerPoOrderItemListByBDMRetailerId($bdm_id, $Retailer_id) {
    $tbl_fields = "*";
    $table_name = "`retailer_po_generate_item_tbl`";
    $where = "bdm_id='$bdm_id' and retailer_id = '$Retailer_id' and status = '1'";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result;
}

function getRetailerStringPoOrderItemListByBDMRetailerId($where) {
    $tbl_fields = "*";
    $table_name = "`retailer_po_generate_item_tbl`";
    $where = $where;
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'item_desc', $asc = 1, $desc = '', $limit = '');
    return $result;
}

function getRetailerNameById($retailer_id) {
    $tbl_fields = "full_name";
    $table_name = "retailer_master";
    $where = "id='$retailer_id'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result->full_name;
}

function getRetailerItemByRetailerId($retailer_id) {
    $tbl_fields = "*";
    $table_name = "retailer_inventory_master";
    $where = "status='1' and retailer_id='$retailer_id'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
}

function getBDMRetailerItemByRetailerId($retailer_id) {
    $tbl_fields = "*";
    $table_name = "retailer_inventory_master";
    $where = "status='1' and retailer_id='$retailer_id'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
}

function getActiveRetailerByBdmStateId($id) {
    $tbl_fields = "*";
    $table_name = "retailer_master";
    $where = " state_id = '$id'";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result;
}

function getActiveRetailerByBdmId($id) {
    $tbl_fields = "*";
    $table_name = "retailer_master";
    $where = "id in($id)";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result;
}

function getRetailerByBdmId($state_id) {
    $tbl_fields = "*";
    $table_name = "retailer_master";
    $where = " state_id = '$state_id'";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result;
}

function getRetailerDataById($retailer_id) {
    $tbl_fields = "*";
    $table_name = "retailer_master";
    $where = " id = '$retailer_id'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result;
}

function getBookSaleByBdmId($date_1, $date_2, $id) {
    $tbl_fields = "*";
    $table_name = "retailer_order_master";
    $where = "date(added_date) between '$date_1' and '$date_2' and retailer_id in($id)";
    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'id', $asc = '', $desc = '1', $limit = '');
    return $result;
}

function getTempTableDetailsByRetailerIdAndPoNo($retail_id, $po_no, $retailer_id) {
    $tbl_fields = "count(*) as count, sum(qty) as qty,inc_no";
    $table_name = "retailer_order_temporary";
    $where = " bdm_id = '$retail_id' and po_no = '$po_no' and retailer_id = '$retailer_id' and  order_status=0";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result;
}

function getTotalPriceFrmTempTbl($bdm_id, $retail_id) {
    $tbl_fields = "sum(price) as total_price";
    $table_name = "retailer_order_temporary";
    $where = " retailer_id = '$retail_id' and bdm_id = '$bdm_id' and order_status = '0'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result->total_price;
}

function getDuplicateOrderCount($fin_year, $retail_id, $item_code, $bdm_id) {
    $tbl_fields = "count(bdm_id) as ccount";
    $table_name = "retailer_order_temporary";
    $where = "fin_year = '$fin_year' and retailer_id = '$retail_id' and item_code = '$item_code' and order_status = '0' and bdm_id = '$bdm_id'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result->ccount;
}

function getTempItemList($bdm_id, $retailer_id) {
    $tbl_fields = "*";
    $table_name = "retailer_order_temporary";
    $where = "bdm_id = '$bdm_id' and retailer_id = '$retailer_id' and order_status = '0'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
}

function getLastIncNo($fin_year, $id) {
    $tbl_fields = "inc_no";
    $table_name = "retailer_order_master";
    $where = "fin_year = '$fin_year' and retailer_id = '$id'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'id', $asc = '', $desc = '1', $limit = '1');
    return $result->inc_no;
}

function getComanyNameById($id) {
    $tbl_fields = "name";
    $table_name = "company_master";
    $where = "id = '$id'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'id', $asc = '', $desc = '1', $limit = '1');
    return $result->name;
}

function getItemParentCategoryIdItemcode($item_code) {
    $tbl_fields = "main_category_id";
    $table_name = "inventory_master";
    $where = "item_code='$item_code'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = '', $limit = '');
    return $result->main_category_id;
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

function getItemDetailByCode($item_code, $retailid) {
    $tbl_fields = "total,uom,current_stock";
    $table_name = "retailer_inventory_master";
    $where = "item_code = '$item_code' and retailer_id = '$retailid'";
    return $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
}

function getInventoryItem($id) {
    $tbl_fields = "*";
    $table_name = "retailer_inventory_master";
    $where = "retailer_id in ($id)";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
}

function getInventoryDataByCode($item_code) {
    $tbl_fields = "*";
    $table_name = "inventory_master";
    $where = "item_code = '$item_code'";
    return $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
}

function getStockCountByItemCodeAndRetailerId($retailer_id, $item_code) {
    $tbl_fields = "*";
    $table_name = "retailer_inventory_master";
    $where = "retailer_id in ($retailer_id) and item_code = '$item_code'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
    return $result;
}

function getInventoryGrnDetailsById($status, $id) {
    $tbl_fields = "*";
    $table_name = "inventory_grn";
    $where = "retailer_id in ($id) and retailer_inwd_flg = '$status'";
    return $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
}

function getGrnDataByGrnid($grnid) {
    $tbl_fields = "*";
    $table_name = "inventory_grn";
    $where = "id = '$grnid' and retailer_inwd_flg = '0'";
    return $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '', $desc = '', $limit = '');
}

function getAllAssignMenuByZomId($zonel_id) {
    $tbl_fields = "menu";
    $table_name = "bdm_master";
    $where = "id='$zonel_id'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '0', $desc = '0', $limit = '');
    return $result->menu;
}

function getAllAssignRetailerIdByZomId($zonel_id) {
    $tbl_fields = "retailer_id";
    $table_name = "bdm_master";
    $where = "id='$zonel_id'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '0', $desc = '0', $limit = '');
    return $result->retailer_id;
}

function getBdmDetailById($id) {
    $tbl_fields = "*";
    $table_name = "bdm_master";
    $where = "id='$id'";
    $result = mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = '0', $desc = '0', $limit = '');
    return $result;
}

function checkMyLogin($where) {
    $tbl_fields = "*";
    $table_name = "bdm_master";
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
    $tbl_fields = "bdm_flg";
    $table_name = "master_menu";
    $where = 'id="' . $menuId . '" AND status="1"';
    $result = mysql_select($tbl_fields, $table_name, $where);
    return $result->bdm_flg;
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
    $where = 'bdm_flg="' . $menuId . '" AND status="1"';

    $result = mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getMenuheader() {
    $tbl_fields = "*";
    $table_name = "master_menu";
    $where = 'bdm_flg="0" AND status="1"';
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
    if ($table_name == 'daily_lid_ec_wise_embossed') {
        //    echo $sql;
        //    exit;
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
        // echo $query;
        // exit;
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
    if ($table_name == 'requisition_slip') {
        // echo $query;
        // exit;
    }
    if (mysqli_query($conn, $query)) {
        return true;
    } else {
        return false;
    }
}
