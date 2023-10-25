<?php

require_once APPLICATION_PATH . '/dto/TimProgrammer/TimProgrammerAddRequest.php';
require_once APPLICATION_PATH . '/dto/TimProgrammer/TimProgrammerFindByIdResponse.php';
require_once APPLICATION_PATH . '/dto/TimProgrammer/TimProgrammerUpdateRequest.php';

class Dpr_TimProgrammerService
{
    protected $db;
    protected $timProgrammer;

    public function __construct()
    {
        $this->db = Zend_Registry::get('db_stela');
        $this->timProgrammer = new TimProgrammer();
        $this->pengguna = new Dpr_PenggunaService();
        $this->programmer = new Dpr_ProgrammerService();
        $this->tiket = new Dpr_TiketService();
    }


    public function addData(TimProgrammerAddRequest $request)
    {
        try{
            $this->validateEmpty($request->getNamaTim());

            $sql = "INSERT INTO tim_programmer(nama_tim) VALUES (?)";
            $statement = $this->db->prepare($sql);
            $statement->execute([$request->getNamaTim()]);
            $request->setId($this->timProgrammer->getAdapter()->lastInsertId());

            //Dapetin Last Insert Id tim programmer
            $request->setId($this->timProgrammer->getAdapter()->lastInsertId());

            return $request;
        }catch(Exception $exception){
            throw $exception;
        }
        
    }

    public function tambahTim($namaTim)
    {
        $request = new TimProgrammerAddRequest();
        $request->setNamaTim($namaTim);

        return $this->addData($request);
    }

    public function findById($id)
    {
        $sql = "SELECT id, nama_tim FROM tim_programmer WHERE id = ?";
        $statement = $this->db->prepare($sql);
        $statement->execute([$id]);
        $statement->setFetchMode(Zend_Db::FETCH_OBJ);

        try{
            if($result = $statement->fetch())
            {
                $response = new TimProgrammerFindByIdResponse(); 

                $response->setId($result->id);
                $response->setNamaTim($result->nama_tim);

                return $response;
            }else{
                return null;
            }
        }finally{
            $statement->closecursor();
        }
        
    }

    public function update(TimProgrammerUpdateRequest $request)
    {
        try{
            $sql = "UPDATE tim_programmer SET nama_tim = ? WHERE id = ?";
            $statement = $this->db->prepare($sql);
            $statement->execute([
                $request->getNamaTim(),
                $request->getId()
            ]);

            return $request;
        }catch(Exception $exception){
            throw $exception;
        }
        
    }

    public function editNamaTim($id_tiket, $id_tim_programmer, $id_leader_programmer)
    {
        try {
            $proyek = $this->tiket->findById($id_tiket)->getNoTiket();
            $namaLeader = $this->pengguna->findById($id_leader_programmer)->getNamaLengkap();
            $namaTim = $proyek . '-' . $namaLeader;

            $data = $this->findById($id_tim_programmer);

            if ($data == null)
            {
                throw new Exception("Data Tidak Ditemukan");
            }

            $request = new TimProgrammerUpdateRequest();
            $request->setId($id_tim_programmer);
            $request->setNamaTim($namaTim);

            $this->update($request);
        }catch (Exception $exception)
        {
            throw $exception;
        }
    }

    public function delete($id)
    {
        try{
			$result = $this->findById($id);

            if($result == null){
                throw new Exception("Data Tidak Ditemukan");
            }

            $sql = "DELETE FROM tim_programmer WHERE id = ?";
            $statement = $this->db->prepare($sql);
            $statement->execute([$id]);

            $result = $this->findById($id);

			if ($result == null){
				return "Berhasil dihapus";
			}else{
				throw new Exception("Masih belum terhapus");
			}
		}catch(Exception $exception){
            throw $exception;
        }
    }

    public function getAllData()
    {
        $sql = "SELECT * FROM tim_programmer";
        $statement = $this->db->query($sql);
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

    public function getListProgrammer($id_tim_programmer)
    {
        $array = null;
        if ($id_tim_programmer != null){
            $timProgrammer = $this->findById($id_tim_programmer);

            if ($timProgrammer != null) {
                $listProgrammer = [];
                $programmer = $this->programmer->getAllDataByIdTImProgrammer($timProgrammer->getId());

                if ($programmer != null)
                {
                    $count = 1;
                    foreach ($programmer as $pro) {
                        $nama_programmer = $this->pengguna->findById($pro->id_pengguna)->getNamaLengkap();
                        $pengguna = $this->pengguna->findById($pro->id_pengguna);
                        $listProgrammer[$count] = [
                            'id' => $pengguna->getId(),
                            'nama' => $nama_programmer,
                            'jabatan' => $pro->jabatan,
                            'profile' => ($pengguna->getProfile()) ? "/stela/assets/profile/{$pengguna->getProfile()}" : "/stela/assets/profile/avatar1.jpeg"
                        ];
                        $count++;
                    }
                }
                $array =  $listProgrammer;
            }
        }

        return $array;
    }

    public function getTimProgrammer($id_tim_programmer)
    {
        $array = null;
        if ($id_tim_programmer != null){
            $timProgrammer = $this->findById($id_tim_programmer);

            if ($timProgrammer != null) {
                $listProgrammer = [];
                $programmer = $this->programmer->getAllDataByIdTImProgrammer($timProgrammer->getId());

                if ($programmer != null)
                {
                    $count = 1;
                    foreach ($programmer as $pro) {
                        $nama_programmer = $this->pengguna->findById($pro->id_pengguna)->getNamaLengkap();
                        $pengguna = $this->pengguna->findById($pro->id_pengguna);
                        $listProgrammer[$count] = [
                            'id' => $pengguna->getId(),
                            'nama' => $nama_programmer,
                            'jabatan' => $pro->jabatan,
                            'profile' => ($pengguna->getProfile()) ? "/stela/assets/profile/{$pengguna->getProfile()}" : "/stela/assets/profile/avatar1.jpeg"

                        ];
                        $count++;
                    }
                }

                $array =  [
                    'nama_tim' => $timProgrammer->getNamaTim(),
                    'list_programmer' => isset($listProgrammer) ? $listProgrammer : null
                ];
            }
        }

        return $array;
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