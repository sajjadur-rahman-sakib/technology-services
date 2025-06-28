<?php
/**
 * PHPMailer Autoloader
 */

// Define the PHPMailer namespace path
$phpmailer_path = __DIR__ . '/phpmailer/phpmailer/src/';

// Autoload function for PHPMailer
spl_autoload_register(function ($classname) use ($phpmailer_path) {
    // Only load PHPMailer classes
    if (strpos($classname, 'PHPMailer\\PHPMailer\\') === 0) {
        $classname = str_replace('PHPMailer\\PHPMailer\\', '', $classname);
        $filename = $phpmailer_path . $classname . '.php';
        
        if (file_exists($filename)) {
            require_once $filename;
        }
    }
});

// Load required PHPMailer classes
require_once $phpmailer_path . 'PHPMailer.php';
require_once $phpmailer_path . 'SMTP.php';
require_once $phpmailer_path . 'Exception.php';
?>
