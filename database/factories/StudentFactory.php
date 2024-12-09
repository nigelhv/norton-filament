<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
          'first_name' => $this->faker->firstName(),
          'surname' => $this->faker->lastName(),
          'on_roll' => '1',
          'location_id' => $this->faker->randomElement(['1', '2']),
        ];
    }
}
