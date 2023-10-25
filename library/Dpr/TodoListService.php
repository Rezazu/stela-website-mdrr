<?php

require_once APPLICATION_PATH . '/dto/TodoList/TodoListAddRequest.php';
require_once APPLICATION_PATH . '/dto/TodoList/TodoListFIndByIdResponse.php';
require_once APPLICATION_PATH . '/dto/TodoList/TodoListUpdateRequest.php';
require_once APPLICATION_PATH . '/dto/TodoList/TodoListUpdateTodoListRequest.php';
require_once APPLICATION_PATH . '/dto/TodoList/TodoListUpdateRevisiRequest.php';
require_once APPLICATION_PATH . '/dto/TodoList/TodoListSoftDeleteRequest.php';

class Dpr_TodoListService
{
    protected $db;
    protected $todoList;
    protected $tanggal_log;

    public function __construct()
    {
        $this->todoList = new TodoList();
        $this->db = Zend_Registry::get('db_stela');
        $this->tanggal_log = date('Y-m-d H:i:s');
    }

    public function addData(TodoListAddRequest $request, $tanggal_input = null, $tanggal_update = null)
    {

        try{
            $this->validateEmpty($request->getTodoList());

            $sql = "INSERT INTO todo_list(id_sub_tahapan, id_programmer, todo_list, deskripsi_revisi, user_input, 
                    tanggal_input, user_update, tanggal_update, keterangan, status_kerja) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $statement = $this->db->prepare($sql);
            $statement->execute([
                $request->getIdSubTahapan(),
                $request->getIdProgrammer(),
                $request->getTodoList(),
                $request->getDeskripsiRevisi(),
                $request->getUserInput(),
                ($tanggal_input) ? $tanggal_input : $this->tanggal_log,
                $request->getUserUpdate(),
                ($tanggal_update) ? $tanggal_update : $this->tanggal_log,
                $request->getKeterangan(),
                $request->getStatusKerja()
            ]);
            $request->setId($this->todoList->getAdapter()->lastInsertId());
            return $request;
        }catch(Exception $exception){
            throw $exception;
        }
        
    }

    public function findById($id)
    {

        $sql = "SELECT id, id_sub_tahapan, id_programmer, todo_list, deskripsi_revisi, keterangan, user_input, status_kerja, tanggal_input, user_update, tanggal_update 
                FROM todo_list WHERE id = ?";
        $statement = $this->db->prepare($sql);
        $statement->execute([$id]);
        $statement->setFetchMode(Zend_Db::FETCH_OBJ);

        try{
            if($result = $statement->fetch())
            {
                $response = new TodoListFIndByIdResponse();

                $response->setId($result->id);
                $response->setIdSubTahapan($result->id_sub_tahapan);
                $response->setIdProgrammer($result->id_programmer);
                $response->setTodoList($result->todo_list);
                $response->setDeskripsiRevisi($result->deskripsi_revisi);
                $response->setKeterangan($result->keterangan);
                $response->setUserInput($result->user_input);
                $response->setStatusKerja($result->status_kerja);
                $response->setTanggalInput($result->tanggal_input);
                $response->setUserUpdate($result->user_update);
                $response->setTanggalUpdate($result->tanggal_update);

                return $response;
            }else{
                return null;
            }
        }finally{
            $statement->closecursor();
        }

    }

    public function updateData(TodoListUpdateRequest $request)
    {
        $sql = "UPDATE todo_list SET id_programmer = ?, todo_list = ?, user_update = ?, status_kerja = ?, tanggal_update = ?, deskripsi_revisi = ?, keterangan = ? WHERE id = ?";
        $statement = $this->db->prepare($sql);
        $statement->execute([
            $request->getIdProgrammer(),
            $request->getTodoList(),
            $request->getUserUpdate(),
            $request->getStatusKerja(),
            $this->tanggal_log,
            $request->getDeskripsiRevisi(),
            $request->getKeterangan(),
            $request->getId()
        ]);

        $statement->closecursor();

        return $request;
    }

    public function updateTodoList(TodoListUpdateTodoListRequest $request)
    {

        try{
            $this->validateEmpty($request->getTodoList());

            $data = $this->findById($request->getId());

            if($data == null){
                throw new Exception("Data Todo List Tidak Ditemukan");
            }

            $data->setId($request->getId());
            $data->setUserUpdate($request->getUserUpdate());
            $data->setTodoList($request->getTodoList());

            $todoListUpdateRequest = $this->update($data);

            $result = $this->updateData($todoListUpdateRequest);

            return $result;
        }catch(Exception $exception){
            throw $exception;
        }
    }

    public function updateRevisi(TodoListUpdateRevisiRequest $request)
    {
        
        try{
            $this->validateEmpty($request->getDeskripsiRevisi());

            $data = $this->findById($request->getId());

            if($data == null){
                throw new Exception("Data Todo List Tidak Ditemukan");
            }

            $jumlah = explode('revisi', $data->getTodoList());
            if (count($jumlah) == 1)
            {
                $count = count($jumlah);
                $todoList = $data->getTodoList() . "-revisi($count)";
            }else{
                $count = substr($jumlah[1], 1) + 1;
                $todoList = explode('-', $data->getTodoList());
                $todoList = $todoList[0] . "-revisi($count)";
            }

            $data->setId($request->getId());
            $data->setStatusKerja($request->getStatusKerja());
            $data->setUserUpdate($request->getUserUpdate());
            $data->setTodoList($todoList);
            $data->setDeskripsiRevisi($request->getDeskripsiRevisi());

            $todoListUpdateRequest = $this->update($data);

            $result = $this->updateData($todoListUpdateRequest);

            return $result;
        }catch(Exception $exception){
            throw $exception;
        }
    }

    public function updateSelesai(TodoListUpdateSelesaiRequest $request)
    {
        try{
            $data = $this->findById($request->getId());

            if($data == null){
                throw new Exception("Data Todo List Tidak Ditemukan");
            }

            $data->setId($request->getId());
            $data->setStatusKerja($request->getStatusKerja());
            $data->setUserUpdate($request->getUserUpdate());
            $data->setIdProgrammer($request->getIdProgrammer());
            $data->setKeterangan($request->getKeterangan());
            
            $todoListUpdateRequest = $this->update($data);

            $result = $this->updateData($todoListUpdateRequest);

            return $result;
        }catch(Exception $exception){
            throw $exception;
        }
    }

    public function getAllDataByIdSubTahapan($id_sub_tahapan)
    {
        $sql = "SELECT * FROM todo_list WHERE id_sub_tahapan = ?";
        $statement = $this->db->prepare($sql);
        $statement->execute([$id_sub_tahapan]);
        $statement->setFetchMode(Zend_Db::FETCH_OBJ);

        try{
            if($result = $statement->fetchAll()){
                return $result;
            }else{
                return null;
            }
        }finally{
            $statement->closecursor();
        }
    }

    //Fnction buat dapetin persentase dari todo list
    public function getPersentaseTodoList($detail, $tahap)
    {
        $total = [
            'perencanaan' => 0,
            'perancangan' => 0,
            'implementasi' => 0,
            'pengujian' => 0,
            'serah_terima' => 0
        ];

        $selesai = [
            'perencanaan' => 0,
            'perancangan' => 0,
            'implementasi' => 0,
            'pengujian' => 0,
            'serah_terima' => 0
        ];

        foreach ($detail as $det)
        {
            if ($det['id_tahapan'] == 2){
                if ($det['todo_list']){
                    foreach ($det['todo_list'] as $todo)
                    {
                        $total['perencanaan'] += 1;
                        if ($todo['status'] == 0)
                        {
                            $selesai['perencanaan'] += 1;
                        }
                    }
                }
            }elseif ($det['id_tahapan'] == 3){
                if ($det['todo_list']){
                    foreach ($det['todo_list'] as $todo)
                    {
                        $total['perancangan'] += 1;
                        if ($todo['status'] == 0)
                        {
                            $selesai['perancangan'] += 1;
                        }
                    }
                }
            }elseif ($det['id_tahapan'] == 4){
                if ($det['todo_list']){
                    foreach ($det['todo_list'] as $todo)
                    {
                        $total['implementasi'] += 1;
                        if ($todo['status'] == 0)
                        {
                            $selesai['implementasi'] += 1;
                        }
                    }
                }
            }elseif ($det['id_tahapan'] == 5){
                if ($det['todo_list']){
                    foreach ($det['todo_list'] as $todo)
                    {
                        $total['pengujian'] += 1;
                        if ($todo['status'] == 0)
                        {
                            $selesai['pengujian'] += 1;
                        }
                    }
                }
            }elseif ($det['id_tahapan'] == 6){
                if ($det['todo_list']){
                    foreach ($det['todo_list'] as $todo)
                    {
                        $total['serah_terima'] += 1;
                        if ($todo['status'] == 0)
                        {
                            $selesai['serah_terima'] += 1;
                        }
                    }
                }
            }
        }

        $counter = 1;
        foreach ($tahap as $tah)
        {
            $listTahapan[$counter] = [
                'id_tahapan' => $tah['id'],
                'tahapan' => $tah['tahapan']
            ];

            if ($tah['id'] == 2)
            {
                $listTahapan[$counter]['persentase'] = $selesai['perencanaan']/$total['perencanaan'];
                $listTahapan[$counter]['todo_list_selesai'] = $selesai['perencanaan'];
                $listTahapan[$counter]['todo_list_total'] = $total['perencanaan'];
            }elseif ($tah['id'] == 3)
            {
                $listTahapan[$counter]['persentase'] = $selesai['perancangan']/$total['perancangan'];
                $listTahapan[$counter]['todo_list_selesai'] = $selesai['perancangan'];
                $listTahapan[$counter]['todo_list_total'] = $total['perancangan'];
            }elseif ($tah['id'] == 4)
            {
                $listTahapan[$counter]['persentase'] = $selesai['implementasi']/$total['implementasi'];
                $listTahapan[$counter]['todo_list_selesai'] = $selesai['implementasi'];
                $listTahapan[$counter]['todo_list_total'] = $total['implementasi'];
            }elseif ($tah['id'] == 5)
            {
                $listTahapan[$counter]['persentase'] = $selesai['pengujian']/$total['pengujian'];
                $listTahapan[$counter]['todo_list_selesai'] = $selesai['pengujian'];
                $listTahapan[$counter]['todo_list_total'] = $total['pengujian'];
            }elseif ($tah['id'] == 6)
            {
                $listTahapan[$counter]['persentase'] = $selesai['serah_terima']/$total['serah_terima'];
                $listTahapan[$counter]['todo_list_selesai'] = $selesai['serah_terima'];
                $listTahapan[$counter]['todo_list_total'] = $total['serah_terima'];
            }
            $counter++;
        }

        return $listTahapan;
    }

    public function getAllData()
    {
        $sql = "SELECT * FROM todo_list";
        $statement = $this->db->query($sql);
        $statement->setFetchMode(Zend_Db::FETCH_OBJ);

        try{
            if($result = $statement->fetchAll()){
                return $result;
            }else{
                return null;
            }
        }finally{
            $statement->closecursor();
        }
    }

    public function delete($id)
    {
        $sql = "DELETE FROM todo_list WHERE id = ?";
        $statement = $this->db->prepare($sql);
        $statement->execute([$id]);

        return "Berhasil dihapus";
    }

    public function softDelete(TodoListSoftDeleteRequest $request)
    {
        try{
            $data = $this->findById($request->getId());

            if($data == null){
                throw new Exception("Data Todo List Tidak Ditemukan");
            }

            $data->setId($request->getId());
            $data->setUserUpdate($request->getUserUpdate());
            $data->setStatusKerja($request->getStatusKerja());
            
            $todoListUpdateRequest = $this->update($data);

            $this->updateData($todoListUpdateRequest);

            return "Berhasil";
        }catch(Exception $exception){
            throw $exception;
        }
    }

    private function update(TodoListFIndByIdResponse $data)
    {
        $todoListUpdateRequest = new TodoListUpdateRequest();

        $todoListUpdateRequest->setId($data->getId());
        $todoListUpdateRequest->setDeskripsiRevisi($data->getDeskripsiRevisi());
        $todoListUpdateRequest->setKeterangan($data->getKeterangan());
        $todoListUpdateRequest->setStatusKerja($data->getStatusKerja());
        $todoListUpdateRequest->setTodoList($data->getTodoList());
        $todoListUpdateRequest->setUserUpdate($data->getUserUpdate());
        $todoListUpdateRequest->setIdProgrammer($data->getIdProgrammer());

        return $todoListUpdateRequest;
    }

    private function validateEmpty($value){
        $validator = new Zend_Validate_NotEmpty([Zend_Validate_NotEmpty::INTEGER + 
            Zend_validate_NotEmpty::ZERO +
            Zend_Validate_NotEmpty::STRING]
        );
        if(!$validator->isValid($value))
        {
            throw new Exception("Tidak boleh kosong");
        }
    }

}