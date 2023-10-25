<?php

class PeranUpdatePeranRequest
{
    private $id;
    private $id_peran;

    
	/**
	 * @return mixed
	 */
	function getId() {
		return $this->id;
	}
	
	/**
	 * @param mixed $id 
	 * @return PeranUpdatePeranRequest 
	 */
	function setId($id){
		$this->id = $id;
	}
	
	/**
	 * @return mixed
	 */
	function getIdPeran() {
		return $this->id_peran;
	}
	
	/**
	 * @param mixed $id_peran 
	 * @return PeranUpdatePeranRequest
	 */
	function setIdPeran($id_peran){
		$this->id_peran = $id_peran;
	}
}