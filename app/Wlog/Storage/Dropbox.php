<?php 

/**
 * Created By Lazade with artisan
 * Created At 2018-10-29
 * */ 

namespace App\Wlog\Storage;

use Storage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use League\Flysystem\Filesystem;
use App\Models\Attachments;
use Spatie\Dropbox\Client;
use Spatie\FlysystemDropbox\DropboxAdapter;

class Dropbox
{
    
    // Properties: 
      
    protected $file = [];
    protected $error = '';
    protected $client;
    protected $adapter;
    protected $filesystem;

    // Public Methods: 

    public function __construct() 
    {
        $this->client = new Client(config('filesystems.disks.dropbox.access_token'));
        $this->adapter = new DropboxAdapter($this->client);
        $this->filesystem = new Filesystem($this->adapter);
    }

    public function localFiles(Request $request)
    {
        $rows = intval($request->rows) > 0 ? $request->rows : 20;
        $list = Attachments::paginate($rows);
        foreach ($list as $key => $item) {
            $ori_path = $item['path'];
            $path = str_replace('www', 'dl', $ori_path);
            $temp = explode('/', str_replace('?dl=0', '', $path));
            $name = end($temp);
            $list[$key]['path'] = $path;
            $list[$key]['name'] = $name;
        }
        return $list;
    }

    public function uploads($name)
    {
        if (!$this->checkFile($name)) {
            return ['status' => 500, 'info' => ['error' => $this->error]]; 
        }
        $info = [];
        foreach ($this->file as $file) {
            $fileObject = $this->fileExists($file['file']);
            if (!empty($fileObject)) {
                $info[] = [
                    'head_state' => 201,
                    'file' => $file['name'],
                    'status' => ' Existed',
                ];
                continue;
            }
            $result = $this->put($file);
            if ($result != false) {
                $info[] = [
                    'head_state' => 200,
                    'status' => ' Uploaded Successfully.',
                    'file' => $result['filename'],
                    'url' => $result['shareUrl'],
                    'id' => $result['id'],
                    'date' => $result['date'],
                ];
            } else {
                $info[] = [
                    'head_state' => 500,
                    'file' => $file['name'],
                    'status' => $this->error,
                ];
            }
        }
        return ['acttion' => 'uploads', 'status' => 200, 'info' => $info];
    }

    public function delete($ids)
    {
        $info = [];
        foreach ($ids as $key => $id) {
            $file = Attachments::where('id', $id)->first();
            $temp = explode('/', str_replace('?dl=0', '', $file->path));
            $name = end($temp);
            // get specific file's path

            $result = $this->remove($name);
            if ($result['status']) {
                if ($this->removeFileFromDB($id)) {
                    $info[] = [
                        'head_state' => 200,
                        'file' => $result['name'],
                        'id' => $id,
                        'status' => 'delete success',
                    ];
                } else {
                    $info[] = [
                        'head_state' => 500,
                        'file' => $result['name'],
                        'status' => 'delete success - DataBase ERROR',
                    ];
                }
            } else {
                $info[] = [
                    'head_state' => 500,
                    'file' => $result['name'],
                    'status' => 'delete success - '.$result['error'],
                ];
            }
            // continue;
        }
        return ['action' => 'delete', 'info' => $info];
    }

    // Private Methods: 

