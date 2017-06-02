<?php

namespace CodeBase\Libraries;

/**
 * Class Messages
 * @package CodeBase\Libraries
 */
class Messages
{
    /**
     * @var string
     */
    public static $message = '';

    /**
     * @param string $message
     */
    public static function setMessage(string $message)
    {
        self::$message = $message;
    }

    /**
     * @return string $message
     */
    public static function getMessage() : string
    {
        return self::$message;
    }
}
