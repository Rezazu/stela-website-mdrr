<?php

class TodoListUpdateRequest
{
    private $id;
	private $id_programmer;
    private $todo_list;
    private $user_update;
    private $status_kerja;
    private $deskripsi_revisi;
    private $keterangan;

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
	function getId() {
		return $this->id;
	}
	
	/**
	 * @param mixed $id 
	 * @return TodoListUpdateRequest
	 */
	function setId($id) {
		$this->id = $id;
	}
	
	/**
	 * @return mixed
	 */
	function getTodoList() {
		return $this->todo_list;
	}
	
	/**
	 * @param mixed $todo_list 
	 * @return TodoListUpdateRequest
	 */
	function setTodoList($todo_list) {
		$this->todo_list = $todo_list;
	}
	
	/**
	 * @return mixed
	 */
	function getUserUpdate() {
		return $this->user_update;
	}
	
	/**
	 * @param mixed $user_update 
	 * @return TodoListUpdateRequest
	 */
	function setUserUpdate($user_update) {
		$this->user_update = $user_update;
	}
	
	/**
	 * @return mixed
	 */
	function getStatusKerja() {
		return $this->status_kerja;
	}
	
	/**
	 * @param mixed $status_kerja 
	 * @return TodoListUpdateRequest
	 */
	function setStatusKerja($status_kerja) {
		$this->status_kerja = $status_kerja;
	}
	
	/**
	 * @return mixed
	 */
	function getDeskripsiRevisi() {
		return $this->deskripsi_revisi;
	}
	
	/**
	 * @param mixed $deskripsi_revisi
	 * @return TodoListUpdateRequest
	 */
	function setDeskripsiRevisi($deskripsi_revisi) {
		$this->deskripsi_revisi = $deskripsi_revisi;
	}
	/**
	 * @return mixed
	 */
	function getIdProgrammer() {
		return $this->id_programmer;
	}
	
	/**
	 * @param mixed $id_programmer 
	 * @return TodoListUpdateRequest
	 */
	function setIdProgrammer($id_programmer) {
		$this->id_programmer = $id_programmer;
	}
}