<?php

class TiketImageLaporan extends Zend_Db_Table
{
    protected $_name;
    protected $_schema;
    protected $_db;

    public function init()
    {
        $this->_name = 'tiket_image_laporan';
        $this->_schema = 'db_stela';
        $this->_db = Zend_Registry::get('db_stela');
    }
}