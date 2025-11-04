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

            if (class_exists(\App\Models\Department::class)) {
                $departmentsCount = \App\Models\Department::count();
            }
            if (class_exists(\App\Models\Admin::class)) {
                $adminsCount = \App\Models\Admin::count();
            }
            if (class_exists(\App\Models\Employee::class)) {
                $totalEmployees = \App\Models\Employee::count();
            }
            if (class_exists(\App\Models\Notification::class)) {
                $notificationsUnread = \App\Models\Notification::where('target', 'admin')->where('is_read', false)->count();
                $notificationsTotal = \App\Models\Notification::where('target', 'admin')->count();
                $notificationsCount = $notificationsUnread;
            }
            if (class_exists(\App\Models\Employee::class)) {
                $newJoinings = \App\Models\Employee::whereDate('created_at', '>=', Carbon::now()->subMonth())->count();
                $dailyJoinings = \App\Models\Employee::whereBetween('created_at', [now()->startOfDay(), now()->endOfDay()])->count();
            }
            if (class_exists(\App\Models\Leave::class)) {
                $pendingLeaves = \App\Models\Leave::where('status', 'pending')->count();
            }

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
            try {
                if (class_exists(\App\Models\Team::class)) {
                    $teams = \App\Models\Team::withCount('employees')->get();
                    if ($teams->isNotEmpty()) {
                        foreach ($teams as $t) {
                            // use the actual team_name field (teams table uses team_name)
                            $teams_distribution['labels'][] = $t->team_name ?? ('Team ' . $t->id);
                            $teams_distribution['data'][] = (int)($t->employees_count ?? 0);
                        }
                    } else {
                        if (class_exists(\App\Models\Employee::class) || \Illuminate\Support\Facades\Schema::hasTable('employees')) {
                            $rows = \Illuminate\Support\Facades\DB::table('employees')
                                ->select('team_id', \Illuminate\Support\Facades\DB::raw('count(*) as cnt'))
                                ->groupBy('team_id')
                                ->get();
                            foreach ($rows as $r) {
                                // when looking up team records, use team_name field
                                $teamModel = \App\Models\Team::find($r->team_id);
                                $name = $teamModel ? ($teamModel->team_name ?? ('Team ' . $r->team_id)) : ('Team ' . $r->team_id);
                                $teams_distribution['labels'][] = $name;
                                $teams_distribution['data'][] = (int)$r->cnt;
                            }
                        }
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

        // --- NEW: compute project-based performance for last 6 months ---
        $performance = ['labels' => [], 'datasets' => []];
        try {
            $labels = [];
            $completionRates = [];
            $avgProgresses = [];
            for ($i = 5; $i >= 0; $i--) {
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

                $completionRate = $started ? round(($completed / $started) * 100, 1) : 0;
                $completionRates[] = $completionRate;
                $avgProgresses[] = is_null($avgProgress) ? 0 : round((float)$avgProgress, 1);
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
            'performance' => $performance,
            'meetings' => $meetings,
            'activities' => $activities,
            // NEW: include notifications in API payload
            'notifications' => $notifications,
        ];

        return response()->json($payload);
    }
}
