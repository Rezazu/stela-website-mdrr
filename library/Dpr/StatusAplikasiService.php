<?php

require_once APPLICATION_PATH . '/dto/StatusAplikasi/StatusAplikasiFindByIdResponse.php';
require_once APPLICATION_PATH . '/dto/StatusAplikasi/StatusAplikasiUpdateDataRequest.php';

class Dpr_StatusAplikasiService
{
    protected $db;
    protected $statusAplikasi;
    protected $tanggal_log;

    public function __construct()
    {
        $this->statusAplikasi = new StatusAplikasi();
        $this->db = Zend_Registry::get('db_stela');
        $this->tanggal_log = date('Y-m-d H:i:s');
    }

    public function findById($id)
    {
        $sql = "SELECT * FROM status_aplikasi WHERE id = ?";
        $statement = $this->db->prepare($sql);
        $statement->execute([$id]);
        $statement->setFetchMode(Zend_Db::FETCH_OBJ);

        try {
            if ($result = $statement->fetch())
            {
                $response = new StatusAplikasiFindByIdResponse();
                $response->setId($result->id);
                $response->setStatus($result->status);
                $response->setStatusAplikasi($result->status_aplikasi);
                $response->setUserUpdate($result->user_update);
                $response->setTanggalUpdate($result->tanggal_update);
                $response->setUserInput($result->user_input);
                $response->setTanggalInput($result->tanggal_input);

                return $response;
            }else{
                return null;
            }
        } finally {
            $statement->closecursor();
        }
    }

    public function addData($status_aplikasi, $user_log)
    {
        $sql = "INSERT INTO status_aplikasi(status_aplikasi, user_input, tanggal_input, user_update, tanggal_update)
                VALUES (?, ?, ?, ?, ?)";
        $statement = $this->db->prepare($sql);
        $statement->execute([
            $status_aplikasi,
            $user_log,
            $this->tanggal_log,
            $user_log,
            $this->tanggal_log
        ]);

        //Dapetin Last Insert Id
        $id = $this->statusAplikasi->getAdapter()->lastInsertId();

        return $this->findById($id);
    }

    public function updateData(StatusAplikasiUpdateDataRequest $request)
    {
        $sql = "UPDATE status_aplikasi SET status_aplikasi = ?, status = ?, user_update = ?, tanggal_update = ? WHERE id = ?";
        $statement = $this->db->prepare($sql);
        $statement->execute([
            $request->getStatusAplikasi(),
            $request->getStatus(),
            $request->getUserUpdate(),
            $this->tanggal_log,
            $request->getId()
        ]);

        $statement->closecursor();

        return $request;
    }

    public function updateJenisAplikasi($id, $status_aplikasi, $user_log)
    {
        try {
            $data = $this->findById($id);

            if ($data == null)
            {
                throw new Exception("Data Jenis Aplikasi Tidak Ditemukan");
            }

            $data->setStatusAplikasi($status_aplikasi);
            $data->setUserUpdate($user_log);

            $jenisAplikasiUpdateRequest = $this->update($data);

            $result = $this->updateData($jenisAplikasiUpdateRequest);

            return $result;

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
                throw new Exception("Data Jenis Dokumen Tidak Ditemukan");
            }

            $data->setStatus(9);
            $data->setUserUpdate($user_log);

            $jenisAplikasiUpdateRequest = $this->update($data);

            $this->updateData($jenisAplikasiUpdateRequest);

            return "Berhasil";

        }catch (Exception $exception)
        {
            throw $exception;
        }
    }

    public function update(StatusAplikasiFindByIdResponse $data)
    {
        $jenisAplikasiUpdateDataRequest = new StatusAplikasiUpdateDataRequest();

        $jenisAplikasiUpdateDataRequest->setId($data->getId());
        $jenisAplikasiUpdateDataRequest->setTanggalUpdate($data->getTanggalUpdate());
        $jenisAplikasiUpdateDataRequest->setUserUpdate($data->getUserUpdate());
        $jenisAplikasiUpdateDataRequest->setStatusAplikasi($data->getStatusAplikasi());
        $jenisAplikasiUpdateDataRequest->setStatus($data->getStatus());

        return $jenisAplikasiUpdateDataRequest;
    }

    public function getAllData()
    {
        try{
            $sql = "SELECT * FROM status_aplikasi WHERE status = 1";
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

    public function getListStatusAplikasi()
    {
        $datas = $this->getAllData();

        $counter = 1;
        foreach ($datas as $d)
        {
            $data[$counter] = [
                'id' => $d->id,
                'status_aplikasi' => $d->status_aplikasi
            ];
            $counter++;
        }

        return $data;
    }

}