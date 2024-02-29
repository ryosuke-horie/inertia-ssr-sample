<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Staff>
 */
class StaffFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // 一般スタッフのみ考慮する
        return [
            'email' => $this->faker->unique()->safeEmail,
            'password' => bcrypt('password123'), // 本番環境ではより強固なパスワード生成を使用
            'business_id' => $this->faker->randomElement([1, 2]),
            'real_name' => $this->faker->name,
            'staff_name' => $this->faker->name,
            'comment' => $this->faker->sentence,
            'is_deleted' => $this->faker->boolean,
            'points' => $this->faker->numberBetween(0, 1000),
            'ai_count' => $this->faker->numberBetween(0, 3),
            'remember_token' => null,
            'deleted_at' => null
        ];
    }
}
