<?php

namespace CodeBase;

use CodeBase\Hooks;
use CodeBase\Traits\Helper as Help;
use CodeBase\Website;

if (!defined('CODEBASE')) {
    die('Direct Access Not Allowed');
}

/**
 * Class Router.
 */
class Router
{
    
    /**
     * @var Helper
     */
    use Help;

    
    /**
     *
     * @var array
     */
    private static $customUrls = [];

    
    /**
     *
     * @var array
     */
    private static $methodUrlRoutes = [];

    /**
     * @var
     */
    private static $numberOfRoutes;

    /**
     * @var
     */
    private static $extractedUrls;

    /**
     * @var array
     */
    private static $routerUrls = [
        'controller' => '',
        'method' => '',
        'params' => [],
    ];

    /**
     * @var string
     */
    private static $urlPath;

    /**
     * @var array
     */
    private static $routes = array();

    /**
     * @var array
     */
    private static $routesAccessMethod = array();

    /**
     * @var bool
     */
    private static $userControllerOnly = false;

    /**
     * @var bool
     */
    private static $userParamOnly = false;


    /**
     * Description
     * @return type
     */
    public static function getAllRoutes()
    {
        $allUrsl = self::$customUrls;
        array_walk($allUrsl, function (&$value, $key) {
            if ($key != 'home') {
                $value = BASE_URL . $value;
            }
            //$value = BASE_URL . $value;
        });
        return $allUrsl;
    }

    /**
     * @return array
     */
    public static function getRoutes()
    {
        $queryString = HttpRequester::get('url');
        $queryString = substr($queryString, 0, strpos($queryString, '?'));
        self::$urlPath = $queryString;

        self::$extractedUrls = explode('/', self::$urlPath);
        $cleanUrls = array();
        for ($i = 0; $i < count(self::$extractedUrls); ++$i) {
            if (self::$extractedUrls[$i] == '' || empty(self::$extractedUrls[$i])) {
                unset(self::$extractedUrls[$i]);
            } else {
                $cleanUrls[] = self::$extractedUrls[$i];
            }
        }

        self::$extractedUrls = $cleanUrls;
        self::$numberOfRoutes = count(self::$extractedUrls);

        if (self::$numberOfRoutes == 0) {
            self::$routerUrls['params'] = DEFAULT_PARAM;
            self::$userParamOnly = true;
        } elseif (self::$numberOfRoutes == 1) {
            self::$routerUrls['controller'] = self::$extractedUrls[0];
            self::$routerUrls['params'] = DEFAULT_PARAM;
            self::$userControllerOnly = true;
            self::$userParamOnly = true;
        } elseif (self::$numberOfRoutes == 2) {
            self::$routerUrls['controller'] = self::$extractedUrls[0];
            if (!self::$extractedUrls[1] || self::$extractedUrls[1] == '') {
                self::$routerUrls['method'] = DEFAULT_METH;
                self::$routerUrls['params'] = DEFAULT_PARAM;
            } elseif ((int) self::$extractedUrls[1] != 0 && is_numeric(self::$extractedUrls[1])) {
                self::$routerUrls['method'] = DEFAULT_METH;
                self::$routerUrls['params'][] = self::$extractedUrls[1];
            } else {
                self::$routerUrls['method'] = self::$extractedUrls[1];
                self::$routerUrls['params'] = DEFAULT_PARAM;
            }
        } elseif (self::$numberOfRoutes >= 3) {
            self::$routerUrls['controller'] = self::$extractedUrls[0];
            self::$routerUrls['method'] = self::$extractedUrls[1];
            for ($i = 2; $i < (self::$numberOfRoutes); ++$i) {
                self::$routerUrls['params'][] = self::$extractedUrls[$i];
            }
        }
        return self::$routerUrls;
    }


    /**
     * Description
     * @param string $urlName
     * @return type
     */
    public static function getUrl(string $urlName) : string
    {
        if (array_key_exists($urlName, self::$customUrls) && $urlName != 'home') {
            return BASE_URL . self::$customUrls[$urlName];
        }
        return '/';
    }

    /**
     * @param unknown $requireBasesForController
     *
     * @throws \Exception
     */
    public static function executeRoute()
    {
        $routesData = self::getRoutes();
        if (self::$userParamOnly == false) {
            $route = strtolower($routesData['controller'] . '/' . $routesData['method']);
        } elseif (self::$userControllerOnly == true) {
            $route = strtolower($routesData['controller']);
        } else {
            $route = '/';
        }
        //die($route);
        if (!array_key_exists($route, self::$routes)) {
            throw new \Exception('required route not found');
        }

        $routePath = explode('@', self::$routes[$route]['execute']);
        if (self::$routes[$route]['auth'] == true) {
            Help::checkAuth(self::$routes[$route]['type']);
        }
        self::validateController($routePath[0]);
        $controllerPath = (new Core(Core::$configStatic))->getControllerPath($routePath[0]);

        //get the controller file
        require $controllerPath;

        $controller = new $routePath[0]();
        $method = $routePath[1];
        self::validateMethod($controller, $method);
        $params = $routesData['params'];

        if (!array_key_exists($route, self::$routesAccessMethod)) {
            throw new \Exception('route access method is not defined');
        }

        $routeAccessMethods = self::$routesAccessMethod[$route];

        if (!in_array(HttpRequester::requestMethod(), $routeAccessMethods)) {
            throw new \Exception('You are not accessing this method correctly');
        }
        //call the hooks
        Hooks::callPreHooks();
        $controller->$method($params);
        Hooks::callPostHooks();
    }

    /**
     * @param unknown $controller
     *
     * @throws \Exception
     */
    public static function validateController($controller)
    {
        $base = (new Core(Core::$configStatic))->getControllersBase();
        $controller = ucwords($controller);
        $path = $base . $controller . '.php';
        if (!file_exists($path)) {
            throw new \Exception('Unable to locate controller ' . $controller . 'in ' . $path);
        }
        return;
    }

    /**
     * call the default controller and method based on the contants set on the config file DEFAULT_CONT, DEFAULT_METH.
     */
    public static function setUpMainController()
    {
        self::$routerUrls['controller'] = DEFAULT_CON;
        self::$routerUrls['method'] = DEFAULT_METH;
        self::$routerUrls['params'][] = DEFAULT_PARAM;
    }

    /**
     * @param $controller
     * @param $method
     *
     * @throws \Exception
     */
    public static function validateMethod($controller, $method)
    {
        $classMethods = get_class_methods($controller);

        if (!in_array($method, $classMethods)) {
            throw new \Exception('Method ' . $method . ' was not found for class ' . $controller);
        }
    }

    /**
     * Description
     * @param type $pathName
     * @param type $routerPath
     * @param type $toExecute
     * @param type|array $routeAccess
     * @param array|array $websiteTags
     * @param type|bool $authRequired
     * @param type|string $authType
     * @return type
     */
    public static function add($pathName, $routerPath, $toExecute, $routeAccess = array('GET'), array $websiteTags = [], $authRequired = false, $authType = 'user')
    {
        self::$routes[$routerPath] = array(
            'execute' => $toExecute,
            'auth' => $authRequired,
            'type' => $authType,
        );
        self::$customUrls[$pathName] = $routerPath;
        self::$routesAccessMethod[$routerPath] = $routeAccess;
        $methodName = substr($toExecute, strpos($toExecute, '@') + 1);
        self::$methodUrlRoutes[$methodName] = $pathName;
        if (!empty($websiteTags) && !empty($websiteTags['title'])) {
            Website::setRouteTags($methodName, $websiteTags);
        }
    }
}
