<?php

class OperatorController extends Zend_Controller_Action
{

    public function init()
    {
        // authorizaton all allowed
        $this->_helper->_acl->allow('verificator');
        $this->_helper->layout->setLayout('layout_operator');
    }

    public function preDispatch()
    {
        $this->tiket = new Dpr_TiketService();
        $this->dokumenLampiran = new Dpr_DokumenLampiranService();
        $this->programmer = new Dpr_ProgrammerService();
        $this->timProgrammer = new Dpr_TimProgrammerService();
        $this->fileManager = new Dpr_FileManagerService();
        $this->dateFormat = new Dpr_Controller_Action_Helper_YmdToIndo();
        $this->pengguna = new Dpr_PenggunaService();
        $this->notifikasi = new Dpr_NotifikasiService();
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
                $this->renderScript('error/404.phtml');
                $this->view->message = ["Page Not Found"];
            } elseif ($exception->getCode() == 500) {
                $this->renderScript('error/500.phtml');
                $this->view->message = "Server Error";
            }
        }
    }

    //localhost/operator
    public function indexAction()
    {
        $this->view->title = 'Verificator';
        try {
            $datas = $this->tiket->getAllDataSingrusForRekapOperator();

            if ($datas[0] == null || $datas[1] == null) {
                throw new Exception("Belum Ada Permintaan");
            }

            /**
             * Button untuk card. herf = '/operator/permintaan/tahun/:tahun'
             */

            $data = [
                'list_permintaan' => $datas[0],
                'list_tahun' => $datas[1],
                'tahun_saat_ini' => explode('-', explode(' ', date('Y-m-d H:i:s'))[0])[0]
            ];

            //            var_dump($data);
            //            die;
            $this->view->data = $data;
        } catch (Exception $exception) {
            //            var_dump($exception->getMessage());
            //            die;
            $this->view->error = $exception->getMessage();
        }
    }

    //localhost/operator/permintaan
    public function permintaanAction()
    {
        //Memperbaharui title
        $this->view->title = 'Daftar Permintaan';

        $this->_helper->viewRenderer->setRender('permintaan');
        try {
            if ($this->getRequest()->getParam('detail')) {
                //localhost/operator/permintaan/detail/:id_tiket
                $this->detailPermintaan();
            } elseif ($this->getRequest()->getParam('programmer')) {
                //localhost/operator/permintaan/programmer/:id_tiket
                $this->programmerPermintaan();
            } else {
                $pesan = $this->getRequest()->getParam('pesan');
                $datas = $this->tiket->getAllDataSingrusForRekap();

                if ($this->getRequest()->getParam('tahun') && $this->getRequest()->getParam('tahun') != 'All') {
                    $tahunInput = $this->getRequest()->getParam('tahun');

                    $counter = 1;
                    foreach ($datas as $data) {
                        $tahun = explode('-', explode(' ', $data->tanggal_input)[0])[0];
                        if ($tahun == $tahunInput) {
                            $result[$counter] = [
                                'id' => $data->id,
                                'no_tiket' => $data->no_tiket,
                                'status' => $data->status_revisi,
                                'deskripsi' => $data->keterangan,
                                'dokumen' => $this->dokumenLampiran->getListDokumenLampiran($data->id),
                                'leader_programmer' => $this->programmer->getLeaderProrgrammer($data->id),
                                'tanggal' => explode(' ', $data->tanggal_input)[0],
                                'jam' => explode(' ', $data->tanggal_input)[1]
                            ];
                            $counter++;
                        }
                    }

                    if ($result == null) {
                        throw new Exception("Belum Ada Permintaan Pada Tahun $tahunInput");
                    }
                } elseif ($this->getRequest()->getParam('tahun') == 'All' || !($this->getRequest()->getParam('tahun'))) {
                    if ($datas == null) {
                        throw new Exception("Belum Ada Permintaan");
                    }

                    $counter = 1;
                    foreach ($datas as $data) {
                        $result[$counter] = [
                            'id' => $data->id,
                            'no_tiket' => $data->no_tiket,
                            'status' => $data->status_revisi,
                            'deskripsi' => $data->keterangan,
                            'dokumen' => $this->dokumenLampiran->getListDokumenLampiran($data->id),
                            'leader_programmer' => $this->programmer->getLeaderProrgrammer($data->id),
                            'tanggal' => explode(' ', $data->tanggal_input)[0],
                            'jam' => explode(' ', $data->tanggal_input)[1]
                        ];

                        $counter++;
                    }
                }

                //Di view nanti pake $this->pesan;
                if ($pesan) {
                    $this->view->pesan = $pesan;
                }

                $this->view->data = $result;
            }
        } catch (Exception $exception) {
            //            var_dump($exception->getMessage());
            //            die;
            $this->view->error = $exception->getMessage();
        }
    }

    //localhost/operator/permintaan/detail/:id_tiket
    private function detailPermintaan()
    {
        //Memperbaharui title
        $this->view->title = 'Detail Permintaan';

        $this->_helper->viewRenderer->setRender('detail');
        $id = $this->getRequest()->getParam('detail');

        if ($this->tiket->findById($id)) {
            $id = $id;
        } else {
            $id = $this->tiket->findByNoTiket($id)->getId();
        }

        $datas = $this->tiket->findById($id);

        if ($datas == null || $datas->getIdSubKategori() != 1) {
            throw new Exception("Data Tidak Ditemukan");
        }

        $data = [
            'id' => $id,
            'status-revisi' => $datas->getStatusRevisi(),
            'nama' => $datas->getNamaPelapor(),
            'unit_kerja' => $datas->getUnitKerjaPelapor(),
            'judul' => $datas->getKeterangan(),
            'deskripsi_permintaan' => $datas->getKeterangan(),
            'no_telepon' => $datas->getHpPelapor(),
            'email' => $datas->getEmailPelapor(),
            'dokumen' => $this->dokumenLampiran->getListDokumenLampiran($id)
        ];

        // var_dump($data['dokumen']);
        // die;

        $this->view->data = $data;
    }

    /**todo verifikasi cuma redirect ke kirim tim. Nanti verifikasi nya ditaro di /permintaan/programmer */
    //Tombol verifikasi
    //localhost/operator/verifikasi/id/:id
    public function verifikasiAction()
    {
        $id = $this->getRequest()->getParam('id');
        $this->_redirect('/operator/permintaan/programmer/' . $id);
    }

    //localhost/operator/permintaan/programmer/:id_tiket
    private function programmerPermintaan()
    {
        //Memperbaharui title
        $this->view->title = 'Pilih Leader';

        $this->_helper->viewRenderer->setRender('programmer');
        $id = $this->getRequest()->getParam('programmer');
        $datas = $this->tiket->findById($id);

        if ($datas == null || $datas->getIdSubKategori() != 1) {
            throw new Exception("Data Tidak Ditemukan");
        }

        $listProgrammer = $this->programmer->getAllProgrammer();

        $data = [
            'id' => $id,
            'proyek' => $datas->getKeterangan(),
            'list_programmer' => $listProgrammer
        ];

        $this->view->data = $data;
    }

    //Tombol revisi
    //localhost/operator/revisi {Post}
    public function revisiAction()
    {
        if ($this->getRequest()->isPost()) {
            $id = $this->_getParam('id');
            $keteranganRevisi = $this->_getParam('deskripsi_revisi');

            $user_update = Zend_Auth::getInstance()->getIdentity()->nama_lengkap;

            $this->tiket->updateOperatorRevisi($id, $keteranganRevisi, $user_update);

            $tiket = $this->tiket->findById($id);

            //Kiirm notif ke user kalo permintaannya terdapat revisi
            $this->notifikasi->sendTo($tiket->getIdPelapor(), $tiket->getNoTiket(), $keteranganRevisi);
            // kirim notif email ke user
            $this->mailerService->revisiTiketSingrus($tiket->getIdPelapor(), $tiket->getNoTiket(), $keteranganRevisi);

            //Masukin log juga dong
            $request = new LogRequest();
            $request->setIdTiket($tiket->getId());
            $request->setPengguna($user_update);
            $request->setIdStatusTiketInternal($tiket->getIdStatusTiketInternal());
            $request->setIdStatusTiket($tiket->getIdStatusTiket());
            $request->setKeterangan("Terdapat revisi $keteranganRevisi");
            (new Dpr_LogService())->addLogData($request);

            $_SESSION['flash'] = "Berhasil Merevisi Permintaan";

            $this->_redirect('/operator/permintaan');
        }
    }

    //Ini tombol kirim di localhost/operator/permintaan/programmer/:id_tiket
    //localhost/operator/kirim
    public function kirimAction()
    {
        try {
            if ($this->getRequest()->isPost()) {
                $id = $this->_getParam('id');
                $idLeaderProgrammer = $this->_getParam('leader_programmer');
                $userUpdate = Zend_Auth::getInstance()->getIdentity()->nama_lengkap;

                $tiket = $this->tiket->findById($id);

                //Ngeverifikasi datanya terlebih dahulu
                $this->tiket->updateOperatorVerifikasi($id, $userUpdate);

                //Masukkin ke log guys kalo diverifikasi
                $request = new LogRequest();
                $request->setIdTiket($tiket->getId());
                $request->setPengguna($userUpdate);
                $request->setIdStatusTiket($tiket->getIdStatusTiket());
                $request->setIdStatusTiketInternal($tiket->getIdStatusTiketInternal());
                $request->setKeterangan("Permintaan diverifikasi oleh $userUpdate");
                (new Dpr_LogService())->addLogData($request);

                //Generate nama tim
                $judul = $this->tiket->findById($id)->getNoTiket();
                $namaLeader = $this->pengguna->findById($idLeaderProgrammer)->getNamaLengkap();
                $namaTim = $judul . "-" . $namaLeader;

                //Ini bagian ngebuat tim programmer baru ke database
                $idTim = $this->timProgrammer->tambahTim($namaTim)->getId();

                //Masukin log lagi kalo udah dibuat tim programmernya
                $request->setKeterangan("Tim programmer $namaTim telah dibuat");
                (new Dpr_LogService())->addLogData($request);

                //Ini buat nambahin lead programmer yang udah ditentuin sebelumnya
                $requestProgrammer = new ProgrammerRequest();
                $requestProgrammer->setIdTimProgrammer($idTim);
                $requestProgrammer->setIdPengguna($idLeaderProgrammer);
                $requestProgrammer->setJabatan('leader');
                $this->programmer->addDataProgrammer($requestProgrammer);

                //Masukin log lagi kalo udah ditentuin siapa leadernya
                $request->setKeterangan("{$this->pengguna->findById($idLeaderProgrammer)->getNamaLengkap()} ditugaskan sebagai leader programmer");
                (new Dpr_LogService())->addLogData($request);

                //Ini nambahin id_tim_programmer ke tiket nya
                $requestTiket = new TiketUpdateIdTimProgrammerRequest();
                $requestTiket->setId($id);
                $requestTiket->setUserUpdate($userUpdate);
                $requestTiket->setIdTimProgrammer($idTim);
                $this->tiket->updateIdTimProgrammer($requestTiket);

                $tiket = $this->tiket->findById($id);

                //Notif untuk leader
                $this->notifikasi->sendTo(
                    $idLeaderProgrammer,
                    $tiket->getNoTiket(),
                    "Ditugaskan Sebagai Leader Pada Permintaan {$tiket->getNoTiket()}"
                );

                //Notif untuk user
                $this->notifikasi->sendTo(
                    $tiket->getIdPelapor(),
                    $tiket->getNoTiket(),
                    "Permintaan dengan nomor tiket {$tiket->getNoTiket()} telah diverifikasi dan dikerjakan oleh {$namaLeader}"
                );

                // kirim notif email ke user 
                $this->mailerService->verifiedTiketSingrus($tiket->getIdPelapor(), $tiket->getNoTiket(), $this->pengguna->findById($idLeaderProgrammer));

                //Ini adata yang dikembalikan
                $_SESSION['flash'] = "Sukses Menambahkan Leader Proyek";

                //Kembali ke halaman permintaan dengan mengirim data pesan
                $this->_redirect('/operator/permintaan');
            }
        } catch (Exception $exception) {
            $this->view->error = $exception->getMessage();
        }
    }

    //Ini tombol hapus dokumen
    //localhost/operator/delete-dokumen/id/:id/id_dokumen/:id_dokumen
    public function deleteDokumenAction()
    {
        try {
            $id = $this->getRequest()->getParam('id');
            $idDokumen = $this->getRequest()->getParam('id_dokumen');

            $dokumen = $this->dokumenLampiran->findById($idDokumen);

            $this->dokumenLampiran->deleteQueryData($idDokumen);
            $this->fileManager->deleteDokumenLampiran($dokumen->image_name);

            //Kalo pake ajax nanti ini gaperlu redirect
            $this->_redirect('/operator/permintaan/detail/' . $id);
        } catch (Exception $exception) {
            var_dump($exception->getMessage());
            die;
        }
    }

    //localhost/operator/project
    public function projectAction()
    {
        $this->view->title = 'Daftar Project';
        $this->_helper->viewRenderer->setRender('project');
        try {
            $pesan = $this->getRequest()->getParam('pesan');

            $datas = $this->tiket->getAllDataSingrusForRekap();

            if ($datas == null) {
                throw new Exception("Belum Ada Data");
            }

            $counter = 1;
            foreach ($datas as $dat) {
                if ($dat->id_tim_programmer) {
                    $leaderProgrammer = $this->programmer->getLeaderProrgrammer($dat->id);

                    $data[$counter] = [
                        'id_tiket' => $dat->id,
                        'id_tim_programmer' => $dat->id_tim_programmer,
                        'tanggal' => explode(' ', $dat->tanggal_input)[0],
                        'jam' => explode(' ', $dat->tanggal_input)[1],
                        'leader' => isset($leaderProgrammer) ? $leaderProgrammer : null,
                        'project' => $dat->keterangan
                    ];
                    $counter++;
                }
            }

            if ($data == null) {
                throw new Exception("Belum Ada Data");
            }

            //Di view pake $this->pesan;
            if ($pesan) {
                $this->view->pesan = $pesan;
            }
            $this->view->data = $data;
        } catch (Exception $exception) {
            // var_dump($exception->getMessage());
            // die;
            $this->view->error = $exception->getMessage();
        }
    }

    //localhost/operator/project-edit/:id_tiket
    public function projectEditAction()
    {
        //Memperbaharui title
        $this->view->title = 'Edit Project';
        $this->_helper->viewRenderer->setRender('edit');
        try {
            $idTiket = $this->_getParam('id_tiket');

            $tiket = $this->tiket->findById($idTiket);

            if ($tiket == null) {
                throw new Exception("Data Tidak Ditemukan");
            }

            $idTim = $tiket->getIdTimProgrammer();

            $currentLeader = $this->programmer->getLeaderProgrammerByIdTim($idTim);

            $listProgrammer = $this->programmer->getAllProgrammer();

            $data = [
                'id_tiket' => $idTiket,
                'id_tim_programmer' => $idTim,
                'proyek' => $tiket->getKeterangan(),
                'current_leader' => [
                    'id_pengguna' => $currentLeader->id_pengguna,
                    'nama' => $this->pengguna->findById($currentLeader->id_pengguna)->getNamaLengkap()
                ],
                'list_programmer' => $listProgrammer
            ];

            // var_dump($data);
            // die;

            $this->view->data = $data;
        } catch (Exception $exception) {
            // var_dump($exception->getMessage());
            // die;

            $this->view->error = $exception->getMessage();
        }
    }

    //Tombol kirim edit data di localhost/operator/project-edit/:id_tiket
    //localhost/operator/kirim-edit
    public function kirimEditAction()
    {
        try {
            if ($this->getRequest()->isPost()) {

                //Ini id tiket
                $id = $this->_getParam('id');

                $tiket = $this->tiket->findById($id);

                //Ini id pengguna si leader
                $idLeaderProgrammer = $this->_getParam('leader_programmer');

                //ini id di table tim programmer
                $idTimProgrammer = $this->tiket->findById($id)->getIdTimProgrammer();

                //Ini id di table programmer
                $idProgrammer = $this->programmer->getLeaderProgrammerByIdTim($idTimProgrammer)->id;

                //Ini id programmer lama
                $idPenggunaProgrammerLama = $this->programmer->getLeaderProgrammerByIdTim($idTimProgrammer)->id_pengguna;

                // var_dump($id);
                // var_dump($idLeaderProgrammer);
                // die;
                //Ini buat ganti nama tim programmernya
                $this->timProgrammer->editNamaTim($id, $idTimProgrammer, $idLeaderProgrammer);

                //Ini update data programmer nya
                $this->programmer->editLeaderProgrammer($idProgrammer, $idLeaderProgrammer);

                //Ini ngirim ke leader yang lama kalo dia dah gajadi leader
                $this->notifikasi->sendTo(
                    $idPenggunaProgrammerLama,
                    $tiket->getNoTiket(),
                    "Sudah tidak menjadi leader di permintaan dengan nomor tiket {$tiket->getNoTiket()}"
                );

                //Notif untuk leader baru
                $this->notifikasi->sendTo(
                    $idLeaderProgrammer,
                    $tiket->getNoTiket(),
                    "Ditugaskan Sebagai Leader Pada Permintaan {$tiket->getNoTiket()}"
                );

                $namaLeaderBaru = $this->pengguna->findById($idLeaderProgrammer)->getNamaLengkap();

                //Notif untuk user
                $this->notifikasi->sendTo(
                    $tiket->getIdPelapor(),
                    $tiket->getNoTiket(),
                    "Leader pada permintaan dengan nomor tiket {$tiket->getNoTiket()} diubah menjadi {$namaLeaderBaru}"
                );

                // kirim notif email ke user
                $this->mailerService->ubahLeaderSingrus($tiket->getIdPelapor(), $tiket->getNoTiket(), $this->pengguna->findById($idLeaderProgrammer));

                $_SESSION['flash'] = "Sukses Update Leader Project";
                $this->_redirect('/operator/project');
            }
        } catch (Exception $exception) {
            $this->view->error = $exception->getMessage();
        }
    }
}
