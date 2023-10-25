<?php

class ListPeranResponse
{
    private $id;
    private $nama_peran;

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
    public function getNamaPeran()
    {
        return $this->nama_peran;
    }

    /**
     * @param mixed $nama_peran
     */
    public function setNamaPeran($nama_peran)
    {
        $this->nama_peran = $nama_peran;
    }

}