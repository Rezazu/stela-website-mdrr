<?php

class AplikasiUpdateAplikasiRequest
{
    private $id;
    private $pengelola;
    private $developer;
    private $jenis_aplikasi;
    private $id_status_aplikasi;
    private $aplikasi;
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
    public function getPengelola()
    {
        return $this->pengelola;
    }

    /**
     * @param mixed $pengelola
     */
    public function setPengelola($pengelola)
    {
        $this->pengelola = $pengelola;
    }

    /**
     * @return mixed
     */
    public function getDeveloper()
    {
        return $this->developer;
    }

    /**
     * @param mixed $developer
     */
    public function setDeveloper($developer)
    {
        $this->developer = $developer;
    }

    /**
     * @return mixed
     */
    public function getJenisAplikasi()
    {
        return $this->jenis_aplikasi;
    }

    /**
     * @param mixed $jenis_aplikasi
     */
    public function setJenisAplikasi($jenis_aplikasi)
    {
        $this->jenis_aplikasi = $jenis_aplikasi;
    }

    /**
     * @return mixed
     */
    public function getIdStatusAplikasi()
    {
        return $this->id_status_aplikasi;
    }

    /**
     * @param mixed $id_status_aplikasi
     */
    public function setIdStatusAplikasi($id_status_aplikasi)
    {
        $this->id_status_aplikasi = $id_status_aplikasi;
    }

    /**
     * @return mixed
     */
    public function getAplikasi()
    {
        return $this->aplikasi;
    }

    /**
     * @param mixed $aplikasi
     */
    public function setAplikasi($aplikasi)
    {
        $this->aplikasi = $aplikasi;
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