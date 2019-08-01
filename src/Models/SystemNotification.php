<?php

namespace Tantupix\Sysnotify\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SystemNotification extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'system_notify_id', 'data', 'created_at', 'updated_at'];
}
