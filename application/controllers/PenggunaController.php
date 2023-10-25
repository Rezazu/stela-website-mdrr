<?php

require_once APPLICATION_PATH . '/dto/Peran/PeranAddDataRequest.php';
require_once APPLICATION_PATH . '/dto/Peran/PeranFIndByIdResponse.php';
require_once APPLICATION_PATH . '/dto/Peran/PeranUpdateDataRequest.php';
require_once APPLICATION_PATH . '/dto/Peran/PeranDeleteRequest.php';
require_once APPLICATION_PATH . '/dto/Peran/PeranUpdatePeranRequest.php';
require_once APPLICATION_PATH . '/dto/Pengguna/PenggunaFIndByIdResponse.php';

class PenggunaController extends Zend_Controller_Action
{
	public function init()
	{
		$this->_helper->_acl->allow();
        $this->_helper->_acl->needAuth();
    }

	public function preDispatch()
	{
		$this->penggunaService = new Dpr_PenggunaService();
		$this->sessionService = new Dpr_SessionService();
        $this->user = Zend_Auth::getInstance()->getIdentity();
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

	// toggle status user
	/**
	 * Method POST
     * url : /pengguna/toggle-status
     * Akses pake AJAX trus di reload
	 */
	public function toggleStatusAction()
	{
        $this->_helper->getHelper('layout')->disableLayout();
        $this->_helper->viewRenderer->setnoRender();

		if ($this->getRequest()->isPost()) {
			// kalo pake session 
			 $idPengguna = Zend_Auth::getInstance()->getIdentity()->id;
			// dibawah buat uji postman aja,hapus aja kalo udah pake session ganti diatas
//			$idPengguna = $this->getRequest()->getParam('id_pengguna');
			// ubah status user
			$this->penggunaService->toggleStatus($idPengguna);
			// update stattus user di session
			$this->sessionService->updateStatusUser();
			// balikin respon bang,biar yg ngirim request seneng
			// kalo gak dikasih respon kadang suka ngambek muehehe
            $pesan = ($this->user->status) ? 'Aktif' : 'Nonaktif';
			$this->getResponse()->setHeader('content-type', 'application/json')->setBody(json_encode([
				'success' => true,
				'message' => "Berhasil ubah status menjadi $pesan",
			]));
		}
	}
}
