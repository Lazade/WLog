<?php

/**
 * Created By Lazade with artisan
 * Created At 2018-10-29
 * */ 

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Wlog\Storage\Dropbox;
use App\Wlog\Storage\Local;

class FileController extends Controller
{
    public function index(Dropbox $dropbox, Request $request)
    {
        $files = $dropbox->localFiles($request);
        return view('backend.file', compact('files'));
    }

    public function getMore(Dropbox $dropbox, Request $request)
    {
        $file = $dropbox->localFiles($request);
        return response()->json($file);
    }

    public function store(Dropbox $dropbox, Request $request)
    {
        $names = array_diff(explode(',', $request['info']), ['']);
        return $dropbox->uploads($names);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Dropbox $dropbox, Request $request)
    {
        $ids = array_diff(explode(',',$request['id']),['']);
        return $dropbox->delete($ids);
    }

    public function uploadLogo(Local $local, Request $request) 
    {
        $response = $local->store($request);
        return response()->json($response);
    }

}
