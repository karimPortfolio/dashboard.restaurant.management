<?php

namespace Database\Factories;

use App\Enums\EmployeeStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "full_name"=> $this->faker->firstName,
            "email"=> $this->faker->unique()->safeEmail,
            "phone"=> $this->faker->unique()->randomNumber(9),
            "role"=> "user",
            "salary"=> $this->faker->numberBetween(1,10),
            "status"=> EmployeeStatus::ACTIVE->value,
            "created_by"=> 1,
        ];
    }
}
