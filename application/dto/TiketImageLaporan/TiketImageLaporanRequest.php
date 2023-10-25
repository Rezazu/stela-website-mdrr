<?php
class TiketImageLaporanRequest
{
    private $id;
    private $id_tiket;
    private $permasalahan_akhir;
    private $solusi;
    private $tipe_laporan;
    private $image_name;
    private $image_type;
    private $image_size;
    private $original_name;
    private $status = 1;
    private $user_input;
    private $user_update;


    public function getPermasalahanAkhir()
    {
        return $this->permasalahan_akhir;
    }

    public function setPermasalahanAkhir($permasalahan_akhir)
    {
        $this->permasalahan_akhir = $permasalahan_akhir;
    }

    public function getSolusi()
    {
        return $this->solusi;
    }

    public function setSolusi($solusi)
    {
        $this->solusi = $solusi;
    }

    public function getTipeLaporan()
    {
        return $this->tipe_laporan;
    }

    public function setTipeLaporan($tipe_laporan)
    {
        $this->tipe_laporan = $tipe_laporan;
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
    public function getOriginalName()
    {
        return $this->original_name;
    }

    /**
     * @param mixed $original_name
     */
    public function setOriginalName($original_name)
    {
        $this->original_name = $original_name;
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
    public function getImageName()
    {
        return $this->image_name;
    }

    /**
     * @param mixed $image_name
     */
    public function setImageName($image_name)
    {
        $this->image_name = $image_name;
    }

    /**
     * @return mixed
     */
    public function getImageType()
    {
        return $this->image_type;
    }

    /**
     * @param mixed $image_type
     */
    public function setImageType($image_type)
    {
        $this->image_type = $image_type;
    }

    /**
     * @return mixed
     */
    public function getImageSize()
    {
        return $this->image_size;
    }

    /**
     * @param mixed $image_size
     */
    public function setImageSize($image_size)
    {
        $this->image_size = $image_size;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
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
