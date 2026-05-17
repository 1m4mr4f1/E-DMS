<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class NotificationService
{
    /**
     * Retrieve a notification by id that belongs to a specific user.
     */
    public function getForUser(int $id, int $userId)
    {
        return DB::table('notifications')
            ->where('id', $id)
            ->where('user_id', $userId)
            ->first();
    }

    /**
     * Mark a single notification as read for the given user.
     * Returns true if update occurred.
     */
    public function markAsRead(int $id, int $userId): bool
    {
        $updated = DB::table('notifications')
            ->where('id', $id)
            ->where('user_id', $userId)
            ->update([
                'is_read' => true,
                'updated_at' => now(),
            ]);

        return (bool) $updated;
    }

    /**
     * Mark all notifications as read for the given user.
     */
    public function markAllAsRead(int $userId): int
    {
        return DB::table('notifications')
            ->where('user_id', $userId)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'updated_at' => now(),
            ]);
    }
}
