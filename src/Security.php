<?php

namespace CodeBase;

use CodeBase\SessionManager as S;

if (!defined('CODEBASE')) {
    die('Direct Access Not Allowed');
}

class Security
{
    private static $numberKeys = [
        '1' => 'SLKD',
        '2' => 'MKDU',
        '3' => 'KolE',
        '4' => 'DUM_',
        '5' => 'LDOl',
        '6' => 'KID_',
        '7' => 'FOO_',
        '8' => 'BAR1',
        '9' => 'MAR3',
        '0' => 'WAR7',
    ];

    private static $letterKeys = [
        'a' => '9999',
        'b' => '4345',
        'c' => '4564',
        'd' => '2342',
        'e' => '5464',
        'f' => '2322',
        'g' => '2341',
        'h' => '3453',
        'i' => '4564',
        'j' => '7686',
        'k' => '3453',
        'l' => '2347',
        'm' => '3564',
        'n' => '3453',
        'o' => '2342',
        'p' => '5434',
        'q' => '5867',
        'r' => '7868',
        's' => '5645',
        't' => '2342',
        'u' => '2342',
        'v' => '3453',
        'w' => '5464',
        'x' => '5342',
        'y' => '4342',
        'z' => '3342',
        '_' => '1111'
    ];


    private static $iv;

    private static $encyrptionKey = '98eurhjfi39f9dfKI&^%kdfn!klSOXLA';


    private static function generateSessionValue(int $value) : string
    {
        $valueArray = str_split((string)$value);
        $stringName = '';

        foreach ($valueArray as $number) {
            $stringName .= self::$numberKeys[$number];
        }

        //$value = openssl_encrypt($stringName, 'aes128', self::$encyrptionKey, 0, self::getIv());
        //echo $value . '<br />';
        return $stringName;
    }


    private static function getIv()
    {
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        return $iv;
    }

    private static function generateSessionName(string $name) : string
    {
        $nameArray = str_split(strtolower($name));
        $stringName = '';

        foreach ($nameArray as $letters) {
            $stringName .= $letters;
        }

        //$name = openssl_encrypt($stringName, 'aes128', self::$encyrptionKey, 0, self::getIv());
        //echo $name . '<br />';
        return $stringName;
    }


    public function setSession(string $sessionName, int $value) : bool
    {
        //echo self::generateSessionName('hello');die;
        try {
            S::setItem(self::generateSessionName($sessionName), self::generateSessionValue($value));
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }


    public function getSession(string $sessionName) : int
    {
        $name = strtolower($sessionName);
        //$name = openssl_encrypt($name, 'aes128', self::$encyrptionKey, 0, self::getIv());

        $encryptedValue = S::getItem($name);

        //$trueValue = openssl_decrypt($encryptedValue, 'aes128', self::$encyrptionKey, 0, self::getIv());
        //return $trueValue;
        $switchedArray = array_flip(self::$numberKeys);

        $correctValue = '';
        foreach (str_split($encryptedValue, 4) as $values) {
            $correctValue .= $switchedArray[$values];
        }

        return (int)$correctValue;
    }
}
