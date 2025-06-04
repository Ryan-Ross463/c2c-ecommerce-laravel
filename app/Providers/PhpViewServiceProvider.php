<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\View\Factory;

class PhpViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Extend the view finder to handle traditional PHP views
        $this->app->afterResolving('view', function (Factory $view) {
            // Force PHP extension to be recognized as a valid view extension
            $extensions = $view->getExtensions();
            if (!array_key_exists('php', $extensions)) {
                $view->addExtension('php', 'php');
            }
        });
    }
}
