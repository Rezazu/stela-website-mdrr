<?php

use Carbon\Carbon;

require_once APPLICATION_PATH . '/dto/Tiket/TiketUpdateSubKategoriRequest.php';
require_once APPLICATION_PATH . '/dto/Tiket/TiketUpdateRevisiServiceDeskRequest.php';
require_once APPLICATION_PATH . '/dto/TiketImageLaporan/TiketImageLaporanRequest.php';

class ServiceDeskController extends Zend_Controller_Action
{
    public function init()
    {
        // $this->_helper->_acl->allow();
        $this->_helper->_acl->allow('servicedesk');
        //        $this->_helper->_acl->allow();
        // sett layout ke stela ke semua action controller
        $this->_helper->layout->setLayout('layout_stela');
    }

    public function preDispatch()
    {
        $this->subKategori = new Dpr_SubKategoriService();
        $this->via = new Dpr_ViaService();
        $this->urgensi = new Dpr_UrgensiService();
        $this->tiket = new Dpr_TiketService();
        $this->dokumenLampiran = new Dpr_DokumenLampiranService();
        $this->status = new Dpr_StatusTiketService();
        $this->dateFormat = new Dpr_Controller_Action_Helper_YmdToIndo();
        $this->tiketPetugas = new Dpr_TiketPetugasService();
        $this->timProgrammer = new Dpr_TimProgrammerService();
        $this->rating = new Dpr_RatingService();
        $this->pengguna = new Dpr_PenggunaService();
        $this->tiketImageLaporan = new Dpr_TiketImageLaporanService();
        $this->log = new Dpr_LogService();
        $this->notifikasiService = new Dpr_NotifikasiService();
        $this->listPeran = new Dpr_ListPeranService();
        $this->peran = new Dpr_PeranService();
        $this->ratingService = new Dpr_RatingService();
        $this->mailerService = new Dpr_MailerService();

        if (!session_id()) session_start();

        $this->view->title;
    }

    public function __call($methodName, $args)
    {
        $this->_helper->getHelper('layout')->disableLayout();
        try {
            parent::__call($methodName, $args);
        } catch (Zend_Controller_Action_Exception $exception) {
            if ($exception->getCode() == 404) {
                // $this->renderScript('error/404.phtml');
                // $this->view->message = ["Page Not Found"];
                return "Bang saya gk nemu halamannya";
            } elseif ($exception->getCode() == 500) {
                $this->renderScript('error/500.phtml');
                $this->view->message = "Server Error";
            }
        }
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
                'jumlah_tiket_nilai' => $rating['jumlah_tiket_nilai'],
                'rating' => $rating['rating'],
                'status' => $petugas->getStatus() ? "Aktif" : "Tidak Aktif",
                'peran' => $this->peran->getPeranStelaOnly($idPetugas) ? $this->peran->getPeranStelaOnly($idPetugas) : 'admin',
                'tiket' => []
            ];
            // get tikets dari table tiket petugas
            if ($this->tiketPetugas->getAllDataByIdPetugas($idPetugas))
                foreach ($this->tiketPetugas->getAllDataByIdPetugas($idPetugas) as $v) {
                    $tiket = $this->tiket->findById($v->id_tiket);
                    array_push(
                        $data['tiket'],
                        [
                            'id_tiket' => $tiket->getId(),
                            'nomor_tiket' => $tiket->getNoTiket(),
                            'tanggal' => $tiket->getTanggalInput(),
                            'rating' => $tiket->getRating(),
                        ]
                    );
                }

