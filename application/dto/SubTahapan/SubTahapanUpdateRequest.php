<?php

class SubTahapanUpdateRequest
{
    private $id;
    private $sub_tahapan;
    private $user_update;
    private $status;

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
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $user_update
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }
}