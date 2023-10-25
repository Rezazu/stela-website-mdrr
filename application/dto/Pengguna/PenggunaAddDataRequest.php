<?php

class PenggunaAddDataRequest
{
    private $id;
    private $nama_lengkap;
    private $username;
    private $email;
    private $password;
    private $kd_departemen;
    private $bagian;
    private $gedung;
    private $unit_kerja;
    private $ruangan;
    private $lantai;
    private $telepon;
    private $hp;
    private $profile;

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
    public function getNamaLengkap()
    {
        return $this->nama_lengkap;
    }

    /**
     * @param mixed $nama_lengkap
     */
    public function setNamaLengkap($nama_lengkap)
    {
        $this->nama_lengkap = $nama_lengkap;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getKdDepartemen()
    {
        return $this->kd_departemen;
    }

    /**
     * @param mixed $kd_departemen
     */
    public function setKdDepartemen($kd_departemen)
    {
        $this->kd_departemen = $kd_departemen;
    }

    /**
     * @return mixed
     */
    public function getBagian()
    {
        return $this->bagian;
    }

    /**
     * @param mixed $bagian
     */
    public function setBagian($bagian)
    {
        $this->bagian = $bagian;
    }

    /**
     * @return mixed
     */
    public function getGedung()
    {
        return $this->gedung;
    }

    /**
     * @param mixed $gedung
     */
    public function setGedung($gedung)
    {
        $this->gedung = $gedung;
    }

    /**
     * @return mixed
     */
    public function getUnitKerja()
    {
        return $this->unit_kerja;
    }

    /**
     * @param mixed $unit_kerja
     */
    public function setUnitKerja($unit_kerja)
    {
        $this->unit_kerja = $unit_kerja;
    }

    /**
     * @return mixed
     */
    public function getRuangan()
    {
        return $this->ruangan;
    }

    /**
     * @param mixed $ruangan
     */
    public function setRuangan($ruangan)
    {
        $this->ruangan = $ruangan;
    }

    /**
     * @return mixed
     */
    public function getLantai()
    {
        return $this->lantai;
    }

    /**
     * @param mixed $lantai
     */
    public function setLantai($lantai)
    {
        $this->lantai = $lantai;
    }

    /**
     * @return mixed
     */
    public function getTelepon()
    {
        return $this->telepon;
    }

    /**
     * @param mixed $telepon
     */
    public function setTelepon($telepon)
    {
        $this->telepon = $telepon;
    }

    /**
     * @return mixed
     */
    public function getHp()
    {
        return $this->hp;
    }

    /**
     * @param mixed $hp
     */
    public function setHp($hp)
    {
        $this->hp = $hp;
    }

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
}