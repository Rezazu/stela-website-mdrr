<?php

require_once APPLICATION_PATH . '/dto/Tiket/TiketAddDataRequest.php';

class TiketController extends Zend_Controller_Action
{

    public function init()
    {
        // authorizaton all allowed
        $this->_helper->_acl->allow();
        // loginnya ilangin
        $this->_helper->_acl->needAuth();
    }

    public function preDispatch()
    {
        // define model want to test
        $this->tiket = new Dpr_TiketService();
        $this->tahapanService = new Dpr_TahapanService();
        $this->tiketPetugasService = new Dpr_TiketPetugasService();
        $this->dokumenLampiran = new Dpr_DokumenLampiranService();
        $this->statusTiket = new Dpr_StatusTiketService();
        $this->timProgrammer = new Dpr_TimProgrammerService();
        $this->logService = new Dpr_LogService();
        $this->fileManager = new Dpr_FileManagerService();
        $this->ratingService = new Dpr_RatingService();
        $this->notifikasiService = new Dpr_NotifikasiService();
        $this->mailerService = new Dpr_MailerService();

        if (!session_id()) session_start();
    }

    public function __call($methodName, $args)
    {
        $this->_helper->getHelper('layout')->disableLayout();
        try {
            parent::__call($methodName, $args);
        } catch (Zend_Controller_Action_Exception $exception) {
            if ($exception->getCode() == 404) {
                $this->renderScript('error/404.phtml');
                $this->view->message = ["Page Not Found"];
            } elseif ($exception->getCode() == 500) {
                $this->renderScript('error/500.phtml');
                $this->view->message = "Server Error";
            }
        }
    }

    //localhost/tiket
    public function indexAction()
    {
        $this->view->title = 'Buat Tiket';

        if ($this->ratingService->checkRating(Zend_Auth::getInstance()->getIdentity()->id)) {
            $_SESSION['flash_error'] = "Berikan Rating Terlebih Dahulu";
            $this->_redirect('/riwayat');
        }
        // $this->_helper->getHelper('layout')->disableLayout();

        $data = [
            'nama' => Zend_Auth::getInstance()->getIdentity()->nama_lengkap,
            'bagian' => Zend_Auth::getInstance()->getIdentity()->bagian,
            'unit_kerja' => Zend_Auth::getInstance()->getIdentity()->unit_kerja,
            'hp' => Zend_Auth::getInstance()->getIdentity()->hp,
            'email' => Zend_Auth::getInstance()->getIdentity()->email,
            'gedung' => Zend_Auth::getInstance()->getIdentity()->gedung,
            'lantai' => Zend_Auth::getInstance()->getIdentity()->lantai,
            'ruangan' => Zend_Auth::getInstance()->getIdentity()->ruangan
        ];

        $this->view->data = $data;
    }

