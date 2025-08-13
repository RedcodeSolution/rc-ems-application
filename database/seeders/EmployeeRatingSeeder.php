<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\EmployeeRating;
use App\Models\User;

class EmployeeRatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First, let's create some employees with proper names if they don't exist
        $employeeNames = [
            'John Smith',
            'Sarah Johnson',
            'Michael Brown',
            'Emily Davis',
            'David Wilson',
            'Lisa Anderson',
            'Robert Taylor',
            'Jennifer Martinez',
            'William Garcia',
            'Amanda Rodriguez'
        ];

        // Create employees with proper names
        foreach ($employeeNames as $index => $name) {
            Employee::updateOrCreate(
                ['employee_id' => 'EMP' . str_pad($index + 1, 3, '0', STR_PAD_LEFT)],
                [
                    'employee_name' => $name,
                    'employee_type' => 'Full-time',
                    'employee_status' => 'Active',
                    'contact_no' => '+1-555-' . str_pad(rand(100, 999), 3, '0', STR_PAD_LEFT) . '-' . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT),
                    'email' => strtolower(str_replace(' ', '.', $name)) . '@company.com',
                    'department_id' => rand(1, 3),
                    'admin_id' => 'ADM001',
                    'paid_status' => 'Paid',
                    'role' => 'Employee'
                ]
            );
        }

        // Create users with proper names if they don't exist
        $userNames = [
            'Manager Johnson',
            'Supervisor Davis',
            'Team Lead Wilson',
            'Project Manager Anderson',
            'Department Head Taylor',
            'Senior Manager Garcia',
            'Lead Developer Rodriguez',
            'Product Manager Martinez'
        ];

        foreach ($userNames as $index => $name) {
            User::updateOrCreate(
                ['email' => strtolower(str_replace(' ', '.', $name)) . '@company.com'],
                [
                    'name' => $name,
                    'email' => strtolower(str_replace(' ', '.', $name)) . '@company.com',
                    'password' => bcrypt('password'),
                    'role' => 'admin',
                    'contact_no' => '+1-555-' . str_pad(rand(100, 999), 3, '0', STR_PAD_LEFT) . '-' . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT)
                ]
            );
        }

        // Get employees and users
        $employees = Employee::all();
        $users = User::all();

        if ($employees->isEmpty() || $users->isEmpty()) {
            $this->command->info('No employees or users found. Please create some first.');
            return;
        }

        $comments = [
            'Excellent performance in project delivery. Shows great leadership skills and technical expertise.',
            'Good team player with strong communication skills. Consistently meets deadlines.',
            'Demonstrates strong problem-solving abilities and attention to detail.',
            'Shows initiative and takes ownership of tasks. Great work ethic.',
            'Excellent technical skills and always willing to help team members.',
            'Consistently delivers high-quality work and meets project requirements.',
            'Strong analytical skills and excellent problem-solving capabilities.',
            'Great communication skills and works well in team environments.',
            'Shows strong leadership potential and excellent organizational skills.',
            'Consistently exceeds expectations and demonstrates strong work ethic.',
            'Outstanding performance in client interactions and project management.',
            'Excellent problem-solving skills and innovative approach to challenges.',
            'Great team collaboration and mentoring abilities.',
            'Consistently delivers high-quality results under pressure.',
            'Shows excellent technical knowledge and practical application.'
        ];

        $ratings = [4, 5, 4, 5, 4, 5, 4, 5, 4, 5, 3, 4, 5];

        // Clear existing ratings
        EmployeeRating::truncate();

        foreach ($employees as $index => $employee) {
            // Create 2-4 ratings per employee
            for ($i = 0; $i < rand(2, 4); $i++) {
                EmployeeRating::create([
                    'employee_id' => $employee->employee_id,
                    'rating' => $ratings[array_rand($ratings)],
                    'comment' => $comments[array_rand($comments)],
                    'rated_by' => $users->random()->id,
                ]);
            }
        }

        $this->command->info('Employee ratings seeded successfully!');
        $this->command->info('Created ' . $employees->count() . ' employees and ' . $users->count() . ' users.');
        $this->command->info('Created ' . EmployeeRating::count() . ' ratings.');
    }
}
