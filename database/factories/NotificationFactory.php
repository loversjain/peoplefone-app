<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
{
    protected $model = \App\Models\Notification::class;

    public function definition()
    {
        // Determine the destination and set user_id accordingly
        $destination = $this->faker->randomElement(['user', 'all']);
        $userId = $destination === 'user' ? $this->faker->randomElement(User::pluck('id')) : null;

        return [
            'type' => $this->faker->randomElement(['marketing', 'invoices', 'system']),
            'short_text' => $this->faker->sentence,
            'expiration' => $this->faker->optional()->dateTimeBetween('now', '+1 month'),
            'destination' => $destination,
            'user_id' => $userId,
        ];
    }
}
