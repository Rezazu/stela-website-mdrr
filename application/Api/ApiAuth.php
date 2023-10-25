<?php
trait ApiAuth
{
    // auth
    // endpoint : /api/login
    /**
     * method POST
     * body :
     * username 
     * password
     */
    public function loginAction()
    {
        // post method
        if ($this->getRequest()->isPost()) {
            try {
                // validate body
                $this->validateBody(['email', 'password']);
                $username = $this->_getParam('email');
                $password = md5($this->_getParam('password'));

                $auth = Zend_Auth::getInstance();

                $db = Zend_Registry::get('db_stela');


                $adapter = new Zend_Auth_Adapter_DbTable($db, 'pengguna', 'email', 'password');
                $adapter->setIdentity($username);

                $adapter->setCredential($password);
                $result = $auth->authenticate($adapter);

                if ($result->isValid()) {
                    $loggedname = $result->getIdentity();
                    $data = $adapter->getResultRowObject(null, 'password');
                    // get peran pengguna
                    $peran = $this->peranService->getAllDataByIdPengguna($data->id);
                    $tmpPeran = [];
                    foreach ((array)$peran as $p) {
                        try {
                            array_push($tmpPeran, $this->listPeranService->findById($p->id_peran)->getNamaPeran());
                        } catch (Exception $e) {
                            break;
                        }
                    }
                    // jika length peran pengguna 0 maka key peran pengguna adalah user
                    if (count($tmpPeran) == 0) {
                        $tmpPeran[0] = 'user';
                    }
                    // jika peran adalah admin maka isi semua list_peran pada key peran
                    if ($tmpPeran[0] == 'admin') {
                        foreach ($this->listPeranService->getAllData() as $v) {
                            if ($v->nama_peran == 'admin') continue;
                            array_push($tmpPeran, $v->nama_peran);
                        }
                    }
                    $data->peran = $tmpPeran;
                    $this->sendJson([
                        'success' => true,
                        'message' => "Berhasil login",
                        'data' => [
                            'user' => $data,
                            'token' => $this->jwt->encode((array) $data),
                        ],
                    ]);
                } else {
                    throw new Exception("Email atau password salah", 400);
                }
            } catch (Exception $e) {
                $this->sendJson([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], $e->getCode() >= 400 ? $e->getCode() : 400);
            }
        }
    }

    // get user info
    /**
     * endpoint : /api/user
     * method GET 
     * headers : 
     * Authorization : Bearer {token}
     */
    public function userAction()
    {
        try {
            $token = $this->getBearerToken();
            $user = $this->jwt->decode($token);
            $data = [];
            if ($this->getRequest()->getParam('id')){
                $petugas = $this->tiketPetugasService->getAllDataByIdPetugas('id');
                $rating =  $this->tiketPetugasService->getRatingByIdPetugas('id');
                array_push($data, [
                    'id' => $petugas['id'],
                    'nama' => $this->penggunaService->findById('id')->getNamaLengkap(),
                    'rating' => floatval($rating['rating']) 
                ]);
                
                $this->sendJson([
                    'success' => true,
                    'message' => 'Data berhasil ditemukan',
                    'data' => $data,
                ]);
            } else {

                $this->sendJson([
                    'success' => true,
                    'message' => 'Data berhasil ditemukan',
                    'data' => $user,
                ]);
            }
        } catch (Exception $e) {
            $this->sendJson([
                'success' => false,
                'message' => $e->getMessage(),
            ], $e->getCode() >= 400 ? $e->getCode() : 400);
        }
    }
    
}
