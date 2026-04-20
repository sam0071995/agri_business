<?php

session_start();
error_reporting(0);
require_once 'includes/common_function.php';

date_default_timezone_set('Asia/Kolkata');

extract($_POST);
if (isset($_POST['request_type'])) {

    if (ltrim(date('m')) > 3) {
        $cd = date('y');
        $dd = $cd + 1;
    } else {
        $dd = date('y');
        $cd = $dd - 1;
    }
    $fin_year = $cd . '-' . $dd;

    if ($_POST['request_type'] == 'get_retailer_po') {
        $retailer_id = $_POST['retailer_id'];
        $data = [];

        //print_r($_SESSION);
        $inc_no = getLastIncNo($fin_year, $retailer_id);
        if ($inc_no == 0) {
            $inc_no = 1;
        } else {
            $inc_no = $inc_no + 1;
        }

        $po_number = "TS/" . $retailer_id . "/" . $fin_year . "/" . $inc_no;

        $item_html = '<select class="chosen-select form-control input-sm sel_cls_item" >
        <option value="">-- Select Item --</option>';
        foreach (getInventoryItem($retailer_id) as $row) {
            $item_html .= "<option value='" . $row->item_code . "'>" . $row->item_desc . "</option>";
        }
        $item_html .= '</select>';


        $data = ["po_no" => $po_number, "item_html" => $item_html];
        echo json_encode($data);
    }

    if ($_POST['request_type'] == 'insert_to_temp_table') {
        $retailer_id = $_POST['retailer_id'];
        $inc_no = getLastIncNo($fin_year, $retailer_id);

        if ($inc_no == '0') {
            $inc_no = 1;
        } else {
            $inc_no = $inc_no + 1;
        }

        $dup_check = getDuplicateOrderCount($fin_year, $retailer_id, $_POST['item_code'], $_SESSION['id']);

        if ($dup_check == '0') {

            $value['bdm_id'] = $_SESSION['id'];
            $value['retailer_id'] = $retailer_id;
            $value['po_no'] = $_POST['po_no'];
            $value['inc_no'] = $inc_no;
            $value['fin_year'] = $fin_year;
            $value['item_name'] = $_POST['item_desc'];
            $value['item_code'] = $_POST['item_code'];
            $value['price'] = $_POST['price'] * $_POST['qty'];
            $value['qty'] = $_POST['qty'];
            $value['order_place_date'] = date('Y-m-d');
            $value['order_status'] = 0;
            $value['uom'] = $_POST['uom'];

            $table = 'retailer_order_temporary';

            $q = insert($table, $value);

            echo '1';
        } else {
            echo '0';
        }
    }

    if ($_POST['request_type'] == 'get_availability') {
        $sel_cls_retailer = $_POST['sel_cls_retailer'];
        $item_code = $_POST['item_code'];

        $item_data = getItemDetailByCode($item_code, $sel_cls_retailer);
        echo json_encode($item_data);
    }

    if ($_POST['request_type'] == 'get_auto_refresh_list') {
        $retailer_id = $_POST['retailer_id'];
        $get_temp_item_list = getTempItemList($_SESSION['id'], $retailer_id);
        $i = 1;
        $total_amt = 0;
        $data = '';
        foreach ($get_temp_item_list as $value) {

            $data = $data . '<tr>
                <td align="center">' . $i . '</td>
                <td>' . $value->item_name . '</td>
                <td align="center"><input type="hidden" class="trn_id_1" value="' . $value->tr_id . '">' . $value->qty . '</td>
                <td align="right">' . $value->price . '</td>
                <td align="center"> 
                <input type="hidden" class="po_num_1" value="' . $value->po_no . '" />
                <i class="fa fa-trash i_remove_cls_small_inv" style="cursor:pointer; color:red" 
                id="' . $value->id . '" data-toggle="tooltip" title="Remove"></i>                         
                </td>
                </tr>';
            $i++;
        }
        echo $data;
    }

    if ($_POST['request_type'] == 'get_price_from_list') {
        $retailer_id = $_POST['retailer_id'];
        echo getTotalPriceFrmTempTbl($_SESSION['id'], $retailer_id);
    }

    if ($_POST['request_type'] == 'remove_item_from_list') {
        $id = $_POST['id'];
        $whr = "id = '$id'";
        $delete = delete('retailer_order_temporary', $whr);
        if ($delete) {
            echo '1';
        } else {
            echo '0';
        }
    }

    if ($_POST['request_type'] == 'confirm_order') {
        $amount = $_POST['amount'];
        $retailer_id = $_POST['retailer_id'];

        // $q = mysqli_query($conn, "SELECT count(*) as count, sum(qty) as qty from small_inventory_order_temporary where order_status=0 and dealer_code='" . $_SESSION['dealer_code'] . "' and po_no='" . $r['po_no'] . "' ");
        $var = getTempTableDetailsByRetailerIdAndPoNo($_SESSION['id'], $_POST['po_no'], $retailer_id);
        // $var = mysqli_fetch_array($q);

        $count = $var->count;
        $qty = $var->qty;
        $inc_no = $var->inc_no;


        if ($amount == 0) {
            echo 0;
        } else if ($count == 0) {
            echo 0;
        } else {

            $null_var = NULL;
            $insertMaster = array();
            $insertMaster['inc_no'] = $inc_no;
            $insertMaster['bdm_id'] = $_SESSION['id'];
            $insertMaster['retailer_id'] = $retailer_id;
            $insertMaster['added_date'] = date("Y-m-d");
            $insertMaster['added_datetime'] = date("Y-m-d H:i:s");
            $insertMaster['order_no'] = $_POST['po_no'];
            $insertMaster['po_no'] = $_POST['po_no'];
            $insertMaster['total_price'] = trim($_POST['amount']);
            // $insertMaster['total_count'] = $count;
            $insertMaster['total_count'] = $qty;
            $insertMaster['status'] = 1;
            $insertMaster['fin_year'] = $fin_year;
            $insertMaster['cus_name'] = $_POST['cus_name'];
            $insertMaster['cus_ph'] = $_POST['cus_ph'];
            $insertMaster['cus_add'] = $_POST['cus_add'];
            // print_r($insertMaster);
            // exit;
            $insrt = insert("retailer_order_master", $insertMaster);
        }

        if ($insrt) {
            echo 3;
            $q = mysqli_query($conn, "UPDATE bdm_order_temporary set order_status=1 where bdm_id='" . $_SESSION['id'] . "'
            and po_no='" . $_POST['po_no'] . "'");
        } else {
            echo 4;
        }
    }

    if ($_POST['request_type'] == 'approve_retailer_po_item') {
        $id = $_POST['id'];
        $bdm_qty = $_POST['bdm_qty'];

        $updarr = array();
        $updarr['bdm_qty'] = $bdm_qty;
        $updarr['status'] = 2;
        $updarr['bdm_approve_date'] = date('Y-m-d H:i:s');
        $whr = "id = '$id' and status = '1'";
        $upd = update('retailer_po_generate_item_tbl', $updarr, $whr);
        if ($upd) {
            echo '0';
        } else {
            echo '1';
        }
    }
    if ($_POST['request_type'] == 'approve_retailer_po_item_ttm') {
        $id = $_POST['id'];
        $bdm_qty = $_POST['bdm_qty'];

        $updarr = array();
        $updarr['tm_qty'] = $bdm_qty;
        $updarr['status'] = 4;
        $updarr['tm_name'] = getBdmDetailById($_SESSION['id'])->name;
        $updarr['tm_date'] = date('Y-m-d H:i:s');
        $whr = "id = '$id' and status = '2'";
        $upd = update('retailer_po_generate_item_tbl', $updarr, $whr);
        if ($upd) {
            echo '0';
        } else {
            echo '1';
        }
    }
}
