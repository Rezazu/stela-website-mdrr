<?php

class TiketUpdateRevisiServiceDeskRequest
{
    private $id;
    private $id_via;
    private $id_sub_kategori;
    private $id_urgensi;
    private $id_status_tiket;
    private $id_status_tiket_internal;
    private $user_update;

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
    public function getIdVia()
    {
        return $this->id_via;
    }

    /**
     * @param mixed $id_via
     */
    public function setIdVia($id_via)
    {
        $this->id_via = $id_via;
    }

    /**
     * @return mixed
     */
    public function getIdSubKategori()
    {
        return $this->id_sub_kategori;
    }

    /**
     * @param mixed $id_sub_kategori
     */
    public function setIdSubKategori($id_sub_kategori)
    {
        $this->id_sub_kategori = $id_sub_kategori;
    }

    /**
     * @return mixed
     */
    public function getIdUrgensi()
    {
        return $this->id_urgensi;
    }

    /**
     * @param mixed $id_urgensi
     */
    public function setIdUrgensi($id_urgensi)
    {
        $this->id_urgensi = $id_urgensi;
    }

    /**
     * @return mixed
     */
    public function getIdStatusTiket()
    {
        return $this->id_status_tiket;
    }

    /**
     * @param mixed $id_status_tiket
     */
    public function setIdStatusTiket($id_status_tiket)
    {
        $this->id_status_tiket = $id_status_tiket;
    }

    /**
     * @return mixed
     */
    public function getIdStatusTiketInternal()
    {
        return $this->id_status_tiket_internal;
    }

    /**
     * @param mixed $id_status_tiket_internal
     */
    public function setIdStatusTiketInternal($id_status_tiket_internal)
    {
        $this->id_status_tiket_internal = $id_status_tiket_internal;
    }
}