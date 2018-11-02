<?php

namespace App\Http\Controllers\App;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use App\Wlog\Interfaces\GeneratorInterface;
use App\Wlog\Services\SiteMap;
use App\Wlog\Services\RssFeed;
use App\Models\Links;
use App\Models\Tags;
use App\Models\Posts;
use Carbon\Carbon;

class HomeController extends Controller implements GeneratorInterface
{
    
    protected $response;

    public function __construct()
    {
    }

    public function home($page = 1, Request $request)
    {
        $request->merge(['page' => $page]);
        $current = __FUNCTION__;
        $posts = Posts::where('state', '1')->orderBy('id', 'desc')->paginate(5);
        return view('app.home')->with(compact('posts', 'current', 'paginate'));
    }

    public function posts($flag)
    {
        $post = app(\App\Wlog\Services\RedisCache::class)->cachePost($flag);
        !empty($post) ?: abort(404, '');
        Posts::increment('views', 1);
        return view('app.post')->with(compact('post'));
    }

    public function tags($flag)
    {
        $current = 'Tag';
        $tag = Tags::where('tag_flag', $flag)->first();
        $posts = $tag->posts;
        return view('app.tag')->with(compact('posts', 'current', 'tag'));
    }

    public function archive($year = '', Posts $posts)
    {
        $current = __FUNCTION__;
        $hasLastYear = true;
        $hasNextYear = true;
        if ($year == '') {
            $year = Carbon::now()->format('Y');
            $hasNextYear = false;
        } 
        $post = $posts->groupByYear($year);
        $lastYearPost = $posts->groupByYear($year-1);
        if (count($lastYearPost) == 0) {
            $hasLastYear = false;
        }
        $data = [
            'lastYear' => $hasLastYear ? $year - 1 : '',
            'nextYear' => $hasNextYear ? $Year + 1 : '',
            'posts' => $post,
            'thisYear' => $year
        ];
        return view('app.archive')->with(compact('data', 'current'));
    }

    public function about()
    {
        return;        
    }

    public function subsribe()
    {
        return;
    }

    public function feed(RssFeed $feed)
    {
        $rss = $feed->getRSS();
        return response($rss)->header('Content-type', 'text/xml;charset=UTF-8');
    }

    public function siteMap(SiteMap $siteMap)
    {
        $map = $siteMap->getSiteMap();
        return response($map)->header('Content-type', 'text/xml;charset=UTF-8');
    }

    private function getLinks()
    {
        $links = Links::orderBy('sort', 'desc')->get();
        return $links;
    }

    /**
     * observer method
     * @param $error
     */
    public function generatorFail($error)
    {
        $this->response = ['status' => 'error', 'id' => '', 'info' => $error];
    }

    /**
     * observer method
     * @param $model
     */
    public function generatorSuccess($model)
    {
        $this->response = ['status' => 'success', 'id' => $model->id, 'info' => '评论发布成功'];
    }

}
