<?php

class PeranFIndByIdResponse
{
    private $id_pengguna;
    private $id_peran;
    private $status;
	/**
	 * @return mixed
	 */
    
	function getIdPengguna() {
		return $this->id_pengguna;
	}
	
	/**
	 * @param mixed $id_pengguna 
	 * @return PeranFIndByIdResponse
	 */
	function setIdPengguna($id_pengguna){
		$this->id_pengguna = $id_pengguna;
	}
	
	/**
	 * @return mixed
	 */
	function getIdPeran() {
		return $this->id_peran;
	}
	
	/**
	 * @param mixed $id_peran 
	 * @return PeranFIndByIdResponse
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
	 * @return PeranFIndByIdResponse
	 */
	function setStatus($status){
		$this->status = $status;
	}
}