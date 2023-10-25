<?php

class ErrorController extends Zend_Controller_Action
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

    }

    public function serverErrorAction()
    {
        $this->_helper->getHelper('layout')->disableLayout();
        $this->_helper->viewRenderer->setRender('500');
    }

    public function notFoundAction()
    {
        $this->_helper->getHelper('layout')->disableLayout();
        $this->_helper->viewRenderer->setRender('404');
    }

    public function forbiddenAction()
    {
        $this->_helper->getHelper('layout')->disableLayout();
        $this->_helper->viewRenderer->setRender('403');
    }
}