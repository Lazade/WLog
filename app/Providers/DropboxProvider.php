<?php

/**
 * Created By Lazade with artisan
 * Created At 2018-10-29
 * */ 

namespace App\Providers;

use Storage;
use League\Flysystem\Filesystem;
use Illuminate\Support\ServiceProvider;
use Spatie\Dropbox\Client as DropboxClient;
use Spatie\FlysystemDropbox\DropboxAdapter;

class DropboxProvider extends ServiceProvider
{

    public function boot()
    {
        Storage::extend('dropbox', function ($app, $config) {
            $client = new DropboxClient(
                $config['access_token']
            );
            return new Filesystem(new DropboxAdapter($client));
        });
    }

    public function register()
    {
        //
    }

}