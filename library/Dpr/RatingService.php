<?php

use Carbon\Carbon;

class Dpr_RatingService
{

	function __construct()
	{
		$this->rating = new Rating();
		$this->penggunaService = new Dpr_PenggunaService();
		$this->tiketService = new Dpr_TiketService();
		$this->tiketPetugasService = new Dpr_TiketPetugasService();
//		$this->penggunaService = new Dpr_PenggunaService();
		$this->db = Zend_Registry::get('db_stela');
	}

	function getAllData()
	{
		$select = $this->rating->select()->where('status = 1')->order('jumlah_tiket');
		$result = $this->rating->fetchAll($select);
		return $result;
	}

	function getAllQueryData()
	{
		$sql = 'SELECT * FROM rating WHERE status = 1 ORDER BY jumlah_tiket DESC';

		$db = Zend_Registry::get('db_stela');
		$stmt = $db->query($sql);
		$stmt->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $stmt->fetchAll();
		return $result;
	}

	function getData($id)
	{
		$select = $this->rating->select()->where('id = ?', $id);
		$result = $this->rating->fetchRow($select);
		return $result;
	}

	function getQueryData($id)
	{
		$sql = 'SELECT *
				FROM rating
				WHERE status = 1 AND id = ?';

		$db = Zend_Registry::get('db_stela');
		$stmt = $db->query($sql, array($id));
		$stmt->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $stmt->fetch();
		return $result;
	}

	function addData($id_pengguna, $jumlah_tiket, $rating)
	{
		$user_log = Zend_Auth::getInstance()->getIdentity()->username;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'id_pengguna' => $id_pengguna,
			'jumlah_tiket' => $jumlah_tiket,
			'rating' => round($rating, 2),
			'user_input' => $user_log,
			'tanggal_input' => $tanggal_log,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);

