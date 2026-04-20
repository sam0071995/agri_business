<?php
error_reporting(0);
ini_set('display_errors', 0);
require_once __DIR__ . '/../includes/config.php';
global $conn;
if (ob_get_length()) ob_end_clean();

//INPUTS
$draw = isset($_POST['draw']) ? $_POST['draw'] : 1;
$start  = isset($_POST['start']) ? intval($_POST['start']) : 0;
$length = isset($_POST['length']) ? intval($_POST['length']) : 20;
if ($length > 1000) $length = 1000; // 🔥 safety limit
$search = '';
if (isset($_POST['search']['value'])) {
    $search = mysqli_real_escape_string($conn, $_POST['search']['value']);
}
$exportCSV = isset($_POST['export_csv']) && $_POST['export_csv'] == 1;

//FILTERS
$where = "m.status NOT IN ('7','8')";
if (!empty($_POST['date_1']) && !empty($_POST['date_2'])) {
    $date_1 = date("Y-m-d", strtotime($_POST['date_1']));
    $date_2 = date("Y-m-d", strtotime($_POST['date_2']));
    $where .= " AND m.added_date BETWEEN '$date_1 00:00:00' AND '$date_2 23:59:59'";
}

if (!empty($_POST['category_id'])) {
    $where .= " AND t.main_category = " . intval($_POST['category_id']);
}

if (!empty($_POST['retailer_id'])) {
    $ids = implode(",", array_map('intval', $_POST['retailer_id']));
    $where .= " AND m.retailer_id IN ($ids)";
}

if (!empty($_POST['company_id'])) {
    $where .= " AND m.company_id = " . intval($_POST['company_id']);
}

//SEARCH
if (!empty($search)) {
    $where .= " AND (
        m.order_no LIKE '%$search%' OR
        m.cus_name LIKE '%$search%' OR
        r.full_name LIKE '%$search%' OR
        im.item_desc LIKE '%$search%' OR
        im.brand_name LIKE '%$search%' OR
        c1.name LIKE '%$search%' OR
        c2.name LIKE '%$search%'
    )";
}

//ORDER
$orderColumn = "m.added_date";
$orderDir = "DESC";
if (isset($_POST['order'][0])) {
    $colIndex = $_POST['order'][0]['column'];
    $dir = $_POST['order'][0]['dir'];
    $map = array(
        1 => 'r.full_name',
        2 => 'm.cus_name',
        3 => 'm.order_no',
        4 => 'im.item_desc',
        11 => 't.qty',
        18 => 't.price',
        22 => 'm.added_date'
    );
    if (isset($map[$colIndex])) {
        $orderColumn = $map[$colIndex];
        $orderDir = ($dir === 'asc') ? 'ASC' : 'DESC';
    }
}

//BASE QUERY
$baseQuery = "
FROM retailer_order_master m
JOIN retailer_order_temporary t ON m.order_no = t.po_no
LEFT JOIN retailer_master r ON r.id = m.retailer_id
LEFT JOIN inventory_master im ON im.item_code = t.item_code
LEFT JOIN categories c1 ON c1.id = t.main_category
LEFT JOIN categories c2 ON c2.id = t.sub_category
WHERE $where
";

