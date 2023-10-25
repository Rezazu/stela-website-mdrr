<?php

class AdminController extends Zend_Controller_Action
{
    public function init()
    {
        $this->_helper->_acl->allow('admin');
         // sett layout ke stela ke semua action controller
        $this->_helper->layout->setLayout('layout_admin');
    }

    public function preDispatch()
    {
        $this->pengguna = new Dpr_PenggunaService();
        $this->peran = new Dpr_PeranService();
        $this->listPeran = new Dpr_ListPeranService();
        $this->tiketPetugas = new Dpr_TiketPetugasService();
        $this->tiket = new Dpr_TiketService();
        $this->fileManager = new Dpr_FileManagerService();
        $this->ratingService = new Dpr_RatingService();
        $this->rating = new Dpr_RatingService();

        if (!session_id()) session_start();
    }

    public function __call($methodName, $args)
    {
        $this->_helper->getHelper('layout')->disableLayout();
        try{
            parent::__call($methodName, $args);
        }catch (Zend_Controller_Action_Exception $exception){
            if ($exception->getCode() == 404){
                $this->renderScript('error/404.phtml');
                $this->view->message = ["Page Not Found"];
            }elseif ($exception->getCode() == 500){
                $this->renderScript('error/500.phtml');
                $this->view->message = "Server Error";
            }
        }
    }

    //localhost/admin
    public function indexAction()
    {
        $this->view->title = "Daftar Pengguna";
        //$this->_helper->getHelper('layout')->disableLayout();
        $penggunas = $this->pengguna->getAllData();

        $counter = 1;
        foreach ($penggunas as $pengguna){
            $perans = $this->peran->getAllDataByIdPengguna($pengguna->id);
            $listPeran = null;
            if ($perans && is_array($perans)){
                $counter1 = 0;
                foreach ($perans as $peran){
                    $listPeran[$counter1] = [
                        'id' => $peran->id_peran,
                        'peran' => $this->listPeran->findById($peran->id_peran)->getNamaPeran()
                    ];
                    $counter1++;
                }
            }
            $result[$counter] = [
                'id_pengguna' => $pengguna->id,
                'nama' => $pengguna->nama_lengkap,
                'status' => $pengguna->status ? 'Aktif' : 'Nonaktif',
                'list_peran' => $listPeran
            ];

            $counter++;
        }

        $this->view->data = $result;
    }

    //localhost/admin/detail/id/$id
    public function detailAction()
    {
        $this->view->title = 'Detail Pengguna';

        try{
            $id = $this->getRequest()->getParam('id');

            $pengguna = $this->pengguna->findById($id);

            if (!$pengguna){
                throw new Exception("Data Tidak Ditemukan");
            }

            $listPeran = null;
            $perans = $this->peran->getAllDataByIdPengguna($id);
            if ($perans && is_array($perans)){
                $counter1 = 0;
                foreach ($perans as $peran){
                    $listPeran[$counter1] = [
                        'id' => $peran->id,
                        'id_peran' => $peran->id_peran,
                        'peran' => $this->listPeran->findById($peran->id_peran)->getNamaPeran()
                    ];
                    $counter1++;
                }
            }

            $result = [
                'id_pengguna' => $pengguna->getId(),
                'nama' => $pengguna->getNamaLengkap(),
                'status' => $pengguna->getStatus() ? 'Aktif' : 'Nonaktif',
                'list+peran' => $listPeran,
                'list_peran_pilihan' => $this->peran->getPeranLeftByIdPengguna($pengguna->getId())
            ];
            if ($pesan = $this->getRequest()->getParam('pesan')) {
                $this->view->pesan = $pesan;
            }
            // var_dump($result);
            // die;
            $this->view->data = $result;

        }catch (Exception $exception){
            $this->view->error = $exception->getMessage();
        }
    }

    //localhost/admin/delete-peran/id_pengguna/$id_pengguna/id_peran/$id_peran
    public function deletePeranAction()
    {
        $idPengguna = $this->getRequest()->getParam('id_pengguna');
        $idPeran = $this->getRequest()->getParam('id_peran');

        $_SESSION['flash'] = ucwords($this->peran->delete($idPeran));

        $this->_redirect("/admin/detail/id/$idPengguna");
    }

