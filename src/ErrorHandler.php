<?php

namespace CodeBase;

use CodeBase\SessionManager;
use CodeBase\HttpRequester;
use CodeBase\View;

if (!defined('CODEBASE')) {
    die('Direct Access Not Allowed');
}

/**
 * Class ErrorHandler.
 */
class ErrorHandler
{

    /** @var array */
    private $errors = array(
        404 => 'Page Not Found',
        110 => 'Value is empty',
        111 => 'Values to not match',
        222 => 'General Error',
    );

    /**
     * @var
     */
    private static $instance;

    /** @var array */
    private $specialErrors = array();

    /**
     * @param string $message
     *
     * @return bool
     */
    public function log(string $message)
    {
        return true;
    }

    /**
     * @param $errorCode
     * @param string $messageHint
     * @param string $customUrl
     *
     * @throws \Exception
     */
    public function throwError(int $errorCode, string $messageHint = '', string $customUrl = '')
    {
        $this::logError($messageHint);
        $messageToLog = $this->errors[$errorCode] . ' | ' . $messageHint;
        $this->log($messageToLog);
        $this->checkErrorSpecial($errorCode, $messageHint, $customUrl);
    }

    /**
     *
     * @param string $message
     * @param string $url
     * @param string $type
     */
    public static function triggerCustomError(string $message, string $url, string $type = 'error')
    {
        SessionManager::setItem('message', $message);
        SessionManager::setItem('messageType', $type);
        redirectToPage($url);
    }

    /**
    * @return $this
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    /**
    * @param string $message
     */
    public function show404Error(string $message)
    {
        View::error404($message);
    }

    /**
     * @param string $message
     */
    public static function logError(string $message)
    {
        $data = [
            'timestamp' => date('Y-m-d H:i:s:u'),
            'message' => $message,
            'ip' => HttpRequester::getUserIp(),
            'id' => SessionManager::getItem('userSessionId'),
        ];

        $fp = fopen(LOG_FILE_PATH, 'a');
        fwrite($fp, json_encode($data) . PHP_EOL);
        fclose($fp);
    }
}
