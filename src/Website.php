<?php

namespace CodeBase;

if (!defined('CODEBASE')) {
    die('Direct Access Not Allowed');
}

use CodeBase\Traits\Helper as Help;
use CodeBase\ErrorHandler as ErHandler;
use CodeBase\Validator;
use CodeBase\SessionManager as S;
use CodeBase\Router;

/**
 * Class Controller.
 */
class Website
{
    /**
     * @var type
     */
    private static $websiteTags = [];

    /**
     * @var type
     */
    private static $defaultTags = [
        'title' => '',
        'description' => '',
        'keywords' => ''
    ];

    /**
     * Description
     * @param string $calledMethodName
     * @return type
     */
    public static function getRouteTags(string $calledMethodName) : array
    {
        if (array_key_exists($calledMethodName, self::$websiteTags)) {
            return self::$websiteTags[$calledMethodName];
        }
        return self::$defaultTags;
    }

    /**
     * Description
     * @param string $methodName
     * @param array|array $routeData
     * @return type
     */
    public static function setRouteTags(string $methodName, array $routeData = [])
    {
        self::$websiteTags[$methodName] = $routeData;
    }

    /**
     * Description
     * @return type
     */
    public static function getAllTags()
    {
        echo '<pre>';
        var_dump(self::$websiteTags);
        die;
    }
}
