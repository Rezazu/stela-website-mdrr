<?php

class TimProgrammerAddRequest
{
    private $id;
    private $nama_tim;

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
    
    public function setNamaTim($nama_tim)
    {
        $this->nama_tim = $nama_tim;
    }

    public function getNamaTim()
    {
        return $this->nama_tim;
    }
}