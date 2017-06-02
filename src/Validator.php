<?php

namespace CodeBase;

if (!defined('CODEBASE')) {
    die('Direct Access Not Allowed');
}
use CodeBase\ErrorHandler as EX;

class Validator
{
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
    * @return array
     */
    public function getCharsToEscape() : array
    {
        return self::$charsToEscape;
    }

    /**
     * @param string $input
     * @return string
     */
    public static function clean(string $input, array $excepts = []) : string
    {
        if (!empty($excepts)) {
            self::$charsToEscape = array_diff(self::$charsToEscape, $excepts);
        }
        return str_replace(self::$charsToEscape, '', $input);
    }

    /**
     * @param string $fieldName
     * @param string $input
     * @param int $min
     * @param int $max
     * @param string $redirectUrl
     * @return string
     */
    public static function validateLength(string $fieldName, string $input, int $min, int $max, $redirectUrl = '/') : string
    {
        if (strlen($input) < $min) {
            EX::triggerCustomError("{$fieldName} is too short, min {$min} characaters", $redirectUrl);
        }

        if (strlen($input) > $max) {
            EX::triggerCustomError("{$fieldName} is too long, max {$max} characaters", $redirectUrl);
        }
        return $input;
    }

    /**
     * @param array $input1
     * @param array $input2
     * @param string $redirectUrl
     * @return string
     */
    public static function validateMatch(array $input1, array $input2, string $redirectUrl = '/') : string
    {
        if ($input1['field'] !== $input2['field']) {
            EX::triggerCustomError("Fields {$input1['fieldName']} does not match {$input2['fieldName']}", $redirectUrl);
        }
        return $input1['field'];
    }


    /**
     * @param string $email
     * @param string $urlRedirect
     */
    public static function validateEmail(string $email, string $urlRedirect = '/')
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            EX::triggerCustomError("Email {$email} is not valid", $urlRedirect);
        }
    }

    /**
     * @param string $email
     * @return bool
     */
    public static function isEmail(string $email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        return true;
    }


    /**
     * @param string $organisationSpecialId
     * @return bool|string
     */
    public static function extractSpecialId(string $organisationSpecialId)
    {
        return substr($organisationSpecialId, 4);
    }


    /**
     * @param string $input
     * @return bool
     */
    public static function isSpecialId(string $input)
    {
        if (substr($input, 0, 1) == '#') {
            return true;
        }
        return false;
    }
}
