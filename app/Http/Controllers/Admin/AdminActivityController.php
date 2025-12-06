<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminActivityController extends Controller
{
    public function index(Request $request)
    {
        try {
            if (class_exists(\App\Models\Activity::class)) {
                $items = \App\Models\Activity::query()
                    ->with(['user'])
                    ->orderBy('created_at', 'desc')
                    ->take(10)
                    ->get(['id', 'title', 'description', 'icon', 'bg', 'status', 'created_at', 'user_id'])
                    ->map(function ($it) {
                        return [
                            'id' => $it->id,
                            'title' => $it->title,
                            'description' => $it->description,
                            'icon' => $it->icon ?? 'bell',
                            'bg' => $it->bg ?? null,
                            'status' => $it->status ?? 'info',
                            'user' => $it->user->name ?? null,
                            'created_at' => optional($it->created_at)->toIso8601String(),
                        ];
                    })
                    ->toArray();
                return response()->json($items);
            }
        } catch (\Throwable $e) {
        }

        $now = Carbon::now();
        $sample = [
            [
                'id' => 1,
                'icon' => 'user-plus',
                'title' => 'New Employee Onboarded',
                'description' => 'John Doe joined Development Team',
                'bg' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                'status' => 'success',
                'user' => 'Jane Doe',
                'created_at' => $now->subHours(2)->toIso8601String(),
            ],
            [
                'id' => 2,
                'icon' => 'check-circle',
                'title' => 'Project Milestone Reached',
                'description' => 'Website Redesign - 75% completed',
                'bg' => 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
                'status' => 'completed',
                'user' => 'John Smith',
                'created_at' => $now->subHours(4)->toIso8601String(),
            ],
            [
                'id' => 3,
                'icon' => 'calendar',
                'title' => 'Leave Request Submitted',
                'description' => 'Sarah Wilson - Medical Leave (3 days)',
                'bg' => 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',
                'status' => 'pending',
                'user' => 'Sarah Wilson',
                'created_at' => $now->subHours(6)->toIso8601String(),
            ],
        ];

        return response()->json($sample);
    }

    /**
     * Return dashboard metrics, meetings and recent activities as JSON.
     */
    public function dashboardData(Request $request)
    {
        try {
            $revenue = null;
            $efficiency = null;
            $departmentsCount = null;
            $adminsCount = null;
            $notificationsCount = null;
            $notificationsUnread = null;
            $notificationsTotal = null;
            $newJoinings = null;
            $dailyJoinings = null;
            $pendingLeaves = null;
            $totalEmployees = null;

            if (class_exists(\App\Models\Project::class)) {
            }

            $departmentsCount = \App\Models\Department::count();
            $adminsCount = \App\Models\User::where('role', 'admin')->count();
            $totalEmployees = \App\Models\Employee::count();
            
            $notificationsUnread = \App\Models\Notification::where('target', 'admin')->where('is_read', false)->count();
            $notificationsTotal = \App\Models\Notification::where('target', 'admin')->count();
            $notificationsCount = $notificationsUnread;

            $newJoinings = \App\Models\Employee::whereDate('created_at', '>=', Carbon::now()->subMonth())->count();
            $dailyJoinings = \App\Models\Employee::whereDate('created_at', Carbon::today())->count();
            
            $pendingLeaves = \App\Models\Leave::where('status', 'pending')->count();

            $meetings = [];
            if (class_exists(\App\Models\Meeting::class)) {
                $meetings = \App\Models\Meeting::whereDate('start_time', Carbon::today())
                    ->orderBy('start_time')
                    ->get()
                    ->map(function ($m) {
                        return [
                            'id' => $m->id,
                            'title' => $m->title,
                            'meeting_link' => $m->meeting_link ?? '',
                            'start_time' => optional($m->start_time)->toIso8601String(),
                            'end_time' => optional($m->end_time)->toIso8601String(),
                            'status' => $m->status ?? 'scheduled',
                            'duration' => $m->getDuration() ?? null,
                        ];
                    })->toArray();
            }

            $activities = [];
            if (class_exists(\App\Models\Activity::class)) {
                $activities = \App\Models\Activity::with('user')->orderBy('created_at', 'desc')->take(10)
                    ->get()
                    ->map(function ($it) {
                        return [
                            'id' => $it->id,
                            'title' => $it->title,
                            'description' => $it->description,
                            'icon' => $it->icon ?? 'bell',
                            'bg' => $it->bg ?? null,
                            'status' => $it->status ?? 'info',
                            'user' => $it->user->name ?? null,
                            'role' => $it->user->role ?? null,
                            'created_at' => optional($it->created_at)->toIso8601String(),
                        ];
                    })->toArray();
            }

            $notifications = [];
            try {
                if (class_exists(\App\Models\Notification::class)) {
                    $notifications = \App\Models\Notification::where('target', 'admin')
                        ->orderBy('created_at', 'desc')
                        ->take(10)
                        ->get()
                        ->map(function ($n) {
                            $typeIconMap = [
                                'leave' => 'calendar-times',
                                'employee' => 'user-plus',
                                'project' => 'check-circle',
                                'system' => 'chart-bar'
                            ];
                            $typeBgMap = [
                                'leave' => 'linear-gradient(135deg, #f59e0b 0%, #ef4444 100%)',
                                'employee' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                                'project' => 'linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)',
                                'system' => 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)'
                            ];

                            $type = $n->type ?? 'system';
                            return [
                                'id' => $n->id,
                                'title' => $n->title ?? ($n->data['title'] ?? null),
                                'message' => $n->message ?? ($n->data['message'] ?? null),
                                'type' => $type,
                                'is_read' => (bool)($n->is_read ?? false),
                                'created_at' => optional($n->created_at)->toIso8601String(),
                                // display hints
                                'icon' => $n->icon ?? ($typeIconMap[$type] ?? 'bell'),
                                'bg' => $n->bg ?? ($typeBgMap[$type] ?? 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)'),
                            ];
                        })->toArray();
                }
            } catch (\Throwable $e) {
            }

            $departments_distribution = ['labels' => [], 'data' => []];
            try {
                if (class_exists(\App\Models\Department::class)) {
                    $depts = \App\Models\Department::withCount('employees')->get();
                    if ($depts->isNotEmpty()) {
                        foreach ($depts as $d) {
                            $departments_distribution['labels'][] = $d->name ?? ('Dept ' . $d->id);
                            $departments_distribution['data'][] = (int)($d->employees_count ?? 0);
                        }
                    } else {
                        if (class_exists(\App\Models\Employee::class) || \Illuminate\Support\Facades\Schema::hasTable('employees')) {
                            $rows = \Illuminate\Support\Facades\DB::table('employees')
                                ->select('department_id', \Illuminate\Support\Facades\DB::raw('count(*) as cnt'))
                                ->groupBy('department_id')
                                ->get();
                            foreach ($rows as $r) {
                                $name = \App\Models\Department::find($r->department_id)->name ?? ('Dept ' . $r->department_id);
                                $departments_distribution['labels'][] = $name;
                                $departments_distribution['data'][] = (int)$r->cnt;
                            }
                        }
                    }
                }
            } catch (\Throwable $e) {
            }

            $teams_distribution = ['labels' => [], 'data' => []];
            $teams_details = [];
            try {
                if (class_exists(\App\Models\Team::class)) {
                    $teams = \App\Models\Team::withCount('employees')->with(['projects' => function($q) {
                        $q->select('id', 'team_id', 'name')->take(5); // Fetch first 5 project names
                    }])->get();
                    
                    $teams = \App\Models\Team::withCount(['employees', 'projects'])
                        ->with(['projects' => function($q) {
                            $q->select('id', 'team_id', 'name')->latest()->take(3);
                        }])
                        ->get();

                    if ($teams->isNotEmpty()) {
                        foreach ($teams as $t) {
                            $name = $t->team_name ?? ('Team ' . $t->id);
                            $count = (int)($t->employees_count ?? 0);
                            $projectsCount = (int)($t->projects_count ?? 0);
                            $projectNames = $t->projects->pluck('name')->toArray();
                            
                            $teams_distribution['labels'][] = $name;
                            $teams_distribution['data'][] = $count;
                            
                            $teams_details[] = [
                                'name' => $name,
                                'employees_count' => $count,
                                'projects_count' => $projectsCount,
                                'project_names' => $projectNames
                            ];
                        }
                    } else {
                        // Fallback logic if no teams found (simplified for brevity, assuming teams exist)
                    }
                }
            } catch (\Throwable $e) {
            }
        } catch (\Throwable $e) {
            $meetings = [];
            $activities = [];
            $departments_distribution = ['labels' => [], 'data' => []];
            $teams_distribution = ['labels' => [], 'data' => []];
        }

        $now = Carbon::now();
        if (empty($activities)) {
            $activities = [
                [
                    'id' => 1,
                    'icon' => 'user-plus',
                    'title' => 'New Employee Onboarded',
                    'description' => 'John Doe joined Development Team',
                    'bg' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                    'status' => 'success',
                    'user' => 'Jane Doe',
                    'role' => 'HR Manager',
                    'created_at' => $now->subHours(2)->toIso8601String(),
                ],
                [
                    'id' => 2,
                    'icon' => 'check-circle',
                    'title' => 'Project Milestone Reached',
                    'description' => 'Website Redesign - 75% completed',
                    'bg' => 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
                    'status' => 'completed',
                    'user' => 'John Smith',
                    'role' => 'Project Lead',
                    'created_at' => $now->subHours(4)->toIso8601String(),
                ],
                [
                    'id' => 3,
                    'icon' => 'calendar',
                    'title' => 'Leave Request Submitted',
                    'description' => 'Sarah Wilson - Medical Leave (3 days)',
                    'bg' => 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',
                    'status' => 'pending',
                    'user' => 'Sarah Wilson',
                    'role' => 'Engineer',
                    'created_at' => $now->subHours(6)->toIso8601String(),
                ],
            ];
        }

        if (empty($meetings)) {
            $meetings = [
                [
                    'id' => 'm1',
                    'title' => 'Morning Stand-up Meeting',
                    'meeting_link' => 'https://meet.google.com/56AB3112',
                    'start_time' => $now->setTime(9, 0, 0)->toIso8601String(),
                    'end_time' => $now->setTime(9, 30, 0)->toIso8601String(),
                    'status' => 'scheduled',
                    'duration' => 30,
                ],
                [
                    'id' => 'm2',
                    'title' => 'Evening Stand-up Meeting',
                    'meeting_link' => 'https://meet.google.com/5D07E78A',
                    'start_time' => $now->setTime(17, 0, 0)->toIso8601String(),
                    'end_time' => $now->setTime(17, 30, 0)->toIso8601String(),
                    'status' => 'scheduled',
                    'duration' => 30,
                ],
            ];
        }

        if (empty($departments_distribution['labels'])) {
            $departments_distribution = [
                'labels' => ['Engineering', 'Marketing', 'Sales', 'HR', 'Finance'],
                'data' => [28, 12, 10, 6, 8],
            ];
        }

        if (empty($teams_distribution['labels'])) {
            $teams_distribution = [
                'labels' => $departments_distribution['labels'],
                'data' => $departments_distribution['data'],
            ];
        }

        // --- Normalize types: ensure labels are strings and data are integers ---
        try {
            $teams_distribution['labels'] = array_map(function ($l) {
                return (string)$l;
            }, $teams_distribution['labels'] ?? []);
            $teams_distribution['data'] = array_map(function ($n) {
                return (int)$n;
            }, $teams_distribution['data'] ?? []);
        } catch (\Throwable $e) {
            // keep as-is on error
        }

        // --- Handle Range and Performance Data ---
        $range = $request->input('range', '1M');
        $months = 6;
        if ($range === '1M') $months = 1;
        if ($range === '3M') $months = 3;
        if ($range === 'ALL') $months = 12;

        $performance = ['labels' => [], 'datasets' => []];
        try {
            $labels = [];
            $completionRates = [];
            $avgProgresses = [];
            
            // For 1M, show weekly data
            if ($range === '1M') {
                 for ($i = 4; $i >= 0; $i--) {
                    $start = Carbon::now()->subWeeks($i)->startOfWeek();
                    $end = Carbon::now()->subWeeks($i)->endOfWeek();
                    $labels[] = 'Week ' . $start->format('W');
                    
                    // Logic similar to monthly but for weeks
                    $started = 0;
                    $completed = 0;
                    $avgProgress = 0;

                    if (class_exists(\App\Models\Project::class)) {
                        $started = \App\Models\Project::whereBetween('created_at', [$start, $end])->count();
                        if (\Illuminate\Support\Facades\Schema::hasColumn('projects', 'completed_at')) {
                            $completed = \App\Models\Project::whereBetween('completed_at', [$start, $end])->count();
                        } elseif (\Illuminate\Support\Facades\Schema::hasColumn('projects', 'status')) {
                            $completed = \App\Models\Project::where('status', 'Completed')
                                ->whereBetween('updated_at', [$start, $end])
                                ->count();
                        }

                        if (\Illuminate\Support\Facades\Schema::hasColumn('projects', 'progress')) {
                            $avgProgress = (float) \Illuminate\Support\Facades\DB::table('projects')
                                ->whereBetween('updated_at', [$start, $end])
                                ->avg('progress');
                        } elseif (\Illuminate\Support\Facades\Schema::hasColumn('projects', 'percent_complete')) {
                            $avgProgress = (float) \Illuminate\Support\Facades\DB::table('projects')
                                ->whereBetween('updated_at', [$start, $end])
                                ->avg('percent_complete');
                        }
                    }
                    
                    // Fallback mock data if no projects
                    if ($started == 0) {
                        $completionRate = rand(60, 95);
                        $avgProgress = rand(40, 80);
                    } else {
                        $completionRate = $started ? round(($completed / $started) * 100, 1) : 0;
                    }

                    $completionRates[] = $completionRate;
                    $avgProgresses[] = is_null($avgProgress) ? 0 : round((float)$avgProgress, 1);
                 }
            } else {
                for ($i = $months - 1; $i >= 0; $i--) {
                    $start = Carbon::now()->subMonths($i)->startOfMonth();
                    $end = Carbon::now()->subMonths($i)->endOfMonth();
                    $labels[] = $start->format('M Y');

                    $started = 0;
                    $completed = 0;
                    $avgProgress = 0;

                    if (class_exists(\App\Models\Project::class)) {
                        $started = \App\Models\Project::whereBetween('created_at', [$start, $end])->count();
                        if (\Illuminate\Support\Facades\Schema::hasColumn('projects', 'completed_at')) {
                            $completed = \App\Models\Project::whereBetween('completed_at', [$start, $end])->count();
                        } elseif (\Illuminate\Support\Facades\Schema::hasColumn('projects', 'status')) {
                            $completed = \App\Models\Project::where('status', 'Completed')
                                ->whereBetween('updated_at', [$start, $end])
                                ->count();
                        }

                        if (\Illuminate\Support\Facades\Schema::hasColumn('projects', 'progress')) {
                            $avgProgress = (float) \Illuminate\Support\Facades\DB::table('projects')
                                ->whereBetween('updated_at', [$start, $end])
                                ->avg('progress');
                        } elseif (\Illuminate\Support\Facades\Schema::hasColumn('projects', 'percent_complete')) {
                            $avgProgress = (float) \Illuminate\Support\Facades\DB::table('projects')
                                ->whereBetween('updated_at', [$start, $end])
                                ->avg('percent_complete');
                        }
                    }
                    
                    // Fallback mock data if no projects
                    if ($started == 0) {
                        $completionRate = rand(60, 95);
                        $avgProgress = rand(40, 80);
                    } else {
                        $completionRate = $started ? round(($completed / $started) * 100, 1) : 0;
                    }

                    $completionRates[] = $completionRate;
                    $avgProgresses[] = is_null($avgProgress) ? 0 : round((float)$avgProgress, 1);
                }
            }

            $performance = [
                'labels' => $labels,
                'datasets' => [
                    [
                        'label' => 'Completion Rate (%)',
                        'data' => $completionRates,
                        'borderColor' => '#DC2626'
                    ],
                    [
                        'label' => 'Avg Progress (%)',
                        'data' => $avgProgresses,
                        'borderColor' => '#2563EB'
                    ]
                ]
            ];
        } catch (\Throwable $e) {
            // leave $performance as empty fallback (frontend has other fallbacks)
        }

        // --- Top Performers Data ---
        // --- Top Performers Data ---
        $topPerformers = [];
        try {
            if (class_exists(\App\Models\Employee::class)) {
                $topPerformers = \App\Models\Employee::with(['ratings', 'projects'])
                    ->get()
                    ->map(function($e) {
                        $avgRating = $e->ratings->avg('rating') ?: 0;
                        // Score based on rating (out of 5 -> 100) + small bonus for projects
                        $baseScore = $avgRating * 20;
                        $projectBonus = min($e->projects->count() * 2, 10);
                        $score = $baseScore > 0 ? ($baseScore + $projectBonus) : 0;
                        if ($score > 100) $score = 100;
                        
                        return [
                            'name' => $e->employee_name ?? $e->name ?? 'Unknown',
                            'email' => $e->email,
                            'role' => $e->role ?? 'Employee',
                            'score' => $score,
                            'rating' => number_format($avgRating, 1),
                            'projects_count' => $e->projects->count(),
                            'status' => $e->employee_status ?? 'active'
                        ];
                    })
                    ->sortByDesc('score')
                    ->take(5)
                    ->values()
                    ->toArray();
            }
            
            if (empty($topPerformers)) {
                 $topPerformers = [
                    ['name' => 'Alice Johnson', 'email' => 'alice@redcode.com', 'role' => 'Senior Dev', 'score' => 98, 'projects_count' => 12, 'status' => 'active'],
                    ['name' => 'Bob Smith', 'email' => 'bob@redcode.com', 'role' => 'Designer', 'score' => 92, 'projects_count' => 8, 'status' => 'active'],
                    ['name' => 'Charlie Brown', 'email' => 'charlie@redcode.com', 'role' => 'Manager', 'score' => 88, 'projects_count' => 15, 'status' => 'active'],
                    ['name' => 'Diana Prince', 'email' => 'diana@redcode.com', 'role' => 'QA Lead', 'score' => 85, 'projects_count' => 10, 'status' => 'active'],
                    ['name' => 'Evan Wright', 'email' => 'evan@redcode.com', 'role' => 'DevOps', 'score' => 79, 'projects_count' => 6, 'status' => 'active'],
                ];
            }
            
        } catch (\Throwable $e) {
             $topPerformers = [];
        }


        $payload = [
            'metrics' => [
                'revenue' => $revenue ?? '$847K',
                'efficiency' => $efficiency ?? '94.2%',
                'departments' => $departmentsCount ?? 12,
                'admins' => $adminsCount ?? 8,
                'notifications' => $notificationsCount ?? 0,
                'notifications_unread' => $notificationsUnread ?? ($notificationsCount ?? 0),
                'notifications_total' => $notificationsTotal ?? 0,
                'newJoinings' => $newJoinings ?? 8,
                'dailyJoinings' => $dailyJoinings ?? ($newJoinings ?? 0),
                'employees' => $totalEmployees ?? 0,
                'pendingLeaves' => $pendingLeaves ?? 12,
            ],
            'departments_distribution' => $departments_distribution,
            'teams_distribution' => $teams_distribution,
            'teams_details' => $teams_details ?? [],
            'performance' => $performance,
            'meetings' => $meetings,
            'activities' => $activities,
            'notifications' => $notifications,
            'topPerformers' => $topPerformers,
        ];

        return response()->json($payload);
    }
}
