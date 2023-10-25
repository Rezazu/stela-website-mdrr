<?php

class BerandaController extends Zend_Controller_Action
{
    
    public function init()
    {
        $this->_helper->_acl->allow();
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

    public function preDispatch()
    {
        $this->tiket = new Dpr_TiketService();
        $this->statusTiket = new Dpr_StatusTiketService();
        $this->dokumenLampiran = new Dpr_DokumenLampiranService();
        $this->tiketPetugas = new Dpr_TiketPetugasService();
        $this->tahapan = new Dpr_TahapanService();
        $this->timProgrammer = new Dpr_TimProgrammerService();
        $this->session = new Dpr_SessionService();

        $this->user = Zend_Auth::getInstance()->getIdentity();

        //Ngirim list_peran ke layout, nanti di layout tinggal foreach($this->>list_peran as $key => $value)
        //key buat index nya dan value buat tulisannya
        //Tapi dicek dulu pake if($this->list_peran){baru jalanin foreach nya}
        $this->view->list_peran = $this->user->peran;

        //ini ngirim list peran aktif saat ini ke layout, nanti tinggal diakses pake $this->peran_aktif
        $this->view->peran_aktif = $this->user->peran_aktif;

        //Ini ngirim status dari pengguna ke layout buat ngecek toogle on or off || 1 = aktif, 0 = tidak aktif
        $this->view->status = $this->user->status;
        $this->view->beranda = 'beranda';

        $this->view->title;
    }

    public function indexAction()
    {
        $this->view->title = 'Beranda';
    }
    public function lacakAction()
    {
        if ($this->getRequest()->isPost()) {

            $no_tiket = $this->_getParam('no_tiket');


            $this->_redirect('/beranda/captcha/no_tiket/'.$no_tiket);
        }
    }
    public function captchaAction()
    {
        $this->view->title = 'Capthca';
        // $this->_helper->getHelper('layout')->disableLayout();
        //$this->_helper->viewRenderer->setNoRender();
       // $this->_redirect('/beranda');
        //var_dump('jvaduvasvh');
        $no_tiket = $this->getRequest()->getParam('no_tiket');
        $this->view->no_tiket = $no_tiket;
    }
    public function statusAction()
    {
        // $this->_helper->getHelper('layout')->disableLayout();
        //$this->_helper->viewRenderer->setNoRender();
       // $this->_redirect('/beranda');
        //var_dump('jvaduvasvh');
    }

    /**
     * ini yang diakses pas dropdownnya berubah
     * url : /beranda/switch-role
     * method : POST
     * isi : index_peran
     * Akses pake AJAX https://stackoverflow.com/questions/28255929/pass-the-dropdown-value-into-controller-with-ajax
    */
    public function switchRoleAction()
    {
        $this->_helper->getHelper('layout')->disableLayout();
        $this->_helper->viewRenderer->setnoRender();

        if ($this->getRequest()->isPost()) {
            //Ngambil index dari dropdown yang diubah
            $index = $this->_getParam('index_peran');

            //Habis itu pake function switchTo buuat ngubah peran aktifnya
            $status = $this->session->switchTo($index);

            //nentuin response berdasarkan statusnya apa
            if ($status){
                $data = [
                    'success' => true,
                    'message' => "Berhasil berganti peran menjadi {$this->view->list_peran[$index]}"
                ];
            }else{
                $data = [
                    'success' => false,
                    'message' => "Gagal berganti peran"
                ];
            }
            //Kasih response
            $this->getResponse()->setHeader('content-type', 'application/json')->setBody(json_encode($data));
        }
    }

    /**
     * Form
     * Method GET
     * isi : no_tiket
     * Action /beranda/status-tiket
    */
    //localhost/beranda/status-tiket
    public function statusTiketAction()
    {
        //Memperbaharui title
        $this->view->title = 'Lacak Status';

        $this->_helper->viewRenderer->setRender('status');
        try {
            $noTiket = $this->getRequest()->getParam('no_tiket');

            //Cek apakah dia udah login atau belum. Kalo udah login dia di redirect ke lacak status di tiket controller
            if (Zend_Auth::getInstance()->getIdentity()->id) {
                $this->_redirect("/tiket/status/no_tiket/$noTiket");
            }

            $datas = $this->tiket->findByNoTiket($noTiket);

            if ($datas == null || $datas->getStatus() == 9){
                throw new Exception("Data Tidak Ditemukan");
            }

            //Data yang pasti dikirim ke view
            $result = [
                'id' => $datas->getId(),
                'no_tiket' => $noTiket,
                'status_tiket' => $this->statusTiket->getQueryData($datas->getIdStatusTiket())->status_tiket,
                'id_status_tiket' => $datas->getIdStatusTiket(),
                'nama_pelapor' => $datas->getNamaPelapor(),
                'bagian' => $datas->getBagianPelapor(),
                'id_pelapor' => $datas->getIdPelapor(),
                'jabatan' => $datas->getBagianPelapor(),
                'keterangan' => $datas->getKeterangan(),
                'unit_kerja' => $datas->getUnitKerjaPelapor(),
                'gedung' => $datas->getGedungPelapor(),
                'lantai' => $datas->getLantaiPelapor(),
                'ruangan' => $datas->getRuanganPelapor(),
                'hp' => $datas->getHpPelapor(),
                'email' => $datas->getEmailPelapor(),
                'solusi' => $datas->getSolusi(),
                'dokumen_lampiran' => $this->dokumenLampiran->getListDokumenLampiran($datas->getId())
            ];
                
            if ($datas->getIdSubKategori() == null) {

                //Nambahin pesan di resultnya kalo belum dikateogrisasiin
                throw new Exception('Masih Dalam Proses Review');

                $this->view->result = $result;
            } else {
                //Menambahkan data id_sub_kategori ke result
                $result['id_sub_kategori'] = $datas->getIdSubKategori();

                if ($datas->getIdSubKategori() == 2) {

                    //Ngambil list petugas menggunaan function getListPetugasStelaAktif
                    $listPetugas = $this->tiketPetugas->getListPetugasStelaAktif($datas->getId());

                    //Memasukkan data list petugas ke result jika ada, kalau tidak maka akan diisi null
                    $result['list_petugas'] = isset($listPetugas) ? $listPetugas : null;

                    $this->view->result = $result;
                } elseif ($datas->getIdSubKategori() == 1) {

                    throw new Exception("Silakan Login Untuk Melacak Tiket Ini");

                    //Memberikan Keterangan revisi yang diberikan operator jika status revisi true/1
                    if ($datas->getStatusRevisi() == 'Dalam Revisi') {
                        $result['pesan_revisi'] = $datas->getKeteranganRevisi();
                    }

                    //Mencari tahapan menggunakan function getTahapan
                    $tahapan = $this->tahapan->getTahapan($datas);

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
        }catch (Exception $exception){

            $this->view->error = $exception->getMessage();
        }
    }

}