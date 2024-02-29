<?php

namespace Database\Factories;

use App\Models\PrivateNotification;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PrivateNotificationFactory extends Factory
{
    protected $model = PrivateNotification::class;

    public function definition()
    {
        return [
            'entity_type' => $this->faker->numberBetween(1, 3),
            'entity_id' => $this->faker->numberBetween(1, 2),
            'title' => $this->faker->text(50),
            'content' => $this->faker->paragraph,
            'is_read' => $this->faker->boolean,
            'start_at' => $start = $this->faker->dateTimeThisMonth(),
            'end_at' => $this->faker->dateTimeBetween($start, '+30 days'),
        ];
    }
}