    /** 
     * checkFile: check files, which get from request, whether existed and valid
     * @param string name
     * @return boolean 
     * 
     * */ 
    private function checkFile($name)
    {
        foreach ($name as $file) {
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

    /** 
     * put: get specific file's information and save
     * @param object $fileObject
     * @return mixed array/boolean
     * 
    */
    private function put($fileObject) 
    {
        $name = $fileObject['name'];
        $file = $fileObject['file'];
        $ext = $file->extension();
        $realPath = $file->getRealPath();
        $fileName = $name.'.'.$ext;
        $contents = file_get_contents($realPath);
        $result = $this->filesystem->put($fileName, $contents, ['visibility' => 'public']);
        if ($result) {
            $hash1 = sha1_file($file->getRealPath());
            $md5 = md5_file($file->getRealPath());
            $shareurl = $this->checkShareLink($fileName);

            if ($shareurl != '' && !is_null($shareurl)) {
                $response = $this->saveFileUrl($shareurl, $hash1, $md5);
            } else {
                $this->error = 'Can not get ShareUrl';
                return false;
            }

            if (!empty($response)) {
                $arr = array(
                    'shareUrl' => str_replace('www', 'dl', $shareurl),
                    'filename' => $fileName,
                    'id' => $response->id,
                    'date' => $response->created_at,
                );
                return $arr;
            } else {
                $this->error = 'Model save ERROR';
                return false;
            }
        } else {
            $this->error = 'Can not upload to Dropbox';
            return false;
        }
    }

    /** 
     * fileExists: check the specific file whether exists in Database
     * @param string $file
     * @return mixed Object / null
     * 
     * */ 
    private function fileExists($file) 
    {
        $realPath = is_string($file) ? $file : $file->getRealPath();
        $hash1 = sha1_file($realPath);
        $data = Attachments::where('hash1', $hash1)->first();
        return $data;
    }

    /** 
     * saveFileUrl: call model to save file data to Database
     * @param string $path
     * @param string $hash1
     * @param string $md5
     * @return int $id : -1
     * 
     * */ 
    private function saveFileUrl($path, $hash1, $md5)
    {
        $attachment = new Attachments;
        $attachment->path = $path;
        $attachment->hash1 = $hash1;
        $attachment->md5 = $md5;
        if ($attachment->save()) {
            return $attachment;
        } else {
            return -1;
        }
    }

    /** 
     * removeFileFromDB: call model to remove file data from Database
     * @param int $id
     * @return boolean 
     * 
    */
    private function removeFileFromDB($id)
    {
        $attachment = new Attachments;
        return $attachment->where('id', $id)->delete();
    }

    //  Dropbox rest API methods:

    /** 
     * getShareLink: generate specific file's sharelink from dropbox 
     * @param string $fileName
     * @return mixed array: error / string: sharelink
     * 
     * */ 
    private function getShareLink($fileName) 
    {
        $ch = curl_init(); 
        $header = array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . config('filesystems.disks.dropbox.access_token')
        );
        $params = json_encode(array(
            "path" => "/".$fileName,
            "settings" => array("requested_visibility"=>"public"),
        ));

        curl_setopt( $ch, CURLOPT_HTTPHEADER, $header );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt( $ch, CURLOPT_URL, 'https://api.dropboxapi.com/2/sharing/create_shared_link_with_settings');
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE );
        curl_setopt( $ch, CURLOPT_POST, TRUE);
        $api_response = curl_exec($ch);
        if(curl_exec($ch) === false) {
            return ['Curl error' => curl_error($ch)];
        }
        $json_response = json_decode($api_response, true);
        return $json_response['url'];
    }

    /** 
     * checkShareLink: check specific file's sharelink whether created, if not call Func(getShareLink)
     * @param string $fileName 
     * @return mixed array: error / string: sharelink
     * 
     * */ 
    private function checkShareLink($fileName)
    {
        $ch = curl_init(); 
        $header = array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . config('filesystems.disks.dropbox.access_token')
        );
        $params = json_encode(array(
            "path" => "/".$fileName,
        ));

        curl_setopt( $ch, CURLOPT_HTTPHEADER, $header );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt( $ch, CURLOPT_URL, 'https://api.dropboxapi.com/2/sharing/list_shared_links');
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE );
        curl_setopt( $ch, CURLOPT_POST, TRUE);
        $api_response = curl_exec($ch);
        if(curl_exec($ch) === false) {
            return ['Curl error' => curl_error($ch)];
        }
        $json_response = json_decode($api_response, true);
        if (empty($json_response['links'])) {
            return $this->getShareLink($fileName);
        } else {
            return $json_response['links'][0]['url'];
        }
    }

    /** 
     * remove: remove specific file from dropbox
     * @param string $fileName
     * @return array
     * 
     * */ 
    private function remove($fileName)
    {
        $ch = curl_init();
        $header = array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . config('filesystems.disks.dropbox.access_token')
        );
        $params = json_encode(array(
            'path' => "/".$fileName,
        ));
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $header );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt( $ch, CURLOPT_URL, 'https://api.dropboxapi.com/2/files/delete_v2');
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE );
        curl_setopt( $ch, CURLOPT_POST, TRUE);
        $api_response = curl_exec($ch);
        if(curl_exec($ch) === false) {
            return ['Curl error' => curl_error($ch)];
        }
        $json_response = json_decode($api_response, true);
        if (array_key_exists('error', $json_response)) {
            return ['status' => false, 'error' => $json_response['error']['.tag']];
        } else {
            return ['status' => true, 'name' => $json_response['metadata']['name']];
        }
    }

}
