<?php
require_once 'config.php';

// Get payment information from SSLCommerz
$tran_id = isset($_POST['tran_id']) ? $_POST['tran_id'] : '';

if (!empty($tran_id)) {
    // Update payment status in database
    $update_payment = "UPDATE payments SET 
                      status = 'CANCELLED',
                      ssl_response = '" . mysqli_real_escape_string($connect, json_encode($_POST)) . "',
                      updated_at = NOW()
                      WHERE transaction_id = '$tran_id'";
    
    mysqli_query($connect, $update_payment);
    
    // Log cancelled payment
    logPaymentEvent($tran_id, 'PAYMENT_CANCELLED', $_POST, $connect);
    error_log("Payment Cancelled: Transaction ID: $tran_id");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Cancelled - Sakib IT Services</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .payment-cancelled {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #ff9800, #f57c00);
            padding: 2rem;
        }
        .cancelled-card {
            background: white;
            padding: 3rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            text-align: center;
            max-width: 500px;
            width: 100%;
        }
        .cancelled-icon {
            font-size: 4rem;
            color: #ff9800;
            margin-bottom: 1rem;
        }
        .cancelled-title {
            font-size: 2rem;
            color: #333;
            margin-bottom: 1rem;
        }
        .cancelled-message {
            color: #666;
            margin-bottom: 2rem;
            line-height: 1.6;
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
        .btn-warning {
            background: #ff9800;
            color: white;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
    <div class="payment-cancelled">
        <div class="cancelled-card">
            <div class="cancelled-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h1 class="cancelled-title">Payment Cancelled</h1>
            <p class="cancelled-message">
                You have cancelled the payment process. Your reservation is still pending and you can try to pay again at any time.
            </p>
            
            <?php if (!empty($tran_id)): ?>
            <div style="background: #fff3cd; padding: 1rem; border-radius: 5px; margin-bottom: 2rem;">
                <p><strong>Transaction ID:</strong> <?php echo htmlspecialchars($tran_id); ?></p>
                <p><strong>Status:</strong> Cancelled</p>
                <p><strong>Time:</strong> <?php echo date('d M Y, h:i A'); ?></p>
            </div>
            <?php endif; ?>
            
            <div class="btn-group">
                <a href="../index.php#contact" class="btn btn-warning">
                    <i class="fas fa-credit-card"></i> Try Payment Again
                </a>
                <a href="../index.php" class="btn btn-primary">
                    <i class="fas fa-home"></i> Back to Home
                </a>
            </div>
        </div>
    </div>
</body>
</html>
