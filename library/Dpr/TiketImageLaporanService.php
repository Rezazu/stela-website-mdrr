<?php

require_once APPLICATION_PATH . '/dto/TiketImageLaporan/TiketImageLaporanRequest.php';

class Dpr_TiketImageLaporanService
{
    protected $db;
    protected $tiketImageLaporan;
    protected $tanggal_log;

    function __construct()
    {
        $this->db = Zend_Registry::get('db_stela');
        $this->tiketImageLaporan = new TiketImageLaporan();
        $this->tanggal_log = date('Y-m-d H:i:s');
        $this->fileManager = new Dpr_FileManagerService();
        $this->dateFormat = new Dpr_Controller_Action_Helper_YmdToIndo();
    }

    public function findById($id)
    {
        try {
            $sql = "SELECT * FROM tiket_image_laporan WHERE id = ?";
            $statement = $this->db->prepare($sql);
            $statement->execute([$id]);
            $statement->setFetchMode(Zend_Db::FETCH_OBJ);
            if ($result = $statement->fetch()) {
                return $result;
            } else {
                return null;
            }

        } finally {
            $statement->closecursor();
        }
    }

    public function addTiketImageLaporan(TiketImageLaporanRequest $request)
    {
        try {
            $sql = "INSERT INTO tiket_image_laporan(id_tiket,permasalahan_akhir,solusi,tipe_laporan, image_name, image_type, image_size, original_name,  user_input, tanggal_input, user_update, tanggal_update) 
                    VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";
            $statement = $this->db->prepare($sql);
            $statement->execute([
                $request->getIdTiket(),
                $request->getPermasalahanAkhir(),
                $request->getSolusi(),
                $request->getTipeLaporan(),
                $request->getImageName(),
                $request->getImageType(),
                $request->getImageSize(),
                $request->getOriginalName(),
                $request->getUserInput(),
                $this->tanggal_log,
                $request->getUserUpdate(),
                $this->tanggal_log
            ]);
            $request->setId($this->tiketImageLaporan->getAdapter()->lastInsertId());
            return $request;
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function addTiketImageLaporan2(TiketImageLaporanRequest $request)
    {
        try {
            $sql = "INSERT INTO tiket_image_laporan(id_tiket,permasalahan_akhir,solusi,tipe_laporan, user_input, tanggal_input, user_update, tanggal_update) 
                    VALUES (?,?,?,?,?,?,?,?)";
            $statement = $this->db->prepare($sql);
            $statement->execute([
                $request->getIdTiket(),
                $request->getPermasalahanAkhir(),
                $request->getSolusi(),
                $request->getUserInput(),
                $this->tanggal_log,
                $request->getUserUpdate(),
                $this->tanggal_log
            ]);
            $request->setId($this->tiketImageLaporan->getAdapter()->lastInsertId());
            return $request;
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function getAllDataBy($id_tiket)
    {
        try {
            $sql = "SELECT * FROM tiket_image_laporan WHERE id_tiket = ? ORDER BY id DESC";
            $statement = $this->db->prepare($sql);
            $statement->execute([$id_tiket]);
            $statement->setFetchMode(Zend_Db::FETCH_OBJ);
            if ($result = $statement->fetchAll()) {
                return $result;
            } else {
                return null;
            }
        } finally {
            $statement->closecursor;
        }
    }

    //Function untuk menambahkan lebih dari 1 file ke database
    public function addMultipleFile($files, TiketImageLaporanRequest $request)
    {
        $temp = null;
        //Perulangan for untuk membuat array dengan format lebih baik
        for ($i = 0; $i < count($files['name']); $i++) {
            $temp[$i]['name'] = $files['name'][$i];
            $temp[$i]['type'] = $files['type'][$i];
            $temp[$i]['tmp_name'] = $files['tmp_name'][$i];
            $temp[$i]['error'] = $files['error'][$i];
            $temp[$i]['size'] = $files['size'][$i];
        }

        $tiket = (new Dpr_TiketService())->findById($request->getIdTiket());

        foreach ($temp as $value) {
            $name = explode('.', $value['name']);

            //Setiap file dilakukan pemindahan dari temporary ke folder fix
            $file = $this->fileManager->saveTiketImageLaporan($value);

            //Memasukkan data sisianya berdasakan file
            $request->setImageName($file['file_name']);
            $request->setImageSize($file['file_size']);
            $request->setImageType($file['file_type']);
            $request->setOriginalName($name[0]);

            //Setelah itu data dokumen dimasukkan ke dalam database
            $response = $this->addTiketImageLaporan($request);

            //Masukkin log guys
            $requestLog = new LogRequest();
            $requestLog->setIdTiket($request->getIdTiket());
            $requestLog->setKeterangan("Upload dokumen laporan {$name[0]}");
            $requestLog->setPengguna($request->getUserInput());
            $requestLog->setIdStatusTiket($tiket->getIdStatusTiket());
            $requestLog->setFileOriginalName($name[0]);
            $requestLog->setFileName($request->getImageName());
            $requestLog->setFileSize($request->getImageSize());
            $requestLog->setFileType($request->getImageType());
            $requestLog->setIdTiketImageLaporan($response->getId());

            if ($request->getTipeLaporan() == 'Terkendala'){
                $requestLog->setIdStatusTiketInternal(2);
            }else{
                $requestLog->setIdStatusTiketInternal(9);
            }

            (new Dpr_LogService())->addLogData($requestLog);

        }
    }

    //Function untuk mengambil list tiket image laporan
    function getListDokumen($id_tiket)
    {
        $dir = APPLICATION_PATH . '/storage/tiket_image_laporan/';
        $data=[];

        $documents = $this->getAllDataBy($id_tiket);

        if ($documents) {
            $counter = 1;
            foreach ($documents as $doc) {
                // $data['laporan_petugas'][$counter] = [
                //     'id_dokumen' => $doc->id,
                //     'permasalahan_akhir' => $doc->permasalahan_akhir,
                //     'solusi' => $doc->solusi,
                //     'tipe_laporan' => $doc->tipe_laporan,
                //     'original_name' => $doc->original_name,
                //     'doc_name' => explode('.', $doc->image_name)[0],
                //     'ext' => explode('.', $doc->image_name)[1],
                //     'image_name' => $doc->image_name,
                //     'user_input' => $doc->user_input,
                //     'tanggal_input' => $doc->tanggal_input,
                //     'path' => $dir . $doc->image_name
                // ];
                array_push($data, [
                    'id_dokumen' => $doc->id,
                    'permasalahan_akhir' => $doc->permasalahan_akhir,
                    'solusi' => $doc->solusi,
                    'tipe_laporan' => $doc->tipe_laporan,
                    'original_name' => $doc->original_name,
                    'doc_name' => explode('.', $doc->image_name)[0],
                    'ext' => explode('.', $doc->image_name)[1],
                    'image_name' => $doc->image_name,
                    'user_input' => $doc->user_input,
                    'tanggal_input' => $doc->tanggal_input,
                    'path' => $dir . $doc->image_name
                ]);

                // $counter++;
            }
            // return $data['laporan_petugas'];
            return $data;
        } else {
            return  null;
        }
    }
}
