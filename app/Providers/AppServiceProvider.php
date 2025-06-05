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
        
        // Only apply custom subpath logic in local development, not on Railway
        $isRailway = env('RAILWAY_ENVIRONMENT') || 
                     strpos(config('app.url'), 'railway.app') !== false;
        
        $customPath = env('APP_SUBPATH');
        if (!$isRailway && $customPath && strpos(config('app.url'), $customPath) !== false) {
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
