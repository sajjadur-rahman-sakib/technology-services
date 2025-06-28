<?php
require_once 'config.php';
require_once 'payment.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit;
}

try {
    // Get reservation data from POST
    $reservation_id = isset($_POST['reservation_id']) ? (int)$_POST['reservation_id'] : 0;
    $service_type = isset($_POST['service_type']) ? $_POST['service_type'] : '';
    
    if (!$reservation_id || !$service_type) {
        echo json_encode(['status' => 'error', 'message' => 'Missing reservation ID or service type']);
        exit;
    }
    
    // Get reservation details from database
    $query = "SELECT * FROM reservation WHERE id = $reservation_id";
    $result = mysqli_query($connect, $query);
    
    if (!$result || mysqli_num_rows($result) === 0) {
        echo json_encode(['status' => 'error', 'message' => 'Reservation not found']);
        exit;
    }
    
    $reservation = mysqli_fetch_assoc($result);
    
    // Check if payment already exists for this reservation
    $payment_check = "SELECT * FROM payments WHERE reservation_id = $reservation_id AND status IN ('SUCCESS', 'PENDING')";
    $payment_result = mysqli_query($connect, $payment_check);
    
    if (mysqli_num_rows($payment_result) > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Payment already exists for this reservation']);
        exit;
    }
    
    // Get service fee from fees table or use default
    $service_fee = getServiceFee($service_type, $connect);
    
    // Generate transaction ID
    $tran_id = payment::generateTransactionId();
    
    // Prepare payment data
    $payment_data = array(
        'amount' => $service_fee,
        'currency' => DEFAULT_CURRENCY,
        'tran_id' => $tran_id,
        'customer_name' => $reservation['name'],
        'customer_email' => $reservation['email'],
        'customer_address' => $reservation['address'],
        'customer_phone' => $reservation['phone'],
        'service_name' => $service_type,
        'reservation_id' => $reservation_id
    );
    
    // Store payment record in database
    $insert_payment = "INSERT INTO payments (reservation_id, transaction_id, amount, currency, service_type, status, created_at) 
                      VALUES ($reservation_id, '$tran_id', $service_fee, '" . DEFAULT_CURRENCY . "', '$service_type', 'PENDING', NOW())";
    
    if (!mysqli_query($connect, $insert_payment)) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to create payment record']);
        exit;
    }
    
    // Initialize SSLCommerz payment
    $sslcommerz = new payment();
    $response = $sslcommerz->initializePayment($payment_data);
    
    if (isset($response['status']) && $response['status'] === 'SUCCESS') {
        // Update payment record with SSLCommerz session ID
        if (isset($response['sessionkey'])) {
            $session_key = mysqli_real_escape_string($connect, $response['sessionkey']);
            $update_payment = "UPDATE payments SET session_key = '$session_key' WHERE transaction_id = '$tran_id'";
            mysqli_query($connect, $update_payment);
        }
        
        // Log successful payment initialization
        logPaymentEvent($tran_id, 'PAYMENT_INITIATED', $response, $connect);
        
        echo json_encode([
            'status' => 'success',
            'payment_url' => $response['GatewayPageURL'],
            'transaction_id' => $tran_id,
            'amount' => $service_fee
        ]);
    } else {
        // Update payment status to failed
        mysqli_query($connect, "UPDATE payments SET status = 'FAILED' WHERE transaction_id = '$tran_id'");
        
        // Log failed payment initialization
        logPaymentEvent($tran_id, 'PAYMENT_INIT_FAILED', $response, $connect);
        
        echo json_encode([
            'status' => 'error',
            'message' => isset($response['failedreason']) ? $response['failedreason'] : 'Payment initialization failed'
        ]);
    }
    
} catch (Exception $e) {
    error_log("Payment initialization error: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'An error occurred while processing payment']);
}
?>