    /**
     * method : POST
     * action : /admin/tambah-peran
     * isi : peran[], id_pengguna (hidden)
    */
    //localhost/tambah-peran
    public function tambahPeranAction()
    {
        if ($this->getRequest()->isPost()) {
            $perans = $this->_getParam('peran');
            $idPengguna = $this->_getParam('id_pengguna');

            $request = new PeranAddDataRequest();
            $request->setIdPengguna($idPengguna);
            //  var_dump($perans);
            //  var_dump($idPengguna);
            //  die;
            foreach ($perans as $peran){
                $request->setIdPeran($peran);
                $this->peran->addData($request);
            }

            $_SESSION['flash'] = "Berhasil Menambahkan Peran";

            $this->_redirect("/admin/detail/id/$idPengguna");
        }
    }

    //localhost/admin/daftar-petugas
    public function daftarPetugasAction()
    {
        $this->view->title = 'Daftar Petugas';

        $this->_helper->viewRenderer->setRender('daftar-petugas');

        try {
            //Mengambil semua data petugas dari table rating
            $datas = $this->pengguna->getPeranStelaForDaftarPetugas();

            $counter = 0;
            foreach ($datas as $data){
                $rating = $this->tiketPetugas->getRatingByIdPetugas($data['id']);

                $result[$counter] = [
                    'id' => $data['id'],
                    'nama' => $data['nama'],
                    'peran' => $data['peran'],
                    'rating' => $rating['rating'],
                    'jumlah_tiket' => $rating['jumlah_tiket'],
                    'jumlah_tiket_nilai' => $rating['jumlah_tiket_nilai'] ? $rating['jumlah_tiket_nilai'] : 0,
                    'status' => $this->pengguna->findById($data['id'])->getStatus() == 0 ? "Tidak Aktif" : "Aktif",
                ];

                $counter++;
            }
            if ($pesan = $this->getRequest()->getParam('pesan')) {
                $this->view->pesan = $pesan;
            }

            $this->view->data = $result;
        } catch (Exception $exception) {
            $this->view->error = $exception->getMessage();
        }
    }

    // detail petugas
    // /admin/detail-petugas/id/{id_petugas}
    public function detailPetugasAction()
    {
        $this->view->title = 'Detail Petugas';

        $this->_helper->viewRenderer->setRender('daftar-petugas-tiket');

        $idPetugas = $this->getRequest()->getParam('id');
        try {
            $petugas = $this->pengguna->findById($idPetugas);
            $rating = $this->tiketPetugas->getRatingByIdPetugas($idPetugas);
            $data = [
                'id' => $idPetugas,
                'nama' => $petugas->getNamaLengkap(),
                'jumlah_tiket' => $rating['jumlah_tiket'],
                'jumlah_tiket_nilai' => $rating['jumlah_tiket_nilai'] ? $rating['jumlah_tiket_nilai'] : 0,
                'rating' => $rating['rating'],
                'status' => $petugas->getStatus() ? "Aktif" : "Tidak Aktif",
                'peran' => $this->peran->getPeranStelaOnly($idPetugas),
                'tiket' => []
            ];
            // get tikets dari table tiket petugas
            if ($this->tiketPetugas->getAllDataByIdPetugas($idPetugas))
                foreach ($this->tiketPetugas->getAllDataByIdPetugas($idPetugas) as $v)
                {
                    $tiket = $this->tiket->findById($v->id_tiket);
                    array_push($data['tiket'],
                        [
                            'id_tiket' => $tiket->getId(),
                            'nomor_tiket' => $tiket->getNoTiket(),
                            'tanggal' => $tiket->getTanggalInput(),
                            'rating' => $tiket->getRating(),
                            'id_status_tiket' => $tiket->getIdStatusTiket()
                        ]);
                }
                if ($pesan = $this->getRequest()->getParam('pesan')) {
                    $this->view->pesan = $pesan;
                }
            $this->view->data = $data;
            //var_dump($data['tiket']);
        } catch (Exception $e) {
            $this->view->error = $e->getMessage();
            var_dump($e->getMessage());
        }
    }

    //Tambah Pengguna
    // /admin/tambah-pengguna
    public function tambahPenggunaAction()
    {
        $this->view->title = 'Tambah Pengguna';
        //Ngasih list peran tok
       // $this->view->list_peran = $this->peran->getPeranLeftByIdPengguna();
         $this->view->list_peran = $this->listPeran->getAllData();
         $this->view->list_programmer = [
             'internal' => 'Internal',
             'external' => 'External'
         ];
        //var_dump($this->list_peran);
        
    }

