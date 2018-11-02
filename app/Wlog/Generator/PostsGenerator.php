<?php

/**
 * Created By Lazade with artisan
 * Created At 2018-10-29
 * */ 

namespace App\Wlog\Generator;


use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Models\Posts;
use App\Wlog\Interfaces\GeneratorInterface;

class PostsGenerator
{

    protected $_error = 'error';

    public function create(GeneratorInterface $observer, Request $request)
    {
        $posts = new Posts();
        $posts = $this->transform($posts, $request);
        if (!$posts) {
            $observer->generatorFail($this->_error);
        }
        $observer->generatorSuccess($posts);
    }

    public function update(GeneratorInterface $observer, Request $request)
    {
        if (empty($request->id)) {
            $observer->generatorFail('ID is not allowed to empty');
        }
        $posts = Posts::firstOrCreate(['flag' => $request->flag]);
        $posts = $this->transform($posts, $request);

        if (!$posts) {
            $observer->generatorFail('error');
        }

        $observer->generatorSuccess($posts);
    }

    public function transform(Posts $posts, Request $request)
    {
        $posts->title = $request->title;
        $posts->flag = strtolower(trim($request->flag));
        $posts->thumb = $request->thumb;
        $posts->tag_id = $request->tag_id;
        $posts->content = (new \Parsedown())->text($request->markdown);
        $posts->markdown = $request->markdown;
        $posts->seo_title = $request->seo_title;
        $posts->seo_description = $request->seo_description;
        $posts->seo_keyword = $request->seo_keyword;
        try {
            $posts->save();
            return $posts;
        } catch (QueryException $exception) {
            if ($exception->errorInfo[1] == 1062) {
                $this->_error = 'Fail: [flag] has existed.';
            }
            return null;
        }
    }

}