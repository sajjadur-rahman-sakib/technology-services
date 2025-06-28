<?php
require_once 'config.php';

class payment {
    private $store_id;
    private $store_password;
    
    public function __construct() {
        $this->store_id = SSLCOMMERZ_STORE_ID;
        $this->store_password = SSLCOMMERZ_STORE_PASSWORD;
    }
    
    /**
     * Initialize payment session
     */
    public function initializePayment($payment_data) {
        $post_data = array();
        
        // Store information
        $post_data['store_id'] = $this->store_id;
        $post_data['store_passwd'] = $this->store_password;
        
        // Payment details
        $post_data['total_amount'] = $payment_data['amount'];
        $post_data['currency'] = isset($payment_data['currency']) ? $payment_data['currency'] : DEFAULT_CURRENCY;
        $post_data['tran_id'] = $payment_data['tran_id'];
        $post_data['success_url'] = PAYMENT_SUCCESS_URL;
        $post_data['fail_url'] = PAYMENT_FAIL_URL;
        $post_data['cancel_url'] = PAYMENT_CANCEL_URL;
        $post_data['ipn_url'] = PAYMENT_IPN_URL;
        
        // Customer information
        $post_data['cus_name'] = $payment_data['customer_name'];
        $post_data['cus_email'] = $payment_data['customer_email'];
        $post_data['cus_add1'] = $payment_data['customer_address'];
        $post_data['cus_city'] = isset($payment_data['customer_city']) ? $payment_data['customer_city'] : 'Dhaka';
        $post_data['cus_country'] = isset($payment_data['customer_country']) ? $payment_data['customer_country'] : 'Bangladesh';
        $post_data['cus_phone'] = $payment_data['customer_phone'];
        
        // Shipping information (can be same as customer info)
        $post_data['ship_name'] = $payment_data['customer_name'];
        $post_data['ship_add1'] = $payment_data['customer_address'];
        $post_data['ship_city'] = isset($payment_data['customer_city']) ? $payment_data['customer_city'] : 'Dhaka';
        $post_data['ship_country'] = isset($payment_data['customer_country']) ? $payment_data['customer_country'] : 'Bangladesh';
        
        // Product information
        $post_data['product_name'] = $payment_data['service_name'];
        $post_data['product_category'] = 'IT Services';
        $post_data['product_profile'] = 'general';
        
        // Additional information
        $post_data['value_a'] = $payment_data['reservation_id']; // Store reservation ID
        $post_data['value_b'] = $payment_data['service_name'];
        $post_data['value_c'] = date('Y-m-d H:i:s');
        
        // Call SSLCommerz API
        return $this->callSSLCommerzAPI(SSLCOMMERZ_SESSION_API, $post_data);
    }
    
    /**
     * Validate payment
     */
    public function validatePayment($tran_id, $amount, $currency = 'BDT') {
        // For sandbox, use GET method for validation
        $validation_url = SSLCOMMERZ_VALIDATION_API . "?" . http_build_query([
            'store_id' => $this->store_id,
            'store_passwd' => $this->store_password,
            'tran_id' => $tran_id,
            'amount' => $amount,
            'currency' => $currency,
            'format' => 'json'
        ]);
        
        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, $validation_url);
        curl_setopt($handle, CURLOPT_TIMEOUT, 30);
        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, FALSE);
        
        $content = curl_exec($handle);
        $error = curl_error($handle);
        
        if (!empty($error)) {
            error_log("CURL Error in validation: " . $error);
            return array('status' => 'FAILED', 'error' => $error);
        }
        
        curl_close($handle);
        
        $response = json_decode($content, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log("JSON Decode Error in validation: " . json_last_error_msg());
            error_log("Raw response: " . $content);
            return array('status' => 'FAILED', 'error' => 'Invalid JSON response');
        }
        
        return $response;
    }
    
    /**
     * Make API call to SSLCommerz
     */
    private function callSSLCommerzAPI($url, $data) {
        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, $url);
        curl_setopt($handle, CURLOPT_TIMEOUT, 30);
        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($handle, CURLOPT_POST, 1);
        curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, FALSE);
        
        $content = curl_exec($handle);
        $error = curl_error($handle);
        
        if (!empty($error)) {
            error_log("CURL Error: " . $error);
            return array('status' => 'FAILED', 'error' => $error);
        }
        
        curl_close($handle);
        
        $response = json_decode($content, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log("JSON Decode Error: " . json_last_error_msg());
            return array('status' => 'FAILED', 'error' => 'Invalid JSON response');
        }
        
        return $response;
    }
    
    /**
     * Generate unique transaction ID
     */
    public static function generateTransactionId() {
        return 'SAKIB-' . date('YmdHis') . '-' . rand(1000, 9999);
    }
}
?>
