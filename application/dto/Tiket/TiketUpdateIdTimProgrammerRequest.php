<?php

class TiketUpdateIdTimProgrammerRequest
{
    private $id;
    private $user_update;
    private $id_tim_programmer;
	/**
	 * @return mixed
	 */
	function getId() {
		return $this->id;
	}
	
	/**
	 * @param mixed $id 
	 * @return TiketUpdateIdTimProgrammerRequest
	 */
	function setId($id){
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
	 * @return TiketUpdateIdTimProgrammerRequest
	 */
	function setUserUpdate($user_update){
		$this->user_update = $user_update;
	}
	
	/**
	 * @return mixed
	 */
	function getIdTimProgrammer() {
		return $this->id_tim_programmer;
	}
	
	/**
	 * @param mixed $id_tim_programmer 
	 * @return TiketUpdateIdTimProgrammerRequest
	 */
	function setIdTimProgrammer($id_tim_programmer){
		$this->id_tim_programmer = $id_tim_programmer;
	}
}