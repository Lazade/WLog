<?php

/**
 * Created By Lazade with artisan
 * Created At 2018-10-29
 * */

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Links;
use App\Wlog\Interfaces\GeneratorInterface;

class LinksController extends Controller implements GeneratorInterface
{

    protected $_response = 'error';

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'logo' => 'required',
            'url' => 'required',
        ]);
        $request->sort = 1;
        app(\App\Wlog\Generator\LinksGenerator::class)->create($this, $request);
        return redirect('avalon/settings');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'logo' => 'required',
            'url' => 'required',
        ]);
        app(\App\Wlog\Generator\LinksGenerator::class)->update($this, $request);
        return response()->json($this->_response);
    }

    public function destroy($id)
    {
        $result = Links::where('id', $id)->delete();
        return response()->json(['status' => !$result ? 'error' : 'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function multiDestroy($id)
    {   
        if (empty($request->ids)) {
            return response()->json(['status' => 'error', 'info' => 'ID is not allowed empty']);
        }
        $result = Links::whereIn('id', $request->ids)->delete();
        return response()->json(['status' => !$result ? 'error' : 'success']);
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
        $this->_response = ['status' => 'error', 'info' => $error];
    }

    /**
     * Genrate Success Use
     *
     * @param $model
     * @return \Illuminate\Http\JsonResponse
     */
    public function generatorSuccess($model)
    {
        $this->_response = ['status' => 'success', 'info' => $model->sort];
    }
}
