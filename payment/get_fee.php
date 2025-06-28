<?php
require_once 'config.php';

header('Content-Type: application/json');

if (!isset($_GET['service'])) {
    echo json_encode(['status' => 'error', 'message' => 'Service not specified']);
    exit;
}

$service_name = $_GET['service'];

try {
    $fee_amount = getServiceFee($service_name, $connect);
    
    echo json_encode([
        'status' => 'success',
        'service' => $service_name,
        'amount' => $fee_amount,
        'currency' => 'BDT'
    ]);
    
} catch (Exception $e) {
    error_log("Fee retrieval error: " . $e->getMessage());
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to retrieve fee',
        'amount' => 500 // Default fallback
    ]);
}
?>
