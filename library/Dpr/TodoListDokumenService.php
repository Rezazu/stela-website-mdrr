<?php

require_once APPLICATION_PATH . '/dto/TodoListDokumen/TodoListDokumenRequest.php';
require_once APPLICATION_PATH . '/dto/TodoListDokumen/TodoListDokumenFIndByIdResponse.php';

class Dpr_TodoListDokumenService{

    protected $db;
    protected $todoListDokumen;
    protected $tanggal_log;

    function __construct()
	{
		$this->todoListDokumen = new TodoListDokumen();
		$this->db = Zend_Registry::get('db_stela');
        $this->tanggal_log = date('Y-m-d H:i:s');
	}

    public function findById($id)
    {
        $sql = "SELECT id, id_todo_list, id_jenis_dokumen, original_name, status FROM todo_list_dokumen WHERE id = ?";
        $statement = $this->db->prepare($sql);
        $statement->execute([$id]);
        $statement->setFetchMode(Zend_Db::FETCH_OBJ);

        if($result = $statement->fetch())
        {
            $response = new TodoListDokumenFindByIdResponse();
            $response->setId($id);
            $response->setDokumenName($result->dokumen_name);
            $response->setOriginalName($result->original_name);
            $response->setIdJenisDokumen($result->id_jenis_dokumen);
            $response->setIdTodoList($result->id_todo_list);
            $response->setStatus($result->status);

            return $response;
        }else{
            return null;
        }
    }
    
    public function addData(TodoListDokumenRequest $request, $tanggal_log = null)
    {
        $result = $this->findById($request->getId());

        if($result != null){
            $this->updateRevisi($result->getId());
        }
        
        $sql = "INSERT INTO todo_list_dokumen(id_todo_list, id_jenis_dokumen, original_name, dokumen_name, dokumen_type,
                dokumen_size, user_input, tanggal_input, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $statement = $this->db->prepare($sql);
        $statement->execute([
            $request->getIdTodoList(),
            $request->getIdJenisDokumen(),
            $request->getOriginalName(),
            $request->getDokumenName(),
            $request->getDokumenType(),
            $request->getDokumenSize(),
            $request->getUserInput(),
            ($tanggal_log) ? $tanggal_log : $this->tanggal_log,
            $request->getStatus()
        ]);
        $request->setId($this->todoListDokumen->getAdapter()->lastInsertId());
        $statement->closecursor();

        return $request;
    }

    

    // mengambil semua data untuk ditampilkan ke programmer
    public function getAllData(){
        $sql = "SELECT * FROM todo_list_dokumen";

        $staement = $this->db->query($sql);
        $staement->setFetchMode(Zend_Db::FETCH_ASSOC);
        
        if($result = $staement->fetchAll()){
            return $result;
        }else{
            return null;
        }
    }

    public function getAllDataForProgrammer($id_todo_list){
        $sql = "SELECT * FROM todo_list_dokumen WHERE id_todo_list = ?";
        $statement = $this->db->prepare($sql);
        $statement->execute([$id_todo_list]);
        $statement->setFetchMode(Zend_Db::FETCH_OBJ);

        if($result = $statement->fetchAll())
        {
            return $result;
        }else{
            return null;
        }

    }

    public function getAllDataForUser($id_todo_list){
        $sql = "SELECT * FROM todo_list_dokumen WHERE id_todo_list = ? AND status = 1";
        $statement = $this->db->prepare($sql);
        $statement->execute([$id_todo_list]);
        $statement->setFetchMode(Zend_Db::FETCH_OBJ);

        if($result = $statement->fetchAll())
        {
            return $result;
        }else{
            return null;
        }
    }

    public function delete($id)
    {
        $sql = "DELETE FROM todo_list_dokumen WHERE id = ?";
        $statement  = $this->db->prepare($sql);
        $statement->execute([$id]);

        try{
            $result = $this->findById($id);

            if($result == null)
            {
                return "Berhasil Dihapus";
            }else{
                return "Masih Belum Terhapus";
            }

        }finally{
            $statement->closecursor();
        }
    }

    public function updateRevisi($id)
    {
        $sql = "UPDATE todo_list_dokumen SET status = 0 WHERE id = ?";
        $statement = $this->db->prepare($sql);
        $statement->execute([$id]);
    }

}