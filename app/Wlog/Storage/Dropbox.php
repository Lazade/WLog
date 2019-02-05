<?php 

/** 
 * Created By Lazade
 * Created At 2019-02-02
*/

namespace App\Wlog\Storage;

use Storage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use League\Flysystem\Filesystem;
use Spatie\Dropbox\Client;
use Spatie\FlysystemDropbox\DropboxAdapter;
use Illuminate\Support\Facades\Cache;

class Dropbox
{

    // Properties:

    protected $file = [];
    protected $error = '';
    protected $token;

    private $new_content_hash = [];
    private $new_list_data = [];

    // Public Methods: 

    public function __construct()
    {
        $this->token = config('filesystems.disks.dropbox.access_token');
    }

    public function getFilesList($force)
    {
        if ($force || !Cache::has('drive_file')) {
            $this->__getList();
        }
        return Cache::get('drive_file');
    }

    public function uploadToDropbox($name)
    {
        if (!$this->__checkFile($name)) {
            return [
                'status' => 500,
                'info' => [
                    'error' => $this->error,
                ],
            ];
        }

        $info = [];
        $new_content_hash = [];
        $new_list_data = [];
        foreach ($this->file as $file) {
            if ($this->__fileExists($file['file'])) {
                $info[] = [
                    'head_state' => 201,
                    'id' => $file['name'],
                    'status' => 'already existed',
                ];
                continue;
            }    
            $result = $this->upload($file);
            if ($result) {
                $temp = [
                    'head_state' => 200,
                    'status' => 'uploaded successfully',
                    'name' => $result['name'],
                    'title' => $result['name'],
                    'id' => $result['id'],
                    'shared_link' => str_ireplace('www', 'dl', $this->list_shared_links($result['name'])),
                ];
                $new_content_hash[] = $result['content_hash'];
                $new_list_data[] = [
                    'id' => $temp['id'],
                    'name' => $temp['name'],
                    'title' => $temp['title'],
                    'shared_link' => $temp['shared_link'],
                ];
                $info[] = $temp;
            } else {
                $info [] = [
                    'head_state' => 500,
                    'file' => $file['name'],
                    'status' => $this->error,
                ];
            }
        }

        $this->__updateCache($new_list_data, $new_content_hash);

        return [
            'action' => 'uploads',
            'status' => '200',
            'info' => $info,
        ];

    }

    public function remove($filenames)
    {
        $info = [];
        $new_list_data = [];
        $new_content_hash = [];
        foreach ($filenames as $key => $filename) {
            $result = $this->delete($filename);
            if ($result !== false) {
                $info[] = [
                    'head_state' => 200,
                    'status' => 'delete success',
                    'name' => $result['metadata']['name'],
                    'id'   => $result['metadata']['id'],
                ];
                $new_list_data[] = [
                    'id' => $result['metadata']['id'],
                    'name' => $result['metadata']['name'],
                ];
                $new_content_hash[] = $result['metadata']['content_hash'];
            } else {
                $info[] = [
                    'head_state' => 500,
                    'id' => $filename,
                    'status' => 'delete error', 
                ];
            }
        }

        $this->__updateCache($new_list_data, $new_content_hash, false);

        return [
            'action' => 'delete', 
            'info' => $info
        ];
    }

    // private method
    private function __updateCache($new_list_data, $new_content_hash, $isAdd = true) 
    {
        $drive_file = Cache::get('drive_file');
        $file_hash = Cache::get('file_hash');
        if ($isAdd === true) {
            $drive_file = array_merge($drive_file, $new_list_data);
            $file_hash = array_merge($file_hash, $new_content_hash);
        } else {
            foreach ($new_content_hash as $hash) {
                $key = array_keys($file_hash, $hash);
                unset($file_hash[end($key)]);
            }
            foreach ($new_list_data as $value) {
                foreach ($drive_file as $key => $df) {
                    if ($value['id'] == $df['id']) {
                        unset($drive_file[$key]);
                    }
                }
            }
        }
        $expiresAt = Carbon::now()->addDays(3);
        Cache::put('drive_file', $drive_file, $expiresAt);
        Cache::put('file_hash', $file_hash, $expiresAt);
    }

    private function __getList()
    {
        $this->list_folder();
        $files = $this->list_shared_links();
        $drive_file = [];
        foreach ($files['links'] as $file) {
            $drive_file[] = [
                'id' => $file['id'],
                'name' => $file['name'],
                'title' => $file['name'],
                'shared_link' => str_ireplace('www', 'dl', $file['url']),
            ];
        }
        $expiresAt = Carbon::now()->addDays(3);
        Cache::put('drive_file', $drive_file, $expiresAt);
    }

    private function __checkFile($name)
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

