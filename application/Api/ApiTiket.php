<?php
require_once APPLICATION_PATH . '/dto/Tiket/TiketAddDataRequest.php';

trait ApiTiket
{

    public function tiketMapper($tiket)
    {
        // kalo datanya cuman 1
        if (isset($tiket->id)) {
            // map kategori tiket
            if ($tiket->id_sub_kategori) {
                $subKategori = $this->subKategoriService->getData($tiket->id_sub_kategori);
                $kategori = $this->kategoriService->getData($subKategori->id_kategori);
                $tiket->id_kategori = $kategori->id;
                $tiket->kategori = $kategori->kategori;
                $tiket->sub_kategori = $subKategori->sub_kategori;
            }

            // map status tiket
            $tiket->status_tiket = $this->statusTiketService->getData($tiket->id_status_tiket)->status_tiket;

            // map dokumen lampiran
            $tiket->dokumen_lampiran = $this->dokumenLampiranService->getListDokumenLampiran($tiket->id);
            // reset index array
            if ($tiket->dokumen_lampiran) {
                $tiket->dokumen_lampiran = array_values($tiket->dokumen_lampiran);
                // ganti pathnya ke storage controller
                $tiket->dokumen_lampiran = array_map(function ($v) {
                    $v['path'] =  "http://$_SERVER[HTTP_HOST]/storage/index/dokumen-lampiran/{$v['doc_name']}/ext/{$v['ext']}";
                    return $v;
                }, $tiket->dokumen_lampiran);
            }

            return $tiket;
        }

        // kalo datanya banyak
        return array_map(function ($v) {
            // map kategori tiket
            if ($v->id_sub_kategori) {
                $subKategori = $this->subKategoriService->getData($v->id_sub_kategori);
                $kategori = $this->kategoriService->getData($subKategori->id_kategori);
                $v->id_kategori = $kategori->id;
                $v->kategori = $kategori->kategori;
                $v->sub_kategori = $subKategori->sub_kategori;
            }

            // map status tiket
            $v->status_tiket = $this->statusTiketService->getData($v->id_status_tiket)->status_tiket;

            // map dokumen lampiran
            $v->dokumen_lampiran = $this->dokumenLampiranService->getListDokumenLampiran($v->id);
            // reset index array
            if ($v->dokumen_lampiran) {
                $v->dokumen_lampiran = array_values($v->dokumen_lampiran);
                // ganti pathnya ke storage controller
                $v->dokumen_lampiran = array_map(function ($v) {
                    $v['path'] =  "http://$_SERVER[HTTP_HOST]/storage/index/dokumen-lampiran/{$v['doc_name']}/ext/{$v['ext']}";
                    return $v;
                }, $v->dokumen_lampiran);
            }

            return $v;
        }, $tiket);
    }

    //get detail petugas tiket
    /**
     * endpoint : /api/petugas/{id}
     * method : GET
     * headers : 
     * Authorization : Bearer {token}
     */
    public function petugasAction(){
        try{
            $idTiket = $this->getRequest()->getParam('id');
            $tiket = $this->tiketService->findById($idTiket);
            $list_petugas = $this->tiketPetugasService->getAllDataByIdTiket($idTiket);
            if (!$tiket) throw new Exception("Tiket tidak ditemukan", 404);
            $data = [];
            if ($list_petugas) {
                $counter1 = 1;
                foreach ($list_petugas as $tugas) {
                    $rating = $this->tiketPetugasService->getRatingByIdPetugas($tugas->id_petugas);
                    array_push($data, [
                        'id' => $tugas->id_petugas,
                        'nama' => $this->penggunaService->findById($tugas->id_petugas)->getNamaLengkap(),
                        'unit_kerja' => $this->penggunaService->findById($tugas->id_petugas)->getUnitKerja(),
                        'rating' => floatval($rating['rating']),
                        'profile' => $this->penggunaService->findById($tugas->id_petugas)->getProfile()
                    ]);

                    $counter1++;
                }
            }
            
            $this->sendJson([
                'success' => true,
                'message' => "Petugas berhasil ditemukan",
                'data' => $this->view->data = $data
            ]);

        } catch (Exception $e) {

        }
    }
    
