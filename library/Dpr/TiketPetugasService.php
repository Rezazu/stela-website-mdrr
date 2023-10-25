<?php
class Dpr_TiketPetugasService
{

	function __construct()
	{
		$this->tiket_petugas = new TiketPetugas();
        $this->db = Zend_Registry::get('db_stela');
        $this->pengguna = new Dpr_PenggunaService();
        $this->peran = new Dpr_PeranService();
        $this->listPeran = new Dpr_ListPeranService();
	}

	function getAllData()
	{
		$select = $this->tiket_petugas->select()->order('id_tiket');
		$result = $this->tiket_petugas->fetchAll($select);
		return $result;
	}

    function getAllDataByIdPetugas($id_petugas){
        $sql = "SELECT * FROM tiket_petugas WHERE id_petugas = ? ORDER BY id_tiket";
        $statement = $this->db->prepare($sql);
        $statement->execute([$id_petugas]);
        $statement->setFetchMode(Zend_Db::FETCH_OBJ);

        if ($result = $statement->fetchAll())
        {
            
            return $result;
        }else{
            return null;
        }
    }

    function getAllDataByIdTiket($id_tiket)
    {
        $sql = "SELECT * FROM tiket_petugas WHERE id_tiket = ? ORDER BY id_petugas";
        $statement = $this->db->prepare($sql);
        $statement->execute([$id_tiket]);
        $statement->setFetchMode(Zend_Db::FETCH_OBJ);

        if ($result = $statement->fetchAll())
        {
            return $result;
        }else{
            return null;
        }
    }

	function getAllQueryData()
	{
		$sql = 'SELECT * FROM tiket_petugas ORDER BY id_tiket';

		$db = Zend_Registry::get('db_stela');
		$stmt = $db->query($sql);
		$stmt->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $stmt->fetchAll();
		return $result;
	}

	function getData($id)
	{
		$select = $this->tiket_petugas->select()->where('id = ?', $id);
		$result = $this->tiket_petugas->fetchRow($select);
		return $result;
	}

	function getQueryData($id)
	{
		$sql = 'SELECT * FROM tiket_petugas WHERE status = 1 AND id = ?';

		$db = Zend_Registry::get('db_stela');
		$stmt = $db->query($sql, array($id));
        $stmt->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $stmt->fetch();
		return $result;
	}

	function addData($id_tiket,	$id_petugas, $user_log)
	{
//		$status = false;
$petugas = $this->getAllDataByIdPetugas($id_petugas);

foreach ($petugas as $tugas)
{
	if ($tugas->id_tiket == $id_tiket)
	{
		$status = true;
	}
}

if ($status == false)
{
//        $user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
	$tanggal_log = date('Y-m-d H:i:s');

	$params = array(
		'id_tiket' => $id_tiket,
		'id_petugas' => $id_petugas,
		'user_input' => $user_log,
		'tanggal_input' => $tanggal_log,
		'user_update' => $user_log,
		'tanggal_update' => $tanggal_log,
	);

	$this->tiket_petugas->insert($params);
}
	}

	function editData($id_tiket, $id_petugas)
	{
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'id_tiket' => $id_tiket,
			'id_petugas' => $id_petugas,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log,
		);

