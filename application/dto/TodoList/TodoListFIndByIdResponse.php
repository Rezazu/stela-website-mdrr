<?php

class TodoListFIndByIdResponse
{
    private $id;
    private $id_sub_tahapan;
    private $id_programmer;
    private $todo_list;
    private $deskripsi_revisi;
    private $keterangan;
    private $user_input;
    private $status_kerja;
    private $tanggal_input;
    private $user_update;
    private $tanggal_update;

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
	 * @return TodoListFIndByIdResponse
	 */
	function setId($id) {
		$this->id = $id;
	}
	
	/**
	 * @return mixed
	 */
	function getIdSubTahapan() {
		return $this->id_sub_tahapan;
	}
	
	/**
	 * @param mixed $id_subTahapan 
	 * @return TodoListFIndByIdResponse
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
	 * @param mixed $id_programmer 
	 * @return TodoListFIndByIdResponse
	 */
	function setIdProgrammer($id_programmer) {
		$this->id_programmer = $id_programmer;
	}
	
	/**
	 * @return mixed
	 */
	function getTodoList() {
		return $this->todo_list;
	}
	
	/**
	 * @param mixed $todo_list 
	 * @return TodoListFIndByIdResponse
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
	 * @return TodoListFIndByIdResponse
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
	 * @return TodoListFIndByIdResponse
	 */
	function setUserInput($user_input) {
		$this->user_input = $user_input;
	}
	
	/**
	 * @return mixed
	 */
	function getStatusKerja() {
		return $this->status_kerja;
	}
	
	/**
	 * @param mixed $status_kerja 
	 * @return TodoListFIndByIdResponse
	 */
	function setStatusKerja($status_kerja) {
		$this->status_kerja = $status_kerja;
	}
	
	/**
	 * @return mixed
	 */
	function getTanggalInput() {
		return $this->tanggal_input;
	}
	
	/**
	 * @param mixed $tanggal_input 
	 * @return TodoListFIndByIdResponse
	 */
	function setTanggalInput($tanggal_input) {
		$this->tanggal_input = $tanggal_input;
	}
	
	/**
	 * @return mixed
	 */
	function getUserUpdate() {
		return $this->user_update;
	}
	
	/**
	 * @param mixed $user_update 
	 * @return TodoListFIndByIdResponse
	 */
	function setUserUpdate($user_update) {
		$this->user_update = $user_update;
	}
	
	/**
	 * @return mixed
	 */
	function getTanggalUpdate() {
		return $this->tanggal_update;
	}
	
	/**
	 * @param mixed $tanggal_update 
	 * @return TodoListFIndByIdResponse
	 */
	function setTanggalUpdate($tanggal_update) {
		$this->tanggal_update = $tanggal_update;
	}
}