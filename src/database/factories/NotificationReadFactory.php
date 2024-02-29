<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\NotificationRead>
 */
class NotificationReadFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'notification_id' => $this->faker->numberBetween(1, 10), // 既存のnotification_idを想定
            'entity_type' => $this->faker->randomElement([1, 2, 3]), // 1, 2, 3 のいずれかをランダムに選択
            'entity_id' => $this->faker->numberBetween(1, 2), // 適当なエンティティID
        ];
    }
}
