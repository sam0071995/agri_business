<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of db
 *
 * @author FTA
 */
class db {
        private $conn;
        private $host;
        private $user;
        private $password;
        private $baseName;
        private $port;
        private $Debug;
     
    function __construct($params=array()) {
        $this->conn = false;
//        $this->host = 'localhost'; //hostname
//        $this->user = 'root'; //username
//        $this->password = ''; //password
        $this->host = '68.178.224.234'; //hostname
        $this->user = 'agro_business_adm'; //username
        $this->password = 'Agro#007@adm'; //password
        $this->baseName = 'agro_business'; //name of your database
        $this->port = '3306';
        $this->debug = true;
        $this->connect();
    }
     
    function __destruct() {
        $this->disconnect();
    }
        
    function connect() {
        if (!$this->conn) {
            try {
                $this->conn = new PDO('mysql:host='.$this->host.';dbname='.$this->baseName.'', $this->user, $this->password, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));  
            }
            catch (Exception $e) {
                die('Erreur : ' . $e->getMessage());
            }
            if (!$this->conn) {
                $this->status_fatal = true;
                echo 'Connection BDD failed';
                die();
            } else {
                $this->status_fatal = false;
            }
        }
        return $this->conn;
    }
     
    function disconnect() {
        if ($this->conn) {
            $this->conn = null;
        }
    }
    
    
    //============== FOR FREEZE STOCK ====================================
    //
       function getAllItemsActiveFromFreeze() {
        $tbl_fields = "*";
        $table_name = "inventory_master_freeze";
        $where = "status != '2' and current_stock != '0'";
        $result = $this->mysql_selects($tbl_fields, $table_name, $where , $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
        return $result;
    }
    
     function getSmallInvFreezeMovebleHisCountByItemCode($item_code){
        $tbl_fields = "*,sum(move_count) as movecount";
        $table_name = "inventory_master_freeze_small_history";
        $where = "item_code = '$item_code'";
        $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
        return $result;
    }
    
     function getLastMoveOptionByItemCodeFromFreezeHistory($item_code){
        $tbl_fields = "move_option";
        $table_name = "inventory_master_freeze_small_history";
        $where = "item_code = '$item_code'";
        $result = $this->mysql_select($tbl_fields, $table_name, $where , $group_by = '', $order_by = 'id', $asc = 0, $desc = 1, $limit = 1);
        return $result->move_option;
    }
    
     function getInventoryMasterDataByItemCode($item_code){
         $tbl_fields = "*";
        $table_name = "inventory_master";
        $where = "item_code = '$item_code' ";
        $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
        return $result;
    }
 
    //
    //============== FOR FREEZE STOCK ====================================
    
    
    function getDataByInvoiceNo($invoice_no){
         $tbl_fields = "*";
        $table_name = "btn_details_factory";
        $where = "btn_no = '$invoice_no' and company_id = '000020'";
        $result = $this->mysql_selects($tbl_fields, $table_name , $where , $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
        return $result;
    }

    function getCompanyGstDetails(){
        $tbl_fields = "*";
        $table_name = "gst_details";
        $where = "status = '1'";
        $result = $this->mysql_select($tbl_fields, $table_name , $where , $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
        return $result;
    }
    function getItemCodeByLidNo($from_lid){
        $tbl_fields = "*";
        $table_name = "lid_sr_no_store";
        $where = "lid_no = '$from_lid'";
        $result = $this->mysql_select($tbl_fields, $table_name , $where , $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
        return $result;
    }
    function getFrameOrderDetailsByOrderNoMaster($order_no){
        $tbl_fields = "*";
        $table_name = "ops_iteam_order_master";
        $where = "outward_status = '1' and order_no = '$order_no'";
        $result = $this->mysql_select($tbl_fields, $table_name , $where , $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
        return $result;
    }

    function getSatateNameByGstCodeFirstTwoChr($state_code){
        $tbl_fields = "name";
        $table_name = "state_master";
        $where = "LEFT(gstin_no,2) = '$state_code'";
        $result = $this->mysql_select($tbl_fields, $table_name , $where , $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
        return $result->name;
    }

    function getStateDataById($state_id){
        $tbl_fields = "*";
        $table_name = "state_master";
        $where = "id = '$state_id'";
        $result = $this->mysql_select($tbl_fields, $table_name , $where , $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
        return $result;
    }

    function getFrameCartSingleDetailsByOrderNo($order_no){
        $tbl_fields = "*";
        $table_name = "ops_iteam_order_cart";
        $where = "order_no = '$order_no'";
        $result = $this->mysql_select($tbl_fields, $table_name , $where , $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
        return $result;
    }

    function getFrameAllOrederData($date_1,$date_2){
        $tbl_fields = "*";
        $table_name = "ops_iteam_order_master";
        $where = "date(outward_date) between '$date_1' and '$date_2' and status = '4'";
        $result = $this->mysql_selects($tbl_fields, $table_name , $where , $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
        return $result;
    }

    function getMaxOutFrameNoFromMaster(){
        $tbl_fields = "MAX(out_inc_no) as frmmaxoutno";
        $table_name = "ops_iteam_order_master";
        $result = $this->mysql_select($tbl_fields, $table_name , $where = '', $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
        return $result->frmmaxoutno;
    }
    
    function getVendorGstinNoById($vendor_id) {
        $tbl_fields = "gstin_no";
        $table_name = "vendor_master";
        $where = "vendor_id='$vendor_id'";
        $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'id', $asc = 0, $desc = 1, $limit = 1);
        if (isset($result->gstin_no)) {
            return $result->gstin_no;
        } else {
            return "NA";
        }
    }
    function getOpsItemDescById($id){
         $tbl_fields = "*";
        $table_name = "ops_item_master";
        $where = "id = '$id'";
        $result = $this->mysql_select($tbl_fields, $table_name , $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
        return $result;
    }
    
    function getFrameCartDetailsByOrderNo($order_no){
        $tbl_fields = "*";
        $table_name = "ops_iteam_order_cart";
        $where = "order_no = '$order_no'";
        $result = $this->mysql_selects($tbl_fields, $table_name , $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
        return $result;
    }

    function getFrameOrderNoOutwardPending(){
        $tbl_fields = "order_no";
        $table_name = "ops_iteam_order_master";
        $where = "status = '2'";
        $result = $this->mysql_selects($tbl_fields, $table_name , $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
        return $result;
    }

     function getInventoryMasterData()
    {
        $tbl_fields='item_code,opening_stock,current_stock,total_stock,issued_stock';
        $result = $this->mysql_selects($tbl_fields, $table_name = 'inventory_master', $where='', $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
        return $result;
    }
   
    function checkMyLogin($username, $password) {   
        $tbl_fields = "*";
        $table_name = "user_master";
        $where = "`user_id`='$username' AND password='$password' AND company_id='000020'";
        $count = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
        if (count($count) > 0) {
            $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
            if (isset($result->user_id)) {
                return $result;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    function getDataofRtoDc()
    {
        $where="date(outward_date) = curdate() and  `deleted`='0'";
        $result = $this->mysql_selects($tbl_fields='*', $table_name='btn_details', $where, $group_by = 'btn_no', $order_by = '', $asc = 0, $desc = 0, $limit = '');
        return $result;
    }


    function GetRGMData($rto_id, $item, $from_date, $to_date)
    {
        $today=date("Y-m-d");
        $where="bd.`btn_no` LIKE 'MRN%' AND cm.`zone_rto`=bd.`rto_id`";
        if(!empty($rto_id))
        {
            $where .= " AND cm.`zone_rto`='$rto_id'";
        }
        if(!empty($item))
        {
            $where .= " AND bd.`article_no`='$item'";
        }
        if($from_date!=$today && $to_date!=$today)
        {
            $where .= " AND bd.`outward_date` BETWEEN '$from_date' AND '$to_date'";
        }
        
        
        $tbl_fields = "cm.`zone_rto_title`, bd.`btn_no`, bd.`outward_date`, bd.`article_no`, bd.`btn_billed_qty`,bd.dealer_id AS dealer_id";
        $table_name = "`btn_details` bd, `company_master_inv` cm";
        $order_by = "bd.`btn_no` DESC";
        $result = $this->mysql_selects($tbl_fields, $table_name, $where, $group_by='', $order_by, $asc = 0, $desc = 0, $limit = '');
        if($result){
            return $result;
        }else{
            return FALSE;
        }
    }
    function getRTObyRtoIdInv($rto_id) {
        if (empty($rto_id)) {
            $rto_id = "";
            $where = "rto_flag='0'";
        } else if ($rto_id == 'all') {
            $rto_id = "";
            $where = "rto_flag='0'";
        } else {
            $rto_id = $_POST['sess_rto_id'];
            $where = "rto_flag='0' AND zone_rto='$rto_id'";
        }
        $tbl_fields = "rto_id, zone_rto, zone_rto_title";
        $table_name = "company_master_inv";
        $result = $this->mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'zone_rto_title', $asc = 0, $desc = 0, $limit = '');
        return $result;
    }  
    function getInventoryGrnBybtnNo($btn_no) {
        $tbl_fields = "b.btn_billed_qty as btn_billed_qty,p.item_desc as item_desc,p.uom as uom, b.remark as remark";
        $table_name = "rgp_inventory b,inventory_master p";
        $where="b.btn_no='$btn_no' and b.article_no=p.item_code  ";
        $result = $this->mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'b.btn_pos_no', $asc = 1, $desc = 0, $limit = '');
        if($result){
            return $result;
        }else{
            return FALSE;
        }
    } 
    function getRgpOutwardReportData($where) {
        
        $tbl_fields="*";
        $table_name = "rgp_inventory";
        $result = $this->mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');

        if($result){
            return $result;
        }else{
            return FALSE;
        }
    } 

    function getTransporterAndLrNo($btn_btn_no)
    {
        $where ="`btn_no`='".$btn_btn_no."'";
        $tbl_fields="`trans_name`, `dl_no`";
        $table_name = "rgp_inventory_master";
        $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');

        if($result){
            return $result;
        }else{
            return FALSE;
        }

    }
    function getRgpInvMaster($btn_no) {
        $tbl_fields = "*";
        $table_name = "rgp_inventory_master";
        $where="btn_no='$btn_no'";
        $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
        if($result){
            return $result;
        }else{
            return FALSE;
        }
    }     
    function getRemarksByItemCodeAndPoNo($item_code,$po_no) {
        $tbl_fields = "remarks";
        $table_name = "company_inward_po_history";
        $where="item_code='$item_code' AND po_no='$po_no'";
        $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
        if($result){
            return $result->remarks;
        }else{
            return FALSE;
        }
    }     
    function getMaxPosRgpInventoryByBtn($btn_no) {
        $tbl_fields = "MAX(btn_pos_no+1) as max_pos";
        $table_name = "rgp_inventory";
        $where="btn_no='$btn_no'";
        $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
        return $result->max_pos;
    }   
    
    function getPlateCountsBetweenPlates($plate_size, $from_lid, $to_lid){
        $tbl_fields = "count(lid_no) as platescount";
        $table_name = "lid_sr_no_store";
        $where="item_code = '$plate_size' and lid_no between '$from_lid' and '$to_lid' and out_flg = '7'";
        $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
        return $result->platescount;
    }
    function getTestInventoryDetails(){
        $table_name = 'inventory_master';
        $tbl_fields = '*';
        $where = "`status`='2'";
        $result = $this->mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
        return $result;
    }
    
    function getInventoryOnlyPlatesDetails(){
        $table_name = 'inventory_master';
        $tbl_fields = '*';
        $where = "`status`='2'";
        $result = $this->mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
        return $result;
    }
    function getCurdateOutPlateCount($item_code){
        $table_name = 'btn_details_factory';
        $tbl_fields = 'SUM(btn_billed_qty) as oemcount';
        $where = "`article_no`='$item_code' and date(outward_date) = curdate()";
        $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
        return $result->oemcount;
    }
    function getCurdateOutPlateCountGuj($item_code){
        $table_name = 'btn_details';
        $tbl_fields = 'SUM(btn_billed_qty) as gujcount';
        $where = "`article_no`='$item_code' and date(outward_date) = curdate() AND deleted = '0'";
        $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
        return $result->gujcount;
    }
    
    function getTotalCountByCode($item_code){
         $table_name = 'inventory_master';
        $tbl_fields = 'total_stock';
        $where = "`item_code`='$item_code'";
        $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
        return $result->total_stock;
    }
    function getOpeningCountByCode($item_code){
         $table_name = 'inventory_master';
        $tbl_fields = 'opening_stock';
        $where = "`item_code`='$item_code'";
        $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
        return $result->opening_stock;
    }
    function getIssueCountByCode($item_code){
         $table_name = 'inventory_master';
        $tbl_fields = 'issued_stock';
        $where = "`item_code`='$item_code'";
        $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
        return $result->issued_stock;
    }
    function getCurrentCountByCode($item_code){
         $table_name = 'inventory_master';
        $tbl_fields = 'current_stock';
        $where = "`item_code`='$item_code'";
        $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
        return $result->current_stock;
    }
    function getCurdateIssueStoockCount($item_code){
        $table_name = 'lid_sr_no_store';
        $tbl_fields = 'count(lid_no) as lidcount';
        $where = "`item_code`='$item_code' and out_flg = '1' and date(out_date_factory) = curdate()";
        $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
        return $result->lidcount;
    }
    function getCurdateIssueStoockCountGuj($item_code){
        $table_name = 'lid_sr_no_store';
        $tbl_fields = 'count(lid_no) as lidcountguj';
        $where = "`item_code`='$item_code' and out_flg = '1' and date(out_date) = curdate()";
        $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
        return $result->lidcountguj;
    }
    function getMaxRgpInventoryByBtn($fin_year) {
        $tbl_fields = "MAX(max_btn_no) as max";
        $table_name = "rgp_inventory";
         $where="btn_no like '%$fin_year%'";
        $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
        return $result->max;
    }   
    function getInventoryByBtnDetails($btn_no) {
        $tbl_fields = "*";
        $table_name = "rgp_inventory";
        $where="btn_no = '$btn_no'";
        $result = $this->mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'btn_pos_no', $asc = 0, $desc = 1, $limit = '');
        return $result;
    }   
    function getInventoryByBtn($btn_no) {
        $tbl_fields = "*";
        $table_name = "rgp_inventory";
        $where="btn_no = '$btn_no'";
        $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'btn_pos_no', $asc = 0, $desc = 1, $limit = '');
        return $result;
    }
    function getItemListByInCode($itemCode) {
        $tbl_fields = "id, item_code, item_desc";
        $table_name = "inventory_master";
        $where="item_code in($itemCode)";
        $result = $this->mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
        return $result;
    }
    function getItemList() {
        $tbl_fields = "id, item_code, item_desc";
        $table_name = "inventory_master";
        $result = $this->mysql_selects($tbl_fields, $table_name, $where='', $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
        return $result;
    }
    function getRTOCompanyDetailByRtoId($rto_id) {
        $tbl_fields = "*";
        $table_name = "company_master";
        $where = "company_id='$rto_id'";
        $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
        return $result;
    }
    function getRTODetailByRtoId($rto_id) {
        $tbl_fields = "*";
        $table_name = "company_master_inv";
        $where = "zone_rto='$rto_id'";
        $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'zone_rto_title', $asc = 0, $desc = 0, $limit = '');
        return $result;
    }
    function getAllRto() {
        $tbl_fields = "rto_id, zone_rto, zone_rto_title";
        $table_name = "company_master_inv";
        $where = "rto_flag='0'";
        $result = $this->mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'zone_rto_title', $asc = 0, $desc = 0, $limit = '');
        return $result;
    }
     function getAllVendors() {
        $tbl_fields = "*";
        $table_name = "vendor_master";
        $result = $this->mysql_selects($tbl_fields, $table_name, $where = '', $group_by = '', $order_by = 'vendor_name', $asc = 1, $desc = '', $limit = '');
        return $result;
    }
    function getVendor($vendor_id) {
        $tbl_fields = "*";
        $table_name = "vendor_master";
        $where = "vendor_id='$vendor_id'";
        $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
        return $result;
    }
    function getSupplierdById($supplier_id) {
        $tbl_fields = "*";
        $table_name = "supplier_master";
        $where = "id='$supplier_id'";
        $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
        return $result;
    }
    
    function getRetailerById($retailer_id) {
        $tbl_fields = "*";
        $table_name = "retailer_master";
        $where = "id='$retailer_id'";
        $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
        return $result;
    }
    
    function getCompanyDetailById($company_id) {
        $tbl_fields = "*";
        $table_name = "company_master";
        $where = "id='$company_id'";
        $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
        return $result;
    }
    function getMasterDealersByRtoId($rto_id) {
        $tbl_fields = "*";
        $table_name = "dealer_masters";
        $where = "`hsrp_flag`='1' AND rto_id='$rto_id'";
        $result = $this->mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit='');
        return $result;
    }   
    function getDealersByRtoId($rto_id) {
        $tbl_fields = "*";
        $table_name = "dealer_masters";
        $where = "rto_id='$rto_id'";
        $result = $this->mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit='');
        return $result;
    }
    function getInventoryLoginFlag($username) {
        $tbl_fields = "*";
        $table_name = "user_master";
        $where = "login_flag='9'";
        $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit='');
        return $result;
    }

    function update_logout($username) {
        $table_name = "user_master";
        $data['login_flag'] = "login_flag-1";
        $data['ip_address'] = null;
        $data['refresh_time'] = null;
        $data['update_datetime'] = null;
        $where = "`user_id`='$username'";
        return $result = $this->update($table_name, $data, $where);
    }

    function update_sesstion($time, $username, $password) {
        $table_name = "user_master";
        $data['login_flag'] = 'login_flag+1';
        $data['ip_address'] = $this->getUserIpAddr();
        $data['refresh_time'] = $time;
        $data['update_datetime'] = date("Y-m-d h:i:s");
        $where = "`user_id`='$username' AND PASSWORD='$password'";
        return $result = $this->update($table_name, $data, $where);
    }

    function getUserIpAddr() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    function getMasterMenuId($menuId) {
        $tbl_fields = "MENU_ID";
        $table_name = "inventory_sub_menu";
        $where = 'id="' . $menuId . '" AND inventory_new="1"';
        $result = $this->mysql_select($tbl_fields, $table_name, $where);
        if($result){
        return $result->MENU_ID;
        }else{
            return false;
        }
    }

    function getSubMenuList($menuId) {
        $tbl_fields = "*";
        $table_name = "inventory_sub_menu";
        $where = "MENU_ID='$menuId' and inventory_new = '1'";
        $result = $this->mysql_selects($tbl_fields, $table_name, $where);
        return $result;
    }

    function getMenuheader() {
        $tbl_fields = "*";
        $table_name = "inventory_menu";
        $where = "`INVENTORY`='1'";
        $result = $this->mysql_selects($tbl_fields, $table_name, $where);
        return $result;
    }
    function getStockTransferHisData(){
        $tbl_fields = "*";
        $table_name = "inventory_master_freeze_history";
        $where = "";
        $result = $this->mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'id', $asc = 1, $desc = 0, $limit = '');
        return $result;
    }
    function getLidTransferDataByBetweenLids($frm_lid,$to_lid){
        $tbl_fields = "*";
        $table_name = "lid_sr_no_store";
        $where = "lid_no between '$frm_lid' and '$to_lid'";
        $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
        return $result;
    }
    function getStockTransferHisDataForSmall(){
        $tbl_fields = "*";
        $table_name = "inventory_master_freeze_small_history";
        $where = "";
        $result = $this->mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'id', $asc = 1, $desc = 0, $limit = '');
        return $result;
    }
    
    function getHOInventoryStock() {
        $tbl_fields = "*";
        $table_name = "inventory_master";
        $where = "active='1'";
        $result = $this->mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'item_desc', $asc = 1, $desc = 0, $limit = '');
        return $result;
    }       
    function getHOInventoryStockFreeze() {
        $tbl_fields = "*";
        $table_name = "inventory_master_freeze";
        $where = "active='1'";
        $result = $this->mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'item_desc', $asc = 1, $desc = 0, $limit = '');
        return $result;
    }       
    
    
//    ajit-----------------------------------------------------------------------------------------------------------------------------------
function amount($number) {
    return number_format((float) $number, 2, '.', '');
}

function object_to_array($object) {
    return (array) $object;
}

function array_to_object($array) {
    return (object) $array;
}

function moneyFormat($amount) {
    return money_format('%!i', $amount);
}

function decimalFormat($amount) {
    return number_format((float) $amount, 2, '.', '');
}
function getInvbentoryGrnByrefno($ref_no) {
    $tbl_fields = "*";
    $table_name = "inventory_grn";
    $where = "ref_no = '$ref_no' AND DATE(`date_time`) > '2018-04-01'";
    $result = $this-> mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    if ($result) {
        return $result;
    } else {
        return 0;
    }
}
function getInvbentoryGrnByrefnogrn($ref_no,$date) {
    $tbl_fields = "*";
    $table_name = "inventory_grn";
    $where = "ref_no = '$ref_no' AND DATE(`date_time`) = '$date'";
    $result = $this-> mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'id', $asc = 1, $desc = 0, $limit = '');
    if ($result) {
        return $result;
    } else {
        return 0;
    }
}
function getPurchaseOrderGrnDetailsByPoNoCon($po_no) {
    
    $tbl_fields = "*";
    $table_name = "inventory_grn";
    $where = "po_no = '$po_no'";
    $result = $this-> num_rows($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    if ($result) {
        return $result;
    } else {
        return 0;
    }
}
function getInvGrnDetailByGrnNo($grn_no,$date_one) {
    $tbl_fields = "*";
    $table_name = "inventory_grn";
    $where = "grn_number = '$grn_no' and date(date_time) = '$date_one'";
    $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = 'item_desc', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    if ($result) {
        return $result;
    } else {
        return 0;
    }
}
function getInvGrnByGrnNo($grn_no,$date_one) {
    $tbl_fields = "*";
    $table_name = "inventory_grn";
    $where = "grn_number = '$grn_no' and date(date_time) = '$date_one'";
    $result = $this->num_rows($tbl_fields, $table_name, $where, $group_by = 'item_desc', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    if ($result) {
        return $result;
    } else {
        return 0;
    }
}
function getPurchaseOrderGrnDetailsByPoNo($po_no) {
    $tbl_fields = "*, sum(billed_qty) as count";
    $table_name = "inventory_grn";
    $where = "po_no = '$po_no'";
    $result = $this->mysql_selects($tbl_fields, $table_name, $where, $group_by = 'item_desc', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    if ($result) {
        return $result;
    } else {
        return 0;
    }
}
function getPurchaseOrerDateBypono($po_no) {
    
    $tbl_fields = "po_date";
    $table_name = "purchase_order";
    $where = "po_no= '$po_no'";
    $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'id', $asc = 0, $desc = 1, $limit = '');
    if($result){
        return $result->po_date;
    }else{
        return "";
    }
}
function getPurchaseOrerByYearMonth($year) {
    
    $tbl_fields = "*";
    $table_name = "purchase_order";
    $where = "MONTH(po_date)>3 AND YEAR(po_date)='".$year."'";
    $result = $this->mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getAllPurchaseOrer() {
    
    $tbl_fields = "*";
    $table_name = "purchase_order";
    $result = $this->mysql_selects($tbl_fields, $table_name, $where = '', $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getItemDetailsByPoIdAndItemId($purchaseId, $item_id) {
    $tbl_fields = "qty";
    $table_name = "purchase_order_detail";
    $where = "id='$purchaseId' AND `item_id`='$item_id'";
    $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    if ($result) {
        return $result->qty;
    } else {
        return 0;
    }
}

function getPurchaseOrderDetailsIddbyPurchaseId($purchaseId) {
    
    $tbl_fields = "id";
    $table_name = "purchase_order_return_detail";
    $where = "id=$purchaseId";
    $result = $this->mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result->id;
}

function getBalancedQuantityByPoAndItemId($item_id, $purchaseId) {
    $tbl_fields = "sum(quantity) as quantity";
    $table_name = "company_inward_po_history";
    $where = "po_id='$purchaseId' AND item_code='$item_id' and status in ('0') and deleted=0";
    $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    if ($result) {
        return $result->quantity;
    } else {
        return 0;
    }
}
function getBalancedQuantityByPoId($purchaseId) {
    $tbl_fields = "sum(quantity) as quantity";
    $table_name = "company_inward_po_history";
    $where = "po_id='$purchaseId'";
    $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    if ($result) {
        return $result->quantity;
    } else {
        return 0;
    }
}

function getPurchaseOrderDetailsByPurchasId($purchase_id) {
    
    $tbl_fields = "purchase_order_return_detail.*, inventory_master.item_desc, inventory_master.uom";
    $table_name = "purchase_order_return_detail join inventory_master on inventory_master.id=purchase_order_detail.item_id and purchase_order_detail.id='$purchase_id'";
    $where = '';
    $result = $this->mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'id', $asc = 0, $desc = 1, $limit = '');
    return $result;
}

function getPurchaseOrderListByStatus($status) {
    $tbl_fields = "purchase_order.id, purchase_order.po_no, purchase_order.po_date, purchase_order.grand_total,vendor_master.vendor_name";
    $table_name = "purchase_order join vendor_master on vendor_master.vendor_id=purchase_order.vendor_id and purchase_order.status='$status'";
    $where = '';
    $result = $this->mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'purchase_order.id desc', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getVendor_inward_po_history($orderId,$item_id)
{
    $tbl_fields = "*";
    $table_name = "company_inward_po_history";
    $where="`po_id`='".$orderId."' AND `item_id`='".$item_id."'";
    $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getDealerInventoryPendingOrderByPoNumber($order_no) {
    
    global $conn_live;
    $tbl_fields = "d.*";
    $table_name = "dealer_purchase_order d, `dealer_masters` dm";
    $where = "d.approval_flag IN ('1','8') AND d.`dealer_id`=dm.`id` AND dm.`hsrp_flag`='1' AND po_no='$order_no'";
    $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'd.zone_approval_date', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getLiDsrNoStoreCount($from_lid, $to_lid, $item_code='',$upload_date='') {
    $tbl_fields = "*";
    $table_name = "lid_sr_no_store";
    $where = "`lid_no` BETWEEN '$from_lid' AND '$to_lid' and item_code='$item_code' AND DATE(upload_date)='$upload_date'";
    $result = $this-> num_rows($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getApprovedOrder() {
    
    global $conn_live;
    $tbl_fields = "d.*";
    $table_name = "dealer_purchase_order d, `dealer_masters` dm";
    $where = "d.approval_flag IN ('1','8') AND d.`dealer_id`=dm.`id` AND dm.`hsrp_flag`='1'";
    $result = $this->mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'd.zone_approval_date', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getItemCount($poId) {
    
    $tbl_fields = "*";
    $table_name = "purchase_order_detail";
    $where = "id='$poId'";
    $result = $this-> num_rows($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getSearchVendors($search) {
    
    $tbl_fields = "*";
    $table_name = "vendor_master";
    $where = "vendor_name like '%$search%' OR serial_no like '%$search%' OR address like '%$search%'";
    $result = $this->mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getVendorNameById($vendor_id) {
    
    $tbl_fields = "vendor_name";
    $table_name = "vendor_master";
    $where = "vendor_id='$vendor_id'";
    $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    if ($result) {
        return $result->vendor_name;
    } else {
        return FALSE;
    }
}

function getPoInwardHistory($po_no)
{
    $tbl_fields = "*";
    $table_name = "company_inward_po_history";
    $where = "`po_no`='".$po_no."'";
    $result = $this->mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}


function getPoDataByItemCodeAndPoNo($item,$po_no)
{
    
    $tbl_fields = "*";
    $table_name = "inventory_grn";
    $where = "`po_no`='".$po_no."' AND `item_desc`='".$item."'";
    $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getVendorReturnGrn() {
    
    $tbl_fields = "*";
    $table_name = "inventory_return_grn";
    $where = 'vandor_id!=0';
    $result = $this->mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getItemsUOMByItemCode($item_code) {
    $tbl_fields = "uom";
    $table_name = "inventory_master";
    $where = "item_code='$item_code'";
    $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getItemsUOM() {
    
    $tbl_fields = "uom";
    $table_name = "inventory_master";
    $where = "uom!=''";
    $result = $this->mysql_selects($tbl_fields, $table_name, $where, $group_by = 'uom', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}
function getPlateSize() {
    $tbl_fields = "*";
    $table_name = "inventory_master";
    $where="status='2'";
    $result = $this->mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}
function getAllItems() {
    $tbl_fields = "*";
    $table_name = "inventory_master";
    $where = "active = '1'";
    $result = $this->mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}


function getPurchaseOrderbyId($purchase_id) {
    
    $tbl_fields = "*";
    $table_name = "purchase_order_return";
    $where = "id='$purchase_id'";
    $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getLidDetailsByLidNo($lid_no) {
    
    $tbl_fields = "*";
    $table_name = "lid_sr_no_store";
    $where = "`rto_id` IS NULL AND`out_flg`='0' AND `lid_no`='$lid_no'";
    $result = $this->mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getLidDetailsByDate($date) {
    
    $tbl_fields = "*";
    $table_name = "lid_sr_no_store";
    $where = "`loc`='CRRI' AND `out_date`='$date'";
    $result = $this->mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getCRRILidDetailsByDate($fromDate,$toData) {
    
    $tbl_fields = "`lid_no`,`item_code`,`upload_date`,`out_date`";
    $table_name = "lid_sr_no_store";
    $where = "`loc`='CRRI' AND `out_date` BETWEEN '".$fromDate."' AND '".$toData."'";
    $result = $this->mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    if ($result) {
        return $result;
    } else {
        return FALSE;
    }
}

function getLastVendor() {
    
    $tbl_fields = "*";
    $table_name = "vendor_master";
    $result = $this->mysql_select($tbl_fields, $table_name, $where = '', $group_by = '', $order_by = 'vendor_id', $asc = 0, $desc = 0, $limit = '1');
    return $result;
}


function purchaseOrderByidCount($orderId) {
    
    $tbl_fields = "*";
    $table_name = "purchase_order_return";
    $where = "po_no='$orderId'";
    $result = $this->num_rows($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function purchaseOrderByid($orderId) {
    
    $tbl_fields = "*";
    $table_name = "purchase_order_return";
    $where = "po_no='$orderId'";
    $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getLastpurchaseOrderDetails() {
    $tbl_fields = "*";
    $table_name = "purchase_order_return";
    $result = $this->mysql_select($tbl_fields, $table_name, $where = '', $group_by = '', $order_by = 'id', $asc = 0, $desc = 1, $limit = '1');
    return $result;
}

function getLastpurchaseOrder() {
    
    $tbl_fields = "*";
    $table_name = "purchase_order_return";
    $result = $this->mysql_select($tbl_fields, $table_name, $where = '', $group_by = '', $order_by = 'id', $asc = 0, $desc = 1, $limit = '1');
    if ($result) {
        return $result->po_no;
    } else {
        return 0;
    }
}
function getLastpurchaseOrderId() {
    
    $tbl_fields = "*";
    $table_name = "purchase_order";
    $result = $this->mysql_select($tbl_fields, $table_name, $where = '', $group_by = '', $order_by = 'id', $asc = 0, $desc = 1, $limit = '1');
    if ($result) {
        return $result->id;
    } else {
        return 0;
    }
}

function getTapeInwardbyToRto($RTO) {
    
    $tbl_fields = "*";
    $table_name = "tape_transfer_rto";
    $where = "to_rto='$RTO' AND status='0'";
    $result = $this->mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function purchaseOrderDetails($orderId) {
   
    $tbl_fields = "*";
    $table_name = "purchase_order_return_detail";
   $where = "id='$orderId' AND `status` in ('0','1') ";
    $result = $this->mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getPoDetails($orderId)
{
    $tbl_fields = "*";
    $table_name = "purchase_order_detail";
    
    $where = "id='$orderId' ";
    $result = $this->mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getOutwardItems($tbl_fields, $table_name, $where) {
    
    $result = $this->mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getItemStatusByItemCode($item_code) {
    
    $tbl_fields = "status";
    $table_name = "inventory_master";
    $where = "item_code='$item_code'";
    $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    if ($result) {
        return $result->status;
    } else {
        return false;
    }
}

function getItemsListSmallInv() {
    
    $tbl_fields = "*";
    $table_name = "inventory_master";
    $where = "cs_flag='1'";
    $result = $this->mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'cat_flag , item_code ,item_desc', $asc = 1, $desc = 0, $limit = '');
    return $result;
}

function getItemsListProductionOutward() {
    
    $tbl_fields = "*";
    $table_name = "inventory_master";
    $where = "status in('1','3')";
    $result = $this->mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'item_desc', $asc = 1, $desc = 0, $limit = '');
    return $result;
}

function getItemsListProductionStatus1() {
    
    $tbl_fields = "*";
    $table_name = "inventory_master";
    $where = "status='1'";
    $result = $this->mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'item_desc', $asc = 1, $desc = 0, $limit = '');
    return $result;
}

function getRowGoodInventoryItems() {
    
    $tbl_fields = "item_code,item_desc";
    $table_name = "inventory_master";
    $where = "status='4' and ex_in_status!='1' and current_stock>0";
    $result = $this->mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'item_desc', $asc = 1, $desc = 0, $limit = '');
    return $result;
}

function getFinishGoodInventoryItems() {
    
    $tbl_fields = "item_code,item_desc";
    $table_name = "inventory_master";
    $where = "status='1' and current_stock>0";
    $result = $this->mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'item_desc', $asc = 1, $desc = 0, $limit = '');
    return $result;
}

function getItemsIdByItemDesc($item_desc) {
    
    $tbl_fields = "id";
    $table_name = "inventory_master";
    $where = "item_desc='$item_desc'";
    $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result->id;
}

function getItemsListProduction() {
    
    $tbl_fields = "*";
    $table_name = "inventory_master";
    $where = "status='1'";
    $result = $this->mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'item_desc', $asc = 1, $desc = 0, $limit = '');
    return $result;
}

function getItemsListIRProduction() {
    
    $tbl_fields = "*";
    $table_name = "inventory_master";
    $where = "status='2'";
    $result = $this->mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'item_desc', $asc = 1, $desc = 0, $limit = '');
    return $result;
}

function getSearchItems($itemSearch) {
    
    $tbl_fields = "*";
    $table_name = "inventory_master";
    $where = "item_code LIKE '%$itemSearch%' OR item_desc LIKE '%$itemSearch%' OR serial_no LIKE '%$itemSearch%'";
    $result = $this->mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getItemOpeningStockByItemCode($item_code, $from_month) {
    
    $tbl_fields = $from_month . "_closing_stock";
    $table_name = "inventory_master";
    $where = "item_code='$item_code'";
    $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result->$tbl_fields;
}

function getItemAprOpeningStockByItemCode($item_code, $from_month) {
    
    $tbl_fields = $from_month . "_opening_stock";
    $table_name = "inventory_master";
    $where = "item_code='$item_code'";
    $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result->$tbl_fields;
}

function getItemclosingStockByItemCode($item_code, $to_month) {
    
    $tbl_fields = $to_month . "_closing_stock";
    $table_name = "inventory_master";
    $where = "item_code='$item_code'";
    $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result->$tbl_fields;
}

function getItemProductionStockByItemCode($item_code, $to_date_first, $from_date_last) {
    
    $tbl_fields = "sum(total_qty) as itemCount";
    $table_name = "lid_upload_master";
    $where = "item_code='$item_code' AND upload_date BETWEEN '$from_date_last' AND '$to_date_first'";
    $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result->itemCount;
}

function getItemIssuedStockByItemCode($item_code, $to_date_first, $from_date_last) {
    
    $tbl_fields = "sum(billed_qty) as itemCount";
    $table_name = "inventory_grn";
    $where = "item_desc='$item_code' AND DATE(`date_time`) BETWEEN '$from_date_last' AND '$to_date_first'";
    $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result->itemCount;
}

function getItemIssuedOpeningStockByItemCode($item_code, $to_date) {
    
    $to_date = date('Y-m-d', strtotime($to_date . "-1 days"));
    $from_date = date('Y-m-01', strtotime($to_date));
    $tbl_fields = "sum(billed_qty) as itemCount";
    $table_name = "inventory_grn";
    $where = "item_desc='$item_code' AND DATE(`date_time`) BETWEEN '$from_date' AND '$to_date'";
    $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result->itemCount;
}

function getItemProductionOpeningStockByItemCode($item_code, $to_date) {
    
    $to_date = date('Y-m-d', strtotime($to_date . "-1 days"));
    $from_date = date('Y-m-01', strtotime($to_date));
    $tbl_fields = "sum(total_qty) as itemCount";
    $table_name = "lid_upload_master";
    $where = "item_code='$item_code' AND upload_date BETWEEN '$from_date' AND '$to_date'";
    $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result->itemCount;
}

function getItemDispatchedStockByItemCode($item_code, $to_date_first, $from_date_last) {
    
    $tbl_fields = "SUM(b.`btn_billed_qty`) AS COUNT";
    $table_name = "`inventory_master` i, `btn_details` b";
    $where = "b.`article_no` = i.`item_code` AND b.deleted='0' AND i.`item_code` = '$item_code' AND b.`outward_date` BETWEEN '$from_date_last'  AND '$to_date_first'";
    $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result->COUNT;
}

function getItemDispatchedOpeningStockByItemCode($item_code, $to_date) {
    
    $to_date = date('Y-m-d', strtotime($to_date . "-1 days"));
    $from_date = date('Y-m-01', strtotime($to_date));
    $tbl_fields = "SUM(`btn_billed_qty`) AS COUNT";
    $table_name = "`btn_details`";
    $where = "`item_code` = '$item_code' AND `outward_date` BETWEEN '$from_date'  AND '$to_date'";
    $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    if ($result) {
        return $result->COUNT;
    } else {
        return FALSE;
    }
}
function getItemNameById($item_code) {
    
    $tbl_fields = "item_desc";
    $table_name = "inventory_master";
    $where = "item_code='$item_code'";
    $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    if ($result) {
        return $result->item_desc;
    } else {
        return FALSE;
    }
}
function getRGPItemNameById($item_code) {
    
    $tbl_fields = "item_desc";
    $table_name = "rgp_item_master";
    $where = "item_code='$item_code'";
    $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    if ($result) {
        return $result->item_desc;
    } else {
        return FALSE;
    }
}

function getItemDetailByCode($itemCode) {
    
    $tbl_fields = "*";
    $table_name = "inventory_master";
    $where = "item_code='$itemCode'";
    $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}
function getItemDescByitemCode($itemCode) {
    
    $tbl_fields = "item_desc";
    $table_name = "inventory_master";
    $where = "item_code='$itemCode'";
    $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result->item_desc;
}
function getDCDetailByDCNo($dc_number) {
    $tbl_fields = "*";
    $table_name = "btn_master";
    $where = "btn_no='$dc_number'";
    $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}
function getDCByDate($date) {
    $tbl_fields = "*";
    $table_name = "btn_master";
    $where = "`btn_date`='$date' AND btn_no like '%GJDC%'";
    $result = $this->mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}
function getDCByDateOfOutwardAgainstRejection() {
    $tbl_fields = "*";
    $table_name = "btn_master";
    $where = "btn_no like '%GJDC%' AND outward_type='1'";
    $result = $this->mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getDCDetailsByDcNo($dc_number, $date) {
    
    $tbl_fields = "*";
    $table_name = "btn_details";
    $where = "btn_no='$dc_number' AND `outward_date`='$date'";
    $result = $this->mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'article_no', $asc = 0, $desc = 0, $limit = '');
    return $result;
}
function getItemsByItemCode($itemCode) {
    $tbl_fields = "*";
    $table_name = "inventory_master";
    $where = "item_code='$itemCode'";
    $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    if ($result) {
        return $result;
    } else {
        return FALSE;
    }
}
function getItemCodeById($itemId) {
    $tbl_fields = "item_code";
    $table_name = "inventory_master";
    $where = "item_code='$itemId'";
    $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    if ($result) {
        return $result->item_code;
    } else {
        return FALSE;
    }
}
function getItemByItemCodeDetails($itemId) {
    
    $tbl_fields = "*";
    $table_name = "inventory_master";
    $where = "item_code='$itemId'";
    $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    if ($result) {
        return $result;
    } else {
        return FALSE;
    }
}
function getRGPItemByItemCodeDetails($itemId) {
    
    $tbl_fields = "*";
    $table_name = "rgp_item_master";
    $where = "item_code='$itemId'";
    $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    if ($result) {
        return $result;
    } else {
        return FALSE;
    }
}

function getItemDetails($itemId) {
    
    $tbl_fields = "*";
    $table_name = "inventory_master";
    $where = "item_code='$itemId'";
    $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getInwardCheckpostGoodsByDesc($tbl_fields, $table_name, $where) {
    
    $result = $this->mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 1, $limit = '');
    return $result;
}

function getInwardCheckpostGoods($tbl_fields, $table_name, $where, $order_by) {
    
    $result = $this->mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by, $asc = 0, $desc = 1, $limit = '');
    return $result;
}

function getLastInsertId() {
    
    $tbl_fields = "*";
    $table_name = "purchase_order";
    $result = $this->mysql_select($tbl_fields, $table_name, $where = '', $group_by = '', $order_by, $asc = 0, $desc = '1', $limit = '1');
    return $result;
}

function getTapeLastInvoiceNo($financialYear) {
    
    $tbl_fields = "tape_invoice_no, id";
    $table_name = "tape_sales_master";
    $where = "financial_year='$financialYear'";
    $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'id', $asc = 0, $desc = 1, $limit = '1');
    return $result;
}

function getOpeningStock($tbl_fields, $table_name, $where) {
    
    $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getTapePrice($Description, $conspicuity) {
    
    $tbl_fields = "*";
    $table_name = "tape_price_master";
    $where = "description='$Description'";
    $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getAllZone() {
    
    $tbl_fields = "`zone_id`,zone_rto,zone_rto_title, `zone_title`";
    $table_name = "company_master_inv";
//    $group_by = "zone_id";
    $result = $this->mysql_selects($tbl_fields, $table_name, $where = '', $group_by, $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getNewDealersByZoneId($zone_id = '', $where = '') {
    
    $tbl_fields = "*";
    $table_name = "dealer_masters";
    $result = $this->mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getRtoNameByRtoId($zone_rto) {
    $tbl_fields = "`zone_rto_title`";
    $table_name = "company_master_inv";
    $where = "zone_rto='" . $zone_rto . "'";
    $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    if ($result) {
        return $result->zone_rto_title;
    } else {
        return '';
    }
}
function getEcNameByRtoId($zone_rto) {
    $tbl_fields = "`name`";
    $table_name = "ec_master";
    $where = "id='" . $zone_rto . "'";
    $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    if ($result) {
        return $result->name;
    } else {
        return '';
    }
}
function getWarehouseNameByRtoId($zone_rto) {
    $tbl_fields = "`name`";
    $table_name = "warehouse_master";
    $where = "id='" . $zone_rto . "'";
    $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    if ($result) {
        return $result->name;
    } else {
        return '';
    }
}
function getZoneIdByRtoId($rto_id) {
    $tbl_fields = "zone_id";
    $table_name = "company_master_inv";
    $where = "zone_rto='" . $rto_id . "'";
    $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result->zone_id;
}
function getZoneDataByZoneId($zone_id) {
    
    $tbl_fields = "*";
    $table_name = "zone_email";
    $where = "zone_id='" . $zone_id . "'";
    $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getRTODataByCondition($rto) {
    
    $tbl_fields = "`zone_rto`,`zone_rto_title`";
    $table_name = "company_master_inv";
    if ($rto == '') {
        $where = "";
    } else {
        $where = "zone_rto='" . $rto . "'";
    }
    $result = $this->mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getRTOData($rto) {
    
    $tbl_fields = "*";
    $table_name = "company_master_inv";
    $where = "zone_rto='" . $rto . "'";
    $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getZoneNameByZoneId($zone_id) {
    
    $tbl_fields = "`zone_title`";
    $table_name = "company_master_inv";
    $where = "zone_id='" . $zone_id . "'";
    $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result->zone_title;
}

function getRtoByZoneId($zone_id) {
    
    $tbl_fields = "`zone_rto`, `zone_rto_title`";
    $table_name = "company_master_inv";
    $where = "zone_id='" . $zone_id . "'";
    $result = $this->mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getDealerNameById($dealerId) {
    
    $tbl_fields = "dealer_name";
    $table_name = "dealer_masters";
    $where = "id=" . $dealerId;
    $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result->dealer_name;
}

function getDealerById($dealerId) {
    
    $tbl_fields = "*";
    $table_name = "dealer_masters";
    $where = "id=" . $dealerId;
    $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getAllDealers($tbl_fields = '', $table_name = '', $where = '', $limit = '') {
    
    $tbl_fields = "*";
    $table_name = "dealer_masters";
    $result = $this->mysql_selects($tbl_fields, $table_name, $where = '', $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit);
    return $result;
}

function getRequestedDealers($limit) {
    
    $tbl_fields = "*";
    $table_name = "dealer_masters";
    $where = "hsrp_flag!=0";
    $result = $this->mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getReqstedCountDealers($limit) {
    
    $tbl_fields = "*";
    $table_name = "dealer_masters";
    $where = "hsrp_flag!=0";
    $result = $this-> num_rows($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit);
    return $result;
}

function getAllCountDealers($tbl_fields, $table_name, $where, $limit) {
    
    $tbl_fields = "*";
    $table_name = "dealer_masters";
    $result = $this-> num_rows($tbl_fields, $table_name, $where = '', $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit);
    return $result;
}

function getAllEnquire($limit, $where) {
    
    $tbl_fields = "*";
    $table_name = "enquiry";
    $result = $this->mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'EnquiryID', $asc = 1, $desc = 0, $limit);
    return $result;
}

function getAllCountEnquire($limit, $where) {
    
    $tbl_fields = "*";
    $table_name = "enquiry";
    $result = $this-> num_rows($tbl_fields, $table_name, $where, $group_by = '', $order_by = 'EnquiryID', $asc = 0, $desc = '1', $limit);
    return $result;
}

function getEnquireById($enquireID) {
    
    $tbl_fields = "*";
    $table_name = "enquiry";
    $where = "EnquiryID=" . $enquireID;
    $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function userLoginCheck($username, $password) {
    
    $tbl_fields = "*";
    $table_name = "user_master";
    $where = "user_id='$username' and password='$password' and approval_level='1'";
    $result = $this-> num_rows($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit);
    return $result;
}

function hsrp_dealership_level_dailly_report($rto, $datetime) {
    
    $tbl_fields = "*";
    $table_name = "hsrp_dealership_level_dailly_report";
    $where = "rto_id='$rto' AND DATE(`datetime`)='" . $datetime . "'";
    return $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
}

function getTodaysReportNumber() {
    
    $tbl_fields = "*";
    $table_name = "hsrp_dealership_level_dailly_report";
    $where = "DATE(`datetime`)='" . date('Y-m-d') . "'";
    return $result = $this-> num_rows($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit);
}

function getReportNumber($finacial_year) {
    
    $tbl_fields = "*";
    $table_name = "hsrp_dealership_level_dailly_report";
    $order_by = "report_no";
    $limit = "1";
    $where = " finacial_year='" . $finacial_year . "'";
    $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by, $asc = 0, $desc = 1, $limit);
    if ($result) {
        return $result->report_no;
    } else {
        return FALSE;
    }
}

function getDealwerByRTOid($rto_id) {
    
    $tbl_fields = "*";
    $table_name = "dealer_masters";
    $where = "`rto_code`='" . $rto_id . "'";
    $result = $this->mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getApplicationTypes() {
    
    $tbl_fields = "*";
    $table_name = "application_type_master";
    $result = $this->mysql_selects($tbl_fields, $table_name, $where = '', $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getGujlivebychassisNo($chassis_no, $dealer_code, $rto_id, $app_type) {
    
    $tbl_fields = "*";
    $table_name = "guj_online_schedule_forms_live";
    $where = "`chasis_no`='$chassis_no' AND `dealer_code`='$dealer_code' AND `app_type`='$app_type' AND `payment_mode`='eWallet'";
    $result = $this->mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function getGujpendingbychassisNo($chassis_no, $dealer_code, $rto_id, $app_type) {
    
    $tbl_fields = "*";
    $table_name = "guj_online_schedule_forms_pending";
    $where = "`chasis_no`='$chassis_no' AND `dealer_code`='$dealer_code' AND `app_type`='$app_type' AND `payment_mode`='eWallet'";
    $result = $this->mysql_selects($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function userLoginData($username, $password) {
    
    $tbl_fields = "*";
    $table_name = "user_master";
    $where = "user_id='$username' and password='$password' and approval_level='1'";
    $result = $this->mysql_select($tbl_fields, $table_name, $where, $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

function allRTOlist() {
    
    $tbl_fields = "`zone_rto_title`, `zone_title`, `zone_rto`, `zone_id`, `rto_id`";
    $table_name = "company_master_inv";
    $result = $this->mysql_selects($tbl_fields, $table_name, $where = '', $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '');
    return $result;
}

//    ajit-----------------------------------------------------------------------------------------------------------------------------------
function num_rows($tbl_fields = '*', $table_name = '', $where = '', $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '') {
        $reponse=array();
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
        if($table_name == 'lid_sr_no_store'){
//            echo $sql;
//            exit;
        }
        $result = $this->conn->prepare($sql);
        $ret = $result->execute();
       if($ret){
            $result->setFetchMode(PDO::FETCH_OBJ);
            $reponse = $result->fetchAll();
            return count($reponse);
       }else{
           return 0;
       }
    }
    function mysql_select($tbl_fields = '*', $table_name = '', $where = '', $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '') {
        $reponse=array();
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
        if($table_name == 'purchase_order'){
        //    echo $sql;
        //    exit;
        }
        $result = $this->conn->prepare($sql);
        $ret = $result->execute();
       if($ret){
            $result->setFetchMode(PDO::FETCH_OBJ);
            $reponse = $result->fetch();
            return $reponse;
       }else{
           return false;
       }
    }
        
    function mysql_selects($tbl_fields = '*', $table_name = '', $where = '', $group_by = '', $order_by = '', $asc = 0, $desc = 0, $limit = '') {
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
   //echo $sql;
        if($table_name == 'inventory_grn b,inventory_master im'){
//            echo $sql;
//            exit;
        }

        $result = $this->conn->prepare($sql);
        $ret = $result->execute();
        if (!$ret) {
           echo 'PDO::errorInfo():';
           echo '<br />';
           echo 'error SQL: '.$sql;
           die();
        }
        $result->setFetchMode(PDO::FETCH_OBJ);
        $reponse = $result->fetchAll();

        return $reponse;
    }
    function updateSum($table_name = '', $data = '', $where = '') {
        $field_q = "";
        foreach ($data as $key => $value) {
            $field_q .= $key . "=" . $value . ",";
        }
        $field_q = rtrim($field_q, ',');
        if (isset($where)) {
            $where = " WHERE " . $where;
        }
        $query = "UPDATE $table_name SET $field_q $where";
        if ($table_name == 'inventory_master') {
//            echo $query;
//            exit;
        }
        $result = $this->conn->prepare($query);
        $ret = $result->execute();
        if (!$ret) {
           echo 'PDO::errorInfo():';
           echo '<br />';
           echo 'error SQL: '.$sql;
           die();
        }else{
            return TRUE;
        }
    }
    function update($table_name = '', $data = '', $where = '') {
        $field_q = "";
        foreach ($data as $key => $value) {
            $field_q .= $key . "='" . $value . "',";
        }
        $field_q = rtrim($field_q, ',');
        if (isset($where)) {
            $where = " WHERE " . $where;
        }
        $query = "UPDATE $table_name SET $field_q $where";
        if ($table_name == 'btn_details') {
//            echo $query;
//            exit;
        }
        $result = $this->conn->prepare($query);
        $ret = $result->execute();
        if (!$ret) {
           echo 'PDO::errorInfo():';
           echo '<br />';
           echo 'error SQL: '.$sql;
           die();
        }else{
            return TRUE;
        }
    }    
    function insert($table_name = '', $data = '') {
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
        if ($table_name == 'purchase_order') {
//            echo $query;
//            exit;
        }
        // $result = $this->conn->prepare($query);
        $ret = $this->conn->exec($query);
        if (!$ret) {
           echo 'PDO::errorInfo():';
           echo '<br />';
           echo 'error SQL: '.$sql;
           die();
        }else{
            return TRUE;
        }
    }   
    
    function delete($table_name = '', $where = '') {
        if (isset($where)) {
            $where = " WHERE " . $where;
        }
        $query = "delete from $table_name $where";
        // $result = $this->conn->prepare($query);
        $ret = $this->conn->exec($query);
        if (!$ret) {
           echo 'PDO::errorInfo():';
           echo '<br />';
           echo 'error SQL: '.$sql;
           die();
        }else{
            return TRUE;
        }
    }
    
    function get_tbl_column($table_name = '') {
        $row = array();
        $sql = "SHOW COLUMNS FROM " . $table_name . ";";
       
        $result = $this->conn->prepare($sql);
        $ret = $result->execute();
        if (!$ret) {
           echo 'PDO::errorInfo():';
           echo '<br />';
           echo 'error SQL: '.$sql;
           die();
        }
        $result->setFetchMode(PDO::FETCH_OBJ);
        $reponse = $result->fetchAll();
        return $reponse->Field;
    }
    
    function execute($sql) {
        if (!$response = $this->conn->exec($sql)) {
            echo 'PDO::errorInfo():';
            echo '<br />';
            echo 'error SQL: '.$sql;
            die();
        }
        return $response;
    }
}