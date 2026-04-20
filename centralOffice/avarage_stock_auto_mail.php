<?php

require_once 'includes/session.php';
require_once 'includes/common_function.php';

if (isset($_POST['show'])) {
    $Retailer_id = $_POST['Retailer_id'];
    $item_code = $_POST['item_code'];
    if (count($Retailer_id)) {
        if ($Retailer_id[0] == "ALL") {
            $retailer_in = "ALL";
        } else {
            $retailer_in = implode(",", $Retailer_id);
        }

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
        <td colspan="38" style="padding: 4px;text-align: center;"><h3>' . getCompanyNameINById($company_id_in) . '</h3></td>
        </tr>
        <tr>
        <td colspan="38" style="padding: 4px;text-align: center;"><p>Stock Report between ' . date("d M Y", strtotime($date_1)) . ' to ' . date("d M Y", strtotime($date_2)) . '</p></td>
        </tr>
        <tr>
            <td rowspan="2">Sr No</td>
            <td rowspan="2">Store Name</td>
            <td rowspan="2">Store OP Balance</td>
            <td rowspan="2">Product Name</td>
            <td rowspan="2">Product Unit</td>
            <td rowspan="2">Category</td>
            <td colspan="4">Opening Stock</td>
            <td colspan="4">Inward (Purchase)</td>
            <td colspan="4">Purchase Return</td>
            <td colspan="4">Stock Transfer (IN)</td>
            <td colspan="4">Stock Transfer (Out)</td>
            <td colspan="4">Credit Note</td>
            <td colspan="4">Outward (Sale)</td>
            <td colspan="4">Clossing Stock</td>
        </tr>
        <tr> 
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
        </tr>
    </thead>'; ?>
        <?php

        $table .= '<tbody>';
        $retailers = getActiveRetailerIN($retailer_in);
        $index = 1;
        foreach ($retailers as $retailer) {
            $retailer_id = $retailer->id;
            $item_code = $_POST['item_code'];
//            $activeItems = getRetailerActivesbYItems($retailer_id, $item_code);
            $activeItems = getRetailerAllItems($retailer_id, $item_code);
            foreach ($activeItems as $activeItem) {
                $retailer_id = $activeItem->retailer_id;
                $item_code = $activeItem->item_code;
                $opening = 0;
                $InwardCountBackend = 0;
                $Inwardpurchae_basicBackend = 0;
                $Inwardpurchase_gstBackend = 0;
                $opening_total_purchase_basic = 0;
                $opening_total_gst_rate = 0;

                $retailer_company_id = getRetailerCompanyIdById($retailer_id);
                if ($retailer_company_id == 3) {
                    $InwardCountBackendData = getBackendRetailerStockTInwardAvarage($retailer_id, $item_code);
                    if (isset($InwardCountBackendData->qty)) {
                        $InwardCountBackend = $InwardCountBackendData->qty;
                        $InwardCountBackend_countId = $InwardCountBackendData->countId;
                        $Inwardpurchae_basicBackend_rate = numberDecimal($InwardCountBackendData->purchae_basic / $InwardCountBackend_countId);
                        $Inwardpurchae_basicBackend = numberDecimal($Inwardpurchae_basicBackend_rate * $InwardCountBackend);
                        $Inwardpurchase_gstBackend = $InwardCountBackendData->purchase_gst;
                        if (empty($InwardCountBackend)) {
                            $InwardCountBackend = 0;
                            $Inwardpurchae_basicBackend = 0;
                            $Inwardpurchase_gstBackend = 0;
                        }
                    }
                    $opening_purchase = getBackendRetailerStockTInwardValueRetaikerMaster($retailer_id);
                } else {
                    $opening = getRetailerItemOpeningStockById($item_code, $retailer_id);
                }

                $totla_inward_avarage = getRetailerStockTInwardAvarage($retailer_id, $item_code, $previous_date);
                if (isset($totla_inward_avarage->po_basic)) {
                    $opening_total_gst_countId = $totla_inward_avarage->countId;
                    $opening_total_purchase_basic_rate = $totla_inward_avarage->po_basic / $totla_inward_avarage->countId;
                    $opening_total_purchase_basic = $Inwardpurchae_basicBackend + ($opening_total_purchase_basic_rate * $totla_inward_avarage->inward_qty);
                    $opening_total_gst_rate = $totla_inward_avarage->po_gst;
                } else {
                    $opening_total_purchase_basic = $Inwardpurchae_basicBackend;
                    $opening_total_gst_rate = $Inwardpurchase_gstBackend;
                }
                $totla_inward = $totla_inward_avarage->inward_qty;
                $total_transfered_avarage = getRetailerStockTransferAvarage($retailer_id, $item_code, $previous_date);
                $total_transfered = $total_transfered_avarage->inward_qty;
                $total_transfered_countId = $total_transfered_avarage->countId;
                $total_transfered_purchase_rate = numberDecimal($total_transfered_avarage->po_basic / $total_transfered_countId);
                $total_transfered_purchase = numberDecimal($total_transfered_purchase_rate * $total_transfered_avarage->inward_qty);

                $total_return_po_avarage = getRetailerTransferPurchareAvarage($retailer_id, $item_code, $previous_date);
                $total_return_po = $total_return_po_avarage->qty;
                $total_return_po_basic = $total_return_po_avarage->amount;

                $total_sales_avarage = getRetailerSalesDetailAvarage($retailer_id, $item_code, $previous_date);
                $total_sales = $total_sales_avarage->qty - $total_sales_avarage->return_qty;
                $total_sales_basic_rate = $total_sales_avarage->basic / $total_sales_avarage->qty;
                $total_sales_basic = $total_sales * $total_sales_basic_rate;

                $opening = numberDecimal($opening + $InwardCountBackend + ($totla_inward - $total_transfered - $total_return_po - $total_sales));
                $opening_purchase = numberDecimal($InwardCountBackend + ($totla_inward - $total_transfered - $total_return_po));

                $opening_basic = numberDecimal(($opening_total_purchase_basic - $total_transfered_purchase - $total_return_po_basic - $total_sales_basic));
                $opening_basic_purchase = numberDecimal(($opening_total_purchase_basic - $total_transfered_purchase - $total_return_po_basic));
//
//                echo "op : " . $opening_basic_purchase;
//                echo '<br/>';
//                echo "op purchase: " . $opening_total_purchase_basic;
//                echo '<br/>';
//                echo "op tra purchase: " . $total_transfered_purchase;
//                echo '<br/>';
//                echo "op return po: " . $total_return_po_basic;
//                echo '<br/>';
//                echo "op sale: " . $total_sales_basic;
//                echo '<br/>';
//                exit;

                $store_trasnfer_in = getRetailerStockTInwardForDateMailBetweenAvarage($retailer_id, $item_code, $date_1, $date_2);
                $store_trasnfer_in_inward_qty = 0;
                $store_trasnfer_in_inward_rate = 0;
                $store_trasnfer_in_inward_basic = 0;
                if (isset($store_trasnfer_in->inward_qty)) {
                    $store_trasnfer_in_inward_qty = $store_trasnfer_in->inward_qty;
                    $store_trasnfer_in_inward_coutId = $store_trasnfer_in->coutId;
                    $store_trasnfer_in_inward_rate = numberDecimal($store_trasnfer_in->po_basic / $store_trasnfer_in_inward_coutId);
                    $store_trasnfer_in_inward_basic = numberDecimal($store_trasnfer_in_inward_rate * $store_trasnfer_in_inward_qty);
                }

                $store_trasnfer_out = getRetailerStockTransferonDateMailBetweenAvaraage($retailer_id, $item_code, $date_1, $date_2);
                $store_trasnfer_out_qty = 0;
                $store_trasnfer_out_basic_rate = 0;
                $store_trasnfer_out_basic = 0;
                if (isset($store_trasnfer_out->inward_qty)) {
                    $store_trasnfer_out_qty = $store_trasnfer_out->inward_qty;
                    $store_trasnfer_out_countId = $store_trasnfer_out->countId;
                    $store_trasnfer_out_basic_rate = numberDecimal($store_trasnfer_out->po_basic / $store_trasnfer_out_countId);
                    $store_trasnfer_out_basic = numberDecimal($store_trasnfer_out_basic_rate * $store_trasnfer_out_qty);
                }

                $store_return_PO = getRetailerTransferPurchareonDateMailBetweenAvarage($retailer_id, $item_code, $date_1, $date_2);
                $store_return_PO_qty = 0;
                $store_return_PO_total_basic_rate = 0;
                $store_return_PO_total_basic = 0;
                if (isset($store_return_PO->qty)) {
                    $store_return_PO_qty = $store_return_PO->qty;
                    $store_return_PO_countId = $store_return_PO->countId;
                    $store_return_PO_total_basic_rate = numberDecimal($store_return_PO->total_basic / $store_return_PO->qty);
                    $store_return_PO_total_basic = numberDecimal($store_return_PO->total_basic);
                }

                $store_in_PO = getRetailerStockTInwardForDatePOMailBetweenAvarage($retailer_id, $item_code, $date_1, $date_2);
                $inward_purchase_qty = 0;
                $inward_purchase_po_basic = 0;
                $inward_purchase_po_basic_rate = 0;
                if (isset($store_in_PO->inward_qty)) {
                    $inward_purchase_qty = numberDecimal($store_in_PO->inward_qty);
                    $inward_purchase_countId = $store_in_PO->countId;
                    $inward_purchase_po_basic_rate = numberDecimal(numberDecimal($store_in_PO->po_basic) / $inward_purchase_countId);
                    $inward_purchase_po_basic = numberDecimal($inward_purchase_qty * $inward_purchase_po_basic_rate);
                }

                $store_in_credit_note_PO = getRetailerStockTInwardForDateCreditNoteMailBetweenAvarage($retailer_id, $item_code, $date_1, $date_2);

                $inward_credit_note_qty = 0;
                $inward_credit_note_po_basic = 0;
                $inward_credit_note_po_basic_rate = 0;
                if (isset($store_in_credit_note_PO->inward_qty)) {
                    $inward_credit_note_qty = $store_in_credit_note_PO->inward_qty;
                    $inward_credit_note_countId = $store_in_credit_note_PO->countId;
                    $inward_credit_note_po_basic_rate = ($store_in_credit_note_PO->po_basic / $inward_credit_note_countId);
                    $inward_credit_note_po_basic = $inward_credit_note_po_basic_rate / $inward_credit_note_qty;
                }

                $sales_on_date_qty = 0;
                $sales_on_date_basic = 0;
                $sales_on_date_gst = 0;
                $sales_on_date_qty_data = getRetailerSalesDetailonDateBetweenFifo($retailer_id, $item_code, $date_1, $date_2);
                if (!empty($sales_on_date_qty_data->qty)) {
                    if (isset($sales_on_date_qty_data->qty)) {
                        $sales_on_date_qty = $sales_on_date_qty_data->qty - $sales_on_date_qty_data->return_qty;
                        $sales_on_date_count = $sales_on_date_qty_data->count;
                        if ($sales_on_date_qty == 0) {
                            $sales_on_date_basic = 0;
                            $sales_on_date_gst = 0;
                        } else {
                            $sales_on_date_basic = $sales_on_date_qty_data->basic / $sales_on_date_qty_data->qty;
                            $sales_on_date_gst = getItemGSTRate($item_code);
                        }
                    }
                }

                $closingStock = numberDecimal($opening + $store_trasnfer_in_inward_qty + $inward_purchase_qty + $inward_credit_note_qty - $store_return_PO_qty - $store_trasnfer_out_qty - $sales_on_date_qty);
                $closingStock_BASIC = numberDecimal($opening_basic + $inward_purchase_po_basic + $store_trasnfer_in_inward_basic - $store_trasnfer_out_basic - $store_return_PO_total_basic + $inward_credit_note_po_basic - ($sales_on_date_qty * $sales_on_date_basic));

                $closingStock_purchase = numberDecimal($opening_purchase + $store_trasnfer_in_inward_qty + $inward_purchase_qty + $inward_credit_note_qty - $store_return_PO_qty - $store_trasnfer_out_qty);
//                echo '<br/>';
                $closingStock_BASIC_purchase = numberDecimal($opening_basic_purchase + $inward_purchase_po_basic + $store_trasnfer_in_inward_basic - $store_trasnfer_out_basic - $store_return_PO_total_basic + $inward_credit_note_po_basic);
//                echo '<br/>';
                $closingStock_BASIC_purchase_rate = $closingStock_BASIC_purchase / $closingStock_purchase;
//                exit;

                $store_in_purchase_fifo = getRetailerStockTInwardForDatePOMailBetweenFifoPurchase($retailer_id, $item_code, $date_1, $date_2);
                if (!isset($store_in_purchase_fifo->po_basic)) {
                    $store_in_purchase_fifo = getRetailerStockTRANSFERInwardForDatePOMailBetweenFifoPurchase($retailer_id, $item_code, $date_1, $date_2);
                }

                if (isset($store_in_purchase_fifo->po_basic)) {
                    $last_po_basic = $store_in_purchase_fifo->po_basic;
                    $last_po_gst = $store_in_purchase_fifo->po_gst;
                } else {
                    $last_po_basic = $opening_total_purchase_basic;
                    $last_po_gst = $opening_total_gst_rate;
                }

                if ($last_po_basic == 0) {
                    $store_in_purchase_whatever = getCompanyRetailerStockTInwardForDatePOMailBetweenFifoPurchaseWhatever($retailer_company_id, $item_code, $date_1, $date_2);
                    if (!isset($store_in_purchase_whatever->po_basic)) {
                        $store_in_purchase_whatever = getRetailerStockTInwardForDatePOMailBetweenFifoPurchaseWhatever($retailer_id, $item_code, $date_1, $date_2);
                    }
                    $last_po_basic = $store_in_purchase_whatever->po_basic;
                    $last_po_gst = $store_in_purchase_whatever->po_gst;
                }

                if (empty($last_po_basic)) {
                    $last_po_basic = 0;
                    $last_po_gst = 0;
                }
                if (empty($opening_total_purchase_basic)) {
                    $opening_total_purchase_basic = 0;
                    $opening_total_gst_rate = 0;
                }

                if ($opening_total_purchase_basic == 0) {
                    $opening_total_purchase_basic = $last_po_basic;
                    $opening_total_gst_rate = $last_po_gst;
                }

                if ($opening_total_purchase_basic == 0) {
                    $opening_total_purchase_basic = $Inwardpurchae_basicBackend;
                    $opening_total_gst_rate = $Inwardpurchase_gstBackend;
                }
//                echo $opening_basic_purchase;
//                echo '<br/>';
//                echo $opening_purchase;
//                echo '<br/>';
//                echo $opening_basic_rate = $opening_basic_purchase / $opening_purchase;
//                exit;
                if ($opening > 0) {
                    $opening_basic_rate = $opening_basic_purchase / $opening_purchase;
                } else {
                    $opening_basic = 0;
                    $opening_basic_rate = 0;
                }

                if ($closingStock < 0) {
                    $closingStock_BASIC_purchase_rate = 0;
                }
                if ($closingStock == 0) {
                    $closingStock_BASIC_purchase_rate = 0;
                }

                $table .= '<tr>'
                        . '<td>' . $index . '</td>'
                        . '<td>' . getRetailerNameById($activeItem->retailer_id) . '</td>'
                        . '<td>' . numberDecimal($opening_purchase) . '</td>'
                        . '<td>' . clean($activeItem->item_desc) . '</td>'
                        . '<td>' . getItemUNITByItemCode($item_code) . '</td>'
                        . '<td>' . getCategoryNameById($activeItem->main_category_id) . '</td>'
                        . '<td>' . numberDecimal($opening) . '</td>'
                        . '<td>' . numberDecimal($opening_total_gst_rate) . '</td>'
                        . '<td>' . numberDecimal($opening_basic_rate) . '</td>'
                        . '<td>' . numberDecimal($opening_basic_rate * $opening) . '</td>'
                        . '<td>' . numberDecimal($inward_purchase_qty) . '</td>'
                        . '<td>' . numberDecimal($last_po_gst) . '</td>'
                        . '<td>' . numberDecimal($inward_purchase_po_basic_rate) . '</td>'
                        . '<td>' . numberDecimal($inward_purchase_po_basic) . '</td>'
                        . '<td>' . numberDecimal($store_return_PO_qty) . '</td>'
                        . '<td>' . numberDecimal($last_po_gst) . '</td>'
                        . '<td>' . numberDecimal($store_return_PO_total_basic_rate) . '</td>'
                        . '<td>' . numberDecimal($store_return_PO_total_basic) . '</td>'
                        . '<td>' . numberDecimal($store_trasnfer_in_inward_qty) . '</td>'
                        . '<td>' . numberDecimal($last_po_gst) . '</td>'
                        . '<td>' . numberDecimal($store_trasnfer_in_inward_rate) . '</td>'
                        . '<td>' . numberDecimal($store_trasnfer_in_inward_basic) . '</td>'
                        . '<td>' . numberDecimal($store_trasnfer_out_qty) . '</td>'
                        . '<td>' . numberDecimal($last_po_gst) . '</td>'
                        . '<td>' . numberDecimal($store_trasnfer_out_basic_rate) . '</td>'
                        . '<td>' . numberDecimal($store_trasnfer_out_basic) . '</td>'
                        . '<td>' . numberDecimal($inward_credit_note_qty) . '</td>'
                        . '<td>' . numberDecimal($last_po_gst) . '</td>'
                        . '<td>' . numberDecimal($inward_credit_note_po_basic_rate) . '</td>'
                        . '<td>' . numberDecimal($inward_credit_note_po_basic) . '</td>'
                        . '<td>' . numberDecimal($sales_on_date_qty) . '</td>'
                        . '<td>' . numberDecimal($sales_on_date_gst) . '</td>'
                        . '<td>' . numberDecimal($sales_on_date_basic) . '</td>'
                        . '<td>' . numberDecimal($sales_on_date_qty * $sales_on_date_basic) . '</td>'
                        . '<td>' . numberDecimal($closingStock) . '</td>'
                        . '<td>' . numberDecimal($last_po_gst) . '</td>'
                        . '<td>' . numberDecimal($closingStock_BASIC_purchase_rate) . '</td>'
                        . '<td>' . numberDecimal($closingStock_BASIC_purchase_rate * $closingStock) . '</td>'
                        . '</tr>';
                $index++;
            }
        }
        $table .= '</tbody>';
        $table .= '</table>';
        $message = $table;

        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=Avaraage_stock_details-" . $date_1 . "-to-" . $date_2 . ".xls");

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