<?php
class Dpr_UrgensiService
{
    function __construct()
    {
        $this->db = Zend_Registry::get('db_stela');
        $this->urgensi = new Urgensi();
    }

    function getAllData()
    {
        $select  = $this->urgensi->select()->where('status=1')->order('id');
        return $this->urgensi->fetchAll($select);
    }

    function getDataById($id)
    {
        $select  = $this->urgensi->select()->where('id=?', $id);
        return $this->urgensi->fetchRow($select);
    }

    function findById($id)
    {
        $sql = "SELECT * FROM urgensi WHERE id = ?";
        $statement = $this->db->prepare($sql);
        $statement->execute([$id]);
        $statement->setFetchMode(Zend_Db::FETCH_OBJ);

        if ($result = $statement->fetch())
        {
            return $result;
        }else{
            return  null;
        }

    }

    public function getListUrgensi()
    {
        $urgensi = $this->getAllData();

        foreach ($urgensi as $u) {
            $listUrgensi[$u['id']] = [
                'id' => $u['id'],
                'urgensi' => $u['nama']
            ];
        }

        return $listUrgensi;
    }
}
