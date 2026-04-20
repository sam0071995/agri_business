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
$filename = "data_export_inward.csv";

// Set headers to indicate file download
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=' . $filename);

// Create a file pointer connected to the output stream
$output = fopen('php://output', 'w');

// Output column headings if necessary (adjust as per your table's columns)
fputcsv($output, array('store', 'dispatch_retailer_id', 'po_no', 'item', 'batch_number', 'expire_date', 'retailer_inwd_date', 'inward_qty', 'retailer_inwd_flg','po_basic','po_gst'));

// Fetch data from MySQL
$query = "SELECT r.`name` AS store,g.`dispatch_retailer_id` AS dispatch_retailer_id,g.`po_no` AS po_no,g.`item_desc` AS item,g.`batch_number` AS batch_number,
    g.`expire_date` AS expire_date,g.`retailer_inwd_date` AS retailer_inwd_date,g.`inward_qty` AS inward_qty,g.`retailer_inwd_flg` AS retailer_inwd_flg,g.`po_basic`,g.`po_gst` 
    FROM inventory_grn g, retailer_master r WHERE g.`retailer_id`=r.`id` 
    GROUP BY g.retailer_id,g.dispatch_retailer_id,g.`item_desc`,g.`batch_number`,g.`expire_date`,g.`retailer_inwd_date`"; // Replace with your actual table and columns
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
