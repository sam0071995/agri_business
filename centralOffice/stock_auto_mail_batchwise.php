<?php

require_once 'includes/session.php';
require_once 'includes/common_function.php';

if (isset($_POST['show'])) {
    $Retailer_id = $_POST['Retailer_id'];
    $item_code = $_POST['item_code'];
    if (count($Retailer_id)) {
        $retailer_in = implode(",", $Retailer_id);

        $date_1 = $_POST['date_1'];
        $date_2 = $_POST['date_2'];

        $date_1 = date("Y-m-d", strtotime($date_1));
        $date_2 = date("Y-m-d", strtotime($date_2));
        $previous_date = date('Y-m-d', strtotime('-1 day', strtotime($date_1)));
        $next_date = date('Y-m-d', strtotime('+1 day', strtotime($date_2)));

        $table = "<table><tr>"
                . "<td></td>"
                . "</tr>"
                . "</table>"
        ?>
        <style>
            table{
                border-collapse: collapse;
                border: 1px solid #CCC;
            }
            table thead tr td{
                padding: 4px;
                text-align: center;
            }
            table tbody tr td{
                padding: 4px;
                text-align: left;
            }
        </style>
        <?php $table = '<table border="1" style="border-collapse: collapse;border: 1px solid #CCC;">
    <thead>
        <tr>
        <td colspan="35" style="padding: 4px;text-align: center;"><h3>' . getCompanyNameINById($company_id_in) . '</h3></td>
        </tr>
        <tr>
        <td colspan="35" style="padding: 4px;text-align: center;"><p>Stock Report between ' . date("d M Y", strtotime($date_1)) . ' to ' . date("d M Y", strtotime($date_2)) . '</p></td>
        </tr>
        <tr>
            <td rowspan="2">Sr No</td>
            <td rowspan="2">Store Name</td>
            <td rowspan="2">Product Name</td>
            <td rowspan="2">Category</td>
            <td colspan="5">Opening Stock</td>
            <td colspan="4">Inward (Purchase)</td>
            <td colspan="4">Purchase Return</td>
            <td colspan="4">Stock Transfer (IN)</td>
            <td colspan="4">Stock Transfer (Out)</td>
            <td colspan="4">Outward (Sale)</td>
            <td colspan="5">Clossing Stock</td>
        </tr>
        <tr> 
            <td>Qty</td>
            <td>BatchCount</td>
            <td>GST Rate</td>
            <td>Basic Rate</td>
            <td>Basic Value</td>
            <td>Qty</td>
            <td>GST Rate</td>
            <td>Basic Rate</td>
            <td>Basic Value</td>
            <td>Qty</td>
            <td>GST Rate</td>
            <td>Basic Rate</td>
            <td>Basic Value</td>
            <td>Qty</td>
            <td>GST Rate</td>
            <td>Basic Rate</td>
            <td>Basic Value</td>
            <td>Qty</td>
            <td>GST Rate</td>
            <td>Basic Rate</td>
            <td>Basic Value</td>
            <td>Qty</td>
            <td>GST Rate</td>
            <td>Basic Rate</td>
            <td>Basic Value</td>
            <td>Qty</td>
            <td>BatchQty</td>
            <td>GST Rate</td>
            <td>Basic Rate</td>
            <td>Basic Value</td>
        </tr>
    </thead>'; ?>
        <?php

        $table .= '<tbody>';
        $retailers = getActiveRetailerIN($retailer_in);
        foreach ($retailers as $retailer) {
            $retailer_id = $retailer->id;
            $item_code = $_POST['item_code'];
            $activeItems = getRetailerActivesbYItems($retailer_id, $item_code);
            $index = 1;
            foreach ($activeItems as $activeItem) {
                $retailerStatus = getRetailerStatusById($activeItem->retailer_id);
                $retailer_id = $activeItem->retailer_id;
                $item_code = $activeItem->item_code;
                $opening = 0;
                $InwardCountBackend = 0;
                $Inwardpurchae_basicBackend = 0;
                $Inwardpurchase_gstBackend = 0;
                $Inwardpurchase_totalBackend = 0;
                $retailer_company_id = getRetailerCompanyIdById($retailer_id);
                $InwardCountBackend_count = 1;
                if ($retailer_company_id == 3) {
                    $InwardCountBackendData = getBackendRetailerStockTInward($retailer_id, $item_code, $previous_date);
                    if ($InwardCountBackendData->count > 0) {
                        $InwardCountBackend = $InwardCountBackendData->qty;
                        $InwardCountBackend_count = $InwardCountBackendData->count;
                        $Inwardpurchae_basicBackend = $InwardCountBackendData->purchae_basic / $InwardCountBackend_count;
                        $Inwardpurchase_gstBackend = ($InwardCountBackendData->purchase_gst / $InwardCountBackend_count);
                        $Inwardpurchase_totalBackend = $InwardCountBackendData->purchase_total / $InwardCountBackend_count;
                        if (empty($InwardCountBackend)) {
                            $InwardCountBackend = 0;
                            $Inwardpurchae_basicBackend = 0;
                            $Inwardpurchase_gstBackend = 0;
                            $Inwardpurchase_totalBackend = 0;
                        }
                    }
                } else {
                    $InwardCountBackend = getRetailerItemOpeningStockById($item_code, $retailer_id);
                }

                $totla_inward = getRetailerStockTInward($retailer_id, $item_code, $previous_date);
                $total_transfered = getRetailerStockTransfer($retailer_id, $item_code, $previous_date);
                $total_return_po = getRetailerTransferPurchare($retailer_id, $item_code, $previous_date);
                $total_sales = getRetailerSalesDetail($retailer_id, $item_code, $previous_date);
//                ECHO $InwardCountBackend;
//                echo '<br/>';
//                ECHO $totla_inward;
//                echo '<br/>';
//                ECHO $total_transfered;
//                echo '<br/>';
//                ECHO $total_return_po;
//                echo '<br/>';
//                ECHO $total_sales;
//                echo '<br/>';
                $opening = numberDecimal($InwardCountBackend + ($totla_inward - $total_transfered - $total_return_po - $total_sales));

                $store_trasnfer_in = getRetailerStockTInwardForDateMailBetween($retailer_id, $item_code, $date_1, $date_2);
                $store_trasnfer_in_inward_qty = 0;
                $store_trasnfer_in_dispatch_retailer_id = 0;
                $store_trasnfer_in_po_basic = 0;
                $store_trasnfer_in_po_total_basic_value = 0;
                $store_trasnfer_in_po_gst = 0;
                if (isset($store_trasnfer_in->inward_qty)) {
                    $store_trasnfer_in_inward_qty = $store_trasnfer_in->inward_qty;
                    $store_trasnfer_in_inward_count = $store_trasnfer_in->count;
                    if ($store_trasnfer_in_inward_qty == 0) {
                        $store_trasnfer_in_po_basic = 0;
                        $store_trasnfer_in_po_gst = 0;
                    } else {
                        $store_trasnfer_in_po_basic = $store_trasnfer_in->po_basic / $store_trasnfer_in_inward_count;
                        $store_trasnfer_in_po_total_basic_value = $store_trasnfer_in_po_basic * $store_trasnfer_in_inward_qty;
                        $store_trasnfer_in_po_gst = $store_trasnfer_in->po_gst / $store_trasnfer_in_inward_count;
                    }
                    $store_trasnfer_in_dispatch_retailer_id = $store_trasnfer_in->dispatch_retailer_id;
                }
//                echo $store_trasnfer_in_inward_qty;
//                exit;
                $store_trasnfer_out = getRetailerStockTransferonDateMailBetween($retailer_id, $item_code, $date_1, $date_2);
                $store_trasnfer_out_qty = 0;
                $store_trasnfer_out_basic = 0;
                $store_trasnfer_out_gst = 0;
                $po_total_basic_value = 0;
                $store_trasnfer_out_retailer_id = 0;
                $store_trasnfer_out_avarage_basic_value = 0;
                if (isset($store_trasnfer_out->inward_qty)) {
                    $store_trasnfer_out_qty = $store_trasnfer_out->inward_qty;
                    if ($store_trasnfer_out_qty == 0) {
                        $store_trasnfer_out_basic = 0;
                        $store_trasnfer_out_gst = 0;
                    } else {
                        $po_total_basic_value = $store_trasnfer_out->po_total_basic_value;
                        $store_trasnfer_out_basic = $store_trasnfer_out->po_basic;
                        $store_trasnfer_out_gst = $store_trasnfer_out->po_gst / $store_trasnfer_out->count;
                        if ($store_trasnfer_out_basic != 0) {
                            $store_trasnfer_out_avarage_basic_value = $po_total_basic_value / $store_trasnfer_out_qty;
                        }
                    }
                    $store_trasnfer_out_retailer_id = $store_trasnfer_out->retailer_id;
                }

                $store_return_PO = getRetailerTransferPurchareonDateMailBetween($retailer_id, $item_code, $date_1, $date_2);
                $store_return_PO_qty = 0;
                $store_return_PO_amount = 0;
                $store_return_PO_gst_rate = 0;
                $store_return_PO_vendor_id = 0;
                $store_return_PO_amount_rate = 0;
                if (isset($store_return_PO->qty)) {
                    $store_return_PO_qty = $store_return_PO->qty;
                    $store_return_PO_count = $store_return_PO->count;
                    $store_return_PO_total_basic = $store_return_PO->amount;
                    if ($store_return_PO_qty == 0) {
                        $store_return_PO_gst_rate = 0;
                        $store_return_PO_amount = 0;
                        $store_return_PO_count = 0;
                    } else {
                        $store_return_PO_amount = $store_return_PO_total_basic;
                        $store_return_PO_amount_rate = $store_return_PO_total_basic / $store_return_PO_qty;
                        if ($store_return_PO_count == 0) {
                            $store_return_PO_count = 1;
                        }
                        $store_return_PO_gst_rate = $store_return_PO->gst_rate / $store_return_PO_count;
                    }
                    $store_return_PO_vendor_id = $store_return_PO->vendor_id;
                }

                $store_in_PO = getRetailerStockTInwardForDatePOMailBetween($retailer_id, $item_code, $date_1, $date_2);
                $inward_purchase_qty = 0;
                $inward_purchase_basic = 0;
                $inward_purchase_basic_rate = 0;
                $inward_purchase_gst = 0;
                $inward_purchase_vendor_id = 0;
                if (isset($store_in_PO->inward_qty)) {
                    $inward_purchase_qty = $store_in_PO->inward_qty;
                    $inward_purchase_cout = $store_in_PO->cout;
                    $inward_purchase_po_total_basic_value = $store_in_PO->po_total_basic_value;
                    if ($inward_purchase_qty == 0) {
                        $inward_purchase_basic = 0;
                    } else {
                        $inward_purchase_basic = $inward_purchase_po_total_basic_value;
                        $inward_purchase_basic_rate = $inward_purchase_basic / $inward_purchase_qty;
                        if ($inward_purchase_cout == 0) {
                            $inward_purchase_cout = 1;
                        }
                        $inward_purchase_gst = $store_in_PO->po_gst / $inward_purchase_cout;
                    }

                    $inward_purchase_vendor_id = $store_in_PO->vendor_id;
                }
                $sales_on_date_qty = 0;
                $sales_on_date_basic = 0;
                $sales_on_date_gst = 0;
                $sales_on_date_qty_data = getRetailerSalesDetailonDateBetween($retailer_id, $item_code, $date_1, $date_2);
                if (!empty($sales_on_date_qty_data->qty)) {
                    if (isset($sales_on_date_qty_data->qty)) {
                        $sales_on_date_qty = $sales_on_date_qty_data->qty;
                        $sales_on_date_count = $sales_on_date_qty_data->count;
                        if ($sales_on_date_qty == 0) {
                            $sales_on_date_basic = 0;
                            $sales_on_date_gst = 0;
                        } else {
                            $sales_on_date_basic = $sales_on_date_qty_data->basic / $sales_on_date_qty;
                            $sales_on_date_gst = ($sales_on_date_qty_data->cgst_rate + $sales_on_date_qty_data->sgst_rate) / $sales_on_date_count;
                        }
                    }
                }
                $itemFreeSrNoOpening = getFreeItemsSrByitemBatchDetailsOpening($retailer_id, $item_code, $previous_date);
                $opening_batch_count = $itemFreeSrNoOpening->count + $itemFreeSrNoOpening->Salecount;
//                $opening_batch_count = $itemFreeSrNoOpening->count;
                $opening_batch_countId = $itemFreeSrNoOpening->countId;
                if ($opening_batch_count == 0) {
                    $opening_total_gst_rate = 0;
                    $opening_total_purchase_basic = 0;
                } else {
                    $opening_total_gst_rate = $itemFreeSrNoOpening->gst;
                    $opening_total_purchase_basic = $itemFreeSrNoOpening->purchase_basic;
                }
                $opening_batch_count_between = 0;
                $opening_batch_countId_between = 0;
//                $itemFreeSrNoOpeningBetweenDate = getFreeItemsSrByitemBatchDetailsOpeningBetweenDate($retailer_id, $item_code, $date_1, $date_2, $previous_date);
//                $opening_batch_count_between = $itemFreeSrNoOpeningBetweenDate->count;
//                $opening_batch_countId_between = $itemFreeSrNoOpeningBetweenDate->countId;
                if ($opening_batch_count_between == 0) {
                    $opening_total_gst_rate_between = 0;
                    $opening_total_purchase_basic_between = 0;
                } else {
//                    $opening_total_gst_rate_between = $itemFreeSrNoOpeningBetweenDate->gst;
//                    $opening_total_purchase_basic_between = $itemFreeSrNoOpeningBetweenDate->purchase_basic;
                }
                $opening_batch_count = $opening_batch_count + $opening_batch_count_between;
                $opening_batch_countId = $opening_batch_countId + $opening_batch_countId_between;
                if ($opening_batch_count != 0) {
                    $opening_total_gst_rate = ($opening_total_gst_rate + $opening_total_gst_rate_between) / $opening_batch_countId;
                    $opening_total_purchase_basic = ($opening_total_purchase_basic + $opening_total_purchase_basic_between) / $opening_batch_countId;
                } else {
//                    $itemrejectedSrNoOpeningBetweenDate = getRejectedItemsSrByitemBatchDetailsOpeningBetweenDate($retailer_id, $item_code, $date_1, $date_2, $previous_date);
//                    $opening_total_gst_rate = $itemrejectedSrNoOpeningBetweenDate->gst;
//                    $opening_total_purchase_basic = $itemrejectedSrNoOpeningBetweenDate->purchase_basic;
                }

                $itemFreeSrNoClosing = getFreeItemsSrByitemBatchDetailsBeforeDate($retailer_id, $item_code, $date_2);
                $closing_batch_count = $itemFreeSrNoClosing->count;
                if ($closing_batch_count == 0) {
                    $closing_batch_gst = 0;
                    $closing_batch_purchase_basic = 0;
                } else {
                    $closing_batch_countId = $itemFreeSrNoClosing->countId;
                    $closing_batch_gst = $itemFreeSrNoClosing->gst / $closing_batch_countId;
                    if ($closing_batch_count < 1) {
                        $closing_batch_purchase_basic = $itemFreeSrNoClosing->purchase_basic * $closing_batch_count;
                    } else {
                        $closing_batch_purchase_basic = $itemFreeSrNoClosing->purchase_basic;
                    }
                }

                $closing_batch_gst_between = 0;
                $closing_batch_purchase_basic_between = 0;
                $itemFreeSrNoClosing_between = getFreeItemsSrByitemBatchDetailsBetweenDate($retailer_id, $item_code, $date_2, $next_date);
                $closing_batch_between_count = $itemFreeSrNoClosing_between->count;

                if ($closing_batch_between_count == 0) {
                    $closing_batch_gst_between = 0;
                    $closing_batch_purchase_basic_between = 0;
                } else {
                    $closing_batch_gst_between = $itemFreeSrNoClosing_between->gst / $itemFreeSrNoClosing_between->countId;
                    if ($closing_batch_between_count < 1) {
                        $closing_batch_purchase_basic_between = $itemFreeSrNoClosing_between->purchase_basic * $closing_batch_between_count;
                    } else {
                        $closing_batch_purchase_basic_between = $itemFreeSrNoClosing_between->purchase_basic;
                    }
                }
                //closed
                $closing_batch_gst_rejected = 0;
                $closing_batch_purchase_basic_rejected = 0;
                $itemFreeSrNoClosing_rejected = getFreeItemsSrByitemBatchDetailsBetweenDateRejected($retailer_id, $item_code, $date_2, $next_date);
                $closing_batch_rejected_count = $itemFreeSrNoClosing_rejected->count;

                if ($closing_batch_rejected_count == 0) {
                    $closing_batch_gst_rejected = 0;
                    $closing_batch_purchase_basic_rejected = 0;
                } else {
                    $closing_batch_gst_rejected = $itemFreeSrNoClosing_rejected->gst / $itemFreeSrNoClosing_rejected->countId;
                    if ($closing_batch_rejected_count < 1) {
                        $closing_batch_purchase_basic_rejected = $itemFreeSrNoClosing_rejected->purchase_basic * $closing_batch_rejected_count;
                    } else {
                        $closing_batch_purchase_basic_rejected = $itemFreeSrNoClosing_rejected->purchase_basic;
                    }
                }

                $closing_batch_count = $closing_batch_count + $closing_batch_between_count + $closing_batch_rejected_count;
                if ($closing_batch_count != 0) {
                    $closing_batch_gst = $closing_batch_gst + $closing_batch_gst_between + $closing_batch_gst_rejected;
                    $closing_batch_purchase_basic = ($closing_batch_purchase_basic + $closing_batch_purchase_basic_between + $closing_batch_purchase_basic_rejected) / $closing_batch_count;
                }

                $closingStock = numberDecimal($opening + $store_trasnfer_in_inward_qty + $inward_purchase_qty - $store_return_PO_qty - $store_trasnfer_out_qty - $sales_on_date_qty);
                $closing_batch_count_saved = $closing_batch_count;

                if ($closingStock <= 0) {
                    $closing_batch_count = 0;
                    $closing_batch_gst = 0;
                    $closing_batch_purchase_basic = 0;
                }


                $gst_rate = getItemGSTRate($item_code);
                $opening_total_gst_rate = $gst_rate;
                $inward_purchase_gst = $gst_rate;
                $store_return_PO_gst_rate = $gst_rate;
                $store_trasnfer_in_po_gst = $gst_rate;
                $store_trasnfer_out_gst = $gst_rate;
                $sales_on_date_gst = $gst_rate;
                $closing_batch_gst = $gst_rate;
//                $opening_batch_count = $opening;


//                echo '<br/>';
                if ($closing_batch_count == 0) {
                    $avarage = 0;
                    if ($opening_total_purchase_basic > 0) {
                        $closing_batch_purchase_basic = ($opening_total_purchase_basic * $opening);
//                        echo '<br/>';
                        $avarage = $opening;
//                        echo '<br/>';
                    }
//                    echo '<br/>';
                    if ($inward_purchase_basic > 0) {
                        $closing_batch_purchase_basic = $closing_batch_purchase_basic + $inward_purchase_basic;
//                        echo '<br/>';
                        $avarage = $avarage + $inward_purchase_qty;
//                        echo '<br/>';
                    }
//                    echo '<br/>';
                    if ($store_trasnfer_in_po_total_basic_value > 0) {
                        $closing_batch_purchase_basic = $closing_batch_purchase_basic + $store_trasnfer_in_po_total_basic_value;
//                        echo '<br/>';
                        $avarage = $avarage + $store_trasnfer_in_inward_qty;
//                        echo '<br/>';
                    }
                    $closing_batch_purchase_basic = numberDecimal($closing_batch_purchase_basic / $avarage);
                }

//                $closing_batch_count = $closingStock;
                $table .= '<tr>'
                        . '<td>' . $index . '</td>'
                        . '<td>' . getRetailerNameById($activeItem->retailer_id) . '</td>'
                        . '<td>' . clean($activeItem->item_desc) . '</td>'
                        . '<td>' . getCategoryNameById($activeItem->main_category_id) . '</td>'
                        . '<td>' . numberDecimal($opening) . '</td>'
                        . '<td>' . numberDecimal($opening_batch_count) . '</td>'
                        . '<td>' . numberDecimal($opening_total_gst_rate) . '</td>'
                        . '<td>' . numberDecimal($opening_total_purchase_basic) . '</td>'
                        . '<td>' . numberDecimal(($opening) * ($opening_total_purchase_basic)) . '</td>'
                        . '<td>' . numberDecimal($inward_purchase_qty) . '</td>'
                        . '<td>' . numberDecimal($inward_purchase_gst) . '</td>'
                        . '<td>' . numberDecimal($inward_purchase_basic_rate) . '</td>'
                        . '<td>' . numberDecimal($inward_purchase_basic) . '</td>'
                        . '<td>' . numberDecimal($store_return_PO_qty) . '</td>'
                        . '<td>' . numberDecimal($store_return_PO_gst_rate) . '</td>'
                        . '<td>' . numberDecimal($store_return_PO_amount_rate) . '</td>'
                        . '<td>' . numberDecimal($store_return_PO_amount) . '</td>'
                        . '<td>' . numberDecimal($store_trasnfer_in_inward_qty) . '</td>'
                        . '<td>' . numberDecimal($store_trasnfer_in_po_gst) . '</td>'
                        . '<td>' . numberDecimal($store_trasnfer_in_po_basic) . '</td>'
                        . '<td>' . numberDecimal($store_trasnfer_in_po_total_basic_value) . '</td>'
                        . '<td>' . numberDecimal($store_trasnfer_out_qty) . '</td>'
                        . '<td>' . numberDecimal($store_trasnfer_out_gst) . '</td>'
                        . '<td>' . numberDecimal($store_trasnfer_out_avarage_basic_value) . '</td>'
                        . '<td>' . numberDecimal($po_total_basic_value) . '</td>'
                        . '<td>' . numberDecimal($sales_on_date_qty) . '</td>'
                        . '<td>' . numberDecimal($sales_on_date_gst) . '</td>'
                        . '<td>' . numberDecimal($sales_on_date_basic) . '</td>'
                        . '<td>' . numberDecimal($sales_on_date_qty * $sales_on_date_basic) . '</td>'
                        . '<td>' . numberDecimal($closingStock) . '</td>'
                        . '<td>' . numberDecimal($closing_batch_count) . '</td>'
                        . '<td>' . numberDecimal($closing_batch_gst) . '</td>'
                        . '<td>' . numberDecimal($closing_batch_purchase_basic) . '</td>'
                        . '<td>' . numberDecimal($closing_batch_count * $closing_batch_purchase_basic) . '</td>'
                        . '</tr>';
                $index++;
            }
            $table .= '</tbody>';
            ?>
            <?php

            $table .= '</table>';
            $message = $table;
        }

        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=stock_details-" . $date_1 . "-to-" . $date_2 . ".xls");

        header("Pragma: no-cache");
        header("Expires: 0");
        echo $message . "\n";
    } else {
        echo 'Empty Retailer.';
    }
} else {
    echo 'Empty Inpiuts.';
}
?>