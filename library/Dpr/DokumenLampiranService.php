<?php

class Dpr_DokumenLampiranService
{

    function __construct()
    {
        $this->dokumen_lampiran = new DokumenLampiran();
        $this->fileManager = new Dpr_FileManagerService();
        $this->logService = new Dpr_LogService();
    }

    public function findById($id)
    {
        $sql = "SELECT * FROM dokumen_lampiran WHERE id = ?";
        $db = Zend_Registry::get('db_stela');
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
        $stmt->setFetchMode(Zend_Db::FETCH_OBJ);

        $result = $stmt->fetch();
        return $result;
    }

    function getAllData()
    {
        $select = $this->dokumen_lampiran->select()->where('status = 1')->order('id_tiket');
        $result = $this->dokumen_lampiran->fetchAll($select);
        return $result;
    }

    function getAllQueryData()
    {
        $sql = 'SELECT * FROM dokumen_lampiran WHERE status = 1 ORDER BY id_tiket';

        $db = Zend_Registry::get('db_stela');
        $stmt = $db->query($sql);
        $stmt->setFetchMode(Zend_Db::FETCH_OBJ);
        $result = $stmt->fetchAll();
        return $result;
    }

    function getData($id)
    {
        $select = $this->dokumen_lampiran->select()->where('id = ?', $id);
        $result = $this->dokumen_lampiran->fetchRow($select);
        return $result;
    }

    function getAllDataByIdTiket($id_tiket)
    {
        $sql = 'SELECT * FROM dokumen_lampiran WHERE status = 1 AND id_tiket = ?';

        $db = Zend_Registry::get('db_stela');
        $stmt = $db->query($sql, array($id_tiket));
        $stmt->setFetchMode(Zend_Db::FETCH_OBJ);
        $result = $stmt->fetchAll();

        return $result;
    }

    function addData($id_tiket, $image_name, $original_name, $image_type, $image_size, $keterangan, $user_log, $tanggal_input = null, $tanggal_update = null, $status = 1)
    {
//		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
        $tanggal_log = date('Y-m-d H:i:s');

        $params = array(
            'id_tiket' => $id_tiket,
            'keterangan' => $keterangan,
            'image_name' => $image_name,
            'original_name' => $original_name,
            'image_type' => $image_type,
            'image_size' => $image_size,
            'user_input' => $user_log,
            'tanggal_input' => ($tanggal_input) ? $tanggal_input : $tanggal_log,
            'user_update' => $user_log,
            'tanggal_update' => ($tanggal_update) ? $tanggal_update : $tanggal_log,
            'status' => $status
        );

        $this->dokumen_lampiran->insert($params);

        return (object)[
            'id' => $this->dokumen_lampiran->getAdapter()->lastInsertId(),
            'image_name' => $image_name,
            'original_name' => $original_name,
            'keterangan' => $keterangan
        ];
    }

    function editData($id, $image_name, $original_name, $image_type, $image_size, $keterangan)
    {
        $user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
        $tanggal_log = date('Y-m-d H:i:s');

        $params = array(
            'keterangan' => $keterangan,
            'original_name' => $original_name,
            'image_name' => $image_name,
            'image_type' => $image_type,
            'image_size' => $image_size,
            'user_update' => $user_log,
            'tanggal_update' => $tanggal_log
        );

        $where = $this->dokumen_lampiran->getAdapter()->quoteInto('id = ?', $id);
        $this->dokumen_lampiran->update($params, $where);
    }

    function deleteData($id)
    {
        $where = $this->dokumen_lampiran->getAdapter()->quoteInto('id = ?', $id);
        $this->dokumen_lampiran->delete($where);
    }

    function deleteQueryData($id)
    {
        $sql = 'DELETE FROM dokumen_lampiran WHERE id = ?';

        $db = Zend_Registry::get('db_stela');
        $db->query($sql, array($id));
    }

