<?php

class TimProgrammerUpdateRequest
{
    private $id;
    private $nama_tim;

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
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