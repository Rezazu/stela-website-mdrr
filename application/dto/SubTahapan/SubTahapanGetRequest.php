<?php
class SubTahapanGetRequest{
    private $id_tahapan;
    private $id_tiket;

    public function setIdTahapan($id_tahapan){
        $this->id_tahapan = $id_tahapan;
    }

    public function getIdTahapan(){
        return $this->id_tahapan;
    }

    public function setIdTiket($id_tiket){
        $this->id_tiket = $id_tiket;
    }

    public function getIdTiket(){
        return $this->id_tiket;
    }
}