    // post permintaan solved
    /**
     * endpoint : /api/tiket/update-pengguna
     * method : POST
     * Headers : 
     * Authorization : Bearer {token}

     */
    public function updatePenggunaAction(){
        try {
            $token = $this->getBearerToken();
            $user = $this->jwt->decode($token);
            $namaUpdate = $user['nama_lengkap'];
            $permasalahanAkhir = $this->_getParam('permasalahan_akhir');
            $solusi = "Diselesaikan oleh pengguna";

            if ($this->getRequest()->isPost()) {
                $this->validateBody([
                    'id',
                    'permasalahan_akhir',
                ]);
            }
            $id = $this->_getParam('id');
            $tiket = $this->tiketService->findById($id);
            $request = new LogRequest();
            $request->setIdTiket($tiket->getId());
            $request->setIdStatusTiketInternal(9);
            $request->setIdStatusTiket($tiket->getIdStatusTiket());
            $request->setPengguna($namaUpdate);
            $request->setKeterangan("$namaUpdate (pengguna), menyelesaikan permintaan");

            $this->logService->addLogData($request);
            
            $request = new TiketImageLaporanRequest();
            $request->setUserUpdate($namaUpdate);
            $request->setUserInput($namaUpdate);
            $request->setPermasalahanAkhir("Diselesaikan pengguna");
            $request->setSolusi("Diselesaikan pengguna");
            $request->setTipeLaporan('Solved');
            $request->setIdTiket($id);
            $request->setStatus(1);

            $this->tiketService->updateStatusTiketInternal($id, 9, $namaUpdate);
            $this->tiketImageLaporanService->addTiketImageLaporan($request);
            $this->tiketService->updateSelesai($id, $permasalahanAkhir, $solusi, $namaUpdate);
            $this->notifikasiService->sendToAllServiceDesk($tiket->getNoTiket(),"Laporan Selesai!");
            $_SESSION['flash'] = 'Berhasil Update Selesai';

            $this->sendJson([
                'success' => true,
                'message' => "Pengguna menyelesaikan tiket",
            ]);
            
        } catch (Exception $e){
            $this->sendJson([
                'success' => false,
                'message' => "Gagal menyelesaikan tiket",
            ]);
        }
    }

    // get all tiket
    /**
     * endpoint : /api/tiket
     * method : GET
     * headers : 
     * Authorization : Bearer {token}
     */

        public function tiketAction()
        {
            try {
                $token = $this->getBearerToken();
                $user = $this->jwt->decode($token);
                $tiket = $this->tiketService->getAllDataByIdPelapor($user['id']);

                $data = [];
                $list_petugas = $this->tiketPetugasService->getAllDataByIdTiket($this->getRequest()->getParam('id'));
                if ($list_petugas) {
                    $counter1 = 1;
                    foreach ($list_petugas as $tugas) {
                        array_push($data, [
                            'id' => $tugas->id_petugas,
                            'nama' => $this->penggunaService->findById($tugas->id_petugas)->getNamaLengkap()
                        ]);

                        $counter1++;
                    }
                }

                if (!$tiket) throw new Exception('Tiket tidak ditemukan', 404);
                
                if ($this->getRequest()->getParam('no_tiket')) {
                    $tiket = $this->tiketService->noDtofindByNoTiket($this->getRequest()->getParam('no_tiket'));
                    $this->sendJson([
                        'success' => true,
                        'message' => "Tiket berhasil ditemukan",
                        'data' => $this->tiketMapper($tiket),
                    ]);
                } else if ($this->getRequest()->getParam('id')) {
                    $tiket = $this->tiketService->noDtofindById($this->getRequest()->getParam('id'));
                    $this->sendJson([
                        'success' => true,
                        'message' => "Tiket berhasil ditemukan",
                        'data' => $this->tiketMapper($tiket),
                    ]);
                } else {
                    $this->sendJson([
                        'success' => true,
                        'message' => "Tiket berhasil ditemukan",
                        'data' => $this->tiketMapper($tiket),
                    ]);
                }

            } catch (Exception $e) {
                $this->sendJson([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], $e->getCode() >= 400 ? $e->getCode() : 400);
            }
        }

    // api permintaan baru
    /**
     * 
     * endpoint : /api/permintaan
     * method : POST
     * Headers : 
     * Authorization : Bearer {token}
     * Body :
     * 
     * 
     */
    public function permintaanAction()
    {
        try {
            $token = $this->getBearerToken();
            $user = $this->jwt->decode($token);
            if ($this->getRequest()->isPost()) {

                // validate body request
                $this->validateBody([
                    'bagian',
                    'gedung',
                    'lantai',
                    'ruangan',
                    'keterangan',
                ]);

                $request = new TiketAddDataRequest();
                // via online
                $request->setIdVia(5);

                $request->setNamaPelapor($user['nama_lengkap']);
                $request->setUserInput($user['nama_lengkap']);
                $request->setUserUpdate($user['nama_lengkap']);
                $request->setIdPelapor($user['id']);
                $request->setTeleponPelapor($user['telepon']);
                $request->setHpPelapor($user['hp']);
                $request->setEmailPelapor($user['email']);
                $request->setUnitKerjaPelapor($user['unit_kerja']);

                // payload/body request
                $request->setBagianPelapor($this->_getParam('bagian'));
                $request->setGedungPelapor($this->_getParam('gedung'));
                $request->setLantaiPelapor($this->_getParam('lantai'));
                $request->setRuanganPelapor($this->_getParam('ruangan'));

                $request->setKeterangan($this->_getParam('keterangan'));

                $tiket = $this->tiketService->addData($request);

                if (!empty($_FILES['dokumen']['name'][0])) {
                    //Memanggil function untuk menyimpan lebih dari 1 file
                    $this->dokumenLampiranService->addMultipleFile($_FILES['dokumen'], $tiket);
                }

                // kalo sukses buat tiket, kirim notif dong bang ke semua service desk
                $this->notifikasiService->sendToAllServiceDesk($tiket->getNoTiket(), "Permintaan baru!");

                // get info tiket
                $tiketResponse = $this->tiketService->noDtofindByNoTiket($tiket->getNoTiket());
                $this->sendJson([
                    'success' => true,
                    'message' => "Permintaan berhasil dibuat",
                    'data' => $this->tiketMapper($tiketResponse),
                ]);
            }else{
                $this->sendJson([
                    'success' => false,
                    'message' => "False method"
                ]);
            }
        } catch (Exception $e) {
            $this->sendJson([
                'success' => false,
                'message' => $e->getMessage(),
            ], $e->getCode() >= 400 ? $e->getCode() : 400);
        }
    }

