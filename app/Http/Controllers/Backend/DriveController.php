<?php 

/** 
 * Created By Lazade with Artisan
 * Created At 2019-02-02
*/

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Wlog\Storage\Dropbox;

class DriveController extends Controller
{

    // get files' list 
    public function fileList(Dropbox $dropbox, Request $request)
    {
        $files = $dropbox->getFilesList(false);
        return view('backend.file', compact('files'));
    }

    // refresh files' list
    public function refresh(Dropbox $dropbox, Request $request)
    {
        $files = $dropbox->getFilesList(true);
        $result = [
            'state' => true,
            'data' => $files,
        ];
        return response()->json($result);
    }

    // upload file(s)
    public function upload(Dropbox $dropbox, Request $request)
    {
        $names = array_diff(explode(',', $request['info']), ['']);
        $response = $dropbox->uploadToDropbox($names);
        return response()->json($response);
    }


    // delete file(s)
    public function delete(Dropbox $dropbox, Request $request)
    {
        $filenames = array_diff(explode(',', $request['filename']), ['']);
        $response = $dropbox->remove($filenames);
        return response()->json($response);
    }

}