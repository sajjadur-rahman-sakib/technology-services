<?php
// SSLCommerz Configuration
define('SSLCOMMERZ_STORE_ID', 'sakib685ef04286eb7');
define('SSLCOMMERZ_STORE_PASSWORD', 'sakib685ef04286eb7@ssl');
define('SSLCOMMERZ_STORE_NAME', 'testsakib6hwl');
define('SSLCOMMERZ_REGISTERED_URL', 'https://sakib.tech/');

// SSLCommerz URLs
define('SSLCOMMERZ_SESSION_API', 'https://sandbox.sslcommerz.com/gwprocess/v3/api.php');
define('SSLCOMMERZ_VALIDATION_API', 'https://sandbox.sslcommerz.com/validator/api/validationserverAPI.php');
define('SSLCOMMERZ_MERCHANT_PANEL', 'https://sandbox.sslcommerz.com/manage/');

// Your website URLs (adjust these based on your actual domain)
define('PAYMENT_SUCCESS_URL', 'http://localhost/project/payment/success.php');
define('PAYMENT_FAIL_URL', 'http://localhost/project/payment/fail.php');
define('PAYMENT_CANCEL_URL', 'http://localhost/project/payment/cancel.php');
define('PAYMENT_IPN_URL', 'http://localhost/project/payment/ipn.php');

// Payment settings
define('DEFAULT_CURRENCY', 'BDT');
define('PAYMENT_TIMEOUT', 30); // in minutes

// Service fees (you can modify these as needed)
define('SERVICE_FEES', [
    'Mobile Phone services' => 1000,
    'PC and Mac notebook service' => 1500,
    'Personal devices security' => 800,
    'Data Management service' => 1200,
    'Smart Watche services' => 600,
    'Digital Cameras services' => 1000
]);

// Function to get service fee from database
function getServiceFee($service_name, $connect) {
    $service_name = mysqli_real_escape_string($connect, $service_name);
    $query = "SELECT fee_amount FROM fees WHERE service_name = '$service_name' AND is_active = 1";
    $result = mysqli_query($connect, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['fee_amount'];
    }
    
    // Fallback to default fees
    return isset(SERVICE_FEES[$service_name]) ? SERVICE_FEES[$service_name] : 500;
}

// Function to log payment events
function logPaymentEvent($transaction_id, $event_type, $event_data, $connect) {
    $transaction_id = mysqli_real_escape_string($connect, $transaction_id);
    $event_type = mysqli_real_escape_string($connect, $event_type);
    $event_data = mysqli_real_escape_string($connect, json_encode($event_data));
    $ip_address = $_SERVER['REMOTE_ADDR'] ?? '';
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    
    $query = "INSERT INTO logs (transaction_id, event_type, event_data, ip_address, user_agent) 
              VALUES ('$transaction_id', '$event_type', '$event_data', '$ip_address', '$user_agent')";
    
    mysqli_query($connect, $query);
}

// Include database connection
require_once '../php/config.php';
?>
