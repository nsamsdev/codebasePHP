<?php

namespace CodeBase;

if (!defined('CODEBASE')) {
    die('Direct Access Not Allowed');
}

use mikehaertl\wkhtmlto\Pdf;

/**
 *
 * @author nihad
 *
 */
class PdfCreator
{
    public $instance;
    private $lastPath;

    /**
     * class __construct
     */
    public function __construct()
    {
        if (!$this->instance) {
            $this->instance = new Pdf([
//                'binary' => '/usr/bin/xvfb-run -- /usr/bin/wkhtmltopdf'
            ]);
        }
    }

    /**
     * @param string
     * @return void
     */
    public function addHtmlText(string $htmlText)
    {
        $this->instance->addPage($htmlText);
    }

    /**
     * @param string $path
     * @return void
     */
    public function saveToPath(string $path)
    {
        if (!$this->instance->saveAs($path)) {
            throw new \Exception($this->instance->getError());
        }
        $this->lastPath = $path;
    }

    /**
     * @return void
     */
    public function display()
    {
        $this->instance->send();
    }

    /**
     * @return void
     */
    public function download()
    {
        $this->instance->send((isset($this->lastPath)) ? $this->lastPath : 'report.pdf');
    }

    /**
     * @return void
     */
    public function getAsString()
    {
        return $this->instance->toString();
    }
}
