<?php 

/**
 * Created By Lazade with artisan
 * Created At 2018-10-29
 * */ 

namespace App\Wlog\Storage;

use Storage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class local
{
    
    public function store(Request $request)
    {
        $path = $request->file('file')->storeAs('', $request->get('filename'), 'public');
        if ($path) {
            $result = [
                'state' => 200,
                'path' => '/'.$path,
            ];
        } else {
            $result = [
                'state' => 500,
                'error' => 'Error',
            ];
        }
        return $result;
    }

}