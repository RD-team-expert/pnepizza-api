<?php

namespace App\Services;

use App\Models\User;
use App\Notifications\AdminNotification;

class NotificationService
{
    public function notifyAdmin($message)
    {
        $adminUsers = User::role('Admin')->get();
        foreach ($adminUsers as $admin) {
            $admin->notify(new AdminNotification($message));
        }

    }
}
