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
$filename = "data_export_opening.csv";

// Set headers to indicate file download
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=' . $filename);

// Create a file pointer connected to the output stream
$output = fopen('php://output', 'w');

// Output column headings if necessary (adjust as per your table's columns)
$columns = [
    'retailer_id', // retailer_name
    'item_code', // item_code
    'batch_no', // batch_no
    'expire_date', // expire_date
    'current_stock', // expire_date
    'purchae_basic', // purchae_basic
    'purchase_gst'      // purchase_gst
];

fputcsv($output, $columns);

// Fetch data from MySQL
$query = "SELECT retailer_id,item_code,batch_no,expire_date,current_stock,purchae_basic,purchase_gst 
FROM item_inward_backend WHERE DATE(update_datetime)='2023-07-01' AND STATUS=1
GROUP BY retailer_id,item_code,batch_no,expire_date,current_stock,purchae_basic,purchase_gst"; // Replace with your actual table and columns
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
