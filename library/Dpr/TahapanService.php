<?php

class Dpr_TahapanService
{

	protected $db;

	function __construct()
	{
		$this->tahapan = new Tahapan();
        $this->subTahapan = new Dpr_SubTahapanService();
        $this->todoList = new Dpr_TodoListService();
        $this->todoListDokumen = new Dpr_TodoListDokumenService();
        $this->pengguna = new Dpr_PenggunaService();
		$this->db = Zend_Registry::get('db_stela');
        $this->dateFormat = new Dpr_Controller_Action_Helper_YmdToIndo();
	}

	function getAllData()
	{
		$sql = "SELECT * FROM tahapan ORDER BY id";

		$statement = $this->db->query($sql);
		$statement->setFetchMode(Zend_Db::FETCH_OBJ);
		$result = $statement->fetchAll();

		return $result;
	}

	function getDataById($id)
	{
		$sql = "SELECT * FROM tahapan WHERE id = ?";
		$statement = $this->db->prepare($sql);
		$statement->execute([$id]);
		$statement->setFetchMode(Zend_Db::FETCH_OBJ);

		try{
			if($result = $statement->fetch()){
                return $result;
			}else{
				return null;
			}
		}finally{
			$statement->closecursor();
		} 
	}

    public function getTahapan(TiketFindByResponse $tiket)
    {
        $subTahapan = $this->subTahapan->getDataSubTahapanByIdTiket($tiket->getId());
        $dir = APPLICATION_PATH . '/storage/todo_list_dokumen/';

        $listSubTahapan = null;
        if ($subTahapan)
        {
            $listTahapan[1] = [
                'id' => 1,
                'tahapan' => $this->getDataById(1)->tahapan,
                'tanggal_mulai' => $this->dateFormat->directAngkaFUll($tiket->getTanggalInput())
            ];

            $tahapanTemp = null;
            $counter1 = 1;
            $counter3 = 2;
            foreach ($subTahapan as $subTahap){
                if ($subTahap->status != 9) {
                    $todoList = $this->todoList->getAllDataByIdSubTahapan($subTahap->id);
                    $listTodoList = null;
                    if ($todoList) {
                        $counter2 = 1;
                        $selesai = 0;
                        foreach ($todoList as $todo) {
                            if ($todo->status_kerja != 9)
                            {
                                $dokumenTodoList = $this->todoListDokumen->getAllDataForUser($todo->id);
                                $listDokumenTodoList = null;
                                if ($dokumenTodoList) {
                                    foreach ($dokumenTodoList as $dokumen) {
                                        $listDokumenTodoList = [
                                            'id_dokumen' => $dokumen->id,
                                            'original_name' => $dokumen->original_name,
                                            'doc_name' => explode('.', $dokumen->dokumen_name)[0],
                                            'ext' => explode('.', $dokumen->dokumen_name)[1],
                                            'dokumen_path' => $dir . $dokumen->name
                                        ];
                                    }
                                }

                                if ($todo->status_kerja == 0)
                                {
                                    $listTodoList[$counter2] = [
                                        'id_todo_list' => $todo->id,
                                        'todo_list' => $todo->todo_list,
                                        'id_programmer' => $todo->id_programmer,
                                        'nama_programmer' => $this->pengguna->findById($todo->id_programmer)->getNamaLengkap(),
                                        'tanggal' => $this->dateFormat->directAngkaFUll($todo->tanggal_update),
                                        'status' => $todo->status_kerja,
                                        'keterangan' => $todo->keterangan,
                                        'dokumen' => $listDokumenTodoList
                                    ];
                                    $selesai += 1;
                                }elseif($todo->status_kerja == 1){
                                    $listTodoList[$counter2] = [
                                        'id_todo_list' => $todo->id,
                                        'todo_list' => $todo->todo_list,
                                        'status' => $todo->status_kerja
                                    ];
                                }elseif($todo->status_kerja == 2){
                                    $listTodoList[$counter2] = [
                                        'id_todo_list' => $todo->id,
                                        'todo_list' => $todo->todo_list,
                                        'status' => $todo->status_kerja,
                                        'keterangan_revisi' => $todo->deskripsi_revisi
                                    ];
                                }

                                $counter2++;
                            }
                        }
                    }

                    //Ini buat ngecek apakah jumlah keseluruhan todolist sama dengan jumlah todolist yang selesai
                    if (count($todoList) == $selesai && $selesai != 0)
                    {
                        if ($subTahap->status != 0)
                        {
                            //Kalo jumlahnya sama dan statusnya belum 0 akan dilakukan perubahan status pada sub tahapan menjadi 0
                            $subTahapBaru = $this->subTahapan->updateStatusSelesai($subTahap->id);
                            $subTahap = $this->subTahapan->findById($subTahapBaru->getId());
                        }else{
                            $subTahap = $this->subTahapan->findById($subTahap->id);
                        }

                    }else{
                        //Kalo ga sama jumlahnya ini yang dijalanin
                        if ($subTahap->status != 1)
                        {

                            //Kalo status sub tahapannya ga 1 ini yang dijalanin, karena bakal ngubah statusnya jadi 1
                            $subTahapBaru = $this->subTahapan->updateStatusBelumSelesai($subTahap->id);
                            $subTahap = $this->subTahapan->findById($subTahapBaru->getId());
                        }else{
                            //Kalo statusnya masih 1 gabakal diubah lagi statusnya
                            $subTahap = $this->subTahapan->findById($subTahap->id);
                        }

                    }

                    $listSubTahapan[$counter1] = [
                        'id_tahapan' => $subTahap->getIdTahapan(),
                        'id_sub_tahapan' => $subTahap->getId(),
                        'sub_tahapan' => $subTahap->getSubTahapan(),
                        'status' => $subTahap->getStatus(),
                        'todo_list' => $listTodoList
                    ];

                    $tahapan = $this->getDataById($subTahap->getIdTahapan())->tahapan;
                    if ($tahapan != $tahapanTemp) {
                        $listTahapan[$counter3] = [
                            'id' => $subTahap->getIdTahapan(),
                            'tahapan' => $tahapan,
                            'tanggal_mulai' => $this->dateFormat->directAngkaFUll($subTahap->getTanggalInput())
                        ];
                        $counter3++;
                    }
                    $tahapanTemp = $tahapan;
                    $counter1++;
                }
            }
            return [$listTahapan, $listSubTahapan];
        }else{
            return [[
                '1' => [
                    'id' => 1,
                    'tahapan' => $this->getDataById(1)->tahapan,
                    'tanggal_mulai' => $this->dateFormat->directAngkaFUll($tiket->getTanggalInput())
                ]
            ]];
        }
    }

