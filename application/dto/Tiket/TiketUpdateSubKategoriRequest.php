<?php

class TiketUpdateSubKategoriRequest
{
    private $id;
    private $id_sub_kategori;
    private $id_urgensi;
    private $user_update;

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