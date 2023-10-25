<?php

use Carbon\Carbon;

require_once APPLICATION_PATH . '/dto/Tiket/TiketAddDataRequest.php';
require_once APPLICATION_PATH . '/dto/Tiket/TiketUpdateDataRequest.php';
require_once APPLICATION_PATH . '/dto/Tiket/TiketFindByResponse.php';
require_once APPLICATION_PATH . '/dto/Tiket/TiketUpdateSubKategoriRequest.php';
require_once APPLICATION_PATH . '/dto/Tiket/TiketUpdateLangsungSelesaiRequest.php';
require_once APPLICATION_PATH . '/dto/Tiket/TiketUpdateIdTimProgrammerRequest.php';
require_once APPLICATION_PATH . '/dto/Tiket/TiketUpdateRevisiServiceDeskRequest.php';

class Dpr_TiketService
{
    protected $db;
    protected $tiket;
    protected $tanggal_log;

    public function __construct()
    {
        $this->dateFormat = new Dpr_Controller_Action_Helper_YmdToIndo();
        $this->db = Zend_Registry::get('db_stela');
        $this->tiket = new Tiket();
        $this->tanggal_log = date('Y-m-d H:i:s');
        $this->statusTiketService = new Dpr_StatusTiketService();
        $this->subKategoriService = new Dpr_SubKategoriService();
        $this->urgensiService = new Dpr_UrgensiService();
        $this->viaService = new Dpr_ViaService();
        $this->log = new Dpr_LogService();
    }

    private function responseFind($result)
    {
        $response = new TiketFindByResponse();
        $response->setId($result->id);
        $response->setNoTiket($result->no_tiket);
        $response->setIdVia($result->id_via);
        $response->setIdPelapor($result->id_pelapor);
        $response->setIdStatusTiket($result->id_status_tiket);
        $response->setIdSubKategori($result->id_sub_kategori);
        $response->setIdTimProgrammer($result->id_tim_programmer);
        $response->setIdAplikasi($result->id_aplikasi);
        $response->setIdUrgensi($result->id_urgensi);
        $response->setIdStatusTiketInternal($result->id_status_tiket_internal);
        $response->setNamaPelapor($result->nama_pelapor);
        $response->setBagianPelapor($result->bagian_pelapor);
        $response->setGedungPelapor($result->gedung_pelapor);
        $response->setUnitKerjaPelapor($result->unit_kerja_pelapor);
        $response->setRuanganPelapor($result->ruangan_pelapor);
        $response->setLantaiPelapor($result->lantai_pelapor);
        $response->setTeleponPelapor($result->telepon_pelapor);
        $response->setHpPelapor($result->hp_pelapor);
        $response->setEmailPelapor($result->email_pelapor);
        $response->setKeterangan($result->keterangan);
        $response->setPermasalahanAkhir($result->permasalahan_akhir);
        $response->setSolusi($result->solusi);
        $response->setTanggalPelaksanaan($result->tanggal_pelaksanaan);
        $response->setRating($result->rating);
        $response->setKeteranganRating($result->keterangan_rating);
        $response->setStatus($result->status);
        $response->setStatusRevisi($result->status_revisi);
        $response->setKeteranganRevisi($result->keterangan_revisi);
        $response->setUserInput($result->user_input);
        $response->setTanggalInput($result->tanggal_input);
        $response->setUserUpdate($result->user_update);
        $response->setTanggalUpdate($result->tanggal_update);
        $response->setReferenceBy($result->reference_by);
        $response->setReferenceTo($result->reference_to);

        return $response;
    }

    public function findById($id)
    {
        $sql = "SELECT * FROM tiket WHERE id = ?";
        $statement = $this->db->prepare($sql);
        $statement->execute([$id]);
        $statement->setFetchMode(Zend_Db::FETCH_OBJ);

        if ($result = $statement->fetch()) {
            return $this->responseFind($result);
        } else {
            return null;
        }
    }

    public function noDtofindById($id)
    {
        $sql = "SELECT * FROM tiket WHERE id = ?";
        $statement = $this->db->prepare($sql);
        $statement->execute([$id]);
        $statement->setFetchMode(Zend_Db::FETCH_OBJ);
        return $statement->fetch();
    }

    public function noDtofindByNoTiket($no_tiket)
    {
        $sql = "SELECT * FROM tiket WHERE no_tiket = ?";
        $statement = $this->db->prepare($sql);
        $statement->execute([$no_tiket]);
        $statement->setFetchMode(Zend_Db::FETCH_OBJ);
        return $statement->fetch();
    }

    public function findByNoTiket($no_tiket)
    {
        $sql = "SELECT * FROM tiket WHERE no_tiket = ?";
        $statement = $this->db->prepare($sql);
        $statement->execute([$no_tiket]);
        $statement->setFetchMode(Zend_Db::FETCH_OBJ);

        if ($result = $statement->fetch()) {
            return $this->responseFind($result);
        } else {
            return null;
        }
    }

