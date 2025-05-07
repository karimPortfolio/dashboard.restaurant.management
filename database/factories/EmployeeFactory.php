<?php

namespace Database\Factories;

use App\Enums\EmployeePosition;
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
            "first_name"=> $this->faker->firstName,
            "last_name"=> $this->faker->lastName,
            "email"=> $this->faker->unique()->safeEmail,
            "phone"=> $this->faker->unique()->randomNumber(9),
            "role"=> "user",
            "salary"=> $this->faker->numberBetween(1,10),
            "status"=> EmployeeStatus::ACTIVE->value,
            "position" => EmployeePosition::WAITER,
            "cnss_number"=> $this->faker->unique()->randomNumber(9),
            "cin_number"=> "KB{$this->faker->unique()->randomNumber(6)}",
            "created_by"=> 1,
            "joining_date"=> $this->faker->dateTimeThisYear,
        ];
    }
}
