<?php

class TiketAddDataRequest
{
	private $id;
	private $id_sub_kategori;
	private $no_tiket;
	private $id_via;
	private $id_pelapor;
	private $id_urgensi;
    private $id_status_tiket = 7;
    private $id_status_tiket_internal = 7;
    private $id_tim_programmer;
    private $id_aplikasi;
    private $tanggal_pelaksanaan;
    private $status_revisi = "Belum Ada Tindakan";
    private $keterangan_revisi;
	private $nama_pelapor;
	private $bagian_pelapor;
	private $gedung_pelapor;
	private $unit_kerja_pelapor;
	private $ruangan_pelapor;
	private $lantai_pelapor;
	private $telepon_pelapor;
	private $hp_pelapor;
	private $email_pelapor;
	private $keterangan;
	private $user_input;
	private $user_update;
    private $reference_by;
    private $reference_to;

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
    public function getTanggalPelaksanaan()
    {
        return $this->tanggal_pelaksanaan;
    }

    /**
     * @param mixed $tanggal_pelaksanaan
     */
    public function setTanggalPelaksanaan($tanggal_pelaksanaan)
    {
        $this->tanggal_pelaksanaan = $tanggal_pelaksanaan;
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
	function getIdVia()
	{
		return $this->id_via;
	}

	/**
	 * @param mixed $id_via 
	 * @return TiketAddDataRequest
	 */
	function setIdVia($id_via)
	{
		$this->id_via = $id_via;
	}

	/**
	 * @return mixed
	 */
	function getIdPelapor()
	{
		return $this->id_pelapor;
	}

	/**
	 * @param mixed $id_pelapor 
	 * @return TiketAddDataRequest
	 */
	function setIdPelapor($id_pelapor)
	{
		$this->id_pelapor = $id_pelapor;
	}

	/**
	 * @return mixed
	 */
	function getNamaPelapor()
	{
		return $this->nama_pelapor;
	}

	/**
	 * @param mixed $nama_pelapor 
	 * @return TiketAddDataRequest
	 */
	function setNamaPelapor($nama_pelapor)
	{
		$this->nama_pelapor = $nama_pelapor;
	}

	/**
	 * @return mixed
	 */
	function getBagianPelapor()
	{
		return $this->bagian_pelapor;
	}

	/**
	 * @param mixed $bagian_pelapor 
	 * @return TiketAddDataRequest
	 */
	function setBagianPelapor($bagian_pelapor)
	{
		$this->bagian_pelapor = $bagian_pelapor;
	}

	/**
	 * @return mixed
	 */
	function getGedungPelapor()
	{
		return $this->gedung_pelapor;
	}

	/**
	 * @param mixed $gedung_pelapor 
	 * @return TiketAddDataRequest
	 */
	function setGedungPelapor($gedung_pelapor)
	{
		$this->gedung_pelapor = $gedung_pelapor;
	}

	/**
	 * @return mixed
	 */
	function getUnitKerjaPelapor()
	{
		return $this->unit_kerja_pelapor;
	}

	/**
	 * @param mixed $unit_kerja_pelapor 
	 * @return TiketAddDataRequest
	 */
	function setUnitKerjaPelapor($unit_kerja_pelapor)
	{
		$this->unit_kerja_pelapor = $unit_kerja_pelapor;
	}

	/**
	 * @return mixed
	 */
	function getRuanganPelapor()
	{
		return $this->ruangan_pelapor;
	}

	/**
	 * @param mixed $ruangan_pelapor 
	 * @return TiketAddDataRequest
	 */
	function setRuanganPelapor($ruangan_pelapor)
	{
		$this->ruangan_pelapor = $ruangan_pelapor;
	}

	/**
	 * @return mixed
	 */
	function getLantaiPelapor()
	{
		return $this->lantai_pelapor;
	}

	/**
	 * @param mixed $lantai_pelapor 
	 * @return TiketAddDataRequest
	 */
	function setLantaiPelapor($lantai_pelapor)
	{
		$this->lantai_pelapor = $lantai_pelapor;
	}

	/**
	 * @return mixed
	 */
	function getTeleponPelapor()
	{
		return $this->telepon_pelapor;
	}

	/**
	 * @param mixed $telepon_pelapor 
	 * @return TiketAddDataRequest
	 */
	function setTeleponPelapor($telepon_pelapor)
	{
		$this->telepon_pelapor = $telepon_pelapor;
	}

	/**
	 * @return mixed
	 */
	function getHpPelapor()
	{
		return $this->hp_pelapor;
	}

	/**
	 * @param mixed $hp_pelapor 
	 * @return TiketAddDataRequest
	 */
	function setHpPelapor($hp_pelapor)
	{
		$this->hp_pelapor = $hp_pelapor;
	}

	/**
	 * @return mixed
	 */
	function getEmailPelapor()
	{
		return $this->email_pelapor;
	}

	/**
	 * @param mixed $email_pelapor 
	 * @return TiketAddDataRequest
	 */
	function setEmailPelapor($email_pelapor)
	{
		$this->email_pelapor = $email_pelapor;
	}

	/**
	 * @return mixed
	 */
	function getKeterangan()
	{
		return $this->keterangan;
	}

	/**
	 * @param mixed $keterangan 
	 * @return TiketAddDataRequest
	 */
	function setKeterangan($keterangan)
	{
		$this->keterangan = $keterangan;
	}

	/**
	 * @return mixed
	 */
	function getUserInput()
	{
		return $this->user_input;
	}

	/**
	 * @param mixed $user_input 
	 * @return TiketAddDataRequest
	 */
	function setUserInput($user_input)
	{
		$this->user_input = $user_input;
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
	 * @return TiketAddDataRequest
	 */
	function setUserUpdate($user_update)
	{
		$this->user_update = $user_update;
	}
	/**
	 * @return mixed
	 */
	function getNoTiket()
	{
		return $this->no_tiket;
	}

	/**
	 * @param mixed $no_tiket 
	 * @return TiketAddDataRequest
	 */
	function setNoTiket($no_tiket)
	{
		$this->no_tiket = $no_tiket;
	}
}
