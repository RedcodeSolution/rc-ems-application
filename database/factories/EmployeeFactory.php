<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'employee_name' => $this->faker->name(),
            'employee_type' => $this->faker->randomElement(['Full-Time', 'Part-Time', 'Contract']),
            'employee_status' => 'Active',
            'contact_no' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'department_id' => \App\Models\Department::inRandomOrder()->first()->department_id ?? 1, // Fallback to 1
            'admin_id' => 1, // Default admin
            'paid_status' => 'Unpaid',
            'role' => 'employee',
            'profile_photo' => null, // Or provide a default path
        ];
    }
}
