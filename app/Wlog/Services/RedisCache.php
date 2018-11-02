<?php

// 

namespace App\Wlog\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\Options;
use App\Models\Posts;

class RedisCache
{

    protected $expiresAt = 0;

    public function __construct()
    {
        $this->expiresAt = Carbon::now()->addMinutes(1440);
    }

    public function cacheSetting()
    {
        $options = cache('options');
        if (empty($post)) {
            $optionsList = Options::where('type', 'base')->select('option_key', 'option_value')->get()->toArray();
            $options = [];
            foreach ($optionsList as $key => $option) {
                $options[strtolower($option['option_key'])] = $option['option_value'];
            }
        }
        return $options;
    }
    
    public function updateSetting() 
    {
        cache()->forget('options');
        $optionsList = Options::where('type', 'base')->select('option_key', 'option_value')->get()->toArray();
        $options = [];
        foreach ($optionsList as $key => $option) {
            $options[strtolower($option['option_key'])] = $option['option_value'];
        }
        cache(['options' => $options], $this->expiresAt);
    }

    public function cachePost($flag)
    {
        $key = hash('sha256', $flag);
        $post = cache($key);
        if (empty($post)) {
            $post = Posts::OfType('post')->where('flag', $flag)->first();
            if (!empty($post)) {
                $post->tag;
                $post->previous = $post->previous();
                cache([$key => $post], $this->expiresAt);
            }
        }
        return $post;
    }

    public function updatePost($flag)
    {
        $key = hash('sha256', $flag);
        cache()->forget($key);
        $post = Posts::OfType('post')->where('flag', $flag)->first();
        $post->tag;
        $post->previous = $post->previous();
        cache([$key => $post], $this->expiresAt);
    }

    public function deletePost($flag)
    {
        $key = hash('sha256', $flag);
        cache()->forget($key);
    }


}