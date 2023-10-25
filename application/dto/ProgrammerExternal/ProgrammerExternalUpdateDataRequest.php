<?php

class ProgrammerExternalUpdateDataRequest
{
    private $id;
    private $nama_instansi;
    private $status;
    private $tanggal_update;
    private $user_update;

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


}