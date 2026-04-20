<?php
session_start();
error_reporting(0);
require_once 'includes/common_function.php';

date_default_timezone_set('Asia/Kolkata');


extract($_POST);

$retailer_id = $_SESSION['id'];
$user_id = $_SESSION['user_id'];
$company_id = $_SESSION['company_id'];
$bdm_id = $_SESSION['bdm_id'];


if (isset($_POST['request_type'])) {
    if ($_POST['request_type'] == 'delete_return_po') {
        $id = $_POST['id'];
        $cartdata = getReturnPurchaseOrderDetailsById($id);
        $cartdata_count = getReturnPurchaseOrderDetailsByIdActive($id);
        if (isset($cartdata->id)) {
            $retailer_id = $cartdata->retailer_id;
            $current_stock = getCurrentStockByRetailerIdAndItemCode($retailer_id, $cartdata->item_id);
            $issued_stock = getIssuedStockByRetailerIdAndItemCode($retailer_id, $cartdata->item_id);

            $stck_arr = array();
            $stck_arr['issued_stock'] = ($issued_stock - $cartdata->qty);
            $stck_arr['current_stock'] = ($current_stock + $cartdata->qty);
            $stckwhr = "item_code = '" . $cartdata->item_id . "' and retailer_id = '$retailer_id'";
            $updstck = update('retailer_inventory_master', $stck_arr, $stckwhr);
            // for update in item_sr_master table=========================
            if ($updstck) {
                $itemsr_arr = array();
                $itemsr_arr['status'] = 0;
                $itemsr_arr['block_datetime'] = date('Y-m-d H:i:s');
                $itemsr_arr['remarks'] = "Free AS PER RETURN PO AT " . date('Y-m-d H:i:s');
                $whr_blck = "batch_no = '" . $cartdata->batch_no . "' and retailer_id = '$retailer_id' and status = '7' and item_code = '" . $cartdata->item_id . "'";
                $upd_item_srtbl = updateIn('item_sr_master', $itemsr_arr, $whr_blck, $cartdata->qty);
                if ($upd_item_srtbl) {
                    if ($cartdata_count <= 1) {
                        $upd_arr = array();
                        $upd_arr['`status`'] = 7;
                        $upd_arr['`delete`'] = 1;
                        $whrr = "id='$cartdata->id'";
                        $purchase_order_return_detail = update("purchase_order_return", $upd_arr, $whrr);
                    }
                    $upd_arr = array();
                    $upd_arr['`status`'] = 7;
                    $upd_arr['`delete`'] = 1;
                    $whrr = "unique_id='$cartdata->unique_id'";
                    $purchase_order_return_detail = update("purchase_order_return_detail", $upd_arr, $whrr);
                    if ($purchase_order_return_detail) {
                        echo '1';
                    } else {
                        echo '0';
                    }
                } else {
                    echo '0';
                }
            } else {
                echo '0';
            }
        } else {
            echo '0';
        }
    }
    if ($_POST['request_type'] == 'getSkuDetails') {
        $id = $_POST['id'];
        echo $uom = getItemUOMByItemCode($id);
    }
    if ($_POST['request_type'] == 'getCurrentItemStock') {
        $id = $_POST['id'];
        $Retailer_id = $_POST['retailer_id'];
        echo getRetailerItemCurrentStockById($id, $Retailer_id);
    }

    if ($_POST['request_type'] == 'getVendorDetils') {
        $vendor_id = $_POST['vendor_id'];
        $vendor_detail = getVendorDetailById($vendor_id);
        ?>

        <script>
            document.getElementById("txt_person").value = "<?php echo $vendor_detail->c_person; ?>";
            document.getElementById("txt_number").value = "<?php echo $vendor_detail->c_number; ?>";
            document.getElementById("txt_address").value = "<?php echo $vendor_detail->address; ?>";
        </script>
        <?php
    }


    if ($_POST['request_type'] == 'insert_to_temp_table') {

        $input_qty = $_POST['qty'];
//        if (is_int($input_qty) != 1) {
//            echo '33';
//            exit;
//        }
//        $input_qty = preg_replace("/[^0-9]/", "", $input_qty);
        if (ltrim(date('m')) > 3) {
            $cd = date('y');
            $dd = $cd + 1;
        } else {
            $dd = date('y');
            $cd = $dd - 1;
        }
        $fin_year = $cd . '' . $dd;


        $inc_no = getLastIncNo($fin_year, $_SESSION['id']);

        if ($inc_no == '0' || $inc_no == 0) {
            $inc_no = 1;
        } else {
            $inc_no = $inc_no + 1;
        }
        if ($batch_wise_sale == 1) {
            if ($company_id == 3) {
                $freebatchCount = getBatchNumberFreeItemsUA($_SESSION['id'], $_POST['txt_batch_no'], $_POST['item_code']);
            } else {
                $freebatchCount = getBatchNumberFreeItemsUA($_SESSION['id'], $_POST['txt_batch_no'], $_POST['item_code']);
//                $freebatchCount = getBatchNumberFreeItems($_SESSION['id'], $_POST['txt_batch_no'], $_POST['item_code']);
            }
            if ($freebatchCount < $input_qty) {
                echo '3';
                exit;
            }
        }
        $item_detail = getRetailerItemCurentQty($_SESSION['id'], $_POST['item_code']);
        $dup_check = getDuplicateOrderCount($fin_year, $_SESSION['id'], $_POST['item_code'], $_POST['txt_batch_no']);
        if ($dup_check == '0') {
            $purchase_no = getItemSrInwardNoByBatchNo($_SESSION['id'], $_POST['txt_batch_no'], $_POST['item_code']);
            $total = $item_detail->total;
            $sgst_rate = $item_detail->sgst_rate;
            $sgst_value = $item_detail->sgst_value;
            $cgst_rate = $item_detail->cgst_rate;
            $cgst_value = $item_detail->cgst_value;
            $basic_price = $item_detail->basic_price;
            $value['retailer_id'] = $_SESSION['id'];
            $value['use_in_crop'] = $_POST['crop'];
            $value['batch_no'] = $_POST['txt_batch_no'];
            $value['po_no'] = $_POST['po_no'];
            $value['inc_no'] = $inc_no;
            $value['purchase_no'] = $purchase_no;
            $value['fin_year'] = $fin_year;
            $value['item_name'] = $_POST['item_desc'];
            $value['item_code'] = $_POST['item_code'];
            $value['price'] = $total * $input_qty;
            $value['basic'] = $basic_price * $input_qty;
            $value['cgst'] = $cgst_value * $input_qty;
            $value['sgst'] = $sgst_value * $input_qty;
            $value['price'] = $total * $input_qty;
            $value['sgst_rate'] = $sgst_rate;
            $value['cgst_rate'] = $cgst_rate;
            $value['qty'] = $input_qty;
            if ($_POST['invoice_date'] == 1) {
                $value['order_place_date'] = date('Y-m-d H:i:s');
                $value['cart_date'] = date('Y-m-d H:i:s');
            } else {
                $value['order_place_date'] = date('Y-m-d H:i:s');
                $value['cart_date'] = date('Y-m-d H:i:s');
//                $value['order_place_date'] = date("Y-m-d", strtotime($_POST['invoice_date']));
            }
            $value['order_status'] = 0;
            $value['uom'] = $_POST['uom'];

            $table = 'retailer_order_temporary';

            $q = insert($table, $value);

            echo '1';
        } else {
            echo '0';
        }
    }

    if ($_POST['request_type'] == 'insert_to_temp_table_b2b') {

        $input_qty = $_POST['qty'];
//        if (!is_int($input_qty)) {
//            echo '33';
//            exit;
//        }
//        $input_qty = preg_replace("/[^0-9]/", "", $input_qty);
        if (ltrim(date('m')) > 3) {
            $cd = date('y');
            $dd = $cd + 1;
        } else {
            $dd = date('y');
            $cd = $dd - 1;
        }
        $fin_year = $cd . '' . $dd;
        $fin_year_with = $cd . '-' . $dd;


        $inc_no = getLastIncNob2b($fin_year, $_SESSION['id']);

        if ($inc_no == '0' || $inc_no == 0) {
            $inc_no = 1;
        } else {
            $inc_no = $inc_no + 1;
        }
        if ($batch_wise_sale == 1) {
            if ($company_id == 3) {
                $freebatchCount = getBatchNumberFreeItemsUA($_SESSION['id'], $_POST['txt_batch_no'], $_POST['item_code']);
            } else {
                $freebatchCount = getBatchNumberFreeItemsUA($_SESSION['id'], $_POST['txt_batch_no'], $_POST['item_code']);
//                $freebatchCount = getBatchNumberFreeItems($_SESSION['id'], $_POST['txt_batch_no'], $_POST['item_code']);
            }

            if ($freebatchCount < $input_qty) {
                echo '3';
                exit;
            }
        }
        $item_detail = getRetailerItemCurentQty($_SESSION['id'], $_POST['item_code']);
        $dup_check = getDuplicateOrderCount($fin_year, $_SESSION['id'], $_POST['item_code'], $_POST['txt_batch_no']);

        if ($dup_check == '0') {
            $purchase_no = getItemSrInwardNoByBatchNo($_SESSION['id'], $_POST['txt_batch_no'], $_POST['item_code']);
            $total = $item_detail->total;
            $sgst_rate = $item_detail->sgst_rate;
            $sgst_value = $item_detail->sgst_value;
            $cgst_rate = $item_detail->cgst_rate;
            $cgst_value = $item_detail->cgst_value;
            $basic_price = $item_detail->basic_price;
            $value['retailer_id'] = $_SESSION['id'];
            $value['batch_no'] = $_POST['txt_batch_no'];
            $value['use_in_crop'] = $_POST['crop'];
            $value['po_no'] = $_POST['po_no'];
            $value['inc_no'] = $inc_no;
            $value['purchase_no'] = $purchase_no;
            $value['fin_year'] = $fin_year;
            $value['item_name'] = $_POST['item_desc'];
            $value['item_code'] = $_POST['item_code'];
            $value['price'] = $total * $input_qty;
            $value['basic'] = $basic_price * $input_qty;
            $value['cgst'] = $cgst_value * $input_qty;
            $value['sgst'] = $sgst_value * $input_qty;
            $value['price'] = $total * $input_qty;
            $value['sgst_rate'] = $sgst_rate;
            $value['cgst_rate'] = $cgst_rate;
            $value['qty'] = $input_qty;
            if ($_POST['invoice_date'] == 1) {
                $value['order_place_date'] = date('Y-m-d H:i:s');
                $value['cart_date'] = date('Y-m-d H:i:s');
            } else {
                $value['cart_date'] = date('Y-m-d H:i:s');
                $value['order_place_date'] = date("Y-m-d H:i:s", strtotime($_POST['invoice_date']));
            }
            $value['order_status'] = 0;
            $value['uom'] = $_POST['uom'];

            $table = 'retailer_order_temporary';

            $q = insert($table, $value);

            echo '1';
        } else {
            echo '0';
        }
    }

    if ($_POST['request_type'] == 'get_availability_for_all_store') {
        $item_code = $_POST['item_code'];
        $zone_id = $_SESSION['zone_id'];
        if ($zone_id != 0) {
            $zone_retailer_masters = getRetailerDataByZoneId($zone_id);
            foreach ($zone_retailer_masters as $zone_retailer_master) {
                $item_data = getItemDetailByCode($item_code, $zone_retailer_master->id);
                $stock_available = 0;
                if (!empty($item_data->current_stock)) {
                    $stock_available = $item_data->current_stock;
                }
                if ($zone_retailer_master->id != $_SESSION['id']) {
                    ?>
                    <div class="col-md-12 marg_tp_one">
                        <label>AvailableStock - <?php echo $zone_retailer_master->name; ?></label> <span class='text-red'></span>
                        <input type="text" class="form-control input-sm txt_cls_stock" value="<?php echo $stock_available; ?>" readonly>
                    </div>
                    <?php
                }
            }
        }
    }

    if ($_POST['request_type'] == 'get_availability') {
        $item_code = $_POST['item_code'];
        $array = array();
        $sr_no_array = array();
        $item_data = getItemDetailByCode($item_code, $_SESSION['id']);
        $item_data_unit = getItemUnitByItemCodeObject($item_code);
        if ($company_id == 3) {
            $sr_no_array['sr_no'] = getFreeSerielNoByRetailerItemVerde($item_code, $_SESSION['id']);
            $obj_merged = (object) array_merge((array) $item_data, (array) $sr_no_array);
            $obj_merged = (object) array_merge((array) $obj_merged, (array) $item_data_unit);
        } else {
            $sr_no_array['sr_no'] = getFreeSerielNoByRetailerItemVerde($item_code, $_SESSION['id']);
//            $sr_no_array['sr_no'] = getFreeSerielNoByRetailerItem($item_code, $_SESSION['id']);
            $obj_merged = (object) array_merge((array) $item_data, (array) $sr_no_array);
            $obj_merged = (object) array_merge((array) $obj_merged, (array) $item_data_unit);
        }
        echo json_encode($obj_merged);
    }

    if ($_POST['request_type'] == 'get_mobiledetails') {
        $cus_ph = $_POST['cus_ph'];
        $customer_data = getBookSaleByMobileNo($cus_ph);
        echo json_encode($customer_data);
    }

    if ($_POST['request_type'] == 'get_auto_refresh_list') {
        $get_temp_item_list = getTempItemList($_SESSION['id']);
        $i = 1;
        $total_amt = 0;
        $data = '';
        foreach ($get_temp_item_list as $value) {

            $data = $data . '<tr>
                <td align="center">' . $i . '</td>
                <td>' . $value->item_name . '</td>
                <td align="center"><input type="hidden" class="trn_id_1" value="' . $value->tr_id . '">' . $value->qty . '</td>
                <td align="right">' . IND_money_format($value->price / $value->qty) . '</td>
                <td align="right">' . IND_money_format($value->price) . '</td>
                <td align="right">' . $value->batch_no . '</td>
                <td align="right">' . $value->use_in_crop . '</td>
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
        echo getTotalPriceFrmTempTbl($_SESSION['id']);
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
        $pending_amt = $_POST['pending_amt'];

        // $q = mysqli_query($conn, "SELECT count(*) as count, sum(qty) as qty from small_inventory_order_temporary where order_status=0 and dealer_code='" . $_SESSION['dealer_code'] . "' and po_no='" . $r['po_no'] . "' ");
        $var = getTempTableDetailsByRetailerIdAndPoNo($_SESSION['id'], $_POST['po_no']);
        // $var = mysqli_fetch_array($q);

        $count = $var->count;
        $qty = $var->qty;


        $inc_no = $var->inc_no;

        $var_dt_check = getOredrDetailsByRetailerIdAndPoNo($_SESSION['id'], $_POST['po_no']);
        foreach ($var_dt_check as $data_one_check) {
            $qty = $data_one_check->qty;
            $item_code = $data_one_check->item_code;
            $batch_no = $data_one_check->batch_no;
            $retailerid = $_SESSION['id'];
            if ($batch_wise_sale == 1) {
                if ($company_id == 3) {
                    $freebatchCount = getBatchNumberFreeItemsVerde($_SESSION['id'], $batch_no, $item_code);
                } else {
                    $freebatchCount = getBatchNumberFreeItemsVerde($_SESSION['id'], $batch_no, $item_code);
//                    $freebatchCount = getBatchNumberFreeItems($_SESSION['id'], $batch_no, $item_code);
                }
                if ($freebatchCount < $qty) {
                    echo '4';
                    exit;
                }
            }
        }
        if ($amount == 0) {
            echo 0;
        } else if ($count == 0) {
            echo 0;
        } else {
            if (ltrim(date('m')) > 3) {
                $cd = date('y');
                $dd = $cd + 1;
            } else {
                $dd = date('y');
                $cd = $dd - 1;
            }
            $fin_year = $cd . "" . $dd;

            // $inc_no = getLastIncNo($fin_year, $_SESSION['id']);
            // if ($inc_no == 0) {
            //     $inc_no = 1;
            // } else {
            //     $inc_no = $inc_no + 1;
            // }


            $null_var = NULL;
            $insertMaster = array();
            $insertMaster['inc_no'] = $inc_no;
            $insertMaster['retailer_id'] = $_SESSION['id'];
            $insertMaster['company_id'] = getRetailerCompanyIdById($_SESSION['id']);
            if ($_POST['invoice_date'] == 1) {
                $insertMaster['added_date'] = date("Y-m-d");
            } else {
                $insertMaster['added_date'] = $_POST['invoice_date'];
            }
            $insertMaster['added_datetime'] = date("Y-m-d H:i:s");
            $insertMaster['order_no'] = $_POST['po_no'];
            $insertMaster['po_no'] = $_POST['po_no'];
            $insertMaster['payment_type'] = $_POST['cus_payment_method'];
            if (isset($_POST['cus_payment_method']) && $_POST['cus_payment_method'] != 0) {
                $insertMaster['transaction_no'] = $_POST['transaction_no'];
            }
            $insertMaster['total_price'] = trim($_POST['amount']);
            $insertMaster['total_count'] = $qty;
            $insertMaster['pending_amount'] = $pending_amt;
            if ($_POST['whatsapp_no'] == 1) {
                $insertMaster['whatsapp_no'] = 1;
            }
            $insertMaster['fin_year'] = $fin_year;
            $insertMaster['cus_name'] = $_POST['cus_name'];
            $insertMaster['cus_village'] = $_POST['cus_village'];
            $insertMaster['cus_ph'] = $_POST['cus_ph'];
            $insertMaster['cus_add'] = $_POST['cus_add'];
            $insertMaster['order_remark'] = $_POST['remark'];

            if (isset($_POST['cus_adhar']) && !empty($_POST['cus_adhar'])) {
                $insertMaster['cus_adhar'] = $_POST['cus_adhar'];
            }
            if ($_POST['discount_checkbx'] == 1 && $_POST['cupon_status'] == 1) {
                $insertMaster['coupon_code'] = $_POST['couponcode'];
            }
            $insrt = true;
            $insrt = insert("retailer_order_master", $insertMaster);
        }

        if ($insrt) {

            // update cupon status========
            if ($_POST['discount_checkbx'] == 1 && $_POST['cupon_status'] == 1) {
                $cupon = array();
                $cupon['status'] = 1;
                $cupon['coupon_used_date'] = date('Y-m-d H:i:s');
                $cupon['coupon_used_order_no'] = $_POST['po_no'];
                $cup_whr = "discount_code='" . $_POST['couponcode'] . "' and retailer_id = '" . $_SESSION['id'] . "'";
                update('tbl_discount_coupon', $cupon, $cup_whr);
            }


            /// FOR STOCK MINUS====================================================
            $var_dt = getOredrDetailsByRetailerIdAndPoNo($_SESSION['id'], $_POST['po_no']);
            foreach ($var_dt as $data_one) {
                $qty = $data_one->qty;
                $item_code = $data_one->item_code;
                $batch_no = $data_one->batch_no;
                $retailerid = $_SESSION['id'];
                if ($company_id == 3) {
                    $freebatch_data = getBatchNumberFreeItemsDetail($_SESSION['id'], $batch_no, $item_code);
                } else {
                    $freebatch_data = getBatchNumberFreeItemsDetail($_SESSION['id'], $batch_no, $item_code);
//                    $freebatchCount = getBatchNumberFreeItems($_SESSION['id'], $batch_no, $item_code);
                }
                $freebatchCount = $freebatch_data->cf;
                if ($freebatchCount >= $qty) {
                    $sale_qty_input = numberDecimal($qty);
                    $sale_qty_input_array = explode(".", $sale_qty_input);

                    $sale_qty_input_1 = $sale_qty_input_array[0];
                    $sale_qty_input_2 = numberDecimal("0." . $sale_qty_input_array[1]);
                    if ($sale_qty_input_1 > 0) {
                        $srUpdate = array();
                        $srUpdate['status'] = 1;
                        $srUpdate['sale_qty'] = 1;
                        $srUpdate['update_datetime'] = date("Y-m-d H:i:s");
                        $srUpdate['order_no'] = $_POST['po_no'];
                        $srUpdateWhere = "item_code='$item_code' AND STATUS='0' AND retailer_id='$retailerid' AND batch_no='$batch_no' and partial=0";
                        $updateIn = TRUE;
                        $updateIn = updateIn('item_sr_master', $srUpdate, $srUpdateWhere, $sale_qty_input_1);
                        if ($updateIn) {
                            $item_sale_master_decimal_1 = array();
                            $item_sale_master_decimal_1['retailer_id'] = $retailer_id;
                            $item_sale_master_decimal_1['purchase_basic'] = $freebatch_data->purchase_basic;
                            $item_sale_master_decimal_1['gst'] = $freebatch_data->gst;
                            $item_sale_master_decimal_1['total'] = $freebatch_data->total;
                            $item_sale_master_decimal_1['company_id'] = $company_id;
                            $item_sale_master_decimal_1['order_no'] = $_POST['po_no'];
                            $item_sale_master_decimal_1['sale_qty'] = $sale_qty_input_1;
                            $item_sale_master_decimal_1['item_code'] = $item_code;
                            $item_sale_master_decimal_1['batch_no'] = $batch_no;
                            $item_sale_master_decimal_1['expire_date'] = $freebatch_data->expire_date;
                            $item_sale_master_decimal_1['sale_date'] = date("Y-m-d H:i:s");
                            $item_sale_master_decimal_1['status'] = 1;
                            insert("item_sale_master_decimal", $item_sale_master_decimal_1);
                        }
                    }
                    if ($sale_qty_input_2 > 0) {
                        $FreeSr_noBybatch = getFreeSr_noBybatch($_SESSION['id'], $batch_no, $item_code, $sale_qty_input_2);
                        if (isset($FreeSr_noBybatch->serial_number)) {
                            $serial_number = $FreeSr_noBybatch->serial_number;
                            $serial_qty = $FreeSr_noBybatch->qty;
                            $sale_qty = $FreeSr_noBybatch->sale_qty;
                            $batch_no = $FreeSr_noBybatch->batch_no;
                            $company_id = $FreeSr_noBybatch->company_id;
                            $purchase_basic = $FreeSr_noBybatch->purchase_basic;
                            $company_id = $FreeSr_noBybatch->company_id;
                            $gst = $FreeSr_noBybatch->gst;
                            $total = $FreeSr_noBybatch->total;
                            $expire_date = $FreeSr_noBybatch->expire_date;

                            $srUpdate = array();
                            $srUpdate['sale_qty'] = numberDecimal($sale_qty + $sale_qty_input_2);
                            $srUpdate['update_datetime'] = date("Y-m-d H:i:s");
                            $srUpdate['order_no'] = $_POST['po_no'];
                            if (numberDecimal($serial_qty - $sale_qty_input_2) == 0.00) {
                                $srUpdate['status'] = 1;
                                $srUpdate['qty'] = 1;
                            } else {
                                $srUpdate['qty'] = numberDecimal($serial_qty - $sale_qty_input_2);
                            }
                            $srUpdate['partial'] = 1;

                            $srUpdateWhere = "serial_number='$serial_number' and item_code='$item_code' AND STATUS='0' AND retailer_id='$retailerid' AND batch_no='$batch_no'";
                            $updateDecimal = true;
                            $updateDecimal = update('item_sr_master', $srUpdate, $srUpdateWhere);
                            if ($updateDecimal) {
                                $item_sale_master_decimal = array();
                                $item_sale_master_decimal['retailer_id'] = $retailer_id;
                                $item_sale_master_decimal['purchase_basic'] = $purchase_basic;
                                $item_sale_master_decimal['gst'] = $gst;
                                $item_sale_master_decimal['total'] = $total;
                                $item_sale_master_decimal['company_id'] = $company_id;
                                $item_sale_master_decimal['order_no'] = $_POST['po_no'];
                                $item_sale_master_decimal['serial_number'] = $serial_number;
                                $item_sale_master_decimal['sale_qty'] = $sale_qty_input_2;
                                $item_sale_master_decimal['item_code'] = $item_code;
                                $item_sale_master_decimal['batch_no'] = $batch_no;
                                $item_sale_master_decimal['expire_date'] = $expire_date;
                                $item_sale_master_decimal['sale_date'] = date("Y-m-d H:i:s");
                                $item_sale_master_decimal['status'] = 1;
                                insert("item_sale_master_decimal", $item_sale_master_decimal);
                            }
                        }
                    }
                }

                $retailer_item_qty = getRetailerItemCurentQty($_SESSION['id'], $item_code);
                $ttl_qty_crt = $retailer_item_qty->current_stock - $qty;
                $ttl_qty_issu = $retailer_item_qty->issued_stock + $qty;

                $upd_ar = array(
                    'current_stock' => $ttl_qty_crt,
                    'issued_stock' => $ttl_qty_issu
                );
                $where = "retailer_id = '$retailerid' and item_code = '$item_code'";
                $upd = update('retailer_inventory_master', $upd_ar, $where);

                $q_1 = mysqli_query($conn, "UPDATE retailer_order_temporary set order_place_date='" . date("Y-m-d H:i:s") . "', stock_flg = '1' where retailer_id = '$retailerid'
                and item_code = '$item_code' and stock_flg = '0'");
            }
            /// FOR STOCK MINUS====================================================
//            echo 'hi1';
//            exit;

            $q = mysqli_query($conn, "UPDATE retailer_order_temporary set order_place_date='" . date("Y-m-d H:i:s") . "', order_status=1 where retailer_id='" . $_SESSION['id'] . "'
            and po_no='" . $_POST['po_no'] . "'");
            echo 3;
        } else {
            echo 5;
        }
    }

    // for cupon code check ================================
    if ($_POST['request_type'] == 'check_cupon_code') {
        $cupon = $_POST['cupon'];

        $check = getCuponeCodeStatus($cupon, $_SESSION['id']);
        echo $rr = json_encode($check);
    }
    // for cupon code check ================================

    if ($_POST['request_type'] == 'get_bank_retailer_selection') {
        $transfer_mode = $_POST['transfer_mode'];
        if (!empty($transfer_mode) && $transfer_mode != 0) {
            if ($transfer_mode == 1) {
                $companies = getBankList($company_id);
                echo '<option value="">--Select Bank--</option>';
                foreach ($companies as $company) {
                    ?>
                    <option value="<?php echo $company->id; ?>"><?php echo $company->bank_name; ?></option>
                    <?php
                }
            } else {
                $retailers = getAllRetailerData($retailer_id);
                echo '<option value="">--Select Retailer--</option>';
                foreach ($retailers as $retailer) {
                    ?>
                    <option value="<?php echo $retailer->id; ?>"><?php echo $retailer->name; ?></option>
                    <?php
                }
            }
        }
    }

    if ($_POST['request_type'] == 'retailer_item_by_id') {
        $retailer_id = $_POST['retailer_id'];

        $data = getRetailerMasterDataById($retailer_id);

        $html = "<option> -- Select Item --</option>";
        foreach ($data as $row) {
            $html .= "<option value='" . $row->item_id . "'>" . $row->item_desc . " ( " . $row->current_stock . " ) </option>";
        }
        $html = $html;
        echo $html;
    }

    if ($_POST['request_type'] == 'delete_stock_trans_data') {
        $id = $_POST['id'];

        $del = delete('retailer_stock_transfer', "id='$id'");
        if ($del) {
            echo '0';
        } else {
            echo '1';
        }
    }

    if ($_POST['request_type'] == 'confirm_stock_tras_request') {
        $retailer_id = $_POST['retailer_id'];
        $transferPendings = getTransferPendingData($retailer_id);
        foreach ($transferPendings as $transferPending) {
            $data_item_sr_master = array();
            $data_item_sr_master['status'] = 8;
            $data_item_sr_master['block_datetime'] = date("Y-m-d H:i:s");
            $data_item_sr_master['block_for'] = $transferPending->frm_retailer_id;
            $whereitem_sr_master = "item_code='$transferPending->item_code' AND retailer_id='$transferPending->frm_retailer_id' and batch_no='$transferPending->batch_no' and status='0'";
            $limit_item_sr_master = "$transferPending->req_qty";
            $updateIn = updateIn('item_sr_master', $data_item_sr_master, $whereitem_sr_master, $limit_item_sr_master);
        }
        $upd = array();
        $upd['status'] = '1';
        $upd['order_no'] = time();
        $upd['add_date'] = date('Y-m-d');
        $upd['add_datetime'] = date('Y-m-d H:i:s');
        $whre = "retailer_id = '$retailer_id' and status = '0' and ctrl_off_flag = '0'";
        $res = update('retailer_stock_transfer', $upd, $whre);
        if ($res) {
            echo '0';
        } else {
            echo '1';
        }
    }

    if ($_POST['request_type'] == 'get_retailers_by_item_id') {
        $item_id = $_POST['item_id'];
        $html = "<option value=''>-- Select Retailer --</option>";
        $data = getRetailerStockByItemId($item_id, $_SESSION['company_id']);
        if (!empty($data)) {
            foreach ($data as $roa) {
                $retname = getRetailerDataById($roa->retailer_id)->name;
                $cur_stck = getCurrentStockByRetailerIdAndItemId($roa->retailer_id, $item_id);
                $html .= "<option value='" . $roa->retailer_id . "'>" . $retname . " ( " . $cur_stck . " ) </option>";
            }
        }
        echo $html;
    }
    if ($_POST['request_type'] == 'get_retailers_batch_no_by_item_id') {
        $item_id = $_POST['item_id'];
        $item_code = getItemCodeByItemId($item_id);
        $retailer_id = $_POST['retailer_id'];
        $html = "<option value=''>-- Select BatchNo - ExpiryDate - CurrrentStock--</option>";
        if ($company_id == 3) {
            $data = getFreeSerielNoByRetailerItemUA($item_code, $retailer_id);
        } else {
            $data = getFreeSerielNoByRetailerItem($item_code, $retailer_id);
        }
        if (!empty($data)) {
            foreach ($data as $roa) {
                $html .= "<option value='" . $roa->batch_no . "'>" . $roa->batch_no . " - " . $roa->expire_date . " ( " . $roa->cf . " ) </option>";
            }
        }
        echo $html;
    }

    if ($_POST['request_type'] == 'confirm_order_btob') {
        $amount = $_POST['amount'];

        // $q = mysqli_query($conn, "SELECT count(*) as count, sum(qty) as qty from small_inventory_order_temporary where order_status=0 and dealer_code='" . $_SESSION['dealer_code'] . "' and po_no='" . $r['po_no'] . "' ");
        $var = getTempTableDetailsByRetailerIdAndPoNo($_SESSION['id'], $_POST['po_no']);
        // $var = mysqli_fetch_array($q);

        $count = $var->count;
        $qty = $var->qty;
        $inc_no = $var->inc_no;

        $var_dt_check = getOredrDetailsByRetailerIdAndPoNo($_SESSION['id'], $_POST['po_no']);
        foreach ($var_dt_check as $data_one_check) {
            $qty = $data_one_check->qty;
            $item_code = $data_one_check->item_code;
            $batch_no = $data_one_check->batch_no;
            $retailerid = $_SESSION['id'];
            if ($batch_wise_sale == 1) {
                $freebatchCount = getBatchNumberFreeItemsUA($_SESSION['id'], $batch_no, $item_code);
//                $freebatchCount = getBatchNumberFreeItems($_SESSION['id'], $batch_no, $item_code);
                if ($freebatchCount < $qty) {
                    echo '4';
                    exit;
                }
            }
        }

        if ($amount == 0) {
            echo 0;
        } else if ($count == 0) {
            echo 0;
        } else {
            if (ltrim(date('m')) > 3) {
                $cd = date('y');
                $dd = $cd + 1;
            } else {
                $dd = date('y');
                $cd = $dd - 1;
            }
            $fin_year = $cd . "" . $dd;

            // $inc_no = getLastIncNo($fin_year, $_SESSION['id']);
            // if ($inc_no == 0) {
            //     $inc_no = 1;
            // } else {
            //     $inc_no = $inc_no + 1;
            // }


            $null_var = NULL;
            $insertMaster = array();
            $insertMaster['inc_no'] = $inc_no;
            $insertMaster['company_id'] = $company_id;
            $insertMaster['retailer_id'] = $_SESSION['id'];
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
            $insertMaster['ship_cus_name'] = $_POST['ship_cus_name'];
            $insertMaster['cus_ph'] = $_POST['cus_ph'];
            $insertMaster['ship_cus_ph'] = $_POST['ship_cus_ph'];
            $insertMaster['cus_add'] = $_POST['cus_add'];
            $insertMaster['ship_cus_add'] = $_POST['ship_cus_add'];
            $insertMaster['gstin_no'] = $_POST['cus_pan'];
            $insertMaster['ship_gstin_no'] = $_POST['ship_cus_pan'];
            $insertMaster['cus_pin'] = $_POST['cus_pin'];
            $insertMaster['ship_cus_pin'] = $_POST['ship_cus_pin'];
            $insertMaster['b2b_flg'] = 1;
            $insertMaster['order_remark'] = $_POST['remark'];
            //             print_r($insertMaster);
            //             exit; 
            $insrt = insert("retailer_order_master", $insertMaster);
        }

        if ($insrt) {
            /// FOR STOCK MINUS====================================================
            $var_dt = getOredrDetailsByRetailerIdAndPoNo($_SESSION['id'], $_POST['po_no']);
            foreach ($var_dt as $data_one) {
                $qty = $data_one->qty;
                $item_code = $data_one->item_code;
                $retailerid = $_SESSION['id'];

                $qty = $data_one->qty;
                $item_code = $data_one->item_code;
                $batch_no = $data_one->batch_no;
                $retailerid = $_SESSION['id'];
//                $freebatchCount = getBatchNumberFreeItems($_SESSION['id'], $batch_no, $item_code);
                $freebatch_data = getBatchNumberFreeItemsDetail($_SESSION['id'], $batch_no, $item_code);
                $freebatchCount = $freebatch_data->cf;
                if ($freebatchCount >= $qty) {
                    $sale_qty_input = numberDecimal($qty);
                    $sale_qty_input_array = explode(".", $sale_qty_input);

                    $sale_qty_input_1 = $sale_qty_input_array[0];
                    $sale_qty_input_2 = numberDecimal("0." . $sale_qty_input_array[1]);
                    if ($sale_qty_input_1 > 0) {
                        $srUpdate = array();
                        $srUpdate['status'] = 1;
                        $srUpdate['qty'] = 0;
                        $srUpdate['sale_qty'] = 1;
                        $srUpdate['update_datetime'] = date("Y-m-d H:i:s");
                        $srUpdate['order_no'] = $_POST['po_no'];
                        $srUpdateWhere = "item_code='$item_code' AND STATUS='0' AND retailer_id='$retailerid' AND batch_no='$batch_no' and partial=0";
                        $updateIn = updateIn('item_sr_master', $srUpdate, $srUpdateWhere, $sale_qty_input_1);
                        if ($updateIn) {
                            $item_sale_master_decimal_1 = array();
                            $item_sale_master_decimal_1['retailer_id'] = $retailer_id;
                            $item_sale_master_decimal_1['purchase_basic'] = $freebatch_data->purchase_basic;
                            $item_sale_master_decimal_1['gst'] = $freebatch_data->gst;
                            $item_sale_master_decimal_1['total'] = $freebatch_data->total;
                            $item_sale_master_decimal_1['company_id'] = $company_id;
                            $item_sale_master_decimal_1['order_no'] = $_POST['po_no'];
                            $item_sale_master_decimal_1['sale_qty'] = $sale_qty_input_1;
                            $item_sale_master_decimal_1['item_code'] = $item_code;
                            $item_sale_master_decimal_1['batch_no'] = $batch_no;
                            $item_sale_master_decimal_1['expire_date'] = $freebatch_data->expire_date;
                            $item_sale_master_decimal_1['sale_date'] = date("Y-m-d H:i:s");
                            $item_sale_master_decimal_1['status'] = 1;
                            insert("item_sale_master_decimal", $item_sale_master_decimal_1);
                        }
                    }
                    if ($sale_qty_input_2 > 0) {
                        $FreeSr_noBybatch = getFreeSr_noBybatch($_SESSION['id'], $batch_no, $item_code, $sale_qty_input_2);
                        if (isset($FreeSr_noBybatch->serial_number)) {
                            $serial_number = $FreeSr_noBybatch->serial_number;
                            $serial_qty = $FreeSr_noBybatch->qty;
                            $sale_qty = $FreeSr_noBybatch->sale_qty;
                            $batch_no = $FreeSr_noBybatch->batch_no;
                            $company_id = $FreeSr_noBybatch->company_id;
                            $purchase_basic = $FreeSr_noBybatch->purchase_basic;
                            $company_id = $FreeSr_noBybatch->company_id;
                            $gst = $FreeSr_noBybatch->gst;
                            $total = $FreeSr_noBybatch->total;
                            $expire_date = $FreeSr_noBybatch->expire_date;

                            $srUpdate = array();
                            $srUpdate['qty'] = numberDecimal($serial_qty - $sale_qty_input_2);
                            $srUpdate['sale_qty'] = numberDecimal($sale_qty + $sale_qty_input_2);
                            $srUpdate['update_datetime'] = date("Y-m-d H:i:s");
                            $srUpdate['order_no'] = $_POST['po_no'];
                            if (numberDecimal($serial_qty - $sale_qty_input_2) == 0.00) {
                                $srUpdate['status'] = 1;
                            }
                            $srUpdate['partial'] = 1;

                            $srUpdateWhere = "serial_number='$serial_number' and item_code='$item_code' AND STATUS='0' AND retailer_id='$retailerid' AND batch_no='$batch_no'";
                            $updateDecimal = true;
                            $updateDecimal = update('item_sr_master', $srUpdate, $srUpdateWhere);
                            if ($updateDecimal) {
                                $item_sale_master_decimal = array();
                                $item_sale_master_decimal['retailer_id'] = $retailer_id;
                                $item_sale_master_decimal['purchase_basic'] = $purchase_basic;
                                $item_sale_master_decimal['gst'] = $gst;
                                $item_sale_master_decimal['total'] = $total;
                                $item_sale_master_decimal['company_id'] = $company_id;
                                $item_sale_master_decimal['order_no'] = $_POST['po_no'];
                                $item_sale_master_decimal['serial_number'] = $serial_number;
                                $item_sale_master_decimal['sale_qty'] = $sale_qty_input_2;
                                $item_sale_master_decimal['item_code'] = $item_code;
                                $item_sale_master_decimal['batch_no'] = $batch_no;
                                $item_sale_master_decimal['expire_date'] = $expire_date;
                                $item_sale_master_decimal['sale_date'] = date("Y-m-d H:i:s");
                                $item_sale_master_decimal['status'] = 1;
                                insert("item_sale_master_decimal", $item_sale_master_decimal);
                            }
                        }
                    }
                }
                $retailer_item_qty = getRetailerItemCurentQty($_SESSION['id'], $item_code);
                $ttl_qty_crt = $retailer_item_qty->current_stock - $qty;
                $ttl_qty_issu = $retailer_item_qty->issued_stock + $qty;

                $upd_ar = array(
                    'current_stock' => $ttl_qty_crt,
                    'issued_stock' => $ttl_qty_issu
                );
                $where = "retailer_id = '$retailerid' and item_code = '$item_code'";
                $upd = update('retailer_inventory_master', $upd_ar, $where);

                $q_1 = mysqli_query($conn, "UPDATE retailer_order_temporary set order_place_date='" . date("Y-m-d H:i:s") . "', stock_flg = '1' where retailer_id = '$retailerid'
                and item_code = '$item_code' and stock_flg = '0'");
            }
            /// FOR STOCK MINUS====================================================


            $q = mysqli_query($conn, "UPDATE retailer_order_temporary set order_place_date='" . date("Y-m-d H:i:s") . "', order_status=1 where retailer_id='" . $_SESSION['id'] . "'
            and po_no='" . $_POST['po_no'] . "'");
            echo 3;
            exit;
        } else {
            echo 5;
        }
    }


    if ($_POST['request_type'] == 'insert_to_retailer_po_table') {
        $item_code = $_POST['item_code'];
        $item_desc = $_POST['item_desc'];
        $Liquidation_Days = $_POST['Liquidation_Days'];
        $availablestck = $_POST['availablestck'];
//        $price = $_POST['price'];
        $qty = $_POST['qty'];
        $uom = $_POST['uom'];
        $remarks = $_POST['remarks'];

        if (empty($availablestck) || $availablestck == '') {
            $availablestck = 0;
        }

        $inventory_data = getInventoryDataByCode($item_code);

        $insarr = array();
        $insarr['retailer_id'] = $retailer_id;
        $insarr['company_id'] = $company_id;
        $insarr['bdm_id'] = $bdm_id;
        $insarr['available_stck'] = $availablestck;
        $insarr['item_code'] = $item_code;
        $insarr['Liquidation_Days'] = $Liquidation_Days;
        $insarr['item_desc'] = $item_desc;
//        $insarr['item_price'] = $price;
        if ($company_id == '2') {
            $insarr['bdm_qty'] = $qty;
        }
        $insarr['qty'] = $qty;
        $insarr['uom'] = $inventory_data->uom;
        $insarr['remarks'] = $remarks;
        $insarr['added_time'] = date('Y-m-d H:i:s');
        $insdata = insert('retailer_po_generate_item_tbl', $insarr);
        echo '1';
    }

    if ($_POST['request_type'] == 'get_retailer_po_item_list') {
        $get_temp_item_list = getRetailerPoItemList($_SESSION['id']);
        $i = 1;
        $total_amt = 0;
        $data = '';
        foreach ($get_temp_item_list as $value) {

            $data = $data . '<tr>
                <td align="center">' . $i . '</td>
                <td>' . $value->item_desc . '</td>
                <td align="center">' . $value->qty . '</td>
                <td align="right">' . $value->available_stck . '</td>
                <td align="right">' . $value->Liquidation_Days . '</td>
                <td align="right">' . $value->remarks . '</td>
                <td align="center"> 
                <i class="fa fa-trash i_remove_cls_small_inv" style="cursor:pointer; color:red" 
                id="' . $value->id . '" data-toggle="tooltip" title="Remove"></i>                         
                </td>
                </tr>';
            $i++;
        }
        echo $data;
    }

    if ($_POST['request_type'] == 'remove_item_retailer_po_list') {
        $id = $_POST['id'];
        $whr = "id = '$id'";
        $delete = delete('retailer_po_generate_item_tbl', $whr);
        if ($delete) {
            echo '1';
        } else {
            echo '0';
        }
    }

    if ($_POST['request_type'] == 'retailer_po_item_confirm_order') {


        $updarr = array();
        if ($company_id == '2') {
            $updarr['status'] = 2;
        } else {
            $updarr['status'] = 1;
        }
        $updarr['added_time'] = date('Y-m-d H:i:s');
        $whrr = "retailer_id = '$retailer_id' and status = '0'";
        $upd = update('retailer_po_generate_item_tbl', $updarr, $whrr);

        if ($upd) {
            echo '0';
        } else {
            echo '1';
        }
    }

    if ($_POST['request_type'] == 'delete_retailer_po_item_after_confirm') {
        $itmid = $_POST['itmid'];
        $whr = "id = '$itmid'";
        $delete = delete('retailer_po_generate_item_tbl', $whr);
        if ($delete) {
            echo '1';
        } else {
            echo '0';
        }
    }
}
