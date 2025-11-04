<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Models\Admin;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\Meeting;
use App\Models\Project;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SuperAdminOverviewController
{

    public function index()
    {
        $currentYear = now()->year;

        // --- Dashboard summary ---
        $dashboardStats = [
            'total_admins' => Admin::count(),
            'total_employees' => Employee::count(),
            'total_departments' => Department::count(),
            'active_projects' => Project::count(),
            'pending_leaves' => Leave::where('status', 'Pending')->count(),
            'recent_registrations' => Employee::where('created_at', '>=', now()->subDays(30))->count(),
        ];

        // --- Monthly Registrations ---
        $monthlyRegistrations = Employee::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as count')
        )
            ->whereYear('created_at', $currentYear)
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // --- Monthly Leave Requests ---
        $monthlyLeaves = Leave::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as count')
        )
            ->whereYear('created_at', $currentYear)
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // --- Monthly Projects (show ALL created projects per month) ---
        $monthlyProjects = Project::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as count')
        )
            ->whereYear('created_at', $currentYear)
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // Fill 12 months (Jan–Dec) with 0 if empty
        $monthly_registrations = [];
        $leave_requests = [];
        $project_completion = [];

        for ($i = 1; $i <= 12; $i++) {
            $monthly_registrations[] = $monthlyRegistrations[$i] ?? 0;
            $leave_requests[] = $monthlyLeaves[$i] ?? 0;
            $project_completion[] = $monthlyProjects[$i] ?? 0;
        }

        // Combine for chart
        $chartData = [
            'monthly_registrations' => $monthly_registrations,
            'leave_requests' => $leave_requests,
            'project_completion' => $project_completion,
        ];

        // --- Recent Activities (last 10) ---
        $recentProjects = Project::orderBy('updated_at', 'desc')->take(5)->get(['project_name', 'status', 'updated_at']);
        $recentLeaves = Leave::orderBy('created_at', 'desc')->take(5)->get(['leave_type', 'status', 'created_at']);
        $recentEmployees = Employee::orderBy('created_at', 'desc')->take(5)->get(['employee_name', 'created_at']);

        $recentActivities = collect();

        foreach ($recentProjects as $project) {
            $recentActivities->push([
                'type' => 'project',
                'icon' => 'fas fa-tasks',
                'action' => 'Project Updated',
                'details' => $project->project_name . ' (' . $project->status . ')',
                'timestamp' => $project->updated_at,
            ]);
        }

        foreach ($recentLeaves as $leave) {
            $recentActivities->push([
                'type' => 'leave',
                'icon' => 'fas fa-calendar-check',
                'action' => 'Leave Request',
                'details' => $leave->leave_type . ' (' . $leave->status . ')',
                'timestamp' => $leave->created_at,
            ]);
        }

        foreach ($recentEmployees as $emp) {
            $recentActivities->push([
                'type' => 'employee',
                'icon' => 'fas fa-user-plus',
                'action' => 'New Employee',
                'details' => $emp->name,
                'timestamp' => $emp->created_at,
            ]);
        }

        $todayMeetings = Meeting::getTodayMeetings();



        $recentActivities = $recentActivities->sortByDesc('timestamp');

        // Return data to view
        return view('super_admin.dashboard', compact(
            'dashboardStats',
            'chartData',
            'recentActivities',
            'todayMeetings'
        ));
    }




}
