<?php

class LogRequest
{
    private $id_dokumen_lampiran;
    private $id_kategori;
    private $id_list_peran;
    private $id_notifikasi;
    private $id_pengguna;
    private $id_peran;
    private $id_programmer;
    private $id_rating;
    private $id_status_tiket;
    private $id_status_tiket_internal;
    private $id_sub_kategori;
    private $id_sub_tahapan;
    private $id_tahapan;
    private $id_tiket;
    private $id_tiket_data;
    private $id_tiket_image_laporan;
    private $id_tiket_petugas;
    private $id_tim_programmer;
    private $id_todo_list;
    private $id_todo_list_dokumen;
    private $id_via;
    private $id_urgensi;
    private $pengguna;
    private $keterangan;
    private $tanggal_input;
    private $file_name;
    private $file_size;
    private $file_type;
    private $file_original_name;
    private $id_aplikasi;

    /**
     * @return mixed
     */
    public function getIdAplikasi()
    {
        return $this->id_aplikasi;
    }

    /**
     * @param mixed $id_aplikasi
     */
    public function setIdAplikasi($id_aplikasi)
    {
        $this->id_aplikasi = $id_aplikasi;
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


    /**
     * @return mixed
     */
    public function getIdDokumenLampiran()
    {
        return $this->id_dokumen_lampiran;
    }

    /**
     * @param mixed $id_dokumen_lampiran
     */
    public function setIdDokumenLampiran($id_dokumen_lampiran)
    {
        $this->id_dokumen_lampiran = $id_dokumen_lampiran;
    }

    /**
     * @return mixed
     */
    public function getIdKategori()
    {
        return $this->id_kategori;
    }

    /**
     * @param mixed $id_kategori
     */
    public function setIdKategori($id_kategori)
    {
        $this->id_kategori = $id_kategori;
    }

    /**
     * @return mixed
     */
    public function getIdListPeran()
    {
        return $this->id_list_peran;
    }

    /**
     * @param mixed $id_list_peran
     */
    public function setIdListPeran($id_list_peran)
    {
        $this->id_list_peran = $id_list_peran;
    }

    /**
     * @return mixed
     */
    public function getIdNotifikasi()
    {
        return $this->id_notifikasi;
    }

    /**
     * @param mixed $id_notifikasi
     */
    public function setIdNotifikasi($id_notifikasi)
    {
        $this->id_notifikasi = $id_notifikasi;
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

    /**
     * @return mixed
     */
    public function getIdPeran()
    {
        return $this->id_peran;
    }

    /**
     * @param mixed $id_peran
     */
    public function setIdPeran($id_peran)
    {
        $this->id_peran = $id_peran;
    }

    /**
     * @return mixed
     */
    public function getIdProgrammer()
    {
        return $this->id_programmer;
    }

    /**
     * @param mixed $id_programmer
     */
    public function setIdProgrammer($id_programmer)
    {
        $this->id_programmer = $id_programmer;
    }

    /**
     * @return mixed
     */
    public function getIdRating()
    {
        return $this->id_rating;
    }

    /**
     * @param mixed $id_rating
     */
    public function setIdRating($id_rating)
    {
        $this->id_rating = $id_rating;
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
    public function getIdSubTahapan()
    {
        return $this->id_sub_tahapan;
    }

    /**
     * @param mixed $id_sub_tahapan
     */
    public function setIdSubTahapan($id_sub_tahapan)
    {
        $this->id_sub_tahapan = $id_sub_tahapan;
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
    public function getIdTiketData()
    {
        return $this->id_tiket_data;
    }

    /**
     * @param mixed $id_tiket_data
     */
    public function setIdTiketData($id_tiket_data)
    {
        $this->id_tiket_data = $id_tiket_data;
    }

    /**
     * @return mixed
     */
    public function getIdTiketImageLaporan()
    {
        return $this->id_tiket_image_laporan;
    }

    /**
     * @param mixed $id_tiket_image_laporan
     */
    public function setIdTiketImageLaporan($id_tiket_image_laporan)
    {
        $this->id_tiket_image_laporan = $id_tiket_image_laporan;
    }

    /**
     * @return mixed
     */
    public function getIdTiketPetugas()
    {
        return $this->id_tiket_petugas;
    }

    /**
     * @param mixed $id_tiket_petugas
     */
    public function setIdTiketPetugas($id_tiket_petugas)
    {
        $this->id_tiket_petugas = $id_tiket_petugas;
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
    public function getIdTodoList()
    {
        return $this->id_todo_list;
    }

    /**
     * @param mixed $id_todo_list
     */
    public function setIdTodoList($id_todo_list)
    {
        $this->id_todo_list = $id_todo_list;
    }

    /**
     * @return mixed
     */
    public function getIdTodoListDokumen()
    {
        return $this->id_todo_list_dokumen;
    }

    /**
     * @param mixed $id_todo_list_dokumen
     */
    public function setIdTodoListDokumen($id_todo_list_dokumen)
    {
        $this->id_todo_list_dokumen = $id_todo_list_dokumen;
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
    public function getPengguna()
    {
        return $this->pengguna;
    }

    /**
     * @param mixed $pengguna
     */
    public function setPengguna($pengguna)
    {
        $this->pengguna = $pengguna;
    }

    /**
     * @return mixed
     */
    public function getKeterangan()
    {
        return $this->keterangan;
    }

    /**
     * @param mixed $keterangan
     */
    public function setKeterangan($keterangan)
    {
        $this->keterangan = $keterangan;
    }

    /**
     * @return mixed
     */
    public function getTanggalInput()
    {
        return $this->tanggal_input;
    }

    /**
     * @param mixed $tanggal_input
     */
    public function setTanggalInput($tanggal_input)
    {
        $this->tanggal_input = $tanggal_input;
    }

    /**
     * @return mixed
     */
    public function getFileName()
    {
        return $this->file_name;
    }

    /**
     * @param mixed $file_name
     */
    public function setFileName($file_name)
    {
        $this->file_name = $file_name;
    }

    /**
     * @return mixed
     */
    public function getFileSize()
    {
        return $this->file_size;
    }

    /**
     * @param mixed $file_size
     */
    public function setFileSize($file_size)
    {
        $this->file_size = $file_size;
    }

    /**
     * @return mixed
     */
    public function getFileType()
    {
        return $this->file_type;
    }

    /**
     * @param mixed $file_type
     */
    public function setFileType($file_type)
    {
        $this->file_type = $file_type;
    }

    /**
     * @return mixed
     */
    public function getFileOriginalName()
    {
        return $this->file_original_name;
    }

    /**
     * @param mixed $file_original_name
     */
    public function setFileOriginalName($file_original_name)
    {
        $this->file_original_name = $file_original_name;
    }




}