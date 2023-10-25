<?php

class ViewerController extends Zend_Controller_Action
{

    public function init()
    {
        $this->_helper->_acl->allow('viewer');
        $this->_helper->layout->setLayout('layout_viewer');
    }

    public function preDispatch()
    {
        $this->tiket = new Dpr_TiketService();
        $this->programmer = new Dpr_ProgrammerService();
        $this->rating = new Dpr_RatingService();
        $this->tiketPetugas = new Dpr_TiketPetugasService();

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

    //localhost/viewer
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

    //localhost/viewer/statistik-tahun
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

    //localhost:viewer/statistik-bulan
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
        }catch (Exception $exception){
            $this->view->error = $exception->getMessage();
        }
    }

    //localhost/viewer/statistik-hari
    public function statistikHariAction()
    {
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
    //localhost/viewer/rekap-petugas-tahun
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

    //localhost/viewer/rekap-kecepatan-tiket
    public function rekapKecepatanTiketAction()
    {
        $this->_helper->viewRenderer->setRender('rekap-kecepatan-tiket');

        $this->view->title = 'Rekap kecepatan Tiket';

        $data = $this->tiket->getRekapPengerjaan();

        $this->view->data = $data;
    }

}