<?php 

/**
 * Created By Lazade with artisan
 * Created At 2019-01-06
 * */ 

namespace App\Http\Controllers\Backend;

use Google_Client;
use Google_Service_Drive;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Wlog\Storage\GoogleDrive;
use App\Wlog\Storage\Local;

class DriveController extends Controller 
{

    protected $google_drive;
    protected $file;
    protected $appRoot = '14nk0vGirYXpa8VEBTjORgeiXuzCBajuO';

    public function __construct(Google_Client $client)
    {
        $this->middleware( function ($request, $next) use ($client) {
            $client->refreshToken($request->attributes->get('token'));
            $driveHandle = new Google_Service_Drive($client);
            $this->google_drive = new GoogleDrive($driveHandle);
            return $next($request);
        });
    }

    // get files:
    public function fileList()
    {
        $files = $this->google_drive->getFiles($this->appRoot, false);
        return view('backend.file', compact('files'));
    }

    // refresh files:
    public function refresh()
    {
        $files = $this->google_drive->getFiles($this->appRoot, true);
        $result = [
            'state' => true,
            'data' => $files,
        ];
        return response()->json($result);
    }

    // upload file(s): 
    public function upload(Request $request)
    {
        $name = array_diff(explode(',', $request['info']), ['']);
        $response = $this->google_drive->store($name, $this->appRoot);
        return response()->json($response);
    }

    // delete file(s):
    public function delete(Request $request)
    {
        $ids = array_diff(explode(',',$request['id']),['']);
        $response = $this->google_drive->delete($ids, $this->appRoot);
        return response()->json($response);
    }

    public function uploadLogo(Local $local, Request $request)
    {
        $response = $local->store($request);
        return response()->json($response);
    }

}
