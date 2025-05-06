<?php

namespace Database\Factories;

use App\Enums\CampaignStatus;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Campaign>
 */
class CampaignFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('-1 month', '+1 month');
        $endDate = $this->faker->dateTimeBetween($startDate, '+3 months');

        return [
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraphs(3, true),
            'target_amount' => $this->faker->randomFloat(2, 1000, 100000),
            'initial_amount' => $this->faker->randomFloat(2, 0, 50000),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => $this->faker->randomElement(CampaignStatus::values()),
            'type' => $this->faker->randomElement(['admin', 'employee']),
            'featured_image' => $this->faker->imageUrl(800, 600, 'business'),
            'category_id' => Category::factory(), // 👈 generate or use existing
            'created_by' => User::factory(),
            'updated_by' => User::factory(),
        ];
    }

    public function mission(): static
    {
        return $this->state(fn (array $attributes) => ['type' => 'admin']);
    }

    public function employeeCampaign(): static
    {
        return $this->state(fn (array $attributes) => ['type' => 'employee']);
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => ['status' => 'active']);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'initial_amount' => $attributes['target_amount'],
        ]);
    }

    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'featured_image' => $this->faker->imageUrl(800, 600, 'business', true, 'featured'),
        ]);
    }
}
