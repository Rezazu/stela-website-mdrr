<?php
class Dpr_FileManagerService
{
    function __construct()
    {
        $this->storagePath = APPLICATION_PATH . '/storage/';
        $this->dokumenLampiran = 'dokumen_lampiran';
        $this->tiketImageLaporan = 'tiket_image_laporan';
        $this->todoListDokumen = 'todo_list_dokumen';
        $this->profilePath = __DIR__ . '/../../public/stela/assets/profile/';
    }

    function storeProfile($file)
    {
        //Getting the file name of the uploaded file
        $fileName = $file['name'];
        //Getting the Temporary file name of the uploaded file
        $fileTempName = $file['tmp_name'];
        //Getting the file size of the uploaded file
        $fileSize = $file['size'];
        //getting the no. of error in uploading the file
        $fileError = $file['error'];
        //Getting the file type of the uploaded file
        $fileType = $file['type'];

        //Getting the file ext
        $fileExt = explode('.', $fileName);
        $fileActualExt = strtolower(end($fileExt));

        //Checking, Is there any file error
        if ($fileError == 0) {
            //Creating a unique name for file
            $fileNameNew = date('Y-m-d h-i-s') . '-' . uniqid() . '.' . $fileActualExt;
            //File destination
            $fileDestination = $this->profilePath . '/' . $fileNameNew;
            //function to move temp location to permanent location
            move_uploaded_file($fileTempName, $fileDestination);
            //Message after success
            return [
                'file_name' => $fileNameNew,
                'file_path' => $fileDestination,
                'file_size' => $fileSize,
                'file_type' => $fileType,
            ];
        } else {
            //Message, If there is some error
            throw new Exception("Something Went Wrong Please try again!");
        }
    }

    function storeTo($file, $folder)
    {
        //Getting the file name of the uploaded file
        $fileName = $file['name'];
        //Getting the Temporary file name of the uploaded file
        $fileTempName = $file['tmp_name'];
        //Getting the file size of the uploaded file
        $fileSize = $file['size'];
        //getting the no. of error in uploading the file
        $fileError = $file['error'];
        //Getting the file type of the uploaded file
        $fileType = $file['type'];

        //Getting the file ext
        $fileExt = explode('.', $fileName);
        $fileActualExt = strtolower(end($fileExt));

        //Checking, Is there any file error
        if ($fileError == 0) {
            //Creating a unique name for file
            $fileNameNew = date('Y-m-d h-i-s') . '-' . uniqid() . '.' . $fileActualExt;
            //File destination
            $fileDestination = $this->storagePath . $folder . '/' . $fileNameNew;
            //function to move temp location to permanent location
            move_uploaded_file($fileTempName, $fileDestination);
            //Message after success
            return [
                'file_name' => $fileNameNew,
                'file_path' => $fileDestination,
                'file_size' => $fileSize,
                'file_type' => $fileType,
            ];
        } else {
            //Message, If there is some error
            throw new Exception("Something Went Wrong Please try again!");
        }
    }

    /**
     * Save uploaded file to folder dokumen_lampiran
     * @param $_FILES ['nama_field'] $file 
     * @return array informasi_file
     */
    function saveDokumenLampiran($file)
    {
        return $this->storeTo($file, $this->dokumenLampiran);
    }

    /**
     * Save uploaded file to folder tiket_image_laporan
     * @param $_FILES['nama_field'] $file 
     * @return array informasi_file
     */
    function saveTiketImageLaporan($file)
    {
        return $this->storeTo($file, $this->tiketImageLaporan);
    }

    /**
     * Save uploaded file to folder todo_list_dokumen
     * @param $_FILES['nama_field'] $file 
     * @return array informasi_file
     */
    function saveTodoListDokumen($file)
    {
        return $this->storeTo($file, $this->todoListDokumen);
    }

    /**
     * Delete file in dokumen lampiran
     * @param string $file_name nama file yang mau dihapus
     * 
     */
    function deleteDokumenLampiran($file_name)
    {
        $path = $this->storagePath . $this->dokumenLampiran . '/' . $file_name;
        return unlink($path);
    }

    /**
     * Delete file in tiket image laporan
     * @param string $file_name nama file yang mau dihapus
     */
    function deleteTiketImageLaporan($file_name)
    {
        $path = $this->storagePath . $this->tiketImageLaporan . '/' . $file_name;
        return  unlink($path);
    }

    /**
     * Delete file in todo list dokumen
     * @param string $file_name nama file yang mau dihapus
     */
    function deleteTodoListDokumen($file_name)
    {
        $path = $this->storagePath . $this->todoListDokumen . '/' . $file_name;
        return  unlink($path);
    }

    public function download($file, $folder)
    {
        // Fetch the file info.
        $filePath = $this->storagePath . $folder . "/{$file}";
        if (file_exists($filePath)) {
            $fileName = basename($filePath);
            $fileSize = filesize($filePath);

            // Output headers.
            header("Cache-Control: private");
            header("Content-Type: application/stream");
            header("Content-Length: " . $fileSize);
            header("Content-Disposition: attachment; filename=" . $fileName);

            // Output file.
            readfile($filePath);
            exit();
        } else {
            die('The provided file path is not valid.');
        }
    }

    public function readFile($file, $folder)
    {
        //Format that allowed to preview
        $arrayExtension = ['jpg', 'png', 'pdf', 'gif', 'jpeg'];

        // Fetch the file info.
        $filePath = $this->storagePath . $folder . "/{$file}";
        if (file_exists($filePath)) {
            $fileName = basename($filePath);
            $fileSize = filesize($filePath);
            $fileMime = mime_content_type($filePath);

            // Output headers.
            header("Cache-Control: private");
            header("Content-Type: {$fileMime}");
            header("Content-Length: " . $fileSize);

            //Yang bisa dibuka cuma file yang extensionnya ada di arrayExtension, sisanya langsung download
            if (!in_array(explode('.', $file)[1], $arrayExtension)) {
                header("Content-Disposition: attachment; filename=" . $fileName);
            }

            // Output file.
            readfile($filePath);
            exit();
        } else {
            die('The provided file path is not valid.');
        }
    }

    // download dokummen lampiran
    public function downloadDokumenLampiran($fileName)
    {
        $this->download($fileName, $this->dokumenLampiran);
    }

    // download tiket image laporan
    public function downloadTiketImageLaporan($fileName)
    {
        $this->download($fileName, $this->tiketImageLaporan);
    }

    // download todo list dokumen
    public function downloadTodoListDokumen($fileName)
    {
        $this->download($fileName, $this->todoListDokumen);
    }

    // read file dokumen lampiran
    public function readDokumenLampiran($fileName)
    {
        $this->readFile($fileName, $this->dokumenLampiran);
    }
    // read file tiket image laporan
    public function readTiketImageLaporan($fileName)
    {
        $this->readFile($fileName, $this->tiketImageLaporan);
    }
    // read todo list dokumen
    public function readTodoListDokumen($fileName)
    {
        $this->readFile($fileName, $this->todoListDokumen);
    }
}
