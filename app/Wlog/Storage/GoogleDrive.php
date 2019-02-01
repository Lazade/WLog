<?php 

/**
 * Created By Lazade with artisan
 * Created At 2019-01-06
 * */ 

namespace App\Wlog\Storage;

use Storage;
use Google_Client;
use Google_Service_Exception;
use Google_Service_Drive;
use Google_Service_Drive_DriveFile;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;

class GoogleDrive 
{
    private $drive_handle; // google drive handle

    protected $error = '';
    protected $file;

    // construct: 
    public function __construct($driveHandle) {
        $this->drive_handle = $driveHandle;
    }


    // api:

    /** 
     * get files in the specified directory(id)
     * @param String $root: specified directory(id)
     * @param boolean $force: if set true, retrieve the files data agin, else get from the cache
     * 
    */
    public function getFiles($root, $force) {
        if ($force || !Cache::has('drive_file')) {
            $drive_file = $this->listFiles($root);
            $expiresAt = Carbon::now()->addDays(3);
            Cache::put('drive_file', $drive_file, $expiresAt);
        }
        return Cache::get('drive_file');
    }

    /** 
     * upload files in the specified directory(id)
     * @param Array name: 
     * @param String root: 
     * 
    */
    public function store($name, $root)
    {
        if (!$this->checkFile($name)) {
            $response = [
                'state' => 500,
                'info' => ['error' => $this->error],
            ];
            return $response;
        }
        $info = [];
        foreach ($this->file as $file) {
            $result = $this->createFile($file, $root);
            $info[] = [
                'head_state' => 200,
                'status' => 'Uploaded Successfully',
                'id' => $result,
                'name' => $result,
            ];
        }
        $response = [
            'status' => 200,
            'action' => 'uploads',
            'info' => $info,
        ];

        // update cache
        $drive_file = $this->listFiles($root);
        $expiresAt = Carbon::now()->addDays(3);
        Cache::put('drive_file', $drive_file, $expiresAt);

        return $response;
    }

    //
    public function delete($ids, $root)
    {
        $info = [];
        foreach ($ids as $key => $id) {
            $result = $this->deleteFile($id);
            if ($result) {
                $info[] = [
                    'head_state' => 200,
                    'id' => $id,
                    'status' => 'delete success',
                ];
            } else {
                $info[] = [
                    'head_state' => 500,
                    'id' => $id,
                    'status' => 'delete success - error',
                ];
            }
        }

        // update cache
        $drive_file = $this->listFiles($root);
        $expiresAt = Carbon::now()->addDays(3);
        Cache::put('drive_file', $drive_file, $expiresAt);

        return ['action' => 'delete', 'info' => $info];
    }

    // private methods
    private function listFiles($root)
    {
        $query = "mimeType='image/jpeg' and '".$root."' in parents and trashed=false";

        $optParams = [
            'fields' => 'files(id, name)',
            'q' => $query,
        ];

        $results = $this->drive_handle->files->listFiles($optParams);

        if (count($results->getFiles()) == 0) {
            print "No files found.\n";
        } else {
            $files = [];
            foreach ($results->getFiles() as $file) {
                $files[] = [
                    'id' => $file->getID(),
                    'name' => $file->getName()
                ];
            }
            return $files;
        }
    } 

    private function createFile($file, $root)
    {
        $name = $file['name'];
        $fileObj = $file['file'];

        $fileMetadata = new Google_Service_Drive_DriveFile([
            'name' => $name,
            'parents' => array($root),
        ]);

        $content = File::get($fileObj);
        $mimeType = File::mimeType($fileObj);

        $result = $this->drive_handle->files->create($fileMetadata, [
            'data' => $content,
            'mimeType' => $mimeType,
            'uploadType' => 'multipart',
            'fields' => 'id, name'
        ]);

        return $result->id;
    }

    private function checkFile($name) 
    {
        foreach ($name as $file ) {
            if (!app('request')->hasFile($file)) {
                $this->error = 'No Files';
                return false;
            }
            $this->file[$file] = [
                'file' => app('request')->file($file),
                'name' => $file,
            ];
            if (!$this->file[$file]['file']->isValid()) {
                $this->error = 'Files is invalid';
                return false;
            }
        }
        return true;
    }
 
    private function deleteFile($id)
    {
        try {
            return $this->drive_handle->files->delete($id);
        } catch (Exception $e) {
            return false;
        }
    }

}