//EXPORT CSV
if ($exportCSV) {
    set_time_limit(0);
    ini_set('memory_limit', '-1');
    while (ob_get_level()) ob_end_clean();
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="Product Wise Sales.csv"');
    $output = fopen('php://output', 'w');
    fputcsv($output, [
        'No','Retailer Name','Customer Name','Order No','Item Name','Brand',
        'HSN Code','Main Category','Sub Category','Payment Type','GST %',
        'Qty','Rate','Unit','Basic','CGST','SGST','IGST','Total',
        'Discount','Coupon Code','Financial Year','Bill Date','Bill Time',
        'Order Type','Credit Note No','Return Qty'
    ]);
    $chunk = 100000;
    $offset = 0;
    $index = 1;
    while (true) {
        $sql = "
        SELECT *
        $baseQuery
        ORDER BY m.added_date DESC
        LIMIT $offset, $chunk
        ";
        $res = mysqli_query($conn, $sql);
        if (!$res || mysqli_num_rows($res) == 0) break;
        while ($row = mysqli_fetch_assoc($res)) {
            $payment_type = ($row['payment_type'] == 0) ? "CASH" :
                           (($row['payment_type'] == 1) ? "ONLINE" : "Cheque/DD");
            $gst_rate = $row['cgst_rate'] + $row['sgst_rate'];
            $rate = ($row['qty'] != 0) ? round($row['price'] / $row['qty'], 2) : 0;
            $order_type = ($row['b2b_flg'] == 0) ? "B2C" : "B2B";
            $finalTotal = $row['price'] - $row['discount_amount'];
            fputcsv($output, [
                $index++,
                $row['full_name'],
                $row['cus_name'],
                $row['order_no'],
                $row['item_desc'],
                $row['brand_name'],
                $row['hsn_code'],
                $row['name'],
                $row['name'],
                $payment_type,
                $gst_rate,
                $row['qty'],
                $rate,
                $row['unit'],
                $row['basic'],
                $row['cgst'],
                $row['sgst'],
                0,
                $finalTotal,
                $row['discount_amount'],
                $row['coupon_code'],
                $row['fin_year'],
                $row['added_date'],
                $row['added_datetime'],
                $order_type,
                $row['credit_note_no'],
                $row['return_qty']
            ]);
        }
        mysqli_free_result($res);
        $offset += $chunk;
        fflush($output);
    }
    fclose($output);
    exit;
}

//MAIN QUERY (PAGINATION)
$sql = "
SELECT 
    r.full_name AS retailer_fullname,
    m.cus_name,
    m.order_no,
    im.item_desc AS item_name,
    im.brand_name,
    im.hsn_code,
    im.unit AS unit_name,
    t.qty,
    t.price AS total_price,
    t.basic,
    t.cgst,
    t.sgst,
    t.cgst_rate,
    t.sgst_rate,
    t.return_qty,
    m.discount_amount,
    m.coupon_code,
    m.fin_year,
    m.payment_type,
    m.added_date,
    m.added_datetime,
    m.b2b_flg,
    t.credit_note_no,
    c1.name AS main_category_name,
    c2.name AS sub_category_name
$baseQuery
ORDER BY $orderColumn $orderDir
LIMIT $start, $length
";
$result = mysqli_query($conn, $sql);

//DATA (SAFE - PAGINATED ONLY)
$data = array();
$index = $start + 1;
while ($row = mysqli_fetch_object($result)) {
    $payment_type = ($row->payment_type == 0) ? "CASH" :
                   (($row->payment_type == 1) ? "ONLINE" : "Cheque/DD");
    $gst_rate = $row->cgst_rate + $row->sgst_rate;
    $rate = ($row->qty != 0) ? round($row->total_price / $row->qty, 2) : 0;
    $order_type = ($row->b2b_flg == 0) ? "B2C" : "B2B";
    $finalTotal = $row->total_price - $row->discount_amount;
    $data[] = array(
        "index" => $index++,
        "retailer_fullname" => $row->retailer_fullname,
        "cus_name" => $row->cus_name,
        "order_no" => $row->order_no,
        "item_name" => $row->item_name,
        "brand_name" => $row->brand_name,
        "hsn_code" => $row->hsn_code,
        "main_category_name" => $row->main_category_name ?: 'NA',
        "sub_category_name" => $row->sub_category_name ?: 'NA',
        "payment_type" => $payment_type,
        "gst_rate" => $gst_rate,
        "qty" => round($row->qty, 2),
        "rate" => $rate,
        "unit_name" => $row->unit_name ?: 'NA',
        "basic" => round($row->basic, 2),
        "cgst" => round($row->cgst, 2),
        "sgst" => round($row->sgst, 2),
        "igst" => 0,
        "total" => round($finalTotal, 2),
        "discount_amount" => round($row->discount_amount, 2),
        "coupon_code" => $row->coupon_code,
        "fin_year" => $row->fin_year,
        "bill_date" => date('d M Y', strtotime($row->added_date)),
        "bill_time" => date('H:i', strtotime($row->added_datetime)),
        "order_type" => $order_type,
        "credit_note_no" => $row->credit_note_no,
        "return_qty" => $row->return_qty
    );
}

//FAST COUNT
$countSql = "SELECT COUNT(*) as total $baseQuery";
$countRes = mysqli_query($conn, $countSql);
$total = mysqli_fetch_assoc($countRes)['total'];

// FINAL OUTPUT
echo json_encode([
    "draw" => intval($draw),
    "recordsTotal" => intval($total),
    "recordsFiltered" => intval($total),
    "data" => $data
]);