		$where = $this->tiket_petugas->getAdapter()->quoteInto('id = ?', $id_tiket);
		$this->tiket_petugas->update($params, $where);
	}

	function deleteData($id)
	{
		$where = $this->tiket_petugas->getAdapter()->quoteInto('id = ?', $id);
		$this->tiket_petugas->delete($where);
	}

	function deleteQueryData($id)
	{
		$sql = 'DELETE FROM tiket_petugas WHERE id = ?';

		$db = Zend_Registry::get('db_stela');
		$db->query($sql, array($id));
	}

	function softDeleteData($id)
	{
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'status' => 9,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);

		$where = $this->tiket_petugas->getAdapter()->quoteInto('id = ?', $id);
		$this->tiket_petugas->update($params, $where);
	}

	function softDeleteQueryData($id)
	{
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$sql = 'UPDATE tiket_petugas SET status = 9, user_update = ?, tanggal_update = ? WHERE id = ?';

		$db = Zend_Registry::get('db_stela');
		$db->query($sql, array($user_log, $tanggal_log, $id));
	}

    public function getListPetugasStelaAktif($id)
    {
        $listPetugas = $this->getAllDataByIdTiket($id);

        if ($listPetugas){
            $counter = 1;
            foreach ($listPetugas as $petugas)
            {
                $peran = $this->peran->getAllDataByIdPengguna($petugas->id_petugas);
                if (is_array($peran))
                {
                    foreach ($peran as $per)
                    {
                        $rating = 0;
                        if ($per->id_peran >= 5 || $per->id_peran == 1){
                            $ratings = $this->getAllDataByIdPetugas($petugas->id_petugas);
                            $counter1 = 0;
                            foreach ($ratings as $r){
                                $tiket = (new Dpr_TiketService())->findById($r->id_tiket);
                                if ($tiket->getIdStatusTiket() == 6 && $tiket->getRating() != null){
                                    $rating += $tiket->getRating();
                                    $counter1++;
                                }
                            }
                            $pengguna = $this->pengguna->findById($petugas->id_petugas);
                            $data[$counter] = [
                                'id' => $petugas->id_petugas,
                                'nama' => $pengguna->getNamaLengkap(),
                                'peran' => ($per->id_peran == 1) ? 'servicedesk' : $this->listPeran->findById($per->id_peran)->getNamaPeran(),
                                'rating' => $counter1 ? $rating/$counter1 : 0,
                                'profile' => ($pengguna->getProfile()) ? "/stela/assets/profile/{$pengguna->getProfile()}" : "/stela/assets/profile/avatar1.jpeg"
                            ];
                        }
                    }
                    $counter++;
                }
            }
        }else{
            $data = null;
        }
        return $data;
    }

    public function getRatingByIdPetugas($id_petugas){
        $tiketPetugas = $this->getAllDataByIdPetugas($id_petugas);
        if ($tiketPetugas){
            $counter = 0;
            $rating = 0.00;
            foreach ($tiketPetugas as $petugas) {
                $tiket = (new Dpr_TiketService())->findById($petugas->id_tiket);
                if ($tiket->getIdStatusTiket() == 6 && $tiket->getRating() != null){
                    $rating += $tiket->getRating();
                    $counter++;
                }
            }
        }
        return [
            'jumlah_tiket' => count($tiketPetugas),
            'jumlah_tiket_nilai' => $counter,
            'rating' => ($rating != 0) ? ($rating/$counter) : 0
        ];
    }

    public function deleteEskalasiByIdPetugasAndIdTiket($id_petugas, $id_tiket)
    {
        $id = null;
        $sql1 = "SELECT * FROM tiket_petugas WHERE id_petugas = ? AND id_tiket = ?";
        $statement1 = $this->db->prepare($sql1);
        $statement1->execute([$id_petugas, $id_tiket]);
        $statement1->setFetchMode(Zend_Db::FETCH_OBJ);
        if ($result = $statement1->fetch()){
            $id = $result->id;
        }

        if ($id) {
            $sql2 = "DELETE FROM tiket_petugas WHERE id = ?";
            $statement2 = $this->db->prepare($sql2);
            $statement2->execute([$id]);
        }

        $data = $this->getData($id);
        if ($data){
            return "Gagal Menghapus";
        }else{
            return "Berhasil Menghapus";
        }
    }

    public function getAllDataOrderByIdPetugas()
    {
        $sql = "SELECT * FROM tiket_petugas ORDER BY tanggal_input";
        $statement = $this->db->query($sql);
        $statement->setFetchMode(Zend_Db::FETCH_OBJ);

        try{
            if ($result = $statement->fetchAll()) {
                return $result;
            }else{
                return null;
            }
        } finally {
            $statement->closecursor();
        }
    }

    public function rekapRatingTahun()
    {
        $datas = $this->getAllDataOrderByIdPetugas();

        $pengguna = [
            'nama' => '',
            'rating' => 0,
            'jumlah_tiket' => 0,
            'jumlah_tiket_rating' => 0,
            'point' => 0
        ];

        $listTahun[1] = 'All';

        $d['All']['tahun'] = 'All';

        $counter = 0;
        $tahunTmp = '';
        if ($datas) {
            foreach ($datas as $data) {
                $tahun = explode('-', explode(' ', $data->tanggal_input)[0])[0];
                $tiket = (new Dpr_TiketService())->findById($data->id_tiket);
                $nama = $this->pengguna->findById($data->id_petugas)->getNamaLengkap();

                if ($tahun != $tahunTmp) {
                    $counter++;
                    $listTahun[$counter + 1] = $tahun;
                    $d[$tahun]['tahun'] = $tahun;
                }

                if (!isset($d['All']['pengguna'][$data->id_petugas])) {
                    $d['All']['pengguna'][$data->id_petugas] = $pengguna;
                    $d['All']['pengguna'][$data->id_petugas]['nama'] = $nama;
                }
                if (!isset($d[$tahun]['pengguna'][$data->id_petugas])) {
                    $d[$tahun]['pengguna'][$data->id_petugas] = $pengguna;
                    $d[$tahun]['pengguna'][$data->id_petugas]['nama'] = $nama;
                }

                $d['All']['pengguna'][$data->id_petugas]['jumlah_tiket'] += 1;
                $d[$tahun]['pengguna'][$data->id_petugas]['jumlah_tiket'] += 1;

                if ($tiket->getIdStatusTiketInternal() == 6 && $tiket->getRating() != null) {
                    $d['All']['pengguna'][$data->id_petugas]['jumlah_tiket_rating'] += 1;
                    $d['All']['pengguna'][$data->id_petugas]['point'] += $tiket->getRating();
                    $d['All']['pengguna'][$data->id_petugas]['rating'] = round($d['All']['pengguna'][$data->id_petugas]['point'] / $d['All']['pengguna'][$data->id_petugas]['jumlah_tiket_rating'], 2);
                    $d[$tahun]['pengguna'][$data->id_petugas]['jumlah_tiket_rating'] += 1;
                    $d[$tahun]['pengguna'][$data->id_petugas]['point'] += $tiket->getRating();
                    $d[$tahun]['pengguna'][$data->id_petugas]['rating'] = round($d[$tahun]['pengguna'][$data->id_petugas]['point'] / $d[$tahun]['pengguna'][$data->id_petugas]['jumlah_tiket_rating'], 2);
                }
                $tahunTmp = $tahun;
            }
        }

        return [$listTahun, $d];
    }
}
