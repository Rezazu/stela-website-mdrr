<?php
class TodoListDokumenFindByIdResponse
{
    private $id;
    private $id_todo_list;
    private $id_jenis_dokumen;
    private $original_name;
    private $dokumen_name;
    private $status;

    /**
     * @return mixed
     */
    public function getDokumenName()
    {
        return $this->dokumen_name;
    }

    /**
     * @param mixed $dokumen_name
     */
    public function setDokumenName($dokumen_name)
    {
        $this->dokumen_name = $dokumen_name;
    }


    /**
     * @return mixed
     */
    public function getIdJenisDokumen()
    {
        return $this->id_jenis_dokumen;
    }

    /**
     * @param mixed $id_jenis_dokumen
     */
    public function setIdJenisDokumen($id_jenis_dokumen)
    {
        $this->id_jenis_dokumen = $id_jenis_dokumen;
    }


	/**
	 * @return mixed
	 */
	function getId() {
		return $this->id;

	}
	
	/**
	 * @param mixed $id 
	 * @return TodoListDokumenFindByIdResponse
	 */
	function setId($id){
		$this->id = $id;
	}
	
	/**
	 * @return mixed
	 */
	function getIdTodoList() {
		return $this->id_todo_list;
	}
	
	/**
	 * @param mixed $id_todo_list 
	 * @return TodoListDokumenFindByIdResponse
	 */
	function setIdTodoList($id_todo_list){
		$this->id_todo_list = $id_todo_list;
	}

    /**
     * @return mixed
     */
    public function getOriginalName()
    {
        return $this->original_name;
    }

    /**
     * @param mixed $original_name
     */
    public function setOriginalName($original_name)
    {
        $this->original_name = $original_name;
    }

	/**
	 * @return mixed
	 */
	function getStatus() {
		return $this->status;
	}
	
	/**
	 * @param mixed $status 
	 * @return TodoListDokumenFindByIdResponse
	 */
	function setStatus($status){
		$this->status = $status;
	}
}