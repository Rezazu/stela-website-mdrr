<?php

class RiwayatController extends Zend_Controller_Action
{
    public function init()
    {
        $this->_helper->_acl->allow();
        $this->_helper->_acl->needAuth();
    }

    public function preDispatch()
    {
        $this->tiketService = new Dpr_TiketService();
        $this->dateFormat = new Dpr_Controller_Action_Helper_YmdToIndo();
        $this->logService = new Dpr_LogService();
        $this->statusTiket = new Dpr_StatusTiketService();
        $this->timProgrammer = new Dpr_TimProgrammerService();
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

    //localhost/riwayat
    public function indexAction()
    {
        $this->view->title = 'Riwayat';
        // $this->_helper->getHelper('layout')->disableLayout();

        try {
            $id_pelapor = Zend_Auth::getInstance()->getIdentity()->id;
            //            $id_pelapor = $this->getRequest()->getParam('id');
            $datas = $this->tiketService->getAllDataByIdPelapor($id_pelapor);

            if ($datas == null) {
                throw new Exception("Belum Ada Riwayat");
            }

            $count = 1;
            foreach ($datas as $data) {

                if ($data->id_sub_kategori == 1) {
                    $listPetugas = ($data->id_tim_programmer) ?
                        $this->timProgrammer->getListProgrammer($data->id_tim_programmer) : null;
                } elseif ($data->id_sub_kategori == 2) {
                    $listPetugas = $this->tiketPetugas->getListPetugasStelaAktif($data->id);
                } else {
                    $listPetugas = null;
                }

                $result[$count] = [
                    'id' => $data->id,
                    'no_tiket' => $data->no_tiket,
                    'tanggal_angka' => $this->dateFormat->directAngka($data->tanggal_input),
                    'tanggal_huruf' => $this->dateFormat->direct($data->tanggal_input),
                    'judul_permasalahan' => $data->keterangan,
                    'id_status_tiket' => $data->id_status_tiket,
                    'status_tiket' => $this->statusTiket->getQueryData($data->id_status_tiket)->status_tiket,
                    'rating' => $data->rating,
                    'list_petugas' => $listPetugas,
                    'jam' => explode(' ', $data->tanggal_input)[1],
                    'tanggal' => explode(' ', $data->tanggal_input)[0],
                    'solusi' => $data->solusi
                ];

                $count++;
            }

            $this->view->result = $result;
        } catch (Exception $exception) {
            $this->view->error = $exception->getMessage();
        }
    }
}
