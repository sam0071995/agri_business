<?php
error_reporting(0);

require_once 'includes/session.php';
require_once 'includes/common_function.php';
require_once 'includes/db.class';
$bdd = new db();

//select vendor
if (isset($_REQUEST['id']) && isset($_REQUEST["type"])) {
    $id = $_REQUEST['id'];
    if ($_REQUEST["type"] == 'itemunit') {
        $uom = getItemUOMByItemCode($id);
        echo "<input type='hidden' id='ajax_sku' value='$uom'>";
    }
}
if (isset($_REQUEST['id']) && isset($_REQUEST["type_basic"])) {
    $id = $_REQUEST['id'];
    if ($_REQUEST["type_basic"] == 'itemunit_basic') {
        $uom = getItemUNITByItemCode($id);
        echo "<input type='hidden' id='ajax_sku' value='$uom'>";
    }
}

if (isset($_POST['request_type'])) {
    extract($_POST);
    if ($_POST['request_type'] == 'close_po_inventory') {
        $po_no = $_POST['po_no'];
        $upd_ar['status'] = 2;
        $whr_1 = "po_no = '$po_no' and status = '1'";
        $upd = $bdd->update('purchase_order', $upd_ar, $whr_1);

        if ($upd) {
            $data['po_no'] = $po_no;
            $data['date'] = date('Y-m-d');
            $data['datetime'] = date('Y-m-d H:i:s');
            $data['remarks'] = 'Close manually by inventory person';
            $insert = $bdd->insert($table_name = 'po_close_history', $data);
        }
        if ($insert && $upd) {
            echo "success";
        } else {
            echo "error";
        }
    } else if ($_POST['request_type'] == 'add_po_cart_item') {

        //        $po_id = getLastpurchaseOrderId() + 1;
        $data_detail = array();
        //        $data_detail["id"] = $po_id;
        $data_detail["user_id"] = $_SESSION['id'];
        $data_detail["item_id"] = $_POST['item_code'];
        $data_detail["qty"] = $_POST['item_qty'];
        $data_detail["rate"] = $_POST['unit_price'];
        $data_detail["amount"] = $_POST['net_amt'];
        $data_detail["gst_rate"] = $_POST['gst_rate'];
        $data_detail["gst_amount"] = $_POST['gst_amt'];
        $data_detail["added_date"] = date('Y-m-d H:i:s');
        $pur_det_insert = insert('purchase_order_detail', $data_detail);
    } else if ($_POST['request_type'] == 'add_po_cart_item_for_basic') {

        $data_detail = array();
        $data_detail["user_id"] = $_SESSION['id'];
        $data_detail["item_id"] = $_POST['item_code'];
        $data_detail["qty"] = $_POST['item_qty'];
        $data_detail["rate"] = $_POST['unit_price'];
        $data_detail["amount"] = $_POST['net_amt'];
        $data_detail["retailer_string"] = implode(',', $_POST['retailer_string']);
        $data_detail["added_date"] = date('Y-m-d H:i:s');
        $pur_det_insert = insert('purchase_order_basic_detail', $data_detail);
    } else if ($_POST['request_type'] == 'add_po_cart_item_retailer') {

        $data_detail = array();
        $data_detail["user_id"] = $_SESSION['id'];
        $data_detail["item_id"] = $_POST['item_code'];
        $data_detail["qty"] = $_POST['item_qty'];
        $data_detail["rate"] = $_POST['unit_price'];
        $data_detail["amount"] = $_POST['net_amt'];
        $data_detail["gst_rate"] = $_POST['gst_rate'];
        $data_detail["gst_amount"] = $_POST['gst_amt'];
        $data_detail["discount_amt"] = $_POST['txt_dis_unitprice'];
        $data_detail["added_date"] = date('Y-m-d H:i:s');
        $pur_det_insert = insert('purchase_order_detail', $data_detail);

        $retupdarr = array();
        $retupdarr['status'] = 3;
        $retupdarr['po_no'] = $_POST['po_number'];
        $retupdarr['po_date'] = date('Y-m-d H:i:s');
        $whhrrr = "retailer_id = '" . $_POST['retailerid'] . "' and status = '2' and item_code = '" . $_POST['item_code'] . "' ";
        update('retailer_po_generate_item_tbl', $retupdarr, $whhrrr);
    } else if ($_POST['request_type'] == 'add_po_cart_item_by_company') {

        $data_detail = array();
        $data_detail["user_id"] = $_SESSION['id'];
        $data_detail["item_id"] = $_POST['item_code'];
        $data_detail["qty"] = $_POST['item_qty'];
        $data_detail["rate"] = $_POST['unit_price'];
        $data_detail["amount"] = $_POST['net_amt'];
        $data_detail["gst_rate"] = $_POST['gst_rate'];
        $data_detail["gst_amount"] = $_POST['gst_amt'];
        $data_detail["discount_amt"] = $_POST['txt_dis_unitprice'];
        $data_detail["retailer_string"] = implode(',', $_POST['retailer_id_item']);
        $data_detail["added_date"] = date('Y-m-d H:i:s');
        $pur_det_insert = insert('purchase_order_detail', $data_detail);
        for ($i = 0; $i <= count($_POST['retailer_id_item']); $i++) {
            $retupdarr = array();
            $retupdarr['status'] = 3;
            $retupdarr['po_no'] = $_POST['po_number'];
            $retupdarr['retailer_string'] = implode(',', $_POST['retailer_id_item']);
            $retupdarr['po_date'] = date('Y-m-d H:i:s');
            $whhrrr = "retailer_id = '" . $_POST['retailer_id_item'][$i] . "' and status = '2' and item_code = '" . $_POST['item_code'] . "' ";
            update('retailer_po_generate_item_tbl', $retupdarr, $whhrrr);
        }
    } else if ($_POST['request_type'] == 'add_po_cart_item_update') {

        $po_id = $_POST['pur_id'];
        $data_detail = array();
        $data_detail["id"] = $po_id;
        $data_detail["user_id"] = $_SESSION['id'];
        $data_detail["item_id"] = $_POST['item_code'];
        $data_detail["qty"] = $_POST['item_qty'];
        $data_detail["rate"] = $_POST['unit_price'];
        $data_detail["amount"] = $_POST['net_amt'];
        $data_detail["gst_rate"] = $_POST['gst_rate'];
        $data_detail["gst_amount"] = $_POST['gst_amt'];
        $data_detail["status"] = 1;
        $data_detail["added_date"] = date('Y-m-d H:i:s');
        $pur_det_insert = insert('purchase_order_detail', $data_detail);
    } else if ($_POST['request_type'] == 'add_po_cart_item_update_for_basic') {

        $po_id = $_POST['pur_id'];
        $data_detail = array();
        $data_detail["id"] = $po_id;
        $data_detail["user_id"] = $_SESSION['id'];
        $data_detail["item_id"] = $_POST['item_code'];
        $data_detail["qty"] = $_POST['item_qty'];
        $data_detail["rate"] = $_POST['unit_price'];
        $data_detail["amount"] = $_POST['net_amt'];
        $data_detail["gst_rate"] = $_POST['gst_rate'];
        $data_detail["gst_amount"] = $_POST['gst_amt'];
        $data_detail["retailer_string"] = implode(',', $_POST['retailer_string']);
        $data_detail["status"] = 1;
        $data_detail["added_date"] = date('Y-m-d H:i:s');
        $pur_det_insert = insert('purchase_order_basic_detail', $data_detail);
    } else if ($_POST['request_type'] == 'get_cart_items_data') {
        $html = " <tr>
                                                        <td colspan='12' align='center'><i><u>
                                                                    <font color='#336633' size='+2'>List of Items</font>
                                                                </u></i></td>
                                                    </tr>


                                                    <tr>
                                                        <th >Item</th>
                                                        <th >SKU</th>
                                                        <th >QTY</th>
                                                        <th >Unit Price</th>
                                                        <th >GSTRate</th>
                                                        <th >GSTAmount</th>
                                                        <th >NetAmount</th>
                                                        <th >Action</th>
                                                    </tr>";
        $i = 1;
        if (count(getPurchaseOrderDetailsCartData($_SESSION['id'])) > 0) {
            $totl_qty = 0;
            $ttl_amt = 0;
            $gst_total = 0;
            foreach (getPurchaseOrderDetailsCartData($_SESSION['id']) as $r5) {
                $uniqid = $r5->unique_id;
                $html .= "
                    <tr id='$i' >
                        <td >" . getItemNameByItemCode($r5->item_id) . " </td>
                        <td >" . getItemUOMByItemCode($r5->item_id) . " </td>
                        <td >" . $r5->qty . " </td>
                        <td >" . $r5->rate . " </td>
                        <td >" . $r5->gst_rate . " </td>
                        <td >" . $r5->gst_amount . " </td>
                        <td >" . $r5->amount . " </td>
                        <td> <button class = 'btn btn-danger btn-xs' onclick = 'delete_cart_data($uniqid);return false;'>Delete</button></td>
                    </tr>";
                $i++;
                $totl_qty = $totl_qty + $r5->qty;
                $ttl_amt = $ttl_amt + $r5->amount;
                $gst_total = $gst_total + $r5->gst_amount;
            }
        }
        $html .= "<input type='hidden' id='ttl_qty' value='$totl_qty' />"
            . "<input type='hidden' id='ttl_amuntt' value = '$ttl_amt' />"
            . "<input type='hidden' id='gst_ttl_valu' value='$gst_total'/>";

        echo $html;
    } else if ($_POST['request_type'] == 'get_cart_items_data_for_basic') {
        $html = " <tr>
                                                        <td colspan='12' align='center'><i><u>
                                                                    <font color='#336633' size='+2'>List of Items</font>
                                                                </u></i></td>
                                                    </tr>


                                                    <tr>
                                                        <th >Item</th>
                                                        <th >Store</th>
                                                        <th >Unit</th>
                                                        <th >QTY</th>
                                                        <th >Unit Price</th>
                                                        <th >NetAmount</th>
                                                        <th >Action</th>
                                                    </tr>";
        $i = 1;
        if (count(getPurchaseOrderDetailsCartDataForBasic($_SESSION['id'])) > 0) {
            $totl_qty = 0;
            $ttl_amt = 0;
            $gst_total = 0;
            foreach (getPurchaseOrderDetailsCartDataForBasic($_SESSION['id']) as $r5) {
                $uniqid = $r5->unique_id;
                $retailer_string = explode(',', $r5->retailer_string);
                $retailer_html = "";
                for ($l = 0; $l < count($retailer_string); $l++) {
                    $retailer_html .= $l + 1 . ". " . getRetailerNameById($retailer_string[$l]) . ' <br/>';
                }
                $html .= "
                    <tr id='$i' >
                        <td >" . getItemNameByItemCode($r5->item_id) . " </td>
                        <td >" . $retailer_html . " </td>
                        <td >" . getItemUNITByItemCode($r5->item_id) . " </td>
                        <td >" . $r5->qty . " </td>
                        <td >" . $r5->rate . " </td>
                        <td >" . $r5->amount . " </td>
                        <td> <button class = 'btn btn-danger btn-xs' onclick = 'delete_cart_data($uniqid);return false;'>Delete</button></td>
                    </tr>";
                $i++;
                $totl_qty = $totl_qty + $r5->qty;
                $ttl_amt = $ttl_amt + $r5->amount;
                $gst_total = $gst_total;
            }
        }
        $html .= "<input type='hidden' id='ttl_qty' value='$totl_qty' />"
            . "<input type='hidden' id='ttl_amuntt' value = '$ttl_amt' />"
            . "<input type='hidden' id='gst_ttl_valu' value='$gst_total'/>";

        echo $html;
    } else if ($_POST['request_type'] == 'get_cart_items_data_retailer') {
?>

        <tr>
            <td colspan='12' align='center'><i><u>
                        <font color='#336633' size='+2'>List of Items</font>
                    </u></i></td>
        </tr>


        <tr>
            <th>Item</th>
            <th>SKU</th>
            <th>QTY</th>
            <th>Unit Price</th>
            <th>GSTRate</th>
            <th>GSTAmount</th>
            <th>NetAmount</th>
            <th>Action</th>
        </tr>
        <?php
        $i = 1;
        if (count(getPurchaseOrderDetailsCartData($_SESSION['id'])) > 0) {
            $totl_qty = 0;
            $ttl_amt = 0;
            $gst_total = 0;
            foreach (getPurchaseOrderDetailsCartData($_SESSION['id']) as $r5) {
                $uniqid = $r5->unique_id;
                $itmcode = $r5->item_id;
        ?>
                <tr id='<?php echo $i; ?>'>
                    <td><?php echo getItemNameByItemCode($r5->item_id); ?> </td>
                    <td><?php echo getItemUOMByItemCode($r5->item_id); ?> </td>
                    <td><?php echo $r5->qty; ?></td>
                    <td><?php echo $r5->rate; ?></td>
                    <td><?php echo $r5->gst_rate; ?></td>
                    <td><?php echo $r5->gst_amount; ?> </td>
                    <td><?php echo $r5->amount; ?></td>
                    <td> <button class='btn btn-danger btn-xs' onclick="delete_cart_data('<?php echo $uniqid; ?>', '<?php echo $itmcode; ?>');return false;">Delete</button></td>
                </tr>
        <?php
                $i++;
                $totl_qty = $totl_qty + $r5->qty;
                $ttl_amt = $ttl_amt + $r5->amount;
                $gst_total = $gst_total + $r5->gst_amount;
            }
        }
        ?>
        <input type='hidden' id='ttl_qty' value='<?php echo $totl_qty; ?>' />
        <input type='hidden' id='ttl_amuntt' value='<?php echo $ttl_amt; ?>' />
        <input type='hidden' id='gst_ttl_valu' value='<?php echo $gst_total; ?>' />

    <?php
    } else if ($_POST['request_type'] == 'get_cart_items_data_company') {
    ?>

        <tr>
            <td colspan='12' align='center'><i><u>
                        <font color='#336633' size='+2'>List of Items</font>
                    </u></i></td>
        </tr>


        <tr>
            <th>Item</th>
            <th>SKU</th>
            <th>QTY</th>
            <th>Unit Price</th>
            <th>GSTRate</th>
            <th>GSTAmount</th>
            <th>NetAmount</th>
            <th>Action</th>
        </tr>
        <?php
        $i = 1;
        if (count(getPurchaseOrderDetailsCartData($_SESSION['id'])) > 0) {
            $totl_qty = 0;
            $ttl_amt = 0;
            $gst_total = 0;
            foreach (getPurchaseOrderDetailsCartData($_SESSION['id']) as $r5) {
                $uniqid = $r5->unique_id;
                $itmcode = $r5->item_id;
                $retailer_string = $r5->retailer_string;
                $added_date = $r5->added_date;
        ?>
                <tr id='<?php echo $i; ?>'>
                    <td><?php echo getItemNameByItemCode($r5->item_id); ?> </td>
                    <td><?php echo getItemUOMByItemCode($r5->item_id); ?> </td>
                    <td><?php echo $r5->qty; ?></td>
                    <td><?php echo $r5->rate; ?></td>
                    <td><?php echo $r5->gst_rate; ?></td>
                    <td><?php echo $r5->gst_amount; ?> </td>
                    <td><?php echo $r5->amount; ?></td>
                    <td> <button class='btn btn-danger btn-xs' onclick="delete_cart_data('<?php echo $uniqid; ?>', '<?php echo $itmcode; ?>', '<?php echo $retailer_string; ?>', '<?php echo $added_date; ?>');return false;">Delete</button></td>
                </tr>
        <?php
                $i++;
                $totl_qty = $totl_qty + $r5->qty;
                $ttl_amt = $ttl_amt + $r5->amount;
                $gst_total = $gst_total + $r5->gst_amount;
            }
        }
        ?>
        <input type='hidden' id='ttl_qty' value='<?php echo $totl_qty; ?>' />
        <input type='hidden' id='ttl_amuntt' value='<?php echo $ttl_amt; ?>' />
        <input type='hidden' id='gst_ttl_valu' value='<?php echo $gst_total; ?>' />

    <?php
    } else if ($_POST['request_type'] == 'get_cart_items_data_update') {
        $poid = $_POST['pur_id'];
        $html = " <tr>
                                                        <td colspan='12' align='center'><i><u>
                                                                    <font color='#336633' size='+2'>List of Items</font>
                                                                </u></i></td>
                                                    </tr>


                                                    <tr>
                                                        <th >Item</th>
                                                        <th >Unit</th>
                                                        <th >QTY</th>
                                                        <th >Unit Price</th>
                                                        <th >GSTRate</th>
                                                        <th >GSTAmount</th>
                                                        <th >NetAmount</th>
                                                        <th >Action</th>
                                                    </tr>";
        $i = 1;
        if (count(getPurchaseOrderDetailsByPurchasId($_SESSION['id'], $poid)) > 0) {
            $totl_qty = 0;
            $ttl_amt = 0;
            $gst_total = 0;
            foreach (getPurchaseOrderDetailsByPurchasId($_SESSION['id'], $poid) as $r5) {
                $item_id = $r5->item_id;
                $orderId = $poid;
                $balanced_quantity = $bdd->getBalancedQuantityByPoAndItemId($item_id, $orderId);
                if (empty($balanced_quantity) && $balanced_quantity == 0) {
                    $balanced_quantity = 0;
                }
                $inward_qty = $r5->qty - $balanced_quantity;
                $uniqid = $r5->unique_id;
                $html .= "
                    <tr id='$i' >
                        <td >" . getItemNameByItemCode($r5->item_id) . " </td>
                        <td >" . getItemUOMByItemCode($r5->item_id) . " </td>
                        <td >" . $r5->qty . " </td>
                        <td >" . $r5->rate . " </td>
                        <td >" . $r5->gst_rate . " </td>
                        <td >" . $r5->gst_amount . " </td>
                        <td >" . $r5->amount . " </td>";
                if ($inward_qty == 0) {
                    $html .= "<td> <button type='button' class = 'btn btn-danger btn-xs' onclick = 'delete_cart_data($uniqid)'>Delete</button></td>";
                } else {
                    $html .= "<td>&nbsp;</td>";
                }
                $html .= "</tr>";
                $i++;
                $totl_qty = $totl_qty + $r5->qty;
                $ttl_amt = $ttl_amt + $r5->amount;
                $gst_total = $gst_total + $r5->gst_amount;
            }
        }
        $html .= "<input type='hidden' id='ttl_qty' value='$totl_qty' />"
            . "<input type='hidden' id='ttl_amuntt' value = '$ttl_amt' />"
            . "<input type='hidden' id='gst_ttl_valu' value='$gst_total'/>";

        echo $html;
    } else if ($_POST['request_type'] == 'get_cart_items_data_update_for_basic') {
        $poid = $_POST['pur_id'];
        $html = " <tr>
                                                        <td colspan='12' align='center'><i><u>
                                                                    <font color='#336633' size='+2'>List of Items</font>
                                                                </u></i></td>
                                                    </tr>


                                                    <tr>
                                                        <th >Item</th>
                                                        <th >Store</th>
                                                        <th >Unit</th>
                                                        <th >QTY</th>
                                                        <th >Unit Price</th>
                                                        <th >NetAmount</th>
                                                        <th >Action</th>
                                                    </tr>";
        $i = 1;
        if (count(getPurchaseOrderDetailsByPurchasIdForBasicCentral($poid)) > 0) {
            $totl_qty = 0;
            $ttl_amt = 0;
            $gst_total = 0;
            foreach (getPurchaseOrderDetailsByPurchasIdForBasicCentral($poid) as $r5) {
                $uniqid = $r5->unique_id;
                $retailer_string = explode(',', $r5->retailer_string);
                $retailer_html = "";
                for ($l = 0; $l < count($retailer_string); $l++) {
                    $retailer_html .= $l + 1 . ". " . getRetailerNameById($retailer_string[$l]) . ' <br/>';
                }
                $html .= "
                    <tr id='$i' >
                        <td >" . getItemNameByItemCode($r5->item_id) . " </td>
                        <td >" . $retailer_html . " </td>
                        <td >" . getItemUOMByItemCode($r5->item_id) . " </td>
                        <td >" . $r5->qty . " </td>
                        <td >" . $r5->rate . " </td>
                        <td >" . $r5->amount . " </td>
                        <td> <button type='button' class = 'btn btn-danger btn-xs' onclick = 'delete_cart_data($uniqid)'>Delete</button></td>
                    </tr>";
                $i++;
                $totl_qty = $totl_qty + $r5->qty;
                $ttl_amt = $ttl_amt + $r5->amount;
                $gst_total = $gst_total;
            }
        }
        $html .= "<input type='hidden' id='ttl_qty' value='$totl_qty' />"
            . "<input type='hidden' id='ttl_amuntt' value = '$ttl_amt' />"
            . "<input type='hidden' id='gst_ttl_valu' value='$gst_total'/>";

        echo $html;
    } else if ($_POST['request_type'] == 'delete_cart_po_data') {
        $uniqid = $_POST['idd'];
        $whrr = "unique_id='$uniqid'";
        $dell = delete('purchase_order_detail', $whrr);
        if ($dell) {
            echo "0";
        } else {
            echo "1";
        }
    } else if ($_POST['request_type'] == 'delete_cart_po_data_for_basic') {
        $uniqid = $_POST['idd'];
        $whrr = "unique_id='$uniqid'";
        $dell = delete('purchase_order_basic_detail', $whrr);
        if ($dell) {
            echo "0";
        } else {
            echo "1";
        }
    } else if ($_POST['request_type'] == 'delete_cart_po_data_retailer') {
        $uniqid = $_POST['idd'];
        $item_code = $_POST['item_code'];
        $retailerid = $_POST['retailerid'];
        $po_number = $_POST['po_number'];
        $whrr = "unique_id='$uniqid'";
        $dell = delete('purchase_order_detail', $whrr);

        $updarr = array();
        $updarr['status'] = 2;
        $whrr = "retailer_id = '$retailerid' and po_no = '$po_number' and item_code = '$item_code' and status = '3'";
        update('retailer_po_generate_item_tbl', $updarr, $whrr);


        if ($dell) {
            echo "0";
        } else {
            echo "1";
        }
    } else if ($_POST['request_type'] == 'delete_cart_po_data_company') {
        $uniqid = $_POST['idd'];
        $item_code = $_POST['item_code'];
        $retailerid = $_POST['retailerid'];
        $retailer_string = $_POST['retailer_string'];
        $added_date = $_POST['added_date'];
        $po_number = trim($_POST['po_number']);

        $whrr = "unique_id='$uniqid' and retailer_string = '$retailer_string' and added_date = '$added_date'";
        $dell = delete('purchase_order_detail', $whrr);

        $updarr = array();
        $updarr['status'] = 2;
        $updarr['po_no'] = NULL;
        $whrr = "retailer_string = '$retailer_string' and po_date='$added_date' and item_code = '$item_code' and status = '3'";

        update('retailer_po_generate_item_tbl', $updarr, $whrr);


        if ($dell) {
            echo "0";
        } else {
            echo "1";
        }
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

if ($_POST['types'] == 'get_retailer_po_item') {
    $retailerid = $_POST['retailerid'];
    $data = getRetailerRequiredItemPoList($retailerid);

    $html = "<option value='0'>-- SELECT ITEM --</option>";
    foreach ($data as $raws) {
        $html .= "<option value='" . $raws->item_code . "'>" . $raws->item_desc . "</option>";
    }
    echo $html;
}
if ($_POST['types'] == 'get_retailer_po_item_company') {
    $company_id_in = $_POST['company_id_in'];
    $data = getRequiredItemPoListByCompanyId($company_id_in);

    $html = "<option value='0'>-- SELECT ITEM --</option>";
    foreach ($data as $raws) {
        $html .= "<option value='" . $raws->item_code . "'>" . $raws->item_desc . "</option>";
    }
    echo $html;
}
if ($_POST['types'] == 'get_item_data_by_retailerid') {
    $itemcode = $_POST['itemcode'];
    $retailerid = $_POST['retailerid'];
    $data = getRetailerRequiredItemPoListByItemCode($retailerid, $itemcode);
    echo json_encode($data);
}

if ($_POST['types'] == 'get_item_data_by_companyid') {
    $itemcode = $_POST['itemcode'];
    $company_id = $_POST['company_id'];

    $html = 'Retailer <br/><select name="retailer_for_item[]" multiple="" id="retailer_for_item" >';
    foreach (getPoItemPendingRetailerListByItemCodeAndCompanyId($itemcode, $company_id) as $data) {
        $html .= "<option value='" . $data->retailer_id . "'>" . getRetailerById($data->retailer_id)->name . " ( " . $data->bdm_qty . " ) </option>";
    }
    $html .= "</select>";

    $get_item_qty = getRetailerOrderPoItemQtyAndUom($itemcode, $company_id);

    $data_arr = array();
    $data_arr['html'] = $html;
    $data_arr['uom'] = $get_item_qty->uom;
    $data_arr['bdm_qty'] = $get_item_qty->bdmqty;

    echo json_encode($data_arr);
}


if ($_POST['types'] == 'get_batch_number_by_itemid_retaielr_id') {
    $retailer_id = $_POST['retailer_id'];
    $inventory_item = $_POST['inventory_item'];

    $data = getBatchNumberByretailerAndItemId($retailer_id, $inventory_item);
    $html = '';
    foreach ($data as $raw) {
        $html .= "<option value='" . $raw->batch_no . "'>" . $raw->batch_no . " (  " . $raw->batchcount . " ) </option>";
    }
    echo $html;
}

if ($_POST['types'] == 'update_status_for_basic_po') {
    $ponoo = $_POST['ponoo'];
    $statusval = $_POST['statusval'];
    $status_remark = $_POST['status_remark'];

    $updarr = array();
    $updarr['status'] = $statusval;
    $updarr['status_remarks'] = $status_remark;
    $updarr['status_upd_date'] = date('Y-m-d H:i:s');
    $whrr = "po_no = '$ponoo'";

    $updd = update('purchase_order_basic', $updarr, $whrr);

    if ($updd) {
        echo "1";
    } else {
        echo "0";
    }
}

if ($_POST['request_type'] == 'get_retailer_refresh_div') {
    ?>
    Retailer Name <br>
    <select class="text" name="Retailer_id[]" id="Retailer_id" multiple="">
        <!--<option value="">--Select Retailer--</option>-->
        <?php foreach (getActiveRetailerDetails($company_id_in) as $active_sellers) { ?>
            <option value="<?php echo $active_sellers->id; ?>"><?php echo $active_sellers->name; ?></option>
        <?php } ?>
    </select>
<?php
}
