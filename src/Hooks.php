<?php

namespace CodeBase;

use CodeBase\View;
use CodeBase\ErrorHandler as EX;

if (!defined('CODEBASE')) {
    die('Direct Access Not Allowed');
}

/**
 * Class Router.
 */
class Hooks
{
    
    /**
     *
     * @var array
     */
    private static $hooksArray = [
        'pre' => [],
        'post' => []
    ];

    
    /**
     *
     * @var array
     */
    private static $vars = [];
    
    /**
     *
     * @var unknown
     */
    private static $ex;

    /**
     * @return void
     */
    public static function hooksInit()
    {
        self::$ex = EX::getInstance();
    }

    /**
     * @param Clousre $clousre
     * @param string $type
     */
    public static function addHook($clousre, string $type)
    {
        if (!isset(self::$hooksArray[$type])) {
            View::error404('Invalid hook type');
        }
        self::$hooksArray[$type][] = $clousre;
    }

    /**
     * @return void
     */
    public static function callPreHooks()
    {
        foreach (self::$hooksArray['pre'] as $clousre) {
            $clousre();
        }
    }

    /**
     * @return void
     */
    public static function callPostHooks()
    {
        foreach (self::$hooksArray['post'] as $clousre) {
            $clousre();
        }
    }
}
