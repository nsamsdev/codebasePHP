<?php
declare(strict_types=1);
session_start();
//session_regenerate_id(true);

/*
 * function to se and identify the fatal errors as generic errors
 */
set_error_handler(function ($severity, $message, $filename, $lineno) {
    if (error_reporting() == 0) {
        return;
    }
    if (error_reporting() & $severity) {
        throw new ErrorException($message, 0, $severity, $filename, $lineno);
    }
});
// define the required constants
define('CODEBASE', __DIR__ . DIRECTORY_SEPARATOR);

// require autoloader
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/configs/configs.php';
require __DIR__ . '/helpers/helpers.php';
require __DIR__ . '/helpers/hooks.php';
require __DIR__ . '/app/routes.php';

use CodeBase\HttpRequester;
use CodeBase\ErrorHandler;
use CodeBase\View;

$errorFound = false;
try {
    require 'setup/bootstrap.php';
} catch (\Exception $e) {
    $errorFound = true;
    $error = $e->getMessage();
    $code = $e->getCode();
    $file = $e->getFile();
    $line = $e->getLine();
} catch (\Throwable $e) {
    $errorFound = true;
    $error = $e->getMessage();
    $code = $e->getCode();
    $file = $e->getFile();
    $line = $e->getLine();
} finally {
    if ($errorFound == true) {
        HttpRequester::setResponseCode(404);
        ErrorHandler::logError('Message: ' . $error . '| Code: ' . $code . ' | File: ' . $file . ' | Line: ' . $line);
        View::error404($error);
    }
}
