<?php

namespace Mriembau\LaravelHasOwner;

use Illuminate\Support\ServiceProvider;

class LaravelHasOwnerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/has-owner.php', 'has-owner');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
// Load config file
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/has-owner.php' => config_path('has-owner.php'),
            ], 'config');
        }
    }
}
