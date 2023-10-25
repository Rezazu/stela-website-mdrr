<?php

class StatusAplikasiUpdateDataRequest
{
    private $id;
    private $status_aplikasi;
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
    public function getStatusAplikasi()
    {
        return $this->status_aplikasi;
    }

    /**
     * @param mixed $status_aplikasi
     */
    public function setStatusAplikasi($status_aplikasi)
    {
        $this->status_aplikasi = $status_aplikasi;
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