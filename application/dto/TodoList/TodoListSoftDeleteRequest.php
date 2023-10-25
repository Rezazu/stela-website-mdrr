<?php

class TodoListSoftDeleteRequest
{
    private $id;
    private $status_kerja = 9;
    private $user_update;
    
	/**
	 * @return mixed
	 */
	function getId() {
		return $this->id;
	}
	
	/**
	 * @param mixed $id 
	 * @return TodoListSoftDeleteRequest
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
	function getUserUpdate() {
		return $this->user_update;
	}
	
	/**
	 * @param mixed $user_update 
	 * @return TodoListSoftDeleteRequest
	 */
	function setUserUpdate($user_update) {
		$this->user_update = $user_update;
	}
}