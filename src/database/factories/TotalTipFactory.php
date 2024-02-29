<?php

namespace Database\Factories;

use App\Models\TotalTip;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TotalTipFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TotalTip::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'year_month' => fake()->dateTimeBetween('-3month', 'today')->format('Y-m'), // 当月から3ヶ月以内の年月を設定
            'entity_type' => '1', // 1はユーザーを表す
            'entity_id' => User::factory(),
            'total_amount' => fake()->numberBetween(100, 10000), // Assuming total_amount ranges from 100 to 10000
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * 3ヶ月以内のデータを作成
     */
    public function within3Months()
    {
        return $this->state(fn (array $attributes) => [
            'year_month' => fake()->dateTimeBetween('-3month', 'today')->format('Y-m'),
        ]);
    }
}
