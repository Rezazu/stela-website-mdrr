<?php
class LoginController extends Zend_Controller_Action
{
    public function init()
    {
        $this->_helper->_acl->allow();
        $this->_helper->_acl->redirectIfAuthenticated();
    }

    public function preDispatch()
    {
        $this->peran = new Dpr_PeranService();
        $this->listPeran = new Dpr_ListPeranService();
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
        $this->_helper->getHelper('layout')->disableLayout();
        // post method
        if ($this->getRequest()->isPost()) {
            $email = $this->_getParam('email');
            if (!$this->_getParam('email')) $email = 'blank';

            $password = md5($this->_getParam('password'));

            $auth = Zend_Auth::getInstance();

            $auth->setStorage(new Zend_Auth_Storage_Session());

            $db = Zend_Registry::get('db_stela');
            $adapter = new Zend_Auth_Adapter_DbTable($db, 'pengguna', 'email', 'password');
            $adapter->setIdentity($email);
            $adapter->setCredential($password);
            $result = $auth->authenticate($adapter);

            if ($result->isValid()) {
                $loggedname = $result->getIdentity();
                $data = $adapter->getResultRowObject(null, 'password');
                // get peran pengguna
                $peran = $this->peran->getAllDataByIdPengguna($data->id);
                $tmpPeran = [];
                foreach ($peran as $p) {
                    array_push($tmpPeran, $this->listPeran->findById($p->id_peran)->getNamaPeran());
                }
                // jika length peran pengguna 0 maka key peran pengguna adalah user
                if (count($tmpPeran) == 0) {
                    $tmpPeran[0] = 'user';
                }
                // jika peran adalah admin maka isi semua list_peran pada key peran
                if ($tmpPeran[0] == 'admin') {
                    foreach ($this->listPeran->getAllData() as $v) {
                        if ($v->nama_peran == 'admin') continue;
                        array_push($tmpPeran, $v->nama_peran);
                    }
                }
                $data->peran = $tmpPeran;
                $data->peran_aktif = $tmpPeran[0];
                $dir = '/stela/assets/profile/';
                $data->profile = $data->profile ? $dir . $data->profile : "https://ui-avatars.com/api/?name={$data->nama_lengkap}";
                $auth->getStorage()->write($data);

                $_SESSION['flash'] = "Berhasil Login";

                $this->_redirect('/');
            } else {
                $_SESSION['flash_error'] = "Email atau Password Salah";
                $this->_redirect('/login');
            }
        }
    }
}
