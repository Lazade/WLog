<?php

/**
 * Created By Lazade with artisan
 * Created At 2018-10-29
 * */ 

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Posts;
use App\Models\Tags;
use App\Wlog\Interfaces\GeneratorInterface;

class PostsController extends Controller implements GeneratorInterface
{
    
    private $_response = '';
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $rows = intval($request->rows) > 0 ? $request->rows : 20;
        $listData = Posts::OfTag($request->category_id)->OfTitle($request->q)->orderBy('id','desc')->paginate($rows);
        $listData = $this->transform($listData);
        return view('backend.posts', compact('listData'));
    }

    /**
     * Show the form for creating a new resource.
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $type = 'create';
        $title = $request['title'];
        if (!empty(Posts::where('title', $title)->first())) {
            return redirect()->back()->withInput()->withErrors('The [Title] has existed');
        }
        $tagsList = Tags::all();
        return view('backend.post_create_and_edit', compact('title', 'tagsList', 'type'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $this->validate($request, [
            'title' => 'required',
            'flag' => 'required',
            'tag_id' => 'required',
            'markdown' => 'required'
        ]);
        app(\App\Wlog\Generator\PostsGenerator::class)->create($this, $request);
        return redirect('avalon/posts')->withInput()->withErrors($this->_response);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $type = 'edit';
        $post = Posts::find($id);
        if (empty($post)) {
            return redirect()->back()->withInput()->withErrors('ID: Post ['.$id.'] does not exist');
        }
        $tagsList = Tags::all();
        return view('backend.post_create_and_edit', compact('title', 'tagsList', 'type', 'post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required',
            'flag' => 'required',
            'tag_id' => 'required',
            'markdown' => 'required'
        ]);
        app(\App\Wlog\Generator\PostsGenerator::class)->update($this, $request);
        return redirect('avalon/posts')->withInput()->withErrors($this->_response);
    }

    public function destroy($id) {
        $flag = Posts::where('id', $id)->first()->flag;
        app(\App\Wlog\Services\RssFeed::class)->updateRSS();
        app(\App\Wlog\Services\SiteMap::class)->updateSiteMap();
        app(\App\Wlog\Services\RedisCache::class)->deletePost($flag);
        $result = Posts::where('id', $id)->delete();
        $status = !$result ? 'error' : 'success';
        $response = [
            'state' => 200,
            'message' => 'POST: id -'.$id.' deleted.',
            'delteID' => 'delte-id-'.$id,
        ];
        return response()->json($response);
    }

    public function changeState($id) {
        $state = Posts::where('id', $id)->first()->state;
        $state = !$state;
        $post = Posts::where('id', $id)->update(['state' => $state]);
        app(\App\Wlog\Services\RssFeed::class)->updateRSS();
        app(\App\Wlog\Services\SiteMap::class)->updateSiteMap();
        $response = [
            'state' => 200,
            'message' => 'POST: id -'.$id.' state changed.',
            'status' => $state == 1 ? true : false,
        ];
        return response()->json($response);
    }

    public function publish($id)
    {
        $flag = Posts::where('id', $id)->first()->flag;
        app(\App\Wlog\Services\RedisCache::class)->updatePost($flag);
        $response = [
            'state' => 200,
            'message' => 'POST: id -'.$id.' published.',
        ];
        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function multiDestroy(Request $request)
    {
        $this->validate($request, [
            'ids' => 'required'
        ]);
        $result = Posts::whereIn('id', $request->ids)->delete();
        $status = !$result ? 'error' : 'success';
        return redirect('avalon/posts')->withInput()->withErrors('delete ['.$id.'] :'.$status);
    }

    /**
     * data transform format
     *
     * @param $data
     * @return mixed
     */
    private function transform($data)
    {
        foreach ($data as $key => $item) {
            $data[$key]['tag'] = $item->tag;
        }
        return $data;
    }


    // Delegate Action
    /**
     * Genrate Fail Use
     *
     * @param $error
     * @return \Illuminate\Http\JsonResponse
     */
    public function generatorFail($error)
    {
        // $this->_response = ['status' => 'error', 'info' => $error];
        $this->_response = $error;
    }

    /**
     * Genrate Success Use
     *
     * @param $model
     * @return \Illuminate\Http\JsonResponse
     */
    public function generatorSuccess($model)
    {
        // $this->_response = ['status' => 'success', 'info' => 'success'];
        $this->_response = 'success';
    }


}
