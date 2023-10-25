<?php

class PeranUpdateDataRequest
{
    private $id_peran;
    private $status;
    private $id;

	/**
	 * @return mixed
	 */
	function getIdPeran() {
		return $this->id_peran;
	}
	
	/**
	 * @param mixed $id_peran 
	 * @return PeranUpdateDataRequest
	 */
	function setIdPeran($id_peran){
		$this->id_peran = $id_peran;
	}
	
	/**
	 * @return mixed
	 */
	function getStatus() {
		return $this->status;
	}
	
	/**
	 * @param mixed $status 
	 * @return PeranUpdateDataRequest
	 */
	function setStatus($status){
		$this->status = $status;
	}
	
	/**
	 * @return mixed
	 */
	function getId() {
		return $this->id;
	}
	
	/**
	 * @param mixed $id 
	 * @return PeranUpdateDataRequest
	 */
	function setId($id){
		$this->id = $id;
	}
}