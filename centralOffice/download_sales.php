<?php

// Database connection
$host = "68.178.224.234";
$username = "agro_business_adm";
$password = "Agro#007@adm";
$database = "agro_business"; // Replace with your database name
// Connect to MySQL
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set the filename for the CSV download
$filename = "data_export_sales.csv";

// Set headers to indicate file download
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=' . $filename);

// Create a file pointer connected to the output stream
$output = fopen('php://output', 'w');

// Output column headings if necessary (adjust as per your table's columns)
$columns = [
    'store', // r.name AS store
    'order_no', // o.item_code
    'item_code', // o.item_code
    'batch_no', // o.batch_no
    'expiry_date', // o.expiry_date
    'order_place_date', // o.order_place_date
    'qty', // o.qty
    'basic', // SUM(o.basic) AS basic
    'cgst_rate', // o.cgst_rate
    'sgst_rate', // o.sgst_rate
    'status'           // o.status
];

fputcsv($output, $columns);

// Fetch data from MySQL
$query = "
SELECT r.`name` AS store,o.`po_no` AS order_no,o.`item_code`,o.`batch_no`,o.`expiry_date`,o.`order_place_date`,o.`qty`,SUM(o.`basic`) AS basic,o.`cgst_rate`,o.`sgst_rate`,o.`status`
FROM retailer_order_temporary o,retailer_master r
WHERE o.`retailer_id`=r.`id`
GROUP BY o.`retailer_id`,o.`po_no`,o.`item_code`,o.`batch_no`,o.`expiry_date`,o.`order_place_date`,o.`status`"; // Replace with your actual table and columns
$result = $conn->query($query);

// Check if there are any rows returned
if ($result->num_rows > 0) {
    // Loop through the result set and write each row to the CSV
    while ($row = $result->fetch_assoc()) {
//        $row_o = array();
//        $row_o['store'] = $row->store;
//        $row_o['dispatch_retailer_id'] = $row->dispatch_retailer_id;
//        $row_o['item'] = $row->item;
//        $row_o['batch_number'] = $row->batch_number;
//        $row_o['expire_date'] = $row->expire_date;
//        $row_o['retailer_inwd_date'] = $row->retailer_inwd_date;
//        $row_o['inward_qty'] = $row->inward_qty;
//        $row_o['retailer_inwd_flg'] = $row->retailer_inwd_flg;
        fputcsv($output, $row);
    }
} else {
    // Optionally, write a message to CSV if no data is found
    fputcsv($output, array('No records found.'));
}

// Close the database connection
$conn->close();
?>
