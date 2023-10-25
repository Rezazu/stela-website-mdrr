<?php
require_once APPLICATION_PATH . '/dto/Tiket/TiketAddDataRequest.php';
require_once APPLICATION_PATH . '/dto/Tiket/TiketUpdateRevisiServiceDeskRequest.php';
require_once APPLICATION_PATH . '/dto/Tiket/TiketUpdateLangsungSelesaiRequest.php';
require_once APPLICATION_PATH . '/dto/Tiket/TiketFindByResponse.php';
require_once APPLICATION_PATH . '/dto/TiketImageLaporan/TiketImageLaporanRequest.php';

trait ApiHelpdesk
{
    // api profile petugas
    /**
     * 
     * endpoint : /api/profile
     * method : GET
     * Headers : 
     * Authorization : Bearer {token}
     * 
     * 
     */
    public function profileAction(){
        $token = $this->getBearerToken();
        $user = $this->jwt->decode($token);
        $idPetugas = $user['id'];

        // $data = [];
        try{
            $petugas = $this->penggunaService->findById($idPetugas);
            $rating = $this->tiketPetugasService->getRatingByIdPetugas($idPetugas);
            $data = [
                'id_petugas' => $idPetugas,
                'nama' => $petugas->getNamaLengkap(),
                'jumlah_tiket' => $rating['jumlah_tiket'],
                'jumlah_tiket_nilai' => $rating['jumlah_tiket_nilai'] ? $rating['jumlah_tiket_nilai'] : 0,
                'rating' => floatval($rating['rating']),
                'status' => $petugas->getStatus() ? "Aktif" : "Tidak Aktif",

            ];

        //     array_push($data,
        //     [
        //         'id_petugas' => $idPetugas,
        //         'nama' => $petugas->getNamaLengkap(),
        //         'jumlah_tiket' => $rating['jumlah_tiket'],
        //         'jumlah_tiket_nilai' => $rating['jumlah_tiket_nilai'] ? $rating['jumlah_tiket_nilai'] : 0,
        //         'rating' => $rating['rating'],
        //         'status' => $petugas->getStatus() ? "Aktif" : "Tidak Aktif",
        //         'peran' => $this->peran->getPeranStelaOnly($idPetugas)['peran'] ? $this->peran->getPeranStelaOnly($idPetugas)['peran'] : 'admin',

        //    ]);
    
            $this->sendJson([
                'success' => true,
                'message' => "Tiket berhasil ditemukan",
                'data' =>  $this->view->data = $data
            ]);

        } catch (Exception $e){
            $this->view->error = $e->getMessage();
        }
    }

    // api update laporan
    /**
     * 
     * endpoint : /api/helpdesk/updateSelesai
     * method : POST
     * Headers : 
     * Authorization : Bearer {token}
     * 
     * 
     */
    public function updateSelesaiAction(){
        try {
            $token = $this->getBearerToken();
            $user = $this->jwt->decode($token);
            $namaUpdate = $user['nama_lengkap'];

            if ($this->getRequest()->isPost()) {
                $this->validateBody([
                    'id',
                    'permasalahan_akhir',
                    'solusi',
            ]);
            $id = $this->_getParam('id');
            $permasalahanAkhir = $this->_getParam('permasalahan_akhir');
            $solusi = $this->_getParam('solusi');
            $tiket = $this->tiketService->findById($id);
            // $laporan = $_FILES[]

            $request = new LogRequest();
            $request->setIdTiket($tiket->getId());
            $request->setIdStatusTiketInternal(9);
            $request->setIdStatusTiket($tiket->getIdStatusTiket());
            $request->setPengguna($namaUpdate);
            $request->setKeterangan("$namaUpdate menyelesaikan tugas");

            $this->logService->addLogData($request);

            $request = new TiketImageLaporanRequest();
            $request->setUserUpdate($namaUpdate);
            $request->setUserInput($namaUpdate);
            $request->setPermasalahanAkhir($permasalahanAkhir);
            $request->setSolusi($solusi);
            $request->setTipeLaporan('Solved');
            $request->setIdTiket($id);
            $request->setStatus(1);

            if (!empty($_FILES['dokumen']['name'][0])) {
                //Buat Objek untuk request
                // Panggil function untuk memasukkan lebih dari 1 file ke tiket image laporan
                $this->tiketImageLaporanService->addMultipleFile($_FILES['dokumen'], $request);
            } 
            else {
                $this->tiketImageLaporanService->addTiketImageLaporan($request);
            }
            // }
            $this->tiketService->updateStatusTiketInternal($id, 9, $namaUpdate);
            $this->notifikasiService->sendToAllServiceDesk($tiket->getNoTiket(),"Laporan Selesai!");
            $_SESSION['flash'] = 'Berhasil Update Selesai';
            $this->sendJson([
                'success' => true,
                'message' => "Solusi berhasil dibuat",
            ]);
        }
    }
        catch (Exception $e) {
            $this->sendJson([
                'success' => false,
                'message' => $e->getMessage(),
            ], $e->getCode() >= 400 ? $e->getCode() : 400);

        }
    }

