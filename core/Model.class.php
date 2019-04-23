<?php
/**
 * Created by PhpStorm.
 * User: lamb
 * Date: 2019/4/23
 * Time: 11:24 AM
 */

class Model extends Sql
{
    protected $_model;
    protected $_table;
    function __construct()
    {
        //连接数据库
        $this->connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
        $this->_model = get_class($this);
        $this->_model = rtrim($this->_model, 'Model');
        $this->_table = strtolower($this->_model);
    }
    function __destruct()
    {
        // TODO: Implement __destruct() method.
    }
}