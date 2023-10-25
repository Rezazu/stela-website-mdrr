<?php

class PeranDeleteRequest
{
    private $id;
    private $status = 9;

	/**
	 * @return mixed
	 */
	function getId() {
		return $this->id;
	}
	
	/**
	 * @param mixed $id 
	 * @return PeranDeleteRequest
	 */
	function setId($id){
		$this->id = $id;
	}
	
	/**
	 * @return mixed
	 */
	function getStatus() {
		return $this->status;
	}
}