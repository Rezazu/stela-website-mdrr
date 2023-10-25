<?php

class Dpr_NotifikasiService
{

    function __construct()
    {
        $this->notifikasi = new Notifikasi();
        $this->penggunaService = new Dpr_PenggunaService();
        $this->programmer = new Dpr_ProgrammerService();
        $this->db = Zend_Registry::get('db_stela');
    }

    /**
     * Service get data notifikasi by id pengguna
     * @param string $id_pengguna id pengguna
     * @return array notifikasi pegguna
     */
    function getAllData($id_pengguna)
    {
        $select = $this->notifikasi->select()->where('id_pengguna=?', $id_pengguna)->order('id DESC');
        $result = $this->notifikasi->fetchAll($select);

        return $result;
    }
    function getDataByIdPengguna($id_pengguna)
    {
        $sql = "SELECT * FROM notifikasi WHERE id_pengguna = ? ORDER BY tanggal DESC";
        $statement = $this->db->prepare($sql);
        $statement->execute([$id_pengguna]);
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

    /**
     * Service untuk mengirim notifikasi ke pengguna by id pengguna
     * @param integer id_pengguna yang mau dikirim notifikasi
     * @param string nomor_tiket nomor tiket
     * @param string keterangan keterangan notifikasi
     * @return string id notifikasi
     */
    function sendTo($id_pengguna, $nomor_tiket, $keterangan)
    {
        $param = [
            'id_pengguna' => $id_pengguna,
            'no_tiket' => $nomor_tiket,
            'keterangan' => $keterangan,
            'tanggal' => date('Y-m-d H:i:s'),
        ];
        return $this->notifikasi->insert($param);
    }

    /**
     * @param integer $id_notifikasi id notifikasi yang mau di update
     * @return boolean
     */
    function read($id_notifikasi)
    {
        $param = [
            'dibaca' => 1,
        ];
        $where = $this->notifikasi->getAdapter()->quoteInto('id = ?', $id_notifikasi);
        return $this->notifikasi->update($param, $where);
    }

    function getDataWithLimit($id_pengguna, $limit)
    {
        $select = $this->notifikasi->select()->where('id_pengguna=?', $id_pengguna)->order('id DESC')->limit($limit);
        $result = $this->notifikasi->fetchAll($select);
        return $result;
    }

    // function send notifikasi ke semua pengguna dengan role service desk
    function sendToAllServiceDesk($nomor_tiket, $keterangan)
    {
        // get users stela only service desk
        $usersStela = $this->penggunaService->getPeranServiceDesk();

        // check if users stela not null
        if ($usersStela)
            // iterate users stela
            foreach ($usersStela as $userStela) {
                // check role is service desk
                if ($userStela['id_peran'] == 6)
                    // kirim notifikasi ke service desk bang biar tahu,kalo gak tau kan gimana yeaaa
                    $this->sendTo($userStela['id'], $nomor_tiket, $keterangan);
            }
    }

    // function send notifikasi ke semua pengguna dengan role operator
    function sendToAllOperator($nomor_tiket, $keterangan)
    {
        // get users Verifikator/Operator
        $usersOperator = $this->penggunaService->getPeranVerificator();
        if ($usersOperator)
            foreach ($usersOperator as $userOperator) {
                // kirim notifikasi ke operator bang biar tahu,kalo gak tau kan gimana yeaaa
                $this->sendTo($userOperator['id'], $nomor_tiket, $keterangan);
            }
    }

    function sendToAllProgrammerByIdTimProgrammer($nomor_tiket, $keterangan, $id_tim_programmer)
    {
        $programmers = $this->programmer->getAllProgrammerByIdTimProgrammer($id_tim_programmer);

        if ($programmers) {
            foreach ($programmers as $programmer) {
                //Kirim notifikasi ke programmer berdasarkan id tim programmernya
                if ($programmer->jabatan != 'leader') {
                    $this->sendTo($programmer->id_pengguna, $nomor_tiket, $keterangan);
                }
            }
        }
    }
}
