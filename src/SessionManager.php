<?php

namespace CodeBase;

if (!defined('CODEBASE')) {
    die('Direct Access Not Allowed');
}

/**
 * Class SessionManager.
 */
class SessionManager
{

    /**
     * @param string $itemName
     * @param string $itemValue
     */
    public static function setItem(string $itemName, string $itemValue)
    {
        @$_SESSION[$itemName] = $itemValue;
    }

    /**
     * @param string $itemName
     */
    public static function getItem(string $itemName)
    {
        return @isset($_SESSION[$itemName]) ? $_SESSION[$itemName] : null;
    }

    
    /**
     *
     */
    public static function killSession()
    {
        session_destroy();
    }

    /**
     * @param string $itemNme
     */
    public static function removerItem(string $itemNme)
    {
        unset($_SESSION[$itemNme]);
    }
}
