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

    public function encyrpt(string $sessionName, $value) : array
    {
        $sessionName = strtolower($sessionName);
        $n = '';
        $s = '';

        //set sessiion name
        foreach (str_split(trim($sessionName)) as $letterOrNumber) {
            if (is_int($letterOrNumber) || $letterOrNumber == ' ') {
                throw new \Exception('can not set an int or have a space in a session name');
            }
            $n.= self::$letterKeys[$letterOrNumber];
        }

        //set session value
        foreach (str_split(trim($value)) as $lON) {
            if ($lON == ' ') {
                throw new \Exception('session value can not have empty space');
            }
            $s.= self::$letterKeys[$lON] ?? self::$numberKeys[$lON];
        }
        //$sessionValue = '';
        return [
            'name' => $n,
            'value' => $s
        ];
    }


    public function decrypt(string $value)
    {
        $n = '';
        $flipped1 = array_flip(self::$numberKeys);
        $flipped2 = array_flip(self::$letterKeys);
        foreach (str_split($value, 4) as $d) {
            $n.= $flipped1[$d] ?? $flipped2[$d];
        }

        return $n;
    }

    public function getSessionName(string $sessionName) : string
    {
        $name = strtolower($sessionName);
        $s = '';
        //$flipped = array_flip(self::$letterKeys);
        foreach (str_split($name) as $d) {
            $s.= self::$letterKeys[$d];
        }

        return $s;
    }



    public function setSession(string $sessionName, $value) : bool
    {
        //echo self::generateSessionName('hello');die;
        $encryptedData = (new self)->encyrpt($sessionName, $value);
        try {
            S::setItem($encryptedData['name'], $encryptedData['value']);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }


    public function getSession(string $sessionName)
    {
        $name = (new self)->getSessionName($sessionName);
        //$name = openssl_encrypt($name, 'aes128', self::$encyrptionKey, 0, self::getIv());

        $encryptedValue = S::getItem($name);

        if (is_null($encryptedValue)) {
            return null;
        }

        //$trueValue = openssl_decrypt($encryptedValue, 'aes128', self::$encyrptionKey, 0, self::getIv());
        //return $trueValue;
        return (new self)->decrypt($encryptedValue);
    }
}
