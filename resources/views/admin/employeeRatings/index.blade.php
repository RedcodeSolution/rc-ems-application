@extends('layouts.admin')
<link rel="stylesheet" href="{{ asset('css/admin/employeeRatings.css') }}">
@section('title', 'Employee Performance Ratings')

@section('content')
<div class="content-area">
    <div class="page-header">
        <div class="header-content">
            <h1 class="page-title">
                <i class="fas fa-star" style="color: #fbbf24; margin-right: 0.75rem;"></i>
                Employee Performance Ratings
            </h1>
            <p class="page-description">
                Visual overview of employee ratings across the organization
            </p>

            <!-- Role-based Access Notice -->
            <div class="role-notice">
                <div class="role-notice-title">
                    <i class="fas fa-info-circle" style="margin-right: 0.5rem;"></i>
                    Access Level: Admin
                </div>
                <div class="role-notice-text">
                    ✅ You can view all employee ratings and rate other employees
                </div>
            </div>

            <!-- Rating Legend -->
            <div class="rating-legend">
                <div class="rating-legend-title">Rating Color Legend:</div>
                <div class="rating-legend-items">
                    <div class="rating-legend-item">
                        <div class="rating-legend-color" style="background-color: #DC2626;"></div>
                        <span>Super Admin</span>
                    </div>
                    <div class="rating-legend-item">
                        <div class="rating-legend-color" style="background-color: #fbbf24;"></div>
                        <span>Admin</span>
                    </div>
                    <div class="rating-legend-item">
                        <div class="rating-legend-color" style="background-color: #f97316;"></div>
                        <span>BA</span>
                    </div>
                    <div class="rating-legend-item">
                        <div class="rating-legend-color" style="background-color: #22c55e;"></div>
                        <span>QA</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="header-actions">
            <button class="btn btn-primary" onclick="openRateEmployeeModal()">
                <i class="fas fa-plus"></i>
                Rate Employee
            </button>
        </div>
    </div>

    <!-- Quick Statistics Cards -->
    @if($ratings->count() > 0)
    <div class="stats-section">
        <div class="stats-grid">
            @php
            $avgRating = $ratings->avg('rating');
            $totalRatings = $ratings->count();
            $fiveStarRatings = $ratings->where('rating', 5)->count();
            $recentRatings = $ratings->where('created_at', '>=', now()->subDays(7))->count();
            @endphp

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-star"></i>
                </div>
                <div class="stat-content">
                    <h3>Average Rating</h3>
                    <div class="stat-number">{{ number_format($avgRating, 1) }}/5</div>
                    <p>Overall performance</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-content">
                    <h3>Total Ratings</h3>
                    <div class="stat-number">{{ $totalRatings }}</div>
                    <p>All time ratings</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-star"></i>
                </div>
                <div class="stat-content">
                    <h3>5-Star Ratings</h3>
                    <div class="stat-number">{{ $fiveStarRatings }}</div>
                    <p>Excellent performance</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-calendar-week"></i>
                </div>
                <div class="stat-content">
                    <h3>This Week</h3>
                    <div class="stat-number">{{ $recentRatings }}</div>
                    <p>Recent ratings</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Employee Ratings Grid -->
    <style>
        .ratings-pagination-wrapper {
            width: 100%;
            grid-column: 1 / -1;
            display: flex;
            justify-content: center;
            margin-top: 2rem;
        }
        .ratings-pagination {
            display: flex;
            justify-content: center;
        }
        .ratings-pagination nav {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            background: #fff;
            padding: 0.75rem;
            border-radius: 0.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .ratings-pagination .pagination {
            display: flex;
            list-style: none;
            padding: 0;
            margin: 0;
            gap: 0.25rem;
        }
        .ratings-pagination .page-item .page-link {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 0.375rem;
            color: var(--text-secondary);
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
            border: 1px solid transparent;
        }
        .ratings-pagination .page-item.active .page-link {
            background: linear-gradient(135deg, #DC2626 0%, #991b1b 100%); /* SuperAdmin Red */
            color: white;
            box-shadow: 0 4px 6px -1px rgba(220, 38, 38, 0.2);
        }
        .ratings-pagination .page-item:not(.active) .page-link:hover {
            background-color: #fef2f2;
            color: #DC2626;
        }
        .ratings-pagination .page-item.disabled .page-link {
            color: #d1d5db;
            pointer-events: none;
        }
    </style>

    <div class="employee-ratings-grid">
        @if($paginatedRatings->count() > 0)
        @foreach($paginatedRatings as $employeeId => $employeeRatingGroup)
        @php
        $employeeData = $employeeRatingGroup->first()->employee;
        $totalRatings = $employeeRatingGroup->count();
        $averageRating = $employeeRatingGroup->avg('rating');
        $percentage = ($averageRating / 5) * 100;

        // Calculate ratings by role
        $superAdminRatings = $employeeRatingGroup->where('rater.role', 'super_admin')->count();
        $adminRatings = $employeeRatingGroup->where('rater.role', 'admin')->count();
        $baRatings = $employeeRatingGroup->where('rater.role', 'ba')->count();
        $qaRatings = $employeeRatingGroup->where('rater.role', 'qa')->count();

        // Calculate percentages for progress bar
        $totalRoleRatings = $superAdminRatings + $adminRatings + $baRatings + $qaRatings;
        $superAdminPercent = $totalRoleRatings > 0 ? ($superAdminRatings / $totalRoleRatings) * 100 : 0;
        $adminPercent = $totalRoleRatings > 0 ? ($adminRatings / $totalRoleRatings) * 100 : 0;
        $baPercent = $totalRoleRatings > 0 ? ($baRatings / $totalRoleRatings) * 100 : 0;
        $qaPercent = $totalRoleRatings > 0 ? ($qaRatings / $totalRoleRatings) * 100 : 0;
        @endphp

        <div class="employee-rating-card">
            <div class="employee-info-section">
                @if(!empty($employeeData->profile_photo))
                <div class="employee-avatar" style="padding:0;">
                    <img src="{{ asset('storage/' . $employeeData->profile_photo) }}" alt="Profile Photo" style="width:60px;height:60px;border-radius:50%;object-fit:cover;">
                </div>
                @else
                <div class="employee-avatar">
                    {{ strtoupper(substr($employeeData->employee_name ?? 'E', 0, 1)) }}
                </div>
                @endif
                <div class="employee-details">
                    <div class="employee-name">{{ $employeeData->employee_name ?? 'Unknown Employee' }}</div>
                    <div class="employee-role">{{ $employeeData->department->name ?? 'No Department' }}</div>
                    <div class="employee-email">{{ $employeeData->email ?? 'No email' }}</div>

                    @php
                        // Get unique ratings keyed by rater to show latest rating per person
                        $uniqueRatings = $employeeRatingGroup->sortByDesc('created_at')->unique('rated_by')->filter(fn($r) => $r->rater);
                        $displayRatings = $uniqueRatings->take(4);
                        $remainingCount = $uniqueRatings->count() - 4;
                    @endphp
                    <div class="raters-info" style="margin-top: 8px; display: flex; align-items: center;">
                        <span style="font-size: 0.75rem; color: #64748b; margin-right: 8px;">Rated by:</span>
                        <div class="avatar-stack" style="display: flex; padding-left: 8px;">
                            @foreach($displayRatings as $rating)
                                @php
                                    $rater = $rating->rater;
                                    $photo = $rater->employee->profile_photo 
                                            ?? $rater->admin->profile_image 
                                            ?? $rater->superAdmin->profile_image 
                                            ?? null;
                                    $initials = strtoupper(substr($rater->name ?? 'U', 0, 1));
                                    $color = $rater->role === 'super_admin' ? '#DC2626' : ($rater->role === 'admin' ? '#fbbf24' : '#3b82f6');
                                    $score = $rating->rating;
                                @endphp
                                <div class="rater-avatar" title="{{ $rater->name ?? 'Unknown' }} ({{ ucfirst(str_replace('_', ' ', $rater->role ?? 'user')) }}) • Rating: {{ $score }}/5" 
                                     style="width: 24px; height: 24px; border-radius: 50%; border: 2px solid white; margin-left: -8px; background-color: {{ $color }}; color: white; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: bold; overflow: hidden; cursor:help;">
                                    @if($photo)
                                        <img src="{{ asset('storage/' . $photo) }}" alt="{{ $rater->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        {{ $initials }}
                                    @endif
                                </div>
                            @endforeach
                            @if($remainingCount > 0)
                                <div class="rater-avatar remaining-count" title="{{ $uniqueRatings->skip(4)->map(fn($r) => $r->rater->name . ' (' . $r->rating . '/5)')->implode(', ') }}"
                                     style="width: 24px; height: 24px; border-radius: 50%; border: 2px solid white; margin-left: -8px; background-color: #e2e8f0; color: #64748b; display: flex; align-items: center; justify-content: center; font-size: 0.65rem; font-weight: bold;">
                                    +{{ $remainingCount }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="rating-progress-container">
                <div class="rating-progress-bar">
                    <div class="rating-progress-segments">
                        @if($superAdminPercent > 0)
                        <div class="progress-segment segment-super-admin" style="width: {{ $superAdminPercent }}%"></div>
                        @endif
                        @if($adminPercent > 0)
                        <div class="progress-segment segment-admin" style="width: {{ $adminPercent }}%"></div>
                        @endif
                        @if($baPercent > 0)
                        <div class="progress-segment segment-ba" style="width: {{ $baPercent }}%"></div>
                        @endif
                        @if($qaPercent > 0)
                        <div class="progress-segment segment-qa" style="width: {{ $qaPercent }}%"></div>
                        @endif
                        @if($percentage < 100)
                        <div class="progress-segment segment-empty" style="width: {{ 100 - $percentage }}%"></div>
                        @endif
                    </div>
                </div>
                <div class="rating-stats">
                    <div class="rating-percentage">{{ round($percentage) }}%</div>
                    <div class="rating-details">
                        <span class="rating-number">{{ number_format($averageRating, 1) }}/5</span>
                        <span class="rating-count">({{ $totalRatings }} ratings)</span>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

        <!-- Pagination Links -->
        <div class="ratings-pagination-wrapper">
            <div class="ratings-pagination">
                {{ $paginatedRatings->links('pagination::bootstrap-4') }}
            </div>
        </div>

        @else
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-star" style="color: var(--text-secondary); font-size: 1.5rem;"></i>
            </div>
            <h3 class="empty-title">No Ratings Yet</h3>
            <p class="empty-text">No employee ratings have been submitted yet.</p>
            <button class="btn btn-primary" onclick="openRateEmployeeModal()">
                <i class="fas fa-plus"></i> Add First Rating
            </button>
        </div>
        @endif
    </div>

    <!-- Rate Employee Modal -->
    <div id="rateEmployeeModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Rate Employee</h2>
                <button class="modal-close" onclick="closeModal('rateEmployeeModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="rateEmployeeForm" action="{{ route('admin.employeeRatings.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="employee_id">Select Employee *</label>
                        <select name="employee_id" id="employee_id" class="form-control" required onchange="loadEmployeeRatings(this.value)">
                            <option value="">Choose an employee...</option>
                            @foreach($employees as $employee)
                            <option value="{{ $employee->employee_id }}">
                                {{ $employee->employee_name }} -   {{ $employee->department->name ?? 'No Department' }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group" id="employee-rating-summary" style="display: none;">
                        <label>Employee Rating Summary</label>
                        <div class="rating-summary">
                            <div class="summary-stats">
                                <div class="stat-item">
                                    <span class="stat-label">Average Rating:</span>
                                    <span class="stat-value" id="avg-rating">-</span>
                                </div>
                                <div class="stat-item">
                                    <span class="stat-label">Total Ratings:</span>
                                    <span class="stat-value" id="total-ratings">-</span>
                                </div>
                            </div>
                            <div class="recent-ratings" id="recent-ratings-list">
                                <!-- Recent ratings will be loaded here -->
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Rating *</label>
                        <div class="rating-input">
                            <div class="star-rating">
                                <input type="radio" name="rating" value="5" id="star5" required>
                                <label for="star5" class="star">★</label>
                                <input type="radio" name="rating" value="4" id="star4">
                                <label for="star4" class="star">★</label>
                                <input type="radio" name="rating" value="3" id="star3">
                                <label for="star3" class="star">★</label>
                                <input type="radio" name="rating" value="2" id="star2">
                                <label for="star2" class="star">★</label>
                                <input type="radio" name="rating" value="1" id="star1">
                                <label for="star1" class="star">★</label>
                            </div>
                            <span class="rating-text">Select a rating</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="comment">Comment</label>
                        <textarea name="comment" id="comment" class="form-control" rows="4" placeholder="Provide feedback about the employee's performance..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeModal('rateEmployeeModal')">
                    Cancel
                </button>
                <button class="btn btn-primary" onclick="submitRating()">
                    <i class="fas fa-save"></i>
                    Submit Rating
                </button>
            </div>
        </div>
    </div>


{{-- Link external JS file for employee rating functionality --}}
<script src="{{ asset('js/admin/employeeRating.js') }}"></script>

@endsection
