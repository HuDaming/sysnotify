<?php

namespace Tantupix\Sysnotify;

use Tantupix\Sysnotify\Models\SystemNotify as Notify;
use App\Contracts\SystemNotifyInterface;

class SystemNotify implements SystemNotifyInterface
{
    public function getModel()
    {
        return new Notify();
    }
}
