<?php

class PenggunaFindByIdResponse
{
	 private $id;
	 private $nama_lengkap;
	 private $username;
	 private $email;
	 private $status;
	 private $kd_departemen;
     private $profile;

    /**
     * @return mixed
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * @param mixed $profile
     */
    public function setProfile($profile)
    {
        $this->profile = $profile;
    }


	/**
	 * @return mixed
	 */
	function getId()
	{
		return $this->id;
	}

	/**
	 * @param mixed $id 
	 * @return PenggunaFindByIdResponse
	 */
	function setId($id)
	{
		$this->id = $id;
	}

	/**
	 * @return mixed
	 */
	function getNamaLengkap()
	{
		return $this->nama_lengkap;
	}

	/**
	 * @param mixed $nama_lengkap 
	 * @return PenggunaFindByIdResponse
	 */
	function setNamaLengkap($nama_lengkap)
	{
		$this->nama_lengkap = $nama_lengkap;
	}

	/**
	 * @return mixed
	 */
	function getUsername()
	{
		return $this->username;
	}

	/**
	 * @param mixed $username 
	 * @return PenggunaFindByIdResponse
	 */
	function setUsername($username)
	{
		$this->username = $username;
	}

	/**
	 * @return mixed
	 */
	function getEmail()
	{
		return $this->email;
	}

	/**
	 * @param mixed $email 
	 * @return PenggunaFindByIdResponse
	 */
	function setEmail($email)
	{
		$this->email = $email;
	}

	/**
	 * @return mixed
	 */
	function getStatus()
	{
		return $this->status;
	}

	/**
	 * @param mixed $status 
	 * @return PenggunaFindByIdResponse
	 */
	function setStatus($status)
	{
		$this->status = $status;
	}

	/**
	 * @return mixed
	 */
	function getKdDepartemen()
	{
		return $this->kd_departemen;
	}

	/**
	 * @param mixed $kd_departemen 
	 * @return PenggunaFindByIdResponse
	 */
	function setKdDepartemen($kd_departemen)
	{
		$this->kd_departemen = $kd_departemen;
	}

	function getBagian()
	{
		return $this->bagian;
	}

	function setBagian($bagian)
	{
		$this->bagian = $bagian;
	}

	function getGedung()
	{
		return $this->gedung;
	}

	function setGedung($gedung)
	{
		$this->gedung = $gedung;
	}

	function getUnitKerja()
	{
		return $this->unit_kerja;
	}

	function setUnitKerja($unit_kerja)
	{
		$this->unit_kerja = $unit_kerja;
	}

	function getRuangan()
	{
		return $this->ruangan;
	}

	function setRuangan($ruangan)
	{
		$this->ruangan = $ruangan;
	}

	function getLantai()
	{
		return $this->lantai;
	}

	function setLantai($lantai)
	{
		$this->lantai = $lantai;
	}

	function getTelepon()
	{
		return $this->telepon;
	}

	function setTelepon($telepon)
	{
		$this->telepon = $telepon;
	}

	function getHp()
	{
		return $this->hp;
	}

	function setHp($hp)
	{
		$this->hp = $hp;
	}
}
