<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->unique()->numberBetween(9800000000,9899999999),
            'address1' => $this->faker->buildingNumber,
            'address2' => $this->faker->streetName,
            'city' => $this->faker->city,
            // 'postal_code' => $this->faker->postcode,
            'postal_code' => $this->faker->unique()->numberBetween(612121,692121),
            'state_id' => 12,
            'district_id' => $this->faker->numberBetween(221,234),
            'branch_id' => $this->faker->numberBetween(1,2),
            'is_approved' => 1,
            'user_id' => 1,
        ];
    }
}
