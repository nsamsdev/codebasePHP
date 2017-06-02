<?php

namespace CodeBase;

if (!defined('CODEBASE')) {
    die('Direct Access Not Allowed');
}

use Illuminate\Database\Eloquent\Model as IluminateModel;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Model.
 */
abstract class Model extends IluminateModel
{
    /**
     *
     * @var array
     */
    private $dbSettings = [];
    
    public function __construct()
    {
        parent::__construct($this->dbSettings);
    }
}
