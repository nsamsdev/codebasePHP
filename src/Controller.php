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
use CodeBase\Website;

/**
 * Class Controller.
 */
class Controller
{
    use Help;

    /**
     * @var string
     */
    private $childName;

    /** @var Core */
    public $core;

    /** @var View */
    public $view;

    /** @var Emailer */
    public $email;

    /**
     * @var array
     */
    protected static $charsToEscape = [
        '!',
        "'",
        '"',
        '*',
        '@',
        '^',
        '#',
        '~',
        '`',
        '+',
        '=',
        ')',
        '(',
        ']',
        '[',
        '{',
        '}',
        '&',
        '%',
        '$',
        '£',
        '¬',
        '|',
        '\\',
        '/',
        '?',
        '<',
        '>',
        ':',
        ';',
    ];

    /**
     * @var array
     */
    private $controllerData = [];

    private $validator;

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        //echo '<pre>';
        //var_dump($GLOBALS);
        //die;

        $rfc = $GLOBALS['RFC'];
        $this->core = $rfc['core'];
        $this->view = $rfc['view'];
        $this->email = $rfc['email'];
        $this->childName = get_class($this);
        //echo $this->childName;die;
        $this->validator = new Validator();
        $message = $this->getMessage();
        $this->view->assign('message', $message['message']);
        $this->view->assign('messageType', $message['messageType']);
    }


    /**
     * @param string $url
     * @param array $pparams
     * @return void
     */
    public function sendOutsideWithParams($url, $params)
    {
        sendOutsideWithParams($url, $params);
    }


    /**
     *
     */
    protected function setTags()
    {
        $function = debug_backtrace();
        $this->view->setSiteSettings(Website::getRouteTags($function[1]['function']));
    }
 
    /**
     * @param string $key
     * @param $value
     */
    public function setControllerViewData(string $key, $value)
    {
        $this->controllerData[$key] = $value;
    }

    /**
     * @return array
     */
    public function getControllerViewData(): array
    {
        return $this->controllerData;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->childName;
    }

    /**
     * @return array
     */
    public function getMessage(): array
    {
        $message = !is_null(SessionManager::getItem('message')) ? SessionManager::getItem('message') : '';
        $messageType = !is_null(SessionManager::getItem('messageType')) ? SessionManager::getItem('messageType') : '';

        SessionManager::removerItem('message');
        SessionManager::removerItem('messageType');
        return [
          'message' => $message,
          'messageType' => $messageType
       ];
    }


    /**
     * @param string $sessionName
     */
    public function checkForProtectionSession(string $sessionName)
    {
        if (S::getItem($sessionName) == null) {
            $this->flashMessage('You must be logged in first', Router::getUrl('ec_l'));
        }
    }

  

    /**
     * @return string
     */
    public function generateRandomString() : string
    {
        return md5(uniqid());
    }

    /**
     * @param string $sessionName
     * @param Models\Users $users
     * @param Models\Deleted $deleted
     */
    public function checkForProtectionFull(string $sessionName, \CodeBase\Models\Users $users, string $deleted)
    {
        $this->checkForProtectionSession($sessionName);
        $userId = S::getItem($sessionName);
        $this->checkForProtectionDB($userId, $users, $deleted);
    }
}