    /**
     * Function yang ngebalikin data berapa jumlah total sub tahapan tiap tahapan
     * Jumlah yang selesai tiap tahapan
     * Sama persentase dari jumlah selesai dibagi total dikali 100
     */
    public function getPersentaseSubTahapan($detail, $tahap)
    {
        $total = [
            'perencanaan' => 0,
            'perancangan' => 0,
            'implementasi' => 0,
            'pengujian' => 0,
            'serah terima' => 0
        ];

        $selesai = [
            'perencanaan' => 0,
            'perancangan' => 0,
            'implementasi' => 0,
            'pengujian' => 0,
            'serah terima' => 0
        ];

        if ($detail) {
            foreach ($detail as $det) {
                if ($det['id_tahapan'] == 2) {
                    $total['perencanaan'] += 1;
                    if ($det['status'] == 0) {
                        $selesai['perencanaan'] += 1;
                    }
                } elseif ($det['id_tahapan'] == 3) {
                    $total['perancangan'] += 1;
                    if ($det['status'] == 0) {
                        $selesai['perancangan'] += 1;
                    }
                } elseif ($det['id_tahapan'] == 4) {
                    $total['implementasi'] += 1;
                    if ($det['status'] == 0) {
                        $selesai['implementasi'] += 1;
                    }
                } elseif ($det['id_tahapan'] == 5) {
                    $total['pengujian'] += 1;
                    if ($det['status'] == 0) {
                        $selesai['pengujian'] += 1;
                    }
                } elseif ($det['id_tahapan'] == 6) {
                    $total['serah terima'] += 1;
                    if ($det['status'] == 0) {
                        $selesai['serah terima'] += 1;
                    }
                }
            }
        }

        $counter = 1;
        foreach ($tahap as $hap)
        {
            $listTahapan[$counter] = [
                'id_tahapan' => $hap['id'],
                'tahapan' => $hap['tahapan']
            ];

            if ($hap['id'] > 1)
            {
                if ($total[strtolower($hap['tahapan'])] != 0)
                {
                    $listTahapan[$counter]['persentase'] = ($selesai[strtolower($hap['tahapan'])]/$total[strtolower($hap['tahapan'])])*100 . "%";
                }else{
                    $listTahapan[$counter]['persentase'] = "0%";
                }
                $listTahapan[$counter]['tahapan_selesai'] = $selesai[strtolower($hap['tahapan'])];
                $listTahapan[$counter]['tahapan_total'] = $total[strtolower($hap['tahapan'])];
            }

            $counter++;
        }

//        Memberikan nilai sisanya dengan semua isinya 0
        for ($i = count($listTahapan)+1; $i <= 6; $i++)
        {
            $listTahapan[$i] = [
                'id_tahapan' => $i,
                'tahapan' => $this->getDataById($i)->tahapan,
                'persentase' => 0 . '%',
                'tahapan_selesai' => 0,
                'tahapan_total' => 0
            ];
        }

        /**
         * Data yang dibalikin meliputi
         * - Persentase
         * - Sub Thapan selesai
         * - Sub tahapan total
         */
        return [$listTahapan, [$total, $selesai]];
    }
}
