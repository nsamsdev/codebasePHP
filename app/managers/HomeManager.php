<?php

namespace CodeBase\Managers;

use CodeBase\Manager;

if (!defined('CODEBASE')) {
    die('Direct Access Not Allowed');
}


/**
 * Class GeneralManager
 * @package CodeBase\Managers
 */
class HomeManager extends Manager
{
   

    /**
     * @var Extras
     */
    private $extras;

    /**
     * GeneralManager constructor.
     */
    public function __construct()
    {
    }
}
