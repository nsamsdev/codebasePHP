<?php

namespace CodeBase;

if (!defined('CODEBASE')) {
    die('Direct Access Not Allowed');
}

use CodeBase\Traits\Helper as Help;
use CodeBase\ErrorHandler as ErHandler;
use CodeBase\Emailer;
use CodeBase\LoginBoaster;
use CodeBase\PdfCreator;
use CodeBase\Router;
use CodeBase\SessionManager as S;
use CodeBase\HttpRequester as Request;

/**
 * Class Controller.
 */
class Manager
{
    
    /**
     *
     */
    public function logout()
    {
        S::removerItem('userSessionId');
        S::removerItem('adminSessionId');
        redirectToPage(Router::getUrl('home'));
    }
}
