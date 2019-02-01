<?php 

/**
 * Created By Lazade with artisan
 * Created At 2019-01-06
 * */ 

namespace App\Providers;

use Google_Client;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;

class GoogleClientProvider extends ServiceProvider
{
    public function boot()
    {

    }

    public function register()
    {
        $this->app->singleton(Google_Client::class, function ($app) {
            $client = new Google_Client();
            Storage::disk('local')->put('client_secret.json', json_encode([
                'web' => config('services.google')
            ]));
            $client->setAuthConfig(Storage::disk('local')->path('client_secret.json'));
            return $client;
        });
    }

}