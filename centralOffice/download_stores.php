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
$filename = "data_export_store.csv";

// Set headers to indicate file download
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=' . $filename);

// Create a file pointer connected to the output stream
$output = fopen('php://output', 'w');

// Output column headings if necessary (adjust as per your table's columns)
$columns = [
    'id',
    'opening',
    'inv_series',
    'user_id',
    'company_id',
    'bdm_id',
    'new_zone_id',
    'zone_id',
    'retailer_code',
    'name',
    'full_name',
    'warehouse_id',
    'address',
    'dc_address',
    'plot_no',
    'road_no',
    'city_no',
    'dist_no',
    'contact_name',
    'contact_number',
    'mobile_otp',
    'lic_no_PESTICIDE',
    'lic_no_FERTILIZER',
    'lic_no_SEEDS',
    'otp_datetime',
    'otp_message',
    'sbh_mobile',
    'pincode',
    'state_id',
    'status',
    'date',
    'added_date',
    'updated_date',
    'email',
    'password',
    'user_group_id',
    'login_status',
    'login_time',
    'login_datetime',
    'inc_code',
    'menu',
    'ec_cd',
    'lat',
    'long',
    'pending_amount',
    'batch_wise_sale'
];

fputcsv($output, $columns);

// Fetch data from MySQL
$query = "SELECT * FROM retailer_master WHERE STATUS=1"; // Replace with your actual table and columns
$result = $conn->query($query);

// Check if there are any rows returned
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }
} else {
    // Optionally, write a message to CSV if no data is found
    fputcsv($output, array('No records found.'));
}

// Close the database connection
$conn->close();
?>