            $this->view->data = $data;
//            var_dump($data);
        } catch (Exception $e) {
            $this->view->error = $e->getMessage();
//            var_dump($e->getMessage())
        }
    }

    public function rekapKecepatanTiketAction()
    {
        $this->_helper->viewRenderer->setRender('rekap-kecepatan-tiket');

        $this->view->title = 'Rekap kecepatan Tiket';

        $data = $this->tiket->getRekapPengerjaan();

        $this->view->data = $data;
    }
    //localhost/servicedesk
    public function indexAction()
    {
        $this->view->title = 'Stela DPR RI';
        //$year = $this->tiket->recapCurrentYear();
        $data = [
            'top_petugas_bulan' => $this->rating->topPetugasTiapBulanTahunIni(),
            'status' => $this->tiket->recapByStatus(),
            'kategori' => $this->tiket->recapByKategori(),
            'urgensi' => $this->tiket->recapByUrgensi(),
            'via' => $this->tiket->recapByVia(),
            'top_petugas' => $this->rating->topPetugas(),
            'week' => $this->tiket->recapCurrentWeek(),
            'month' => $this->tiket->recapCurrentMonth(),
            'year' => $this->tiket->recapCurrentYear()
        ];

        // passing ke view
        $this->view->data = $data;
        //$this->view->year = $year;
        // var_dump($data);
        // die;
    }

    //localhost/servicedesk/rekap-petugas-tahun
    public function rekapPetugasTahunAction()
    {
        $this->view->title = 'Rekap Rating Petugas';

        $datas = $this->tiketPetugas->rekapRatingTahun();

        $data = [
            'list_tahun' => $datas[0],
            'list_data' => $datas[1]
        ];

        // var_dump($data);
        // die;
        $this->view->data = $data;
    }


    //localhost/servicedesk/statistik-tahun
    public function statistikTahunAction()
    {
        $this->view->title = 'Statistik Tahunan';
        //$this->_helper->viewRenderer->setRender('index');
        try {
            $datas = $this->tiket->rekapTiapTahun();

            $data = [
                'list_tahun' => $datas[1],
                'list_data' => $datas[0]
            ];
            $this->view->data = $data;
        } catch (Exception $exception) {
            $this->view->error = $exception->getMessage();
        }
    }

    //localhost:servicedesk/statistik-bulan
    public function statistikBulanAction()
    {
        $this->view->title = 'Statistik Bulanan';

        try {
            $datas = $this->tiket->rekapTiapBulan();

            $data = [
                'list_tahun' => $datas[0],
                'list_data' => $datas[1]
            ];

            $this->view->data = $data;
        } catch (Exception $exception) {
            $this->view->error = $exception->getMessage();
        }
    }

    //localhost/servicedesk/statistik-hari
    public function statistikHariAction()
    {
        $this->view->title = 'Statistik Harian';

        $datas = $this->tiket->rekapTiapHari();

        $data = [
            'list_tahun' => $datas[0],
            'list_bulan' => $datas[1],
            'list_data' => $datas[2]
        ];

        // var_dump($data);
        // die;
        $this->view->data = $data;
    }

    //localhost/servicedesk/buat-tiket
    public function buatTiketAction()
    {
        $this->view->title = 'Buat Tiket';

        $this->_helper->viewRenderer->setRender('buat-tiket');

        $listKategori = $this->subKategori->getListSubKategori();
        $listVia = $this->via->getListVia();
        $listUrgensi = $this->urgensi->getListUrgensi();

        $data = [
            'list_kategori' => $listKategori,
            'list_via' => $listVia,
            'list_urgensi' => $listUrgensi
        ];

        $this->view->data = $data;
    }

    //localhost/servicedesk/tiket-success
    public function tiketSuccessAction()
    {

        if ($this->getRequest()->isPost()) {
            try {

                $request = new TiketAddDataRequest();

                $kategori = $this->_getParam('list_kategori');
                $via = $this->_getParam('via');
                $urgensi = $this->_getParam('urgensi');

                $request->setIdVia($via);
                $request->setIdUrgensi($urgensi);

                //Kalo nanti pake session pake nama pembuat yang getIdentity

                $namaPembuat = Zend_Auth::getInstance()->getIdentity()->nama_lengkap;
                //$namaPembuat = $this->_getParam('nama_pembuat');

                $email = $this->_getParam('email_pelapor');

                $request->setNamaPelapor($this->_getParam('nama'));
                $request->setBagianPelapor($this->_getParam('jabatan'));
                $request->setUnitKerjaPelapor($this->_getParam('unit_kerja'));
                $request->setGedungPelapor($this->_getParam('gedung'));
                $request->setLantaiPelapor($this->_getParam('lantai'));
                $request->setRuanganPelapor($this->_getParam('ruangan'));
                $request->setKeterangan($this->_getParam('keterangan'));
                $request->setTeleponPelapor($this->_getParam('telepon_pelapor'));
                $request->setHpPelapor($this->_getParam('hp_pelapor'));
                $request->setEmailPelapor($email);
                $request->setIdSubKategori($kategori);
                $request->setUserInput($namaPembuat);
                $request->setUserUpdate($namaPembuat);

                $request->setIdPelapor(Zend_Auth::getInstance()->getIdentity()->id);

                //Di add datanya ke table tiket
                $tiket = $this->tiket->addData($request);

                //Masukin ke log juga dong walaupun yang bikin servicedesk
                $request = new LogRequest();
                $request->setIdTiket($tiket->getId());
                $request->setIdStatusTiket($tiket->getIdStatusTiket());
                $request->setIdStatusTiketInternal($tiket->getIdStatusTiketInternal());
                $request->setKeterangan("$namaPembuat membuatkan tiket baru");
                $request->setIdVia($via);
                $request->setIdUrgensi($urgensi);
                $request->setIdSubKategori($kategori);
                $request->setPengguna($namaPembuat);
                $this->log->addLogData($request);

                //Nanti di form bagian dokumen id nya harus "dokumen[]"
                //Kalo ada dokumen maka dimasukkan ke database dokumen lampiran
                if (!empty($_FILES['dokumen']['name'][0])) {
                    //Function untuk menyimpan dokumen
                    $this->dokumenLampiran->addMultipleFile($_FILES['dokumen'], $tiket);
                }

                // kirim notifikasi ke service desk bang
                $this->notifikasiService->sendToAllServiceDesk($tiket->getNoTiket(), "Permintaan baru!");

                //Kalo sub kategorinya singrus kasih notif ke semua verificator yak
                if ($kategori == 1) {
                    $this->notifikasiService->sendToAllOperator($tiket->getNoTiket(), "Permintaan baru!");
                }

                $this->tiket->updateStatusTiket($tiket->getId(), 1, $namaPembuat);
                $this->tiket->updateStatusTiketInternal($tiket->getId(), 1, $namaPembuat);

                $this->_redirect('/servicedesk/tiket-sukses/no_tiket/' . $tiket->getNoTiket());

                //Mengembalikan No Tiket
                //                $this->view->noTiket = $tiket->getNoTiket();
            } catch (Exception $exception) {
                $this->view->error = $exception->getMessage();
            }
        }
    }

    //localhost/servicedesk/tiket-sukses
    public function tiketSuksesAction()
    {
        $this->_helper->viewRenderer->setRender('tiket-success');

        $noTiket = $this->getRequest()->getParam('no_tiket');

        $this->view->no_tiket = $noTiket;
    }

    //localhost/servicedesk/daftar-petugas
    //    public function daftarPetugasAction()
    //    {
    //        $this->view->title = 'Daftar Petugas';
    //
    //        $this->_helper->viewRenderer->setRender('daftar-petugas');
    //
    //        try {
    //            //Mengambil semua data petugas dari table rating
    //            $datas = $this->pengguna->getPeranStela();
    //
    //            $counter = 0;
    //            foreach ($datas as $data){
    //                $rating = $this->tiketPetugas->getRatingByIdPetugas($data['id']);
    //
    //                $result[$counter] = [
    //                    'id' => $data['id'],
    //                    'nama' => $data['nama'],
    //                    'peran' => $data['peran'],
    //                    'rating' => $rating['rating'],
    //                    'jumlah_tiket' => $rating['jumlah_tiket'],
    //                    'jumlah_tiket_nilai' => $rating['jumlah_tiket_nilai'],
    //                    'status' => $this->pengguna->findById($data['id'])->getStatus() == 0 ? "Tidak Aktif" : "Aktif",
    //                ];
    //
    //                $counter++;
    //            }
    //
    //
    //            $this->view->data = $result;
    //        } catch (Exception $exception) {
    //            $this->view->error = $exception->getMessage();
    //        }
    //    }

    // /servicedesk/daftar-petugas
    public function daftarPetugasAction()
    {
        $this->view->title = 'Daftar Petugas';

        $this->_helper->viewRenderer->setRender('daftar-petugas');

        try {
            $datas = $this->pengguna->getPeranStelaForDaftarPetugas();

            $counter = 0;
            foreach ($datas as $data) {

                $result[$counter] = [
                    'id' => $data['id'],
                    'nama' => $data['nama'],
                    'peran' => $data['peran'],
                    'status' => $this->pengguna->findById($data['id'])->getStatus() == 0 ? "Tidak Aktif" : "Aktif",
                ];

                $counter++;
            }


            $this->view->data = $result;
        } catch (Exception $exception) {
            $this->view->error = $exception->getMessage();
        }
    }

    // route untuk beri rating tiket
    // method POST
    /**
     * Payload :
     * id_petugas
     * id_tiket
     * rating
     */
    // action ke /servicedesk/beri-rating
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
                    $this->notifikasiService->sendTo($v->id_petugas, $tiket->getNoTiket(), "Pengguna sudah memberikan rating!");
                }

            // kirim notifikasi ke semua service desk
            $this->notifikasiService->sendToAllServiceDesk($tiket->getNoTiket(), "Pengguna sudah memberkan rating!");
            // redirect ke datail petugas lagi bang
            $this->getResponse()->setRedirect('/servicedesk/detail-petugas/id/' . $idPetugas);
        }
    }

    //localhost/servicedesk/permintaan
    //Kurang Judul dan No HP dan keterangan : Udah Ya
    public function permintaanAction()
    {
        $this->view->title = 'Daftar Permintaan';

        //
        // $this->_helper->getHelper('layout')->layout('layout123');

        try {
            //localhost/servicedesk/permintaan/detail/:id_tiket
            if ($this->getRequest()->getParam('detail')) {
                $this->detailPermintaan();
            } elseif ($this->getRequest()->getParam('log')) {
                //localhost/servicedesk/permintaan/log/:id_tiket
                $this->logPermintaan();
            } elseif ($this->getRequest()->getParam('revisi')) {
                //localhost/servicedesk/permintaan/revisi/:id_tiket
                $this->revisiPermintaan();
            } else {
                //localhost/servicedesk/permintaan
                $this->_helper->viewRenderer->setRender('permintaan');

                //Mengambil semua data dari tiket
                $datas = $this->tiket->getAllData();

                //Kalo datanya null maka ditampilkan belum ada tiket
                if ($datas == null) {
                    throw new Exception("Belum Ada Tiket Permintaan");
                }

                //Variable untuk data
                $data = [];

                $count = 1;
                //Foreach untuk mengecek setiap tiket yang ada
                foreach ($datas as $d) {
                    if ($d->id_sub_kategori != null) {
                        //Jika sudah dikategorikan, maka akan diambil id nya dan dicari namanya
                        $kategori = $this->subKategori->getData($d->id_sub_kategori)->sub_kategori;
                        if ($d->id_sub_kategori == 1) {
                            $listPetugas = $this->timProgrammer->getListProgrammer($d->id_tim_programmer);
                        } elseif ($d->id_sub_kategori == 2) {
                            $listPetugas = $this->tiketPetugas->getListPetugasStelaAktif($d->id);
                        } else {
                            $listPetugas = null;
                        }
                    } else {
                        $kategori = null;
                        $listPetugas = null;
                    }

                    $data[$count] = [
                        'id' => $d->id,
                        'id_urgensi' => $d->id_urgensi,
                        'urgensi' => $this->urgensi->findById($d->id_urgensi)->nama,
                        'id_status_tiket' => $d->id_status_tiket,
                        'status_tiket' => $this->status->getQueryData($d->id_status_tiket)->status_tiket,
                        'id_status_tiket_internal' => $d->id_status_tiket_internal,
                        'status_tiket_internal' => $this->status->getQueryData($d->id_status_tiket_internal)->status_tiket,
                        'jam' => explode(' ', $d->tanggal_input)[1],
                        'tanggal_saja' => explode(' ', $d->tanggal_input)[0],
                        'no_tiket' => $d->no_tiket,
                        'tanggal' => $d->tanggal_input,
                        'kategori' => $kategori,
                        'list_petugas' => $listPetugas,
                        'keterangan' => $d->keterangan,
                        'no_hp' => $d->hp_pelapor
                    ];
                    $count++;
                }
                $this->view->data = $data;
            }
        } catch (Exception $exception) {
            $this->_helper->viewRenderer->setRender('permintaan');

            $this->view->error = $exception->getMessage();
        }
    }

    //localhost/servicedesk/permintaan/revisi/:id_tiket
    private function revisiPermintaan()
    {
        $this->view->title = 'Revisi Permintaan';

        $this->_helper->viewRenderer->setRender('revisi');

        $id = $this->getRequest()->getParam('revisi');

        $datas = $this->tiket->findById($id);

        if ($datas == null) {
            throw new Exception("Data Tidak DItemukan");
        }

        $data = [
            'id' => $datas->getId(),
            'no_tiket' => $datas->getNoTiket(),
            'id_via' => $datas->getIdVia(),
            'via' => $this->via->getData($datas->getIdVia())->via,
            'id_sub_kategori' => $datas->getIdSubKategori(),
            'sub_kategori' => ($datas->getIdSubKategori()) ?
                $this->subKategori->getData($datas->getIdSubKategori())->sub_kategori : null,
            'id_status_tiket' => $datas->getIdStatusTiket(),
            'status_tiket' => $this->status->getData($datas->getIdStatusTiket())->status_tiket,
            'id_status_tiket_internal' => $datas->getIdStatusTiketInternal(),
            'status_tiket_internal' => $this->status->getData($datas->getIdStatusTiketInternal())->status_tiket,
            'id_urgensi' => $datas->getIdUrgensi(),
            'urgensi' => $this->urgensi->findById($datas->getIdUrgensi())->nama,
            'id_pelapor' => $datas->getIdPelapor(),
            'pelapor' => $this->pengguna->findById($datas->getIdPelapor())->getNamaLengkap(),
            'unit_kerja' => $datas->getUnitKerjaPelapor(),
            'hp' => $datas->getHpPelapor(),
            'email' => $datas->getEmailPelapor(),
            'keterangan' => $datas->getKeterangan(),
            'bagian' => $datas->getBagianPelapor(),
            'gedung' => $datas->getGedungPelapor(),
            'lantai' => $datas->getLantaiPelapor(),
            'ruangan' => $datas->getRuanganPelapor(),
            'petugas_aktif' => $this->tiketPetugas->getListPetugasStelaAktif($datas->getId()),
            'list_urgensi' => $this->urgensi->getListUrgensi(),
            'list_sub_kategori' => $this->subKategori->getListSubKategori(),
            'list_status' => $this->status->getListStatus(),
            'list_eskalasi' => $this->pengguna->getPeranStela(),
            'list_via' => $this->via->getListVia(),
        ];

        if ($this->getRequest()->getParam('pesan')) {
            $this->view->pesan = $this->getRequest()->getParam('pesan');
        }

        $this->view->data = $data;
    }

    /**
     * Form
     * method = POST
     * Action = /servicedes/proses-revisi
     * isi : id, id_via, id_sub_kategori, id_urgensi, id_status_tiket, id_status_tiket_internal
     * note Buat eskalasi pake update eskalasi revisi sekarang bi, jadi pas kirim revisi dia ga masuk kesini tapi metodenya sama kayak eskalasi yang biasa
     */
    //localhost/servicedesk/proses-revisi
    public function prosesRevisiAction()
    {
        if ($this->getRequest()->isPost()) {

            //kalo dah pake session ini diganti bawahnya
            //            $userUpdate = $this->_getParam('user_update');
            $userUpdate = Zend_Auth::getInstance()->getIdentity()->nama_lengkap;

            $idTiket = $this->_getParam('id');
            $idVia = $this->_getParam('id_via');
            $idSubKategori = $this->_getParam('id_sub_kategori');
            $idUrgensi = $this->_getParam('id_urgensi');
            // $idStatusTiket = $this->_getParam('id_status_tiket');
            // $idStatusTiketInternal = $this->_getParam('id_status_tiket_internal');

            $tiket = $this->tiket->findById($idTiket);

            if ($idSubKategori != $tiket->getIdSubKategori()) {
                $this->tiket->updateStatusTiket($idTiket, 1, $userUpdate);
                $this->tiket->updateStatusTiketInternal($idTiket, 1, $userUpdate);
            }

            $tiket = $this->tiket->findById($idTiket);

            if ($idVia != $tiket->getIdVia() || $idUrgensi != $tiket->getIdUrgensi()) {
                $request = new LogRequest();
                $request->setIdTiket($tiket->getId());
                $request->setPengguna($userUpdate);
                $request->setIdStatusTiket($tiket->getIdStatusTiket());
                $request->setIdStatusTiketInternal($tiket->getIdStatusTiketInternal());
            }

            if ($idVia != $tiket->getIdVia()) {
                //Masukkin log
                $request->setKeterangan("Revisi via menjadi {$this->via->getData($idVia)->via}");
                $this->log->addLogData($request);
            }

            if ($idUrgensi != $tiket->getIdUrgensi()) {
                //Masukkin log
                $request->setKeterangan("Revisi urgensi menjadi {$this->urgensi->findById($idUrgensi)->nama}");
                $this->log->addLogData($request);
            }


            $request = new TiketUpdateRevisiServiceDeskRequest();
            $request->setId($idTiket);
            $request->setIdVia($idVia);
            $request->setIdSubKategori($idSubKategori);
            $request->setIdUrgensi($idUrgensi);
            $request->setUserUpdate($userUpdate);

            $this->tiket->updateRevisiServiceDesk($request);

            $tiket = $this->tiket->findById($idTiket);

            $_SESSION['flash'] = ucwords("Berhasil revisi permintaan {$tiket->getNoTiket()}");

            $this->_redirect('/servicedesk/permintaan/revisi/' . $idTiket);
        }
    }

    //button eskalasi revisi

    /**
     * FORM
     * method : POST
     * action : /servicedesk/update-eskalasi-revisi
     * isi : id(hidden), eskalasi[]
     */
    public function updateEskalasiRevisiAction()
    {
        $this->_helper->getHelper('layout')->disableLayout();

        if ($this->getRequest()->isPost()) {

            $id = $this->_getParam('id');
            $eskalasi = $this->_getParam('eskalasi');

            $nama = Zend_Auth::getInstance()->getIdentity()->nama_lengkap;

            $tiket = $this->tiket->findById($id);

            //Buat objct log
            $request = new LogRequest();
            $request->setIdTiket($id);
            $request->setPengguna($nama);
            $request->setIdStatusTiket($tiket->getIdStatusTiket());
            $request->setIdStatusTiketInternal($tiket->getIdStatusTiketInternal());

            foreach ($eskalasi as $lasi) {
                // assign petugas untuk eskalasi
                $this->tiketPetugas->addData($id, $lasi, $nama);

                //Masukkin log siapa ae yang di eskalasi
                $request->setKeterangan("Eskalasi petugas {$this->pengguna->findById($lasi)->getNamaLengkap()}");
                $this->log->addLogData($request);
            }

            $_SESSION['flash'] = ucwords("Berhasil menambahkan petugas");

            $this->_redirect('/servicedesk/permintaan/revisi/' . $id);
        }
    }

    //localhost/servicedesk/delete-eskalasi/id_tiket/:id_tiket/id_petugas/:id_petugas
    public function deleteEskalasiAction()
    {
        $id = $this->getRequest()->getParam('id_tiket');
        $id_petugas = $this->getRequest()->getParam('id_petugas');

        $tiket = $this->tiket->findById($id);

        //Masukkin log juga euyy
        $request = new LogRequest();
        $request->setIdTiket($id);
        $request->setIdStatusTiket($tiket->getIdStatusTiket());
        $request->setIdStatusTiketInternal($tiket->getIdStatusTiketInternal());
        $request->setKeterangan("Menghapus petugas {$this->pengguna->findById($id_petugas)->getNamaLengkap()}");
        $request->setPengguna(Zend_Auth::getInstance()->getIdentity()->nama_lengkap);
        $this->log->addLogData($request);

        $_SESSION['flash'] = ucwords($this->tiketPetugas->deleteEskalasiByIdPetugasAndIdTiket($id_petugas, $id));

        $this->_redirect('/servicedesk/permintaan/revisi/' . $id);
    }

    //localhost/servicedesk/permintaan/detail/:id_tiket
    private function detailPermintaan()
    {
        $this->view->title = 'Detail Permintaan';

        $this->_helper->viewRenderer->setRender('detail-permintaan');

        $id = $this->getRequest()->getParam('detail');

        if ($this->tiket->findById($id)) {
            $id = $id;
        } else {
            $id = $this->tiket->findByNoTiket($id)->getId();
        }

        $datas = $this->tiket->findById($id);
        if ($datas == null) {
            throw new Exception("Data Tidak Ditemukan");
        }

        $data = [
            'id' => $datas->getId(),
            'id_via' => $datas->getIdVia(),
            'via' => $this->via->getData($datas->getIdVia())->via,
            'id_status_tiket_internal' => $datas->getIdStatusTiketInternal(),
            'status_tiket_internal' => $this->status->getData($datas->getIdStatusTiketInternal())->status_tiket,
            'id_status_tiket' => $datas->getIdStatusTiket(),
            'status_tiket' => $this->status->getData($datas->getIdStatusTiket())->status_tiket,
            'status' => $datas->getStatus(),
            'id_sub_kategori' => $datas->getIdSubKategori(),
            'sub_kategori' => ($datas->getIdSubKategori()) ?
                $this->subKategori->getData($datas->getIdSubKategori())->sub_kategori : null,
            'id_urgensi' => $datas->getIdUrgensi(),
            'urgensi' => $this->urgensi->findById($datas->getIdUrgensi())->nama,
            'no_tiket' => $datas->getNoTiket(),
            'nama_pelapor' => $datas->getNamaPelapor(),
            'jabatan' => $datas->getBagianPelapor(),
            'unit_kerja' => $datas->getUnitKerjaPelapor(),
            'email_pelapor' => $datas->getEmailPelapor(),
            'bagian_pelapor' => $datas->getBagianPelapor(),
            'gedung' => $datas->getGedungPelapor(),
            'lantai' => $datas->getLantaiPelapor(),
            'ruangan' => $datas->getRuanganPelapor(),
            'keterangan' => $datas->getKeterangan(),
            'permasalahan_akhir' => $datas->getPermasalahanAkhir(),
            'solusi' => $datas->getSolusi(),
            'hp' => $datas->getHpPelapor(),
            'kategori' => $datas->getIdSubKategori(),
            'list_urgensi' => $this->urgensi->getListUrgensi(),
            'list_sub_kategori' => $this->subKategori->getListSubKategori(),
            'list_status' => $this->status->getListStatus(),
            'jam' => explode(' ', $datas->getTanggalInput())[1],
            'tanggal' => explode(' ', $datas->getTanggalInput())[0]

        ];


        $petugasAktif = $this->tiketPetugas->getListPetugasStelaAktif($id);

        $data['petugas_aktif'] = $petugasAktif;

        $eskalasi = $this->pengguna->getPeranStela();

        $data['list_eskalasi'] = $eskalasi;

        $data['dokumen_lampiran'] = $this->dokumenLampiran->getListDokumenLampiran($id);

        $data['laporan_petugas'] = $this->tiketImageLaporan->getListDokumen($id);


        //Ngirim pesan ke view kalo ada pesan
        if ($pesan = $this->getRequest()->getParam('pesan')) {
            $this->view->pesan = $pesan;
        }
        // var_dump($data);
        // die;
        $this->view->data = $data;
    }

    //localhost/servicedesk/permintaan/log/:id_tiket
    private function logPermintaan()
    {
        $this->view->title = 'Log';

        $this->_helper->viewRenderer->setRender('log-permintaan');

        $id = $this->getRequest()->getParam('log');

        try {
            $tiket = $this->tiket->findById($id);

            if (!$tiket) {
                throw new Exception("Belum Ada Data");
            }
            //Pasti dikirim ke log
            $data = [
                'id_tiket' => $tiket->getId(),
                'no_tiket' => $tiket->getNoTiket()
            ];

            //Memanggil function untuk mendapatkan log nya
            $data['list_log'] = $this->log->getAllLogServiceDesk($tiket);

            if ($data['list_log'] == null) {
                throw new Exception("Belum Ada Data");
            }

            $this->view->data = $data;
        } catch (Exception $exception) {
            $this->view->error = $exception->getMessage();
        }
    }

    //Button delete
    public function deleteAction()
    {

        $this->_helper->viewRenderer->setRender('permintaan');

        $id = $this->getRequest()->getParam('id');

        $nama = Zend_Auth::getInstance()->getIdentity()->nama_lengkap;
        //        $nama = $this->getRequest()->getParam('nama');

        try {
            $pesan = $this->tiket->softDelete($id, $nama);

            $this->view->pesan = $pesan;
        } catch (Exception $exception) {
            $this->view->error = $exception->getMessage();
        }
    }

    //button pilih kategori dan urgensi sekaligus
    //localhost/servicedesk/update-kategori
    public function updateKategoriAction()
    {
        if ($this->getRequest()->isPost()) {

            $id = $this->_getParam('id');
            $idSubKategori = $this->_getParam('id_sub_kategori');
            $idUrgensi = $this->_getParam('id_urgensi');

            $nama = Zend_Auth::getInstance()->getIdentity()->nama_lengkap;
            //            $nama = $this->_getParam('nama');

            $request = new TiketUpdateSubKategoriRequest();

            $request->setId($id);
            $request->setIdSubKategori($idSubKategori);
            $request->setIdUrgensi($idUrgensi);
            $request->setUserUpdate($nama);
            // var_dump($request);
            // die;
            $this->tiket->updateSubKategori($request);
            // get tiket info
            $tiket = $this->tiket->findById($id);
            // kasih notif ke user kalo tiketnya udah di open
            $this->notifikasiService->sendTo(
                $tiket->getIdPelapor(),
                $tiket->getNoTiket(),
                "Tiket akan segera kami proses, mohon ditunggu"
            );
            // kasih email ke user
            $this->mailerService->kategoriTiket($tiket->getIdPelapor(), $tiket->getNoTiket());
            // kirim notifikasi ke semua service desk
            $this->notifikasiService->sendToAllServiceDesk($tiket->getNoTiket(), "Tiket sudah dikategorikan!");

            if ($idSubKategori == 1) {
                $this->notifikasiService->sendToAllOperator($tiket->getNoTiket(), "Ada Permintaan Baru");
            }

            //Masukkin ke log gayn
            $request = new LogRequest();
            $request->setIdTiket($tiket->getId());
            $request->setPengguna($nama);
            $request->setIdSubKategori($idSubKategori);
            $request->setIdStatusTiketInternal($tiket->getIdStatusTiketInternal());
            $request->setIdStatusTiket($tiket->getIdStatusTiket());
            $request->setIdUrgensi($tiket->getIdUrgensi());

            //Log nyimpen kategori
            $request->setKeterangan(
                "Permintaan dikategorikan {$this->subKategori->getData($tiket->getIdSubKategori())->sub_kategori}"
            );
            $this->log->addLogData($request);

            //Log nyimpen urgensi
            $request->setKeterangan(
                "Urgensi permintaan {$this->urgensi->findById($tiket->getIdUrgensi())->nama}"
            );
            $this->log->addLogData($request);

            $_SESSION['flash'] = ucwords("Ubah kategori berhasil");
            $this->_redirect('/servicedesk/permintaan/detail/' . $id);
        }
    }

    //button ubah status
    public function updateStatusAction()
    {
        try {
            if ($this->getRequest()->isPost()) {

                $id = $this->_getParam('id');
                $idStatus = $this->_getParam('id_status');

                $nama = Zend_Auth::getInstance()->getIdentity()->nama_lengkap;
                //                $nama = $this->_getParam('nama');

                $this->tiket->updateStatusTiket($id, $idStatus, $nama);
                $this->tiket->updateStatusTiketInternal($id, $idStatus, $nama);

                // get info tiket
                $tiket = $this->tiket->findById($id);
                // kirim notifikasi ke semua service desk bang
                $this->notifikasiService->sendToAllServiceDesk($tiket->getNoTiket(), "Status tiket diperbarui!");

                $_SESSION['flash'] = ucwords("Ubah status berhasil");
                $this->_redirect('/servicedesk/permintaan/detail/' . $id);
            }
        } catch (Exception $th) {
            var_dump($th->getMessage());
            die;
        }
    }

    //button eskalasi
    public function updateEskalasiAction()
    {
        if ($this->getRequest()->isPost()) {

            $id = $this->_getParam('id');
            $eskalasi = $this->_getParam('eskalasi');

            $nama = Zend_Auth::getInstance()->getIdentity()->nama_lengkap;
            //            $nama = $this->_getParam('nama');

            // update status tiket internal menjadi 5 = on process
            $this->tiket->updateStatusTiketInternal($id, 5, $nama);

            // update status tiket user menjadi 5 = on process
            $this->tiket->updateStatusTiket($id, 5, $nama);
            // get info tiket
            $tiket = $this->tiket->findById($id);

            //Buat objct log
            $request = new LogRequest();
            $request->setIdTiket($id);
            $request->setPengguna($nama);
            $request->setIdStatusTiket($tiket->getIdStatusTiket());
            $request->setIdStatusTiketInternal($tiket->getIdStatusTiketInternal());

            foreach ($eskalasi as $lasi) {
                // assign petugas untuk eskalasi
                $this->tiketPetugas->addData($id, $lasi, $nama);
                // kirim notifikasi bang jangan lupa,kalo lupa berabe
                // bisa bisa gak dikerjain tuh tiket
                $this->notifikasiService->sendTo($lasi, $tiket->getNoTiket(), "Eskalasi baru!");
                //Masukkin log siapa ae yang di eskalasi
                $request->setKeterangan("Eskalasi petugas {$this->pengguna->findById($lasi)->getNamaLengkap()}");
                $this->log->addLogData($request);
            }
            // kirim notifikasi ke pelapor juga ey
            $this->notifikasiService->sendTo($tiket->getIdPelapor(), $tiket->getNoTiket(), "Tiket kamu sedang dalam proses");
            $_SESSION['flash'] = "Eskalasi Petugas Berhasil";
            $this->_redirect('/servicedesk/permintaan/detail/' . $id);
        }
    }

    //buat button finish ngubah status tiket ke 6 dan status internal menjadi 6
    public function updateSelesaiAction()
    {
        if ($this->getRequest()->isPost()) {

            $id = $this->_getParam('id');
            $nama = Zend_Auth::getInstance()->getIdentity()->nama_lengkap;
            $permasalahanAkhir = $this->_getParam('permasalahan_akhir');
            $solusi = $this->_getParam('solusi');
            $this->tiket->updateSelesai($id, $permasalahanAkhir, $solusi, $nama);

            // get info tiket
            $tiket = $this->tiket->findById($id);
            // kirim notifikasi ke semua service desk bang
            $this->notifikasiService->sendToAllServiceDesk($tiket->getNoTiket(), "Tiket selesai!");
            // kirim notifikasi ke user
            $this->notifikasiService->sendTo($tiket->getIdPelapor(), $tiket->getNoTiket(), "Tiket kamu sudah selesai,jangan lupa memberikan rating ya");
            // kirim email ke pelapor
            $this->mailerService->selesaiTiket($tiket->getIdPelapor(), $tiket->getNoTiket());

            //Masukkin log guys kalo udah selesai tiketnya
            $request = new LogRequest();
            $request->setPengguna($nama);
            $request->setIdTiket($tiket->getId());
            $request->setIdStatusTiketInternal($tiket->getIdStatusTiketInternal());
            $request->setIdStatusTiket($tiket->getIdStatusTiket());
            $request->setKeterangan("Permintaan Selesai");
            $this->log->addLogData($request);

            $_SESSION['flash'] = "Tiket Berhasil Diselesaikan";
            $this->_redirect('/servicedesk/permintaan/detail/' . $id);
        }
    }

    public function updateSolusiAction()
    {
        if ($this->getRequest()->isPost()) {

            $id = $this->_getParam('id');
            $permasalahanAkhir = $this->_getParam('permasalahan_akhir');
            $solusi = $this->_getParam('solusi');
            $nama = Zend_Auth::getInstance()->getIdentity()->nama_lengkap;

            $tiket = $this->tiket->findById($id);

            //Kalo servicedesk bisa nyelesain masukin juga ke tiket petugas sama tiket image laporan cuy, kasian nanti ga dianggep
            $this->tiketPetugas->addData($id, Zend_Auth::getInstance()->getIdentity()->id, $nama);
            //Masukkin log juga kalo dia jadi petugas
            $request = new LogRequest();
            $request->setIdTiket($id);
            $request->setIdStatusTiketInternal($tiket->getIdStatusTiketInternal());
            $request->setIdStatusTiket($tiket->getIdStatusTiket());
            $request->setPengguna($nama);
            $request->setKeterangan("Eskalasi petugas $nama");
            $this->log->addLogData($request);

            //Karena kalo bisa diselesain sama servicedesk biasanya ga susah susah banget kan, jadi gaperlu upload dokumen gapapa
            $request = new TiketImageLaporanRequest();
            $request->setUserInput($nama);
            $request->setUserUpdate($nama);
            $request->setPermasalahanAkhir($permasalahanAkhir);
            $request->setSolusi($solusi);
            $request->setTipeLaporan('Solved');
            $request->setIdTiket($id);

            if (!empty($_FILES['dokumen']['name'][0])) {
                //Panggil function untuk memasukkan lebih dari 1 file ke tiket image laporan kalo ada filenya
                $this->tiketImageLaporan->addMultipleFile($_FILES['dokumen'], $request);
            } else {
                //kalo gada filenya langsung masukin aja bwang ke tiketImageLaporan
                $this->tiketImageLaporan->addTiketImageLaporan($request);

                //Masukin log jan lupa
                $request = new LogRequest();
                $request->setIdTiket($id);
                $request->setPengguna($nama);
                $request->setIdStatusTiket($tiket->getIdStatusTiket());
                $request->setIdStatusTiketInternal(9);
                $request->setKeterangan("$nama memberikan solusi");
                $this->log->addLogData($request);
            }
            $this->tiket->updateStatusTiketInternal($id, 9, $nama);
            $this->tiket->updateStatusTiket($id, 5, $nama);

            //Send notif ke pelapor ya
            $tiket = $this->tiket->findById($id);

            $this->notifikasiService->sendTo(
                $tiket->getIdPelapor(),
                $tiket->getNoTiket(),
                "Solusi sudah diberikan"
            );

            //Nanti kirim email disini ya
            $this->mailerService->solusiTiket($tiket->getIdPelapor(), $tiket->getNoTiket(),
                $permasalahanAkhir, $solusi, Zend_Auth::getInstance()->getIdentity()->email,
                Zend_Auth::getInstance()->getIdentity()->hp);

            $_SESSION['flash'] = ucwords("Solusi Berhasil Terkirim");
            $this->_redirect('/servicedesk/permintaan/detail/' . $id);
        }
    }

    //Buat export ke excel
    //herf = '/servicedesk/export'
    public function exportAction()
    {
        $this->_helper->getHelper('layout')->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $styleArrayBorder = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );

        $styleArrayHeader = array(
            'font' => array(
                'bold' => true,
                'size' => 26
            )
        );

        $styleArray = array(
            'font' => array(
                'bold' => true
            )
        );

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("PUSTEKINFO");
        $objPHPExcel->createSheet(1);
        $objPHPExcel->setActiveSheetIndex(1);

        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Daftar Permintaan');
        $objPHPExcel->getActiveSheet()->mergeCells('A1:Q1');
        $objPHPExcel->getActiveSheet()->getStyle('A1:Q1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A1:Q1')->applyFromArray($styleArrayHeader);

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->setCellValue('A2', 'No.');
        $objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray($styleArray);

        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->setCellValue('B2', 'Pelapor');
        $objPHPExcel->getActiveSheet()->getStyle('B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('B2')->applyFromArray($styleArray);

        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->setCellValue('C2', 'No Tiket');
        $objPHPExcel->getActiveSheet()->getStyle('C2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('C2')->applyFromArray($styleArray);

        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->setCellValue('D2', 'Via');
        $objPHPExcel->getActiveSheet()->getStyle('D2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('D2')->applyFromArray($styleArray);

        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->setCellValue('E2', 'Status');
        $objPHPExcel->getActiveSheet()->getStyle('E2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('E2')->applyFromArray($styleArray);

        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->setCellValue('F2', 'Sub Kategori');
        $objPHPExcel->getActiveSheet()->getStyle('F2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('F2')->applyFromArray($styleArray);

        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->setCellValue('G2', 'Urgensi');
        $objPHPExcel->getActiveSheet()->getStyle('G2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('G2')->applyFromArray($styleArray);

        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->setCellValue('H2', 'Bagian');
        $objPHPExcel->getActiveSheet()->getStyle('H2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('H2')->applyFromArray($styleArray);

        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->setCellValue('I2', 'Gedung');
        $objPHPExcel->getActiveSheet()->getStyle('I2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('I2')->applyFromArray($styleArray);

        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->setCellValue('J2', 'Unit Kerja');
        $objPHPExcel->getActiveSheet()->getStyle('J2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('J2')->applyFromArray($styleArray);

        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->setCellValue('K2', 'Ruangan');
        $objPHPExcel->getActiveSheet()->getStyle('K2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('K2')->applyFromArray($styleArray);

        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->setCellValue('L2', 'Lantai');
        $objPHPExcel->getActiveSheet()->getStyle('L2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('L2')->applyFromArray($styleArray);

        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->setCellValue('M2', 'No HP');
        $objPHPExcel->getActiveSheet()->getStyle('M2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('M2')->applyFromArray($styleArray);

        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->setCellValue('N2', 'Email');
        $objPHPExcel->getActiveSheet()->getStyle('N2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('N2')->applyFromArray($styleArray);

        $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->setCellValue('O2', 'Permasalahan');
        $objPHPExcel->getActiveSheet()->getStyle('O2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('O2')->applyFromArray($styleArray);

        $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->setCellValue('P2', 'Keterangan');
        $objPHPExcel->getActiveSheet()->getStyle('P2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('P2')->applyFromArray($styleArray);

        $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->setCellValue('Q2', 'Tanggal Pembuatan');
        $objPHPExcel->getActiveSheet()->getStyle('Q2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('Q2')->applyFromArray($styleArray);

        $indeks = 1;
        $row = 3;

        $datas = $this->tiket->getAllData();
        foreach ($datas as $data) {
            $objPHPExcel->getActiveSheet()->setCellValue("A{$row}", $indeks);
            $objPHPExcel->getActiveSheet()->getStyle('A' . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A' . $row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

            $objPHPExcel->getActiveSheet()->setCellValue("B{$row}", $data->nama_pelapor);
            $objPHPExcel->getActiveSheet()->getStyle('B' . $row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

            $objPHPExcel->getActiveSheet()->setCellValue("C{$row}", $data->no_tiket);
            $objPHPExcel->getActiveSheet()->getStyle('C' . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('C' . $row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

            $objPHPExcel->getActiveSheet()->setCellValue("D{$row}", $this->via->getData($data->id_via)->via);
            $objPHPExcel->getActiveSheet()->getStyle('D' . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('D' . $row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

            $objPHPExcel->getActiveSheet()->setCellValue("E{$row}", $this->status->getQueryData($data->id_status_tiket)->status_tiket);
            $objPHPExcel->getActiveSheet()->getStyle('E' . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('E' . $row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

            $objPHPExcel->getActiveSheet()->setCellValue("F{$row}", ($data->id_sub_kategori == null) ? " " :
                $this->subKategori->getData($data->id_sub_kategori)->sub_kategori);
            $objPHPExcel->getActiveSheet()->getStyle('F' . $row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

            $objPHPExcel->getActiveSheet()->setCellValue("G{$row}", ($data->id_urgensi == null) ? " " :
                $this->urgensi->findById($data->id_urgensi)->nama);
            $objPHPExcel->getActiveSheet()->getStyle('G' . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('G' . $row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

            $objPHPExcel->getActiveSheet()->setCellValue("H{$row}", $data->bagian_pelapor);
            $objPHPExcel->getActiveSheet()->getStyle('H' . $row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

            $objPHPExcel->getActiveSheet()->setCellValue("I{$row}", $data->gedung_pelapor);
            $objPHPExcel->getActiveSheet()->getStyle('I' . $row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

            $objPHPExcel->getActiveSheet()->setCellValue("J{$row}", $data->unit_kerja_pelapor);
            $objPHPExcel->getActiveSheet()->getStyle('J' . $row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

            $objPHPExcel->getActiveSheet()->setCellValue("K{$row}", $data->ruangan_pelapor);
            $objPHPExcel->getActiveSheet()->getStyle('K' . $row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

            $objPHPExcel->getActiveSheet()->setCellValue("L{$row}", $data->lantai_pelapor);
            $objPHPExcel->getActiveSheet()->getStyle('L' . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('L' . $row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

            $objPHPExcel->getActiveSheet()->setCellValue("M{$row}", $data->hp_pelapor);
            $objPHPExcel->getActiveSheet()->getStyle('M' . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('M' . $row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

            $objPHPExcel->getActiveSheet()->setCellValue("N{$row}", $data->email_pelapor);
            $objPHPExcel->getActiveSheet()->getStyle('N' . $row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

            $objPHPExcel->getActiveSheet()->setCellValue("O{$row}", $data->judul_permasalahan);
            $objPHPExcel->getActiveSheet()->getStyle('O' . $row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

            $objPHPExcel->getActiveSheet()->setCellValue("P{$row}", $data->keterangan);
            $objPHPExcel->getActiveSheet()->getStyle("P{$row}")->getAlignment()->setWrapText(true);
            $objPHPExcel->getActiveSheet()->getStyle('P' . $row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

            $objPHPExcel->getActiveSheet()->setCellValue("Q{$row}", $this->dateFormat->directAngkaFUll($data->tanggal_input));
            $objPHPExcel->getActiveSheet()->getStyle('Q' . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('Q' . $row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

            $indeks++;
            $row++;
        }

        $objPHPExcel->getActiveSheet()->getStyle('A1:Q' . ($row - 1))->applyFromArray($styleArrayBorder);

        header("Content-Type: application/ms-excel");
        header("Content-Disposition: attachment; filename=Daftar Permintaan.xls");

        $objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
        $objWriter->save("php://output");
    }
}
