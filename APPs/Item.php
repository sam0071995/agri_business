<?php

header('Content-Type: application/json');
$key = "XiQsr4sRWtlPuFJNrj6UxEDSSdAdRRAL";

function DecrPassMethod($encryptedData, $key) {
    $method = 'aes-256-cbc'; // Example encryption method
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($method)); // Generate a secure IV
    $encryptedData = base64_decode($encryptedData);
    list($encryptedText, $iv) = explode('::', $encryptedData, 2);
    return $decrypted = openssl_decrypt($encryptedText, $method, $key, 0, $iv);
}

$DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];
$path = $DOCUMENT_ROOT . "\APPs\agrosindia-in-chain.pem";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://agrosindia.in/APPs/API");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_PORT, 443);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
//curl_setopt($ch, CURLOPT_POSTFIELDS, '{ "type": "categories","category_id":"2" }');
//curl_setopt($ch, CURLOPT_POSTFIELDS, '{ "type": "categories"}');
curl_setopt($ch, CURLOPT_POSTFIELDS, '{ "type": "items","store_id":"15" }');
//curl_setopt($ch, CURLOPT_POSTFIELDS, '{"type": "items","store_id": "41","item_code": "AGRO480"}');
//curl_setopt($ch, CURLOPT_POSTFIELDS, '{"type": "stores"}');
//curl_setopt($ch, CURLOPT_POSTFIELDS, '{"type": "stores","store_id": "15"}');
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'content-type: application/json'
));
curl_setopt($ch, CURLOPT_CAINFO, $path);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, $path);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $path);


curl_setopt($ch, CURLOPT_TIMEOUT_MS, 5000);
echo $response = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Curl error: ' . curl_error($ch);
}
curl_close($ch);
//$response_array = json_decode($response);
//if ($response_array->status == 200) {
//    print_r($response_array->response);
//} else {
//    print_r($response_array->response);
//}