    // api beri rating tiket
    /**
     * endpoint : /api/rating/id/{id_tiket}
     * method : POST
     * Headers : 
     * Authorization : Bearer {token}
     * Body : 
     * rating
     */

    public function ratingAction()
    {
        try {
            $token = $this->getBearerToken();
            $user = $this->jwt->decode($token);
            if ($this->getRequest()->isPost()) {
                // get payload
                $idTiket = $this->getRequest()->getParam('id');
                $rating = $this->getRequest()->getParam('rating');
                $ratingKeterangan = $this->getRequest()->getParam('ratingKeterangan');
                // get info tiket 
                $tiket = $this->tiketService->findById($idTiket);
                if (!$tiket) throw new Exception("Tiket tidak ditemukan", 404);
                // get user inpuFt from token
                $userInput = $user['username'];

                // update rating table tiket
                $this->ratingService->updateRatingTiket($idTiket, $rating, $ratingKeterangan, $userInput);

                // get petugas yang ngerjain tiket
                $petugas = $this->tiketPetugasService->getAllDataByIdTiket($idTiket);

                // kalo ada petugasnya kasih rating ke petugasnya bang,biar semangat
                if ($petugas)
                    foreach ($petugas as $v) {
                        // kasih rating ke petugas yang ngerjain tiket,kalo ngasih hati buat kamu aja bukan petugas :)
                        $this->ratingService->addToPetugas($v->id_petugas, $rating, $userInput);
                        // selain kirim rating ke petugas,kasih juga notifikasi biar ngeh kalo udah dikasih notif
                        // tambah seneng juga kan muehehe
                        $this->notifikasiService->sendTo($v->id_petugas, $tiket->getNoTiket(), "Pengguna sudah memberikan rating!");
                    }

                // kirim notifikasi ke semua service desk
                $this->notifikasiService->sendToAllServiceDesk($tiket->getNoTiket(), "Pengguna sudah memberkan rating!");
                $this->sendJson([
                    'success' => true,
                    'message' => "Berhasil memberikan rating",
                    "data" => $rating
                ]);
            }
        } catch (Exception $e) {
            $this->sendJson([
                'success' => false,
                'message' => $e->getMessage(),
            ], $e->getCode() >= 400 ? $e->getCode() : 400);
        }
    }

    // api upload dokumen lampiran by nomor tiket
    /**
     * endpoint : /api/dokumen-lampiran/nomor_tiket/{nomor_tiket}
     * method : POST
     * Headers : 
     * Authorization : Bearer {token}
     * Body : 
     * dokumen
     */
    public function dokumenLampiranAction()
    {
        try {
            $token = $this->getBearerToken();
            $user = $this->jwt->decode($token);
            if ($this->getRequest()->isPost()) {
                // validate body request
                $this->validateBody(["nomor_tiket"]);
                // validate file dokumen
                if (!$_FILES['dokumen']) throw new Exception("dokumen diperlukan", 400);
                $tiket = $this->tiketService->noDtoFindByNoTiket($this->_getParam('nomor_tiket'));
                // kalo tiketnya tidak ada
                if (!$tiket) throw new Exception("Nomor tiket tidak ditemukan", 404);
                // uploading file
                $uploadedFile = $this->fileManagerService->saveDokumenLampiran($_FILES['dokumen']);
                // add record dokumen lampiran
                $this->dokumenLampiranService->addData(
                    $tiket->id,
                    $uploadedFile['file_name'],
                    $_FILES['dokumen']['name'],
                    $uploadedFile['file_type'],
                    $uploadedFile['file_size'],
                    $tiket->keterangan,
                    $user['nama_lengkap']
                );
                $this->sendJson([
                    'success' => true,
                    'message' => "Berhasil upload file",
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
