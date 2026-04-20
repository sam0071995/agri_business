<?php

require_once 'DB.php';

class API {

    private $conn;
    private $key = 'XiQsr4sRWtlPuFJNrj6UxEDSSdAdRRAL';
    private $store = "('15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31','32','33','34','35','36','37','38','39','40','41','50','52','53','55','56','60','61','62','63','64','65','67','68','71','72','73','74','75','76','77','78','79','80')";

    public function __construct() {
        $database = new DB();
        $this->conn = $database->getConnection();
    }

    function encPassMethod($data) {
        $method = 'aes-256-cbc'; // Example encryption method
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($method)); // Generate a secure IV
        $encrypted = openssl_encrypt($data, $method, $this->key, 0, $iv);

        // Save the IV alongside your encrypted data
        return $encryptedData = base64_encode($encrypted . '::' . $iv);
    }

    function DecrPassMethod($encryptedData) {
        $method = 'aes-256-cbc'; // Example encryption method
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($method)); // Generate a secure IV
        $encryptedData = base64_decode($encryptedData);
        list($encryptedText, $iv) = explode('::', $encryptedData, 2);
        return $decrypted = openssl_decrypt($encryptedText, $method, $this->key, 0, $iv);
    }

    // Process the incoming request
    public function processRequest() {
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'POST':
                $this->handlePost();
                break;
            default:
                $this->response(405, '{"status":"405", "response":"Method Not Allowed"}');
                break;
        }
    }

    // Handle POST Request (Create new item)
    private function handlePost() {
        $input = json_decode(file_get_contents('php://input'), true);
        if (isset($input['type'])) {
            if ($input['type'] == 'stores') {
                if (isset($input['store_id'])) {
                    $retailer_id = $input['store_id'];
                    $query = "SELECT id,NAME,address,pincode,contact_name,contact_number,`lat`,`long` " 
                            . "FROM retailer_master WHERE STATUS='1' and id='$retailer_id' and id in " . $this->store . " AND company_id='3'";
                    $stmt = $this->conn->prepare($query);
                    $stmt->execute();
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                    if ($result) {
//                        $enc_data = $this->encPassMethod(json_encode($result));
//                        $enc_data = json_encode($result);
                        $respose_array = array();
                        $respose_array['status'] = "200";
                        $respose_array['response'] = $result;
                        $json_encode = json_encode($respose_array);
                        $this->response(200, $json_encode);
                    } else {
                        $this->response(404, '{"status":"404", "response":"Store not found"}');
                    }
                } else {
                    $query = "SELECT id,NAME,address,pincode,contact_name,contact_number,`lat`,`long` "
                            . "FROM retailer_master WHERE STATUS='1' and id in " . $this->store . " AND company_id='3'";
                    $stmt = $this->conn->prepare($query);
                    $stmt->execute();
                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    if ($result) {
//                        $enc_data = $this->encPassMethod(json_encode($result));
//                        $enc_data = json_encode($result);
                        $respose_array = array();
                        $respose_array['status'] = "200";
                        $respose_array['response'] = $result;
                        $json_encode = json_encode($respose_array);
                        $this->response(200, $json_encode);
                    } else {
                        $this->response(404, '{"status":"404", "response":"Store not found"}');
                    }
                }
            } else if ($input['type'] == 'categories') {
                if (!isset($input['category_id'])) {
                    $query = "SELECT id,name,parent_category FROM categories WHERE STATUS='1'";
                    $stmt = $this->conn->prepare($query);
                    $stmt->execute();
                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    if ($result) {
                        $respose_array = array();
                        $respose_array['status'] = "200";
                        $respose_array['response'] = $result;
                        $json_encode = json_encode($respose_array);
                        $this->response(200, $json_encode);
                    } else {
                        $this->response(404, '{"status":"404", "response":"Category not found"}');
                    }
                } else {
                    $category_id = $input['category_id'];
                    $query = "SELECT id,name,parent_category "
                            . "FROM categories WHERE STATUS='1' and id='$category_id'";
                    $stmt = $this->conn->prepare($query);
                    $stmt->execute();
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                    if ($result) {
                        $respose_array = array();
                        $respose_array['status'] = "200";
                        $respose_array['response'] = $result;
                        $json_encode = json_encode($respose_array);
                        $this->response(200, $json_encode);
                    } else {
                        $this->response(404, '{"status":"404", "response":"Category not found"}');
                    }
                }
            } else if ($input['type'] == 'items') {
                if (isset($input['store_id'])) {
                    $retailer_id = $input['store_id'];
                    $queryString = "";
                    if (isset($input['item_code'])) {
                        $item_code = $input['item_code'];
                        $queryString = " and item_code='$item_code'";
                    }
                    $query = "SELECT retailer_id as storeID,main_category_id as category_id,item_code,description,brand_name,hsn_code,item_desc AS item,current_stock AS stock,unit,uom,basic_price,igst_rate AS gst_rate "
//                    $query = "SELECT retailer_id as storeID,main_category_id as category_id,item_code,description,hsn_code,item_desc AS item,current_stock AS stock,unit,uom,basic_price,igst_rate AS gst_rate "
                            . "FROM retailer_inventory_master WHERE current_stock>0 and retailer_id='" . $retailer_id . "' and retailer_id in " . $this->store . " AND STATUS='1' " . $queryString . "";
                    $stmt = $this->conn->prepare($query);
                    $stmt->execute();
                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    if ($result) {
//                        $enc_data = $this->encPassMethod(json_encode($result));
                        $enc_data = json_encode($result);
                        $respose_array = array();
                        $respose_array['status'] = "200";
                        $respose_array['response'] = $result;
                        $json_encode = json_encode($respose_array);
                        $this->response(200, $json_encode);
                    } else {
                        $this->response(404, '{"status":"404", "response":"Item not found"}');
                    }
                } else {
                    $this->response(408, '{"status":"408", "response":"Bad Request: store id is required"}');
                }
            } else {
                $this->response(403, '{"status":"403", "response":"Bad Request: Invalid Request type"}');
            }
        } else {
            $this->response(400, '{"status":"400", "response":"Bad Request: Type is required"}');
        }
    }

    // Utility function for sending HTTP responses
    private function response($status, $data) {
//        http_response_code($status);
        header('Content-Type: application/json');
        echo $data;
    }

}

// Instantiate the API and process the request
$api = new API();
$api->processRequest();
