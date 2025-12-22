<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\EmployeeRating;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeRatingController extends Controller
{
    /**
     * Display all employee ratings.
     */
    public function index()
    {
        $user = Auth::user();

        $ratings = EmployeeRating::with(['rater', 'employee'])
            ->orderBy('created_at', 'desc')
            ->get();

        $totalRatings = $ratings->count();
        $averageRating = $totalRatings > 0 ? $ratings->avg('rating') : 0;
        $fiveStarRatings = $ratings->where('rating', 5)->count();
        $fourStarRatings = $ratings->where('rating', 4)->count();
        $threeStarRatings = $ratings->where('rating', 3)->count();
        $twoStarRatings = $ratings->where('rating', 2)->count();
        $oneStarRatings = $ratings->where('rating', 1)->count();

        $recentRatings = $ratings->where('created_at', '>=', now()->subDays(30));


        $ratingDistribution = [
            '5_star' => $totalRatings > 0 ? round(($fiveStarRatings / $totalRatings) * 100, 1) : 0,
            '4_star' => $totalRatings > 0 ? round(($fourStarRatings / $totalRatings) * 100, 1) : 0,
            '3_star' => $totalRatings > 0 ? round(($threeStarRatings / $totalRatings) * 100, 1) : 0,
            '2_star' => $totalRatings > 0 ? round(($twoStarRatings / $totalRatings) * 100, 1) : 0,
            '1_star' => $totalRatings > 0 ? round(($oneStarRatings / $totalRatings) * 100, 1) : 0,
        ];

        // Group ratings by employee for the list
        $groupedRatings = $ratings->groupBy('employee_id');

        // Manual Pagination for the grouped items
        $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1;
        $perPage = 6; // Adjust per page as needed
        $currentItems = $groupedRatings->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $paginatedRatings = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentItems,
            $groupedRatings->count(),
            $perPage,
            $currentPage,
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
        );

        $employees = Employee::where('employee_id', '!=', $user->employee_id)->get();

        $data = [
            'ratings' => $ratings, // Keep for stats
            'paginatedRatings' => $paginatedRatings, // For the list
            'totalRatings' => $totalRatings,
            'averageRating' => $averageRating,
            'fiveStarRatings' => $fiveStarRatings,
            'fourStarRatings' => $fourStarRatings,
            'threeStarRatings' => $threeStarRatings,
            'twoStarRatings' => $twoStarRatings,
            'oneStarRatings' => $oneStarRatings,
            'recentRatings' => $recentRatings,
            'ratingDistribution' => $ratingDistribution,
            'employee' => $user,
            'employees' => $employees
        ];

        return view('employees.ratings.index', $data);
    }

    /**
     * Store a new employee rating.
     */
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,employee_id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();

        if (!in_array(strtolower($user->role), ['ba', 'qa'])) {
            return response()->json([
                'success' => false,
                'message' => 'Only BA and QA employees can rate other employees.'
            ], 403);
        }

        if ($user->employee_id === $request->employee_id) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot rate yourself.'
            ], 400);
        }

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

        // Calculate new average rating for the employee
        $newAverage = EmployeeRating::where('employee_id', $request->employee_id)->avg('rating');

        return response()->json([
            'success' => true,
            'message' => 'Rating submitted successfully!',
            'rating' => $rating->load(['employee', 'rater']),
            'employee_id' => $request->employee_id,
            'new_average_rating' => round($newAverage, 1)
        ]);
    }

    /**
     * Get ratings for a specific employee.
     */
    public function getEmployeeRatings($employeeId)
    {
        $ratings = EmployeeRating::with(['rater', 'employee'])
            ->where('employee_id', $employeeId)
            ->orderBy('created_at', 'desc')
            ->get();

        $employee = Employee::find($employeeId);

        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found.'
            ], 404);
        }

        $averageRating = $ratings->count() > 0 ? $ratings->avg('rating') : 0;

        $recentRatings = $ratings->take(10)->map(function($rating) {
            return [
                'id' => $rating->id,
                'rating' => $rating->rating,
                'comment' => $rating->comment,
                'created_at' => $rating->created_at->format('M d, Y'),
                'rater_name' => $rating->rater->name ?? 'Unknown',
                'rater_role' => $rating->rater->role ?? 'unknown'
            ];
        });

        return response()->json([
            'success' => true,
            'employee' => [
                'name' => $employee->employee_name,
                'email' => $employee->email,
                'role' => $employee->role
            ],
            'average_rating' => round($averageRating, 1),
            'total_ratings' => $ratings->count(),
            'recent_ratings' => $recentRatings
        ]);
    }

    public function getTopRated()
    {
        $topRatedEmployees = \App\Models\Employee::whereHas('ratings')
            ->withAvg('ratings', 'rating')
            ->orderByDesc('ratings_avg_rating')
            ->take(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $topRatedEmployees->map(function ($emp) {
                return [
                    'id' => $emp->employee_id, // Explicitly use employee_id
                    'employee_name' => $emp->employee_name,
                    'profile_photo' => $emp->profile_photo ? asset('storage/' . $emp->profile_photo) : null,
                    'avg_rating' => round($emp->ratings_avg_rating, 1)
                ];
            })
        ]);
    }
}
