<?php 

/**
 * Created By Lazade with artisan
 * Created At 2018-10-29
 * */ 

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Options;
use App\Models\Links;
use App\Models\User;

class SettingsController extends Controller
{
    public function settings()
    {
        $optionsOriData = Options::where('type', 'base')->get();
        $optionsExtData = Options::where('type', 'extends')->get();
        $optionsData = [];
        $extendsData = [];
        foreach ($optionsOriData as $v) {
            $optionsData[$v->option_key] = $v->option_value;
        }
        foreach ($optionsExtData as $v) {
            $extendsData[] = [
                'option_key' => $v->option_key,
                'option_value' => $v->option_value,
                'data_type' => $v->data_type,
            ];
        }
        $linksData = Links::orderBy('sort', 'desc')->get();
        return view('backend.settings', compact('optionsData', 'linksData', 'optionsExtData'));
    }

    public function uploadImage(Request $request) {
        $path = $request->file('image')->storeAs('/', $request->get('filename'), 'public');
        $result['success'] = true;
        $result['url'] = '/'.$path;
        return json_encode($result);
    }
}