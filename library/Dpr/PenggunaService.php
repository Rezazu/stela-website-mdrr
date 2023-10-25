<?php

require_once APPLICATION_PATH . '/dto/Pengguna/PenggunaFIndByIdResponse.php';
require_once APPLICATION_PATH . '/dto/Pengguna/PenggunaAddDataRequest.php';

class Dpr_PenggunaService
{

    protected $db;
    protected $pengguna;

    function __construct()
    {
        $this->db = Zend_Registry::get('db_stela');
        $this->pengguna = new Pengguna();
        $this->peran = new Dpr_PeranService();
        $this->listPeran = new Dpr_ListPeranService();
    }

    public function getAllData()
    {
        $sql = "SELECT * FROM pengguna";
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

    public function addData(PenggunaAddDataRequest $request)
    {
        $sql = "INSERT INTO pengguna(nama_lengkap, username, email, password, kd_departemen, bagian, gedung, unit_kerja,
                     ruangan, lantai, telepon, hp, profile) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $statement = $this->db->prepare($sql);
        $statement->execute([
            $request->getNamaLengkap(),
            $request->getUsername(),
            $request->getEmail(),
            $request->getPassword(),
            $request->getKdDepartemen(),
            $request->getBagian(),
            $request->getGedung(),
            $request->getUnitKerja(),
            $request->getRuangan(),
            $request->getLantai(),
            $request->getTelepon(),
            $request->getHp(),
            $request->getProfile()
        ]);
        $request->setId($this->pengguna->getAdapter()->lastInsertId());

        return $request;
    }

    //bagian,gedung,unit_kerja,ruangan,lantai,telepon,hp
    public function findById($id)
    {

        $sql = "SELECT * FROM pengguna WHERE id = ?";

        $statement = $this->db->prepare($sql);
        $statement->execute([$id]);

        $statement->setFetchMode(Zend_Db::FETCH_OBJ);

        try {
            if ($result = $statement->fetch()) {
                $response = new PenggunaFindByIdResponse();
                $response->setId($id);
                $response->setEmail($result->email);
                $response->setNamaLengkap($result->nama_lengkap);
                $response->setUsername($result->username);
                $response->setKdDepartemen($result->kd_departemen);
                $response->setStatus($result->status);
                $response->setBagian($result->bagian);
                $response->setGedung($result->gedung);
                $response->setUnitKerja($result->unit_kerja);
                $response->setRuangan($result->ruangan);
                $response->setLantai($result->lantai);
                $response->setTelepon($result->telepon);
                $response->setHp($result->hp);
                $response->setProfile($result->profile);

                return $response;
            } else {
                throw new Exception("Data Tidak Ditemukan", 404);
            }
        } catch (Exception $exception) {
            throw $exception;
        } finally {
            $statement->closecursor();
        }
    }

    public function deleteData($id_pengguna)
    {
        $sql = "DELETE FROM pengguna WHERE id = ?";
        $statement = $this->db->prepare($sql);
        $statement->execute([$id_pengguna]);

        return "Berhasil Dihapus";
    }

    public function getPeranStela()
    {
        $pengguna = $this->getAllData();

        $counter = 1;
        foreach ($pengguna as $guna) {
            // kalo statusnya 0 skip aja bang
            if ($guna->status == 0) continue;
            $peran = $this->peran->getAllDataByIdPengguna($guna->id);
            if (is_array($peran)) {
                foreach ($peran as $per) {
                    // cuman helpdesk sama it specialist doang yang diajak
                    if ($per->id_peran == 5 || $per->id_peran == 7) {
                        $data[$counter] = [
                            'id' => $guna->id,
                            'nama' => $guna->nama_lengkap,
                            'id_peran' => $per->id_peran,
                            'peran' => $this->listPeran->findById($per->id_peran)->getNamaPeran()
                        ];
                    }
                }
                $counter++;
            }
        }
        // sorting by id peran
        usort($data, function ($first, $second) {
            return $first['id_peran'] > $second['id_peran'];
        });
        return $data;
    }

    public function getPeranServiceDesk()
    {
        $pengguna = $this->getAllData();

        $counter = 1;
        foreach ($pengguna as $guna) {
            // kalo statusnya 0 skip aja bang
            if ($guna->status == 0) continue;
            $peran = $this->peran->getAllDataByIdPengguna($guna->id);
            if (is_array($peran)) {
                foreach ($peran as $per) {
                    //    ambil peran service desk doang
                    if ($per->id_peran == 6) {
                        $data[$counter] = [
                            'id' => $guna->id,
                            'nama' => $guna->nama_lengkap,
                            'id_peran' => $per->id_peran,
                            'peran' => $this->listPeran->findById($per->id_peran)->getNamaPeran(),
                            'hp' => $this->findById($guna->id)->getHp(),
                        ];
                    }
                }
                $counter++;
            }
        }
        return array_values($data);
    }

    public function getPeranStelaForDaftarPetugas()
    {
        $pengguna = $this->getAllData();

        $counter = 1;
        foreach ($pengguna as $guna) {
            // kalo statusnya 0 skip aja bang
            //            if ($guna->status == 0) continue;
            $peran = $this->peran->getAllDataByIdPengguna($guna->id);
            if (is_array($peran)) {
                foreach ($peran as $per) {
                    // cuman helpdesk sama it specialist doang yang diajak
                    if ($per->id_peran >= 5 || $per->id_peran == 1) {
                        $data[$counter] = [
                            'id' => $guna->id,
                            'nama' => $guna->nama_lengkap,
                            'id_peran' => $per->id_peran,
                            'profile' => ($guna->profile) ? '/stela/assets/profile/' . $guna->profile : "https://ui-avatars.com/api/?name={$guna->nama_lengkap}",
                            'peran' => $this->listPeran->findById($per->id_peran)->getNamaPeran()
                        ];
                    }
                }
                $counter++;
            }
        }
        // sorting by id peran
        usort($data, function ($first, $second) {
            return $first['id_peran'] > $second['id_peran'];
        });
        return $data;
    }

    public function getPeranVerificator()
    {
        $pengguna = $this->getAllData();

        $counter = 1;
        foreach ($pengguna as $guna) {
            // kalo statusnya 0 skip aja bang
            if ($guna->status == 0) continue;
            $peran = $this->peran->getAllDataByIdPengguna($guna->id);
            if (is_array($peran)) {
                foreach ($peran as $per) {
                    if ($per->id_peran == 2) {
                        $data[$counter] = [
                            'id' => $guna->id,
                            'nama' => $guna->nama_lengkap,
                            'peran' => $this->listPeran->findById($per->id_peran)->getNamaPeran()
                        ];
                    }
                }
                $counter++;
            }
        }
        return $data;
    }

    // toggle status user jadi 0 kalo tidak aktif
    // status 1 kalo aktif
    public function toggleStatus($id_pengguna)
    {
        $user = $this->findById($id_pengguna);
        $params = [
            'status' => !$user->getStatus(),
        ];
        $where = $this->pengguna->getAdapter()->quoteInto('id=?', $id_pengguna);
        $this->pengguna->update($params, $where);
    }
}
