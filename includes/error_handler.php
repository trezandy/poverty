<?php
// Set error log path
$error_log_path = __DIR__ . '/../logs/php_error.log';

// Ensure error logging is enabled
ini_set('log_errors', 1);
ini_set('error_log', $error_log_path);
error_reporting(E_ALL);

// Custom error handler
function customErrorHandler($errno, $errstr, $errfile, $errline) {
    $error_message = date('[Y-m-d H:i:s]') . " Error: [$errno] $errstr in $errfile on line $errline\n";
    error_log($error_message);
    
    // Don't show errors in production
    if(getenv('ENVIRONMENT') !== 'production') {
        echo "<pre>$error_message</pre>";
    }
    
    return true;
}

// Set the custom error handler
set_error_handler('customErrorHandler');

// Custom exception handler
function customExceptionHandler($exception) {
    $error_message = date('[Y-m-d H:i:s]') . " Exception: " . $exception->getMessage() . 
                    "\nFile: " . $exception->getFile() . 
                    "\nLine: " . $exception->getLine() . 
                    "\nTrace: " . $exception->getTraceAsString() . "\n";
    error_log($error_message);
    
    // Don't show errors in production
    if(getenv('ENVIRONMENT') !== 'production') {
        echo "<pre>$error_message</pre>";
    }
}

// Set the custom exception handler
set_exception_handler('customExceptionHandler');
?> 