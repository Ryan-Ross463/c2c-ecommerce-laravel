<?php

namespace App\Providers;

use App\Http\SessionHelper;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;

class FallbackSessionServiceProvider extends ServiceProvider
{
    
    public function register(): void
    {
        //
    }

    
    public function boot(): void
    {
        // Try to create and initialize the sessions table if needed
        if (config('session.driver') === 'database') {
            try {
                // Initialize the sessions table
                if (!SessionHelper::initializeSessionsTable()) {
                    // If initialization fails, fall back to file driver
                    Log::warning('Falling back to file session driver due to database issues');
                    config(['session.driver' => 'file']);
                    
                    // Make sure the sessions directory exists
                    $sessionsPath = storage_path('framework/sessions');
                    if (!file_exists($sessionsPath)) {
                        mkdir($sessionsPath, 0755, true);
                    }
                }
            } catch (\Exception $e) {
                // Log any errors and fall back to file driver
                Log::error('Session table initialization error, falling back to file driver', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                
                config(['session.driver' => 'file']);
            }
        }
    }
}
