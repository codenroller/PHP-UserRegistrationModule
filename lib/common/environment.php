<?php 
// set true if production environment else false for development
define ('IS_ENV_PRODUCTION', true);

// configure error reporting options
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', !IS_ENV_PRODUCTION);
ini_set('error_log', '../log/php_errors.txt');

// set time zone to use date/time functions without warnings
date_default_timezone_set('Europe/Paris');

// set custom exception handler
set_exception_handler('customExceptionHandler');

function customExceptionHandler($ex) {
    $dt = new DateTime();//error occurrence time
    
    // Tell the user something unthreatening
    print 'Something unexpected has happened! We were not able to process your request. <br/>';
    print 'If the problem persists do not hesitate to contact system administrator admin@example.com';
    
    // Log more detailed information for a sysadmin to review
    error_log("{$ex->getMessage()} in {$ex->getFile()} @ {$ex->getLine()}\nTrace: {$ex->getTraceAsString()}\n");
}

?>