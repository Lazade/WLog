<?php

/**
 * Created By Lazade with artisan
 * Created At 2018-10-29
 * */

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Posts;

class TrashController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $rows = intval($request->rows) > 0 ? $request->rows : 20;
        $listData = Posts::onlyTrashed()->OfTag($request->tag_id)->OfTitle($request->q)->paginate($rows);
        $listData = $this->transform($listData);
        return view('backend.trash', compact('listData'));
    }

    public function restore($id)
    {
        $result = Posts::withTrashed()->where('id', $id)->restore();
        $response = [
            'state' => $result == true ? 200 : 400,
            'message' => 'POST: id -'.$id.' restore '.($result == true ? 'success' : 'failure'),
            'delteID' => 'delte-id-'.$id,
        ];
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
        $result = Posts::where('id', $id)->forceDelete();
        $response = [
            'state' => 200, 
            'message' => 'POST: id -'.$id.' Deleted Forever.',
            'delteID' => 'delte-id-'.$id,
        ];
        return response()->json($response);
    }

    public function multiDestroy(Request $request)
    {
        $this->validate($request, [
            'ids' => 'required'
        ]);
        $result = Posts::whereIn('id', $request->ids)->forceDelete();
    }

    private function transform($data)
    {
        foreach ($data as $key => $item) {
            $item->tag;
        }
        return $data;
    }

}
