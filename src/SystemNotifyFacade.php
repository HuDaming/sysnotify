<?php

namespace Tantupix\Sysnotify;

use Illuminate\Support\Facades\Facade;

class SystemNotifyFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'sysnotify';
    }
}
