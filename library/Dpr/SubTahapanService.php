<?php

include_once APPLICATION_PATH . '/dto/SubTahapan/SubTahapanAddRequest.php';
include_once APPLICATION_PATH . '/dto/SubTahapan/SubTahapanUpdateRequest.php';
include_once APPLICATION_PATH . '/dto/SubTahapan/SubTahapanFindIdResponse.php';
include_once APPLICATION_PATH . '/dto/SubTahapan/SubTahapanUpdateStatusRequest.php';
include_once APPLICATION_PATH . '/dto/SubTahapan/SubTahapanUpdateSubTahapanRequest.php';
include_once APPLICATION_PATH . '/dto/SubTahapan/SubTahapanSoftDeleteRequest.php';


class Dpr_SubTahapanService
{

	protected $db;
    protected $subTahapan;
    protected $tanggal_log;

	function __construct()
	{
		$this->subTahapan = new SubTahapan();
		$this->db = Zend_Registry::get('db_stela');
        $this->tanggal_log = date('Y-m-d H:i:s');
	}

	public function addData(SubTahapanAddRequest $request, $tanggal_input = null, $tanggal_update = null)
	{
		try{
			$this->validateEmpty($request->getSubTahapan());

			$sql = "INSERT INTO sub_tahapan(id_tahapan, id_tiket, sub_tahapan, user_input, tanggal_input, user_update, tanggal_update, status) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
			$statement = $this->db->prepare($sql);
			$statement->execute([
				$request->getIdTahapan(), 
				$request->getIdTiket(), 
				$request->getSubTahapan(), 
				$request->getUserInput(),
                ($tanggal_input) ? $tanggal_input : $this->tanggal_log,
				$request->getUserUpdate(),
                ($tanggal_update) ? $tanggal_update : $this->tanggal_log,
                $request->getStatus()
			]);

            //Dapetin Last Insert Id
            $request->setId($this->subTahapan->getAdapter()->lastInsertId());

			return $request;
		}catch(Exception $exception){
			throw $exception;
		}

	}

	public function findById($id)
	{
		$sql = "SELECT * FROM sub_tahapan WHERE id = ?";
		$statement = $this->db->prepare($sql);
		$statement->execute([$id]);
        $statement->setFetchMode(Zend_Db::FETCH_OBJ);

		try{
			if($result = $statement->fetch()){
				$response = new SubTahapanFindIdResponse();

				$response->setId($result->id);
				$response->setIdTahapan($result->id_tahapan);
				$response->setIdTiket($result->id_tiket);
				$response->setUserInput($result->user_input);
				$response->setSubTahapan($result->sub_tahapan);
				$response->setStatus($result->status);
				$response->setTanggalInput($result->tanggal_input);
				$response->setUserUpdate($result->user_update);
				$response->setTanggalUpdate($result->tanggal_update);
				
				return $response;
			}else{
				return null;
			}
		}finally{
			$statement->closecursor();
		}
	}

	public function updateData(SubTahapanUpdateRequest $request)
	{
		$sql = "UPDATE sub_tahapan SET sub_tahapan = ?, user_update = ?, status = ?, tanggal_update = ? WHERE id = ?";
		$statement = $this->db->prepare($sql);
		$statement->execute([
			$request->getSubTahapan(),
			$request->getUserUpdate(),
			$request->getStatus(),
			$this->tanggal_log,
			$request->getId()
		]);

		$statement->closecursor();

		return $request;
	}

    public function getAllSubTahapanByIdTiket($id_tiket)
    {
        $sql = "SELECT * FROM sub_tahapan WHERE id_tiket = ? ORDER BY id_tahapan ASC";
        $statement = $this->db->prepare($sql);
        $statement->execute([$id_tiket]);
        $statement->setFetchMode(Zend_Db::FETCH_OBJ);
        try {
            if ($result = $statement->fetchAll())
            {
                return $result;
            }else{
                return null;
            }
        } finally {
            $statement->closecursor();
        }
    }

	public function updateSubTahapan(SubTahapanUpdateSubTahapanRequest $request)
	{
		try{
			$this->validateEmpty($request->getSubTahapan());

			$data = $this->findById($request->getId());

			if($data == null){
				throw new Exception("Data Sub Tahapan Tidak Ditemukan");
			}

            $data->setId($request->getId());
            $data->setUserUpdate($request->getUserUpdate());
            $data->setSubTahapan($request->getSubTahapan());

			$subTahapanUpdateRequest = $this->update($data);

			$result = $this->updateData($subTahapanUpdateRequest);

			return $result;
		}catch(Exception $exception){
			throw $exception;
		}
	}