    private function __fileExists($file)
    {
        $size = 4 * 1024 * 1024;
        $realPath = is_string($file) ? $file : $file->getRealPath();
        $block_hash = '';
        $fp = fopen($realPath, "rb");
        while (!feof($fp)) {
            $block = fread($fp, $size);
            $block_hash .= hash('sha256', $block, true);
            unset($block);
        }
        fclose($fp);
        $hash = hash('sha256', $block_hash);
        return in_array($hash, Cache::get('file_hash'));
    }

    // Dropbox Rest API

    private function list_shared_links($filename = null)
    {
        $ch = curl_init();
        $header = array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->token
        );
        if (is_null($filename)) {
            $params = json_encode(null);
        } else {
            $params = json_encode(array(
                'path' => '/'.$filename,
            ));
        }
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $header );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt( $ch, CURLOPT_URL, 'https://api.dropboxapi.com/2/sharing/list_shared_links');
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE );
        curl_setopt( $ch, CURLOPT_POST, TRUE);

        try {
            $api_response = curl_exec($ch);
        } catch (Exception $e) {
            throw new Exception($e->message());
        }

        curl_close($ch);
        $json_response = json_decode($api_response, true);

        if (array_key_exists('error', $json_response)) {

        } else {
            if (is_null($filename)) {
                return $json_response;
            } else {
                return empty($json_response['links']) ? $this->create_shared_link_with_settings($filename) : $json_response['links'][0];
            }
        }
    }

    private function list_folder()
    {
        $ch = curl_init();
        $header = array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->token
        );
        $params = json_encode(array(
            'path' => '',
        ));
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $header );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt( $ch, CURLOPT_URL, 'https://api.dropboxapi.com/2/files/list_folder');
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE );
        curl_setopt( $ch, CURLOPT_POST, TRUE);
        try {
            $api_response = curl_exec($ch);
        } catch (Exception $e) {
            throw new Exception($e->message());
        }
        curl_close($ch);
        $json_response = json_decode($api_response, true);
        if (array_key_exists('entries', $json_response)) {
            $data = [];
            foreach ($json_response['entries'] as $value) {
                $data[] = $value['content_hash'];
            }
            $expiresAt = Carbon::now()->addDays(3);
            Cache::put('file_hash', $data, $expiresAt);
        }
    }

    private function upload($file)
    {
        $_file = $file['file'];
        $ch = curl_init();
        $params = json_encode(array(
            'path' => '/' . $file['name'].'.'.$_file->extension(),
            'autorename' => true,
        ));
        $header = array(
            'Content-Type: application/octet-stream',
            'Dropbox-API-Arg:' . $params,
            'Authorization: Bearer ' . $this->token,
        );
        $stream = fopen($_file->getRealPath(), 'rb');
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $header );
        curl_setopt( $ch, CURLOPT_PUT, true );
        curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'POST' );
        curl_setopt( $ch, CURLOPT_INFILE, $stream );
        curl_setopt( $ch, CURLOPT_INFILESIZE, $_file->getSize());
        curl_setopt( $ch, CURLOPT_URL, 'https://content.dropboxapi.com/2/files/upload');
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE );
        try {
            $api_response = curl_exec($ch);
        } catch (Exception $e) {
            throw new Exception($e->message());
        }
        curl_close($ch);
        fclose($stream);
        $json_response = json_decode($api_response, true);
        return $json_response;
    }

    private function create_shared_link_with_settings($filename)
    {
        $ch = curl_init();
        $args = json_encode(array(
            'path' => '/'.$filename,
            'settings' => array(
                'requested_visibility' => 'public',
            ),
        ));
        $header = array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->token,
        );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $header );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $args);
        curl_setopt( $ch, CURLOPT_URL, 'https://api.dropboxapi.com/2/sharing/create_shared_link_with_settings');
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE );
        curl_setopt( $ch, CURLOPT_POST, TRUE);
        try {
            $api_response = curl_exec($ch);
        } catch (Exception $e) {
            throw new Exception($e->message());
        }
        curl_close($ch);
        return json_decode($api_response, true)['url'];
    }

    private function delete($filename)
    {
        $ch = curl_init();
        $params = json_encode(array(
            'path' => '/'.$filename,
        ));
        $header = array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->token,
        );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $header );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt( $ch, CURLOPT_URL, 'https://api.dropboxapi.com/2/files/delete_v2');
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE );
        curl_setopt( $ch, CURLOPT_POST, TRUE);
        try {
            $api_response = curl_exec($ch);
        } catch (Exception $e) {
            throw new Exception($e->message());
        }
        $json_response = json_decode($api_response, true);
        curl_close($ch);
        if (array_key_exists('error', $json_response)) {
            return false;
        } else {
            return $json_response;
        }
    }

}
