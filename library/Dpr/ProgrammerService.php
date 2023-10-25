<?php

require_once APPLICATION_PATH . '/dto/Programmer/ProgrammerUpdateRequest.php';
require_once APPLICATION_PATH . '/dto/Programmer/ProgrammerRequest.php';
require_once APPLICATION_PATH . '/dto/Programmer/ProgrammerResponse.php';


class Dpr_ProgrammerService
{

    protected $db;
    protected $programmer;
    protected $tanggal_log;

    function __construct()
    {
        $this->programmerExternal = new Dpr_ProgrammerExternalService();
        $this->programmer = new Programmer();
        $this->tiket = new Dpr_TiketService();
        $this->pengguna = new Dpr_PenggunaService();
        $this->peran = new Dpr_PeranService();
        $this->listPeran = new Dpr_ListPeranService();
        $this->aplikasi = new Dpr_AplikasiService();
        $this->tahapanService = new Dpr_TahapanService();
        $this->dateFormat = new Dpr_Controller_Action_Helper_YmdToIndo();
        $this->db = Zend_Registry::get('db_stela');
        $this->tanggal_log = date('Y-m-d H:i:s');
    }

    public function getAllDataByIdTImProgrammer($id_tim_programmer)
    {
        $sql = "SELECT * FROM programmer WHERE id_tim_programmer = ?";
        $statement = $this->db->prepare($sql);
        $statement->execute([$id_tim_programmer]);
        $statement->setFetchMode(Zend_Db::FETCH_OBJ);

        try {
            if ($result = $statement->fetchAll()) {
                return $result;
            } else {
                return null;
            }
        } finally {
            $statement->closecursor();
        }
    }

    public function getAllDataByIdPengguna($id_pengguna)
    {
        try {
            $sql = "SELECT * FROM programmer WHERE id_pengguna = ?";
            $statement = $this->db->prepare($sql);
            $statement->execute([$id_pengguna]);
            $statement->setFetchMode(Zend_Db::FETCH_OBJ);

            if ($result = $statement->fetchAll()) {
                return $result;
            } else {
                return null;
            }
        } finally {
            $statement->closecursor();
        }
    }

    public function addDataProgrammer(ProgrammerRequest $request)
    {
        $sql = "INSERT INTO programmer(jabatan, id_tim_programmer, id_pengguna) VALUES (?,?,?)";
        $statement = $this->db->prepare($sql);
        $statement->execute([
            $request->getJabatan(),
            $request->getIdTimProgrammer(),
            $request->getIdPengguna()
        ]);
        $request->setId($this->programmer->getAdapter()->lastInsertId());
        $statement->closecursor();

        return $request;
    }

    public function addMultipleProgrammer($id_tim_programmer, $id_programmer)
    {
        $status = false;

        $datas = $this->getAllDataByIdTImProgrammer($id_tim_programmer);

        foreach ($datas as $data) {
            if ($data->id_pengguna == $id_programmer) {
                $status = true;
            }
        }

        if ($status == false) {
            $request = new ProgrammerRequest();

            $request->setIdPengguna($id_programmer);
            $request->setIdTimProgrammer($id_tim_programmer);
            $request->setJabatan('programmer');

            $this->addDataProgrammer($request);
        }
    }

    public function updateDataProgrammer(ProgrammerUpdateRequest $request)
    {
        $sql = "UPDATE programmer SET jabatan = ?, id_tim_programmer = ?, id_pengguna = ? WHERE id = ?";
        $statement = $this->db->prepare($sql);
        $statement->execute([
            $request->getJabatan(),
            $request->getIdTimProgrammer(),
            $request->getIdPengguna(),
            $request->getId()
        ]);
        $statement->closecursor();
        return $request;
    }

