<?php 

/**
 * Created By Lazade with artisan
 * Created At 2018-10-29
 * */

namespace App\Wlog\Generator;

use Illuminate\Http\Request;
use App\Models\Links;
use App\Wlog\Interfaces\GeneratorInterface;

class LinksGenerator
{
    public function create(GeneratorInterface $observer, Request $request)
    {
        $links = new Links();
        $links = $this->transform($links, $request);

        if (!$links) {
            $observer->generatorFail('error');
        }

        $observer->generatorSuccess($links);
    }

    public function update(GeneratorInterface $observer, Request $request)
    {
        if (empty($request->id)) {
            $observer->generatorFail('ID is not allowed empty');
        }
        $links = Links::where('id', $request->id)->first();
        $links = $this->transform($links, $request);

        if (!$links) {
            $observer->generatorFail('error');
        }

        $observer->generatorSuccess($links);
    }

    public function transform(Links $links, Request $request) 
    {
        $links->name = $request->name;
        $links->logo = $request->logo;
        $links->url = strtolower($request->url);
        $links->sort = intval($request->sort);
        try {
            $links->save();
            return $links;
        } catch (QueryException $exception) {
            if ($exception->errorInfo[1] == 1062) {
                $this->_error = 'Fail: [name] has existed.';
            }
            return null;
        }
    }
}