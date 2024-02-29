<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Notification;
use Faker\Generator as Faker;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Notification>
 */
class NotificationFactory extends Factory
{
    protected $model = Notification::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fileType = $this->faker->randomElement(['jpeg', 'png', 'gif', 'pdf']);

        return [
            'entity_type' => $this->faker->numberBetween(1, 4),
            'title' => $this->faker->text(50),
            'content' => $fileType === 'pdf' ? '' : $this->faker->paragraph, // file_type が 'pdf' の場合、content を空に設定
            'file_type' => $fileType,
            'file_name' => $this->faker->word . '.' . $this->faker->fileExtension,
            'file_size' => $this->faker->numberBetween(1024, 10240),
            'start_at' => $start = $this->faker->dateTimeThisMonth(),
            'end_at' => $this->faker->dateTimeBetween($start, '+30 days'),
        ];
    }
}
