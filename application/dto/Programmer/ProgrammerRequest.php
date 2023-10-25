<?php

class ProgrammerRequest
{
    private $id;
    private $jabatan;
    private $id_tim_programmer;
    private $id_pengguna;

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
    public function getJabatan()
    {
        return $this->jabatan;
    }

    /**
     * @param mixed $jabatan
     */
    public function setJabatan($jabatan)
    {
        $this->jabatan = $jabatan;
    }

    /**
     * @return mixed
     */
    public function getIdTimProgrammer()
    {
        return $this->id_tim_programmer;
    }

    /**
     * @param mixed $id_tim_programmer
     */
    public function setIdTimProgrammer($id_tim_programmer)
    {
        $this->id_tim_programmer = $id_tim_programmer;
    }

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



}