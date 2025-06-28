<?php
include 'config.php';
// Fix: use correct variable for DB connection
if (isset($conn)) {
    $db = $conn;
} elseif (isset($connect)) {
    $db = $connect;
} else {
    die('Database connection not found.');
}

$status = null;
$searched = false;

if (isset($_POST['search']) && !empty($_POST['phone'])) {
    $phone = mysqli_real_escape_string($db, $_POST['phone']);
    $phone_clean = ltrim(str_replace([' ', '-'], '', $phone), '0');
    
    // Updated query to include payment information
    $sql = "SELECT r.*, p.transaction_id, p.amount, p.currency, p.status as payment_status, p.created_at as payment_date
            FROM reservation r 
            LEFT JOIN payments p ON r.id = p.reservation_id 
            WHERE REPLACE(REPLACE(r.phone, ' ', ''), '-', '') = '$phone_clean' 
            ORDER BY r.id DESC";
    
    $result = mysqli_query($db, $sql);
    $searched = true;
    $tracking_history = [];
    $reservation_data = null;

    while ($row = mysqli_fetch_assoc($result)) {
        if (!$reservation_data) {
            $reservation_data = $row; // Store the latest reservation
        }
        $tracking_history[] = $row['tracking'];
    }

    $tracking_history = array_unique($tracking_history); // Only unique statuses
    if (count($tracking_history) > 0) {
        $status = $tracking_history[0];
    } else {
        $status = false;
    }

    $all_statuses = [
        'Order placed',
        'In progress',
        'Serviceing',
        'Service complete',
        'Ready for delivery',
        'Delivered'
    ];

    $ordered_history = [];
    if ($status === 'Serviceing') {
        $ordered_history = ['Serviceing', 'In progress', 'Order placed'];
    } else if ($status === 'In progress') {
        $ordered_history = ['In progress', 'Order placed'];
    } else if ($status === 'Order placed') {
        $ordered_history = ['Order placed'];
    } else if ($status === 'Service complete') {
        $ordered_history = ['Service complete', 'Serviceing', 'In progress', 'Order placed'];
    } else if ($status === 'Ready for delivery') {
        $ordered_history = ['Ready for delivery', 'Service complete', 'Serviceing', 'In progress', 'Order placed'];
    } else if ($status === 'Delivered') {
        $ordered_history = ['Delivered', 'Ready for delivery', 'Service complete', 'Serviceing', 'In progress', 'Order placed'];
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $searched = false;
    $status = null;
} else {
    $status = null;
    $searched = false;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Your Service</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .track-container { max-width: 600px; margin: 60px auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 8px rgba(40,167,69,0.08); }
        .track-container h2 { text-align: center; margin-bottom: 20px; color:rgb(38, 97, 163); }
        .track-container input[type="text"] { width: 100%; padding: 10px; border: 1px solid rgb(38, 97, 163); border-radius: 4px; margin-bottom: 15px; }
        .track-container button { width: 100%; background: rgb(38, 97, 163); color: #fff; border: none; padding: 10px; border-radius: 4px; font-size: 16px; cursor: pointer; transition: background 0.2s; }
        .track-container button:hover { background:rgb(38, 97, 163); }
        .track-container .result { margin-top: 20px; text-align: center; font-size: 18px; }
        .track-container .notfound { color: #dc3545; }
        .track-container .found { color:rgb(38, 97, 163); }

        /* Payment Info Styles */
        .payment-info {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            text-align: left;
        }
        .payment-info h3 {
            color: #495057;
            margin-bottom: 15px;
            font-size: 18px;
        }
        .payment-status {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 12px;
            text-transform: uppercase;
        }
        .payment-status.paid { background: #d4edda; color: #155724; }
        .payment-status.pending { background: #fff3cd; color: #856404; }
        .payment-status.failed { background: #f8d7da; color: #721c24; }
        .payment-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            padding: 5px 0;
        }
        .pay-now-btn {
            background: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 15px;
            width: 100%;
            font-size: 16px;
        }
        .pay-now-btn:hover {
            background: #218838;
        }

        .tracking-progress {
            display: flex;
            justify-content: center;
            margin-top: 30px;
        }
        .steps {
            display: flex;
            justify-content: space-between;
            position: relative;
            width: 100%;
            max-width: 100%;
            margin: 0 auto;
        }
        .step {
            flex: 1;
            text-align: center;
            position: relative;
            padding: 10px;
        }
        .step:not(:last-child)::after {
            content: '';
            position: absolute;
            top: 30px;
            right: -50%;
            width: 100%;
            height: 4px;
            background:rgba(70, 131, 172, 0.2);
            z-index: 0;
        }
        .step.completed:not(:last-child)::after {
            background:rgb(38, 97, 163);
        }
        .step .icon {
            font-size: 26px;
            width: 44px;
            height: 44px;
            margin: 0 auto;
            background-color:rgba(70, 131, 172, 0.2);
            border-radius: 50%;
            line-height: 44px;
            color:rgb(38, 97, 163);
        }
        .step.completed .icon {
            background-color:rgb(38, 97, 163);
            color: #fff;
        }
        .step .label {
            margin-top: 8px;
            font-size: 14px;
            font-weight: bold;
            color:rgb(38, 97, 163);
        }
    </style>
</head>
<body>
    <div class="track-container">
        <h2>Track Your Service</h2>
        <form method="post" action="">
            <input type="text" name="phone" placeholder="Enter your phone number" required>
            <button type="submit" name="search">Search</button>
        </form>

        <?php if ($searched): ?>
        <div class="result">
            <?php if ($status === false): ?>
                <span class="notfound">No reservation found for this phone number.</span>
            <?php else: ?>
                <span class="found">Tracking Status: <strong><?php echo htmlspecialchars($status); ?></strong></span>

                <!-- Payment Information -->
                <?php if ($reservation_data): ?>
                <div class="payment-info">
                    <h3><i class="fas fa-credit-card"></i> Payment Information</h3>
                    
                    <?php if ($reservation_data['payment_status']): ?>
                        <div class="payment-row">
                            <span><strong>Payment Status:</strong></span>
                            <span class="payment-status <?php echo strtolower($reservation_data['payment_status']); ?>">
                                <?php echo htmlspecialchars($reservation_data['payment_status']); ?>
                            </span>
                        </div>
                        
                        <?php if ($reservation_data['transaction_id']): ?>
                        <div class="payment-row">
                            <span><strong>Transaction ID:</strong></span>
                            <span><?php echo htmlspecialchars($reservation_data['transaction_id']); ?></span>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($reservation_data['amount']): ?>
                        <div class="payment-row">
                            <span><strong>Amount:</strong></span>
                            <span><?php echo htmlspecialchars($reservation_data['amount'] . ' ' . ($reservation_data['currency'] ?? 'BDT')); ?></span>
                        </div>
                        <?php endif; ?>
                        
                        <div class="payment-row">
                            <span><strong>Service:</strong></span>
                            <span><?php echo htmlspecialchars($reservation_data['problem']); ?></span>
                        </div>
                        
                        <?php if ($reservation_data['payment_date']): ?>
                        <div class="payment-row">
                            <span><strong>Payment Date:</strong></span>
                            <span><?php echo date('d M Y, h:i A', strtotime($reservation_data['payment_date'])); ?></span>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Show pay now button if payment is pending -->
                        <?php if ($reservation_data['payment_status'] === 'PENDING'): ?>
                        <button class="pay-now-btn" onclick="initiatePayment(<?php echo $reservation_data['id']; ?>, '<?php echo htmlspecialchars($reservation_data['problem']); ?>')">
                            <i class="fas fa-credit-card"></i> Pay Now
                        </button>
                        <?php endif; ?>
                        
                    <?php else: ?>
                        <div class="payment-row">
                            <span><strong>Payment Status:</strong></span>
                            <span class="payment-status pending">PENDING</span>
                        </div>
                        <div class="payment-row">
                            <span><strong>Service:</strong></span>
                            <span><?php echo htmlspecialchars($reservation_data['problem']); ?></span>
                        </div>
                        <button class="pay-now-btn" onclick="initiatePayment(<?php echo $reservation_data['id']; ?>, '<?php echo htmlspecialchars($reservation_data['problem']); ?>')">
                            <i class="fas fa-credit-card"></i> Pay Now
                        </button>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <?php if (!empty($ordered_history)): ?>
                <?php
                    $all_display_statuses = [
                        'Order placed' => 'Order placed',
                        'In progress' => 'In progress',
                        'Serviceing' => 'Serviceing',
                        'Service complete' => 'Service complete',
                        'Ready for delivery' => 'Ready for delivery',
                        'Delivered' => 'Delivered'
                    ];
                    $icons = [
                        'Order placed' => '📦',
                        'In progress' => '🛒',
                        'Serviceing' => '🚚',
                        'Service complete' => '🛵',
                        'Ready for delivery' => '🤝',
                        'Delivered' => '✅'
                    ];

                    $completed_stages = [];
                    foreach ($ordered_history as $stage) {
                        if (isset($all_display_statuses[$stage])) {
                            $completed_stages[] = $all_display_statuses[$stage];
                        }
                    }
                ?>
                <div class="tracking-progress">
                    <div class="steps">
                        <?php foreach (array_unique($all_display_statuses) as $display_stage): ?>
                            <div class="step <?php echo in_array($display_stage, $completed_stages) ? 'completed' : ''; ?>">
                                <div class="icon"><?php echo $icons[$display_stage]; ?></div>
                                <div class="label"><?php echo $display_stage; ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>

    <!-- Add Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Add jQuery for AJAX -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    
    <script>
    function initiatePayment(reservationId, serviceType) {
        // Disable the button and show loading state
        const btn = event.target;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
        
        $.ajax({
            url: '../payment/initiate.php',
            type: 'POST',
            data: {
                reservation_id: reservationId,
                service_type: serviceType
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    // Redirect to payment gateway
                    window.location.href = response.payment_url;
                } else {
                    alert('Payment initialization failed: ' + response.message);
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-credit-card"></i> Pay Now';
                }
            },
            error: function() {
                alert('Failed to initiate payment. Please try again.');
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-credit-card"></i> Pay Now';
            }
        });
    }
    </script>
</body>
</html>

