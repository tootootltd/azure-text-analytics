<?php

namespace Tootootltd\AzureTextAnalytics;

use Illuminate\Support\ServiceProvider;

class AzureTextAnalyticsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('azure-text-analytics.php'),
            ], 'config');
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'azure-text-analytics');

        // Register the main class to use with the facade
        $this->app->singleton('azure-text-analytics', function () {
            return new AzureTextAnalytics;
        });
    }
}
