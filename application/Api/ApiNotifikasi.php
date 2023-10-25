<?php

trait ApiNotifikasi
{

    // get notifikasi user
    /**
     * endpoint : /api/notifikasi
     * method GET 
     * headers :
     * Authorization : Bearer {token}
     */
    public function notifikasiAction()
    {
        try {
            $token = $this->getBearerToken();
            $user = $this->jwt->decode($token);
            $notifikasi = $this->notifikasiService->getAllData($user['id']);
            $this->sendJson([
                'success' => true,
                'message' => "Notifikasi berhasil ditemukan",
                'data' => $notifikasi->toArray(),
            ]);
        } catch (Exception $e) {
            $this->sendJson([
                'success' => false,
                'message' => $e->getMessage(),
            ], $e->getCode() >= 400 ? $e->getCode() : 400);
        }
    }

    // put notifikasi untuk ubah baca notif 
    /**
     * endpoint : /api/read-notifikasi/id_notif/{id_notif}
     * method PUT
     * headers :
     * Authorization : Bearer {token}
     */
    public function readNotifikasiAction()
    {
        try {
            $token = $this->getBearerToken();
            $user = $this->jwt->decode($token);
            if ($this->getRequest()->isPut()) {
                $idNotif = $this->getRequest()->getParam("id_notif");
                $this->notifikasiService->read($idNotif);
                $this->sendJson([
                    'success' => true,
                    'message' => "Notifikasi berhasil dibaca",
                ]);
            }
        } catch (Exception $e) {
            $this->sendJson([
                'success' => false,
                'message' => $e->getMessage(),
            ], $e->getCode() >= 400 ? $e->getCode() : 400);
        }
    }
}
