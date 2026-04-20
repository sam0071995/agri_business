<?php

session_start();
error_reporting(0);
require_once 'includes/common_function.php';

date_default_timezone_set('Asia/Kolkata');


extract($_POST);

$user_id = $_SESSION['id'];

if ($_POST['type'] == 'show_return_po_cart_items') {
    $retailer_id = $_POST['txt_retailer'];
    $html = "<tr>
    <td colspan = '9' align = 'center'><i><u>
    <font color = '#336633' size = '+2'>List of Items</font>
    </u></i></td>
    </tr>

    

    <tr>
    <th>Srno</th>
    <th align = 'left'>Item</th>
    <th align = 'left'>SKU</th>
    <th align = 'left'>QTY</th>
    <th align = 'left'>Unit Price</th>
    <th align = 'left'>Total Amount</th>
    <th align = 'left'>Delivery Date</th>
    <th align = 'left'>BatchNo</th>
    <th align = 'left'>Action</th>
    </tr>
    <tr>";

    $i = 1;
    $ttlqty = 0;
    $ttlamt = 0;
    foreach (getReturnPurchaseOrderDetailsByretailerId($retailer_id, $user_id) as $r5) {
        $uniqid = $r5->unique_id;
        $html .= " <tr id='tr_" . $uniqid . "'>
                        <td>" . $i . "</td>
                        <td>" . getItemNameByItemCode($r5->item_id) . " </td>
                        <td>" . getItemUOMByItemCode($r5->item_id) . "</td>
                        <td>" . $r5->qty . " </td>
                        <td>" . $r5->rate . " </td>
                        <td>" . $r5->amount . " </td>
                        <td>" . $r5->delivery_date . " </td>
                        <td>" . $r5->batch_no . " </td>
                        <td> 
                        <button class = 'btn btn-danger btn-xs' onclick = 'delete_item($uniqid); return false;'>Delete</button>
                        </td>
                       </tr>";
        $i++;
        $ttlqty = $ttlqty + $r5->qty;
        $ttlamt = $ttlamt + $r5->amount;
    }


    $html .= "</tr>";
    $html .= "<input type='hidden' id='ttl_qty' value='$ttlqty' />"
            . "<input type='hidden' id='ttl_amt' value='$ttlamt' />";
    echo $html;
}

if ($_POST['type'] == 'delete_return_po_item') {
    $rawid = $_POST['raw_id'];

    $whree = "unique_id = '$rawid' and status = '0'";
    $delete = delete('purchase_order_return_detail', $whree);
    if ($delete) {
        echo '0';
    } else {
        echo '1';
    }
}

if ($_POST['type'] == 'add_return_po_item_into_cart') {
    $retailer_id = $_POST['txt_vendor'];
    $po_type = $_POST['po_type'];
    $return_po_no = "PORET" . $retailer_id . "" . $fin_year_latest . "" . $po_no_increase;
    $item_code = $_POST['item_code'];
    $item_qty = $_POST['item_qty'];
    $unit_price = $_POST['unit_price'];
    $net_amt = $_POST['net_amt'];
    $delivry_date = $_POST['delivry_date'];
    $txt_batch_no = $_POST['txt_batch_no'];

    $insarr = array();
    $insarr['item_id'] = $item_code;
    $insarr['po_type'] = $po_type;
    $insarr['qty'] = $item_qty;
    $insarr['rate'] = $unit_price;
    $insarr['amount'] = $net_amt;
    $insarr['delivery_date'] = date('Y-m-d', strtotime($delivry_date));
    $insarr['user_id'] = $user_id;
    $insarr['added_date'] = date('Y-m-d H:i:s');
    $insarr['retailer_id'] = $retailer_id;
    $insarr['batch_no'] = $txt_batch_no;
    $insarr['expiry_date'] = getBatchExpiryDateByBatchNo($retailer_id, $txt_batch_no, $item_code);
    $ins = insert('purchase_order_return_detail', $insarr);
}

if ($_POST['type'] == 'get_item_wise_batchno_details') {
    $item_code = $_POST['item_code'];
    $retailer_id = $_POST['txt_vendor'];
//    $sr_no_array = array();

    if ($company_id == 3) {
        $sr_no_array['sr_no'] = getFreeSerielNoByRetailerItemVerde($item_code, $retailer_id);
    } else {
        $sr_no_array['sr_no'] = getFreeSerielNoByRetailerItemUA($item_code, $retailer_id);
    }
    echo json_encode((object) $sr_no_array);
//    print_r($sr_no_array);
}
