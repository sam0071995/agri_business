<?php

session_start();
error_reporting(0);
require_once 'includes/common_function.php';

date_default_timezone_set('Asia/Kolkata');

extract($_POST);
if (isset($_POST['request_type'])) {
    if ($_POST['request_type'] == 'inward_reject') {
        $id = $_POST['id'];
        $transferPending = getGrnDataByGrnid($id);

        $retailer_id = $_SESSION['id'];
        if (isset($retailer_id)) {
            if ($transferPending->dispatch_retailer_id != 0) {
                $data_item_sr_master = array();
                $data_item_sr_master['status'] = 0;
                $data_item_sr_master['update_datetime'] = date("Y-m-d H:i:s");
                $whereitem_sr_master = "item_code='$transferPending->item_desc' AND retailer_id='$transferPending->dispatch_retailer_id' and batch_no='$transferPending->batch_number' and status='7'";
                $limit_item_sr_master = round($transferPending->billed_qty - $transferPending->inward_qty);
                $updateIn = updateIn('item_sr_master', $data_item_sr_master, $whereitem_sr_master, $limit_item_sr_master);
            } else {
                $data_company_inward_po_history = array();
                $data_company_inward_po_history['status'] = 2;
                $data_company_inward_po_history['deleted'] = 2;
                $data_company_inward_po_history['delete_date'] = date("Y-m-d H:i:s");
                $wherecompany_inward_po_history = "po_id='$transferPending->po_id' AND item_code='$transferPending->item_desc' AND retailer_id='$retailer_id'";
                $updateIn = update('company_inward_po_history', $data_company_inward_po_history, $wherecompany_inward_po_history);

                $data_purchase_order = array();
                $data_purchase_order['status'] = 0;
                $wherepurchase_order = "id='$transferPending->po_id' AND retailer_id='$retailer_id'";
                $updateIn = update('purchase_order', $data_purchase_order, $wherepurchase_order);
            }
            $remarks = $_POST['remarks'];
            $update_data = array();
            $update_data['retailer_inwd_flg'] = 7;
            $update_data['status'] = 7;
            $update_data['retailer_inwd_date'] = date('Y-m-d H:i:s');
            $update_data['dalete_date'] = date('Y-m-d H:i:s');
            if (empty($remarks)) {
                $update_data['remark'] = "Inward Rejected by Retailer.";
            } else {
                $update_data['remark'] = $remarks;
            }
            $update_data_whr = "id = '$id' and retailer_inwd_flg = '0' and retailer_id = '$retailer_id'";
            $uupd = update('inventory_grn', $update_data, $update_data_whr);
            if ($uupd) {
                echo '0';
            } else {
                echo '101';
            }
        } else {
            echo '101';
        }
    }

    if ($_POST['request_type'] == 'inward_grn') {
        $id = $_POST['id'];
        $inward_qty_input = $_POST['inward_qty'];
        $batch_number = $_POST['batch_number'];
        $Vehicle_Number = $_POST['Vehicle_Number'];
        $name_of_person = $_POST['name_of_person'];
        $manufacturing_date = date("Y-m-d", strtotime($_POST['manufacturing_date']));

        $retailer_id = $_SESSION['id'];
        $grn_data = getGrnDataByGrnid($id);
        $billed_qty_po_qty = $grn_data->billed_qty;
        $dispatch_retailer_id = $grn_data->dispatch_retailer_id;

        if ($manufacturing_date >= date("Y-m-d")) {
            echo '4';
            exit;
        }
        $expire_date = date("Y-m-d", strtotime($_POST['expire_date']));
        if ($expire_date == "1970-01-01") {
            echo '7';
            exit;
        }
        if ($manufacturing_date == "1970-01-01") {
            echo '8';
            exit;
        }
        if ($dispatch_retailer_id == 0 && $expire_date <= date("Y-m-d")) {
            echo '6';
            exit;
        }
        if ($manufacturing_date == $expire_date) {
            echo '5';
            exit;
        }
        if ($dispatch_retailer_id == 0 && $manufacturing_date > $expire_date) {
            echo '9';
            exit;
        }

        $billed_qty_po_inward_qty = $grn_data->inward_qty;
        $billed_qty_po_po_no = $grn_data->po_no;
        $billed_qty_po_po_basic = $grn_data->po_basic;
        $billed_qty_po_po_gst = $grn_data->po_gst;
        $billed_qty_po_po_total = $grn_data->po_total;
        $total_inward = numberDecimal($inward_qty_input + $billed_qty_po_inward_qty);
        if (numberDecimal($billed_qty_po_qty) < numberDecimal($total_inward)) {
            echo '2';
            exit;
        }
        if ($billed_qty_po_qty == 0) {
            echo '3';
            exit;
        }
        if ($billed_qty_po_qty == $total_inward) {
            $retailer_inwd_flg = "1";
        } else {
            $retailer_inwd_flg = "0";
        }

        $update_data = array();
        $update_data['retailer_inwd_flg'] = $retailer_inwd_flg;
        $update_data['inward_qty'] = $total_inward;
        $update_data['inward_Vehicle_Number'] = $Vehicle_Number;
        $update_data['inward_name_of_person'] = $name_of_person;
        $update_data['batch_number'] = $batch_number;
        $update_data['manufacture_date'] = date("Y-m-d", strtotime($manufacturing_date));
        $update_data['expire_date'] = date("Y-m-d", strtotime($expire_date));
        $update_data['retailer_inwd_date'] = date('Y-m-d H:i:s');
        $update_data_whr = "id = '$id' and retailer_inwd_flg = '0' and retailer_id = '$retailer_id'";
        $uupd = update('inventory_grn', $update_data, $update_data_whr);

        if ($uupd) {
            if (ltrim(date('m')) > 3) {
                $cd = date('y');
                $dd = $cd + 1;
            } else {
                $dd = date('y');
                $cd = $dd - 1;
            }
            $fin_year = $cd . '' . $dd;
            $inc_no = getLastSrIncNo($fin_year, $retailer_id);
            for ($i = 0; $i < $inward_qty_input; $i++) {
                $retailer_id_sr = sprintf('%02d', $retailer_id);
                $inc_no_sr = sprintf('%08d', $inc_no);
                $serial_number = $fin_year . $retailer_id_sr . $inc_no_sr;
                $insertSr_no = array();
                $insertSr_no['serial_number'] = $serial_number;
                $insertSr_no['batch_no'] = $batch_number;
                $insertSr_no['manufacturing_date'] = date("Y-m-d", strtotime($manufacturing_date));
                $insertSr_no['expire_date'] = date("Y-m-d", strtotime($expire_date));
                $insertSr_no['item_desc'] = getItemNameByItemCode($grn_data->item_desc);
                $insertSr_no['item_id'] = getItemIdByItemCode($grn_data->item_desc);
                $insertSr_no['item_code'] = $grn_data->item_desc;
                $insertSr_no['po_date'] = $grn_data->po_date;
                $insertSr_no['vendor_id'] = $grn_data->vendor_id;
                $insertSr_no['retailer_id'] = $retailer_id;
                $insertSr_no['po_no'] = $billed_qty_po_po_no;
                $insertSr_no['rec_no'] = $grn_data->ref_po_no;
                $insertSr_no['purchase_basic'] = $billed_qty_po_po_basic;
                $insertSr_no['gst'] = $billed_qty_po_po_gst;
                $insertSr_no['total'] = $billed_qty_po_po_total;
                $insertSr_no['grn_id'] = $id;
                $insertSr_no['inc_no'] = $inc_no;
                $insertSr_no['fin_year'] = $fin_year;
                $insertSr_no['company_id'] = getRetailerCompanyIdById($retailer_id);
                $insertSr_no['date'] = date("Y-m-d");
                $insertSr_no['datetime'] = date("Y-m-d H:i:s");
                $insert = insert("item_sr_master", $insertSr_no);
                $inc_no++;
            }
            $check_stock_dup = getStockCountByItemCodeAndByRetailerId($retailer_id, $grn_data->item_desc);
            $check_stock_dup_f = getStockCountByItemCodeAndByRetailerId($grn_data->dispatch_retailer_id, $grn_data->item_desc);
            $inv_master_data_f = array();

            if ($grn_data->dispatch_retailer_id != 0) {
                $inv_master_data_f['issued_stock'] = $check_stock_dup_f->issued_stock + $inward_qty_input;
                $inv_master_data_f['current_stock'] = $check_stock_dup_f->current_stock - $inward_qty_input;
                $whr_f = "item_code = '$grn_data->item_desc' and retailer_id = '$grn_data->dispatch_retailer_id'";
                $update_f = update('retailer_inventory_master', $inv_master_data_f, $whr_f);
            }

            if (count($check_stock_dup) > 0) {
                $inv_master_data = array();
                $inv_master_data['receive_stock'] = $check_stock_dup->receive_stock + $inward_qty_input;
                $inv_master_data['current_stock'] = $check_stock_dup->current_stock + $inward_qty_input;
                $inv_master_data['last_po_basic'] = $billed_qty_po_po_basic;
                $inv_master_data['last_po_gst'] = $billed_qty_po_po_gst;
                $whr = "item_code = '$grn_data->item_desc' and retailer_id = '$retailer_id'";
                $update = update('retailer_inventory_master', $inv_master_data, $whr);
            } else {
                $inventory_data = getInventoryDataByCode($grn_data->item_desc);
                $inv_master_data = array();
                $inv_master_data['retailer_id'] = $retailer_id;
                $inv_master_data['company_id'] = getRetailerCompanyIdById($retailer_id);
                $inv_master_data['item_id'] = $inventory_data->id;
                $inv_master_data['item_code'] = $inventory_data->item_code;
                $inv_master_data['item_desc'] = $inventory_data->item_desc;
                $inv_master_data['opening_stock'] = '0';
                $inv_master_data['receive_stock'] = $inward_qty_input;
                $inv_master_data['issued_stock'] = '0';
                $inv_master_data['current_stock'] = $inward_qty_input;
                $inv_master_data['minimum_stock'] = '0';
                $inv_master_data['hsn_code'] = $inventory_data->hsn_code;
                $inv_master_data['basic_price'] = $inventory_data->basic_price;
                $inv_master_data['cgst_rate'] = $inventory_data->cgst_rate;
                $inv_master_data['cgst_value'] = $inventory_data->cgst_value;
                $inv_master_data['sgst_rate'] = $inventory_data->sgst_rate;
                $inv_master_data['sgst_value'] = $inventory_data->sgst_value;
                $inv_master_data['igst_rate'] = $inventory_data->igst_rate;
                $inv_master_data['igst_value'] = $inventory_data->igst_value;
                $inv_master_data['total'] = $inventory_data->total;
                $inv_master_data['status'] = '1';
                $inv_master_data['last_po_basic'] = $billed_qty_po_po_basic;
                $inv_master_data['last_po_gst'] = $billed_qty_po_po_gst;
                $inv_master_data['uom'] = $inventory_data->uom;
                $inv_master_data['date'] = date('Y-m-d');
                $inv_master_data['active'] = '1';

                $ins = insert('retailer_inventory_master', $inv_master_data);
            }


            // FOR HISTORY========================================================
            $hiss_arr = array();
            $hiss_arr['grn_id'] = $id;
            $hiss_arr['retailer_id'] = $grn_data->retailer_id;
            $hiss_arr['po_no'] = $grn_data->po_no;
            $hiss_arr['po_id'] = $grn_data->po_id;
            $hiss_arr['batch_no'] = $batch_number;
            $hiss_arr['manufacturing_date'] = date("Y-m-d", strtotime($manufacturing_date));
            $hiss_arr['expire_date'] = date("Y-m-d", strtotime($expire_date));
            $hiss_arr['item_desc'] = $grn_data->item_desc;
            $hiss_arr['qty'] = $inward_qty_input;
            $hiss_arr['name_of_person'] = $name_of_person;
            $hiss_arr['Vehicle_Number'] = $Vehicle_Number;
            $hiss_arr['inwd_datetime'] = date('Y-m-d H:i:s');
            $ins = insert('retailer_inward_history', $hiss_arr);

            echo "0";
        } else {
            echo "1";
        }
    }


    if ($_POST['request_type'] == 'dispatch_req_stock') {
        $oredrno = $_POST['oredrno'];

        $data = getInvReqByRetailerDetailsByOrderNo($oredrno);
        foreach ($data as $row) {
            $ins_data = array();
            $ins_data['retailer_id'] = $row->retailer_id;
            $ins_data['dispatch_retailer_id'] = $_SESSION['id'];
            $ins_data['po_no'] = $oredrno;
            $ins_data['item_desc'] = $row->item_code;
            $ins_data['billed_qty'] = $row->req_qty;
            $ins_data['date_time'] = date('Y-m-d H:i:s');
            $ins = insert('inventory_grn', $ins_data);
        }

        $upd = array();
        $upd['status'] = '2';
        $upd['dispatch_date'] = date('Y-m-d H:i:s');
        $wwhr = "order_no='$oredrno' and status = '1'";
        update('retailer_stock_transfer', $upd, $wwhr);

        if ($ins) {
            echo '0';
        } else {
            echo '1';
        }
    }
}