    public function findByIdTimProgrammer($id_tim_programmer)
    {
        $sql = "SELECT * FROM tiket WHERE id_tim_programmer = ?";
        $statement = $this->db->prepare($sql);
        $statement->execute([$id_tim_programmer]);
        $statement->setFetchMode(Zend_Db::FETCH_OBJ);

        if ($result = $statement->fetch()) {
            return $this->responseFind($result);
        } else {
            return null;
        }
    }

    public function getAllData()
    {
        $sql = "SELECT * FROM tiket WHERE status = 1 ORDER BY tanggal_input ASC";
        $statement = $this->db->query($sql);
        $statement->setFetchMode(Zend_Db::FETCH_OBJ);

        if ($result = $statement->fetchAll()) {
            return $result;
        } else {
            return null;
        }
    }

    public function getAllDataSingrusForRekapOperator()
    {
        $datas = $this->getAllDataSingrusForRekap();

        $initiatePermintaan = 0;

        $listTahun[1] = 'All';
        $result['All']['tahun'] = 'All';
        $result['All']['permintaan'] = $initiatePermintaan;

        $counter = 0;
        $tahunTemp = '';
        if ($datas) {
            foreach ($datas as $data) {
                $tiket = $this->findById($data->id);
                $tahun = explode(
                    '-',
                    explode(
                        ' ',
                        $this->dateFormat->directAngkaFUll($tiket->getTanggalInput())
                    )[1]
                )[2];

                if ($tahun != $tahunTemp) {
                    $counter++;
                    $listTahun[$counter + 1] = $tahun;
                    $result[$tahun]['tahun'] = $tahun;
                    $result[$tahun]['permintaan'] = $initiatePermintaan;
                }

                $result[$tahun]['permintaan'] += 1;
                $result['All']['permintaan'] += 1;

                $tahunTemp = $tahun;
            }
        }

        return [$result, $listTahun];
    }

    public function getAllDataSingrusForRekap()
    {
        $sql = "SELECT * FROM tiket WHERE status = 1 AND id_sub_kategori = 1 ORDER BY tanggal_input ASC";
        $statement = $this->db->query($sql);
        $statement->setFetchMode(Zend_Db::FETCH_OBJ);

        if ($result = $statement->fetchAll()) {
            return $result;
        } else {
            return null;
        }
    }

    public function getDataKeterangan($id_tiket)
    {
        $sql = "SELECT keterangan FROM tiket where id_tiket = ? ";
        $statement = $this->db->query($sql);
        $statement->setFetchMode(Zend_Db::FETCH_OBJ);
        if ($result = $statement->fetchAll()) {
            return $result;
        } else {
            return null;
        }
    }

    public function getAllDataByIdPelapor($id_pelapor)
    {
        $sql = "SELECT * FROM tiket WHERE id_pelapor = ? AND status = 1 ORDER BY id DESC";
        $statement = $this->db->prepare($sql);
        $statement->execute([$id_pelapor]);
        $statement->setFetchMode(Zend_Db::FETCH_OBJ);

        if ($result = $statement->fetchAll()) {
            return $result;
        } else {
            return null;
        }
    }

