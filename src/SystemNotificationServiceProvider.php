<?php

namespace Tantupix\Sysnotify;

use Illuminate\Support\ServiceProvider;

class SystemNotificationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('sysnotification', function ($app) {
            return new SystemNotification;
        });
    }
}
