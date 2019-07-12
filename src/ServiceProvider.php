<?php

namespace Sarfraznawaz2005\ServerMonitor;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Sarfraznawaz2005\ServerMonitor\Console\CheckCommand;
use Sarfraznawaz2005\ServerMonitor\Console\StatusCommand;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        if (!$this->app->routesAreCached()) {
            require __DIR__ . '/Http/routes.php';
        }

        $this->loadViewsFrom(__DIR__ . '/Views', 'servermonitor');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/Config/config.php' => config_path('server-monitor.php'),
            ], 'servermonitor.config');

            $this->publishes([
                __DIR__ . '/Views' => base_path('resources/views/vendor/servermonitor'),
            ], 'servermonitor.views');
        }
    }

    /**
     * Register package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/Config/config.php', 'servermonitor');

        $this->app->bind('command.servermonitor.check', CheckCommand::class);
        $this->app->bind('command.servermonitor.status', StatusCommand::class);

        $this->commands([
            'command.servermonitor.check',
            'command.servermonitor.status'
        ]);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return ['ServerMonitor'];
    }
}