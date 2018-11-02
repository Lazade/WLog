<?php

/**
 * Created By Lazade with artisan
 * Created At 2018-10-29
 * */ 

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tags;
use App\Models\Posts;
use App\Wlog\Interfaces\GeneratorInterface;

class TagsController extends Controller implements GeneratorInterface
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
        $listData = Tags::paginate($rows);
        $listData = $this->transform($listData);
        return view('backend.tags', compact('listData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $type = 'create';
        return view('backend.tags_create_edit', compact('type'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'tag_name' => 'required',
            'tag_flag' => 'required',
        ]);
        app(\App\Wlog\Generator\TagsGenerator::class)->create($this, $request);
        // return response()->json($this->_response);
        return redirect('avalon/tags')->withInput()->withError($this->_response);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $debug = true;
        return view('app.tags', compact('debug'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $type = 'edit';
        $data = Tags::find($id);
        if (empty($data)) {
            return redirect()->back()->withInput()->withErrors('ID: Tag ['.$id.'] does not exist');
        }
        return view('backend.tags_edit', compact('data', 'type'));
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
            'tag_name' => 'required',
            'tag_flag' => 'required',
        ]);
        $request['id'] = $id;
        app(\App\Wlog\Generator\TagsGenerator::class)->update($this, $request);
        return redirect('avalon/tags')->withInput()->withErrors($this->_response);
    }

    public function destroy($id) 
    {
        $result = Tags::where('id', $id)->delete();
        $response = [
            'state' => 200,
            'message' => 'TAG: id -'.$id.' deleted',
            'delteID' => 'delte-id-'.$id,
        ];
        return response()->json($response);
    }

    /**
     * Remove the specified multi resource from storage.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function multiDestroy(Request $request)
    {
        if (empty($request->ids)) {
            return response()->json(['status' => 'error', 'info' => 'ID is not allowed empty']);
        }
        $result = Tags::whereIn('id', $request->ids)->delete();
        return response()->json(['status' => !$result ? 'error' : 'success']);
    }

    private function transform($data)
    {
        foreach ($data as $k => $v) {
            $count = Posts::where('tag_id', $v->id)->count();
            $data[$k]['count'] = $count;
        }
        return $data;
    }

    // Delegate Actions
    /**
     * Generate Fail Use
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
     * Generate Success Use
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
