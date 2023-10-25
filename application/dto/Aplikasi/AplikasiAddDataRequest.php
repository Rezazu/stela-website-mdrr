<?php

class AplikasiAddDataRequest
{
    private $aplikasi;
    private $id_status_aplikasi;
    private $jenis_aplikasi;
    private $developer;
    private $pengelola;
    private $user_input;
    private $user_update;

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