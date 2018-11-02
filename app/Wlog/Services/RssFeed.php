<?php 

namespace App\Wlog\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Suin\RSSWriter\Channel;
use Suin\RSSWriter\Feed;
use Suin\RSSWriter\Item;
use App\Models\Posts;
use App\Models\Options;

class RssFeed
{
    
    public function getRSS()
    {
        $expiresAt = Carbon::now()->addMinutes(120);
        if (Cache::has('rss-feed')) {
            return Cache::get('rss-feed');
        }
        $rss = $this->buildRssData();
        Cache::add('rss-feed', $rss, $expiresAt);
        return $rss;
    }

    public function updateRSS()
    {
        $expiresAt = Carbon::now()->addMinutes(120);
        if (Cache::has('rss-feed')) {
            Cache::forget('rss-feed');
        }
        $rss = $this->buildRssData();
        Cache::add('rss-feed', $rss, $expiresAt);
        return true;
    }

    public function buildRssData()
    {
        $config = array();
        $configs = Options::where('type', 'base')->select('option_key', 'option_value')->get()->toArray();
        foreach ($configs as $key => $cfg) {
            $config[$cfg['option_key']] = $cfg['option_value'];
        }
        $now = Carbon::now();
        $feed = new Feed();
        $channel = new Channel();
        
        $channel
            ->title($config['web_name'])
            ->description($config['content'])
            ->url(url('/'))->language('zh-CN')
            ->copyright('Copyright (c) '.$config['web_name'])
            ->lastBuildDate($now->timestamp)
            ->appendTo($feed);

        $posts = Posts::OfType('post')->where('state', 1)->orderBy('id', 'desc')->take(15)->get();

        foreach ($posts as $post) {
            $item = new Item();
            $item
                ->title(self::utf8_for_xml($post->title))
                ->description(self::utf8_for_xml('<![CDATA['.$post->seo_description.']]>'))
                ->url(url('/post/'.$post->flag))
                ->pubDate($post->created_at->timestamp)
                ->guid(url('/post/'.$post->flag), true)
                ->appendTo($channel);
        }

        $feed = (string)$feed;

        $search = array('<rss xmlns:content="http://purl.org/rss/1.0/modules/content/" version="2.0">', '<channel>');
        $replace = array('<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">', '<channel>' . "\n" .'<atom:link href="'. url('/feed') .'" rel="self" type="application/rss+xml" />');
        $feed = str_replace($search, $replace, $feed);

        return $feed;
    }

    protected static function utf8_for_xml($string)
    {
        return preg_replace ('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', ' ', $string);
    }

}