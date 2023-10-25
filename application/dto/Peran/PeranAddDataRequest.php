<?php

class PeranAddDataRequest
{
    private $id;
    private $id_pengguna;
    private $id_peran;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }


	/**
	 * @return mixed
	 */
	function getIdPengguna() {
		return $this->id_pengguna;
	}

	/**
	 * @param mixed $id_pengguna
	 * @return PEranAddDataRequest
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
	 * @return PEranAddDataRequest
	 */
	function setIdPeran($id_peran){
		$this->id_peran = $id_peran;
	}
}