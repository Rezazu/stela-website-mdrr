<?php

require_once APPLICATION_PATH . '/dto/Log/LogRequest.php';

class Dpr_LogService
{

    protected $db;
    protected $log;
    protected $tanggal_log;

    public function __construct()
    {
        $this->log = new Log();
        $this->db = Zend_Registry::get('db_stela');
        $this->tanggal_log = date('Y-m-d H:i:s');
        $this->status = new Dpr_StatusTiketService();
        $this->dateFormat = new Dpr_Controller_Action_Helper_YmdToIndo();
        $this->listPeran = new Dpr_ListPeranService();
        $this->peran = new Dpr_PeranService();
        $this->tahapan = new Dpr_TahapanService();
    }

    public function addLogData(LogRequest $request)
    {

        $sql = "INSERT INTO log(id_aplikasi, id_urgensi, id_status_tiket_internal, id_dokumen_lampiran, id_kategori, id_list_peran, id_notifikasi, id_pengguna, id_peran, 
                id_programmer, id_rating, id_status_tiket, id_sub_kategori, id_sub_tahapan, id_tahapan, id_tiket, 
                id_tiket_data, id_tiket_image_laporan, id_tiket_petugas, id_tim_programmer, id_todo_list, 
                id_todo_list_dokumen, id_via, pengguna, keterangan, tanggal_input, file_name, file_size, file_type,
                file_original_name) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $statement = $this->db->prepare($sql);
        $statement->execute([
            $request->getIdAplikasi(),
            $request->getIdUrgensi(),
            $request->getIdStatusTiketInternal(),
            $request->getIdDokumenLampiran(),
            $request->getIdKategori(),
            $request->getIdListPeran(),
            $request->getIdNotifikasi(),
            $request->getIdPengguna(),
            $request->getIdPeran(),
            $request->getIdProgrammer(),
            $request->getIdRating(),
            $request->getIdStatusTiket(),
            $request->getIdSubKategori(),
            $request->getIdSubTahapan(),
            $request->getIdTahapan(),
            $request->getIdTiket(),
            $request->getIdTiketData(),
            $request->getIdTiketImageLaporan(),
            $request->getIdTiketPetugas(),
            $request->getIdTimProgrammer(),
            $request->getIdTodoList(),
            $request->getIdTodoListDokumen(),
            $request->getIdVia(),
            $request->getPengguna(),
            $request->getKeterangan(),
            $this->tanggal_log,
            $request->getFileName(),
            $request->getFileSize(),
            $request->getFileType(),
            $request->getFileOriginalName()
        ]);
        $statement->closecursor();

        return $request;
    }

    public function getLogByIdTiket($id_tiket)
    {
        $sql = "SELECT * FROM log WHERE id_tiket = ?";
        $statement = $this->db->prepare($sql);
        $statement->execute([$id_tiket]);
        $statement->setFetchMode(Zend_Db::FETCH_OBJ);

        try {
            if ($result = $statement->fetchAll()) {
                return $result;
            } else {
                return null;
            }
        } finally {
            $statement->closecursor();
        }
    }

    public function getLogInDetailProgrammer($id_tiket)
    {
        $sql = "SELECT * FROM log WHERE id_tiket = ? AND (id_todo_list IS NOT NULL OR id_sub_tahapan IS NOT NULL 
                OR id_tahapan IS NOT NULL OR id_todo_list_dokumen IS NOT NULL OR id_sub_kategori = 1)";
        $statement = $this->db->prepare($sql);
        $statement->execute([$id_tiket]);
        $statement->setFetchMode(Zend_Db::FETCH_OBJ);

        try {
            if ($result = $statement->fetchAll()) {
                return $result;
            } else {
                return null;
            }
        } finally {
            $statement->closecursor();
        }
    }

    public function getAllLogServiceDesk(TiketFindByResponse $tiket)
    {
        $log = $this->getLogByIdTiket($tiket->getId());

        $counter = 1;
        foreach ($log as $l) {
            $d = null;
            if ($l->id_dokumen_lampiran) {
                $d = [
                    'folder' => 'dokumen-lampiran',
                    'nama' => $l->file_original_name,
                    'doc_name' => explode('.', $l->file_name)[0],
                    'ext' => explode('.', $l->file_name)[1]
                ];
            } elseif ($l->id_tiket_image_laporan) {
                $d = [
                    'folder' => 'tiket-image-laporan',
                    'nama' => $l->file_original_name,
                    'doc_name' => explode('.', $l->file_name)[0],
                    'ext' => explode('.', $l->file_name)[1]
                ];
            } elseif ($l->id_dokumen_lampiran) {
                $d = [
                    'folder' => 'dokumen-lampiran',
                    'nama' => $l->file_original_name,
                    'doc_name' => explode('.', $l->file_name)[0],
                    'ext' => explode('.', $l->file_name)[1]
                ];
            } elseif ($l->id_todo_list_dokumen) {
                $d = [
                    'folder' => 'todo-list-dokumen',
                    'nama' => $l->file_original_name,
                    'doc_name' => explode('.', $l->file_name)[0],
                    'ext' => explode('.', $l->file_name)[1]
                ];
            }

            $date = explode(' ', $this->dateFormat->directAngkaFUll($l->tanggal_input));
            $data[$counter] = [
                'id_log' => $l->id,
                'status_tiket_internal' => ($l->id_status_tiket_internal) ? $this->status->getData($l->id_status_tiket_internal)->status_tiket : null,
                'status_tiket' => ($l->id_status_tiket) ? $this->status->getData($l->id_status_tiket)->status_tiket : null,
                'tanggal' => $date[1],
                'waktu' => $date[0],
                'tahapan_programmer' => $this->tahapan->getDataById($l->id_tahapan)->tahapan,
                'petugas' => $l->pengguna,
                'keteranagn' => $l->keterangan,
                'dokumen' => ($d) ? $d : null
            ];
            $counter++;
        }

        return $data;

    }
}