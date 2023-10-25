<?php

require_once APPLICATION_PATH . '/dto/Peran/PeranAddDataRequest.php';
require_once APPLICATION_PATH . '/dto/Peran/PeranFIndByIdResponse.php';
require_once APPLICATION_PATH . '/dto/Peran/PeranUpdateDataRequest.php';
require_once APPLICATION_PATH . '/dto/Peran/PeranDeleteRequest.php';
require_once APPLICATION_PATH . '/dto/Peran/PeranUpdatePeranRequest.php';

class Dpr_PeranService
{

    protected $db;
    protected $pengguna;
    protected $tanggal_log;

    function __construct()
    {
        $this->db = Zend_Registry::get('db_stela');
        $this->peran = new Peran();
        $this->listPeran = new Dpr_ListPeranService();
        $this->tanggal_log = date('Y-m-d H:i:s');
    }


    public function findById($id)
    {
        $sql = "SELECT id_pengguna, id_peran, status FROM peran WHERE id = ?";
        $statement = $this->db->prepare($sql);
        $statement->execute([$id]);
        $statement->setFetchMode(Zend_Db::FETCH_OBJ);

        try {
            if ($result = $statement->fetch()) {
                $response = new PeranFIndByIdResponse();
                $response->setIdPengguna($result->id_pengguna);
                $response->setIdPeran($result->id_peran);
                $response->setStatus($result->status);

                return $response;
            } else {
                return null;
            }
        } finally {
            $statement->closecursor();
        }
    }

    public function getAllDataByIdPengguna($id_pengguna)
    {
        $sql = "SELECT id, id_peran FROM peran WHERE id_pengguna = ? ORDER BY id_peran";
        $statement = $this->db->prepare($sql);
        $statement->execute([$id_pengguna]);
        $statement->setFetchMode(Zend_Db::FETCH_OBJ);

        try {
            if ($result = $statement->fetchAll()) {
                return $result;
            } else {
                return "User";
            }
        } finally {
            $statement->closecursor();
        }
    }

    public function getPeranLeftByIdPengguna($id_pengguna)
    {
        $perans = $this->getAllDataByIdPengguna($id_pengguna);
        $listPeran = $this->listPeran->getAllData();

        if ($perans[0]->id_peran != 1 || $perans == 'User') {
            $result = [];
            foreach ($listPeran as $peran) {
                $status = false;
                if ($perans != 'User') {
                    foreach ($perans as $list) {
                        if ($peran->id == $list->id_peran) {
                            $status = true;
                        }
                    }
                }
                if (!$status){
                    array_push($result, ['id' => $peran->id, 'nama_peran' => $peran->nama_peran]);
                }
            }
        }

        return (isset($result)) ? $result : null;
    }

    // get only peran stela
    public function getPeranStelaOnly($id_pengguna)
    {
        $peran = $this->getAllDataByIdPengguna($id_pengguna);
        if ($peran)
            foreach ($peran as $v) {
                if ($v->id_peran >= 5) return [
                    'id_peran' => $v->id_peran,
                    'peran' => ucfirst($this->listPeran->findById($v->id_peran)->getNamaPeran()),
                ];
            }
    }

    public function getAllData()
    {
        $sql = "SELECT * FROM peran";
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

    public function getAllDataAktif()
    {
        $sql = "SELECT * FROM peran WHERE status = 1";
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

    public function addData(PeranAddDataRequest $request)
    {
        try {
            $sql = "INSERT INTO peran(id_pengguna, id_peran) VALUES (?, ?)";

            $statement = $this->db->prepare($sql);
            $statement->execute([
                $request->getIdPengguna(),
                $request->getIdPeran()
            ]);
            $request->setId($this->peran->getAdapter()->lastInsertId());
            return $request;
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function updateData(PeranUpdateDataRequest $request)
    {
        $sql = "UPDATE peran SET id_peran = ?, status = ? WHERE id = ?";
        $statement = $this->db->prepare($sql);
        $statement->execute([
            $request->getIdPeran(),
            $request->getStatus(),
            $request->getId()
        ]);

        $statement->closecursor();

        return $request;
    }


    public function updatePeran(PeranUpdatePeranRequest $request)
    {
        try {
            $data = $this->findById($request->getId());

            if ($data == null) {
                throw new Exception("Data Tidak Ditemukan");
            }

            $peranUpdateDataRequest = new PeranUpdateDataRequest();
            $peranUpdateDataRequest->setId($request->getId());
            $peranUpdateDataRequest->setIdPeran($request->getIdPeran());
            $peranUpdateDataRequest->setStatus($data->getStatus());

            $result = $this->updateData($peranUpdateDataRequest);

            return $result;
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function softDelete(PeranDeleteRequest $request)
    {
        try {
            $data = $this->findById($request->getId());

            if ($data == null) {
                throw new Exception("Data Tidak Ditemukan");
            }

            $peranUpdateDataRequest = new PeranUpdateDataRequest();
            $peranUpdateDataRequest->setId($request->getId());
            $peranUpdateDataRequest->setIdPeran($data->getIdPeran());
            $peranUpdateDataRequest->setStatus($request->getStatus());

            $this->updateData($peranUpdateDataRequest);

            return "Berhasil Dihapus";
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function delete($id)
    {
        try {
            $data = $this->findById($id);

            if ($data == null) {
                throw new Exception("Data Tidak Ditemukan");
            }

            $sql = "DELETE FROM peran WHERE id = ?";
            $statement = $this->db->prepare($sql);
            $statement->execute([$id]);

            $data = $this->findById($id);

            if ($data == null) {
                return "Berhasil Dihapus";
            } else {
                return "Data Tidak Berhasil Dihapus";
            }
        } catch (Exception $exception) {
            throw $exception;
        }
    }
}