    //localhost/tiket/success
    public function successAction()
    {

        // $this->_helper->viewRenderer->setRender('success');

        if ($this->getRequest()->isPost()) {
            try {
                
                $request = new TiketAddDataRequest();

                $request->setIdVia(5);

                $nama = Zend_Auth::getInstance()->getIdentity()->nama_lengkap;
                // $nama = $this->_getParam('nama');

                $request->setNamaPelapor($nama);
                $request->setBagianPelapor(Zend_Auth::getInstance()->getIdentity()->bagian);
                $request->setUnitKerjaPelapor(Zend_Auth::getInstance()->getIdentity()->unit_kerja);
                $request->setGedungPelapor($this->_getParam('gedung'));
                $request->setLantaiPelapor($this->_getParam('lantai'));
                $request->setRuanganPelapor($this->_getParam('ruangan'));
                $request->setKeterangan($this->_getParam('keterangan'));
                $request->setUserInput($nama);
                $request->setUserUpdate($nama);

                $request->setIdPelapor(Zend_Auth::getInstance()->getIdentity()->id);
                // $request->setIdPelapor($this->_getParam('id_pelapor'));

                $request->setTeleponPelapor(Zend_Auth::getInstance()->getIdentity()->telepon);
                // $request->setTeleponPelapor($this->_getParam('telepon_pelapor'));

                $request->setHpPelapor(Zend_Auth::getInstance()->getIdentity()->hp);
                // $request->setHpPelapor($this->_getParam('hp_pelapor'));

                $request->setEmailPelapor(Zend_Auth::getInstance()->getIdentity()->email);
                // $request->setEmailPelapor($this->_getParam('email_pelapor'));

                $tiket = $this->tiket->addData($request);

                //Masukin data ke log
                $request = new LogRequest();
                $request->setIdTiket($tiket->getId());
                $request->setKeterangan("{$tiket->getNamaPelapor()} membuat tiket baru");
                $request->setPengguna($tiket->getNamaPelapor());
                $request->setIdPengguna($tiket->getIdPelapor());
                $request->setIdVia($tiket->getIdVia());
                $request->setIdStatusTiket($tiket->getIdStatusTiket());
                $request->setIdStatusTiketInternal($tiket->getIdStatusTiketInternal());

                $this->logService->addLogData($request);

                if (!empty($_FILES['dokumen']['name'][0])) {
                    //Memanggil function untuk menyimpan lebih dari 1 file
                    $this->dokumenLampiran->addMultipleFile($_FILES['dokumen'], $tiket);
                }

                // kalo sukses buat tiket, kirim notif dong bang ke semua service desk
                $this->notifikasiService->sendToAllServiceDesk($tiket->getNoTiket(), "Permintaan baru!");

                // kirim email
                $this->mailerService->berhasilBuatTiket(Zend_Auth::getInstance()->getIdentity()->id, $tiket->getNoTiket());
                $this->_redirect('/tiket/sukses/no_tiket/' . $tiket->getNoTiket() . '/id/' . $tiket->getId());

                // $this->view->no_tiket = $tiket->getNoTiket();
            } catch (Exception $exception) {
                $_SESSION['flash_error'] = $exception->getMessage();
            }
        }
    }

    //localhost/tiket/sukses
    public function suksesAction()
    {
        $this->view->title = 'Kode Tiket';
        $this->_helper->viewRenderer->setRender('succes');

        $noTiket = $this->getRequest()->getParam('no_tiket');
        $id = $this->getRequest()->getParam('id');

        $this->view->no_tiket = $noTiket;
        $this->view->id = $id;
    }

