<?php

namespace CodeBase\Traits;

use CodeBase\SessionManager;
use CodeBase\Security;

if (!defined('CODEBASE')) {
    die('Direct Access Not Allowed');
}

/**
 * Class Games.
 */
trait Helper
{

    /** @var   */
    public $message;

    /**
     * @param string $message
     * @param string $urlToRedirectTo
     */
    public function flashMessage($message, $urlToRedirectTo, string $type = 'success')
    {
        SessionManager::setItem('message', $message);
        SessionManager::setItem('messageType', $type);
        redirectToPage($urlToRedirectTo);
    }

    /**
     * @param string $sessionName
     */
    public static function checkAuth(string $sessionName)
    {
        if (Security::getSession($sessionName) == null) {
            SessionManager::setItem('message', 'You are not allowed to view this page');
            redirectToPage(BASE_URL);
        }
    }
}
