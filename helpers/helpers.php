<?php

if (!defined('CODEBASE')) {
    die('Direct Access Not Allowed');
}

/**
 * @param $url
 */
function redirectToPage($url)
{
    header('Location: ' . $url);
    exit();
}

/**
 *
 */
function logout()
{
    session_destroy();
    redirectToPage('/');
}


/**
 * @param array $array
 * @param string $keyName
 * @return array
 */
function groupBy($array, $keyName)
{
    $newArray = [];
    if (empty($array)) {
        return [];
    }
    foreach ($array as $data) {
        if (!isset($data[$keyName])) {
            return [];
        }
        $organisation = $data[$keyName];
        unset($data[$keyName]);
        $newArray[$organisation][] = $data;
    }
    return $newArray;
}


/**
 * @param $array1Large
 * @param $array2
 * @param $keyName
 * @return mixed
 */
function groupByInner($array1Large, $array2, $keyName)
{
    foreach ($array1Large as &$data) {
        foreach ($array2 as $user) {
            if (key($data) == $user[$keyName]) {
                array_unshift($data, $user);
            }
        }
        unset($data);
    }
    return $array1Large;
}

/**
 * @param $baseUrl
 * @param $params
 */
function redirectWithParams($baseUrl, $params)
{
    redirectToPage($baseUrl . '?' . http_build_query($params));
}

/**
 * @param $absuleDir
 * @param string $extinsion
 * @return string
 */
function generateRandomVarcharForLocation($absuleDir, $extinsion = '.jpg')
{
    $stringName = md5(random_int(1, 1000) . 'Hello' . date('y-m-d'));
    while (file_exists($absuleDir . $stringName . $extinsion)) {
        $stringName = md5(random_int(2000, 10000) . 'testtest');
    }
    return $stringName . $extinsion;
}


/**
 * @param string $url
 * @param array $params
 * @return void
 */
function sendOutsideWithParams(string $url, array $params)
{
    $url = $url . '?' . http_build_query($params);
    redirectToPage($url);
}
