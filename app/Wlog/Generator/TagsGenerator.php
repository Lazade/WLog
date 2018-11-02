<?php 

/**
 * Created By Lazade with artisan
 * Created At 2018-10-29
 * */ 

namespace App\Wlog\Generator;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Models\Tags;
use App\Wlog\Interfaces\GeneratorInterface;

class TagsGenerator 
{
    public function create(GeneratorInterface $observer, Request $request)
    {
        $tags = new Tags();
        $tags = $this->transform($tags, $request);

        if (!$tags) {
            $observer->generatorFail('error');
        }

        $observer->generatorSuccess($tags);
    }

    public function update(GeneratorInterface $observer, Request $request)
    {
        if (empty($request->id)) {
            $observer->generatorFail('ID is not allowed empty');
        }
        $tags = Tags::where('id', $request->id)->first();
        if (empty($tags)) {
            $observer->generatorFail('error');
        }

        $tags = $this->transform($tags, $request);
        if (!$tags) {
            $observer->generatorFail('error');
        }

        $observer->generatorSuccess($tags);
    }

    public function transform(Tags $tags, Request $request)
    {
        $tags->tag_name = trim($request->tag_name);
        $tags->tag_flag = strtolower(trim($request->tag_flag));
        try {
            $tags->save();
            return $tags;
        } catch (QueryException $exception) {
            if ($exception->errorInfo[1] == 1062) {
                $this->_error = 'Fail: [tag_flag] has existed.';
            }
            return null;
        }
    }

}