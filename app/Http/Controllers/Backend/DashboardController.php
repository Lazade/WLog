<?php

/**
 * Created By Lazade with artisan
 * Created At 2018-10-29
 * */ 

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Posts;

class DashboardController extends Controller
{
    
    public function dashboard() 
    {
        $pcount = Posts::count();
        $tpcount = Posts::onlyTrashed()->count();
        $recent_posts = Posts::orderBy('created_at', 'desc')->limit(5)->select('id', 'title', 'created_at', 'flag')->get();
        return view('backend.dashboard')->with(compact('pcounts', 'tpcount', 'recent_posts'));
    }

    public function refreshSetting()
    {
        app(\App\Wlog\Services\RedisCache::class)->cacheSetting();
        return response()->json(['state' => 200, 'message' => 'Settings Updated']);
    }


}
