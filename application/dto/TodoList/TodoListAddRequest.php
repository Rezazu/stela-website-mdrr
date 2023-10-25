<?php

class TodoListAddRequest
{
    private $id;
    private $id_sub_tahapan;
    private $id_programmer = null;
    private $todo_list;
    private $deskripsi_revisi = null;
    private $user_input;
    private $user_update;
    private $keterangan;
    private $status_kerja = 1;

    /**
     * @param null $id_programmer
     */
    public function setIdProgrammer($id_programmer)
    {
        $this->id_programmer = $id_programmer;
    }


    /**
     * @return mixed
     */
    public function getKeterangan()
    {
        return $this->keterangan;
    }

    /**
     * @param mixed $keterangan
     */
    public function setKeterangan($keterangan)
    {
        $this->keterangan = $keterangan;
    }

    /**
     * @return mixed
     */
    public function getStatusKerja()
    {
        return $this->status_kerja;
    }

    /**
     * @param mixed $status_kerja
     */
    public function setStatusKerja($status_kerja)
    {
        $this->status_kerja = $status_kerja;
    }


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    
	/**
	 * @return mixed
	 */
	function getIdSubTahapan() {
		return $this->id_sub_tahapan;
	}
	
	/**
	 * @param mixed $id_sub_tahapan 
	 * @return TodoListAddRequest
	 */
	function setIdSubTahapan($id_sub_tahapan) {
		$this->id_sub_tahapan = $id_sub_tahapan;
	}
	
	/**
	 * @return mixed
	 */
	function getIdProgrammer() {
		return $this->id_programmer;
	}
	
	
	/**
	 * @return mixed
	 */
	function getTodoList() {
		return $this->todo_list;
	}
	
	/**
	 * @param mixed $todo_list 
	 * @return TodoListAddRequest
	 */
	function setTodoList($todo_list) {
		$this->todo_list = $todo_list;
	}
	
	/**
	 * @return mixed
	 */
	function getDeskripsiRevisi() {
		return $this->deskripsi_revisi;
	}
	
	/**
	 * @param mixed $deskripsi_revisi
	 * @return TodoListAddRequest
	 */
	function setDeskripsiRevisi($deskripsi_revisi) {
		$this->deskripsi_revisi = $deskripsi_revisi;
	}
	
	/**
	 * @return mixed
	 */
	function getUserInput() {
		return $this->user_input;
	}
	
	/**
	 * @param mixed $user_input 
	 * @return TodoListAddRequest
	 */
	function setUserInput($user_input) {
		$this->user_input = $user_input;
	}
	
	/**
	 * @return mixed
	 */
	function getUserUpdate() {
		return $this->user_update;
	}
	
	/**
	 * @param mixed $user_update 
	 * @return TodoListAddRequest
	 */
	function setUserUpdate($user_update) {
		$this->user_update = $user_update;
	}
}