    function softDeleteData($id)
    {
        $user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
        $tanggal_log = date('Y-m-d H:i:s');

        $params = array(
            'status' => 9,
            'user_update' => $user_log,
            'tanggal_update' => $tanggal_log
        );

        $where = $this->dokumen_lampiran->getAdapter()->quoteInto('id = ?', $id);
        $this->dokumen_lampiran->update($params, $where);
    }

    function softDeleteQueryData($id)
    {
        $user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
        $tanggal_log = date('Y-m-d H:i:s');

        $sql = 'UPDATE dokumen_lampiran SET status = 9, user_update = ?, tanggal_update = ? WHERE id = ?';

        $db = Zend_Registry::get('db_stela');
        $db->query($sql, array($user_log, $tanggal_log, $id));
    }

    //Function untuk meyimpan lebih dari 1 file dokumnen lampiran
    function addMultipleFile($files, $tiket, $statusRevisi = null)
    {
        $temp = null;
        for ($i = 0; $i < count($files['name']); $i++) {
            $temp[$i]['name'] = $files['name'][$i];
            $temp[$i]['type'] = $files['type'][$i];
            $temp[$i]['tmp_name'] = $files['tmp_name'][$i];
            $temp[$i]['error'] = $files['error'][$i];
            $temp[$i]['size'] = $files['size'][$i];
        }

        foreach ($temp as $value) {
            $name = explode('.', $value['name']);
            $file = $this->fileManager->saveDokumenLampiran($value);

            $data = $this->addData($tiket->getId(), $file['file_name'],
                $name[0], $file['file_type'], $file['file_size'], $tiket->getKeterangan(),
                $tiket->getUserInput());

            $request = new LogRequest();
            $request->setIdTiket($tiket->getId());
            $request->setPengguna($tiket->getNamaPelapor());
            $request->setIdPengguna($tiket->getIdPelapor());
            $request->setIdStatusTiket($tiket->getIdStatusTiket());
            $request->setIdStatusTiketInternal($tiket->getIdStatusTiketInternal());
            $request->setFileName($file['file_name']);
            $request->setFileType($file['file_type']);
            $request->setFileSize($file['file_size']);
            $request->setFileOriginalName($name[0]);
            $request->setIdDokumenLampiran($data->id);

            if ($statusRevisi == 'Dalam Revisi'){

                $request->setKeterangan("Upload dokumen revisi {$name[0]}");

            }else{
                $request->setKeterangan("Upload dokumen lampiran {$name[0]}");
            }

            $this->logService->addLogData($request);
        }
    }

    //Function untuk mendapatkan list dokumen lampiran berdasarkan id tiket
    function getListDokumenLampiran($id_tiket)
    {
        $dir = APPLICATION_PATH . '/storage/dokumen_lampiran/';
        $data = [];

        $documents = $this->getAllDataByIdTiket($id_tiket);

        if ($documents) {
            $counter = 1;
            foreach ($documents as $doc) {
                // $data['dokumen_lampiran'][$counter] = [
                //     'id_dokumen' => $doc->id,
                //     'original_name' => $doc->original_name,
                //     'image_name' => $doc->image_name,
                //     'doc_name' => explode('.', $doc->image_name)[0],
                //     'ext' => explode('.', $doc->image_name)[1],
                //     'user_input' => $doc->user_input,
                //     'tanggal_input' => $doc->tanggal_input,
                //     'path' => $dir . $doc->image_name
                // ];
                // $counter++;
                array_push($data, [
                    'id_dokumen' => $doc->id,
                    'original_name' => $doc->original_name,
                    'image_name' => $doc->image_name,
                    'doc_name' => explode('.', $doc->image_name)[0],
                    'ext' => explode('.', $doc->image_name)[1],
                    'user_input' => $doc->user_input,
                    'tanggal_input' => $doc->tanggal_input,
                    'path' => $dir . $doc->image_name
                ]);
            }
            return $data;
        } else {
            return null;
        }
    }
}
