<?php

require_once APPLICATION_PATH . '/dto/JenisDokumen/JenisDokumenFindByIdResponse.php';
require_once APPLICATION_PATH . '/dto/JenisDokumen/JenisDokumenUpdateDataRequest.php';

class Dpr_JenisDokumenService
{
    protected $db;
    protected $jenisDokumen;
    protected $tanggal_log;

    public function __construct()
    {
        $this->jenisDokumen = new JenisDokumen();
        $this->db = Zend_Registry::get('db_stela');
        $this->tanggal_log = date('Y-m-d H:i:s');
    }

    public function findById($id)
    {
        $sql = "SELECT * FROM jenis_dokumen WHERE id = ?";
        $statement = $this->db->prepare($sql);
        $statement->execute([$id]);
        $statement->setFetchMode(Zend_Db::FETCH_OBJ);

        try {
            if ($result = $statement->fetch())
            {
                $response = new JenisDokumenFindByIdResponse();
                $response->setId($result->id);
                $response->setStatus($result->status);
                $response->setJenisDokumen($result->jenis_dokumen);
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

    public function addData($jenis_dokumen, $user_log)
    {
        $sql = "INSERT INTO jenis_dokumen(jenis_dokumen, user_input, tanggal_input, user_update, tanggal_update)
                VALUES (?, ?, ?, ?, ?)";
        $statement = $this->db->prepare($sql);
        $statement->execute([
            $jenis_dokumen,
            $user_log,
            $this->tanggal_log,
            $user_log,
            $this->tanggal_log
        ]);

        //Dapetin Last Insert Id
        $id = $this->jenisDokumen->getAdapter()->lastInsertId();

        return $this->findById($id);
    }

    public function updateData(JenisDokumenUpdateDataRequest $request)
    {
        $sql = "UPDATE jenis_dokumen SET jenis_dokumen = ?, status = ?, user_update = ?, tanggal_update = ? WHERE id = ?";
        $statement = $this->db->prepare($sql);
        $statement->execute([
            $request->getJenisDokumen(),
            $request->getStatus(),
            $request->getUserUpdate(),
            $this->tanggal_log,
            $request->getId()
        ]);

        $statement->closecursor();

        return $request;
    }

    public function updateJenisDokumen($id, $jenis_dokumen, $user_log)
    {
        try {
            $data = $this->findById($id);

            if ($data == null)
            {
                throw new Exception("Data Jenis Dokumen Tidak Ditemukan");
            }

            $data->setJenisDokumen($jenis_dokumen);
            $data->setUserUpdate($user_log);

            $jenisDokumenUpdateRequest = $this->update($data);

            $result = $this->updateData($jenisDokumenUpdateRequest);

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

            $jenisDokumenUpdateRequest = $this->update($data);

            $this->updateData($jenisDokumenUpdateRequest);

            return "Berhasil";

        }catch (Exception $exception)
        {
            throw $exception;
        }
    }

    public function update(JenisDokumenFindByIdResponse $data)
    {
        $jenisDokumenUpdateRequest = new JenisDokumenUpdateDataRequest();

        $jenisDokumenUpdateRequest->setId($data->getId());
        $jenisDokumenUpdateRequest->setTanggalUpdate($data->getTanggalUpdate());
        $jenisDokumenUpdateRequest->setUserUpdate($data->getUserUpdate());
        $jenisDokumenUpdateRequest->setJenisDokumen($data->getJenisDokumen());
        $jenisDokumenUpdateRequest->setStatus($data->getStatus());

        return $jenisDokumenUpdateRequest;
    }

    public function getAllData()
    {
        try{
            $sql = "SELECT * FROM jenis_dokumen WHERE status = 1";
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

    public function getListJenisDokumen(){
        $datas = $this->getAllData();

        $counter = 1;
        foreach ($datas as $data){
            $result[$counter] = [
                'id' => $data->id,
                'jenis_dokumen' => $data->jenis_dokumen
            ];
            $counter++;
        }

        return $result;
    }

}