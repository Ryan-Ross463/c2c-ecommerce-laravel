<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        require_once app_path('Helpers/ViewHelpers.php');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        } else {
            URL::forceScheme('http');
        }
        
        URL::forceRootUrl(config('app.url'));
        
        $customPath = env('APP_SUBPATH');
        if ($customPath && strpos(config('app.url'), $customPath) !== false) {
            $this->app['request']->server->set(
                'SCRIPT_NAME',
                $customPath . '/index.php'
            );
            
            $this->app['url']->setRootControllerNamespace('App\\Http\\Controllers');
            $this->app['url']->setKeyResolver(function() {
                return env('APP_KEY');
            });
        }
    }
}
