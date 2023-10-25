<?php

class TodoListUpdateSelesaiRequest
{
    private $id;
    private $status_kerja = 0;
    private $user_update;
	private $id_programmer;
    private $keterangan;

    /**
     * @return mixed
     */
    public function getKeterangan()
    {
        return $this->keterangan;
    }

    /**
     * @param mixed $keterangan
     */
    public function setKeterangan($keterangan)
    {
        $this->keterangan = $keterangan;
    }

    
	/**
	 * @return mixed
	 */
	function getId() {
		return $this->id;
	}
	
	/**
	 * @param mixed $id 
	 * @return TodoListUpdateSelesaiRequest
	 */
	function setId($id){
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
	 * @return TodoListUpdateSelesaiRequest
	 */
	function setUserUpdate($user_update){
		$this->user_update = $user_update;
	}
	/**
	 * @return mixed
	 */
	function getIdProgrammer() {
		return $this->id_programmer;
	}
	
	/**
	 * @param mixed $id_programmer 
	 * @return TodoListUpdateSelesaiRequest
	 */
	function setIdProgrammer($id_programmer){
		$this->id_programmer = $id_programmer;
	}
}