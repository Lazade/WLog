<?php

if (!function_exists("getGA")) {
    function getGA()
    {
        $GA = \App\Models\Options::where('option_key', 'ga')->first()->option_value;
        echo $GA;
    }
}

if (!function_exists('bloginfo')) {
/** 
 * get options
 * @param string $key -> option_key
 * @return string
 * 
 * */ 
    function bloginfo($key) 
    {
        $options = app(\App\Wlog\Services\RedisCache::class)->cacheSetting();
        return isset($options[$key]) ? $options[$key] : '';
    }
}

if (!function_exists('bloglinks')) {
/** 
 * get and generate links into html format
 * 
 * */ 
    function bloglinks()
    {
        $links =  \App\Models\Links::orderBy('sort', 'asc')->get();
        foreach ($links as $link ) {
            echo '<li><a href="'.$link->url.'" target="_blank" rel="noopener noreferrer" class="link"><i class="'.$link->logo.'"></i></a></li>';
        }
    }
}