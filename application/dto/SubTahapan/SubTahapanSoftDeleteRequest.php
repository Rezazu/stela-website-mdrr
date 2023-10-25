<?php

class SubTahapanSoftDeleteRequest
{
    private $id;
    private $user_update;
	private $status = 9;

	public function getStatus()
	{
		return $this->status;
	}

    
	/**
	 * @return mixed
	 */
	function getId() {
		return $this->id;
	}
	
	/**
	 * @param mixed $id 
	 * @return SubTahapanSoftDeleteRequest
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
	 * @return SubTahapanSoftDeleteRequest
	 */
	function setUserUpdate($user_update) {
		$this->user_update = $user_update;
	}
}