<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Department;
use App\Models\Employee;
use App\Models\EmployeeRating;
use App\Models\Leave;
use App\Models\Meeting;
use App\Models\Project;
use App\Models\SuperAdmin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeRatingController extends Controller
{

    /**
     * Display the Employee Ratings page for Super Admin.
     */
    public function employeeRatings()
    {
        // Get all employee ratings with employee and rater information
        $ratings = EmployeeRating::with(['employee', 'rater.employee', 'rater.admin', 'rater.superAdmin'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate statistics
        $totalRatings = $ratings->count();
        $averageRating = $totalRatings > 0 ? $ratings->avg('rating') : 0;
        $fiveStarRatings = $ratings->where('rating', 5)->count();
        $fourStarRatings = $ratings->where('rating', 4)->count();
        $threeStarRatings = $ratings->where('rating', 3)->count();
        $twoStarRatings = $ratings->where('rating', 2)->count();
        $oneStarRatings = $ratings->where('rating', 1)->count();

        // Get recent ratings (last 30 days)
        $recentRatings = $ratings->where('created_at', '>=', now()->subDays(30));

        // Calculate rating distribution percentages
        $ratingDistribution = [
            '5_star' => $totalRatings > 0 ? round(($fiveStarRatings / $totalRatings) * 100, 1) : 0,
            '4_star' => $totalRatings > 0 ? round(($fourStarRatings / $totalRatings) * 100, 1) : 0,
            '3_star' => $totalRatings > 0 ? round(($threeStarRatings / $totalRatings) * 100, 1) : 0,
            '2_star' => $totalRatings > 0 ? round(($twoStarRatings / $totalRatings) * 100, 1) : 0,
            '1_star' => $totalRatings > 0 ? round(($oneStarRatings / $totalRatings) * 100, 1) : 0,
        ];

        $topRatedEmployees = $ratings->groupBy('employee_id')
            ->map(function ($employeeRatings) {
                return [
                    'employee' => $employeeRatings->first()->employee,
                    'average_rating' => $employeeRatings->avg('rating'),
                    'total_ratings' => $employeeRatings->count(),
                    'recent_ratings' => $employeeRatings->where('created_at', '>=', now()->subDays(30))->count()
                ];
            })
            ->sortByDesc('average_rating')
            ->take(5);

        $data = [
            'ratings' => $ratings,
            'totalRatings' => $totalRatings,
            'averageRating' => $averageRating,
            'fiveStarRatings' => $fiveStarRatings,
            'fourStarRatings' => $fourStarRatings,
            'threeStarRatings' => $threeStarRatings,
            'twoStarRatings' => $twoStarRatings,
            'oneStarRatings' => $oneStarRatings,
            'recentRatings' => $recentRatings,
            'ratingDistribution' => $ratingDistribution,
            'topRatedEmployees' => $topRatedEmployees
        ];

        // Group ratings by employee for the list
        $groupedRatings = $ratings->groupBy('employee_id');

        // Manual Pagination for the grouped items
        $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1;
        $perPage = 6;
        $currentItems = $groupedRatings->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $paginatedRatings = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentItems,
            $groupedRatings->count(),
            $perPage,
            $currentPage,
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
        );

        $data['paginatedRatings'] = $paginatedRatings;

        return view('super_admin.employee_ratings', $data);
    }

    public function storeEmployeeRating(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,employee_id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();

        if (!$user || strtolower($user->role) !== 'super_admin') {
            return response()->json([
                'success' => false,
                'message' => 'Only Super Admins can submit ratings here.'
            ], 403);
        }

        // Prevent rating self if super admin is mapped to an employee_id
        if (!empty($user->employee_id) && (string)$user->employee_id === (string)$request->employee_id) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot rate yourself.'
            ], 400);
        }

        // Allow daily ratings: prevent multiple ratings in the same day per rater per employee
        $existingRating = EmployeeRating::where('employee_id', $request->employee_id)
            ->where('rated_by', $user->id)
            ->whereDate('created_at', today())
            ->first();

        if ($existingRating) {
            return response()->json([
                'success' => false,
                'message' => 'You have already rated this employee today.'
            ], 400);
        }

        $rating = EmployeeRating::create([
            'employee_id' => $request->employee_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'rated_by' => $user->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Rating submitted successfully!',
            'rating' => $rating->load(['employee', 'rater'])
        ]);
    }

    /**
     * Get ratings summary for a specific employee (used by modal summary in blade).
     */
    public function getEmployeeRatings(Request $request, $employeeId)
    {
        $query = EmployeeRating::with(['rater', 'employee'])
            ->where('employee_id', $employeeId)
            ->orderBy('created_at', 'desc');

        $totalRatings = $query->count();
        $averageRating = $totalRatings > 0 ? round($query->avg('rating'), 1) : 0;

        $ratings = $query->paginate(3);

        $employee = Employee::find($employeeId);

        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found.'
            ], 404);
        }

        $formattedRatings = collect($ratings->items())->map(function ($rating) {
            return [
                'id' => $rating->id,
                'rating' => $rating->rating,
                'comment' => $rating->comment,
                'created_at' => $rating->created_at->format('M d, Y'),
                'rater_name' => $rating->rater->name ?? 'Unknown',
                'rater_role' => $rating->rater->role ?? 'unknown',
            ];
        });

        return response()->json([
            'success' => true,
            'employee' => [
                'name' => $employee->employee_name,
                'email' => $employee->email,
                'role' => $employee->role,
            ],
            'average_rating' => $averageRating,
            'total_ratings' => $totalRatings,
            'recent_ratings' => $formattedRatings,
            'ratings' => $formattedRatings,
            'pagination' => [
                'current_page' => $ratings->currentPage(),
                'last_page' => $ratings->lastPage(),
                'has_more' => $ratings->hasMorePages(),
                'total' => $ratings->total(),
            ]
        ]);
    }
}
