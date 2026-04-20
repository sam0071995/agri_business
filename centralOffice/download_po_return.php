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
$filename = "data_export_po_return.csv";

// Set headers to indicate file download
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=' . $filename);

// Create a file pointer connected to the output stream
$output = fopen('php://output', 'w');

// Output column headings if necessary (adjust as per your table's columns)
$columns = [
    'store', // r.name AS store
    'po_no', // p.po_no
    'added_date', // p.added_date
    'item_code', // p.item_id AS item_code
    'qty', // p.qty
    'rate', // p.rate
    'gst_rate', // p.gst_rate
    'batch_no', // p.batch_no
    'expiry_date', // p.expiry_date
    'delete'           // p.delete
];

fputcsv($output, $columns);

// Fetch data from MySQL
$query = "SELECT r.`name` AS store,p.`po_no`,p.`added_date`,p.`item_id` AS item_code,p.`qty`,p.`rate`,p.`gst_rate`,p.`batch_no`,p.`expiry_date`,p.`delete` 
FROM `purchase_order_return_detail` p, retailer_master r
WHERE p.`retailer_id`=r.`id`
GROUP BY p.`retailer_id`,p.`po_no`,p.`added_date`,p.`item_id`,p.`qty`,p.`rate`,p.`gst_rate`,p.`batch_no`,p.`expiry_date`,p.`delete`"; // Replace with your actual table and columns
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
