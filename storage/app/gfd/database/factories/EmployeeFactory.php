<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Department;

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
    public function definition()
    {
        return [
            'forename' => $this->faker->firstName($gender = null),
            'surname' => $this->faker->lastName,
            'status' => ($this->faker->boolean() ? 'Active' : 'Inactive'),
            'department_id' => Department::inRandomOrder()->get()->first()->id
        ];
    }
}
