
<?php

$to = "nandvanaajit@gmail.com";
$subject = "PHP Mail Test";
$message = "Hello from PHP!";
$headers = "From: nandvanaajit@gmail.com\r\n";
$headers .= "Reply-To: nandvanaajit@gmail.com\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

if (mail($to, $subject, $message, $headers)) {
    echo "Mail function works!";
} else {
    echo "Mail function failed!";
}

exit;
?>


<?php

require_once 'mail_common_function.php';

$item_code = "ALL";
$retailer_in = "ALL";

$retailer_in = "28";

$date_1 = "2025-11-01";
$date_2 = date("Y-m-d");

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
        <td colspan="36" style="padding: 4px;text-align: center;"><h3>' . getCompanyNameINById($company_id_in) . '</h3></td>
        </tr>
        <tr>
        <td colspan="36" style="padding: 4px;text-align: center;"><p>Stock Report between ' . date("d M Y", strtotime($date_1)) . ' to ' . date("d M Y", strtotime($date_2)) . '</p></td>
        </tr>
        <tr>
            <td rowspan="2">Sr No</td>
            <td rowspan="2">Store Name</td>
            <td rowspan="2">Product Name</td>
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
    $activeItems = getMailRetailerAllItems($retailer_id, $item_code);
    foreach ($activeItems as $activeItem) {
        $retailerStatus = getRetailerStatusById($activeItem->retailer_id);
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
            $InwardCountBackendData = getBackendRetailerStockTInwardFifo($retailer_id, $item_code);
            if (isset($InwardCountBackendData->qty)) {
                $InwardCountBackend = $InwardCountBackendData->qty;
                $Inwardpurchae_basicBackend = $InwardCountBackendData->purchae_basic;
                $Inwardpurchase_gstBackend = $InwardCountBackendData->purchase_gst;
                if (empty($InwardCountBackend)) {
                    $InwardCountBackend = 0;
                    $Inwardpurchae_basicBackend = 0;
                    $Inwardpurchase_gstBackend = 0;
                }
            }
        } else {
            $opening = getRetailerItemOpeningStockById($item_code, $retailer_id);
        }

        $totla_inward = getRetailerStockTInward($retailer_id, $item_code, $previous_date);
        $opening_puirchase_fifo = getRetailerStockTInwardFifoPurchaseGST($retailer_id, $item_code, $previous_date);
        if (isset($opening_puirchase_fifo->po_basic) && $opening_puirchase_fifo->po_basic > 0) {
            $opening_total_purchase_basic = $opening_puirchase_fifo->po_basic;
            $opening_total_gst_rate = $opening_puirchase_fifo->po_gst;
        } else {
            $opening_total_purchase_basic = $Inwardpurchae_basicBackend;
            $opening_total_gst_rate = $Inwardpurchase_gstBackend;
        }

        $total_transfered = getRetailerStockTransfer($retailer_id, $item_code, $previous_date);
        $total_return_po = getRetailerTransferPurchare($retailer_id, $item_code, $previous_date);
        $total_sales = getRetailerSalesDetail($retailer_id, $item_code, $previous_date);
        $opening = numberDecimal($opening + $InwardCountBackend + ($totla_inward - $total_transfered - $total_return_po - $total_sales));

        $store_trasnfer_in = getRetailerStockTInwardForDateMailBetweenFifo($retailer_id, $item_code, $date_1, $date_2);
        $store_trasnfer_in_inward_qty = 0;
        if (isset($store_trasnfer_in->inward_qty)) {
            $store_trasnfer_in_inward_qty = $store_trasnfer_in->inward_qty;
        }

        $store_trasnfer_out = getRetailerStockTransferonDateMailBetweenFifo($retailer_id, $item_code, $date_1, $date_2);
        $store_trasnfer_out_qty = 0;
        if (isset($store_trasnfer_out->inward_qty)) {
            $store_trasnfer_out_qty = $store_trasnfer_out->inward_qty;
        }

        $store_return_PO = getRetailerTransferPurchareonDateMailBetweenFifo($retailer_id, $item_code, $date_1, $date_2);
        $store_return_PO_qty = 0;
        if (isset($store_return_PO->qty)) {
            $store_return_PO_qty = $store_return_PO->qty;
        }

        $store_in_PO = getRetailerStockTInwardForDatePOMailBetweenFifo($retailer_id, $item_code, $date_1, $date_2);
        $inward_purchase_qty = 0;
        if (isset($store_in_PO->inward_qty)) {
            $inward_purchase_qty = $store_in_PO->inward_qty;
        }

        $store_in_credit_note_PO = getRetailerStockTInwardForDateCreditNoteMailBetweenFifo($retailer_id, $item_code, $date_1, $date_2);
        $inward_credit_note_qty = 0;
        if (isset($store_in_credit_note_PO->inward_qty)) {
            $inward_credit_note_qty = $store_in_credit_note_PO->inward_qty;
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

        $store_in_purchase_fifo = getRetailerStockTInwardForDatePOMailBetweenFifoPurchase($retailer_id, $item_code, $date_1, $date_2);
        if (isset($store_in_purchase_fifo->po_basic)) {
            $last_po_basic = $store_in_purchase_fifo->po_basic;
            $last_po_gst = $store_in_purchase_fifo->po_gst;
        } else {
            $last_po_basic = $opening_total_purchase_basic;
            $last_po_gst = $opening_total_gst_rate;
        }
        if ($last_po_basic == 0) {
            $store_in_purchase_whatever = getRetailerStockTInwardForDatePOMailBetweenFifoPurchaseWhatever($retailer_id, $item_code, $date_1, $date_2);
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

        $table .= '<tr>'
                . '<td>' . $index . '</td>'
                . '<td>' . getRetailerNameById($activeItem->retailer_id) . '</td>'
                . '<td>' . clean($activeItem->item_desc) . '</td>'
                . '<td>' . getCategoryNameById($activeItem->main_category_id) . '</td>'
                . '<td>' . numberDecimal($opening) . '</td>'
                . '<td>' . numberDecimal($opening_total_gst_rate) . '</td>'
                . '<td>' . numberDecimal($opening_total_purchase_basic) . '</td>'
                . '<td>' . numberDecimal(($opening) * ($opening_total_purchase_basic)) . '</td>'
                . '<td>' . numberDecimal($inward_purchase_qty) . '</td>'
                . '<td>' . numberDecimal($last_po_gst) . '</td>'
                . '<td>' . numberDecimal($last_po_basic) . '</td>'
                . '<td>' . numberDecimal($inward_purchase_qty * $last_po_basic) . '</td>'
                . '<td>' . numberDecimal($store_return_PO_qty) . '</td>'
                . '<td>' . numberDecimal($last_po_gst) . '</td>'
                . '<td>' . numberDecimal($last_po_basic) . '</td>'
                . '<td>' . numberDecimal($store_return_PO_gst_rate * $last_po_basic) . '</td>'
                . '<td>' . numberDecimal($store_trasnfer_in_inward_qty) . '</td>'
                . '<td>' . numberDecimal($last_po_gst) . '</td>'
                . '<td>' . numberDecimal($last_po_basic) . '</td>'
                . '<td>' . numberDecimal($last_po_basic * $store_trasnfer_in_inward_qty) . '</td>'
                . '<td>' . numberDecimal($store_trasnfer_out_qty) . '</td>'
                . '<td>' . numberDecimal($last_po_gst) . '</td>'
                . '<td>' . numberDecimal($last_po_basic) . '</td>'
                . '<td>' . numberDecimal($last_po_basic * $store_trasnfer_out_qty) . '</td>'
                . '<td>' . numberDecimal($inward_credit_note_qty) . '</td>'
                . '<td>' . numberDecimal($last_po_gst) . '</td>'
                . '<td>' . numberDecimal($last_po_basic) . '</td>'
                . '<td>' . numberDecimal($inward_credit_note_qty * $last_po_basic) . '</td>'
                . '<td>' . numberDecimal($sales_on_date_qty) . '</td>'
                . '<td>' . numberDecimal($sales_on_date_gst) . '</td>'
                . '<td>' . numberDecimal($sales_on_date_basic) . '</td>'
                . '<td>' . numberDecimal($sales_on_date_qty * $sales_on_date_basic) . '</td>'
                . '<td>' . numberDecimal($closingStock) . '</td>'
                . '<td>' . numberDecimal($last_po_gst) . '</td>'
                . '<td>' . numberDecimal($last_po_basic) . '</td>'
                . '<td>' . numberDecimal($closingStock * $last_po_basic) . '</td>'
                . '</tr>';
        $index++;
    }
}
$table .= '</tbody>';
$table .= '</table>';
//echo $message = $table;

