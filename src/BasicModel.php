<?php

namespace CodeBase;

if (!defined('CODEBASE')) {
    die('Direct Access Not Allowed');
}

class BasicModel extends \PDO
{
    
    /**
     *
     */
    public function __construct()
    {
        $dsn = CODEBASE_DB_DRIVER . ':host=' . CODEBASE_DB_HOST . ';dbname=' . CODEBASE_DB_NAME;
        parent::__construct($dsn, CODEBASE_DB_USER, CODEBASE_DB_PASS);
    }

    
    /**
     *
     * @param string $sql
     * @throws \Exception
     * @return PDOStatement
     */
    public function _query(string $sql)
    {
        try {
            $query = $this->query($sql);
        } catch (\PDOException $e) {
            throw new \Exception($e->getMessage());
        }
        return $query;
    }
}
