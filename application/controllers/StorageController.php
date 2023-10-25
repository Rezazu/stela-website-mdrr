<?php
class StorageController extends Zend_Controller_Action
{
    public function init()
    {
        $this->_helper->_acl->allow();
    }

    public function preDispatch()
    {
        $this->fileManagerService = new Dpr_FileManagerService();
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

    // only read file
    public function indexAction()
    {
        $this->getHelper('layout')->disableLayout();
        $this->getHelper('view_renderer')->setNoRender();

        // localhost/storage/index/dokumen-lampiran/:file_name/ext/:ext
        if ($this->getRequest()->getParam('dokumen-lampiran')) {
            $fileName = $this->getRequest()->getParam('dokumen-lampiran');
            $ext = $this->getRequest()->getParam('ext');
            $fileName = "$fileName.$ext";
            $this->fileManagerService->readDokumenLampiran($fileName);
        }

        // localhost/storage/index/tiket-image-laporan/:file_name
        if ($this->getRequest()->getParam('tiket-image-laporan')) {
            $fileName = $this->getRequest()->getParam('tiket-image-laporan');
            $ext = $this->getRequest()->getParam('ext');
            $fileName = "$fileName.$ext";
            $this->fileManagerService->readTiketImageLaporan($fileName);
        }

        // localhost/storage/index/todo-list-dokumen/:file_name
        if ($this->getRequest()->getParam('todo-list-dokumen')) {
            $fileName = $this->getRequest()->getParam('todo-list-dokumen');
            $ext = $this->getRequest()->getParam('ext');
            $fileName = "$fileName.$ext";
            $this->fileManagerService->readTodoListDokumen($fileName);
        }
    }
    // force download
    public function downloadAction()
    {
        $this->getHelper('layout')->disableLayout();
        $this->getHelper('view_renderer')->setNoRender();

        // localhost/storage/download/dokumen-lampiran/:file_name
        if ($this->getRequest()->getParam('dokumen-lampiran')) {
            $fileName = $this->getRequest()->getParam('dokumen-lampiran');
            $ext = $this->getRequest()->getParam('ext');
            $fileName = "$fileName.$ext";
            $this->fileManagerService->downloadDokumenLampiran($fileName);
        }

        // localhost/storage/download/tiket-image-laporan/:file_name
        if ($this->getRequest()->getParam('tiket-image-laporan')) {
            $fileName = $this->getRequest()->getParam('tiket-image-laporan');
            $ext = $this->getRequest()->getParam('ext');
            $fileName = "$fileName.$ext";
            $this->fileManagerService->downloadTiketImageLaporan($fileName);
        }

        // localhost/storage/download/todo-list-dokumen/:file_name
        if ($this->getRequest()->getParam('todo-list-dokumen')) {
            $fileName = $this->getRequest()->getParam('todo-list-dokumen');
            $ext = $this->getRequest()->getParam('ext');
            $fileName = "$fileName.$ext";
            $this->fileManagerService->downloadTodoListDokumen($fileName);
        }
    }
}
