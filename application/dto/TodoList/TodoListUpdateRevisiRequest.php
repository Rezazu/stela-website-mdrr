<?php

class TodoListUpdateRevisiRequest
{
    private $id;
    private $status_kerja = 2;
    private $deskripsi_revisi;
    private $user_update;
    
	/**
	 * @return mixed
	 */
	function getId() {
		return $this->id;
	}
	
	/**
	 * @param mixed $id 
	 * @return TodoListUpdateRevisiRequest
	 */
	function setId($id) {
		$this->id = $id;
	}
	
	/**
	 * @return mixed
	 */
	function getStatusKerja() {
		return $this->status_kerja;
	}

	/**
	 * @return mixed
	 */
	function getDeskripsiRevisi() {
		return $this->deskripsi_revisi;
	}
	
	/**
	 * @param mixed $deskripsi_revisi
	 * @return TodoListUpdateRevisiRequest
	 */
	function setDeskripsiRevisi($deskripsi_revisi) {
		$this->deskripsi_revisi = $deskripsi_revisi;
	}
	
	/**
	 * @return mixed
	 */
	function getUserUpdate() {
		return $this->user_update;
	}
	
	/**
	 * @param mixed $user_update 
	 * @return TodoListUpdateRevisiRequest
	 */
	function setUserUpdate($user_update) {
		$this->user_update = $user_update;
	}
}