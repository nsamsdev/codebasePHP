<?php

if (!defined('CODEBASE')) {
    die('Direct Access Not Allowed');
}
define('APP_PATH', CODEBASE . 'app/views/');
define('MODEL_PATH', CODEBASE . 'app/models/');
//check secure eccess
if (!empty($_SERVER['HTTPS'])) {
    define('WEBSITE_ACCESS', 'https://');
} else {
    define('WEBSITE_ACCESS', 'http://');
}
define('CODEBASE_URL_PATH', $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
define('CODEBASE_EMAIL_HOST', '');
define('CODEBASE_EMAIL_USERNAME', '');
define('CODEBASE_EMAIL_PASSWORD', '');
define('STORAGE_LOCATION', __DIR__ . '/../public/storage/');
define('CODEBASE_EMAIL_PORT', '');
define('CODEBASE_DB_DRIVER', 'mysql');
define('CODEBASE_DB_HOST', '');
define('CODEBASE_DB_NAME', '');
define('CODEBASE_DB_USER', '');
define('CODEBASE_DB_PASS', '');
define('CODEBASE_DB_PORT', 3306);
define('LOG_FILE_PATH', CODEBASE . 'logs/logs.logs');
define('SENDGRID_API_KEY', '');
define('SEND_GRID_TEMPLATE_ID_1', '');
define('TEMPLATE_DIR', APP_PATH . 'templates');
define('CACHE_DIR', APP_PATH . 'cache');
define('CONFIG_DIR', APP_PATH . 'configs');
define('COMPILE_DIR', APP_PATH . 'compile');
define('BASE_URL', '/codebase/');
define('DEFAULT_CON', 'home');
define('DEFAULT_METH', 'index');
define('DEFAULT_PARAM', []);
define('MAIL_DEBUG', false);
define('WEBSITE_HOST', 'http://' . $_SERVER['HTTP_HOST'] . '/');
define('MESSAGES_DIR', CODEBASE . 'app/messages/');


/** @var $defaultSiteSettings */
$defaultSiteSettings = [
    'title' => '',
    'keywords' => '',
    'description' => ''
];

/** @var $configs */
$configs = array(
    'APP_BASE_DIR' => CODEBASE . 'app/',
    'PROJECT_STATE' => 'development',
    'VIEWS_DIR' => CODEBASE . 'app/views/',
);

define('PROJECT_STATE', 'development');
$GLOBALS['WEBSITE_HOST'] = WEBSITE_HOST;
