<?php

namespace Tantupix\Sysnotify;

use Tantupix\Sysnotify\Models\SystemNotify;
use Tantupix\Sysnotify\Models\SystemNotification as Notification;
use App\Contracts\SystemNotificationInterface;
use Carbon\Carbon;

class SystemNotification implements SystemNotificationInterface
{
    public function show($id)
    {
        $attributes = ['user_id' => auth()->id(), 'system_notify_id' => $id];
        if (!Notification::where($attributes)->exists()) {
            Notification::create($attributes);
        }
        return SystemNotify::findOrFail($id);
    }

    public function notifications($type = 'all')
    {
        $pageSize = request()->input('page_size');

        switch ($type) {
            case 'read':
                $notifications = SystemNotify::readNotifications()->paginate($pageSize);
                break;
            case 'unread':
                $notifications = SystemNotify::unreadNotifications()->paginate($pageSize);
                break;
            default:
                $notifications = SystemNotify::getNotifications();
                break;
        }

        if ($notifications && in_array($type, ['read', 'unread'])) {
            SystemNotify::batchFormatNotification($notifications);
        }

        return $notifications;
    }

    public function unreadCount()
    {
        return SystemNotify::unreadNotifications()->count();
    }

    public function markAsRead()
    {
        // 获取所有未读系统通知
        $unreadNotifications = SystemNotify::unreadNotifications()->get();

        if ($unreadNotifications) {
            $attributes = [];
            $datetime = Carbon::now()->toDateTimeString();
            foreach ($unreadNotifications->pluck('id') as $id) {
                $attributes[] = [
                    'user_id' => auth()->id(),
                    'system_notify_id' => $id,
                    'created_at' => $datetime,
                    'updated_at' => $datetime,
                ];
            }

            Notification::insert($attributes);
        }
    }

    public function destroy()
    {
        return Notification::where('user_id', auth()->id())->delete();
    }
}
