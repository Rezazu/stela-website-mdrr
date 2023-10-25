<?php

class SubTahapanUpdateSubTahapanRequest
{
    private $id;
    private $sub_tahapan;
    private $user_update;
	/**
	 * @return mixed
	 */
	function getId() {
		return $this->id;
	}
	
	/**
	 * @param mixed $id 
	 * @return SubTahapanUpdateSubTahapanRequest
	 */
	function setId($id){
		$this->id = $id;
	}
	
	/**
	 * @return mixed
	 */
	function getSubTahapan() {
		return $this->sub_tahapan;
	}
	
	/**
	 * @param mixed $sub_tahapan 
	 * @return SubTahapanUpdateSubTahapanRequest
	 */
	function setSubTahapan($sub_tahapan){
		$this->sub_tahapan = $sub_tahapan;
	}
	
	/**
	 * @return mixed
	 */
	function getUserUpdate() {
		return $this->user_update;
	}
	
	/**
	 * @param mixed $user_update 
	 * @return SubTahapanUpdateSubTahapanRequest
	 */
	function setUserUpdate($user_update){
		$this->user_update = $user_update;
	}
}