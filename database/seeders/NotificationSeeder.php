<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    public function run()
    {
        \App\Models\Notification::factory(100)->create();
    }
}
