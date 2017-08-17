<?php

use CodeBase\Router;

if (!defined('CODEBASE')) {
    die('Direct Access Not Allowed');
}

//general
Router::add('home', '/', 'Home@index', ['GET'], [
    'title' => '',
    'description' => '',
    'keywords' => ''
]);
