<?php

class TodoListDokumenRequest{

	private $id = 0;
    private $id_todo_list;
    private $id_jenis_dokumen;
    private $original_name;
    private $dokumen_name;
	private $dokumen_type;
	private $dokumen_size;
	private $user_input;
    private $status = 1;

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
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
	function getIdTodoList() {
		return $this->id_todo_list;
	}

	/**
	 * @param mixed $id_todo_list 
	 * @return TodoListDokumenRequest
	 */
	function setIdTodoList($id_todo_list){
		$this->id_todo_list = $id_todo_list;
	}

	/**
	 * @return mixed
	 */
	function getUserInput() {
		return $this->user_input;
	}
	
	/**
	 * @param mixed $user_update 
	 * @return TodoListDokumenRequest
	 */
	function setUserInput($user_update){
		$this->user_input = $user_update;
	}
	/**
	 * @return mixed
	 */
	function getId() {
		return $this->id;
	}
	
	/**
	 * @param mixed $id 
	 * @return TodoListDokumenRequest
	 */
	function setId($id){
		$this->id = $id;
	}
	/**
	 * @return mixed
	 */
	function getDokumenName() {
		return $this->dokumen_name;
	}
	
	/**
	 * @param mixed $dokumen_name 
	 * @return TodoListDokumenRequest
	 */
	function setDokumenName($dokumen_name){
		$this->dokumen_name = $dokumen_name;
	}
	
	/**
	 * @return mixed
	 */
	function getDokumenType() {
		return $this->dokumen_type;
	}
	
	/**
	 * @param mixed $dokumen_type 
	 * @return TodoListDokumenRequest
	 */
	function setDokumenType($dokumen_type){
		$this->dokumen_type = $dokumen_type;
	}
	
	/**
	 * @return mixed
	 */
	function getDokumenSize() {
		return $this->dokumen_size;
	}
	
	/**
	 * @param mixed $dokumen_size 
	 * @return TodoListDokumenRequest
	 */
	function setDokumenSize($dokumen_size){
		$this->dokumen_size = $dokumen_size;
	}
}