<?php

namespace Tantupix\Sysnotify\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\SystemNotify
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SystemNotify newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SystemNotify newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SystemNotify query()
 * @mixin \Eloquent
 */
class SystemNotify extends Model
{
    use SoftDeletes;

    protected $fillable = ['title', 'thumb', 'body', 'status'];

    protected $casts = [
        'status' => 'boolean'
    ];

    public function scopeRecent($query)
    {
        return $query->orderBy('id', 'desc');
    }

    public function notifications()
    {
        return $this->hasMany(SystemNotification::class, 'system_notify_id')
            ->where('user_id', auth()->id())
            ->withTrashed();
    }

    public function toNotification()
    {
        return [
            'class' => get_class($this),
            'title' => $this->title,
            'body' => $this->body,
            'created_at' => $this->created_at->toDateTimeString()
        ];
    }

    /**
     * 获取全部通知
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function getNotifications()
    {
        return self::select('system_notifies.*', 'system_notifications.created_at as read_at')
            ->leftJoin('system_notifications', function ($json) {
                $json->on('system_notifies.id', '=', 'system_notifications.system_notify_id')
                    ->where('system_notifications.user_id', auth()->id());
            })
            ->where('system_notifies.status', true)
            ->whereNull('system_notifications.deleted_at')
            ->orderBy('system_notifies.id', 'desc')
            ->paginate();
    }

    /**
     * 未读通知
     *
     * @return $this|\Illuminate\Database\Eloquent\Builder
     */
    public static function unreadNotifications()
    {
        return self::has('notifications', '<', 1)
            ->where('status', true)
            ->whereNull('deleted_at')
            ->recent();
    }

    /**
     * 已读通知
     *
     * @return $this|\Illuminate\Database\Eloquent\Builder
     */
    public static function readNotifications()
    {
        return self::with('notifications')
            ->whereHas('notifications', function ($query) {
                $query->whereNull('deleted_at');
            })
            ->where('status', true)
            ->whereNull('deleted_at')
            ->recent();
    }

    /**
     * 格式化系统通知
     *
     * @param $notification
     * @return mixed
     */
    public static function formatNotification(&$notification)
    {
        if (!empty($notificationDetail)) {
            $notificationDetail = ($notification->notifications)[0];
            $notification->read_at = $notificationDetail->created_at->toDateTimeString();
        } else {
            $notification->read_at = null;
        }

        unset($notification->notifications);
        return $notification;
    }

    /**
     * 批量格式化系统通知
     *
     * @param $notifications
     * @return mixed
     */
    public static function batchFormatNotification(&$notifications)
    {
        foreach ($notifications as $key => $notification) {
            $notifications[$key] = self::formatNotification($notification);
        }
        return $notifications;
    }
}
