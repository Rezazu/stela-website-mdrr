<?php


require_once APPLICATION_PATH . '/dto/Peran/ListPeranResponse.php';

class Dpr_ListPeranService
{
    protected $db;
    protected $listPeran;

    public function __construct()
    {
        $this->db = Zend_Registry::get('db_stela');
        $this->listPeran = new ListPeran();
    }

    public function getAllData()
    {
        $sql = "SELECT * FROM list_peran";
        $statement = $this->db->query($sql);
        $statement->setFetchMode(Zend_Db::FETCH_OBJ);

        try{
            if($result = $statement->fetchAll())
            {
                return $result;
            }else
            {
                return null;
            }
        }finally{
            $statement->closecursor();
        }
    }

    public function findById($id)
    {
        $sql = "SELECT nama_peran FROM list_peran WHERE id = ?";
        $statement = $this->db->prepare($sql);
        $statement->execute([$id]);
        $statement->setFetchMode(Zend_Db::FETCH_OBJ);

        try{
            if($result = $statement->fetch())
            {
                $response = new ListPeranResponse();
                $response->setNamaPeran($result->nama_peran);

                return $response;
            }else
            {
                throw new Exception("Peran Ini Tidak Ada");
            }
        }catch(Exception $exception){
            throw $exception;
        }finally{
            $statement->closecursor();
        }
    }
}