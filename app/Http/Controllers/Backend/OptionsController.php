<?php

/**
 * Created By Lazade with artisan
 * Created At 2018-10-29
 * */

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Options;
use App\Wlog\Interfaces\GeneratorInterface;

class OptionsController extends Controller implements GeneratorInterface
{   

    private $_response = [];

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'data_type' => 'required',
            'option_key' => 'required',
            'option_value' => 'required',
        ]);
        app(\App\Wlog\Generator\OptionsGenerator::class)->create($this, $request);
        if ($this->_response['status'] == 'error') {
            return redirect('avalon/settings')->withInput()->withError($this->_response['info']);
        } else {
            return redirect('avalon/settings');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $flag = true;
        $post = $request->all();
        foreach ($post as $key => $value) {
            if ($key == '_token') continue;
            $options = Options::where('option_key', $key)->update(['option_value' => $value]);
            if (!$options) {
                $flag =false;
                $this->_response = array_merge(array(
                    'option_key' => $key,
                    'info' => $options
                ));
            }
        }
        if (!$flag) {
            return redirect('avalon/settings')->withInput()->withError($this->_response);
        } 
        return redirect('avalon/settings');
    }

    public function updateExt(Request $request)
    {
        $id = $request->get('id');
        $option = Options::where('id', $id)->update(['option_value' => $request->get('option_value')]);
        if (!$option) {
            $response = [
                'state' => 500,
            ];
        } else {
            $response = [
                'state' => 200,
            ];
        }
        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (empty($request->ids)) {
            return response()->json(['status' => 'error', 'info' => 'ID is not allowed empty']);
        }
        $result = Options::notbase()->whereIn('id', $request->ids)->delete();
        return response()->json(['status' => !$result ? 'error' : 'success']);
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
        $this->_response = ['status' => 'error', 'info' => $error];
    }

    /**
     * Generate Success Use
     *
     * @param $model
     * @return \Illuminate\Http\JsonResponse
     */
    public function generatorSuccess($model)
    {
        $this->_response = ['status' => 'success', 'info' => 'success'];
    }
}
