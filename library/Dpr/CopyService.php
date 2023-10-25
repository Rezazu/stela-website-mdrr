<?php

class Dpr_CopyService
{

    public function __construct()
    {
        $this->tanggal_log = date('Y-m-d H:i:s');
        $this->tiket = new Dpr_TiketService();
        $this->programmer = new Dpr_ProgrammerService();
        $this->timProgrammer = new Dpr_TimProgrammerService();
        $this->pengguna = new Dpr_PenggunaService();
        $this->subTahapan = new Dpr_SubTahapanService();
        $this->todoList = new Dpr_TodoListService();
        $this->todoListDokumen = new Dpr_TodoListDokumenService();
        $this->dokumenLampiran = new Dpr_DokumenLampiranService();
    }

    public function updateLanjutkanProgrammer($id_tiket, $user_log, $tanggal_baru = null)
    {
        try {
            $oldData = $this->tiket->findById($id_tiket);

            $oldDate = $oldData->getTanggalInput();

            $newDate = str_replace(explode(' ', $oldData->getTanggalInput())[0],
                ((explode('-', explode(' ', $oldData->getTanggalInput())[0])[0])+1) . '-1-2',
                $oldDate);

            //Atau Kalo mau user yang input tahunnya bisa begini

            //$newDate = str_replace((explode('-', explode(' ', $oldData->getTanggalInput())[0])[0]),
            //    $tanggal_baru, $oldDate);

            $request = new TiketAddDataRequest();
            $request->setIdSubKategori($oldData->getIdSubKategori());
            $request->setIdVia($oldData->getIdVia());
            $request->setIdPelapor($oldData->getIdPelapor());
            $request->setIdUrgensi($oldData->getIdUrgensi());
            $request->setIdStatusTiket($oldData->getIdStatusTiket());
            $request->setIdStatusTiketInternal($oldData->getIdStatusTiketInternal());
            $request->setIdAplikasi($oldData->getIdAplikasi());
            $request->setTanggalPelaksanaan($oldData->getTanggalPelaksanaan());
            $request->setStatusRevisi($oldData->getStatusRevisi());
            $request->setKeteranganRevisi($oldData->getKeteranganRevisi());
            $request->setNamaPelapor($oldData->getNamaPelapor());
            $request->setBagianPelapor($oldData->getBagianPelapor());
            $request->setGedungPelapor($oldData->getGedungPelapor());
            $request->setUnitKerjaPelapor($oldData->getUnitKerjaPelapor());
            $request->setRuanganPelapor($oldData->getRuanganPelapor());
            $request->setLantaiPelapor($oldData->getLantaiPelapor());
            $request->setTeleponPelapor($oldData->getTeleponPelapor());
            $request->setHpPelapor($oldData->getHpPelapor());
            $request->setEmailPelapor($oldData->getEmailPelapor());
            $request->setKeterangan($oldData->getKeterangan());
            $request->setUserInput($oldData->getUserInput());
            $request->setUserUpdate($user_log);
            $request->setReferenceBy($oldData->getId());
            $response = $this->tiket->addData($request, $newDate);

            //How to copy all dokumen_lampiran
            $dokumenLampiran = $this->dokumenLampiran->getAllDataByIdTiket($oldData->getId());
            if ($dokumenLampiran){
                foreach ($dokumenLampiran as $dokumen){
                    $this->dokumenLampiran->addData(
                        $response->getId(),
                        $dokumen->image_name,
                        $dokumen->original_name,
                        $dokumen->image_type,
                        $dokumen->image_size,
                        $dokumen->keterangan,
                        $dokumen->user_input,
                        $dokumen->tanggal_input,
                        $dokumen->tanggal_update,
                        $dokumen->status
                    );
                }
            }

            //How to copy all sub_tahapan and todo_list form old tiket to new tiket
            $subTahapan = $this->subTahapan->getAllSubTahapanByIdTiket($id_tiket);
            if ($subTahapan) {
                foreach ($subTahapan as $tahapan) {
                    $requestSubTahapan = new SubTahapanAddRequest();
                    $requestSubTahapan->setIdTahapan($tahapan->id_tahapan);
                    $requestSubTahapan->setIdTiket($response->getId());
                    $requestSubTahapan->setSubTahapan($tahapan->sub_tahapan);
                    $requestSubTahapan->setUserInput($tahapan->user_input);
                    $requestSubTahapan->setUserUpdate($tahapan->user_update);
                    $requestSubTahapan->setStatus($tahapan->status);
                    $idSubTahapan = $this->subTahapan->addData($requestSubTahapan, $tahapan->tanggal_input, $tahapan->tanggal_update)->getId();

                    $todoList = $this->todoList->getAllDataByIdSubTahapan($tahapan->id);
                    if ($todoList){
                        foreach ($todoList as $todo){
                            $requestTodoList = new TodoListAddRequest();
                            $requestTodoList->setIdSubTahapan($idSubTahapan);
                            $requestTodoList->setIdProgrammer($todo->id_programmer);
                            $requestTodoList->setTodoList($todo->todo_list);
                            $requestTodoList->setDeskripsiRevisi($todo->deskripsi_revisi);
                            $requestTodoList->setUserInput($todo->user_input);
                            $requestTodoList->setUserUpdate($todo->user_update);
                            $requestTodoList->setKeterangan($todo->keterangan);
                            $requestTodoList->setStatusKerja($todo->status_kerja);
                            $idTodoList = $this->todoList->addData($requestTodoList, $todo->tanggal_input, $todo->tanggal_update)->getId();

                            $todoListDokumen = $this->todoListDokumen->getAllDataForProgrammer($todo->id);
                            if ($todoListDokumen){
                                foreach ($todoListDokumen as $dokumen){
                                    $requestDokumen = new TodoListDokumenRequest();
                                    $requestDokumen->setIdJenisDokumen($dokumen->id_jenis_dokumen);
                                    $requestDokumen->setIdTodoList($idTodoList);
                                    $requestDokumen->setOriginalName($dokumen->original_name);
                                    $requestDokumen->setDokumenName($dokumen->dokumen_name);
                                    $requestDokumen->setDokumenSize($dokumen->dokumen_size);
                                    $requestDokumen->setDokumenType($dokumen->dokumen_type);
                                    $requestDokumen->setUserInput($dokumen->user_input);
                                    $requestDokumen->setStatus($dokumen->status);
                                    $this->todoListDokumen->addData($requestDokumen, $dokumen->tanggal_input);
                                }
                            }
                        }
                    }
                }
            }

            //How to get duplicae leader to new id_tim_programmer
            $idTimProgrammer = $oldData->getIdTimProgrammer();
            $leader = $this->pengguna->findById($this->programmer->getLeaderProgrammerByIdTim($idTimProgrammer)->id_pengguna);
            $namaTim = $response->getNoTiket() . '-' . $leader->getNamaLengkap();
            $idTim = $this->timProgrammer->tambahTim($namaTim)->getId();

            //Ini buat nambahin lead programmer yang udah ditentuin sebelumnya
            $requestProgrammer = new ProgrammerRequest();
            $requestProgrammer->setIdTimProgrammer($idTim);
            $requestProgrammer->setIdPengguna($leader->getId());
            $requestProgrammer->setJabatan('leader');
            $this->programmer->addDataProgrammer($requestProgrammer);

            //Ini buat namnbahin programmer dari tiket sebelumnya ke tiket baru
            $allPorgrammer = $this->programmer->getAllProgrammerByIdTimProgrammer($oldData->getIdTimProgrammer());
            if ($allPorgrammer){
                foreach ($allPorgrammer as $pro){
                    $this->programmer->addMultipleProgrammer($idTim, $pro['id_pengguna']);
                }
            }

            //Ini nambahin id_tim_programmer ke tiket nya
            $requestTiket = new TiketUpdateIdTimProgrammerRequest();
            $requestTiket->setId($response->getId());
            $requestTiket->setUserUpdate($user_log);
            $requestTiket->setIdTimProgrammer($idTim);
            $this->tiket->updateIdTimProgrammer($requestTiket);

            //Update reference_to di tiket lama
            $oldData->setReferenceTo($response->getId());
            $oldData->setUserUpdate($user_log);
            $request = $this->tiket->update($oldData);
            $this->tiket->updateData($request);

            return $response->getId();

        } catch (Exception $exception) {
            throw $exception;
        }
    }
}