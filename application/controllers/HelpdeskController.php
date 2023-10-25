<?php

require_once APPLICATION_PATH . '/dto/TiketImageLaporan/TiketImageLaporanRequest.php';

class HelpdeskController extends Zend_Controller_Action
{

    public function init()
    {
        $this->_helper->_acl->allow('helpdesk');
        $this->_helper->_acl->allow('IT specialist');
        $this->_helper->layout->setLayout('layout_stela_helpdesk');
    }

    public function preDispatch()
    {
        $this->tiket = new Dpr_TiketService();
        $this->tiketPetugas = new Dpr_TiketPetugasService();
        $this->subKategori = new Dpr_SubKategoriService();
        $this->status = new Dpr_StatusTiketService();
        $this->pengguna = new Dpr_PenggunaService();
        $this->dateFormat = new Dpr_Controller_Action_Helper_YmdToIndo();
        $this->dokumenLampiran = new Dpr_DokumenLampiranService();
        $this->tiketImageLaporan = new Dpr_TiketImageLaporanService();
        $this->fileManager = new Dpr_FileManagerService();
        $this->logService = new Dpr_LogService();
        $this->urgensi = new Dpr_UrgensiService();
        $this->statusTiket = new Dpr_StatusTiketService();
        $this->notifikasiService = new Dpr_NotifikasiService();
        $this->rating = new Dpr_RatingService();
        $this->ratingService = new Dpr_RatingService();
        $this->peran = new Dpr_PeranService();

        if (!session_id()) session_start();

        $this->view->title;
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

    public function indexAction()
    {
        //Belum Ada, mungkin soon
        $this->_redirect('/helpdesk/permintaan');
    }
    
    public function profileAction()
    {
        $this->view->title = 'Profile Petugas';
        $idPetugas = Zend_Auth::getInstance()->getIdentity()->id;
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
                'peran' => $this->peran->getPeranStelaOnly($idPetugas)['peran'] ? $this->peran->getPeranStelaOnly($idPetugas)['peran'] : 'admin',
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
                    ]);
                }

            $this->view->data = $data;

        } catch (Exception $e) {
            $this->view->error = $e->getMessage();
        }
    
    }

    //localhost/helpdesk/permintaan
    public function permintaanAction()
    {
        $this->view->title = 'Daftar Permintaan';
       // $this->_helper->getHelper('layout')->disableLayout();

        try {
            //localhost/helpdesk/permintaan/detail/:nomor_tiket
            if ($this->getRequest()->getParam('detail')) {
                $this->detailPermintaan();
            } else {
                $this->_helper->viewRenderer->setRender('permintaan');

               $id = Zend_Auth::getInstance()->getIdentity()->id;
                // $id = $this->getRequest()->getParam('id');

                $petugas = $this->tiketPetugas->getAllDataByIdPetugas($id);


                if ($petugas == null) {
                    throw new Exception("Kamu Belum Ada Tugas");
                }

                $counter = 1;
                foreach ($petugas as $pet) {
                    $tiket = $this->tiket->findById($pet->id_tiket);

                    if ($tiket->getIdSubKategori() == 2) {
                        $listPetugas = $this->tiketPetugas->getAllDataByIdTiket($tiket->getId());
                        $list = null;

                        if ($listPetugas) {
                            $counter1 = 1;
                            foreach ($listPetugas as $tugas) {
                                $list[$counter1] = [
                                    'id' => $tugas->id_petugas,
                                    'nama' => $this->pengguna->findById($tugas->id_petugas)->getNamaLengkap()
                                ];

                                $counter1++;
                            }
                        }
                       // $urgensi = $this->urgensi->findById($d->id_urgensi)->nama;
                        $data[$counter] = [
                            'id_tiket' => $pet->id_tiket,
                            'tanggal' => $this->dateFormat->directAngkaFUll($tiket->getTanggalInput()),
                            'kategori' => $this->subKategori->getData($tiket->getIdSubKategori())->sub_kategori,
                            'status' => $this->status->getData($tiket->getIdStatusTiket())->id,
                           // 'urgensi' => isset($urgensi) ? $urgensi : null,
                            'petugas' => $list,
                        ];
                        $counter++;

                    }
                }

                if (!$data) {
                    throw new Exception("Kamu Belum Ada Tugas");
                }
                // var_dump($data);
                // die;
                $this->view->data = $data;
            }
        } catch (Exception $exception) {
            $this->_helper->viewRenderer->setRender('permintaan');

            $this->view->error = $exception->getMessage();
        }
    }

    //localhost/helpdesk/permintaan/detail/:nomor_tiket
    private function detailPermintaan()
    {
        $this->view->title = 'Detail Permintaan';
       // $this->_helper->getHelper('layout')->disableLayout();
        $this->_helper->viewRenderer->setRender('detail-permintaan');

        $id = $this->getRequest()->getParam('detail');

        if($this->tiket->findById($id)){
            $id = $id;
        }else{
            $id = $this->tiket->findByNoTiket($id)->getId();
        }

        $datas = $this->tiket->findById($id);
        //var_dump($datas);
        //die;

        if ($datas == null || $datas->getStatus() == 9 || $datas->getIdSubKategori() != 2) {
            throw new Exception("Data Tidak Ditemukan");
        }

        $data = [
            'id' => $id,
            'nama' => $datas->getNamaPelapor(),
            'jabatan' => $datas->getBagianPelapor(),
            'unit_kerja' => $datas->getUnitKerjaPelapor(),
            'gedung' => $datas->getGedungPelapor(),
            'lantai' => $datas->getLantaiPelapor(),
            'email' => $datas->getEmailPelapor(),
            'hp' => $datas->getHpPelapor(),
            'ruangan' => $datas->getRuanganPelapor(),
            'kategori' => $this->subKategori->getData($datas->getIdSubKategori())->sub_kategori,
            'keterangan' => $datas->getKeterangan(),
            'status' => $datas->getStatus(),
            'id_status_tiket' => $datas->getIdStatusTiket(),
            'status_tiket' => $this->status->getData($datas->getIdStatusTiket())->status_tiket,
            'id_status_tiket_internal' => $datas->getIdStatusTiketInternal(),
            'status_tiket_internal' => $this->statusTiket->getData($datas->getIdStatustiketInternal())->status_tiket,
            'laporan_petugas' => $this->tiketImageLaporan->getListDokumen($id)
        ];

        $data['dokumen_lampiran'] = $this->dokumenLampiran->getListDokumenLampiran($id);

        $this->view->data = $data;
    }

    public function updateSelesaiAction()
    {
        $this->_helper->getHelper('layout')->disableLayout();

        if ($this->getRequest()->isPost()) {

            $namaUpdate = Zend_Auth::getInstance()->getIdentity()->nama_lengkap;
            // $namaUpdate = $this->_getParam('nama');

            $id = $this->_getParam('id'); //Nanti ini ambil dari hidden form field
            $permasalahanAkhir = $this->_getParam('permasalahan_akhir');
            $solusi = $this->_getParam('solusi');

            $tiket = $this->tiket->findById($id);

            //Masukkin log guys
            $request = new LogRequest();
            $request->setIdTiket($tiket->getId());
            $request->setIdStatusTiketInternal(9);
            $request->setIdStatusTiket($tiket->getIdStatusTiket());
            $request->setPengguna($namaUpdate);
            $request->setKeterangan("$namaUpdate menyelesaikan tugas");

            $this->logService->addLogData($request);

            if (!empty($_FILES['dokumen']['name'][0])) {
                //Buat Objek untuk request
                $request = new TiketImageLaporanRequest();
                $request->setUserUpdate($namaUpdate);
                $request->setUserInput($namaUpdate);
                $request->setPermasalahanAkhir($permasalahanAkhir);
                $request->setSolusi($solusi);
                $request->setTipeLaporan('Solved');
                $request->setIdTiket($id);
                $request->setStatus(1);

                //Panggil function untuk memasukkan lebih dari 1 file ke tiket image laporan
                $this->tiketImageLaporan->addMultipleFile($_FILES['dokumen'], $request);
            // }

            // ubah status tiket internal menjadi solved = 9
            $this->tiket->updateStatusTiketInternal($id, 9, $namaUpdate);
            // get info tiket
            $tiket  = $this->tiket->findById($id);
            // kirim notifikasi ke semua service desk
            $this->notifikasiService->sendToAllServiceDesk($tiket->getNoTiket(), "Laporan selesai!");
            $_SESSION['flash'] = 'Berhasil Update Selesai';
            $this->_redirect('/helpdesk/permintaan/detail/' . $id);
        }
    
        }
    }
    public function updateTerkendalaAction()
    {
        $this->_helper->getHelper('layout')->disableLayout();

        if ($this->getRequest()->isPost()) {

            $namaUpdate = Zend_Auth::getInstance()->getIdentity()->nama_lengkap;
            // $namaUpdate = $this->_getParam('nama');

            $id = $this->_getParam('id'); //Nanti ini ambil dari hidden form field
            $permasalahanAkhir = $this->_getParam('permasalahan_akhir');
            $solusi = $this->_getParam('solusi');

            $tiket = $this->tiket->findById($id);

            //Masukkin log guys
            $request = new LogRequest();
            $request->setIdTiket($tiket->getId());
            $request->setIdStatusTiketInternal(2);
            $request->setIdStatusTiket($tiket->getIdStatusTiket());
            $request->setPengguna($namaUpdate);
            $request->setKeterangan("$namaUpdate mengalami kendala");

            $this->logService->addLogData($request);

            if (!empty($_FILES['dokumen']['name'][0])) {
                //Buat Objek untuk request
                $request = new TiketImageLaporanRequest();
                $request->setUserUpdate($namaUpdate);
                $request->setUserInput($namaUpdate);
                $request->setPermasalahanAkhir($permasalahanAkhir);
                $request->setSolusi($solusi);
                $request->setTipeLaporan('Terkendala');
                $request->setIdTiket($id);
                $request->setStatus(1);

                //Panggil function untuk memasukkan lebih dari 1 file ke tiket image laporan
                $this->tiketImageLaporan->addMultipleFile($_FILES['dokumen'], $request);
            }

            // ubah status tiket internal menjadi on hold = 2
            $this->tiket->updateStatusTiketInternal($id, 2, $namaUpdate);
            // get info tiket
            $tiket = $this->tiket->findById($id);

            // kirim notifikasi ke service desk
            $this->notifikasiService->sendToAllServiceDesk($tiket->getNoTiket(), "Laporan terkendala");
            $_SESSION['flash'] = 'Berhasil Update Terkendala';
            $this->_redirect('/helpdesk/permintaan/detail/' . $id);
        }
    }

    // button kerjakan
    /**
     * URL  =
     * Method POST
     * params
     * id_tiket
     * user_update
     */
    public function kerjakanAction()
    {
        // check request method post
        if ($this->getRequest()->isPost()) {
            $idTiket = $this->getRequest()->getParam('id_tiket');
            // ini kalo udah pake session
            $userUpdate = Zend_Auth::getInstance()->getIdentity()->username;

            // update status tiket internal menjadi assigned = 8
            $this->tiket->updateStatusTiketInternal($idTiket, 8, $userUpdate);
            // get info tiket
            $tiket = $this->tiket->findById($idTiket);
            // kirim notifikasi ke service desk
            $this->notifikasiService->sendToAllServiceDesk($tiket->getNoTiket(), "Petugas dalam proses pengerjaan");

            //Masukin log
            $request = new LogRequest();
            $request->setIdTiket($idTiket);
            $request->setPengguna($userUpdate);
            $request->setIdStatusTiketInternal($tiket->getIdStatusTiketInternal());
            $request->setIdStatusTiket($tiket->getIdStatusTiket());
            $request->setKeterangan("Petugas {$userUpdate} mengambil pekerjaan");
            $this->logService->addLogData($request);

            $_SESSION['flash'] = 'Berhasil Mengerjakan Permintaan';

            // balik lagi ke detail permintaan bang muehehehe
            $this->_redirect('/helpdesk/permintaan/detail/' . $idTiket);
        }
    }
}

