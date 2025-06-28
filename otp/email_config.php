<?php
// Set timezone for consistent time handling
date_default_timezone_set('Asia/Dhaka'); // Change this to your local timezone

// SMTP Configuration for OTP Email
// Update these settings according to your email provider

define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587); // Use 587 for TLS or 465 for SSL
define('SMTP_USERNAME', 'sajjadur18@cse.pstu.ac.bd');
define('SMTP_PASSWORD', 'uojc gfem tjdi fqbv'); // App password for Gmail
define('SMTP_SECURE', 'ssl'); // 'tls' or 'ssl'
define('SMTP_FROM_NAME', 'Sakib IT Services');

// OTP Settings
define('OTP_LENGTH', 6);
define('OTP_EXPIRY_MINUTES', 5);

// Email settings
define('ENABLE_EMAIL_LOGGING', true); // Set to false in production
define('DEVELOPMENT_MODE', false); // Set to true to only log OTPs without sending emails
?>
