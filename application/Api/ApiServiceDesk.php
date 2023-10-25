<?php
require_once APPLICATION_PATH . '/dto/Tiket/TiketAddDataRequest.php';
require_once APPLICATION_PATH . '/dto/Tiket/TiketUpdateRevisiServiceDeskRequest.php';
require_once APPLICATION_PATH . '/dto/Tiket/TiketUpdateLangsungSelesaiRequest.php';
require_once APPLICATION_PATH . '/dto/Tiket/TiketFindByResponse.php';

trait ApiServiceDesk
{
    // public function tiketPetugasMapper($tiket)
    // {
    //     // kalo datanya cuman 1
    //     if (isset($tiket->id)) {
    //         // map kategori tiket
    //         if ($tiket->id_sub_kategori) {
    //             $subKategori = $this->subKategoriService->getData($tiket->id_sub_kategori);
    //             $kategori = $this->kategoriService->getData($subKategori->id_kategori);
    //             $tiket->id_kategori = $kategori->id;
    //             $tiket->kategori = $kategori->kategori;
    //             $tiket->sub_kategori = $subKategori->sub_kategori;
    //         }

    //         // map status tiket
    //         $tiket->status_tiket = $this->statusTiketService->getData($tiket->id_status_tiket)->status_tiket;

    //         // map dokumen lampiran
    //         $tiket->dokumen_lampiran = $this->dokumenLampiranService->getListDokumenLampiran($tiket->id);
    //         // reset index array
    //         if ($tiket->dokumen_lampiran) {
    //             $tiket->dokumen_lampiran = array_values($tiket->dokumen_lampiran);
    //             // ganti pathnya ke storage controller
    //             $tiket->dokumen_lampiran = array_map(function ($v) {
    //                 $v['path'] =  "http://$_SERVER[HTTP_HOST]/storage/index/dokumen-lampiran/{$v['doc_name']}/ext/{$v['ext']}";
    //                 return $v;
    //             }, $tiket->dokumen_lampiran);
    //         }

    //         return $tiket;
    //     }

    //     // kalo datanya banyak
    //     return array_map(function ($v) {
    //         // map kategori tiket
    //         if ($v->id_sub_kategori) {
    //             $subKategori = $this->subKategoriService->getData($v->id_sub_kategori);
    //             $kategori = $this->kategoriService->getData($subKategori->id_kategori);
    //             $v->id_kategori = $kategori->id;
    //             $v->kategori = $kategori->kategori;
    //             $v->sub_kategori = $subKategori->sub_kategori;
    //         }

    //         // map status tiket
    //         $v->status_tiket = $this->statusTiketService->getData($v->id_status_tiket)->status_tiket;

    //         // map dokumen lampiran
    //         $v->dokumen_lampiran = $this->dokumenLampiranService->getListDokumenLampiran($v->id);
    //         // reset index array
    //         if ($v->dokumen_lampiran) {
    //             $v->dokumen_lampiran = array_values($v->dokumen_lampiran);
    //             // ganti pathnya ke storage controller
    //             $v->dokumen_lampiran = array_map(function ($v) {
    //                 $v['path'] =  "http://$_SERVER[HTTP_HOST]/storage/index/dokumen-lampiran/{$v['doc_name']}/ext/{$v['ext']}";
    //                 return $v;
    //             }, $v->dokumen_lampiran);
    //         }

    //         return $v;
    //     }, $tiket);
    // }
    
    // get nomor hp service desk
    // public function servicedeskAction()
    // {
    //     try {
    //         $token = $this->getBearerToken();
    //         $user = $this->jwt->decode($token);
    //         // get all nomor hp service desk
    //         $result = $this->penggunaService->getPeranServiceDesk();
    //         $this->sendJson([
    //             'success' => true,
    //             'message' => "Data service desk berhasil ditemukan",
    //             'data' => $result,
    //         ]);
    //     } catch (Exception $e) {
    //         $this->sendJson([
    //             'success' => false,
    //             'message' => $e->getMessage(),
    //         ], $e->getCode() >= 400 ? $e->getCode() : 400);
    //     }
    // }

    // get all tiket
    /**
     * 
     * endpoint : /api/servicedesk/permintaan
     * method : GET
     * headers : 
     * Authorization : Bearer {token}
     * 
     * 
     */
    public function servicedeskAction() 
    {
        try {
            $token = $this->getBearerToken();
            $user = $this->jwt->decode($token);
            $tiket = $this->tiketService->getAllData();
            if (!$tiket) throw new Exception('Tiket tidak ditemukan', 404);


            if ($this->getRequest()->getParam('id_petugas')) {
                $tiket = $this->tiketPetugasService->getAllDataByIdPetugas($this->getRequest()->getParam('id'));
            }

            $this->sendJson([
                'success' => true,
                'message' => "Tiket berhasil ditemukan",
                // 'data' => $this->tiketPetugasMapper($tiket),
                'data' => $tiket
            ]);
        } catch (Exception $e) {
            $this->sendJson([
                'success' => false,
                'message' => $e->getMessage(),
            ], $e->getCode() >= 400 ? $e->getCode() : 400);
        }
    }

}