<?php

namespace Database\Factories;

use App\Models\Notification;
use App\Models\NotificationUser;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<NotificationUser>
 */
class NotificationUserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'notification_id' => Notification::factory(),
        ];
    }
}
