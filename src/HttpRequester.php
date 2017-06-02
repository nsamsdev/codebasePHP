<?php

namespace CodeBase;

use Httpful\Request as Requester;
use Httpful\Mime;
use CodeBase\ErrorHandler as EX;

if (!defined('CODEBASE')) {
    die('Direct Access Not Allowed');
}

/**
 * Class HttpRequester.
 */
class HttpRequester
{

    /**
     * @var Core
     */
    private $coreInstance;

    
    /**
     *
     * @var ErrorHandler
     */
    private $errorHandler;

    /**
     * HttpRequester constructor.
     *
     * @param Core $core
     */
    public function __construct(Core $core)
    {
        $this->coreInstance = $core;
        $this->errorHandler = EX::getInstance();
    }


    /**
     *
     * @param array $data
     * @param string $dataType
     * @param string $url
     * @return mixed
     */
    public static function callUrlWithData(array $data, string $dataType, string $url)
    {
        switch ($dataType) {
            case 'post':
                $response = self::callWithPost($data, $url);
                break;
            case 'json':
                $response = self::callWithJson($data, $url);
                break;
            case 'xml':
                $response = self::callWithXml($data, $url);
                break;
            default:
               $this->errorHandler->throwError(400, 'Invalid Json Response', '/');
        }
        return $response;
    }


    /**
    * @param array $xmlData
    * @param string $url
     */
    private static function callWithXml(array $xmlData, string $url)
    {
        return Requester::get($url)
            ->expectsXml()
            ->send();
    }

    /**
     * @param array $jsonDataArray
     * @param string $url
     * @return mixed
     */
    private static function callWithJson(array $jsonDataArray, string $url)
    {
        return Requester::get($url)
            ->expectsJson()
            ->send();
    }

    /**
     * @param array $postData
     * @param string $url
     */
    private static function callWithPost(array $postData, string $url)
    {
        return Requester::post($url)
            ->body(json_encode($postData))
            ->send();
    }

    /**
     * @param string $getName
     *
     * @return string
     */
    public static function get(string $getName)
    {
        return $_GET[$getName] ?? null;
    }

    /**
     * @return array
     */
    public static function allGet()
    {
        return $_GET;
    }

    /**
     * @param string $postName
     *
     * @return string
     */
    public static function post(string $postName)
    {
        return $_POST[$postName] ?? null;
    }

    /**
     * @return mixed
     */
    public static function allPost()
    {
        return $_POST;
    }

    /**
     * @param string $fileName
     *
     * @return array
     */
    public static function file(string $fileName)
    {
        return $_FILES[$fileName] ?? null;
    }

    /**
     * @return mixed
     */
    public static function allFiles()
    {
        return $_FILES;
    }

    /**
     * @param $code
     */
    public static function setResponseCode(int $code)
    {
        http_response_code($code);

        return;
    }

    /**
     * @param string $url
     *
     * @return string
     */
    public static function requestUrl(string $url): string
    {
        $response = Requester::get($url)
                ->expectsType(Mime::JSON)
                ->send();

        return $response;
    }

    /**
     * @param string $url
     * @param array  $headers
     *
     * @return string
     */
    public static function sendRequestWithHeaders(string $url, array $headers): string
    {
        $response = Requester::get($url)
                ->addHeaders($headers)
                ->expectsType(Mime::JSON)
                ->send();

        return $response;
    }

    /**
     * @return string
     */
    public static function getUserIp(): string
    {
        $ip = null;

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip ?? '';
    }

    /**
     * @param string $siteAccessing
     * @return bool
     */
    public static function getWebsite(string $siteAccessing): bool
    {
        return (strpos($_SERVER['HTTP_HOST'], 'blabla') !== false) ? true : false;
    }

    /**
     * @return string
     */
    public static function requestMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }
}
