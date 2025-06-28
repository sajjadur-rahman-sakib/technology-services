<?php
require_once 'config.php';
require_once 'payment.php';

// Log IPN request for debugging
error_log("IPN Request received: " . json_encode($_POST));

// Get payment information from SSLCommerz IPN
$tran_id = isset($_POST['tran_id']) ? $_POST['tran_id'] : '';
$amount = isset($_POST['amount']) ? $_POST['amount'] : '';
$currency = isset($_POST['currency']) ? $_POST['currency'] : '';
$status = isset($_POST['status']) ? $_POST['status'] : '';

if (empty($tran_id)) {
    error_log("IPN Error: Missing transaction ID");
    http_response_code(400);
    exit;
}

try {
    // Get payment record from database
    $query = "SELECT * FROM payments WHERE transaction_id = '$tran_id'";
    $result = mysqli_query($connect, $query);
    
    if (!$result || mysqli_num_rows($result) === 0) {
        error_log("IPN Error: Payment record not found for transaction ID: $tran_id");
        http_response_code(404);
        exit;
    }
    
    $payment = mysqli_fetch_assoc($result);
    $reservation_id = $payment['reservation_id'];
    
    // Validate payment with SSLCommerz
    $sslcommerz = new payment();
    $validation_response = $sslcommerz->validatePayment($tran_id, $amount, $currency);
    
    if (isset($validation_response['status']) && $validation_response['status'] === 'VALID') {
        // Payment is valid
        if ($status === 'VALID' || $status === 'VALIDATED') {
            // Update payment status to success
            $update_payment = "UPDATE payments SET 
                              status = 'SUCCESS',
                              ssl_response = '" . mysqli_real_escape_string($connect, json_encode($_POST)) . "',
                              ipn_received = 1,
                              updated_at = NOW()
                              WHERE transaction_id = '$tran_id'";
            
            mysqli_query($connect, $update_payment);
            
            // Update reservation status
            $update_reservation = "UPDATE reservation SET 
                                  tracking = 'Payment Completed - Service Confirmed',
                                  payment_status = 'PAID'
                                  WHERE id = $reservation_id";
            
            mysqli_query($connect, $update_reservation);
            
            // Log successful IPN
            logPaymentEvent($tran_id, 'IPN_SUCCESS', $_POST, $connect);
            error_log("IPN Success: Payment validated and updated for transaction ID: $tran_id");
            
        } else {
            // Payment failed
            $update_payment = "UPDATE payments SET 
                              status = 'FAILED',
                              ssl_response = '" . mysqli_real_escape_string($connect, json_encode($_POST)) . "',
                              ipn_received = 1,
                              updated_at = NOW()
                              WHERE transaction_id = '$tran_id'";
            
            mysqli_query($connect, $update_payment);
            
            // Log failed IPN
            logPaymentEvent($tran_id, 'IPN_FAILED', $_POST, $connect);
            error_log("IPN Failed: Payment failed for transaction ID: $tran_id, Status: $status");
        }
        
    } else {
        // Payment validation failed
        $update_payment = "UPDATE payments SET 
                          status = 'INVALID',
                          ssl_response = '" . mysqli_real_escape_string($connect, json_encode($_POST)) . "',
                          ipn_received = 1,
                          updated_at = NOW()
                          WHERE transaction_id = '$tran_id'";
        
        mysqli_query($connect, $update_payment);
        
        // Log validation failure
        logPaymentEvent($tran_id, 'IPN_VALIDATION_FAILED', $validation_response, $connect);
        error_log("IPN Validation Failed: Invalid payment for transaction ID: $tran_id");
    }
    
    // Send OK response to SSLCommerz
    http_response_code(200);
    echo "OK";
    
} catch (Exception $e) {
    error_log("IPN Error: " . $e->getMessage());
    http_response_code(500);
    echo "ERROR";
}
?>
