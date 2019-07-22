<?php

namespace Tantupix\Sysnotify;

use Illuminate\Support\Facades\Facade;

class SystemNotificationFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'sysnotification';
    }
}
