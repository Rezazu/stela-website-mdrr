<?php

class Aplikasi extends Zend_Db_Table
{
    protected $_name;
    protected $_schema;
    protected $_db;

    public function init()
    {
        $this->_name = 'aplikasi';
        $this->_schema = 'deb_stela';
        $this->_db = Zend_Registry::get('db_stela');
    }

}