echo $setData = $table;
EXIT;
//    $dir = './billdesk/'; 
$dir = $dir = __DIR__ . '/';
$filename = "StockFile_" . date('YmdHis', strtotime($datetime)) . ".txt";

//WRITE DATA INTO FILE

$myfile = fopen($dir . $filename, "w") or die("Unable to open file!");
$write_data = fwrite($myfile, $setData);
fclose($myfile);
$encoded_content = chunk_split(base64_encode($content));
$to = "nandvanaajit@gmail.com,ajit.nandvana@hsrp.in";
//    $to = "HSRPUP<ajit.nandvana@hsrp.in>";
$from = "HSRPUP<it.support@hsrp.in>";
$subject = "Stock File " . date("d M Y", strtotime($date)) . " HSRPUP";
$boundary = md5(time());
$message_body = "Dear Sir,\n
    Find attached refund file with all required details. Kindly proceed.\n
Thanks & Regards, 
Ajit Nandvana,
Software Team Leader,
FTA HSRP SOLUTIONS PVT LTD,
Phn :+91 9687641497";

$data = chunk_split(base64_encode($setData));
$headers = "From: $from";
// boundary 
$semi_rand = md5(time());
$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";

// headers for attachment 
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: multipart/mixed; boundary=\"" . $mime_boundary . "\"\r\n\r\n";
// multipart boundary 
$message = "This is a multi-part message in MIME format.\n\n"
        . "--{$mime_boundary}\n"
        . "Content-Type: text/plain; charset=\"iso-8859-1\"\n"
        . "Content-Transfer-Encoding: 7bit\n\n" . $message_body . "\n\n";
$message .= "--{$mime_boundary}\n";
$message .= "Content-Type: {\"application/octet-stream\"};\n" . " name=\"$filename\"\n" .
        "Content-Transfer-Encoding: base64\n\n" . $data . "\n\n";
$message .= "--{$mime_boundary}\n";

$sentMailResult = mail($to, $subject, $message, $headers);

if ($sentMailResult) {
    echo "Done";
} else {
    echo "Not done";
}
?>

