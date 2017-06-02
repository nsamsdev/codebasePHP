<?php

namespace CodeBase;

if (!defined('CODEBASE')) {
    die('Direct Access Not Allowed');
}
use Imagine\Imagick\Imagine;

class Uploader
{
    
    /**
     *
     * @var string
     */
    const IMAGES_LOCATION = STORAGE_LOCATION;
    
    
    /**
     *
     * @var unknown
     */
    private $options;

    
    /**
     *
     * @var unknown
     */
    private $file;


    /**
     *
     * @param array $options
     */
    public function __construct(array $options)
    {
        //echo $this::IMAGES_LOCATION;die;
        $this->options = $options;
    }

    
    /**
     *
     * @param array $file
     * @return \CodeBase\Uploader
     */
    public function setFile(array $file)
    {
        $this->file = $file;
        return $this;
    }


    /**
     *
     * @return void|string
     */
    public function upload($isImage = true)
    {
        if (!$isImage || !in_array($this->getCorrectExtension($this->file['type']), ['.jpg', '.png'])) {
            $upload = $this->generalUpload();
            return $upload;
        }

        if ($this->file['size'] > $this->options['maxSize']) {
            return;
        }

        if (!in_array($this->file['type'], $this->options['allowedTypes'])) {
            return;
        }

        $image = (new Imagine())->open($this->file['tmp_name']);
        $newPath = generateRandomVarcharForLocation($this::IMAGES_LOCATION, '.jpg');
        $image->save($this::IMAGES_LOCATION . $newPath);
        return '/public/images/' . $newPath;
    }


    /**
     * @param string $memeType
     * @return string
     */
    public function getCorrectExtension(string $memeType) : string
    {
        switch ($memeType) {
            case 'application/pdf':
                $ext = '.pdf';
                break;
            case 'application/msword':
                $ext = '.doc';
                break;
            case 'image/jpeg':
            case 'image/pjpeg':
            case 'iimage/jpeg':
                $ext = '.jpg';
                break;
            case 'image/png':
                $ext = '.png';
                break;
            default:
                $ext = '';
        }
        return $ext;
    }


    /**
     * @return void|string
     */
    public function generalUpload()
    {
        if ($this->file['size'] > $this->options['maxSize']) {
            return;
        }

        if (!in_array($this->file['type'], $this->options['allowedTypes'])) {
            return;
        }

        $filePath = generateRandomVarcharForLocation($this::IMAGES_LOCATION, $this->getCorrectExtension($this->file['type']));
        if (!move_uploaded_file($this->file['tmp_name'], CODEBASE . 'public/images/' . $filePath)) {
            return;
        }
        return '/public/images/' . $filePath;
    }
}
