<?php

class NotifikasiController extends Zend_Controller_Action
{

    public function init()
    {
        $this->_helper->_acl->allow();
        $this->_helper->_acl->needAuth();
    }

    public function preDispatch()
    {
        $this->notifikasiService = new Dpr_NotifikasiService();
        $this->tiketService = new Dpr_TiketService();
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

    public function indexAction()
    {
        // $this->_helper->getHelper('layout')->disableLayout();
        // $this->_helper->viewRenderer->setRender();

        //var_dump($this->notifikasiService->getAllData(Zend_Auth::getInstance()->getIdentity()->id));
        //$data = $this->notifikasiService->getAllData(Zend_Auth::getInstance()->getIdentity()->id);
        $data  = (new Dpr_NotifikasiService)->getDataByIdPengguna(Zend_Auth::getInstance()->getIdentity()->id);
        $this->view->data = $data;
    }

    public function ajaxAction()
    {
        $this->_helper->getHelper('layout')->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $userId = Zend_Auth::getInstance()->getIdentity()->id;
        $data = $this->notifikasiService->getDataWithLimit($userId, 3)->toArray();
        $this->getResponse()->setBody(json_encode($data))->setHeader('Content-Type', 'application/json');
    }

    // read notifikasi
    public function readNotifikasiAction()
    {
        $idNotif = $this->getRequest()->getParam('id_notif');
        $nomorTiket = $this->getRequest()->getParam('nomor_tiket');
        $idTiket = $this->tiketService->findByNoTiket($nomorTiket)->getId();
        $peran = Zend_Auth::getInstance()->getIdentity()->peran_aktif;
        // read notifnya
        $this->notifikasiService->read($idNotif);
        // kalo gak,redirect ke status tiket
        $this->getResponse()->setRedirect("/tiket/status/no_tiket/{$nomorTiket}");

        // redirect ke mana
        if (strtoupper($peran) == strtoupper("servicedesk")) {
            // kalo service desk
            $this->getResponse()->setRedirect("/servicedesk/permintaan/detail/{$idTiket}");
        }
        // kalo helpdesk
        if (strtoupper($peran) == strtoupper("helpdesk") || strtoupper($peran) == strtoupper("it specialist")) {
            $this->getResponse()->setRedirect("/helpdesk/permintaan/detail/{$idTiket}");
        }
        // kalo programmer
        if (strtoupper($peran) == strtoupper("programmer")) {
            $this->getResponse()->setRedirect("/programmer/permintaan/detail/{$idTiket}");
        }
        // kalo operator
        if (strtoupper($peran) == strtoupper("verificator")) {
            $this->getResponse()->setRedirect("/operator/permintaan/detail/{$idTiket}");
        }
    }
}
