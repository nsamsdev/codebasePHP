<?php

namespace CodeBase\Models;

use CodeBase\BasicModel;
use CodeBase\Libraries\Messages;

/**
 * Class Users
 * @package CodeBase\Models
 */
class Users extends BasicModel
{
    /**
     * @var string
     */
    private $table = 'users';

    /**
     * Users constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

}