    public function editLeaderProgrammer($id_programmer, $id_pengguna)
    {
        try {
            $data = $this->findDataProgrammerById($id_programmer);

            if ($data == null) {
                throw new Exception("Data Tidak DItemukan");
            }

            $data->setIdPengguna($id_pengguna);

            $request = new ProgrammerUpdateRequest();
            $request->setId($data->getId());
            $request->setIdPengguna($data->getIdPengguna());
            $request->setJabatan($data->getJabatan());
            $request->setIdTimProgrammer($data->getIdTimProgrammer());

            $this->updateDataProgrammer($request);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function deleteDataProgrammer($id)
    {
        $sql = "DELETE FROM programmer WHERE id = ?";
        $statement = $this->db->prepare($sql);
        $statement->execute([$id]);
        return "Data Berhasil dihapus";
    }

    public function getAllDataProgrammer()
    {
        $sql = "SELECT * FROM programmer";
        $statement = $this->db->query($sql);
        $statement->setFetchMode(Zend_Db::FETCH_OBJ);

        try {
            if ($result = $statement->fetchAll()) {
                return $result;
            } else {
                return null;
            }
        } finally {
            $statement->closecursor();
        }
    }

    public function findDataProgrammerById($id)
    {

        $sql = "SELECT id, jabatan, id_tim_programmer, id_pengguna 
                FROM programmer WHERE id = ?";
        $statement = $this->db->prepare($sql);
        $statement->execute([$id]);
        $statement->setFetchMode(Zend_Db::FETCH_OBJ);

        try {
            if ($result = $statement->fetch()) {
                $response = new ProgrammerResponse();

                $response->setJabatan($result->jabatan);
                $response->setIdTimProgrammer($result->id_tim_programmer);
                $response->setIdPengguna($result->id_pengguna);
                $response->setId($result->id);

                return $response;
            } else {
                return null;
            }
        } finally {
            $statement->closecursor();
        }

    }

    public function findByIdTimAndIdPengguna($id_tim_programmer, $id_pengguna)
    {
        try {
            $sql = "SELECT * FROM programmer WHERE id_tim_programmer = ? AND id_pengguna = ?";
            $statement = $this->db->prepare($sql);
            $statement->execute([
                $id_tim_programmer,
                $id_pengguna
            ]);
            $statement->setFetchMode(Zend_Db::FETCH_OBJ);

            if ($result = $statement->fetch()) {
                return $result;
            } else {
                return null;
            }
        } finally {
            $statement->closecursor();
        }
    }

    public function getLeaderProgrammerByIdTim($id_tim)
    {
        $sql = "SELECT * FROM programmer WHERE id_tim_programmer = ? AND jabatan = 'leader'";
        $statement = $this->db->prepare($sql);
        $statement->execute([$id_tim]);
        $statement->setFetchMode(Zend_Db::FETCH_OBJ);

        try {
            if ($result = $statement->fetch()) {
                return $result;
            } else {
                return null;
            }
        } finally {
            $statement->closecursor();
        }
    }

    public function getLeaderProrgrammer($id_tiket)
    {
        $tiket = $this->tiket->findById($id_tiket);

        $programmer = $this->getAllDataByIdTImProgrammer($tiket->getIdTimProgrammer());

        if ($programmer) {
            foreach ($programmer as $pro) {
                if ($pro->jabatan == "leader") {
                    $result = $this->pengguna->findById($pro->id_pengguna)->getNamaLengkap();
                }
            }
        }

        return isset($result) ? $result : null;

    }

    public function getAllProgrammerByIdTimProgrammer($id_tim_programmer){

        $data = $this->getAllDataByIdTImProgrammer($id_tim_programmer);

        $counter = 1;
        foreach ($data as $dat){
            if ($dat->jabatan == 'programmer'){
                $result[$counter] = [
                    'id_pengguna' => $dat->id_pengguna,
                    'jabatan' => $dat->jabatan
                ];
                $counter++;
            }
        }

        return $result;
    }

    public function getAllProgrammer()
    {
        $pengguna = $this->pengguna->getAllData();

        $counter = 1;
        foreach ($pengguna as $guna) {
            if ($this->programmerExternal->findByIdPengguna($guna->id)) continue;

            $listPeran = $this->peran->getAllDataByIdPengguna($guna->id);

            if (is_array($listPeran)) {
                foreach ($listPeran as $peran) {
                    if ($peran->id_peran == 3) {
                        $result[$counter] = [
                            'id' => $guna->id,
                            'nama' => $this->pengguna->findById($guna->id)->getNamaLengkap()
                        ];
                        $counter++;
                    }
                }
            }
        }

        return isset($result) ? $result : null;
    }

    public function getAllProgrammerForAssignedProgrammer()
    {
        $pengguna = $this->pengguna->getAllData();

        $counter = 1;
        foreach ($pengguna as $guna) {
            if ($this->programmerExternal->findByIdPengguna($guna->id)) continue;
            if ($guna->id == Zend_Auth::getInstance()->getIdentity()->id) continue;

            $listPeran = $this->peran->getAllDataByIdPengguna($guna->id);

            if (is_array($listPeran)) {
                foreach ($listPeran as $peran) {
                    if ($peran->id_peran == 3) {
                        $result[$counter] = [
                            'id' => $guna->id,
                            'nama' => $this->pengguna->findById($guna->id)->getNamaLengkap()
                        ];
                        $counter++;
                    }
                }
            }
        }

        return isset($result) ? $result : null;
    }

    public function getPeranByIdPenggunaAndIdTimProgrammer($id_pengguna, $id_tim_programmer)
    {
        $sql = "SELECT * FROM programmer WHERE id_pengguna = ? AND id_tim_programmer = ?";
        $statement = $this->db->prepare($sql);
        $statement->execute([$id_pengguna, $id_tim_programmer]);
        $statement->setFetchMode(Zend_Db::FETCH_OBJ);

        if ($result = $statement->fetch()) {
            return $result->jabatan;
        } else {
            return null;
        }
    }

    public function checkDeveloper($id_pengguna)
    {
        $pengguna = $this->programmerExternal->findByIdPengguna($id_pengguna);

        if ($pengguna) {
            return 'External';
        } else {
            return 'Internal';
        }
    }

    public function getRekap($datas)
    {

        $internal = [
            'permohonan' => 0,
            'perencanaan' => 0,
            'perancangan' => 0,
            'implementasi' => 0,
            'pengujian' => 0,
            'serah terima' => 0
        ];

        $external = [
            'permohonan' => 0,
            'perencanaan' => 0,
            'perancangan' => 0,
            'implementasi' => 0,
            'pengujian' => 0,
            'serah terima' => 0
        ];
        $listTahun[1] = 'All';

        $d['All']['tahun'] = 'All';
        $d['All']['internal'] = $internal;
        $d['All']['external'] = $external;

        $counter = 0;
        $tahunTemp = '';
        foreach ($datas as $data) {
            $aplikasi = ($data->id_aplikasi) ? $this->aplikasi->findById($data->id_aplikasi) : null;
            $tiket = $this->tiket->findById($data->id);
            $tahapan = $this->tahapanService->getTahapan($tiket);
            $tahap = $tahapan[0];
            $tahap = $tahap[count($tahap)]['tahapan'];

            if ($aplikasi) {
                $tahun = explode('-',
                    explode(' ',
                        $this->dateFormat->directAngkaFUll($tiket->getTanggalInput()))
                    [1]
                )[2];

                if ($tahun != $tahunTemp) {
                    $counter++;
                    $listTahun[$counter + 1] = $tahun;
                    $d[$tahun]['tahun'] = $tahun;
                    $d[$tahun]['internal'] = $internal;
                    $d[$tahun]['external'] = $external;
                }

                if ($aplikasi->getDeveloper() == 'Internal') {
                    $d[$tahun]['internal'][strtolower($tahap)] += 1;
                    $d['All']['internal'][strtolower($tahap)] += 1;
                } else {
                    $d[$tahun]['external'][strtolower($tahap)] += 1;
                    $d['All']['external'][strtolower($tahap)] += 1;
                }

                $tahunTemp = $tahun;
            }
        }

        return [$d, $listTahun];
    }

    public function getListInternalByTahapan($tahapan_input, $tahun_input)
    {
        $tikets = $this->tiket->getAllDataSingrusForRekap();

        $counter = 1;
        foreach ($tikets as $tiket) {
            $tiket = $this->tiket->findById($tiket->id);

            if ($tiket->getIdAplikasi()) {
                $aplikasi = $this->aplikasi->findById($tiket->getIdAplikasi());
                if ($aplikasi && $aplikasi->getDeveloper() == 'Internal') {
                    $tahapan = $this->tahapanService->getTahapan($tiket);
                    $tahap = $tahapan[0];
                    $tahun = explode('-',
                        explode(' ',
                            $this->dateFormat->directAngkaFUll($tiket->getTanggalInput()))
                        [1]
                    )[2];

                    if ($tahun_input == 'All') {
                        if (strtolower($tahap[count($tahap)]['tahapan']) == $tahapan_input) {
                            $data[$counter] = [
                                'id_tiket' => $tiket->getId(),
                                'no_tiket' => $tiket->getNoTiket(),
                                'keterangan' => $tiket->getKeterangan(),
                                'tanggal' => explode(' ',$tiket->getTanggalInput())[0],
                                'jam' => explode(' ',$tiket->getTanggalInput())[1],
                                'aplikasi' => $aplikasi->getAplikasi(),
                                'jenis_aplikasi' => $aplikasi->getJenisAplikasi(),
                                'leader_programmer' => ($this->getLeaderProrgrammer($tiket->getId())) ?
                                    $this->getLeaderProrgrammer($tiket->getId()) : null
                            ];
                            $counter++;
                        }
                    } else {
                        if (strtolower($tahap[count($tahap)]['tahapan']) == $tahapan_input && $tahun == $tahun_input) {
                            $data[$counter] = [
                                'id_tiket' => $tiket->getId(),
                                'no_tiket' => $tiket->getNoTiket(),
                                'keterangan' => $tiket->getKeterangan(),
                                'tanggal' => explode(' ',$tiket->getTanggalInput())[0],
                                'jam' => explode(' ',$tiket->getTanggalInput())[1],
                                'aplikasi' => $aplikasi->getAplikasi(),
                                'jenis_aplikasi' => $aplikasi->getJenisAplikasi(),
                                'leader_programmer' => ($this->getLeaderProrgrammer($tiket->getId())) ?
                                    $this->getLeaderProrgrammer($tiket->getId()) : null
                            ];
                            $counter++;
                        }
                    }

                }
            }
        }

        return $data;
    }

    public function getListExternalByTahapan($tahapan_input, $tahun_input)
    {
        $tikets = $this->tiket->getAllDataSingrusForRekap();

        $counter = 1;
        foreach ($tikets as $tiket) {
            $tiket = $this->tiket->findById($tiket->id);

            if ($tiket->getIdAplikasi()) {
                $aplikasi = $this->aplikasi->findById($tiket->getIdAplikasi());
                if ($aplikasi && $aplikasi->getDeveloper() == 'External') {
                    $tahapan = $this->tahapanService->getTahapan($tiket);
                    $tahap = $tahapan[0];
                    $tahun = explode('-',
                        explode(' ',
                            $this->dateFormat->directAngkaFUll($tiket->getTanggalInput()))
                        [1]
                    )[2];

                    if ($tahun_input == 'All') {
                        if (strtolower($tahap[count($tahap)]['tahapan']) == $tahapan_input) {
                            $data[$counter] = [
                                'id_tiket' => $tiket->getId(),
                                'no_tiket' => $tiket->getNoTiket(),
                                'keterangan' => $tiket->getKeterangan(),
                                'tanggal' => explode(' ',$tiket->getTanggalInput())[0],
                                'jam' => explode(' ',$tiket->getTanggalInput())[1],
                                'aplikasi' => $aplikasi->getAplikasi(),
                                'jenis_aplikasi' => $aplikasi->getJenisAplikasi(),
                                'leader_programmer' => ($this->getLeaderProrgrammer($tiket->getId())) ?
                                    $this->getLeaderProrgrammer($tiket->getId()) : null
                            ];
                            $counter++;
                        }
                    } else {
                        if (strtolower($tahap[count($tahap)]['tahapan']) == $tahapan_input && $tahun == $tahun_input) {
                            $data[$counter] = [
                                'id_tiket' => $tiket->getId(),
                                'no_tiket' => $tiket->getNoTiket(),
                                'keterangan' => $tiket->getKeterangan(),
                                'tanggal' => explode(' ',$tiket->getTanggalInput())[0],
                                'jam' => explode(' ',$tiket->getTanggalInput())[1],
                                'aplikasi' => $aplikasi->getAplikasi(),
                                'jenis_aplikasi' => $aplikasi->getJenisAplikasi(),
                                'leader_programmer' => ($this->getLeaderProrgrammer($tiket->getId())) ?
                                    $this->getLeaderProrgrammer($tiket->getId()) : null
                            ];
                            $counter++;
                        }
                    }
                }
            }
        }

        return $data;
    }

}