    //localhost/tiket/status?no_tiket atau localhost/tiket/status/:id
    public function statusAction()
    {
        $this->view->title = 'Lacak Status';
        //$this->_helper->getHelper('layout')->disableLayout();

        try {
            $id = $this->getRequest()->getParam('id');
            if (isset($id)) {
                $tiket = $this->tiket->findById($id);
                if ($tiket == null) {
                    //Kalo tiketnya ga ketemu bakal di throw exception
                    throw new Exception("Permintaan Tidak Ditemukan");
                }
                $noTiket = $tiket->getNoTiket();
            } else {
                $noTiket = $this->_getParam('no_tiket');
                $tiket = $this->tiket->findByNoTiket($noTiket);
                if ($tiket == null) {
                    //Kalo tiketnya ga ketemu bakal di throw exception
                    throw new Exception("Permintaan Tidak Ditemukan");
                }
                $id = $tiket->getId();
            }

            $datas = $this->tiket->findByNoTiket($noTiket);

            //Misal datanya gada atau statusnya itu bukan satu maka akan di throw exception
            if ($datas == null || $datas->getStatus() == 9) {
                throw new Exception("Permintaan Tidak Ditemukan");
            }

            //Data yang pasti dikirim ke view
            $result = [
                'id' => $datas->getId(),
                'no_tiket' => $noTiket,
                'status_tiket' => $this->statusTiket->getQueryData($datas->getIdStatusTiket())->status_tiket,
                'id_status_tiket' => $datas->getIdStatusTiket(),
                'nama_pelapor' => $datas->getNamaPelapor(),
                'id_pelapor' => $datas->getIdPelapor(),
                'bagian' => $datas->getBagianPelapor(),
                'keterangan' => $datas->getKeterangan(),
                'unit_kerja' => $datas->getUnitKerjaPelapor(),
                'gedung' => $datas->getGedungPelapor(),
                'lantai' => $datas->getLantaiPelapor(),
                'ruangan' => $datas->getRuanganPelapor(),
                'hp' => $datas->getHpPelapor(),
                'email' => $datas->getEmailPelapor(),
                'solusi' => $datas->getSolusi(),
            ];

            //Memanggil function untuk menadapatkan list dokumen lampiran
            $result['dokumen_lampiran'] = $this->dokumenLampiran->getListDokumenLampiran($id);

            if ($datas->getIdSubKategori() == null) {
                //Kalo belum dikategorisasiin dia ke view status
                $this->_helper->viewRenderer->setRender('status');

                //Nambahin pesan di resultnya kalo belum dikateogrisasiin
                $result['pesan'] = 'Masih Dalam Proses Review';

                $this->view->result = $result;
            } else {
                //Menambahkan data id_sub_kategori ke result
                $result['id_sub_kategori'] = $datas->getIdSubKategori();

                if ($datas->getIdSubKategori() == 2) {
                    //Kalo sub kategorinya infra atau 2, dia ke view status-infra
                    $this->_helper->viewRenderer->setRender('status');

                    //Ngambil list petugas menggunaan function getListPetugasStelaAktif
                    $listPetugas = $this->tiketPetugasService->getListPetugasStelaAktif($id);

                    //Memasukkan data list petugas ke result jika ada, kalau tidak maka akan diisi null
                    $result['list_petugas'] = isset($listPetugas) ? $listPetugas : null;

                    $this->view->result = $result;
                } elseif ($datas->getIdSubKategori() == 1) {
                    //Kalo dia sub kategorinya singrus atau 1, dia ke view status-singrus
                    $this->_helper->viewRenderer->setRender('status-singa-rusia-perencanaan');

                    //Memberikan Keterangan revisi yang diberikan operator jika status revisi true/1
                    if ($datas->getStatusRevisi() == 'Dalam Revisi') {
                        $result['pesan_revisi'] = $datas->getKeteranganRevisi();
                    }

                    //Mencari tahapan menggunakan function getTahapan
                    $tahapan = $this->tahapanService->getTahapan($tiket);

                    /*
                     * Memasukkan data tahapan ke result
                     * Data berisikan sampe tahapan mana tiket itu dikerjakan
                     * Kalo belum dikerjain bakal keluar tahapan permohonan aja
                     * */
                    $result['tahapan'] = $tahapan[0];

                    /*
                     * Memasukkan data detail dari semua todo list ke result
                     * Data Berisikan List sub tahapan beserta status, dan juga list todolist beserta dokumen paling terakhir
                     * */
                    $result['detail'] = isset($tahapan[1]) ? $tahapan[1] : null;

                    //Mencari data tim programmer
                    $timProgrammer = $this->timProgrammer->getTimProgrammer($datas->getIdTimProgrammer());

                    if ($datas->getStatusRevisi() == 'Dalam Revisi') {
                        $result['keterangan_revisi'] = $datas->getKeteranganRevisi() == null ? null : $datas->getKeteranganRevisi();
                    }

                    //Memasukkan data tim programmer
                    $result['tim_programmer'] = $timProgrammer;

                    $this->view->result = $result;
                }
            }
        } catch (Exception $exception) {
            //Kalo ada error dia masuknya ke view status
            $this->_helper->viewRenderer->setRender('status');
            $this->view->error = $exception->getMessage();
            // var_dump($exception->getMessage());
        }
    }

    //Ini tombol hapus dokumen
    //localhost/tiket/delete-dokumen/id/:id/id_dokumen/:id_dokumen
    public function deleteDokumenAction()
    {
        try {
            $id = $this->getRequest()->getParam('id');
            $idDokumen = $this->getRequest()->getParam('id_dokumen');

            $dokumen = $this->dokumenLampiran->findById($idDokumen);

            $this->dokumenLampiran->deleteQueryData($idDokumen);
            //            $this->fileManager->deleteDokumenLampiran($dokumen->image_name);

            //Kalo pake ajax nanti ini gaperlu redirect
            $this->_redirect('/tiket/status/id/' . $id);
        } catch (Exception $exception) {
            $_SESSION['flash_error'] = $exception->getMessage();
        }
    }

