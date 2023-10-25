<?php

class ProgrammerExternal extends Zend_Db_Table
{
    protected $_name;
    protected $_schema;
    protected $_db;

    public function init()
    {
        $this->_name = 'programmer_external';
        $this->_schema = 'db_stela';
        $this->_db = Zend_Registry::get('db_stela');
    }
}