		$this->rating->insert($params);
	}

	function editData($id, $jumlah_tiket, $rating)
	{
		$user_log = Zend_Auth::getInstance()->getIdentity()->username;
		$tanggal_log = date('Y-m-d H:i:s');
		$params = array(
			'jumlah_tiket' => $jumlah_tiket,
			'rating' => $rating,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
		$where = $this->rating->getAdapter()->quoteInto('id = ?', $id);
		$this->rating->update($params, $where);
	}

	function deleteData($id)
	{
		$where = $this->rating->getAdapter()->quoteInto('id = ?', $id);
		$this->rating->delete($where);
	}

	function softDeleteData($id)
	{
		$user_log = Zend_Auth::getInstance()->getIdentity()->username;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'status' => 9,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);

		$where = $this->rating->getAdapter()->quoteInto('id = ?', $id);
		$this->rating->update($params, $where);
	}

	function softDeleteQueryData($id)
	{
		$user_log = Zend_Auth::getInstance()->getIdentity()->username;
		$tanggal_log = date('Y-m-d H:i:s');

		$sql = 'UPDATE rating SET status = 9, user_update = ?, tanggal_update = ? WHERE id = ?';

		$db = Zend_Registry::get('db_stela');
		$db->query($sql, array($user_log, $tanggal_log, $id));
	}


	/**
	 * Get data rating by id pengguna
	 * @param integer $id_pengguna id pengguna
	 * @return array data rating
	 */
	function getDataByIdPengguna($id_pengguna)
	{
		$select = $this->rating->select()->where('id_pengguna = ?', $id_pengguna);
		$result = $this->rating->fetchRow($select);
		return $result;
	}

	/**
	 * Service mengirim rating ke petugas by id petugas.
	 * Melakukan kalkulasi otomatis untuk menambahkan rating pekerjaan
	 * rating kecepatan dan total rating
	 * @param integer $id_pengguna id petugas yang akan ditambah rating
	 * @param float $rating_pekerjaan rating pekerjaan yang ditambahkan
	 * @param float $rating_kecepatan rating kecepatan yang ditambahkan
	 * @param string $user_input user yang memberikan rating
	 * @return boolean true|false 1|0
	 */
	function addToPetugas($id_pengguna, $rating, $user_input)
	{
		$oldData = $this->getDataByIdPengguna($id_pengguna);

		// kalo belum ada datanya buat aja dulu
		if (!$oldData) {
			return $this->addData($id_pengguna, 1, $rating);
		}

		// kalo udah ada datanya ya update aja ya kan
		$param = [
			'jumlah_tiket' => $oldData['jumlah_tiket'] + 1,
			'rating' => 0,
			'user_update' => $user_input,
			'tanggal_update' => date('Y-m-d H:i:s'),
		];
		// calculate rating pekerjaan 
		$oldRating = $oldData['rating'] * $oldData['jumlah_tiket'];
		$param['rating'] = round(($oldRating + $rating) / ($oldData['jumlah_tiket'] + 1), 2);

		// update
		$where = $this->rating->getAdapter()->quoteInto('id = ?', $oldData['id']);
		return $this->rating->update($param, $where);
	}

	// check rating
	function checkRating($id_pengguna)
	{
		$sql = "SELECT * FROM tiket WHERE id_status_tiket = 6 AND rating is NULL AND id_pelapor = ?";

		$db = Zend_Registry::get('db_stela');
		$stmt = $db->query($sql, [$id_pengguna]);
		$stmt->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $stmt->fetch();

		return $result ? true : false;
	}

	// function update rating di table tiket
	function updateRatingTiket($id_tiket, $rating, $ratingKeterangan, $user_input)
	{
		$sql = "UPDATE tiket SET
                rating=?, keterangan_rating = ?, user_update = ?, tanggal_update = ? WHERE id = ?";
		$statement = $this->db->prepare($sql);
		$statement->execute([
			$rating,
			$ratingKeterangan,
			$user_input,
            date('Y-m-d H:i:s'),
			$id_tiket,
		]);
	}

	// function get top 3 petugas by rating
	// this month and this year
	function topPetugas()
	{
        $allPetugas = $this->penggunaService->getPeranStelaForDaftarPetugas();
        $today = Carbon::now();
        $topMonth = [];
        $topYear = [];
        // iterate petugas
        foreach ($allPetugas as $petugas) {
            $tmpMonth = [
                'id_pengguna' => $petugas['id'],
                'nama' => $petugas['nama'],
                'peran' => $petugas['peran'],
                'jumlah_tiket' => 0,
                'jumlah_tiket_rating' => 0,
				'rating' => 0,
                'point' => 0
			];

            $tmpYear = [
                'id_pengguna' => $petugas['id'],
                'nama' => $petugas['nama'],
                'peran' => $petugas['peran'],
                'profile' => $petugas['profile'],
                'jumlah_tiket' => 0,
                'jumlah_tiket_rating' => 0,
                'rating' => 0,
                'point' => 0
            ];
            // iterate tiket petguas
            $allTiketPetugas = $this->tiketPetugasService->getAllDataByIdPetugas($petugas['id']);
            // kalo dia pernah ngerjain tiket ya gasin
            if ($allTiketPetugas)
                foreach ($allTiketPetugas as $tiketPetugas) {
                    // get info tiket
                    $tiket = $this->tiketService->findById($tiketPetugas->id_tiket);

                    // check month
                    if (Carbon::parse($tiket->getTanggalInput())->format("Y-m") == $today->format("Y-m")) {
                        $tmpMonth['jumlah_tiket']++;
//                        $tmpMonth['rating'] += $tiket->getRating();
                        if ($tiket->getIdStatusTiketInternal() == 6 && $tiket->getRating()){
                            $tmpMonth['jumlah_tiket_rating']++;
                            $tmpMonth['point'] += $tiket->getRating();
                        }
                    }

                    // check year
                    if (Carbon::parse($tiket->getTanggalInput())->year == $today->year) {
                        $tmpYear['jumlah_tiket']++;
//                        $tmpYear['rating'] += $tiket->getRating();
                        if ($tiket->getIdStatusTiketInternal() == 6 && $tiket->getRating()){
                            $tmpYear['jumlah_tiket_rating']++;
                            $tmpYear['point'] += $tiket->getRating();
                        }
                    }
                }

			// calculate rating
			$tmpMonth['rating'] = $tmpMonth['jumlah_tiket_rating'] == 0 ? 0 : $tmpMonth['point'] / $tmpMonth['jumlah_tiket_rating'];
			$tmpYear['rating'] = $tmpYear['jumlah_tiket_rating'] == 0 ? 0 : $tmpYear['point'] / $tmpYear['jumlah_tiket_rating'];

            // push to top variable
            array_push($topMonth, $tmpMonth);
            array_push($topYear, $tmpYear);
        }

        // sorting by rating
        usort($topMonth, function ($first, $second) {
            return $first['point'] < $second['point'];
        });
        usort($topYear, function ($first, $second) {
            return $first['point'] < $second['point'];
        });

        return [
            'date_info' => $today->toDateString(),
            'month' => array_slice($topMonth, 0, 3),
            'year' => array_slice($topYear, 0, 3),
        ];
	}

    public function topPetugasTiapBulanTahunIni()
    {
        $arrayTop = [
            1 => null,
            2 => null,
            3 => null
        ];

        $arrayBulan = [
            'Januari' => $arrayTop,
            'Februari' => $arrayTop,
            'Maret' => $arrayTop,
            'April'=> $arrayTop,
            'Mei' => $arrayTop,
            'Juni' => $arrayTop,
            'Juli' => $arrayTop,
            'Agustus' => $arrayTop,
            'September' => $arrayTop,
            'Oktober' => $arrayTop,
            'November' => $arrayTop,
            'Desember' => $arrayTop
        ];

        $tahunSaatIni = date('Y');
        if ($datas = $this->tiketService->getAllData()){
            $temp = [];
            foreach ($datas as $data){
                $tahun = explode('-', explode(' ', $data->tanggal_input)[0])[0];
                if ($tahun == $tahunSaatIni){
                    $bulan = explode(' ', (new Dpr_Controller_Action_Helper_YmdToIndo())->direct($data->tanggal_input)[1])[1];
                    if ($petugas = $this->tiketPetugasService->getAllDataByIdTiket($data->id)){
                        foreach ($petugas as $petuga){
                            $pengguna = $this->penggunaService->findById($petuga->id_petugas)->getNamaLengkap();
                            $peran = (new Dpr_PeranService())->getAllDataByIdPengguna($petuga->id_petugas)[0];
                            if ($data->id_status_tiket == 6 && $data->rating != null){
                                $temp[$bulan][$pengguna] += $data->rating;
                            }
                        }
                    }
                }
            }
        }

        if ($temp) {
            $keys = array_keys($temp);
        }
        $keysBulan = array_keys($arrayBulan);

        for ($i = 0; $i < count($temp); $i++){
            arsort($temp[$keys[$i]]);
        }

        for ($i = 0; $i < count($keysBulan) ; $i++){
            if (isset($temp[$keysBulan[$i]])){
                $nama = array_keys($temp[$keysBulan[$i]]);
                $arrayBulan[$keysBulan[$i]] = [
                    1 => $nama[0] ? $nama[0] : null,
                    2 => $nama[1] ? $nama[1] : null,
                    3 => $nama[2] ? $nama[2] : null
                ];
            }
        }

        return $arrayBulan;
    }
}