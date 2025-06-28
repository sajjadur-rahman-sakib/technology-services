<?php
require_once 'config.php';

// Get payment information from SSLCommerz
$tran_id = isset($_POST['tran_id']) ? $_POST['tran_id'] : '';
$status = isset($_POST['status']) ? $_POST['status'] : '';
$error = isset($_POST['error']) ? $_POST['error'] : 'Payment failed';

if (!empty($tran_id)) {
    // Update payment status in database
    $update_payment = "UPDATE payments SET 
                      status = 'FAILED',
                      ssl_response = '" . mysqli_real_escape_string($connect, json_encode($_POST)) . "',
                      updated_at = NOW()
                      WHERE transaction_id = '$tran_id'";
    
    mysqli_query($connect, $update_payment);
    
    // Log failed payment
    logPaymentEvent($tran_id, 'PAYMENT_FAILED', $_POST, $connect);
    error_log("Payment Failed: Transaction ID: $tran_id, Error: $error");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Failed - Sakib IT Services</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .payment-failed {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f44336, #d32f2f);
            padding: 2rem;
        }
        .failed-card {
            background: white;
            padding: 3rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            text-align: center;
            max-width: 500px;
            width: 100%;
        }
        .failed-icon {
            font-size: 4rem;
            color: #f44336;
            margin-bottom: 1rem;
        }
        .failed-title {
            font-size: 2rem;
            color: #333;
            margin-bottom: 1rem;
        }
        .failed-message {
            color: #666;
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        .error-details {
            background: #ffebee;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            border-left: 4px solid #f44336;
        }
        .btn-group {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }
        .btn {
            padding: 0.8rem 2rem;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        .btn-primary {
            background: #007bff;
            color: white;
        }
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        .btn-danger {
            background: #f44336;
            color: white;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
    <div class="payment-failed">
        <div class="failed-card">
            <div class="failed-icon">
                <i class="fas fa-times-circle"></i>
            </div>
            <h1 class="failed-title">Payment Failed</h1>
            <p class="failed-message">
                Unfortunately, your payment could not be processed. Please try again or contact our support team.
            </p>
            
            <?php if (!empty($tran_id)): ?>
            <div class="error-details">
                <h4 style="margin-bottom: 1rem; color: #333;">Error Details</h4>
                <p><strong>Transaction ID:</strong> <?php echo htmlspecialchars($tran_id); ?></p>
                <p><strong>Error:</strong> <?php echo htmlspecialchars($error); ?></p>
                <p><strong>Time:</strong> <?php echo date('d M Y, h:i A'); ?></p>
            </div>
            <?php endif; ?>
            
            <div class="btn-group">
                <a href="../index.php#contact" class="btn btn-danger">
                    <i class="fas fa-redo"></i> Try Again
                </a>
                <a href="../index.php" class="btn btn-primary">
                    <i class="fas fa-home"></i> Back to Home
                </a>
                <a href="mailto:sakib.x@icloud.com" class="btn btn-secondary">
                    <i class="fas fa-envelope"></i> Contact Support
                </a>
            </div>
        </div>
    </div>
</body>
</html>
