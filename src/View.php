<?php

namespace CodeBase;

if (!defined('CODEBASE')) {
    die('Direct Access Not Allowed');
}
use CodeBase\Router;

/**
 * Class View.
 */
class View extends \Smarty
{

    /** @var array */
    private $siteDefaultSettings = array(
        'keywords' => '',
        'description' => '',
        'title' => ''
    );
    
    /**
     *
     * @var array
     */
    private $assignedData = [];

    /**
     * View constructor.
     *
     * @param $defaultSettings
     */
    public function __construct(array $defaultSettings)
    {
        parent::__construct();
        $this->setTemplateDir(TEMPLATE_DIR);
        $this->setCacheDir(CACHE_DIR);
        $this->setCompileDir(COMPILE_DIR);
        $this->setConfigDir(CONFIG_DIR);
        $this->assign('url', BASE_URL);
        $this->assign('canonical', WEBSITE_ACCESS . CODEBASE_URL_PATH);
        $this->setDefaultModifiers(array(
            'escape:"htmlall"',
        ));
    }

    /**
     * default 404 and general break error handle.
     *
     * @param string $message
     */
    public static function error404(string $message)
    {
        $view = new self(array());
        $view->assign('route', Router::getAllRoutes());
        $view->render('error404', array(
            'message' => $message,
            'mode' => PROJECT_STATE,
        ));
    }

    /**
     * to display the template and assign data.
     *
     * @param string $templateName
     * @param array  $data
     */
    public function render(string $templateName, array $data = array())
    {
        $this->assign('route', Router::getAllRoutes());
        $this->setSiteHeaders($this->siteDefaultSettings);
        foreach ($data as $key => $value) {
            if (!in_array($key, $this->assignedData)) {
                $this->assign($key, $value);
                $this->assignedData[$key] = $value;
            }
        }
        $this->display('header.tpl');
        $this->display($templateName . '.tpl');
        $this->display('footer.tpl');
    }



    /**
    * @param array
     */
    public function setSiteSettings(array $settings)
    {
        $this->siteDefaultSettings = $settings;
    }

    /**
     * @param array $headers
     */
    public function setSiteHeaders(array $headers = array())
    {
        foreach ($headers as $header => $value) {
            $this->assign($header, $value);
        }
    }
}
