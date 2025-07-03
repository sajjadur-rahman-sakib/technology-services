<?php
// Set consistent timezone for OTP operations
date_default_timezone_set('Asia/Dhaka'); // Set to your local timezone

require_once '../php/config.php';
require_once 'email_config.php';
require_once 'email_sender.php';

// Function to generate random OTP
function generateOTP($length = OTP_LENGTH) {
    return str_pad(random_int(0, pow(10, $length) - 1), $length, '0', STR_PAD_LEFT);
}

// Function to send OTP email
function sendOTPEmail($email, $otp, $name) {
    $subject = 'OTP Verification - ' . SMTP_FROM_NAME;
    $message = "
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <title>OTP Verification</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background-color: #f4f4f4; }
            .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
            .header { background: linear-gradient(135deg, #007bff, #0056b3); color: white; padding: 30px 20px; text-align: center; }
            .content { padding: 30px 20px; }
            .otp-box { background: #f8f9fa; border: 2px dashed #007bff; border-radius: 10px; padding: 20px; text-align: center; margin: 20px 0; }
            .otp { color: #007bff; font-size: 42px; font-weight: bold; letter-spacing: 8px; font-family: 'Courier New', monospace; }
            .footer { background: #f8f9fa; padding: 20px; text-align: center; font-size: 12px; color: #666; }
            .warning { background: #fff3cd; border: 1px solid #ffeaa7; color: #856404; padding: 15px; border-radius: 5px; margin: 20px 0; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>🔐 OTP Verification</h1>
                <p>" . SMTP_FROM_NAME . "</p>
            </div>
            <div class='content'>
                <h2>Hello " . htmlspecialchars($name) . "!</h2>
                <p>You've requested a service reservation. To verify your email address, please use the following One-Time Password (OTP):</p>
                
                <div class='otp-box'>
                    <div class='otp'>$otp</div>
                </div>
                
                <p><strong>⏰ This OTP is valid for " . OTP_EXPIRY_MINUTES . " minutes only.</strong></p>
                <p>Please enter this OTP in the verification form to complete your service reservation.</p>
                
                <div class='warning'>
                    <strong>🔒 Security Note:</strong> Never share this OTP with anyone. Our team will never ask for your OTP via phone or email.
                </div>
                
                <p>If you have any questions, feel free to contact us.</p>
                <p>Best regards,<br><strong>" . SMTP_FROM_NAME . " Team</strong></p>
            </div>
            <div class='footer'>
                <p>If you didn't request this OTP, please ignore this email.</p>
                <p>© 2025 " . SMTP_FROM_NAME . ". All rights reserved.</p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    // Create Gmail sender instance (using PHPMailer)
    $emailSender = new EmailSender(SMTP_USERNAME, SMTP_PASSWORD, SMTP_FROM_NAME);
    
    // Log OTP for development/debugging
    if (ENABLE_EMAIL_LOGGING) {
        error_log("OTP Email Attempt - To: $email, Name: $name, OTP: $otp");
    }
    
    // Try to send the email using PHPMailer
    try {
        $result = $emailSender->sendEmail($email, $subject, $message, $name);
        
        if (ENABLE_EMAIL_LOGGING) {
            error_log("Real email send result for $email: " . ($result ? 'SUCCESS' : 'FAILED'));
        }
        
        return $result;
    } catch (Exception $e) {
        error_log("Real email sending error: " . $e->getMessage());
        
        // In development mode, return true anyway so we can test with logged OTPs
        if (DEVELOPMENT_MODE) {
            error_log("DEVELOPMENT MODE: Email failed but continuing. OTP for $email ($name): $otp");
            return true;
        }
        
        return false;
    }
}

// Handle OTP generation and sending
if(isset($_POST['send_otp'])) {
    // Debug logging for resend attempts
    if (ENABLE_EMAIL_LOGGING) {
        error_log("OTP Send Request - POST data: " . print_r($_POST, true));
    }
    
    // Check if all required fields are present
    $required_fields = ['customer_name', 'customer_address', 'customer_mobile', 'customer_email', 'customer_problem', 'customer_description'];
    $missing_fields = [];
    
    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || empty($_POST[$field])) {
            $missing_fields[] = $field;
        }
    }
    
    if (!empty($missing_fields)) {
        if (ENABLE_EMAIL_LOGGING) {
            error_log("OTP Send Failed - Missing fields: " . implode(', ', $missing_fields));
        }
        echo json_encode(['status' => 'error', 'message' => 'Missing required fields: ' . implode(', ', $missing_fields)]);
        exit;
    }
    
    $name = mysqli_real_escape_string($connect, $_POST['customer_name']);
    $address = mysqli_real_escape_string($connect, $_POST['customer_address']);
    $phone = mysqli_real_escape_string($connect, $_POST['customer_mobile']);
    $email = mysqli_real_escape_string($connect, $_POST['customer_email']);
    $problem = mysqli_real_escape_string($connect, $_POST['customer_problem']);
    $description = mysqli_real_escape_string($connect, $_POST['customer_description']);
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid email format']);
        exit;
    }
    
    // Generate OTP
    $otp = generateOTP();
    
    // Calculate expiry time using current database time to ensure consistency
    $expiry_query = "SELECT DATE_ADD(NOW(), INTERVAL " . OTP_EXPIRY_MINUTES . " MINUTE) as expires_at";
    $expiry_result = mysqli_query($connect, $expiry_query);
    $expiry_data = mysqli_fetch_assoc($expiry_result);
    $expires_at = $expiry_data['expires_at'];
    
    // Debug logging for time synchronization
    if (ENABLE_EMAIL_LOGGING) {
        error_log("OTP Generation - Current PHP time: " . date('Y-m-d H:i:s'));
        error_log("OTP Generation - Calculated expiry: " . $expires_at);
        error_log("OTP Generation - OTP: $otp for $email");
    }
    
    // Store temporary data with OTP
    $query = "INSERT INTO temporary (name, address, phone, email, problem, description, otp, expires_at, created_at) 
              VALUES ('$name', '$address', '$phone', '$email', '$problem', '$description', '$otp', '$expires_at', NOW())
              ON DUPLICATE KEY UPDATE 
              name='$name', address='$address', phone='$phone', problem='$problem', 
              description='$description', otp='$otp', expires_at='$expires_at', created_at=NOW()";
    
    if(mysqli_query($connect, $query)) {
        if (ENABLE_EMAIL_LOGGING) {
            error_log("OTP stored successfully in database for $email");
        }
        
        // Send OTP email
        if(sendOTPEmail($email, $otp, $name)) {
            echo json_encode(['status' => 'success', 'message' => 'OTP sent to your email']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to send OTP email']);
        }
    } else {
        if (ENABLE_EMAIL_LOGGING) {
            error_log("Database error while storing OTP: " . mysqli_error($connect));
        }
        echo json_encode(['status' => 'error', 'message' => 'Failed to process request: ' . mysqli_error($connect)]);
    }
    exit;
}

// Handle OTP verification
if(isset($_POST['verify_otp'])) {
    $email = trim(mysqli_real_escape_string($connect, $_POST['customer_email']));
    $entered_otp = trim(mysqli_real_escape_string($connect, $_POST['otp']));
    
    // Debug logging
    if (ENABLE_EMAIL_LOGGING) {
        error_log("OTP Verification Attempt - Email: $email, Entered OTP: $entered_otp");
    }
    
    // First, let's see what's in the database for this email
    $debug_query = "SELECT email, otp, expires_at, TIMESTAMPDIFF(SECOND, NOW(), expires_at) as seconds_remaining FROM temporary WHERE email = '$email'";
    $debug_result = mysqli_query($connect, $debug_query);
    
    if (ENABLE_EMAIL_LOGGING && mysqli_num_rows($debug_result) > 0) {
        $debug_data = mysqli_fetch_assoc($debug_result);
        error_log("Debug - Stored OTP: {$debug_data['otp']}, Expires: {$debug_data['expires_at']}, Seconds remaining: {$debug_data['seconds_remaining']}");
        error_log("Debug - OTP Match: " . ($debug_data['otp'] === $entered_otp ? 'YES' : 'NO'));
        error_log("Debug - Time Valid: " . ($debug_data['seconds_remaining'] > 0 ? 'YES' : 'NO'));
    }
    
    // Check OTP
    $query = "SELECT * FROM temporary WHERE email = '$email' AND otp = '$entered_otp' AND expires_at > NOW()";
    $result = mysqli_query($connect, $query);
    
    if (ENABLE_EMAIL_LOGGING) {
        error_log("OTP Verification Query: $query");
        error_log("OTP Verification Result: " . mysqli_num_rows($result) . " rows found");
    }
    
    if(mysqli_num_rows($result) > 0) {
        $temp_data = mysqli_fetch_assoc($result);
        
        // Move data to main reservations table
        $tracking = 'Order placed';
        $insert_query = "INSERT INTO reservation (name, address, phone, email, problem, description, tracking, payment_status) 
                        VALUES ('{$temp_data['name']}', '{$temp_data['address']}', '{$temp_data['phone']}', 
                               '{$temp_data['email']}', '{$temp_data['problem']}', '{$temp_data['description']}', '$tracking', 'PENDING')";
        
        if(mysqli_query($connect, $insert_query)) {
            $reservation_id = mysqli_insert_id($connect);
            
            // Delete temporary data
            mysqli_query($connect, "DELETE FROM temporary WHERE email = '$email'");
            
            echo json_encode([
                'status' => 'success', 
                'message' => 'Reservation confirmed! Please proceed to payment.',
                'reservation_id' => $reservation_id,
                'service_type' => $temp_data['problem']
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to save reservation']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid or expired OTP']);
    }
    exit;
}
?>
