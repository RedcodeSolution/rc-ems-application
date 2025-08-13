<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use App\Models\Employee;
use App\Models\Leave;
use Carbon\Carbon;

class AdminLeaveSeeder extends Seeder
{
    public function run()
    {
        // Create sample admin records
        $admins = [
            [
                'admin_id' => 'ADM001',
                'admin_name' => 'John Smith'
            ],
            [
                'admin_id' => 'ADM002',
                'admin_name' => 'Sarah Johnson'
            ],
            [
                'admin_id' => 'ADM003',
                'admin_name' => 'Michael Brown'
            ],
            [
                'admin_id' => 'ADM004',
                'admin_name' => 'Emily Davis'
            ],
            [
                'admin_id' => 'ADM005',
                'admin_name' => 'David Wilson'
            ]
        ];

        foreach ($admins as $adminData) {
            Admin::updateOrCreate(
                ['admin_id' => $adminData['admin_id']],
                $adminData
            );
        }

        // Create sample employees who are admins
        $adminEmployees = [
            [
                'employee_id' => 'EMP001',
                'employee_name' => 'John Smith',
                'email' => 'john.smith@company.com',
                'contact_no' => '+1234567890',
                'admin_id' => 'ADM001',
                'employee_type' => 'admin',
                'employee_status' => 'active'
            ],
            [
                'employee_id' => 'EMP002',
                'employee_name' => 'Sarah Johnson',
                'email' => 'sarah.johnson@company.com',
                'contact_no' => '+1234567891',
                'admin_id' => 'ADM002',
                'employee_type' => 'admin',
                'employee_status' => 'active'
            ],
            [
                'employee_id' => 'EMP003',
                'employee_name' => 'Michael Brown',
                'email' => 'michael.brown@company.com',
                'contact_no' => '+1234567892',
                'admin_id' => 'ADM003',
                'employee_type' => 'admin',
                'employee_status' => 'active'
            ],
            [
                'employee_id' => 'EMP004',
                'employee_name' => 'Emily Davis',
                'email' => 'emily.davis@company.com',
                'contact_no' => '+1234567893',
                'admin_id' => 'ADM004',
                'employee_type' => 'admin',
                'employee_status' => 'active'
            ],
            [
                'employee_id' => 'EMP005',
                'employee_name' => 'David Wilson',
                'email' => 'david.wilson@company.com',
                'contact_no' => '+1234567894',
                'admin_id' => 'ADM005',
                'employee_type' => 'admin',
                'employee_status' => 'active'
            ]
        ];

        foreach ($adminEmployees as $employeeData) {
            Employee::updateOrCreate(
                ['employee_id' => $employeeData['employee_id']],
                $employeeData
            );
        }

        // Sample leave types and reasons
        $leaveTypes = ['sick', 'casual', 'annual'];
        $reasons = [
            'Medical appointment and recovery',
            'Family vacation',
            'Personal emergency',
            'Mental health day',
            'Wedding ceremony',
            'Conference attendance',
            'Home renovation',
            'Child care responsibilities',
            'Religious observance',
            'Professional development'
        ];

        // Create sample leave requests for admin employees
        $adminEmployeeIds = ['EMP001', 'EMP002', 'EMP003', 'EMP004', 'EMP005'];
        
        foreach ($adminEmployeeIds as $employeeId) {
            // Create 2-4 leave requests per admin
            $numLeaves = rand(2, 4);
            
            for ($i = 0; $i < $numLeaves; $i++) {
                $startDate = Carbon::now()->addDays(rand(1, 30));
                $duration = rand(1, 5);
                $endDate = $startDate->copy()->addDays($duration - 1);
                
                $status = ['pending', 'approved', 'rejected'][rand(0, 2)];
                $leaveType = $leaveTypes[array_rand($leaveTypes)];
                $reason = $reasons[array_rand($reasons)];
                
                $leaveData = [
                    'leave_id' => 'LEV-' . date('Y') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
                    'employee_id' => $employeeId,
                    'leave_type' => $leaveType,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'duration' => $duration,
                    'reason' => $reason,
                    'contact_number' => '+123456789' . rand(0, 9),
                    'status' => $status,
                    'applied_date' => Carbon::now()->subDays(rand(1, 10)),
                ];

                // Add approval/rejection data based on status
                if ($status === 'approved') {
                    $leaveData['approved_by'] = 'ADM001';
                    $leaveData['approved_date'] = Carbon::now()->subDays(rand(1, 5));
                    $leaveData['comments'] = 'Approved - Valid request';
                } elseif ($status === 'rejected') {
                    $leaveData['rejected_by'] = 'ADM001';
                    $leaveData['rejected_date'] = Carbon::now()->subDays(rand(1, 5));
                    $leaveData['rejection_reason'] = 'Insufficient notice period';
                    $leaveData['comments'] = 'Rejected - Please provide more advance notice';
                }

                Leave::updateOrCreate(
                    ['leave_id' => $leaveData['leave_id']],
                    $leaveData
                );
            }
        }

        $this->command->info('Admin leave data seeded successfully!');
    }
} 