    //proses tambah pengguna
    /**
     * method : post
     * action : /admin/proses-tambah-pengguna
     * isi : nama_lengkap, username, email, password, kd_departemen, bagian, gedung, unit_kerja, ruangan, lantai, telepon, hp, profile(upload gambar), peran[] (optional)
    */
    public function prosesTambahPenggunaAction()
    {
        if ($this->getRequest()->isPost()) {

            //Ngambil peran kalo ada
            $peran = $this->_getParam('peran');
            $request = new PenggunaAddDataRequest();
            $request->setNamaLengkap($this->_getParam('nama_lengkap'));
            $request->setUsername($this->_getParam('username'));
            $request->setEmail($this->_getParam('email'));
            $request->setPassword(md5($this->_getParam('password')));
            $request->setKdDepartemen($this->_getParam('kd_departemen'));
            $request->setBagian($this->_getParam('bagian'));
            $request->setGedung($this->_getParam('gedung'));
            $request->setUnitKerja($this->_getParam('unit_kerja'));
            $request->setRuangan($this->_getParam('ruangan'));
            $request->setLantai($this->_getParam('lantai'));
            $request->setTelepon($this->_getParam('telepon'));
            $request->setHp($this->_getParam('hp'));

            $programmer = $this->_getParam('programmer');

            try{
                if ($_FILES['profile']) {
                    $data = $this->fileManager->storeProfile($_FILES['profile']);
                    $request->setProfile($data['file_name']);
                }
            }catch (Exception $exception){
                $this->view->error = $exception->getMessage();
            }

            $response = $this->pengguna->addData($request);

            $status = false;
            //Check apakah ada array peran
            if (isset($peran)){
                //Kalo ada dibuat object buat nambahin perannya
                $request = new PeranAddDataRequest();
                $request->setIdPengguna($response->getId());
                foreach ($peran as $per){
                    if ($per == 3) $status = true;
                    $request->setIdPeran($per);
                    $this->peran->addData($request);
                }
            }

            $userInput = Zend_Auth::getInstance()->getIdentity()->nama_lengkap;

            if ($programmer == 'external' && $status)
            {
                (new Dpr_ProgrammerExternalService())->addData($response->getId(), $response->getNamaLengkap(), $userInput);
            }

            $_SESSION['flash'] = "Berhasil Tambah Pengguna";

            //Redirect ke detail aja lah ya kalo dia udah berhasil nambahin pengguna
            $this->_redirect("/admin/detail/id/{$response->getId()}");
        }
    }

    // action ke /admin/beri-rating
    public function beriRatingAction()
    {
        $this->_helper->getHelper('layout')->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        // method post
        if ($this->getRequest()->isPost()) {
            // get payload
            $idTiket = $this->getRequest()->getParam('id_tiket');
            $rating = $this->getRequest()->getParam('rating');
            $idPetugas = $this->getRequest()->getParam('id_petugas');
            // get info tiket
            $tiket = $this->tiket->findById($idTiket);
            // get user input from session
            $userInput = Zend_Auth::getInstance()->getIdentity()->username;

            // update rating table tiket
            $this->ratingService->updateRatingTiket($idTiket, $rating, $userInput);

            // get petugas yang ngerjain tiket
            $petugas = $this->tiketPetugas->getAllDataByIdTiket($idTiket);

            // kalo ada petugasnya kasih rating ke petugasnya bang,biar semangat
            if ($petugas)
                foreach ($petugas as $v) {
                    // kasih rating ke petugas yang ngerjain tiket,kalo ngasih hati buat kamu aja bukan petugas :)
                    $this->ratingService->addToPetugas($v->id_petugas, $rating, $userInput);
                    // selain kirim rating ke petugas,kasih juga notifikasi biar ngeh kalo udah dikasih notif
                    // tambah seneng juga kan muehehe
                    // $this->notifikasiService->sendTo($v->id_petugas, $tiket->getNoTiket(), "Pengguna sudah memberikan rating!");
                }

            $_SESSION['flash'] = "Berhasil Beri Rating";

            // kirim notifikasi ke semua service desk
            // $this->notifikasiService->sendToAllServiceDesk($tiket->getNoTiket(), "Pengguna sudah memberkan rating!");
            // redirect ke datail petugas lagi bang
            $this->getResponse()->setRedirect('/admin/detail-petugas/id/' . $idPetugas);
        }
    }


    //localhost/admin/delete-pengguna/id/$id_pengguna
    public function deletePenggunaAction()
    {
        $idPengguna = $this->_getParam('id');

        $pesan = $this->pengguna->deleteData($idPengguna);

        $_SESSION['flash'] = ucwords($pesan);

        $this->_redirect('/admin/index');
    }
}