<?php

require_once APPLICATION_PATH . '/dto/TodoList/TodoListUpdateSelesaiRequest.php';
require_once APPLICATION_PATH . '/dto/Log/LogRequest.php';
require_once APPLICATION_PATH . '/dto/Programmer/ProgrammerRequest.php';
require_once APPLICATION_PATH . '/dto/Programmer/ProgrammerUpdateRequest.php';

class ProgrammerController extends Zend_Controller_Action
{

	public function init()
	{
		// akses buat viewer liat index dan detail dari halaman programmer
         $this->_helper->_acl->allow('viewer', ['index', 'detail']);
		$this->_helper->_acl->allow('programmer');
        $this->_helper->layout->setLayout('layout_programmer');
    }

    public function preDispatch()
    {
        // define model want to test
        $this->programmerExternal = new Dpr_ProgrammerExternalService();
        $this->statusAplikasi = new Dpr_StatusAplikasiService();
        $this->aplikasi = new Dpr_AplikasiService();
        $this->statusTiket = new Dpr_StatusTiketService();
        $this->logService = new Dpr_LogService();
        $this->programmer = new Dpr_ProgrammerService();
        $this->tiket = new Dpr_TiketService();
        $this->timProgrammer = new Dpr_TimProgrammerService();
        $this->dokumenLampiran = new Dpr_DokumenLampiranService();
        $this->tahapanService = new Dpr_TahapanService();
        $this->todoList = new Dpr_TodoListService();
        $this->pengguna = new Dpr_PenggunaService();
        $this->subTahapan = new Dpr_SubTahapanService();
        $this->fileManager = new Dpr_FileManagerService();
        $this->todoListDokumen = new Dpr_TodoListDokumenService();
        $this->dateFormat = new Dpr_Controller_Action_Helper_YmdToIndo();
        $this->persentase = new Dpr_PersentaseService();
        $this->copy = new Dpr_CopyService();
        $this->jenisDokumen = new Dpr_JenisDokumenService();
        $this->notifikasi = new Dpr_NotifikasiService();
        $this->mailerService = new Dpr_MailerService();

        //Ini yang dicek di layout untuk mengecek apakah dia programmer internal atau external
        $idPengguna = Zend_Auth::getInstance()->getIdentity()->id;

        //Kalo udah pake session ini dihapus, ganti atasnya
        // $idPengguna = 4;

        $this->view->developer = $this->programmer->checkDeveloper($idPengguna);

        //Ngasih title gayn
        $this->view->title;

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

    /**
     * Ini kalo tiketnya belum ditentuin aplikasinya gabakal masuk ke rekap
     */
    //localhost/programmer
    public function indexAction()
    {
        try {
            $datas = $this->tiket->getAllDataSingrusForRekap();

            if ($datas == null) {
                throw new Exception("Belum Ada Data");
            }

            $result = $this->programmer->getRekap($datas);

            if ($result[0] == null || $result[1] == null) {
                throw new Exception("Belum Ada Data");
            }

            /**
             * Button untuk card yang internal. herf = '/programmer/detail/developer/internal/tahapan/:tahapan/tahun/:tahun'
             * Button untuk card yang external. herf = '/programmer/detail/developer/external/tahapan/:tahapan/tahun/:tahun'
             */
            $data = [
                'list_rekap' => $result[0],
                'list_tahun' => $result[1],
                'tahun_saat_ini' => explode('-', explode(' ', date('Y-m-d H:i:s'))[0])[0]
            ];

            $this->view->title = 'Programmer';
            $this->view->data = $data;
        } catch (Exception $exception) {
            // var_dump($exception->getMessage());
            // die;
            $_SESSION['flash_error'] = $exception->getMessage();
        }
    }

    //localhost/programmer/detail
    public function detailAction()
    {
        try {
            if ($this->getRequest()->getParam('developer') == 'internal') {
                //localhost/programmer/detail/developer/internal/tahapan/:tahapan/tahun/:tahun
                $this->detailInternal();
            } else {
                //localhost/programmer/detail/developer/external/tahapan/:tahapan/tahun/:tahun
                $this->detailExternal();
            }
        } catch (Exception $exception) {
            //            var_dump($exception->getMessage());
            //            die;

            $_SESSION['flash_error'] = $exception->getMessage();
        }
    }

    //localhost/programmer/detail/developer/internal/tahapan/:tahapan/tahun/:tahun
    private function detailInternal()
    {
        //Memperbaharui title
        $this->view->title = 'Detail Internal';

        //Butuh tahapan dari url parameter
        $this->_helper->viewRenderer->setRender('index-lanjutan');
        $tahapan = $this->getRequest()->getParam('tahapan');

        //Butuh tahun dari url parameter
        $tahun = $this->getRequest()->getParam('tahun');

        $data = $this->programmer->getListInternalByTahapan($tahapan, $tahun);


        if ($data == null) {
            $this->view->tahun = $tahun;
            $this->view->tahapan = ucfirst($tahapan);
            $this->view->developer = "Internal";

            $this->view->data = $data;
            throw new Exception("Tidak Ada List Permintaan di Tahapan " . ucwords($tahapan) . " Pada Tahun $tahun");
        }

        $this->view->tahapan = ucfirst($tahapan);
        $this->view->tahun = $tahun;
        $this->view->developer = "Internal";
        $this->view->data = $data;
    }

    //localhost/programmer/detail/developer/external/tahapan/:tahapan/tahun/:tahun
    private function detailExternal()
    {
        //Memperbaharui title
        $this->view->title = 'Detail External';

        //Butuh tahapan dari url parameter
        $this->_helper->viewRenderer->setRender('index-lanjutan');
        //        $this->_helper->viewRenderer->setRender('permintaan');
        $tahapan = $this->getRequest()->getParam('tahapan');

        //Butuh tahun dari url parameter
        $tahun = $this->getRequest()->getParam('tahun');

        $data = $this->programmer->getListExternalByTahapan($tahapan, $tahun);

        if ($data == null) {
            $this->view->tahun = $tahun;
            $this->view->tahapan = ucfirst($tahapan);
            $this->view->developer = "Eksternal";
            $this->view->data = $data;
            throw new Exception("Tidak Ada List Permintaan di Tahapan " . ucwords($tahapan) . " Pada Tahun $tahun");
        }

        $this->view->tahapan = ucfirst($tahapan);
        $this->view->tahun = $tahun;
        $this->view->developer = "External";
        $this->view->data = $data;
    }

    //localhost/programmer/permintaan
    public function permintaanAction()
    {
        //Memperbaharui title
        $this->view->title = 'Daftar Permintaan';

        //Nanti ini yang dipake kalo dah pake session
        $idPengguna = Zend_Auth::getInstance()->getIdentity()->id;


        $this->_helper->viewRenderer->setRender('permintaan');

        try {

            if ($this->getRequest()->getParam('detail')) {
                //localhost/programmer/permintaan/detail/:id_tiket
                $this->detailPermintaan();
            } else {
                //Pesan ini nanti diambil ketika leader udah nambahin anggota tim nya
                $pesan = $this->getRequest()->getParam('pesan');

                //Ini ngambil semua permintaan yang id tim programmernya udah di set sama operator
                $allIdTim = $this->programmer->getAllDataByIdPengguna($idPengguna);

                //Kalo gada tiket yang udah di set jadi tugas nya sama operator maka akan di throw exception kalo belum ada permintaan
                //Nanti ambil datanya di catch
                if ($allIdTim == null) {
                    throw new Exception("Belum Ada Permintaan");
                }
                // var_dump($allIdTim);
                // die;
                //Perulangan untuk menyimpan semua data ke array data
                $counter = 1;
                foreach ($allIdTim as $tim) {
                    $tiket = $this->tiket->findByIdTimProgrammer($tim->id_tim_programmer);
                    $aplikasi = ($tiket->getIdAplikasi()) ? $this->aplikasi->findById($tiket->getIdAplikasi()) : null;
                    $tahapan = $this->tahapanService->getTahapan($tiket);
                    $tahap = $tahapan[0];
                    $detail = isset($tahapan[1]) ? $tahapan[1] : null;
                    //Ini persentase yang didapet dari sub tahapan
                    $listPersentase = $this->tahapanService->getPersentaseSubTahapan($detail, $tahap)[1];


                    $data[$counter] = [
                        'id_tiket' => $tiket->getId(),
                        'no_tiket' => $tiket->getNoTiket(),
                        'developer' => ($aplikasi) ? $aplikasi->getDeveloper() : null,
                        'persentase' => round($this->persentase->getPersentase($listPersentase)),
                        'id_tim_programmer' => $tiket->getIdTimProgrammer(),
                        'tanggal' => explode(' ', $tiket->getTanggalInput())[0],
                        'jam' => explode(' ', $tiket->getTanggalInput())[1],
                        'judul_permintaan' => $tiket->getKeterangan(),
                        'dokumen_lampiran' => $this->dokumenLampiran->getListDokumenLampiran($tiket->getId()),
                        'list_tim' => $this->timProgrammer->getListProgrammer($tiket->getIdTimProgrammer()),
                        'aplikasi' => ($aplikasi) ? $aplikasi->getAplikasi() : null,
                        'id_status_tiket' => $tiket->getIdStatusTiket(),
                        'status_tiket' => $this->statusTiket->getData($tiket->getIdStatusTiket())->status_tiket,
                        'reference_to' => $tiket->getReferenceTo()
                    ];
                    $counter++;
                }

                /**
                 * Data yang dikirim terdiri adri :
                 * id_tiket, id dari tiket
                 * id_tim_programmer, id dari tim programmer pada tiket
                 * tanggal, sebagai tanggal pembuatan tiket
                 * judul_permintaan, sebagai deskripsi permintaan
                 * list_tim,berisikan data tim dari project tersebut. Terdiri dari id, nama, jabatan
                 * dokumen_lampiran, berisikan list dokumen lampiran yang berisikan id_dokumen, original_name, image_name, user_input, tanggal_input, path
                 * list_tim sama dokumen_lampiran bentuknya array kalo ada. jadi di foreach tapi kasih if kalo ga null datanya
                 */

                //Di view nanti pake $this->pesan;
                if ($pesan) {
                    $this->view->pesan = $pesan;
                }

                //Nanti di button Action yang detail, herf nya gini '/programmer/permintaan/detail/' . $id_tiket
                //Button Action yang project, herf nya gini '/programmer/project/id_tiket/' . $id_tiket
                $this->view->data = $data;
            }
        } catch (Exception $exception) {
            // var_dump($exception->getMessage());
            // die;

            //Ini data kalo belum ada permintaan
            $_SESSION['flash_error'] = $exception->getMessage();
        }
    }

    //localhost/programmer/permintaan/detail/:id_tiket
    private function detailPermintaan()
    {
        //Memperbaharui title
        $this->view->title = 'Detail Permintaan';

        //Kalo ada parameter pesan akan diambil disini
        $pesan = $this->getRequest()->getParam('pesan');

        //Ini sementara, nanti kalo dah pake session hapus aja
        // $idPengguna = $this->getRequest()->getParam('id_pengguna');
        $this->_helper->viewRenderer->setRender('detail');
        //Kalo udah pake session ini yang dipake
        $idPengguna = Zend_Auth::getInstance()->getIdentity()->id;

        //Data yang harus dapet itu id tiket nya
        $idTiket = $this->getRequest()->getParam('detail');

        if ($this->tiket->findById($idTiket)) {
            $idTiket = $idTiket;
        } else {
            $idTiket = $this->tiket->findByNoTiket($idTiket)->getId();
        }

        $tiket = $this->tiket->findById($idTiket);

        if ($tiket->getIdSubKategori() != 1) {
            throw new Exception("Data Tidak Ditemukan");
        }

        $programmer = $this->programmer->findByIdTimAndIdPengguna($tiket->getIdTimProgrammer(), $idPengguna);

        $aplikasi = ($tiket->getIdAplikasi() != null) ?
            $this->aplikasi->findById($tiket->getIdAplikasi())->getAplikasi() : null;

        if (!$programmer->jabatan) {
            throw new Exception("Data Tidak Ditemukan");
        }

        $data = [
            'peran' => $programmer->jabatan,
            'id_tiket' => $tiket->getId(),
            'id_tim_programmer' => $tiket->getIdTimProgrammer(),
            'pelapor' => $tiket->getNamaPelapor(),
            'id_status_tiket' => $tiket->getIdStatusTiket(),
            'status_tiket' => $this->statusTiket->getData($tiket->getIdStatusTiket())->status_tiket,
            'email' => $tiket->getEmailPelapor(),
            'no_telepon' => $tiket->getHpPelapor(),
            'permasalahan' => $tiket->getKeterangan(),
            'deskripsi' => $tiket->getKeterangan(),
            'dokumen_lampiran' => $this->dokumenLampiran->getListDokumenLampiran($tiket->getId()),
            'id_aplikasi_aktif' => $tiket->getIdAplikasi(),
            'aplikasi' => $aplikasi,
            'list_aplikasi' => $this->aplikasi->getListAplikasi(),
            'reference_by' => $tiket->getReferenceBy(),
            'reference_to' => $tiket->getReferenceTo()
        ];

        //Kalo ada pesannya bakal ditambahin ke array data
        if ($pesan) {
            $this->view->pesan = $pesan;
        }

        /**
         * Data yang dikirim ke view meliputi :
         * peran, berisikan peran user pada project tersebut
         * id_tiket
         * id_tim_programmer
         * pelapor, berisikan nama pelapor
         * email, berisikan email pelapor
         * no_telepon, berisikan no pelapor
         * permaslaahan, berisikan judul permasalahan
         * dokumen_lampiran, data berbentuk araay. harus di loop
         *
         */

        // var_dump($data);
        // die;

        //Buat ngisi aplikasinya nanti tambah button konfirmasi

        //Buat peran nanti di cek, kalo dia leader, tombol tambah tim nya baru bakal dimunculin
        //Herf buat tombol tambah tim '
        $this->view->data = $data;
    }

    //Ini tombol log buat programmer
    //localhost/programmer/log/id/$id_tiket
    public function logAction()
    {
        //Memperbaharui title
        $this->view->title = 'Log';

        $this->_helper->viewRenderer->setRender('log');
        try {
            //            $dir = APPLICATION_PATH . '/storage/todo_list_dokumen/';
            $idTiket = $this->getRequest()->getParam('id');
            $tiket = $this->tiket->findById($idTiket);
            $idPengguna = Zend_Auth::getInstance()->getIdentity()->id;

            $programmer = $this->programmer->findByIdTimAndIdPengguna($tiket->getIdTimProgrammer(), $idPengguna);
            if (!$programmer->jabatan) {
                throw new Exception("Data Tidak Ditemukan");
            }

            $datas = $this->logService->getLogInDetailProgrammer($idTiket);

            if ($datas == null) {
                throw new Exception("Belum Ada Data");
            }

            $counter = 1;
            foreach ($datas as $data) {
                $result[$counter] = [
                    'id' => $data->id,
                    'keterangan' => $data->keterangan,
                    'programmer' => $data->pengguna,
                    'tanggal' => $this->dateFormat->directAngkaFUll($data->tanggal_input),
                    'nama_dokumen' => $data->file_original_name,
                    'dokumen' => ($data->file_name) ? [
                        'doc_name' => explode('.', $data->file_name)[0],
                        'ext' => explode('.', $data->file_name)[1]
                    ] : null
                ];
                $counter++;
            }

            $this->view->no_tiket = $tiket->getNoTiket();

            $this->view->data = $result;
        } catch (Exception $exception) {

            $_SESSION['flash_error'] = $exception->getMessage();
        }
    }

    //Ini tombol lanjutkan buat leader programmer
    //localhost/programmer/lanjutkan
    public function lanjutkanAction()
    {
        $this->_helper->getHelper('layout')->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        if ($this->getRequest()->isPost()) {
            //kalo udah pake session pake zend_auth
            $idPengguna = Zend_Auth::getInstance()->getIdentity()->id;
            $namaPengguna = Zend_Auth::getInstance()->getIdentity()->nama_lengkap;

            try {
                //Nanti form nya dibuat kayak konfirmasi apakah ingin dilanjutkan pada tahun depan

                //Dikirim hidden di form id tiket nya
                $idTiket = $this->_getParam('id_tiket');

                $this->checkLeader($idTiket, $idPengguna);

                $idBaru = $this->copy->updateLanjutkanProgrammer($idTiket, $namaPengguna);
                $tiketLama = $this->tiket->findById($idTiket);
                $tiketBaru = $this->tiket->findById($idBaru);

                //Kirim notif ke user kalo permintaannya dilanjutkan pada tahun depan
                $this->notifikasi->sendTo($tiketLama->getIdPelapor(), $tiketBaru->getNoTiket(),
                    "Permintaan dengan no tiket {$tiketLama->getNoTiket()} dilanjutkan pada tahun depan dengan no tiket baru : {$tiketBaru->getNoTiket()}");

                //Masukin log ke tiket lama juga kalo dia dilanjutin ditahun depan
                $request = new LogRequest();
                $request->setIdTiket($tiketLama->getId());
                $request->setIdStatusTiketInternal($tiketLama->getIdStatusTiketInternal());
                $request->setIdStatusTiket($tiketLama->getIdStatusTiket());
                $request->setPengguna($namaPengguna);
                $request->setIdSubKategori(1);
                $request->setKeterangan("Permintaan dilanjutkan pada tahun depan");
                $this->logService->addLogData($request);

                //Masukin juga ke log kalo ada tiket baru kebuat otomatis ngelanjutin tiket lama
                $request->setIdTiket($tiketBaru->getId());
                $request->setIdStatusTiketInternal($tiketBaru->getIdStatusTiketInternal());
                $request->setIdStatusTiket($tiketBaru->getIdStatusTiket());
                $request->setPengguna($namaPengguna);
                $request->setIdSubKategori(1);
                $request->setKeterangan("Permintaan baru melanjutkan permintaan {$tiketLama->getNoTiket()}");
                $this->logService->addLogData($request);

                $_SESSION['flash'] = "Berhasil Melanjutkan Project Pada Tahun Depan";

                $this->_redirect('/programmer/permintaan/detail/' . $idBaru);
            } catch (Exception $exception) {

                $_SESSION['flash_error'] = $exception->getMessage();
            }
        }
    }

    //Ini tombol selesai buat leader programmer
    //localhost/programmer/selesai
    public function selesaiAction()
    {
        $this->_helper->getHelper('layout')->disableLayout();
        if ($this->getRequest()->isPost()) {
            //kalo udah pake session pake zend_auth
            $idPengguna = Zend_Auth::getInstance()->getIdentity()->id;
            $namaPengguna = Zend_Auth::getInstance()->getIdentity()->nama_lengkap;

            try {
                //Nanti form nya bisa dibuat kayak konfirmasi apakah yakin ingin menyelesaikan permintaan

                //Dikirim hidden di form id tiket nya
                $idTiket = $this->_getParam('id_tiket');

                $this->checkLeader($idTiket, $idPengguna);

                $this->tiket->updateSelesaiProgrammer($idTiket, $namaPengguna);

                $tike = $this->tiket->findById($idTiket);

                //Kirim notif ke user kalo permintaannya sudah selesai
                $this->notifikasi->sendTo(
                    $tike->getIdPelapor(),
                    $tike->getNoTiket(),
                    "Permintaan dengan no tiket {$tike->getNoTiket()} sudah selesai"
                );
                // kirim notif email ke user juga
                $this->mailerService->selesaiTiket($tike->getIdPelapor(), $tike->getNoTiket());
                //Kirim ke log biasa bwang
                $request = new LogRequest();
                $request->setIdTiket($tike->getId());
                $request->setPengguna($namaPengguna);
                $request->setIdSubKategori(1);
                $request->setIdStatusTiket($tike->getIdStatusTiket());
                $request->setIdStatusTiketInternal($tike->getIdStatusTiketInternal());
                $request->setKeterangan("Permintaan sudah diselesaikan");
                $this->logService->addLogData($request);

                $_SESSION['flash'] = "Project Berhasil Diselesaikan";

                $this->_redirect('/programmer/permintaan/detail/' . $idTiket);

            }catch (Exception $exception){
                $_SESSION['flash_error'] = $exception->getMessage();
            }
        }
    }

    //Ini tombol konfirmasi buat leader programmer
    //localhost/programmer/konfirmasi
    public function konfirmasiAction()
    {
        // $this->_helper->getHelper('layout')->disableLayout();
        if ($this->getRequest()->isPost()) {
            //kalo udah pake session pake zend_auth
            $idPengguna = Zend_Auth::getInstance()->getIdentity()->id;
            $namaPengguna = Zend_Auth::getInstance()->getIdentity()->nama_lengkap;

            try {
                //dikirim secara hidden di form
                $idTiket = $this->_getParam('id_tiket');

                //Yang harus dikirim di form
                $idAplikasi = $this->_getParam('id_aplikasi');

                $this->checkLeader($idTiket, $idPengguna);

                $this->tiket->updateIdAplikasi($idTiket, $idAplikasi, $namaPengguna);

                $tiket = $this->tiket->findById($idTiket);
                $aplikasi = $this->aplikasi->findById($idAplikasi);

                //Kirim notifikasi ke user kalo permintaannya sudah menjadi apalikasi apa
                $this->notifikasi->sendTo($tiket->getIdPelapor(), $tiket->getNoTiket(),
                    "Permintaan dengan no tiket {$tiket->getNoTiket()} ditentukan sebagai aplikasi {$aplikasi->getAplikasi()}");

                //Masukin ke log kalo udah ditentuin aplikasinya bwang
                $request = new LogRequest();
                $request->setIdTiket($tiket->getId());
                $request->setIdStatusTiketInternal($tiket->getIdStatusTiketInternal());
                $request->setIdStatusTiket($tiket->getIdStatusTiket());
                $request->setPengguna($namaPengguna);
                $request->setIdAplikasi($idAplikasi);
                $request->setKeterangan("Permintaan ditentukan sebagai aplikasi {$aplikasi->getAplikasi()}");
                $this->logService->addLogData($request);

                $_SESSION['flash'] = "Permintaan Dikonfirmasi Menjadi Aplikasi {$aplikasi->getAplikasi()}";

                $this->_redirect('/programmer/permintaan/detail/' . $idTiket);
            } catch (Exception $exception) {
                $_SESSION['flash_error'] = $exception->getMessage();
            }
        }
    }

    //Ini timbol tambah tim buat leader programmer
    //localhost/programmer/tim/:id_tiket
    public function timAction()
    {

        //Memperbaharui title
        $this->view->title = 'Tim Programmer';

        $this->_helper->viewRenderer->setRender('tim');

        try {
            //Ini sementara, nanti kalo dah pake session hapus aja
            // $idPengguna = $this->getRequest()->getParam('id_pengguna');

            //Kalo udah pake session ini yang dipake
            $idPengguna = Zend_Auth::getInstance()->getIdentity()->id;

            //Data yang harus dapet id_tiket
            $idTiket = $this->_getParam('id_tiket');

            $tiket = $this->tiket->findById($idTiket);

            if ($tiket == null) {
                throw new Exception("Data Tidak Ditemukan");
            }

            //Ini nanti misal belum dikonfirmasi aplikasi apa dan ngakses secara paksa
            // bakal di redirect ke permintaan detail buat nuruh konfirmasi aplikasinya
            if ($tiket->getIdAplikasi() == null) {
                $_SESSION['flash_error'] = "Tentukan Aplikasi Terlebih Dahulu";
                $this->_redirect("/programmer/permintaan/detail/{$idTiket}");
            }

            $tim = $this->timProgrammer->findById($tiket->getIdTimProgrammer());

            $programmer = $this->programmer->findByIdTimAndIdPengguna($tiket->getIdTimProgrammer(), $idPengguna);
            //kalo session pengguna bukan leader di project ini maka dia akan dilempar ke halaman 403 forbidden
            if ($programmer->jabatan != 'leader') {
                $this->_redirect('error');
            }

            $data = [
                'id_tiket' => $tiket->getId(),
                'id_tim_programmer' => $tiket->getIdTimProgrammer(),
                'nama_tim' => $tim->getNamaTim(),
                'judul' => $tiket->getKeterangan()
            ];

            if ($this->aplikasi->findById($tiket->getIdAplikasi())->getDeveloper() == 'Internal') {
                $listProgrammer = $this->programmer->getAllProgrammerForAssignedProgrammer();

                $data['list_programmer'] = $listProgrammer;
            } elseif ($this->aplikasi->findById($tiket->getIdAplikasi())->getDeveloper() == 'External') {
                $listProgrammer = $this->programmerExternal->getListProgrammerExternal();

                $data['list_programmer'] = $listProgrammer;
            }

            /**
             * Data yang dikirim meliputi :
             * id_tiket
             * id_tim_programmer
             * nama_tim
             * judul
             * list_programmer, dalam bentuk array
             */

            // var_dump($data);
            // die;

            //Di form methodnya post, actionnya '/programmer/kirim-tim'
            //yang perlu dikirim di form id_tim_programmer sama programmer yang berisikan list id_pengguna yang ditambahin.
            //name programmer = programmer[]
            $this->view->data = $data;
        } catch (Exception $exception) {
//            var_dump($exception->getMessage());
//            die;
            $_SESSION['flash_error'] = $exception->getMessage();
        }
    }

    //localhost/programmer/kirim-tim
    public function kirimTimAction()
    {
        if ($this->getRequest()->isPost()) {
            //Data yang perlu ada dari post itu id_tim_programmer sama programmer[]
            $idTim = $this->_getParam('id_tim_programmer');
            $programmer = $this->_getParam('programmer');

            $noTiket = explode('-', $this->timProgrammer->findById($idTim)->getNamaTim())[0];
            $tiket = $this->tiket->findByNoTiket($noTiket);

            //kalo udah pake session ini hapus aja
            $pengguna = Zend_Auth::getInstance()->getIdentity()->nama_lengkap;

            //Buat object log
            $request = new LogRequest();
            $request->setIdTiket($tiket->getId());
            $request->setIdAplikasi($tiket->getIdAplikasi());
            $request->setIdTimProgrammer($tiket->getIdTimProgrammer());
            $request->setIdStatusTiket($tiket->getIdStatusTiket());
            $request->setIdStatusTiketInternal($tiket->getIdStatusTiketInternal());
            $request->setPengguna($pengguna);

            //Ini fungsi buat nambahin programmer ke tim programmer
            foreach ($programmer as $pro) {
                $this->programmer->addMultipleProgrammer($idTim, $pro);
                $this->notifikasi->sendTo($pro, $tiket->getNoTiket(),
                    "Ada permintaan baru");

                //Kirim ke log juga dong
                $request->setIdProgrammer($pro);
                $request->setKeterangan("Menambahkan {$this->pengguna->findById($pro)->getNamaLengkap()} sebagai programmer");
                $this->logService->addLogData($request);
            }

            //Pesan yang dikasih kalo berhasil nambahin sebelum redirect
            $_SESSION['flash'] = "Sukses Tambah Programmer";

            //Nanti kalo udah pake session ini yang dipake
            $this->_redirect('/programmer/permintaan');
        }
    }

    //localhost/programmer/project/:id_tiket
    public function projectAction()
    {
        //Memperbaharui title
        $this->view->title = 'Project';

        $this->_helper->viewRenderer->setRender('project');
        try {
            //Yang perlu didapetin disini itu id_tiket nya
            $idTiket = $this->getRequest()->getParam('id_tiket');
            $tiket = $this->tiket->findById($idTiket);

            //kalo udah pake session pake zend_auth
            $idPengguna = Zend_Auth::getInstance()->getIdentity()->id;

            $peran = $this->programmer->getPeranByIdPenggunaAndIdTimProgrammer($idPengguna, $tiket->getIdTimProgrammer());

            if (!$peran) {
                throw new Exception("Data Tidak Ditemukan");
            }

            if ($tiket == null) {
                throw new Exception("Data Tidak Ditemukan");
            }

            $tahapan = $this->tahapanService->getTahapan($tiket);

            $tahap = $tahapan[0];

            $detail = isset($tahapan[1]) ? $tahapan[1] : null;

            //Ini persentase yang didapet dari sub tahapan
            $listTahapan = $this->tahapanService->getPersentaseSubTahapan($detail, $tahap)[0];

            /**
             * Tambahan aktif true or false, kalo true berarti dia aktif, kalo false engga
             */
            $data = [
                'peran' => $peran,
                'id_tiket' => $tiket->getId(),
                'judul' => $tiket->getKeterangan(),
                'aplikasi' => ($tiket->getIdAplikasi() != null) ?
                    $this->aplikasi->findById($tiket->getIdAplikasi())->getAplikasi() : null,
                'reference_to' => $tiket->getReferenceTo(),
                'reference_by' => $tiket->getReferenceBy(),
                'aktif' => !(($tiket->getReferenceTo() != null || $tiket->getIdStatusTiketInternal() == 6)),
                'tahapan' => $listTahapan
            ];

            /**
             * Data yang dikirim terdiri dari :
             * peran,
             * id_tiket,
             * judul,
             * tahapan, dalam, bentuk list yang isinya id_tahapan, tahapan, persentase, tahapan_selesai, tahapan_total
             */

            $this->view->data = $data;
        } catch (Exception $exception) {

            $_SESSION['flash_error'] = $exception->getMessage();
        }
    }

    //localhost/programmer/project-detail/:id_tiket/:id_tahapan
    public function projectDetailAction()
    {
        //Memperbaharui title
        $this->view->title = 'Detail Project';

        $this->_helper->viewRenderer->setRender('project-detail');
        try {

            $idPengguna = Zend_Auth::getInstance()->getIdentity()->id;

            //Data yang harus didapetin id_tiket sama id_tahapan
            $idTiket = $this->getRequest()->getParam('id_tiket');
            $idTahapan = $this->getRequest()->getParam('id_tahapan');

            $tiket = $this->tiket->findById($idTiket);

            $peran = $this->programmer->getPeranByIdPenggunaAndIdTimProgrammer($idPengguna, $tiket->getIdTimProgrammer());

            if (!$peran) {
                throw new Exception("Data Tidak Ditemukan");
            }

            if ($tiket == null) {
                throw new Exception("Data Tidak Ditemukan");
            }

            $tahapan = $this->tahapanService->getTahapan($tiket);

            $detail = isset($tahapan[1]) ? $tahapan[1] : null;

            //Kalo ada detailnya dilakuin foreach
            if ($detail) {
                //loop untuk ngambil data sub tahapan sesuai dengan tahapan yang dipilih, ini juga ngirim data todo list kalo ada
                $counter = 1;
                foreach ($detail as $det) {
                    if ($det['id_tahapan'] == $idTahapan) {
                        $listSubTahapan[$counter] = $det;
                        $counter++;
                    }
                }
            }

            /**
             * Data yang dikirim meliputi
             * id_tiket
             * id_tahapan
             * nama_tahapan
             * project
             * peran, digunakan untuk mengecek, kalo dia leader nanti bakal ada tombol tambah, edit dan hapus untuk sub tahapan sama todo list
             * list_sub_tahapan, dalam bentuk array yang berisikan sub tahapan kalo ada, selain itu juga ada todo_list tiap sub_tahapan yang bentuknya array juga
             */
            $data = [
                'id_tiket' => $idTiket,
                'id_tahapan' => $idTahapan,
                'nama_tahapan' => $this->tahapanService->getDataById($idTahapan)->tahapan,
                'project' => $tiket->getKeterangan(),
                'peran' => $peran,
                'list_sub_tahapan' => isset($listSubTahapan) ? $listSubTahapan : null,
                'aplikasi' => ($tiket->getIdAplikasi() != null) ?
                    $this->aplikasi->findById($tiket->getIdAplikasi())->getAplikasi() : null,
                'list_jenis_dokumen' => $this->jenisDokumen->getListJenisDokumen()
            ];
            // var_dump($data['list_sub_tahapan']);
            // die;

            //nampilinnya pake foreach buat list_sub_tahapan, tapi di cek juga dulu null engga, trus di cek juga list_sub_tahapan['todo_list] kalo ga null di foreach juga
            // var_dump($data);
            // die;

            //Form tambah sub_tahapan method = post, action = '/programmer/tambah-sub-tahapan'
            //Form edit sub_tahapan method = post, action = '/programmer/edit-sub-tahapan'
            //Form hapus sub_tahapan method = post, action = '/programmer/hapus-sub-tahapan'
            //Form tambah todo_list method = post, action = '/programmer/tambah-todo-list'
            //Form edit todo_list method = post, action = '/programmer/edit-todo-list'
            //Form hapus todo_list method = post, action = '/programmer/hapus-todo-list'
            //Form revisi todo_list method = post, action = '/programmer/revisi-todo-list'
            //Form selesai todo_list method = post, action = 'programmer/selesai-todo-list'
            $this->view->data = $data;
        } catch (Exception $exception) {
            // var_dump($exception->getMessage());
            // die;
            $_SESSION['flash_error'] = $exception->getMessage();
        }
    }

    //Button untuk tambah sub tahapan
    public function tambahSubTahapanAction()
    {
        if ($this->getRequest()->isPost()) {
            //kalo udah pake session pake zend_auth
            $idPengguna = Zend_Auth::getInstance()->getIdentity()->id;
            $namaPengguna = Zend_Auth::getInstance()->getIdentity()->nama_lengkap;

            //Yang harus diisi di form nya
            $subTahapan = $this->_getParam('sub_tahapan'); //Ini isinya nama dari sub tahapannya

            //id_tiket sama id_tahapan dikirim lewat form di hidden input
            $idTiket = $this->_getParam('id_tiket');
            $idTahapan = $this->_getParam('id_tahapan');

            //Cek dia leader atau bukan, kalo bukan bakal di redirect ke forbidden
            $this->checkLeader($idTiket, $idPengguna);

            $request = new SubTahapanAddRequest();
            $request->setIdTahapan($idTahapan);
            $request->setIdTiket($idTiket);
            $request->setSubTahapan($subTahapan);
            $request->setUserInput($namaPengguna);
            $request->setUserUpdate($namaPengguna);

            $dataSubTahapan = $this->subTahapan->addData($request);

            $tiket = $this->tiket->findById($idTiket);

            //Ini kirim notif ke user kalo ada sub tahapan baru
            $this->notifikasi->sendTo($tiket->getIdPelapor(), $tiket->getNoTiket(),
                "Ada sub tahapan baru : {$subTahapan}");

            //Kirim notif ke semua programmer kalo ada sub tahapan baru
            $this->notifikasi->sendToAllProgrammerByIdTimProgrammer($tiket->getNoTiket(),
                "Ada sub tahapan baru : {$subTahapan}", $tiket->getIdTimProgrammer());

            //Kirim log biasa
            $request = new LogRequest();
            $request->setIdTiket($tiket->getId());
            $request->setPengguna($namaPengguna);
            $request->setIdStatusTiketInternal($tiket->getIdStatusTiketInternal());
            $request->setIdStatusTiket($tiket->getIdStatusTiket());
            $request->setIdSubTahapan($dataSubTahapan->getId());
            $request->setIdTahapan($idTahapan);
            $request->setKeterangan("Sub tahapan baru '{$subTahapan}'");
            $this->logService->addLogData($request);

            $_SESSION['flash'] = "Berhasil Menambah Sub Tahapan";

            $this->_redirect('/programmer/project-detail/id_tiket/' . $idTiket . '/id_tahapan/' . $idTahapan);
        }
    }

    //Button untuk edit sub tahapan
    public function editSubTahapanAction()
    {
        try {
            if ($this->getRequest()->isPost()) {
                //kalo udah pake session pake zend_auth
                $idPengguna = Zend_Auth::getInstance()->getIdentity()->id;
                $namaPengguna = Zend_Auth::getInstance()->getIdentity()->nama_lengkap;

                //Yang harus diisi di form nya
                $subTahapan = $this->_getParam('sub_tahapan'); //Ini isinya nama dari sub tahapannya

                //id_tiket, id_sub_tahapan sama id_tahapan dikirim lewat form di hidden input
                $idTiket = $this->_getParam('id_tiket');
                $idSubTahapan = $this->_getParam('id_sub_tahapan');
                $idTahapan = $this->_getParam('id_tahapan');

                $dataSubTahapan = $this->subTahapan->findById($idSubTahapan);

                //Cek dia leader atau bukan, kalo bukan bakal di redirect ke forbidden
                $this->checkLeader($idTiket, $idPengguna);

                $request = new SubTahapanUpdateSubTahapanRequest();
                $request->setId($idSubTahapan);
                $request->setSubTahapan($subTahapan);
                $request->setUserUpdate($namaPengguna);

                $this->subTahapan->updateSubTahapan($request);

                $tiket = $this->tiket->findById($idTiket);

                //Kirim log biasa
                $request = new LogRequest();
                $request->setIdTiket($tiket->getId());
                $request->setPengguna($namaPengguna);
                $request->setIdStatusTiketInternal($tiket->getIdStatusTiketInternal());
                $request->setIdStatusTiket($tiket->getIdStatusTiket());
                $request->setIdSubTahapan($idSubTahapan);
                $request->setIdTahapan($idTahapan);
                $request->setKeterangan("Sub tahapan '{$dataSubTahapan->getSubTahapan()}' diedit menjadi '{$subTahapan}'");
                $this->logService->addLogData($request);

                $_SESSION['flash'] = "Berhasil Mengedit Sub Tahapan";

                $this->_redirect('/programmer/project-detail/id_tiket/' . $idTiket . '/id_tahapan/' . $idTahapan);
            }
        } catch (Exception $exception) {
            $_SESSION['flash_error'] = $exception->getMessage();
        }
    }

    //Button untuk hapus sub tahapan
    public function hapusSubTahapanAction()
    {
        try {
            if ($this->getRequest()->isPost()) {
                //kalo udah pake session pake zend_auth
                $idPengguna = Zend_Auth::getInstance()->getIdentity()->id;
                $namaPengguna = Zend_Auth::getInstance()->getIdentity()->nama_lengkap;

                //Nanti pas tombol hapus dibuat form aja, tapi isinya cuma nampilin pesan konfirmasi kayak "Apakah anda yakin untuk menghapus"
                //Tapi disitu ada hidden input kayak dibawah ini
                //id_tiket, id_sub_tahapan sama id_tahapan dikirim lewat form di hidden input
                $idTiket = $this->_getParam('id_tiket');
                $idSubTahapan = $this->_getParam('id_sub_tahapan');
                $idTahapan = $this->_getParam('id_tahapan');

                $dataSubTahapan = $this->subTahapan->findById($idSubTahapan);

                //Cek dia leader atau bukan, kalo bukan bakal di redirect ke forbidden
                $this->checkLeader($idTiket, $idPengguna);

                $this->subTahapan->delete($idSubTahapan);

                $tiket = $this->tiket->findById($idTiket);

                //Sampe bosen naro log
                //Kirim log biasa
                $request = new LogRequest();
                $request->setIdTiket($tiket->getId());
                $request->setPengguna($namaPengguna);
                $request->setIdStatusTiketInternal($tiket->getIdStatusTiketInternal());
                $request->setIdStatusTiket($tiket->getIdStatusTiket());
                $request->setIdSubTahapan($idSubTahapan);
                $request->setIdTahapan($idTahapan);
                $request->setKeterangan("Sub tahapan '{$dataSubTahapan->getSubTahapan()}' dihapus");
                $this->logService->addLogData($request);

                $_SESSION['flash'] = "Berhasil Menghapus Sub Tahapan";

                $this->_redirect('/programmer/project-detail/id_tiket/' . $idTiket . '/id_tahapan/' . $idTahapan);
            }
        } catch (Exception $exception) {
            $_SESSION['flash_error'] = $exception->getMessage();
        }
    }

    //Button untuk Tambah todo list : butuh id_sub_tahapan
    public function tambahTodoListAction()
    {
        if ($this->getRequest()->isPost()) {
            //kalo udah pake session pake zend_auth
            $idPengguna = Zend_Auth::getInstance()->getIdentity()->id;
            $namaPengguna = Zend_Auth::getInstance()->getIdentity()->nama_lengkap;

            //Yang harus diisi di form nya
            $todoList = $this->_getParam('todo_list'); //Ini isinya nama dari todo list

            //id_tiket, id_sub_tahapan sama id_tahapan dikirim lewat form di hidden input
            $idTiket = $this->_getParam('id_tiket');
            $idSubTahapan = $this->_getParam('id_sub_tahapan');
            $idTahapan = $this->_getParam('id_tahapan');

            //Cek dia leader atau bukan, kalo bukan bakal di redirect ke forbidden
            $this->checkLeader($idTiket, $idPengguna);

            $request = new TodoListAddRequest();
            $request->setIdSubTahapan($idSubTahapan);
            $request->setTodoList($todoList);
            $request->setUserInput($namaPengguna);
            $request->setUserUpdate($namaPengguna);

            $dataTodoList = $this->todoList->addData($request);

            $tiket = $this->tiket->findById($idTiket);

            //Ini kirim ke programmer kalo ada todo list baru
            $this->notifikasi->sendToAllProgrammerByIdTimProgrammer($tiket->getNoTiket(),
                "Ada todo list baru : {$todoList}", $tiket->getIdTimProgrammer());

            //lelah log
            //Kirim log biasa
            $request = new LogRequest();
            $request->setIdTiket($tiket->getId());
            $request->setPengguna($namaPengguna);
            $request->setIdStatusTiketInternal($tiket->getIdStatusTiketInternal());
            $request->setIdStatusTiket($tiket->getIdStatusTiket());
            $request->setIdTodoList($dataTodoList->getId());
            $request->setIdSubTahapan($idSubTahapan);
            $request->setIdTahapan($idTahapan);
            $request->setKeterangan("Todo list baru '{$todoList}'");
            $this->logService->addLogData($request);

            $_SESSION['flash'] = "Berhasil Menambah Todo List";

            $this->_redirect('/programmer/project-detail/id_tiket/' . $idTiket . '/id_tahapan/' . $idTahapan);
        }
    }

    //Button untuk edit todo_list
    public function editTodoListAction()
    {
        if ($this->getRequest()->isPost()) {
            //kalo udah pake session pake zend_auth
            $idPengguna = Zend_Auth::getInstance()->getIdentity()->id;
            $namaPengguna = Zend_Auth::getInstance()->getIdentity()->nama_lengkap;

            //Yang harus diisi di form nya
            $todoList = $this->_getParam('todo_list'); //Ini isinya nama dari todo list

            //id_tiket, id_todo_list sama id_tahapan dikirim lewat form di hidden input
            $idTiket = $this->_getParam('id_tiket');
            $idTodoList = $this->_getParam('id_todo_list');
            $idTahapan = $this->_getParam('id_tahapan');

            $dataTodoList = $this->todoList->findById($idTodoList);
            $tiket = $this->tiket->findById($idTiket);

            //Cek dia leader atau bukan, kalo bukan bakal di redirect ke forbidden
            $this->checkLeader($idTiket, $idPengguna);

            $request = new TodoListUpdateTodoListRequest();
            $request->setId($idTodoList);
            $request->setTodoList($todoList);
            $request->setUserUpdate($namaPengguna);

            $this->todoList->updateTodoList($request);

            //Biasa
            //Kirim log biasa
            $request = new LogRequest();
            $request->setIdTiket($tiket->getId());
            $request->setPengguna($namaPengguna);
            $request->setIdStatusTiketInternal($tiket->getIdStatusTiketInternal());
            $request->setIdTodoList($idTodoList);
            $request->setIdStatusTiket($tiket->getIdStatusTiket());
            $request->setIdSubTahapan($dataTodoList->getIdSubTahapan());
            $request->setIdTahapan($idTahapan);
            $request->setKeterangan("Todo list '{$dataTodoList->getTodoList()}' diedit menjadi '{$todoList}'");
            $this->logService->addLogData($request);

            $_SESSION['flash'] = "Berhasil Mengedit Todo List";

            $this->_redirect('/programmer/project-detail/id_tiket/' . $idTiket . '/id_tahapan/' . $idTahapan);
        }
    }

    //Button untuk hapus todo_list
    public function hapusTodoListAction()
    {
        if ($this->getRequest()->isPost()) {
            //kalo udah pake session pake zend_auth
            $idPengguna = Zend_Auth::getInstance()->getIdentity()->id;
            $namaPengguna = Zend_Auth::getInstance()->getIdentity()->nama_lengkap;

            //Nanti pas tombol hapus dibuat form aja, tapi isinya cuma nampilin pesan konfirmasi kayak "Apakah anda yakin untuk menghapus"
            //Tapi disitu ada hidden input kayak dibawah ini
            //id_tiket, id_todo_list sama id_tahapan dikirim lewat form di hidden input
            $idTiket = $this->_getParam('id_tiket');
            $idTodoList = $this->_getParam('id_todo_list');
            $idTahapan = $this->_getParam('id_tahapan');

            $dataTodoList = $this->todoList->findById($idTodoList);
            $tiket = $this->tiket->findById($idTiket);

            //Cek dia leader atau bukan, kalo bukan bakal di redirect ke forbidden
            $this->checkLeader($idTiket, $idPengguna);

            $this->todoList->delete($idTodoList);

            //Biasa
            //Kirim log biasa
            $request = new LogRequest();
            $request->setIdTiket($tiket->getId());
            $request->setPengguna($namaPengguna);
            $request->setIdStatusTiketInternal($tiket->getIdStatusTiketInternal());
            $request->setIdTodoList($idTodoList);
            $request->setIdStatusTiket($tiket->getIdStatusTiket());
            $request->setIdSubTahapan($dataTodoList->getIdSubTahapan());
            $request->setIdTahapan($idTahapan);
            $request->setKeterangan("Todo list '{$dataTodoList->getTodoList()}' dihapus");
            $this->logService->addLogData($request);

            $_SESSION['flash'] = "Berhasil Menghapus Todo List";

            $this->_redirect('/programmer/project-detail/id_tiket/' . $idTiket . '/id_tahapan/' . $idTahapan);
        }
    }

    //Button untuk revisi todo_list
    public function revisiTodoListAction()
    {
        if ($this->getRequest()->isPost()) {
            //kalo udah pake session pake zend_auth
            $idPengguna = Zend_Auth::getInstance()->getIdentity()->id;
            $namaPengguna = Zend_Auth::getInstance()->getIdentity()->nama_lengkap;

            //Yang harus diisi di form nya
            $deskripsiRevisi = $this->_getParam('deskripsi_revisi'); //Ini isinya deskripsi revisi

            //id_tiket, id_todo_list sama id_tahapan dikirim lewat form di hidden input
            $idTiket = $this->_getParam('id_tiket');
            $idTodoList = $this->_getParam('id_todo_list');
            $idTahapan = $this->_getParam('id_tahapan');

            $dataTodoList = $this->todoList->findById($idTodoList);

            //Cek dia leader atau bukan, kalo bukan bakal di redirect ke forbidden
            $this->checkLeader($idTiket, $idPengguna);

            $request = new TodoListUpdateRevisiRequest();
            $request->setId($idTodoList);
            $request->setUserUpdate($namaPengguna);
            $request->setDeskripsiRevisi($deskripsiRevisi);

            $this->todoList->updateRevisi($request);

            $tiket = $this->tiket->findById($idTiket);
            $todoList = $this->todoList->findById($idTodoList);

            //Ini kirim ke programmer kalo todo_list ini ada revisi
            $this->notifikasi->sendToAllProgrammerByIdTimProgrammer($tiket->getNoTiket(),
                "Terdapat revisi pada todo list : {$todoList->getTodoList()} dengan keterangan : {$deskripsiRevisi}", $tiket->getIdTimProgrammer());

            //Biasa
            //Kirim log biasa
            $request = new LogRequest();
            $request->setIdTiket($tiket->getId());
            $request->setPengguna($namaPengguna);
            $request->setIdStatusTiketInternal($tiket->getIdStatusTiketInternal());
            $request->setIdTodoList($idTodoList);
            $request->setIdStatusTiket($tiket->getIdStatusTiket());
            $request->setIdSubTahapan($dataTodoList->getIdSubTahapan());
            $request->setIdTahapan($idTahapan);
            $request->setKeterangan("Todo list '{$dataTodoList->getTodoList()}' direvisi");
            $this->logService->addLogData($request);

            $_SESSION['flash'] = "Berhasil Merevisi Todo List";

            $this->_redirect('/programmer/project-detail/id_tiket/' . $idTiket . '/id_tahapan/' . $idTahapan);
        }
    }

    //Button untuk selesai todo_list yang ngirim dokumen juga
    //Nanti di project-detail todo_listnya kasih if. Kalo statusnya 1 atau 2 dia baru ada tombol selesai do todo list
    public function selesaiTodoListAction()
    {
        if ($this->getRequest()->isPost()) {
            //kalo udah pake session pake zend_auth
            $idPengguna = Zend_Auth::getInstance()->getIdentity()->id;
            $namaPengguna = Zend_Auth::getInstance()->getIdentity()->nama_lengkap;

            //Yang harus diisi di form nya
            $keterangan = $this->_getParam('keterangan'); //Ini isinya keterangan

            //id_tiket, id_sub_tahapan, id_jenis_dokumen sama id_tahapan dikirim lewat form di hidden input
            $idTiket = $this->_getParam('id_tiket');
            $idTodoList = $this->_getParam('id_todo_list');
            $idTahapan = $this->_getParam('id_tahapan');
            $idJenisDokumen = $this->_getParam('id_jenis_dokumen');

            $todoList = $this->todoList->findById($idTodoList);

            //Buat object LogRequest
            $logRequest = new LogRequest();
            $logRequest->setIdTodoList($idTodoList);
            $logRequest->setIdTiket($idTiket);
            $logRequest->setIdSubKategori(1);
            $logRequest->setPengguna($namaPengguna);
            $logRequest->setKeterangan($todoList->getTodoList() . " Selesai");
            $logRequest->setTanggalInput(date('Y-m-d H:i:s'));
            $logRequest->setIdPengguna($idPengguna);
            $logRequest->setIdProgrammer($idPengguna);
            $logRequest->setIdTahapan($idTahapan);
            $logRequest->setIdTimProgrammer($this->tiket->findById($idTiket)->getIdTimProgrammer());
            $logRequest->setIdVia($this->tiket->findById($idTiket)->getIdVia());
            $logRequest->setIdSubTahapan($todoList->getIdSubTahapan());

            //Dicek apakah di formnya user nyantumin dokumen
            if (!empty($_FILES['dokumen']['name'])) {
                //Kalo di formnya nyantumin dokumen maka akan disimpan di database dan dipindah lokasinya
                $name = explode('.', $_FILES['dokumen']['name']);

                $dokumen = $this->fileManager->saveTodoListDokumen($_FILES['dokumen']); //Butuh dokumen juga, name = dokumen

                $request = new TodoListDokumenRequest();
                $request->setIdTodoList($idTodoList);
                $request->setIdJenisDokumen($idJenisDokumen);
                $request->setOriginalName($name[0]);
                $request->setDokumenName($dokumen['file_name']);
                $request->setUserInput($namaPengguna);
                $request->setDokumenSize($dokumen['file_size']);
                $request->setDokumenType($dokumen['file_type']);

                $idTodoListDokumen = $this->todoListDokumen->addData($request)->getId();

                //Kalo ada dokumen dimasukkin ke log juga datanya
                $logRequest->setIdTodoListDokumen($idTodoListDokumen);
                $logRequest->setFileName($dokumen['file_name']);
                $logRequest->setFileSize($dokumen['file_size']);
                $logRequest->setFileType($dokumen['file_type']);
                $logRequest->setFileOriginalName($name[0]);
            }

            //Kalo gada dokumen langsung ini yang jalan
            $request = new TodoListUpdateSelesaiRequest();
            $request->setUserUpdate($namaPengguna);
            $request->setIdProgrammer($idPengguna);
            $request->setId($idTodoList);
            $request->setKeterangan($keterangan);

            $this->todoList->updateSelesai($request);

            $tiket = $this->tiket->findById($idTiket);
            $leader = $this->programmer->getLeaderProgrammerByIdTim($tiket->getIdTimProgrammer());

            //Ini kirim notif ke leader kalo todo_list udah diselesaikan oleh
            $this->notifikasi->sendTo($leader->id_pengguna, $tiket->getNoTiket(),
                "Todo list : {$todoList->getTodoList()} telah diselesaikan oleh {$namaPengguna}");

            //Habis itu dimasukkin ke log aktifitasnya
            $logRequest->setIdStatusTiket($tiket->getIdStatusTiket());
            $logRequest->setIdStatusTiketInternal($tiket->getIdStatusTiketInternal());
            $this->logService->addLogData($logRequest);

            $_SESSION['flash'] = "Berhasil Menyelesaikan Todo List";

            $this->_redirect('/programmer/project-detail/id_tiket/' . $idTiket . '/id_tahapan/' . $idTahapan);
        }
    }

    //Ini nanti bagian aplikasi semua

    //localhost/programmer/aplikasi
    public function aplikasiAction()
    {
        //Memperbaharui title
        $this->view->title = 'Daftar Aplikasi';

        $this->_helper->viewRenderer->setRender('aplikasi');

        try {

            $this->checkDeveloper();

            //Kalo ada pesan di parameter diambil disini
            $pesan = $this->getRequest()->getParam('pesan');

            $datas = $this->aplikasi->getAllData();

            if ($datas == null) {
                throw new Exception("Belum Ada Daftar Aplikasi");
            }

            $counter = 1;
            foreach ($datas as $d) {
                $data[$counter] = [
                    'id' => $d->id,
                    'aplikasi' => $d->aplikasi,
                    'status_aplikasi' => $this->statusAplikasi->findById($d->id_status_aplikasi)->getStatusAplikasi(),
                    'jenis_aplikasi' => $d->jenis_aplikasi,
                    'developer' => $d->developer,
                    'pengelola' => $d->pengelola,
                    'tanggal' => explode(' ', $d->tanggal_input)[0],
                    'jam' => explode(' ', $d->tanggal_input)[1]
                ];
                $counter++;
            }

            //Kalo ada pesannya nanti dikirim ke view pake $this->pesan
            if ($pesan) {
                $this->view->pesan = $pesan;
            }
            $this->view->data = $data;
        } catch (Exception $exception) {

            $_SESSION['flash_error'] = $exception->getMessage();
        }
    }

    //localhost/programmer/tambah-aplikasi
    public function tambahAplikasiAction()
    {
        //Memperbaharui title
        $this->view->title = 'Tambah Aplikasi';

        $this->_helper->viewRenderer->setRender('tambah-aplikasi');
        $this->checkDeveloper();

        $data = [
            'list_status_aplikasi' => $this->statusAplikasi->getListStatusAplikasi(),
            'list_jenis_aplikasi' => ['Front-End', 'Back-End', 'Full-Stack'],
            'list_developer' => ['Internal', 'External'],
        ];

        $this->view->data = $data;
    }

    /**
     * method = post
     * action = '/programmer/proses-tambah-aplikasi'
     */
    //localhost/programmer/proses-tambah-aplikasi
    public function prosesTambahAplikasiAction()
    {
        if ($this->getRequest()->isPost()) {

            $this->checkDeveloper();

            //kalo udah pake session pake zend_auth
            $namaPengguna = Zend_Auth::getInstance()->getIdentity()->nama_lengkap;
            // $namaPengguna = $this->_getParam('nama_pengguna');

            $request = new AplikasiAddDataRequest();
            $request->setUserUpdate($namaPengguna);
            $request->setUserInput($namaPengguna);

            /**
             * Yang harus dikirim di form terdiri dari :
             * aplikasi, name = aplikasi
             * id_status_aplikasi, name = id_status_aplikasi
             * jenis_aplikasi, name = jenis_aplikasi
             * developer, name = developer
             * pengelola, name = pengelola
             */
            $request->setAplikasi($this->_getParam('aplikasi'));
            $request->setIdStatusAplikasi($this->_getParam('id_status_aplikasi'));
            $request->setJenisAplikasi($this->_getParam('jenis_aplikasi'));
            $request->setDeveloper($this->_getParam('developer'));
            $request->setPengelola($this->_getParam('pengelola'));

            $this->aplikasi->addData($request);

            //Nanti redirect ke halaman awal aplikasi
            $_SESSION['flash'] = "Berhasil Menambahkan Aplikasi";
            $this->_redirect('/programmer/aplikasi');
        }
    }

    //localhost/programmer/edit-aplikasi/id_aplikasi/$id_aplikasi
    public function editAplikasiAction()
    {
        //Memperbaharui title
        $this->view->title = 'Edit Aplikasi';

        $this->checkDeveloper();
        $this->_helper->viewRenderer->setRender('edit-aplikasi');
        // $this->_helper->getHelper('layout')->disableLayout();

        try {
            $idAplikasi = $this->getRequest()->getParam('id_aplikasi');

            $data = $this->aplikasi->findById($idAplikasi);

            if ($data == null) {
                throw new Exception("Data Tidak Ditemukan");
            }

            $result = [
                'id' => $data->getId(),
                'aplikasi' => $data->getAplikasi(),
                'status_aplikasi' => $this->statusAplikasi->findById($data->getIdStatusAplikasi())->getStatusAplikasi(),
                'jenis_aplikasi' => $data->getJenisAplikasi(),
                'developer' => $data->getDeveloper(),
                'pengelola' => $data->getPengelola(),
                'list_status_aplikasi' => $this->statusAplikasi->getListStatusAplikasi(),
                'list_jenis_aplikasi' => ['Front-End', 'Back-End', 'Full-Stack'],
                'list_developer' => ['Internal', 'External'],
            ];

            $this->view->data = $result;
        } catch (Exception $exception) {

            $_SESSION['flash_error'] = $exception->getMessage();
        }
    }

    /**
     * method = post
     * action = '/programmer/proses-edit-aplikasi'
     */
    //localhost/programmer/proses-edit-aplikasi
    public function prosesEditAplikasiAction()
    {
        if ($this->getRequest()->isPost()) {

            $this->checkDeveloper();

            //kalo udah pake session pake zend_auth
            //$namaPengguna = Zend_Auth::getInstance()->getIdentity()->nama_lengkap;
            $namaPengguna = $this->_getParam('nama_pengguna');

            $request = new AplikasiUpdateAplikasiRequest();
            $request->setUserUpdate($namaPengguna);

            //Yang dikriim secara hidden di form
            $request->setId($this->_getParam('id'));

            /**
             * Yang harus dikriim di form terdiri dari
             * pengelola, name = pengelola
             * developer, name = developer
             * jenis_aplikasi, name = jenis_aplikasi
             * id_status_aplikasi, name = id_status_aplikasi
             * aplikasi, name = aplikasi
             */
            $request->setPengelola($this->_getParam('pengelola'));
            $request->setDeveloper($this->_getParam('developer'));
            $request->setJenisAplikasi($this->_getParam('jenis_aplikasi'));
            $request->setIdStatusAplikasi($this->_getParam('id_status_aplikasi'));
            $request->setAplikasi($this->_getParam('aplikasi'));

            $this->aplikasi->updateAplikasi($request);

            //Kalo udah nanti redirect ke halaman aplikasi lagi dengan pesan
            $_SESSION['flash'] = "Berhasil Mengedit Aplikasi";
            $this->_redirect('/programmer/aplikasi');
        }
    }

    /**
     * method = post
     * action = '/programmer/delete-aplikasi'
     */
    //localhost/programmer/delete-aplikasi
    public function deleteAplikasiAction()
    {
        //kalo udah pake session pake zend_auth
        //$namaPengguna = Zend_Auth::getInstance()->getIdentity()->nama_lengkap;
        $namaPengguna = $this->_getParam('nama_pengguna');

        if ($this->getRequest()->isPost()) {

            $this->checkDeveloper();

            try {
                //Ini nanti muncul form yang isinya cuma konfirmasi apakah yakin ingin menghapus atau tidak

                /**
                 * id_aplikasi dikirim secara hidden di form, name = id_aplikasi
                 */
                $idAplikasi = $this->_getParam('id_aplikasi');

                $_SESSION['flash'] = $this->aplikasi->softDelete($idAplikasi, $namaPengguna);

                //Kalo berhasil nanti dikirim pesan pas di redirect ke aplikasi
                $this->_redirect('/programmer/aplikasi');
            } catch (Exception $exception) {
                $_SESSION['flash'] = $exception->getMessage();

                //Kalo gagal juga dikirim pesan pas redirect ke aplikasi
                $this->_redirect('/programmer/aplikasi');
            }
        }
    }

    //Function buat check dia leader programmer atau bukan
    private function checkLeader($id_tiket, $id_pengguna)
    {
        $tiket = $this->tiket->findById($id_tiket);

        $programmer = $this->programmer->findByIdTimAndIdPengguna($tiket->getIdTimProgrammer(), $id_pengguna);
        //kalo session pengguna bukan leader di project ini maka dia akan dilempar ke halaman 403 forbidden
        if ($programmer->jabatan != 'leader') {
            $this->_redirect('/error/forbidden');
        }
    }

    //Function buat check dia internal atau bukan, kalo bukan nanti di redirect ke forbidden
    private function checkDeveloper()
    {
        if ($this->view->developer != 'Internal') {
            $this->_redirect('/error/forbidden');
        }
    }
}