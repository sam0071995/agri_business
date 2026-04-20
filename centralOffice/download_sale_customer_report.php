<?php

require_once 'includes/session.php';
$item_code = '';
$retailer_id = '';
$where_q = '';
if (isset($_POST['Retailer_id'])) {
    $retailer_id = $_POST['Retailer_id'];
}
if (isset($_GET['f_date']) && isset($_GET['l_date']) && isset($_GET['Retailer_id']) && isset($_GET['company_id_in'])) {
    $date_1 = date("Y-m-d", strtotime($_GET['f_date']));
    $date_2 = date("Y-m-d", strtotime($_GET['l_date']));
    $retailer_id = 'All';
    if (isset($_GET['Retailer_id'])) {
        $retailer_id = $_GET['Retailer_id'];
        $item_code = $_GET['item_code'];
    }
    if ($retailer_id != "All") {
        $where_q = " AND m.`retailer_id`='$retailer_id'";
    }

// Your Query
    $query = "
SELECT 
    rm.name,
    cm.name as company_id,
    m.added_datetime,
    m.order_no,
    t.price AS total_price,
    t.basic,
    m.total_count,
    m.fin_year,
    im.item_desc as item_code,
    t.qty,
    t.uom,
    m.payment_type,
    m.transaction_no,
    t.cgst_rate,
    t.sgst_rate,
    t.cgst,
    t.sgst,
    m.added_date,
    t.batch_no,
    m.cus_name,
    m.cus_add,
    m.cus_ph,
    m.cus_adhar,
    m.cus_pin,
    m.b2b_flg,
    m.gstin_no,
    t.credit_note_no,
    t.return_qty,
    t.use_in_crop,
    m.discount_amount,
    m.coupon_code,
    t.main_category,
    t.sub_category
FROM retailer_order_master m
INNER JOIN retailer_order_temporary t 
    ON m.order_no = t.po_no
INNER JOIN retailer_master rm 
    ON m.retailer_id = rm.id
INNER JOIN company_master cm 
    ON m.company_id = cm.id
INNER JOIN inventory_master im 
    ON t.item_code = im.item_code
WHERE 
    m.added_date >= '$date_1 00:00:00'
    AND m.added_date <= '$date_2 23:59:59'
    AND m.status NOT IN ('7','8')
    AND m.company_id IN ($company_id_in)
" . $where_q;
//    echo $query;
//    exit;
    $result = mysqli_query($conn, $query);

// Set headers for Excel download
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=retailer_report.xls");

// Column headers
    echo "Retailer ID\tMain Category\tSub Category\tCompany ID\tAdded Datetime\tOrder No\tTotal Price\tBasic\tTotal Count\tFin Year\tItem\tQty\tUOM\tPayment Type\tTransaction No\tCGST Rate\tSGST Rate\tCGST\tSGST\tDiscountAmt\tCouponCode\tAdded Date\tBatch No\tCustomer Name\tCustomer Address\tCustomer Phone\tCustomer Adhar\tPin\tB2B Flag\tGSTIN\tCredit Note\tReturn Qty\tCrops\n";

// Data rows
    while ($row = mysqli_fetch_assoc($result)) {

        foreach ($row as $key => $value) {
            $value = trim($value); // remove start/end spaces
            $value = preg_replace('/\s+/', ' ', $value); // remove extra spaces/tabs/newlines
            $value = str_replace("\t", ' ', $value); // remove tabs
            $value = str_replace("\n", ' ', $value); // remove new line
            $value = str_replace("\r", ' ', $value); // remove carriage return
            $row[$key] = $value;
        }

        echo
        $row['name'] . "\t" .
        getCategoryNameById($row['main_category']) . "\t" .
        getCategoryNameById($row['sub_category']) . "\t" .
        $row['company_id'] . "\t" .
        $row['added_datetime'] . "\t" .
        $row['order_no'] . "\t" .
        $row['total_price'] . "\t" .
        $row['basic'] . "\t" .
        $row['total_count'] . "\t" .
        $row['fin_year'] . "\t" .
        $row['item_code'] . "\t" .
        $row['qty'] . "\t" .
        $row['uom'] . "\t" .
        $row['payment_type'] . "\t" .
        $row['transaction_no'] . "\t" .
        $row['cgst_rate'] . "\t" .
        $row['sgst_rate'] . "\t" .
        $row['cgst'] . "\t" .
        $row['sgst'] . "\t" .
        $row['discount_amount'] . "\t" .
        $row['coupon_code'] . "\t" .
        $row['added_date'] . "\t" .
        $row['batch_no'] . "\t" .
        $row['cus_name'] . "\t" .
        $row['cus_add'] . "\t" .
        $row['cus_ph'] . "\t" .
        $row['cus_adhar'] . "\t" .
        $row['cus_pin'] . "\t" .
        $row['b2b_flg'] . "\t" .
        $row['gstin_no'] . "\t" .
        $row['credit_note_no'] . "\t" .
        $row['return_qty'] . "\t" .
        $row['use_in_crop'] . "\n";
    }


    exit;
} else {
    echo 'Empty Request';
    exit;
}
?>
