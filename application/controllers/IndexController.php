<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        // $this->_helper->_acl->allow('verificator');
        // $this->_helper->_acl->allow('helpdesk');
        // $this->_helper->_acl->allow('admin');
        $this->_helper->_acl->allow();
        // $this->_helper->_acl->needAuth();
    }

    public function preDispatch()
    {
        $this->sessionService = new Dpr_SessionService();
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
        // $this->_helper->getHelper('layout')->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $this->_redirect('/beranda');
        //var_dump('jvaduvasvh');
    }

    public function logoutAction()
    {
        Zend_Auth::getInstance()->clearIdentity();
        $this->_redirect('/login');
    }

    public function mailerAction()
    {
        $this->_helper->getHelper('layout')->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $mailer = new Dpr_MailerService();

        $request = $this->getRequest();
        $idPengguna = $request->getParam('userid');

        if ($mailer->permintaanMasuk($idPengguna)) {
            echo "sukses, cek email anda";
        } else {
            echo "gagal mengirim pesan";
        }
    }
}
