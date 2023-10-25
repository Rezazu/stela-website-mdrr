<?php
class SubTahapanDomain{
    private $id;
    private $id_tahapan;
    private $id_tiket;
    private $sub_tahapan;
    private $user_input;
    private $user_update;

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
    public function getIdTahapan()
    {
        return $this->id_tahapan;
    }

    /**
     * @param mixed $id_tahapan
     */
    public function setIdTahapan($id_tahapan)
    {
        $this->id_tahapan = $id_tahapan;
    }

    /**
     * @return mixed
     */
    public function getIdTiket()
    {
        return $this->id_tiket;
    }

    /**
     * @param mixed $id_tiket
     */
    public function setIdTiket($id_tiket)
    {
        $this->id_tiket = $id_tiket;
    }

    /**
     * @return mixed
     */
    public function getSubTahapan()
    {
        return $this->sub_tahapan;
    }

    /**
     * @param mixed $sub_tahapan
     */
    public function setSubTahapan($sub_tahapan)
    {
        $this->sub_tahapan = $sub_tahapan;
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