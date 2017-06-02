<?php

namespace CodeBase;

if (!defined('CODEBASE')) {
    die('Direct Access Not Allowed');
}

/**
 * Class Core.
 */
class Core
{

    /**
     * @var array
     */
    private $configs;

    /**
     * @var unknown
     */
    public static $configStatic;

    /**
     * Core constructor.
     *
     * @param $configs
     */
    public function __construct($configs)
    {
        $this->configs = $configs;
        self::$configStatic = $configs;
    }

    /**
     * @param string $filePath
     *
     * @throws \Exception
     */
    public function loader($filePath)
    {
        if (!file_exists($filePath)) {
            throw new \Exception('Unable to locate model');
        }

        @require $filePath;
    }

    /**
     * @return mixed
     */
    public function getBaseDir()
    {
        return self::$configStatic['APP_BASE_DIR'];
    }

    /**
     * @return string
     */
    public function getControllersBase()
    {
        return self::$configStatic['APP_BASE_DIR'] . 'controllers/';
    }

    /**
     * @param $controller
     *
     * @return string
     */
    public function getControllerPath($controller)
    {
        $controller = ucwords($controller);

        return $this->getControllersBase() . $controller . '.php';
    }

    /**
     * @return string
     */
    public function getViewsDir()
    {
        return self::$configStatic['VIEWS_DIR'];
    }
}
