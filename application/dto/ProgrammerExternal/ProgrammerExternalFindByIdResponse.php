<?php

class ProgrammerExternalFindByIdResponse
{
    private $id;
    private $id_pengguna;
    private $nama_instansi;
    private $status;
    private $user_input;
    private $tanggal_input;
    private $user_update;
    private $tanggal_update;

    /**
     * @return mixed
     */
    public function getIdPengguna()
    {
        return $this->id_pengguna;
    }

    /**
     * @param mixed $id_pengguna
     */
    public function setIdPengguna($id_pengguna)
    {
        $this->id_pengguna = $id_pengguna;
    }


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
    public function getNamaInstansi()
    {
        return $this->nama_instansi;
    }

    /**
     * @param mixed $nama_instansi
     */
    public function setNamaInstansi($nama_instansi)
    {
        $this->nama_instansi = $nama_instansi;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getUserInput()
    {
        return $this->user_input;
    }

    /**
     * @param mixed $user_input
     */
    public function setUserInput($user_input)
    {
        $this->user_input = $user_input;
    }

    /**
     * @return mixed
     */
    public function getTanggalInput()
    {
        return $this->tanggal_input;
    }

    /**
     * @param mixed $tanggal_input
     */
    public function setTanggalInput($tanggal_input)
    {
        $this->tanggal_input = $tanggal_input;
    }

    /**
     * @return mixed
     */
    public function getUserUpdate()
    {
        return $this->user_update;
    }

    /**
     * @param mixed $user_update
     */
    public function setUserUpdate($user_update)
    {
        $this->user_update = $user_update;
    }

    /**
     * @return mixed
     */
    public function getTanggalUpdate()
    {
        return $this->tanggal_update;
    }

    /**
     * @param mixed $tanggal_update
     */
    public function setTanggalUpdate($tanggal_update)
    {
        $this->tanggal_update = $tanggal_update;
    }


}