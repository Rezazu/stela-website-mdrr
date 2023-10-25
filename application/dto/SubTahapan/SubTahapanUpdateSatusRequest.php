<?php

class SubTahapanUpdateSatusRequest
{
    private $id;
    private $user_update;
    private $status = 0;

    
	/**
	 * @return mixed
	 */
	function getId() {
		return $this->id;
	}
	
	/**
	 * @param mixed $id 
	 * @return SubTahapanUpdateRequest
	 */
	function setId($id) {
		$this->id = $id;
	}
	
	/**
	 * @return mixed
	 */
	function getUserUpdate() {
		return $this->user_update;
	}
	
	/**
	 * @param mixed $user_update 
	 * @return SubTahapanUpdateRequest
	 */
	function setUserUpdate($user_update) {
		$this->user_update = $user_update;
	}
	
	/**
	 * @return mixed
	 */
	function getStatus() {
		return $this->status;
	}
}