    // api update laporan
    /**
     * 
     * endpoint : /api/helpdesk/updateTerkendala
     * method : POST
     * Headers : 
     * Authorization : Bearer {token}
     * 
     * 
     */
    public function updateTerkendalaAction(){
    try{
        // if ($this->getRequest()->isPost()) {
            $token = $this->getBearerToken();
            $user = $this->jwt->decode($token);
            $namaUpdate = $user['nama_lengkap'];

            
            if ($this->getRequest()->isPost()) {
                $this->validateBody([
                    'id',
                    'permasalahan_akhir',
                    'solusi',
            ]);
            $id = $this->_getParam('id');
            $permasalahanAkhir = $this->_getParam('permasalahan_akhir');
            $kendala = $this->_getParam('kendala');
            $tiket = $this->tiketService->findById($id);

            $request = new LogRequest();
            $request->setIdTiket($tiket->getId());
            $request->setIdStatusTiketInternal(2);
            $request->setIdStatusTiket($tiket->getIdStatusTiket());
            $request->setPengguna($namaUpdate);
            $request->setKeterangan("$namaUpdate mengalami kendala");

            $this->logService->addLogData($request);

            $request = new TiketImageLaporanRequest();
            $request->setUserUpdate($namaUpdate);
            $request->setUserInput($namaUpdate);
            $request->setPermasalahanAkhir($permasalahanAkhir);
            $request->setSolusi($kendala);
            $request->setTipeLaporan('Terkendala');
            $request->setIdTiket($id);
            $request->setStatus(1);

            if (!empty($_FILES['dokumen']['name'][0])) {
                //Buat Objek untuk request
                // Panggil function untuk memasukkan lebih dari 1 file ke tiket image laporan
                $this->tiketImageLaporanService->addMultipleFile($_FILES['dokumen'], $request);
            } 
            else {
                $this->tiketImageLaporanService->addTiketImageLaporan($request);
            }

            $this->tiketService->updateStatusTiketInternal($id, 2, $namaUpdate);
            $this->notifikasiService->sendToAllServiceDesk($tiket->getNoTiket(),"Laporan Terkendala!");
            $_SESSION['flash'] = 'Berhasil Update Terkendala';
            $this->sendJson([
                'success' => true,
                'message' => "Terkendala berhasil dibuat",
                ''
            ]);
        }
    }
    catch (Exception $e) {
        $this->sendJson([
            'success' => false,
            'message' => $e->getMessage(),
        ], $e->getCode() >= 400 ? $e->getCode() : 400);

    }
    }

    // get all tiket petugas
    /**
     * 
     * endpoint : /api/helpdesk/helloWorld
     * method : GET
     * headers : 
     * Authorization : Bearer {token}
     * 
     * 
     */
    public function helpdeskAction() 
    {
        try {
            $token = $this->getBearerToken();
            $user = $this->jwt->decode($token);
            $petugas = $this->tiketPetugasService->getAllDataByIdPetugas($user['id']);
            if ($this->getRequest()->getParam('no_tiket')) {
                $noTiket = $this->getRequest()->getParam('no_tiket');
                $tiket = $this->tiketService->noDtofindByNoTiket($noTiket);
                $tiketPelapor = $this->tiketService->findByNoTiket($noTiket);
                $tiket -> dokumen_lampiran = $this->dokumenLampiranService->getListDokumenLampiran($tiketPelapor -> getId());
                $tiket -> laporan_petugas = $this->tiketImageLaporanService->getListDokumen($tiketPelapor -> getId());
                $tiket -> pelapor = $tiketPelapor->getNamaPelapor();
                $tiket -> bagian = $tiketPelapor->getBagianPelapor();
                $this->sendJson([
                    'success' => true,
                    'message' => "Tiket berhasil ditemukan",
                    'data' => $tiket
                ]);

            } else {
                $data = [];
                foreach ($petugas as $pet){
                    $tiket = $this->tiketService->findById($pet->id_tiket);
                    $listPetugas = $this->tiketPetugasService->getAllDataByIdTiket($tiket->getId());
                    $list = null;
                    if ($listPetugas) {
                        $counter1 = 1;
                        foreach ($listPetugas as $tugas) {
                            $list[$counter1] = [
                                'id' => $tugas->id_petugas,
                                'nama' => $this->penggunaService->findById($tugas->id_petugas)->getNamaLengkap()
                            ];
                            $counter1++;
                        }
                    }
                    
                    array_push($data, [
                        'id_tiket' => $pet->id_tiket,
                        'no_tiket' => $tiket->getNoTiket(),
                        'keterangan' => $tiket->getKeterangan(),
                        'tanggal_input' => $this->dateFormat->directAngkaFUll($tiket->getTanggalInput()),
                        'kategori' => $this->subKategori->getData($tiket->getIdSubKategori())->sub_kategori,
                        'id_status_tiket' => $this->statusTiketService->getData($tiket->getIdStatusTiket())->id,
                        'pelapor' => $tiket->getNamaPelapor(),
                        'bagian' => $tiket->getBagianPelapor(),
                        'unit_kerja_pelapor' => $tiket->getUnitKerjaPelapor(),
                        'gedung_pelapor' => $tiket->getGedungPelapor(),
                        'lantai_pelapor' => $tiket->getLantaiPelapor(),
                        'ruangan_pelapor' => $tiket->getRuanganPelapor(),
                        'hp_pelapor' => $tiket->getHpPelapor(),
                        'email_pelapor' => $tiket->getEmailPelapor(),
                        'permasalahan_akhir'=>$tiket->getPermasalahanAkhir(),
                        'solusi'=> $tiket->getSolusi(),
                        'rating'=>$tiket->getRating(),
                        'keterangan_rating'=>$tiket->getKeteranganRating(),
                        'dokumen_lampiran' => $this->dokumenLampiranService->getListDokumenLampiran($tiket->getId()),
                        'laporan_petugas' => $this->tiketImageLaporanService->getListDokumen($tiket->getId()),
                        'urgensi'=>$this->urgensiService->findById($tiket->getIdUrgensi())->nama,
                        'petugas' => $list,
                    ]);
                    $data_reversed = array_reverse($data, false);
                }
                
                $this->sendJson([
                    'success' => true,
                    'message' => "Tiket berhasil ditemukan",
                    'data' =>  $this->view->data = $data_reversed
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