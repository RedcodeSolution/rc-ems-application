<?php

namespace App\Http\Controllers\Admin;
 
use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\EmployeeRating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeRatingsController extends Controller
{
    /**
     * Display a listing of employee ratings.
     */
    public function index(Request $request)
    {
        $query = EmployeeRating::with(['employee.department', 'rater.employee', 'rater.admin', 'rater.superAdmin']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('employee', function($q) use ($search) {
                $q->where('employee_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('rating_filter')) {
            $query->where('rating', $request->rating_filter);
        }

        if ($request->filled('department_filter')) {
            $query->whereHas('employee.department', function($q) use ($request) {
                $q->where('name', $request->department_filter);
            });
        }

        if ($request->filled('date_filter')) {
            switch ($request->date_filter) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'week':
                    $query->where('created_at', '>=', now()->subDays(7));
                    break;
                case 'month':
                    $query->where('created_at', '>=', now()->subDays(30));
                    break;
                case 'quarter':
                    $query->where('created_at', '>=', now()->subDays(90));
                    break;
            }
        }

        $sort = $request->get('sort', 'date_desc');
        switch ($sort) {
            case 'date_asc':
                $query->orderBy('created_at', 'asc');
                break;
            case 'date_desc':
                $query->orderBy('created_at', 'desc');
                break;
            case 'rating_desc':
                $query->orderBy('rating', 'desc');
                break;
            case 'rating_asc':
                $query->orderBy('rating', 'asc');
                break;
            case 'employee':
                $query->join('employees', 'employee_ratings.employee_id', '=', 'employees.employee_id')
                    ->orderBy('employees.employee_name', 'asc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $ratings = $query->get();
        $employees = Employee::with(['department'])->get();

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

        return view('admin.employeeRatings.index', compact('employees', 'ratings', 'paginatedRatings'));
    }

    /**
     * Store a newly created employee rating.
     */
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,employee_id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        $admin = Auth::user();

        if ($admin->employee_id === $request->employee_id) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You cannot rate yourself.'
                ], 400);
            }
            return redirect()->back()->with('error', 'You cannot rate yourself.');
        }

        $existingRating = EmployeeRating::where('employee_id', $request->employee_id)
            ->where('rated_by', $admin->id)
            ->whereDate('created_at', today())
            ->first();

        if ($existingRating) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have already rated this employee today.'
                ], 400);
            }
            return redirect()->back()->with('error', 'You have already rated this employee today.');
        }

        $rating = EmployeeRating::create([
            'employee_id' => $request->employee_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'rated_by' => $admin->id,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Employee rating added successfully!',
                'rating' => $rating
            ]);
        }

        return redirect()->route('admin.employeeRatings.index')
            ->with('success', 'Employee rating added successfully!');
    }


    /**
     * Display the specified employee rating.
     */
    public function show(EmployeeRating $employeeRating)
    {
        $employeeRating->load(['employee.department', 'rater']);
        return view('admin.employeeRatings.show', compact('employeeRating'));
    }

    /**
     * Show the form for editing the specified employee rating.
     */
    public function edit(EmployeeRating $employeeRating)
    {
        $employees = Employee::with(['department'])->get();
        $employeeRating->load(['employee.department', 'rater']);
        return view('admin.employeeRatings.edit', compact('employeeRating', 'employees'));
    }

    /**
     * Update the specified employee rating.
     */
    public function update(Request $request, EmployeeRating $employeeRating)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,employee_id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        $employeeRating->update([
            'employee_id' => $request->employee_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->route('admin.employeeRatings.index')
            ->with('success', 'Employee rating updated successfully!');
    }

    /**
     * Remove the specified employee rating.
     */
    public function destroy(EmployeeRating $employeeRating)
    {
        $employeeRating->delete();

        return redirect()->route('admin.employeeRatings.index')
            ->with('success', 'Employee rating deleted successfully!');
    }
    /**
     * Get ratings summary for a specific employee.
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
            'employee_name' => $employee->employee_name,
            'employee_email' => $employee->email,
            'employee_department' => $employee->department->name ?? 'N/A',
            'average_rating' => $averageRating,
            'total_ratings' => $totalRatings,
            'ratings' => $formattedRatings,
            'pagination' => [
                'current_page' => $ratings->currentPage(),
                'last_page' => $ratings->lastPage(),
                'has_more' => $ratings->hasMorePages(),
                'total' => $ratings->total(),
            ]
        ]);
    }
    public function getCardHtml($employeeId)
{
    // Fetch the specific employee and their ratings
    // Note: Use the same logic/relationships as your main index method
    $employeeRatingGroup = Rating::where('employee_id', $employeeId)
        ->with(['rater', 'employee'])
        ->get();

    if($employeeRatingGroup->isEmpty()) {
        return response()->json(['html' => '']);
    }

    $employeeData = $employeeRatingGroup->first()->employee;

    // Render a partial view. 
    // IMPORTANT: You might need to extract the card HTML into a separate blade file 
    // named 'partials.employee_rating_card.blade.php' to make this cleaner.
    // For now, let's assume you pass the data to a view that only contains the card markup.
    
    $html = view('partials.employee_rating_card', [
        'employeeRatingGroup' => $employeeRatingGroup,
        'employeeData' => $employeeData
    ])->render();

    return response()->json(['success' => true, 'html' => $html]);
}
}
