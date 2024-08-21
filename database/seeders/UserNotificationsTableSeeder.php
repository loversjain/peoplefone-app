<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;

class UserNotificationsTableSeeder extends Seeder
{
    public function run()
    {
        // Example: Fetch all users and notifications
        $users = \App\Models\User::all();
        $notifications = \App\Models\Notification::all();

        // Ensure there are users and notifications to seed
        if ($users->isEmpty() || $notifications->isEmpty()) {
            Log::info('No users or notifications found to seed.');
            return;
        }

        // Insert sample data
        foreach ($users as $user) {
            foreach ($notifications as $notification) {
                DB::table('user_notifications')->insert([
                    'user_id' => $user->id,
                    'notification_id' => $notification->id,
                    'is_read' => Arr::random([false, true]), // Randomly set as read or unread
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
