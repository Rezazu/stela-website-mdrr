<?php

require_once APPLICATION_PATH . '/dto/ProgrammerExternal/ProgrammerExternalFindByIdResponse.php';
require_once APPLICATION_PATH . '/dto/ProgrammerExternal/ProgrammerExternalUpdateDataRequest.php';

class Dpr_ProgrammerExternalService
{
    protected $db;
    protected $programmerExternal;
    protected $tanggal_log;

    public function __construct()
    {
        $this->programmerExternal = new ProgrammerExternal();
        $this->db = Zend_Registry::get('db_stela');
        $this->tanggal_log = date('Y-m-d H:i:s');
    }

    public function getListProgrammerExternal()
    {
        $datas = $this->getAllData();

        $counter = 1;
        foreach ($datas as $data)
        {
            $result[$counter] = [
                'id' => $data->id_pengguna,
                'nama' => $data->nama_instansi
            ];
            $counter++;
        }

        return $result;
    }

    private function findByResponse($result)
    {
        $response = new ProgrammerExternalFindByIdResponse();
        $response->setId($result->id);
        $response->setIdPEngguna($result->id_pengguna);
        $response->setStatus($result->status);
        $response->setNamaInstansi($result->nama_instansi);
        $response->setUserUpdate($result->user_update);
        $response->setTanggalUpdate($result->tanggal_update);
        $response->setUserInput($result->user_input);
        $response->setTanggalInput($result->tanggal_input);
        
        return $response;
    }

    public function findById($id)
    {
        $sql = "SELECT * FROM programmer_external WHERE id = ?";
        $statement = $this->db->prepare($sql);
        $statement->execute([$id]);
        $statement->setFetchMode(Zend_Db::FETCH_OBJ);

        try {
            if ($result = $statement->fetch())
            {
                return $this->findByResponse($result);
            }else{
                return null;
            }
        } finally {
            $statement->closecursor();
        }
    }

    public function findByIdPengguna($id_pengguna)
    {
        $sql = "SELECT * FROM programmer_external WHERE id_pengguna = ?";
        $statement = $this->db->prepare($sql);
        $statement->execute([$id_pengguna]);
        $statement->setFetchMode(Zend_Db::FETCH_OBJ);

        try {
            if ($result = $statement->fetch())
            {
                return $this->findByResponse($result);
            }else{
                return null;
            }
        } finally {
            $statement->closecursor();
        }
    }

    public function addData($id_pengguna, $nama_instansi, $user_log)
    {
        $sql = "INSERT INTO programmer_external(id_pengguna, nama_instansi, user_input, tanggal_input, user_update, tanggal_update)
                VALUES (?, ?, ?, ?, ?, ?)";
        $statement = $this->db->prepare($sql);
        $statement->execute([
            $id_pengguna,
            $nama_instansi,
            $user_log,
            $this->tanggal_log,
            $user_log,
            $this->tanggal_log
        ]);

        //Dapetin Last Insert Id
        $id = $this->programmerExternal->getAdapter()->lastInsertId();

        return $this->findById($id);
    }

    public function updateData(ProgrammerExternalUpdateDataRequest $request)
    {
        $sql = "UPDATE programmer_external SET nama_instansi = ?, status = ?, user_update = ?, tanggal_update = ? WHERE id = ?";
        $statement = $this->db->prepare($sql);
        $statement->execute([
            $request->getNamaInstansi(),
            $request->getStatus(),
            $request->getUserUpdate(),
            $this->tanggal_log,
            $request->getId()
        ]);

        $statement->closecursor();

        return $request;
    }

    public function updateProgrammerExternal($id, $nama_instansi, $user_log)
    {
        try {
            $data = $this->findById($id);

            if ($data == null)
            {
                throw new Exception("Data Programmer External Tidak Ditemukan");
            }

            $data->setNamaInstansi($nama_instansi);
            $data->setUserUpdate($user_log);

            $programmerExternalUpdateDataRequest = $this->update($data);

            $result = $this->updateData($programmerExternalUpdateDataRequest);

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

            $programmerExternalUpdateDataRequest = $this->update($data);

            $this->updateData($programmerExternalUpdateDataRequest);

            return "Berhasil";

        }catch (Exception $exception)
        {
            throw $exception;
        }
    }

    public function update(ProgrammerExternalFindByIdResponse $data)
    {
        $programmerExternalUpdateDataRequest = new ProgrammerExternalUpdateDataRequest();

        $programmerExternalUpdateDataRequest->setId($data->getId());
        $programmerExternalUpdateDataRequest->setTanggalUpdate($data->getTanggalUpdate());
        $programmerExternalUpdateDataRequest->setUserUpdate($data->getUserUpdate());
        $programmerExternalUpdateDataRequest->setNamaInstansi($data->getNamaInstansi());
        $programmerExternalUpdateDataRequest->setStatus($data->getStatus());

        return $programmerExternalUpdateDataRequest;
    }

    public function getAllData()
    {
        try{
            $sql = "SELECT * FROM programmer_external WHERE status = 1";
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

}