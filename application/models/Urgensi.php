<?php
class Urgensi extends Zend_Db_Table
{
    protected $_name;
    protected $_schema;
    protected $_db;

    function __construct()
    {
        $this->_name = "urgensi";
        $this->_schema = "db_stela";
        $this->_db = Zend_Registry::get('db_stela');
    }
}
