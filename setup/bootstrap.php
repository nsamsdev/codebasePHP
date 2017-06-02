<?php
if (!defined('CODEBASE')) {
    die('Direct Access Not Allowed');
}

use CodeBase\Core;
use CodeBase\View;
use CodeBase\Router;
use CodeBase\Emailer;
use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule();

$capsule->addConnection(array(
    'driver' => CODEBASE_DB_DRIVER,
    'host' => CODEBASE_DB_HOST,
    'database' => CODEBASE_DB_NAME,
    'username' => CODEBASE_DB_USER,
    'password' => CODEBASE_DB_PASS,
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => '',
));

$capsule->setAsGlobal();
$capsule->bootEloquent();

$GLOBALS['RFC'] = [
    'core' => new Core($configs),
    'view' => new View($defaultSiteSettings),
    'email' => new Emailer
];
$GLOBALS['customText'] = require(CODEBASE . '/configs/custom_text.php');
Router::executeRoute();
