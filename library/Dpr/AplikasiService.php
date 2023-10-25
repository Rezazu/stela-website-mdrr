<?php

require_once APPLICATION_PATH . '/dto/Aplikasi/AplikasiFindByIdResponse.php';
require_once APPLICATION_PATH . '/dto/Aplikasi/AplikasiAddDataRequest.php';
require_once APPLICATION_PATH . '/dto/Aplikasi/AplikasiUpdateDataRequest.php';
require_once APPLICATION_PATH . '/dto/Aplikasi/AplikasiUpdateAplikasiRequest.php';

class Dpr_AplikasiService
{
    protected $db;
    protected $aplikasi;
    protected $tanggal_log;

    public function __construct()
    {
        $this->aplikasi = new Aplikasi();
        $this->db = Zend_Registry::get('db_stela');
        $this->tanggal_log = date('Y-m-d H:i:s');
    }

    public function getAllData()
    {
        try{
            $sql = "SELECT * FROM aplikasi WHERE status = 1";
            $statement = $this->db->query($sql);
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

    public function getListAplikasi()
    {
        $data = $this->getAllData();

        $counter = 1;
        foreach ($data as $d)
        {
            $result[$counter] = [
                'id' => $d->id,
                'aplikasi' => $d->aplikasi
            ];
            $counter++;
        }

        return $result;
    }

    public function findById($id)
    {
        $sql = "SELECT * FROM aplikasi WHERE id = ?";
        $statement = $this->db->prepare($sql);
        $statement->execute([$id]);
        $statement->setFetchMode(Zend_Db::FETCH_OBJ);

        try {
            if ($result = $statement->fetch())
            {
                $response = new AplikasiFindByIdResponse();
                $response->setId($id);
                $response->setAplikasi($result->aplikasi);
                $response->setIdStatusAplikasi($result->id_status_aplikasi);
                $response->setJenisAplikasi($result->jenis_aplikasi);
                $response->setDeveloper($result->developer);
                $response->setPengelola($result->pengelola);
                $response->setStatus($result->status);
                $response->setUserInput($result->user_input);
                $response->setTanggalInput($result->tanggal_input);
                $response->setUserUpdate($result->user_update);
                $response->setTanggalUpdate($result->tanggal_update);

                return $response;
            }else{
                return null;
            }
        } finally {
            $statement->closecursor();
        }
    }

    public function addData(AplikasiAddDataRequest $request)
    {
        $sql = "INSERT INTO aplikasi(aplikasi, id_status_aplikasi, jenis_aplikasi, developer, pengelola, user_input, tanggal_input, user_update, tanggal_update)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $statement = $this->db->prepare($sql);
        $statement->execute([
            $request->getAplikasi(),
            $request->getIdStatusAplikasi(),
            $request->getJenisAplikasi(),
            $request->getDeveloper(),
            $request->getPengelola(),
            $request->getUserInput(),
            $this->tanggal_log,
            $request->getUserUpdate(),
            $this->tanggal_log
        ]);

        return $request;
    }

    public function updateData(AplikasiUpdateDataRequest $request)
    {
        $sql = "UPDATE aplikasi SET aplikasi = ?, id_status_aplikasi = ?, jenis_aplikasi = ?, developer = ?, 
                    pengelola = ?, status = ?, user_update = ?, tanggal_update = ? WHERE id = ?";
        $statement = $this->db->prepare($sql);
        $statement->execute([
            $request->getAplikasi(),
            $request->getIdStatusAplikasi(),
            $request->getJenisAplikasi(),
            $request->getDeveloper(),
            $request->getPengelola(),
            $request->getStatus(),
            $request->getUserUpdate(),
            $this->tanggal_log,
            $request->getId()
        ]);

        return $request;
    }

    public function updateAplikasi(AplikasiUpdateAplikasiRequest $request)
    {
        try {
            $data = $this->findById($request->getId());

            if ($data == null)
            {
                throw new Exception("Data Tidak DItemukan");
            }

            $data->setUserUpdate($request->getUserUpdate());
            $data->setPengelola($request->getPengelola());
            $data->setDeveloper($request->getDeveloper());
            $data->setJenisAplikasi($request->getJenisAplikasi());
            $data->setIdStatusAplikasi($request->getIdStatusAplikasi());
            $data->setAplikasi($request->getAplikasi());

            $aplikasiUpdateDataRequest = $this->update($data);

            $response = $this->updateData($aplikasiUpdateDataRequest);

            return $response;


        }catch (Exception $exception)
        {
            throw $exception;
        }
    }

    public function softDelete($id, $user_log)
    {
        try {
            $data = $this->findById($id);

            if ($data == null)
            {
                throw new Exception("Gagal Dihapus");
            }

            $data->setStatus(9);
            $data->setUserUpdate($user_log);

            $aplikasiUpdateDataRequest = $this->update($data);

            $this->updateData($aplikasiUpdateDataRequest);

            return "Berhasil Dihapus";

        }catch (Exception $exception)
        {
            throw $exception;
        }
    }

    public function update(AplikasiFindByIdResponse $data)
    {
        $aplikasiUpdateDataRequest = new AplikasiUpdateDataRequest();

        $aplikasiUpdateDataRequest->setId($data->getId());
        $aplikasiUpdateDataRequest->setAplikasi($data->getAplikasi());
        $aplikasiUpdateDataRequest->setIdStatusAplikasi($data->getIdStatusAplikasi());
        $aplikasiUpdateDataRequest->setJenisAplikasi($data->getJenisAplikasi());
        $aplikasiUpdateDataRequest->setDeveloper($data->getDeveloper());
        $aplikasiUpdateDataRequest->setPengelola($data->getPengelola());
        $aplikasiUpdateDataRequest->setStatus($data->getStatus());
        $aplikasiUpdateDataRequest->setUserUpdate($data->getUserUpdate());

        return $aplikasiUpdateDataRequest;
    }
}