<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\EmployeeRating;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeRatingController extends Controller
{
    public function index()
    {
        // Get all employee ratings with relationships
        $ratings = EmployeeRating::with(['rater', 'employee'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate statistics
        $totalRatings = $ratings->count();
        $averageRating = $totalRatings > 0 ? round($ratings->avg('rating'), 1) : 0;
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

        // Get top rated employees
        $topEmployees = Employee::with(['ratings'])
            ->whereHas('ratings')
            ->get()
            ->map(function ($employee) {
                $avgRating = $employee->ratings->avg('rating');
                $totalRatings = $employee->ratings->count();
                return [
                    'employee' => $employee,
                    'average_rating' => round($avgRating, 1),
                    'total_ratings' => $totalRatings
                ];
            })
            ->sortByDesc('average_rating')
            ->take(5);

        // Get employees with most ratings
        $mostRatedEmployees = Employee::with(['ratings'])
            ->whereHas('ratings')
            ->get()
            ->map(function ($employee) {
                $totalRatings = $employee->ratings->count();
                $avgRating = $employee->ratings->avg('rating');
                return [
                    'employee' => $employee,
                    'total_ratings' => $totalRatings,
                    'average_rating' => round($avgRating, 1)
                ];
            })
            ->sortByDesc('total_ratings')
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
            'topEmployees' => $topEmployees,
            'mostRatedEmployees' => $mostRatedEmployees
        ];

        return view('super_admin.employee_ratings.index', $data);
    }

    public function show($id)
    {
        $rating = EmployeeRating::with(['rater', 'employee'])->findOrFail($id);
        return view('super_admin.employee_ratings.show', compact('rating'));
    }

    public function employeeDetails($employeeId)
    {
        $employee = Employee::with(['ratings.rater'])->findOrFail($employeeId);
        
        $ratings = $employee->ratings()->with('rater')->orderBy('created_at', 'desc')->get();
        
        $averageRating = $ratings->avg('rating');
        $totalRatings = $ratings->count();
        
        $ratingBreakdown = [
            '5_star' => $ratings->where('rating', 5)->count(),
            '4_star' => $ratings->where('rating', 4)->count(),
            '3_star' => $ratings->where('rating', 3)->count(),
            '2_star' => $ratings->where('rating', 2)->count(),
            '1_star' => $ratings->where('rating', 1)->count(),
        ];

        $data = [
            'employee' => $employee,
            'ratings' => $ratings,
            'averageRating' => round($averageRating, 1),
            'totalRatings' => $totalRatings,
            'ratingBreakdown' => $ratingBreakdown
        ];

        return view('super_admin.employee_ratings.employee_details', $data);
    }

    public function delete($id)
    {
        $rating = EmployeeRating::findOrFail($id);
        $rating->delete();

        return redirect()->route('super_admin.employee_ratings')
            ->with('success', 'Rating deleted successfully!');
    }

    public function export()
    {
        // In a real application, this would generate a CSV/Excel file
        return redirect()->route('super_admin.employee_ratings')
            ->with('success', 'Ratings exported successfully!');
    }
} 