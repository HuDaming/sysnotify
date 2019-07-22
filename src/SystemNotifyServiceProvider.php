<?php

namespace Tantupix\Sysnotify;

use Illuminate\Support\ServiceProvider;

class SystemNotifyServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('sysnotify', function () {
            return new SystemNotify;
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/migrations');
    }
}