    public function addData(TiketAddDataRequest $request, $tanggal_log = null)
    {
        try {

            $this->validateEmpty($request->getKeterangan());

            $no_tiket = uniqid();
            $sql = "INSERT INTO tiket(id_sub_kategori, no_tiket, id_via, id_pelapor, id_urgensi, nama_pelapor,
                  bagian_pelapor, gedung_pelapor, unit_kerja_pelapor, ruangan_pelapor, lantai_pelapor, telepon_pelapor,
                  hp_pelapor, email_pelapor, keterangan, user_input, user_update, tanggal_input, tanggal_update,
                  reference_by, reference_to, id_status_tiket, id_status_tiket_internal, id_tim_programmer, id_aplikasi,
                  tanggal_pelaksanaan, status_revisi, keterangan_revisi)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $statement = $this->db->prepare($sql);
            $statement->execute([
                $request->getIdSubKategori(),
                $no_tiket,
                $request->getIdVia(),
                $request->getIdPelapor(),
                $request->getIdUrgensi(),
                $request->getNamaPelapor(),
                $request->getBagianPelapor(),
                $request->getGedungPelapor(),
                $request->getUnitKerjaPelapor(),
                $request->getRuanganPelapor(),
                $request->getLantaiPelapor(),
                $request->getTeleponPelapor(),
                $request->getHpPelapor(),
                $request->getEmailPelapor(),
                $request->getKeterangan(),
                $request->getUserInput(),
                $request->getUserUpdate(),
                ($tanggal_log) ? $tanggal_log : $this->tanggal_log,
                $this->tanggal_log,
                $request->getReferenceBy(),
                $request->getReferenceTo(),
                $request->getIdStatusTiket(),
                $request->getIdStatusTiketInternal(),
                $request->getIdTimProgrammer(),
                $request->getIdAplikasi(),
                $request->getTanggalPelaksanaan(),
                $request->getStatusRevisi(),
                $request->getKeteranganRevisi()

            ]);

            //Dapetin Last Insert Id
            $request->setId($this->tiket->getAdapter()->lastInsertId());
            $request->setNoTiket($no_tiket);

            return $request;
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function updateData(TiketUpdateDataRequest $request)
    {
        $sql = "UPDATE tiket SET id_aplikasi = ?, id_status_tiket = ?, id_status_tiket_internal = ?, id_sub_kategori = ?, 
                id_tim_programmer = ?, id_urgensi = ?, permasalahan_akhir = ?, solusi = ?, 
                tanggal_pelaksanaan = ?, rating = ?, status = ?, status_revisi = ?, keterangan_revisi = ?, 
                user_update = ?, tanggal_update = ?, reference_by = ?, reference_to = ?, id_via = ? WHERE id = ?";
        $statement = $this->db->prepare($sql);
        $statement->execute([
            $request->getIdAplikasi(),
            $request->getIdStatusTiket(),
            $request->getIdStatusTiketInternal(),
            $request->getidSubKategori(),
            $request->getIdTimProgrammer(),
            $request->getIdUrgensi(),
            $request->getPermasalahanAkhir(),
            $request->getSolusi(),
            $request->getTanggalPelaksanaan(),
            $request->getRating(),
            $request->getStatus(),
            $request->getStatusRevisi(),
            $request->getKeteranganRevisi(),
            $request->getUserUpdate(),
            $this->tanggal_log,
            $request->getReferenceBy(),
            $request->getReferenceTo(),
            $request->getIdVia(),
            $request->getId()
        ]);

        return $request;
    }

    public function softDelete($id, $userUpdate)
    {
        try {
            $data = $this->findById($id);

            $this->validateData($data);

            $data->setStatus(9);
            $data->setUserUpdate($userUpdate);

            $tiketUpdateDataRequest = $this->update($data);

            $this->updateData($tiketUpdateDataRequest);

            return "Data Berhasil Dihapus";
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function updateSubKategori(TiketUpdateSubKategoriRequest $request)
    {

        try {
            $data = $this->findById($request->getId());

            $this->validateData($data);

            $data->setId($request->getId());
            // update status tiket menjiad 1 = open
            $data->setIdStatusTiket(1);
            // updaet status tiket internal menjadi 1 = open
            $data->setIdStatusTiketInternal(1);
            $data->setIdUrgensi($request->getIdUrgensi());
            $data->setUserUpdate($request->getUserUpdate());
            $data->setIdSubKategori($request->getIdSubKategori());
            $data->setTanggalPelaksanaan($this->tanggal_log);

            $tiketUpdateDataRequest = $this->update($data);

            $result = $this->updateData($tiketUpdateDataRequest);

            return $result;
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function updateStatusTiket($id, $idStatus, $nama)
    {
        try {
            $data = $this->findById($id);

            $this->validateData($data);

            $data->setId($id);
            $data->setIdStatusTiket($idStatus);
            $data->setUserUpdate($nama);

            $tiketUpdateDataRequest = $this->update($data);

            $result = $this->updateData($tiketUpdateDataRequest);

            $tiket = $this->findById($id);

            //Masukkin ke log
            $request = new LogRequest();
            $request->setIdTiket($id);
            $request->setPengguna($nama);
            $request->setIdStatusTiketInternal($tiket->getIdStatusTiketInternal());
            $request->setIdStatusTiket($idStatus);
            $request->setKeterangan("Ubah status tiket menjadi {$this->statusTiketService->getData($idStatus)->status_tiket}");
            $this->log->addLogData($request);

            return $result;
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    // update status tiket internal
    public function updateStatusTiketInternal($id, $idStatus, $nama)
    {
        try {
            $data = $this->findById($id);

            $this->validateData($data);

            $data->setId($id);
            $data->setIdStatusTiketInternal($idStatus);
            $data->setUserUpdate($nama);

            $tiketUpdateDataRequest = $this->update($data);

            $result = $this->updateData($tiketUpdateDataRequest);

            $tiket = $this->findById($id);

            //Masukkin ke log
            $request = new LogRequest();
            $request->setIdTiket($id);
            $request->setPengguna($nama);
            $request->setIdStatusTiket($tiket->getIdStatusTiket());
            $request->setIdStatusTiketInternal($idStatus);
            $request->setKeterangan("Ubah status tiket internal menjadi {$this->statusTiketService->getData($idStatus)->status_tiket}");
            $this->log->addLogData($request);

            return $result;
        } catch (Exception $exception) {
            throw $exception;
        }
    }


    public function updateSelesai($id, $permasalahan_akhir, $solusi, $nama)
    {
        try {
            $data = $this->findById($id);

            $this->validateData($data);

            $data->setId($id);
            $data->setIdStatusTiket(6);
            $data->setIdStatusTiketInternal(6);
            $data->setPermasalahanAkhir($permasalahan_akhir);
            $data->setSolusi($solusi);
            $data->setUserUpdate($nama);

            $tiketUpdateDataRequest = $this->update($data);

            $result = $this->updateData($tiketUpdateDataRequest);

            return $result;
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function updateHelpdeskSelesai($id, $userUpdate, $permasalahanAkhir, $solusi)
    {
        try {
            $data = $this->findById($id);

            $this->validateData($data);

            $data->setId($id);
            $data->setUserUpdate($userUpdate);
            $data->setPermasalahanAkhir($permasalahanAkhir);
            $data->setSolusi($solusi);


            $tiketUpdateDataRequest = $this->update($data);

            $result = $this->updateData($tiketUpdateDataRequest);

            return $result;
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function updateHelpdeskTerkendala($id, $userUpdate, $permasalahanAkhir, $solusi)
    {
        try {
            $data = $this->findById($id);

            $this->validateData($data);

            $data->setId($id);
            $data->setUserUpdate($userUpdate);
            $data->setPermasalahanAkhir($permasalahanAkhir);
            $data->setSolusi($solusi);


            $tiketUpdateDataRequest = $this->update($data);

            $result = $this->updateData($tiketUpdateDataRequest);

            return $result;
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function updateOperatorVerifikasi($id, $userUpdate)
    {
        try {
            $data = $this->findById($id);

            $this->validateData($data);

            $data->setStatusRevisi('Terverifikasi');
            $data->setKeteranganRevisi(null);
            $data->setUserUpdate($userUpdate);

            $tiketUpdateDataRequest = $this->update($data);

            $result = $this->updateData($tiketUpdateDataRequest);

            return $result;
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function updateOperatorRevisi($id, $keteranganRevisi, $userUpdate)
    {
        try {
            $data = $this->findById($id);

            $this->validateData($data);

            $data->setKeteranganRevisi($keteranganRevisi);
            $data->setStatusRevisi('Dalam Revisi');
            $data->setUserUpdate($userUpdate);

            $tiketUpdateDataRequest = $this->update($data);

            $result = $this->updateData($tiketUpdateDataRequest);

            return $result;
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function updateUserSingrusRevisi($id, $userUpdate)
    {
        try {
            $data = $this->findById($id);

            $this->validateData($data);

            $data->setStatusRevisi('Sudah Revisi');
            $data->setUserUpdate($userUpdate);

            $tiketUpdateDataRequest = $this->update($data);

            $result = $this->updateData($tiketUpdateDataRequest);

            return $result;
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function updateLangsungSelesai(TiketUpdateLangsungSelesaiRequest $request)
    {

        try {
            $this->validateEmpty($request->getPermasalahanAkhir());
            $this->validateEmpty($request->getSolusi());

            $data = $this->findById($request->getId());

            $this->validateData($data);

            $data->setTanggalPelaksanaan($this->tanggal_log);
            $data->setId($request->getId());
            $data->setIdSubKategori($request->getIdSubKategori());
            $data->setIdUrgensi($request->getIdUrgensi());
            $data->setUserUpdate($request->getUserUpdate());
            $data->setSolusi($request->getSolusi());
            $data->setPermasalahanAkhir($request->getPermasalahanAkhir());
            $data->setIdStatusTiket(6);

            $tiketUpdateDataRequest = $this->update($data);

            $result = $this->updateData($tiketUpdateDataRequest);

            return $result;
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function updateIdTimProgrammer(TiketUpdateIdTimProgrammerRequest $request)
    {
        try {
            $data = $this->findById($request->getId());

            $this->validateData($data);

            $data->setId($request->getId());
            $data->setUserUpdate($request->getUserUpdate());
            $data->setIdTimProgrammer($request->getIdTimProgrammer());

            $tiketUpdateDataRequest = $this->update($data);

            $result = $this->updateData($tiketUpdateDataRequest);

            return $result;
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function updateIdAplikasi($id_tiket, $id_aplikasi, $user_log)
    {
        try {
            $data = $this->findById($id_tiket);

            $this->validateData($data);

            $data->setId($id_tiket);
            $data->setIdAplikasi($id_aplikasi);
            $data->setUserUpdate($user_log);

            $tiketUpdateDataRequest = $this->update($data);

            $result = $this->updateData($tiketUpdateDataRequest);

            return $result;
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function updateSelesaiProgrammer($id_tiket, $user_log)
    {
        try {
            $data = $this->findById($id_tiket);

            $this->validateData($data);

            $data->setId($id_tiket);
            $data->setIdStatusTiket(6);
            $data->setIdStatusTiketInternal(6);
            $data->setUserUpdate($user_log);

            $tiketUpdateDataRequest = $this->update($data);

            $result = $this->updateData($tiketUpdateDataRequest);

            return $result;
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function updateRevisiServiceDesk(TiketUpdateRevisiServiceDeskRequest $request)
    {
        try {
            $data = $this->findById($request->getId());

            $this->validateData($data);

            $data->setId($request->getId());
            $data->setIdVia($request->getIdVia());
            $data->setIdSubKategori($request->getIdSubKategori());
            $data->setIdUrgensi($request->getIdUrgensi());
            $data->setUserUpdate($request->getUserUpdate());

            $tiketUpdateDataRequest = $this->update($data);

            $result = $this->updateData($tiketUpdateDataRequest);

            return $result;
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function update(TiketFindByResponse $data)
    {
        $tiketUpdateDataRequest = new TiketUpdateDataRequest();

        $tiketUpdateDataRequest->setIdAplikasi($data->getIdAplikasi());
        $tiketUpdateDataRequest->setIdStatusTiket($data->getIdStatusTiket());
        $tiketUpdateDataRequest->setIdStatusTiketInternal($data->getIdStatusTiketInternal());
        $tiketUpdateDataRequest->setIdSubKategori($data->getIdSubKategori());
        $tiketUpdateDataRequest->setIdTimProgrammer($data->getIdTimProgrammer());
        $tiketUpdateDataRequest->setIdUrgensi($data->getIdUrgensi());
        $tiketUpdateDataRequest->setIdVia($data->getIdVia());
        $tiketUpdateDataRequest->setSolusi($data->getSolusi());
        $tiketUpdateDataRequest->setPermasalahanAkhir($data->getPermasalahanAkhir());
        $tiketUpdateDataRequest->setSolusi($data->getSolusi());
        $tiketUpdateDataRequest->setTanggalPelaksanaan($data->getTanggalPelaksanaan());
        $tiketUpdateDataRequest->setRating($data->getRating());
        $tiketUpdateDataRequest->setStatus($data->getStatus());
        $tiketUpdateDataRequest->setStatusRevisi($data->getStatusRevisi());
        $tiketUpdateDataRequest->setKeteranganRevisi($data->getKeteranganRevisi());
        $tiketUpdateDataRequest->setUserUpdate($data->getUserUpdate());
        $tiketUpdateDataRequest->setId($data->getId());
        $tiketUpdateDataRequest->setReferenceTo($data->getReferenceTo());
        $tiketUpdateDataRequest->setReferenceBy($data->getReferenceBy());

        return $tiketUpdateDataRequest;
    }

    private function validateData(TiketFindByResponse $data)
    {
        if ($data == null) {
            throw new Exception("Data Tidak Ditemukan");
        }
    }

    private function validateEmpty($value)
    {
        $validator = new Zend_Validate_NotEmpty(
            [Zend_Validate_NotEmpty::INTEGER +
                Zend_validate_NotEmpty::ZERO +
                Zend_Validate_NotEmpty::STRING]
        );
        if (!$validator->isValid($value)) {
            throw new Exception("Tidak boleh kosong");
        }
    }

    // recap jumlah tiket by status tiket
    public function recapByStatus()
    {
        $today = Carbon::now();
        $allTiket = $this->getAllData();
        $allStatus = $this->statusTiketService->getAllData();
        $data = [];
        // iterate all status
        foreach ($allStatus as $status) {
            // initiate data
            $data[$status->status_tiket] = [
                'today' => 0,
                'month' => 0,
                'year' => 0,
            ];
            if ($allTiket)
                foreach ($allTiket as $tiket) {
                    if ($tiket->id_status_tiket == $status->id || $tiket->id_status_tiket_internal == $status->id) {
                        // today
                        if (Carbon::parse($tiket->tanggal_input)->toDateString() == $today->toDateString())
                            $data[$status->status_tiket]['today']++;

                        // month
                        if (Carbon::parse($tiket->tanggal_input)->format("Y-m") == $today->format("Y-m"))
                            $data[$status->status_tiket]['month']++;

                        // year
                        if (Carbon::parse($tiket->tangal_input)->year == $today->year)
                            $data[$status->status_tiket]['year']++;
                    }
                }
        }
        return $data;
    }

    // recap jumlah tiket by kategori
    public function recapByKategori()
    {
        $today = Carbon::now();
        $allTiket = $this->getAllData();
        $allKategori = $this->subKategoriService->getAllData();
        $data = [];
        // iterate all status
        foreach ($allKategori as $kategori) {
            // initiate data
            $data[$kategori->sub_kategori] = [
                'today' => 0,
                'month' => 0,
                'year' => 0,
            ];
            if ($allTiket)
                foreach ($allTiket as $tiket) {
                    if ($tiket->id_sub_kategori == $kategori->id) {
                        // today
                        if (Carbon::parse($tiket->tanggal_input)->toDateString() == $today->toDateString())
                            $data[$kategori->sub_kategori]['today']++;

                        // month
                        if (Carbon::parse($tiket->tanggal_input)->format("Y-m") == $today->format("Y-m"))
                            $data[$kategori->sub_kategori]['month']++;

                        // year
                        if (Carbon::parse($tiket->tangal_input)->year == $today->year)
                            $data[$kategori->sub_kategori]['year']++;
                    }
                }
        }
        return $data;
    }

    // recap jumlah tiket by urgensi
    public function recapByUrgensi()
    {
        $today = Carbon::now();
        $allTiket = $this->getAllData();
        $allUrgensi = $this->urgensiService->getAllData();
        $data = [];
        // iterate all status
        foreach ($allUrgensi as $urgensi) {
            // initiate data
            $data[$urgensi->nama] = [
                'today' => 0,
                'month' => 0,
                'year' => 0,
            ];
            if ($allTiket)
                foreach ($allTiket as $tiket) {
                    if ($tiket->id_urgensi == $urgensi->id) {
                        // today
                        if (Carbon::parse($tiket->tanggal_input)->toDateString() == $today->toDateString())
                            $data[$urgensi->nama]['today']++;

                        // month
                        if (Carbon::parse($tiket->tanggal_input)->format("Y-m") == $today->format("Y-m"))
                            $data[$urgensi->nama]['month']++;

                        // year
                        if (Carbon::parse($tiket->tangal_input)->year == $today->year)
                            $data[$urgensi->nama]['year']++;
                    }
                }
        }
        return $data;
    }

    // recap jumlah tiket by via
    public function recapByVia()
    {
        $today = Carbon::now();
        $allTiket = $this->getAllData();
        $allVia = $this->viaService->getAllData();
        $data = [];
        // iterate all status
        foreach ($allVia as $via) {
            // initiate data
            $data[$via->via] = [
                'today' => 0,
                'month' => 0,
                'year' => 0,
            ];
            if ($allTiket)
                foreach ($allTiket as $tiket) {
                    if ($tiket->id_via == $via->id) {
                        // today
                        if (Carbon::parse($tiket->tanggal_input)->toDateString() == $today->toDateString())
                            $data[$via->via]['today']++;

                        // month
                        if (Carbon::parse($tiket->tanggal_input)->format("Y-m") == $today->format("Y-m"))
                            $data[$via->via]['month']++;

                        // year
                        if (Carbon::parse($tiket->tangal_input)->year == $today->year)
                            $data[$via->via]['year']++;
                    }
                }
        }
        return $data;
    }

    // recap jumlah tiket harian di minggu ini
    function recapCurrentWeek()
    {
        $endOfWeek = Carbon::now()->endOfWeek();
        $startOfWeek = Carbon::now()->startOfWeek();
        $data = [
            'from' => $startOfWeek->toDateString(),
            'until' => $endOfWeek->toDateString(),
            'total_tiket' => 0,
            'data' => [],
        ];
        $allTiket = $this->getAllData();
        // iterate from start date until end of current week
        while ($startOfWeek->toDateString() <= $endOfWeek->toDateString()) {
            $tmp = [
                'date' => $startOfWeek->toDateString(),
                'jumlah_tiket' => 0,
            ];
            if ($allTiket)
                foreach ($allTiket as $tiket) {
                    if (Carbon::parse($tiket->tanggal_input)->toDateString() == $startOfWeek->toDateString()) {
                        $tmp['jumlah_tiket']++;
                        $data['total_tiket']++;
                    }
                }
            array_push($data['data'], $tmp);
            $startOfWeek->addDay(1);
        }
        return $data;
    }

    // recap jumlah tiket mingguan di bulan ini
    function recapCurrentMonth()
    {
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();
        $numOfWeek = $startDate->diffInWeeks($endDate);
        $currentWeek = 0;
        $data = [
            'from' => $startDate->toDateString(),
            'until' => $endDate->toDateString(),
            'total_tiket' => 0,
            'data' => [],
        ];

        // get All tiket data
        $allTiket = $this->getAllData();
        while ($currentWeek <= $numOfWeek) {
            $tmp = [
                'from' => $startDate->toDateString(),
                'until' => $startDate->endOfWeek()->toDateString() <= $endDate->toDateString() ? $startDate->endOfWeek()->toDateString() : $endDate->toDateString(),
                'jumlah_tiket' => 0,
            ];
            if ($allTiket)
                foreach ($allTiket as $tiket) {
                    if (Carbon::parse($tiket->tanggal_input)->toDateString() >= $tmp['from'] && Carbon::parse($tiket->tanggal_input)->toDateString() <= $tmp['until']) {
                        $tmp['jumlah_tiket']++;
                        $data['total_tiket']++;
                    }
                }
            array_push($data['data'], $tmp);
            $startDate = $startDate->endOfWeek()->addDay(1);
            $currentWeek++;
        }
        return $data;
    }

    // recap jumlah tiket perbulan periode 1 tahun
    function recapCurrentYear()
    {
        $startMonth = Carbon::now()->startOfYear();
        $data = [
            'from' => $startMonth->toDateString(),
            'until' => Carbon::now()->endOfYear()->toDateString(),
            'total_tiket' => 0,
            'data' => [],
        ];
        // get all tiket
        $allTiket = $this->getAllData();
        // iterate dari bulan pertama sampe bulan terakhir
        for ($i = 0; $i < 12; $i++) {
            $tmp = [
                'date' => $startMonth->format("Y-m"),
                'jumlah_tiket' => 0,
            ];
            // iterate tiket
            if ($allTiket)
                foreach ($allTiket as $tiket) {
                    if (Carbon::parse($tiket->tanggal_input)->format("Y-m") == $startMonth->format("Y-m")) {
                        $tmp['jumlah_tiket']++;
                        $data['total_tiket']++;
                    }
                }

            array_push($data['data'], $tmp);
            $startMonth->addMonth();
        }
        return $data;
    }

    public function rekapTiapTahun()
    {
        $tikets = $this->getAllData();

        $subKategori = [
            'Singa Rusia' => 0,
            'Internet & Jaringan' => 0,
            'Sistem Operasi' => 0,
            'Software' => 0,
            'Hardware' => 0,
            'Aplikasi' => 0,
            'Pengembangan Jaringan' => 0,
            'Lain-Lain' => 0,
            'Teleconference' => 0,
            'File Sharing dan Cloud' => 0,
            'Undangan Rapat' => 0,
            'Masuk Ruang Server' => 0,
            'Insiden' => 0,
        ];

        $listTahun[1] = 'All';
        $d['All']['tahun'] = 'All';
        $d['All']['tiket'] = 0;
        $d['All']['sub_kategori'] = $subKategori;

        $tahunTmp = '';
        $counter = 0;
        if ($tikets) {
            foreach ($tikets as $tiket) {
                $namaSubKategori = ($tiket->id_sub_kategori) ?
                    $this->subKategoriService->getData($tiket->id_sub_kategori)->sub_kategori : null;

                $tahun = explode('-', explode(' ', $tiket->tanggal_input)[0])[0];
                if ($tahun != $tahunTmp) {
                    $counter++;
                    $listTahun[$counter + 1] = $tahun;
                    $d[$tahun]['tahun'] = $tahun;
                    $d[$tahun]['tiket'] = 0;
                    $d[$tahun]['sub_kategori'] = $subKategori;
                }

                if ($namaSubKategori) {
                    $d[$tahun]['sub_kategori'][$namaSubKategori] += 1;
                    $d['All']['sub_kategori'][$namaSubKategori] += 1;
                }
                $d['All']['tiket'] += 1;
                $d[$tahun]['tiket'] += 1;
                $tahunTmp = $tahun;
            }
        }
        return [$d, $listTahun];
    }

    public function rekapTiapBulan()
    {
        $tikets = $this->getAllData();

        $bulanArray = [
            'Januari' => 0,
            'Februari' => 0,
            'Maret' => 0,
            'April' => 0,
            'Mei' => 0,
            'Juni' => 0,
            'Juli' => 0,
            'Agustus' => 0,
            'September' => 0,
            'Oktober' => 0,
            'November' => 0,
            'Desember' => 0
        ];

        $subKategori = [
            'Singa Rusia' => $bulanArray,
            'Internet & Jaringan' => $bulanArray,
            'Sistem Operasi' => $bulanArray,
            'Software' => $bulanArray,
            'Hardware' => $bulanArray,
            'Aplikasi' => $bulanArray,
            'Pengembangan Jaringan' => $bulanArray,
            'Lain-Lain' => $bulanArray,
            'Teleconference' => $bulanArray,
            'File Sharing dan Cloud' => $bulanArray,
            'Undangan Rapat' => $bulanArray,
            'Masuk Ruang Server' => $bulanArray,
            'Insiden' => $bulanArray
        ];

        $listTahun[1] = 'All';

        $d['All']['tahun'] = 'All';
        $d['All']['tiket'] = $bulanArray;
        $d['All']['sub_kategori'] = $subKategori;

        $counter = 0;
        $tahunTmp = '';
        if ($tikets) {
            foreach ($tikets as $tiket) {
                $namaSubKategori = ($tiket->id_sub_kategori) ?
                    $this->subKategoriService->getData($tiket->id_sub_kategori)->sub_kategori : null;

                $tahun = explode('-', explode(' ', $tiket->tanggal_input)[0])[0];
                $bulan = explode(' ', $this->dateFormat->direct($tiket->tanggal_input)[1])[1];
                if ($tahun != $tahunTmp) {
                    $counter++;
                    $listTahun[$counter + 1] = $tahun;
                    $d[$tahun]['tahun'] = $tahun;
                    $d[$tahun]['tiket'] = $bulanArray;
                    $d[$tahun]['sub_kategori'] = $subKategori;
                }

                $d['All']['tiket'][$bulan] += 1;
                $d['All']['sub_kategori'][$namaSubKategori][$bulan] += 1;
                $d[$tahun]['tiket'][$bulan] += 1;
                $d[$tahun]['sub_kategori'][$namaSubKategori][$bulan] += 1;

                $tahunTmp = $tahun;
            }
        }
        return [$listTahun, $d];
    }

    public function rekapTiapHari()
    {
        $tikets = $this->getAllData();

        $hariArray = [
            'Senin' => 0,
            'Selasa' => 0,
            'Rabu' => 0,
            'Kamis' => 0,
            "Jum'at" => 0,
            'Sabtu' => 0,
            'Minggu' => 0
        ];

        $bulanArray = [
            'Januari' => $hariArray,
            'Februari' => $hariArray,
            'Maret' => $hariArray,
            'April' => $hariArray,
            'Mei' => $hariArray,
            'Juni' => $hariArray,
            'Juli' => $hariArray,
            'Agustus' => $hariArray,
            'September' => $hariArray,
            'Oktober' => $hariArray,
            'November' => $hariArray,
            'Desember' => $hariArray
        ];

        $subKategori = [
            'Singa Rusia' => $bulanArray,
            'Internet & Jaringan' => $bulanArray,
            'Sistem Operasi' => $bulanArray,
            'Software' => $bulanArray,
            'Hardware' => $bulanArray,
            'Aplikasi' => $bulanArray,
            'Pengembangan Jaringan' => $bulanArray,
            'Lain-Lain' => $bulanArray,
            'Teleconference' => $bulanArray,
            'File Sharing dan Cloud' => $bulanArray,
            'Undangan Rapat' => $bulanArray,
            'Masuk Ruang Server' => $bulanArray,
            'Insiden' => $bulanArray
        ];

        $listTahun[1] = 'All';

        $d['All']['tahun'] = 'All';
        $d['All']['tiket'] = $bulanArray;
        $d['All']['sub_kategori'] = $subKategori;
        $counter = 1;
        foreach ($bulanArray as $key => $value) {
            $listBulan[$counter] = $key;
            $counter++;
        }

        $counter2 = 0;
        $tahunTmp = '';
        foreach ($tikets as $tiket) {
            $namaSubKategori = ($tiket->id_sub_kategori) ?
                $this->subKategoriService->getData($tiket->id_sub_kategori)->sub_kategori : null;

            $tanggalFull = explode(' ', $this->dateFormat->direct($tiket->tanggal_input)[1]);
            $hari = $tanggalFull[0];
            $bulan = $tanggalFull[1];
            $tahun = $tanggalFull[2];

            if ($tahun != $tahunTmp) {
                $counter2++;
                $listTahun[$counter2 + 1] = $tahun;
                $d[$tahun]['tahun'] = $tahun;
                $d[$tahun]['tiket'] = $bulanArray;
                $d[$tahun]['sub_kategori'] = $subKategori;
            }

            $d['All']['tiket'][$bulan][$hari] += 1;
            $d['All']['sub_kategori'][$namaSubKategori][$bulan][$hari] += 1;
            $d[$tahun]['tiket'][$bulan][$hari] += 1;
            $d[$tahun]['sub_kategori'][$namaSubKategori][$bulan][$hari] += 1;

            $tahunTmp = $tahun;
        }

        return [$listTahun, $listBulan, $d];
    }

    public function getRekapPengerjaan(){
        $tikets = $this->getAllData();

        $counter = 1;
        if ($tikets) {
            foreach ($tikets as $tiket) {
                $tanggalMulai = new DateTime($tiket->tanggal_input);
                $tanggalSelesai = ($tiket->id_status_tiket == 6) ? new DateTime($tiket->tanggal_update) : 0;

                if ($tanggalSelesai != 0) {
                    $difference = $tanggalSelesai->diff($tanggalMulai);
                } else {
                    $difference = null;
                }

                if ($difference->days) {
                    $lamaPengerjaan = $difference->h ? "{$difference->days} hari {$difference->h} jam" : "{$difference->days} hari";
                } elseif ($difference->h) {
                    $lamaPengerjaan = $difference->i ? "{$difference->h} jam {$difference->i} menit" : "{$difference->h} jam";
                } elseif ($difference->i) {
                    $lamaPengerjaan = $difference->s ? "{$difference->i} menit {$difference->s} detik" : "{$difference->i} menit";
                } elseif ($difference->s) {
                    $lamaPengerjaan = "{$difference->s} detik";
                } else {
                    $lamaPengerjaan = "Belum Selesai";
                }

                $data[$counter] = [
                    'id' => $tiket->id,
                    'no_tiket' => $tiket->no_tiket,
                    'tanggal_mulai' => $tanggalMulai->format('Y-m-d H:i:s'),
                    'tanggal_selesai' => $tanggalSelesai ? $tanggalSelesai->format('Y-m-d H:i:s') : 'Belum Selesai',
                    'lama_pengerjaan' => $lamaPengerjaan
                ];

                $counter++;
            }
        }

        return $data;
    }
}
