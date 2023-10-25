<?php
class Kategori extends Zend_Db_Table
{
    protected $_name;
    protected $_schema;
    protected $_db;
    public function init()
    {
        $this->_name = 'kategori';
        $this->_schema  = 'db_stela';
        $this->_db  = Zend_Registry::get('db_stela');
    }
}
