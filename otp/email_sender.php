<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once 'email_config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

/**
 * Gmail SMTP Email Sender using PHPMailer
 * This will actually send emails to user's inbox
 */
class EmailSender {
    private $username;
    private $password;
    private $fromName;
    
    public function __construct($username, $password, $fromName = '') {
        $this->username = $username;
        $this->password = $password;
        $this->fromName = $fromName ?: 'Sakib IT Services';
    }
    
    public function sendEmail($to, $subject, $htmlBody, $toName = '') {
        $mail = new PHPMailer(true);
        
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = SMTP_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = $this->username;
            $mail->Password   = $this->password;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = SMTP_PORT;
            
            // Enable verbose debug output (comment out in production)
            // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            
            // Recipients
            $mail->setFrom($this->username, $this->fromName);
            $mail->addAddress($to, $toName);
            
            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $htmlBody;
            
            // Alternative plain text body
            $mail->AltBody = strip_tags($htmlBody);
            
            $result = $mail->send();
            
            if ($result) {
                error_log("✅ Email sent successfully to: $to");
                return true;
            } else {
                error_log("❌ Failed to send email to: $to");
                return false;
            }
            
        } catch (Exception $e) {
            error_log("❌ Email sending failed: {$mail->ErrorInfo}");
            error_log("Exception: " . $e->getMessage());
            return false;
        }
    }
    
    public function testConnection() {
        $mail = new PHPMailer(true);
        
        try {
            $mail->isSMTP();
            $mail->Host = SMTP_HOST;
            $mail->SMTPAuth = true;
            $mail->Username = $this->username;
            $mail->Password = $this->password;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = SMTP_PORT;
            $mail->SMTPDebug = SMTP::DEBUG_CONNECTION;
            
            // Test connection without sending
            if ($mail->smtpConnect()) {
                $mail->smtpClose();
                return true;
            }
            return false;
            
        } catch (Exception $e) {
            error_log("SMTP Connection test failed: " . $e->getMessage());
            return false;
        }
    }
}
?>
