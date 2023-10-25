<?php

class SubTahapanFindIdResponse{
    private $id;
    private $id_tahapan;
    private $id_tiket;
    private $sub_tahapan;
    private $user_input;
    private $status;
    private $tanggal_input;
    private $user_update;
    private $tanggal_update;

	/**
	 * @return mixed
	 */
	function getId() {
		return $this->id;
	}
	
	/**
	 * @param mixed $id 
	 * @return SubTahapanFindIdRequest
	 */
	function setId($id) {
		$this->id = $id;
	}
	
	/**
	 * @return mixed
	 */
	function getIdTahapan() {
		return $this->id_tahapan;
	}
	
	/**
	 * @param mixed $id_tahapan 
	 * @return SubTahapanFindIdRequest
	 */
	function setIdTahapan($id_tahapan) {
		$this->id_tahapan = $id_tahapan;
	}
	
	/**
	 * @return mixed
	 */
	function getIdTiket() {
		return $this->id_tiket;
	}
	
	/**
	 * @param mixed $id_tiket 
	 * @return SubTahapanFindIdRequest
	 */
	function setIdTiket($id_tiket) {
		$this->id_tiket = $id_tiket;
	}
	
	/**
	 * @return mixed
	 */
	function getSubTahapan() {
		return $this->sub_tahapan;
	}
	
	/**
	 * @param mixed $sub_tahapan 
	 * @return SubTahapanFindIdRequest
	 */
	function setSubTahapan($sub_tahapan) {
		$this->sub_tahapan = $sub_tahapan;
	}
	
	/**
	 * @return mixed
	 */
	function getUserInput() {
		return $this->user_input;
	}
	
	/**
	 * @param mixed $user_input 
	 * @return SubTahapanFindIdRequest
	 */
	function setUserInput($user_input) {
		$this->user_input = $user_input;
	}
	
	/**
	 * @return mixed
	 */
	function getStatus() {
		return $this->status;
	}
	
	/**
	 * @param mixed $status 
	 * @return SubTahapanFindIdRequest
	 */
	function setStatus($status) {
		$this->status = $status;
	}
	
	/**
	 * @return mixed
	 */
	function getTanggalInput() {
		return $this->tanggal_input;
	}
	
	/**
	 * @param mixed $tanggal_input 
	 * @return SubTahapanFindIdRequest
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
	 * @return SubTahapanFindIdRequest
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
	 * @return SubTahapanFindIdRequest
	 */
	function setTanggalUpdate($tanggal_update) {
		$this->tanggal_update = $tanggal_update;
	}
}