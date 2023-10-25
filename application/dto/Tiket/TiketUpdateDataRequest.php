<?php

class TiketUpdateDataRequest
{
	private $id;
	private $id_status_tiket;
	private $id_status_tiket_internal;
	private $id_sub_kategori;
	private $id_tim_programmer;
	private $id_urgensi;
    private $id_aplikasi;
    private $id_via;
	private $permasalahan_akhir;
	private $solusi;
	private $tanggal_pelaksanaan;
	private $rating;
	private $status;
	private $status_revisi;
	private $keterangan_revisi;
	private $user_update;
    private $reference_by;
    private $reference_to;

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
    public function getReferenceBy()
    {
        return $this->reference_by;
    }

    /**
     * @param mixed $reference_by
     */
    public function setReferenceBy($reference_by)
    {
        $this->reference_by = $reference_by;
    }

    /**
     * @return mixed
     */
    public function getReferenceTo()
    {
        return $this->reference_to;
    }

    /**
     * @param mixed $reference_to
     */
    public function setReferenceTo($reference_to)
    {
        $this->reference_to = $reference_to;
    }


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
	public function getStatusRevisi()
	{
		return $this->status_revisi;
	}

	/**
	 * @param mixed $status_revisi
	 */
	public function setStatusRevisi($status_revisi)
	{
		$this->status_revisi = $status_revisi;
	}

	/**
	 * @return mixed
	 */
	public function getKeteranganRevisi()
	{
		return $this->keterangan_revisi;
	}

	/**
	 * @param mixed $keterangan_revisi
	 */
	public function setKeteranganRevisi($keterangan_revisi)
	{
		$this->keterangan_revisi = $keterangan_revisi;
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
	function getId()
	{
		return $this->id;
	}

	/**
	 * @param mixed $id 
	 * @return TiketUpdateDataRequest
	 */
	function setId($id)
	{
		$this->id = $id;
	}

	/**
	 * @return mixed
	 */
	function getIdStatusTiket()
	{
		return $this->id_status_tiket;
	}

	/**
	 * @param mixed $id_status_tiket 
	 * @return TiketUpdateDataRequest
	 */
	function setIdStatusTiket($id_status_tiket)
	{
		$this->id_status_tiket = $id_status_tiket;
	}

	/**
	 * @return mixed
	 */
	function getIdStatusTiketInternal()
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
	 * @param mixed $id_status_tiket_internal
	 * @return TiketUpdateDataRequest
	 */

	/**
	 * @return mixed
	 */
	function getIdSubKategori()
	{
		return $this->id_sub_kategori;
	}

	/**
	 * @param mixed $id_sub_kategori 
	 * @return TiketUpdateDataRequest
	 */
	function setIdSubKategori($id_sub_kategori)
	{
		$this->id_sub_kategori = $id_sub_kategori;
	}

	/**
	 * @return mixed
	 */
	function getIdTimProgrammer()
	{
		return $this->id_tim_programmer;
	}

	/**
	 * @param mixed $id_tim_programmer 
	 * @return TiketUpdateDataRequest
	 */
	function setIdTimProgrammer($id_tim_programmer)
	{
		$this->id_tim_programmer = $id_tim_programmer;
	}

	/**
	 * @return mixed
	 */
	function getPermasalahanAkhir()
	{
		return $this->permasalahan_akhir;
	}

	/**
	 * @param mixed $permasalahan_akhir 
	 * @return TiketUpdateDataRequest
	 */
	function setPermasalahanAkhir($permasalahan_akhir)
	{
		$this->permasalahan_akhir = $permasalahan_akhir;
	}

	/**
	 * @return mixed
	 */
	function getSolusi()
	{
		return $this->solusi;
	}

	/**
	 * @param mixed $solusi 
	 * @return TiketUpdateDataRequest
	 */
	function setSolusi($solusi)
	{
		$this->solusi = $solusi;
	}

	/**
	 * @return mixed
	 */
	function getTanggalPelaksanaan()
	{
		return $this->tanggal_pelaksanaan;
	}

	/**
	 * @param mixed $tanggal_pelaksanaan 
	 * @return TiketUpdateDataRequest
	 */
	function setTanggalPelaksanaan($tanggal_pelaksanaan)
	{
		$this->tanggal_pelaksanaan = $tanggal_pelaksanaan;
	}

	/**
	 * @return mixed
	 */
	function getRating()
	{
		return $this->rating;
	}

	/**
	 * @param mixed $rating_pekerjaan 
	 * @return TiketUpdateDataRequest
	 */
	function setRating($rating)
	{
		$this->rating = $rating;
	}

	/**
	 * @return mixed
	 */
	function getStatus()
	{
		return $this->status;
	}

	/**
	 * @param mixed $status 
	 * @return TiketUpdateDataRequest
	 */
	function setStatus($status)
	{
		$this->status = $status;
	}

	/**
	 * @return mixed
	 */
	function getUserUpdate()
	{
		return $this->user_update;
	}

	/**
	 * @param mixed $user_update 
	 * @return TiketUpdateDataRequest
	 */
	function setUserUpdate($user_update)
	{
		$this->user_update = $user_update;
	}
}
