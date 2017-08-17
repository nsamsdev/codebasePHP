<?php

if (!defined('CODEBASE')) {
    die('Direct Access Not Allowed');
}
use CodeBase\Managers\HomeManager;
use CodeBase\Router;
use CodeBase\SessionManager as S;

/**
 * Class General
 */
class Home extends \CodeBase\Controller
{
    private $manager;

    /** @var array */
    private $pageHeaders = [
        'title' => '',
        'description' => '',
        'keywords' => '',
        'canonical' => '',
    ];

    /**
     * General constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->manager = new HomeManager();
    }

    /**
     * @param array $params
     */
    public function index(array $params)
    {
        $this->setTags();
        $this->setControllerViewData('name', 'codebase');
        $this->view->render('home', $this->getControllerViewData());
    }
}