	public function updateStatus(SubTahapanUpdateStatusRequest $request)
	{
		try{
			$data = $this->findById($request->getId());

			if($data == null){
				throw new Exception("Data Sub Tahapan Tidak Ditemukan");
			}

            $data->setStatus($request->getStatus());
            $data->setUserUpdate($request->getUserUpdate());
            $data->setId($request->getId());

			$subTahapanUpdateRequest = $this->update($data);

			$result = $this->updateData($subTahapanUpdateRequest);

			return $result;
		}catch(Exception $exception){
			throw $exception;
		}
	}

    public function updateStatusSelesai($id_sub_tahapan)
    {
        try {

            $data = $this->findById($id_sub_tahapan);

            if($data == null){
                throw new Exception("Data Sub Tahapan Tidak Ditemukan");
            }

            $data->setStatus(0);
            $data->setId($id_sub_tahapan);

            $subTahapanUpdateRequest = $this->update($data);

            $result = $this->updateData($subTahapanUpdateRequest);

            return $result;
        }catch (Exception $exception){
            throw $exception;
        }
    }

    public function updateStatusBelumSelesai($id_sub_tahapan)
    {
        try {

            $data = $this->findById($id_sub_tahapan);

            if($data == null){
                throw new Exception("Data Sub Tahapan Tidak Ditemukan");
            }

            $data->setStatus(1);
            $data->setId($id_sub_tahapan);

            $subTahapanUpdateRequest = $this->update($data);

            $result = $this->updateData($subTahapanUpdateRequest);

            return $result;
        }catch (Exception $exception){
            throw $exception;
        }
    }

	function getAllDataByIdTahapanTiket($id_tahapan, $id_tiket)
	{
		$sql = "SELECT id, id_tahapan, id_tiket, sub_tahapan, status FROM sub_tahapan WHERE id_tahapan = ? AND id_tiket = ?";
		$statement = $this->db->prepare($sql);
        $statement->execute([$id_tahapan, $id_tiket]);
		$statement->setFetchMode(Zend_Db::FETCH_OBJ);

        try{
            if($result = $statement->fetchAll()){
				return $result;
			}else{
				return null;
			}
        }finally{
            $statement->closecursor();
        }
	}

	public function getDataSubTahapanByIdTiket($id_tiket)
	{
		$sql = "SELECT id, id_tahapan, id_tiket, sub_tahapan, status, tanggal_input FROM sub_tahapan WHERE id_tiket = ? ORDER BY id_tahapan";
		$statement = $this->db->prepare($sql);
		$statement->execute([$id_tiket]);
		$statement->setFetchMode(Zend_Db::FETCH_OBJ);

        try{
            if($result = $statement->fetchAll()){
				return $result;
			}else{
				return null;
			}
        }finally{
            $statement->closecursor();
        }

	}

	function delete($id)
	{
		$sql = "DELETE FROM sub_tahapan WHERE id = ?";
		$statement = $this->db->prepare($sql);
		$statement->execute([$id]);

		try{
			$result = $this->findById($id);

			if ($result == null){
				return "Berhasil dihapus";
			}else{
				return "Masih belum terhapus";
			}
		}finally{
			$statement->closecursor();
		}

	}

	function softDelete(SubTahapanSoftDeleteRequest $request)
	{
		try{
			$data = $this->findById($request->getId());

			if ($data == null){
				throw new Exception("Data Sub Tahapan Tidak Ditemukan");
			}

            $data->setId($request->getId());
            $data->setUserUpdate($request->getUserUpdate());
            $data->setStatus($request->getStatus());

			$subTahapanUpdateRequest = $this->update($data);

			$this->updateData($subTahapanUpdateRequest);

			return "Berhasil";

		}catch(Exception $exception){
			throw $exception;
		}
	}

    private function update(SubTahapanFindIdResponse $data)
    {
        $subTahapanUpdateRequest = new SubTahapanUpdateRequest();

        $subTahapanUpdateRequest->setId($data->getId());
        $subTahapanUpdateRequest->setStatus($data->getStatus());
        $subTahapanUpdateRequest->setUserUpdate($data->getUserUpdate());
        $subTahapanUpdateRequest->setSubTahapan($data->getSubTahapan());

        return $subTahapanUpdateRequest;
    }

	private function validateEmpty($value){
        $validator = new Zend_Validate_NotEmpty(Zend_Validate_NotEmpty::INTEGER + 
            Zend_validate_NotEmpty::ZERO +
            Zend_Validate_NotEmpty::STRING
        );
        if(!$validator->isValid($value))
        {
            throw new Exception("Tidak boleh kosong");
        }
    }
	
}
