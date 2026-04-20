<?php
require_once 'includes/session.php';
require_once 'includes/common_function.php';

if (isset($_REQUEST['types'])) {
    if ($_REQUEST['types'] == "get_inventory_gst_rate") {
//        $Retailer_id = $_POST['Retailer_id'];
        $inventory_item_id = $_POST['inventory_item'];
        $item_data = getproductDetailsById($inventory_item_id);
//        $basic_price = getRetailerItemBasicPriceById($inventory_item_id, $Retailer_id);
        ?>
        <p>
            <b class="red">IGST Rate : <?php echo $item_data->igst_rate; ?></b> | 
            <b class="green">SGST Rate : <?php echo $item_data->sgst_rate; ?></b> | 
            <b class="blue">CGST Rate : <?php echo $item_data->cgst_rate; ?></b>
        </p>
        <?php
        exit;
    } else if ($_REQUEST['types'] == "Update_PO_rate") {
        $unique_id = $_POST['unique_id'];
        $rate = $_POST['rate'];
        $gst_rate = $_POST['gst_rate'];
        $item_id = 0;
        $vendor_id = 0;
        $retailer_id = 0;
        $company_id = 0;
        $po_no = 0;
        $result = 0;
        $purchase_order_detail = getPoDetailsByPoUniqueId($unique_id);
        if (isset($purchase_order_detail->id)) {
            $unique_id = $purchase_order_detail->unique_id;
            $po_id = $purchase_order_detail->id;
            $item_id = $purchase_order_detail->item_id;
            $old_rate = $purchase_order_detail->rate;
            $old_gst_rate = $purchase_order_detail->gst_rate;
            $purchase_order_master_dataa = getPurchaseOrdergetItemCountById($po_id);
            $retailer_id = $purchase_order_master_dataa->retailer_id;
            $company_id = $purchase_order_master_dataa->company_id;
            $vendor_id = $purchase_order_master_dataa->vendor_id;
            $po_no = $purchase_order_master_dataa->po_no;
            $po_number = getPurchaseOrderNumberByPOId($purchase_order_detail->id);
            $amount = $rate * $purchase_order_detail->qty;
            $gst_amount = ($amount * $gst_rate) / 100;

            $purchase_order_detail_dataUpdate = array();
            $purchase_order_detail_dataUpdate['rate'] = numberDecimal($rate);
            $purchase_order_detail_dataUpdate['gst_rate'] = numberDecimal($gst_rate);
            $purchase_order_detail_dataUpdate['amount'] = numberDecimal($amount);
            $purchase_order_detail_dataUpdate['gst_amount'] = numberDecimal($gst_amount);
            $purchase_order_detail_dataUpdate['rate_updatedatetime'] = date("Y-m-d H:i:s");
            $where_purchase_order_detail_dataUpdate = "item_id='$purchase_order_detail->item_id' and unique_id='$purchase_order_detail->unique_id'";
            $update_purchase_order_detail = update("purchase_order_detail", $purchase_order_detail_dataUpdate, $where_purchase_order_detail_dataUpdate);

            $po_total = $rate + (($rate * $gst_rate) / 100);
            $billed_qty = getInwardedBalanceQtyInoiceNo($retailer_id, $po_no, $item_id);
            $inventory_grn_dataUpdate = array();
            $inventory_grn_dataUpdate['po_basic'] = numberDecimal($rate);
            $inventory_grn_dataUpdate['po_gst'] = numberDecimal($gst_rate);
            $inventory_grn_dataUpdate['po_total'] = numberDecimal($po_total);
            $inventory_grn_dataUpdate['po_total_basic_value'] = $billed_qty * $rate;
            $where_inventory_grn_dataUpdate = "item_desc='$purchase_order_detail->item_id' and po_id='$po_id'";
            $update_inventory_grn = update("inventory_grn", $inventory_grn_dataUpdate, $where_inventory_grn_dataUpdate);

            $item_sr_master_dataUpdate = array();
            $item_sr_master_dataUpdate['purchase_basic'] = numberDecimal($rate);
            $item_sr_master_dataUpdate['gst'] = numberDecimal($gst_rate);
            $item_sr_master_dataUpdate['total'] = numberDecimal($po_total);
            $where_item_sr_master_dataUpdate = "item_code='$purchase_order_detail->item_id' and po_no='$po_number'";
            $update_item_sr_master = update("item_sr_master", $item_sr_master_dataUpdate, $where_item_sr_master_dataUpdate);
            $where_item_sr_master_dataUpdate_rec_no = "item_code='$purchase_order_detail->item_id' and rec_no='$po_number'";
            $update_item_sr_master_rec = update("item_sr_master", $item_sr_master_dataUpdate, $where_item_sr_master_dataUpdate_rec_no);

            if ($update_purchase_order_detail && $update_inventory_grn && $update_item_sr_master) {
                $po_sum_price = getPurchaseOrderDetailsBySum($po_id);
                $purchase_order_dataUpdate = array();
                $purchase_order_dataUpdate['net_total'] = numberDecimal($po_sum_price->amount);
                $purchase_order_dataUpdate['grand_total'] = numberDecimal($po_sum_price->amount);
                $purchase_order_dataUpdate['sub_total'] = numberDecimal($po_sum_price->amount);
                $purchase_order_dataUpdate['amount'] = numberDecimal($po_sum_price->amount);
                $purchase_order_dataUpdate['cgst_amt'] = numberDecimal($po_sum_price->gst_amount / 2);
                $purchase_order_dataUpdate['sgst_amt'] = numberDecimal($po_sum_price->gst_amount / 2);
                $where_purchase_order_dataUpdate = "id='$po_id'";
                $update_purchase_order = update("purchase_order", $purchase_order_dataUpdate, $where_purchase_order_dataUpdate);
                if ($update_purchase_order) {
                    $result = '100';
                } else {
                    $result = '103';
                }
            } else {
                $result = '102';
            }
        } else {
            $result = '101';
        }

        $po_rate_update_history = array();
        $po_rate_update_history['user_id'] = $_SESSION['id'];
        $po_rate_update_history['retailer_id'] = $retailer_id;
        $po_rate_update_history['company_id'] = $company_id;
        $po_rate_update_history['po_no'] = $po_no;
        $po_rate_update_history['po_id'] = $po_id;
        $po_rate_update_history['unique_id'] = $unique_id;
        $po_rate_update_history['item_code'] = $item_id;
        $po_rate_update_history['vendor_id'] = $vendor_id;
        $po_rate_update_history['rate'] = $old_rate;
        $po_rate_update_history['gst_rate'] = $old_gst_rate;
        $po_rate_update_history['new_rate'] = $rate;
        $po_rate_update_history['new_gst_rate'] = $gst_rate;
        $po_rate_update_history['datetime'] = date("Y-m-d H:i:s");
        $po_rate_update_history['status'] = $result;
        insert("po_rate_update_history", $po_rate_update_history);
        echo $result;
        exit;
    } else if ($_REQUEST['types'] == "get_retailer_free_item_sr_no") {
        $Retailer_id = $_POST['Retailer_id'];
        $retailer_free_srs = getFreeItemListSrByitemBatchGroup($Retailer_id);
        ?>
        <select class="form-field-select-2 form-control" name="inventory_item_sr_no" id="inventory_item_free_sr_select" required="required">
            <option value="">--Select Item | BatchNo | ExpiryDate--</option>
            <?php
            foreach ($retailer_free_srs as $retailer_free_sr) {
                ?>
                <option value="<?php echo $retailer_free_sr->item_code . "||" . $retailer_free_sr->batch_no . "||" . $retailer_free_sr->expire_date . "||" . $retailer_free_sr->manufacturing_date; ?>"><?php echo $retailer_free_sr->item_desc . " | BatchNo : " . $retailer_free_sr->batch_no . " || ExpiryDate : " . $retailer_free_sr->expire_date . " | Qty : " . $retailer_free_sr->count ?></option>
                <?php
            }
            ?>
        </select>
        <?php
    } else if ($_REQUEST['types'] == "get_inventory_gst_rate_test") {
        $Retailer_id = $_POST['Retailer_id'];
        $inventory_item_id = $_POST['inventory_item'];
        $item_data = getproductDetailsById($inventory_item_id);
        $pricetbl = "<table class='table'>"
                . "<thead>"
                . "<tr>"
                . "<th>Distributer</th>"
                . "<th>BasicPrice</th>"
                . "</tr>"
                . "</thead>"
                . "<tbody>";
        foreach ($Retailer_id as $ddtakey => $ddtaval) {
            $retailer_name = getRetailerById($ddtaval)->name;
            $basic_price = getRetailerItemBasicPriceById($inventory_item_id, $ddtaval);
            if ($basic_price != '0.000') {
                $pricetbl .= "<tr>"
                        . "<td>" . $retailer_name . "</td>"
                        . "<td>" . $basic_price . "</td>"
                        . "</tr>";
            }
        }
        $pricetbl .= "</tbody>"
                . "</table>";
        ?>
        <p>
            <b class="red">IGST Rate : <?php echo $item_data->igst_rate; ?></b> | 
            <b class="green">SGST Rate : <?php echo $item_data->sgst_rate; ?></b> | 
            <b class="blue">CGST Rate : <?php echo $item_data->cgst_rate; ?></b>
        </p>

        <script type="text/javascript">
            document.getElementById("item_basic_price").value = "<?php echo $item_data->basic_price; ?>";
            document.getElementById('basic_price_tbl').innerHTML = "<?php echo $pricetbl; ?>"
        </script>
        <?php
        exit;
    } else if ($_REQUEST['types'] == 'getVendorDetils') {
        $vendor_id = $_POST['vendor_id'];
        $vendor_detail = getVendorDetailById($vendor_id);
        ?>

        <script>
            document.getElementById("txt_person").value = "<?php echo $vendor_detail->c_person; ?>";
            document.getElementById("txt_number").value = "<?php echo $vendor_detail->c_number; ?>";
            document.getElementById("txt_address").value = "<?php echo $vendor_detail->address; ?>";
        </script>
        <?php
    } else if ($_REQUEST['types'] == 'checkItemName') {
        $item_name = $_POST['item_name'];
        $item_data = getproductNameById($item_name);
        if ($item_data > 0) {
            echo '1';
            exit;
        } else {
            echo '2';
            exit;
        }
    } else if ($_REQUEST['types'] == 'delete_inwar_history') {
        $inward_hist_no = base64_decode($_POST['inward_no']);
        $inward_detail = getInwardedOrderNoHistory($inward_hist_no);
        if (!isset($inward_detail->id)) {
            echo '5';
            exit;
        }
        $grn_detail = getInwardedPoByGrnId($inward_detail->retailer_id, $inward_detail->grn_id);
        $inward_no = $inward_detail->grn_id;
        $retailer_id = $grn_detail->retailer_id;
        $dispatch_retailer_id = $grn_detail->dispatch_retailer_id;
        $item_desc = $grn_detail->item_desc;
        $po_no = $grn_detail->po_no;
        $billed_qty = $inward_detail->qty;

        $delete_inward_po = array();
        $delete_inward_po['po_no'] = $po_no;
        $delete_inward_po['user_id'] = $user_id;
        $delete_inward_po['hist_id'] = $inward_hist_no;
        $delete_inward_po['inward_id'] = $inward_no;
        $delete_inward_po['item_code'] = $item_desc;
        $delete_inward_po['qty'] = $billed_qty;
        $delete_inward_po['datetime'] = date("Y-m-d H:i:s");
        $insert = insert("delete_inward_po", $delete_inward_po);
        if ($insert) {
            $retailer_inward_history = array();
            $retailer_inward_history['deleted'] = 1;
            $retailer_inward_history['delete_datetime'] = date("Y-m-d H:i:s");
            $whr_retailer_inward_history = "id='$inward_hist_no' and grn_id = '$inward_no' and retailer_id = '$retailer_id'";
            $update_retailer_inward_history = update('retailer_inward_history', $retailer_inward_history, $whr_retailer_inward_history);
            if ($update_retailer_inward_history) {
                $check_stock_dup = getStockCountByItemCodeAndRetailerId($retailer_id, $item_desc);
                $check_stock_dup_f = getStockCountByItemCodeAndRetailerId($dispatch_retailer_id, $item_desc);
                $inv_master_data_f = array();
                if ($dispatch_retailer_id != 0) {
                    $inv_master_data_f['issued_stock'] = $check_stock_dup_f->issued_stock - $billed_qty;
                    $inv_master_data_f['current_stock'] = $check_stock_dup_f->current_stock + $billed_qty;
                    $whr_f = "item_code = '$item_desc' and retailer_id = '$dispatch_retailer_id'";
                    $update_f = update('retailer_inventory_master', $inv_master_data_f, $whr_f);

                    $retailer_stock_transfer_dataUpdate = array();
                    $retailer_stock_transfer_dataUpdate['status'] = 7;
                    $retailer_stock_transfer_dataUpdate['ctrl_off_flag'] = 7;
                    $retailer_stock_transfer_dataUpdate['deleted'] = 1;
                    $retailer_stock_transfer_dataUpdate['dete_datetime'] = date("Y-m-d H:i:s");
                    $where_stock_transfer_dataUpdate = "item_code='$item_desc' and order_no='$po_no' and retailer_id='$retailer_id' and frm_retailer_id='$dispatch_retailer_id'";
                    $update = update("retailer_stock_transfer", $retailer_stock_transfer_dataUpdate, $where_stock_transfer_dataUpdate);
                } else {
//                    $purchase_order_data = array();
//                    $purchase_order_data['STATUS'] = 0;
//                    $purchase_order_whr = "po_no='$po_no' AND STATUS='2'";
//                    update('purchase_order', $purchase_order_data, $purchase_order_whr);

                    $inv_master_data_update_data_whr_po_history = array();
                    $inv_master_data_update_data_whr_po_history['status'] = 2;
                    $inv_master_data_update_data_whr_po_history['deleted'] = 1;
                    $inv_master_data_update_data_whr_po_history['delete_date'] = date("Y-m-d H:i:s");
                    $update_data_whr_po_history = "retailer_id='$retailer_id' AND po_no='$po_no' AND item_code='$item_desc' and quantity='$billed_qty'";
                    update('company_inward_po_history', $inv_master_data_update_data_whr_po_history, $update_data_whr_po_history);
                }

                $inv_master_data_ret = array();
                $inv_master_data_ret['receive_stock'] = $check_stock_dup->receive_stock - $billed_qty;
                $inv_master_data_ret['current_stock'] = $check_stock_dup->current_stock - $billed_qty;
                $whr = "item_code = '$item_desc' and retailer_id = '$retailer_id'";
                $update_retailer = update('retailer_inventory_master', $inv_master_data_ret, $whr);

                $grn_billed_qty = $grn_detail->billed_qty;
                $grn_inward_qty = $grn_detail->inward_qty;
                $grn_inward_qty_pending = $grn_detail->inward_qty - $billed_qty;

                $update_inventory_grn = "id = '$inward_no' and retailer_id = '$retailer_id'";
                $update_inventory_grn_dataUpdate = array();
                $update_inventory_grn_dataUpdate['status'] = 1;
                $update_inventory_grn_dataUpdate['inward_qty'] = $grn_inward_qty_pending;
                $update_inventory_grn_dataUpdate['retailer_inwd_flg'] = 0;
                $update_inventory_grn_dataUpdate['dalete_date'] = date("Y-m-d H:i:s");
                $inventory_grn = update("inventory_grn", $update_inventory_grn_dataUpdate, $update_inventory_grn);

                $batch_no = $inward_detail->batch_no;
                if (!empty($batch_no)) {
                    $expire_date = $inward_detail->expire_date;
                    $update_item_sr_master_dataUpdate = array();
                    $update_item_sr_master_dataUpdate['status'] = 7;
                    $update_item_sr_master_dataUpdate['remarks'] = "DeleteWrongInward";
                    $update_item_sr_master_dataUpdate['update_datetime'] = date("Y-m-d H:i:s");
                    $update_item_sr_master = "item_code='$item_desc' and batch_no = '$batch_no' and expire_date='$expire_date' and grn_id='$inward_detail->grn_id' and retailer_id = '$retailer_id'";
                    $item_sr_master = updateLimit("item_sr_master", $update_item_sr_master_dataUpdate, $update_item_sr_master, $billed_qty);
                }
                if ($inventory_grn) {
                    echo '1';
                    exit;
                } else {
                    echo '2';
                    exit;
                }
            } else {
                echo '3';
            }
        } else {
            echo '101';
        }
        exit;
    } else if ($_REQUEST['types'] == 'reject_order_customer') {
        $order_no = base64_decode($_POST['order_no']);
        $reason = $_POST['reason'];
        $products = checkOrder($order_no);
        if (isset($products->order_no)) {
            $temporary_data = checkOrderTemporary($order_no);
            $order_no = $products->order_no;
            $dataUpdate = array();
            $dataUpdate['status'] = 7;
            $dataUpdate['reject_reason'] = $reason;
            $whereData = "order_no='$order_no' AND status not in ('7','8')";
            $update = update("retailer_order_master", $dataUpdate, $whereData);
            if ($update) {
                $dataUpdate_a = array();
                $dataUpdate_a['status'] = 7;
                $dataUpdate_a['reject_reason'] = $reason;
                $whereData_a = "po_no='$order_no' AND status not in ('7','8')";
                $update_1 = update("retailer_order_temporary", $dataUpdate_a, $whereData_a);
                if ($update_1) {
                    foreach ($temporary_data as $temporary_dat) {
                        $itemMsater = getRetailerItemByItemCodeRetailerId($temporary_dat->item_code, $temporary_dat->retailer_id);
                        $dataUpdate_B = array();
                        $dataUpdate_B['issued_stock'] = $itemMsater->issued_stock - $temporary_dat->qty;
                        $dataUpdate_B['current_stock'] = $itemMsater->current_stock + $temporary_dat->qty;
                        $whereData_B = "retailer_id='$temporary_dat->retailer_id' AND item_code='$temporary_dat->item_code'";
                        $update_1 = update("retailer_inventory_master", $dataUpdate_B, $whereData_B);

                        $data_item_sr_master = array();
                        $data_item_sr_master['status'] = 0;
                        $data_item_sr_master['update_datetime'] = date("Y-m-d H:i:s");
                        $whereitem_sr_master = "item_code='$temporary_dat->item_code' AND retailer_id='$temporary_dat->retailer_id' and batch_no='$temporary_dat->batch_no' and order_no='$order_no' and status='1'";
                        $updateIn = update('item_sr_master', $data_item_sr_master, $whereitem_sr_master);
                    }

                    $data_history_for_sales_rejection = array();
                    $data_history_for_sales_rejection['user_name'] = $username;
                    $data_history_for_sales_rejection['user_id'] = $user_id;
                    $data_history_for_sales_rejection['order_no'] = $order_no;
                    $data_history_for_sales_rejection['date'] = date("Y-m-d H:i:s");
                    $history_for_sales_rejection = insert('history_for_sales_rejection', $data_history_for_sales_rejection);

                    echo '1';
                    exit;
                } else {
                    echo '4';
                    exit;
                }
            } else {
                echo '3';
                exit;
            }
        } else {
            echo '2';
            exit;
        }
        exit;
    } else if ($_REQUEST['types'] == 'approveRequest') {
        $order_item_id = $_POST['order_item_id'];
        $challan_no = $_POST['challan_no'];
        $dataUpdate = array();
        $dataUpdate['user_id'] = $user_id;
        $dataUpdate['ctrl_off_flag'] = 1;
        if (!empty($challan_no)) {
            $dataUpdate['challan_no'] = $challan_no;
        }
        $dataUpdate['approve_datetime'] = date("Y-m-d H:i:s");
        $whereCondition = "ctrl_off_flag='0' and id='$order_item_id'";
        $update = update("retailer_stock_transfer", $dataUpdate, $whereCondition);
        if ($update) {
            echo '1';
            exit;
        } else {
            echo '2';
            exit;
        }
    } else if ($_REQUEST['types'] == 'rejectRequest') {
        $order_item_id = $_POST['order_item_id'];
        $stock_detail = getApproveStockRequestById($order_item_id);
        $dataUpdate = array();
        $dataUpdate['ctrl_off_flag'] = 7;
        $dataUpdate['user_id'] = $user_id;
        $dataUpdate['approve_datetime'] = date("Y-m-d H:i:s");
        $whereCondition = "ctrl_off_flag='0' and id='$order_item_id'";
        $update = update("retailer_stock_transfer", $dataUpdate, $whereCondition);
        if ($update) {
            $data_item_sr_master = array();
            $data_item_sr_master['status'] = 0;
            $data_item_sr_master['update_datetime'] = date("Y-m-d H:i:s");
            $whereitem_sr_master = "item_code='$stock_detail->item_code' AND retailer_id='$stock_detail->frm_retailer_id' and batch_no='$stock_detail->batch_no' and status='8'";
            $updateLimit = updateLimit('item_sr_master', $data_item_sr_master, $whereitem_sr_master, round($stock_detail->req_qty));
            if ($updateLimit) {
                echo '1';
                exit;
            } else {
                echo '3';
                exit;
            }
        } else {
            echo '2';
            exit;
        }
    } else if ($_REQUEST['types'] == 'approveTransaction') {
        $order_item_id = $_POST['order_item_id'];
        $verify = $_POST['verify_data'];
        if (empty($verify)) {
            echo '3';
            exit;
        }

        $remarks = $_POST['remarks'];
        $dataUpdate = array();
        $dataUpdate['user_id'] = $user_id;
        $dataUpdate['status'] = $verify;
        $dataUpdate['remarks'] = $remarks;
        $dataUpdate['approve_datetime'] = date("Y-m-d H:i:s");
        $whereCondition = "id='$order_item_id'";
        $update = update("transaction_details", $dataUpdate, $whereCondition);
        if ($update) {
            echo '1';
            exit;
        } else {
            echo '2';
            exit;
        }
    } else if ($_REQUEST['types'] == 'approveExpense') {
        $order_item_id = $_POST['order_item_id'];
        $verify = $_POST['verify_data'];
        if (empty($verify)) {
            echo '3';
            exit;
        }

        $remarks = $_POST['remarks'];
        $dataUpdate = array();
        $dataUpdate['user_id'] = $user_id;
        $dataUpdate['status'] = $verify;
        $dataUpdate['account_remarks'] = $remarks;
        $dataUpdate['approve_datetime'] = date("Y-m-d H:i:s");
        $whereCondition = "id='$order_item_id'";
        $update = update("expense_details", $dataUpdate, $whereCondition);
        if ($update) {
            echo '1';
            exit;
        } else {
            echo '2';
            exit;
        }
    } else {
        
    }
}
?> 