    //Ini tombol upload dokumen di singrus
    //localhost/tiket/upload-dokumen
    public function uploadDokumenAction()
    {
        try {
            if ($this->getRequest()->isPost()) {

                $id = $this->getRequest()->getParam('id');
                $userUpdate = Zend_Auth::getInstance()->getIdentity()->nama_lengkap;
                // $userUpdate = $this->getRequest()->getParam('user_update');

                $tiket = $this->tiket->findById($id);

                if ($tiket == null) {
                    throw new Exception("Data Tidak Ditemukan");
                }

                //Buat object log
                $request = new LogRequest();
                $request->setIdTiket($tiket->getId());
                $request->setPengguna($userUpdate);
                $request->setIdStatusTiket($tiket->getIdStatusTiket());
                $request->setIdStatusTiketInternal($tiket->getIdStatusTiketInternal());

                if ($tiket->getStatusRevisi() == "Dalam Revisi") {
                    $this->tiket->updateUserSingrusRevisi($id, $userUpdate);
                    $this->notifikasiService->sendToAllOperator(
                        $tiket->getNoTiket(),
                        "Permintaan dengan no tiket {$tiket->getNoTiket()} sudah direvisi"
                    );

                    //Kirim ke log dong bwang
                    $request->setKeterangan("Permintaan sudah direvisi");
                    $this->logService->addLogData($request);
                }

                if (isset($_FILES['dokumen'])) {
                    //Memanggil function untuk menyimpan lebih dari 1 file
                    $this->dokumenLampiran->addMultipleFile($_FILES['dokumen'], $tiket, $tiket->getStatusRevisi());
                }

                //Kalo pake ajax ga perlu redirect
                $this->_redirect('/tiket/status/id/' . $id);
            }
        } catch (Exception $exception) {
            $this->view->error = $exception->getMessage();
        }
    }

    // route untuk beri rating tiket
    // method POST
    /**
     * Payload :
     * id_tiket
     * rating
     */
    // action ke localhost/tiket/beri-rating
    public function beriRatingAction()
    {
        $this->_helper->getHelper('layout')->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        // method post
        if ($this->getRequest()->isPost()) {
            // get payload
            $idTiket = $this->getRequest()->getParam('id_tiket');
            $rating = $this->getRequest()->getParam('rating');
            // get info tiket
            $tiket = $this->tiket->findById($idTiket);
            // get user input from session
            $userInput = Zend_Auth::getInstance()->getIdentity()->nama_lengkap;

            // update rating table tiket
            $this->ratingService->updateRatingTiket($idTiket, $rating, $userInput);

            // get petugas yang ngerjain tiket
            $petugas = $this->tiketPetugasService->getAllDataByIdTiket($idTiket);

            // kalo ada petugasnya kasih rating ke petugasnya bang,biar semangat
            if ($petugas)
                foreach ($petugas as $v) {
                    // kasih rating ke petugas yang ngerjain tiket,kalo ngasih hati buat kamu aja bukan petugas :)
                    $this->ratingService->addToPetugas($v->id_petugas, $rating, $userInput);
                    // selain kirim rating ke petugas,kasih juga notifikasi biar ngeh kalo udah dikasih notif
                    // tambah seneng juga kan muehehe
                    $this->notifikasiService->sendTo($v->id_petugas, $tiket->getNoTiket(), "Pengguna sudah memberikan rating!");
                }

            // kirim notifikasi ke semua service desk
            $this->notifikasiService->sendToAllServiceDesk($tiket->getNoTiket(), "Pengguna sudah memberkan rating!");

            //Kirim ke log juga kalo udah ngasih rating di tiket ini
            $request = new LogRequest();
            $request->setIdTiket($idTiket);
            $request->setIdStatusTiketInternal($tiket->getIdStatusTiketInternal());
            $request->setIdStatusTiket($tiket->getIdStatusTiket());
            $request->setPengguna($userInput);
            $request->setKeterangan("$userInput memberikan rating");
            $this->logService->addLogData($request);

            $_SESSION['flash'] = "Berhasil Memberikan Rating";
            // redirect ke riwayat lagi bang
            $this->getResponse()->setRedirect('/riwayat');
        }
    }
}
