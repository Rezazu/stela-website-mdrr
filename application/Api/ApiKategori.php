<?php
trait ApiKategori
{

    // get kategori 
    /**
     * endpoint : /api/kategori
     * method : GET 
     * headers : 
     * Authorization : Bearer {token}
     */
    public function kategoriAction()
    {
        try {
            $token = $this->getBearerToken();
            $user = $this->jwt->decode($token);
            $kategori = $this->kategoriService->getAllDataWithSubKategori();
            $this->sendJson([
                'success' => true,
                'message' => "Kategori berhasil ditemukan",
                'data' => $kategori,
            ]);
        } catch (Exception $e) {
            $this->sendJson([
                'success' => false,
                'message' => $e->getMessage(),
            ], $e->getCode() >= 400 ? $e->getCode() : 400);
        }
    }
}
