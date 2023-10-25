<?php
require_once APPLICATION_PATH . "/Api/ApiTiket.php";
require_once APPLICATION_PATH . "/Api/ApiAuth.php";
require_once APPLICATION_PATH . "/Api/ApiKategori.php";
require_once APPLICATION_PATH . "/Api/ApiNotifikasi.php";
require_once APPLICATION_PATH . "/Api/ApiServiceDesk.php";
require_once APPLICATION_PATH . "/Api/ApiHelpdesk.php";
class ApiController extends Zend_Controller_Action
{
    use ApiTiket, ApiAuth, ApiKategori, ApiNotifikasi, ApiServiceDesk, ApiHelpdesk;
    public function init()
    {
        $this->_helper->_acl->allow();
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout->disableLayout();
    }

    public function preDispatch()
    {
        $this->jwt = new Dpr_TokenManagerService();
        $this->dateFormat = new Dpr_Controller_Action_Helper_YmdToIndo();
        $this->subKategori = new Dpr_SubKategoriService();
        $this->tiketpetugasService = new Dpr_TiketPetugasService();
        $this->penggunaService = new Dpr_PenggunaService();
        $this->listPeranService = new Dpr_ListPeranService();
        $this->peranService = new Dpr_PeranService();
        $this->logService = new Dpr_LogService();
        $this->tiketService = new Dpr_TiketService();
        $this->notifikasiService = new Dpr_NotifikasiService();
        $this->kategoriService = new Dpr_KategoriService();
        $this->subKategoriService = new Dpr_SubKategoriService();
        $this->dokumenLampiranService = new Dpr_DokumenLampiranService();
        $this->tiketImageLaporanService = new Dpr_TiketImageLaporanService();
        $this->ratingService = new Dpr_RatingService();
        $this->tiketPetugasService = new Dpr_TiketPetugasService();
        $this->statusTiketService = new Dpr_StatusTiketService();
        $this->dokumenLampiranService = new Dpr_DokumenLampiranService();
        $this->fileManagerService = new Dpr_FileManagerService();
        $this->urgensiService = new Dpr_UrgensiService();
    }

    private function getBearerToken()
    {
        $headers = $this->getRequest()->getHeader('authorization');
        // HEADER: Get the access token from the header
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        }
        throw new Exception("Unauthorized", 401);
    }

    private function sendJson($body, $code = 200)
    {
        $request = $this->getResponse();
        $request->setHeader('content-type', 'multipart/form-data');
        $request->setBody(json_encode($body));
        $request->setHttpResponseCode($code);
    }

    private function validateBody($validator)
    {
        $body = $this->getRequest()->getParams();
        foreach ($validator as $v) {
            if (!isset($body[$v]) || empty($body[$v])) {
                throw new Exception("{$v} is required", 400);
            }
        }
    }

    public function indexAction()
    {
        $this->sendJson([
            'success' => true,
            'message' => 'ini api bang,bukan air',
        ]);
    }
}
