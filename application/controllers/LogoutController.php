<?php

class LogoutController extends Zend_Controller_Action
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
    }

    public function indexAction()
    {
        Zend_Auth::getInstance()->clearIdentity();

        $_SESSION['flash'] = "Berhasil Logout";

        $this->_redirect('/');
    }
}
