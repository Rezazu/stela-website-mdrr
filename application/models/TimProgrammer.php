<?php

class TimProgrammer extends Zend_Db_Table
{
    protected $_name;
    protected $_schema;
    protected $_db;

    public function init()
    {
        $this->_name = 'tim_programmer';
        $this->_schema = 'db_stela';
        $this->_db = Zend_Registry::get('db_stela');
    }
}