@extends('layouts.super_admin')

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
                Access Level: Super Admin
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

@if($ratings->count() > 0)
<div class="stats-section" style="margin-bottom: 2rem;">
    <div class="stats-grid" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:1.5rem;">
        @php
            $avgRating = $ratings->avg('rating');
            $totalRatings = $ratings->count();
            $fiveStarRatings = $ratings->where('rating', 5)->count();
            $recentRatings = $ratings->where('created_at', '>=', now()->subDays(7))->count();
        @endphp

        <div class="stat-card" style="background:#fff;border-radius:12px;padding:1.5rem;box-shadow:0 2px 16px 0 rgba(220,38,38,0.07);display:flex;align-items:center;gap:1rem;">
            <div class="stat-icon" style="width:60px;height:60px;border-radius:12px;background:var(--gradient-primary);display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.5rem;">
                <i class="fas fa-star"></i>
            </div>
            <div class="stat-content">
                <h3 style="font-size:0.875rem;font-weight:600;color:var(--text-secondary);margin:0 0 0.5rem 0;text-transform:uppercase;letter-spacing:0.05em;">Average Rating</h3>
                <div class="stat-number" style="font-size:2rem;font-weight:700;color:var(--text-primary);">{{ number_format($avgRating, 1) }}/5</div>
                <p style="font-size:0.875rem;color:var(--text-secondary);margin:0;">Overall performance</p>
            </div>
        </div>

        <div class="stat-card" style="background:#fff;border-radius:12px;padding:1.5rem;box-shadow:0 2px 16px 0 rgba(220,38,38,0.07);display:flex;align-items:center;gap:1rem;">
            <div class="stat-icon" style="width:60px;height:60px;border-radius:12px;background:var(--gradient-primary);display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.5rem;">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="stat-content">
                <h3 style="font-size:0.875rem;font-weight:600;color:var(--text-secondary);margin:0 0 0.5rem 0;text-transform:uppercase;letter-spacing:0.05em;">Total Ratings</h3>
                <div class="stat-number" style="font-size:2rem;font-weight:700;color:var(--text-primary);">{{ $totalRatings }}</div>
                <p style="font-size:0.875rem;color:var(--text-secondary);margin:0;">All time ratings</p>
            </div>
        </div>

        <div class="stat-card" style="background:#fff;border-radius:12px;padding:1.5rem;box-shadow:0 2px 16px 0 rgba(220,38,38,0.07);display:flex;align-items:center;gap:1rem;">
            <div class="stat-icon" style="width:60px;height:60px;border-radius:12px;background:var(--gradient-primary);display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.5rem;">
                <i class="fas fa-star"></i>
            </div>
            <div class="stat-content">
                <h3 style="font-size:0.875rem;font-weight:600;color:var(--text-secondary);margin:0 0 0.5rem 0;text-transform:uppercase;letter-spacing:0.05em;">5-Star Ratings</h3>
                <div class="stat-number" style="font-size:2rem;font-weight:700;color:var(--text-primary);">{{ $fiveStarRatings }}</div>
                <p style="font-size:0.875rem;color:var(--text-secondary);margin:0;">Excellent performance</p>
            </div>
        </div>

        <div class="stat-card" style="background:#fff;border-radius:12px;padding:1.5rem;box-shadow:0 2px 16px 0 rgba(220,38,38,0.07);display:flex;align-items:center;gap:1rem;">
            <div class="stat-icon" style="width:60px;height:60px;border-radius:12px;background:var(--gradient-primary);display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.5rem;">
                <i class="fas fa-calendar-week"></i>
            </div>
            <div class="stat-content">
                <h3 style="font-size:0.875rem;font-weight:600;color:var(--text-secondary);margin:0 0 0.5rem 0;text-transform:uppercase;letter-spacing:0.05em;">This Week</h3>
                <div class="stat-number" style="font-size:2rem;font-weight:700;color:var(--text-primary);">{{ $recentRatings }}</div>
                <p style="font-size:0.875rem;color:var(--text-secondary);margin:0;">Recent ratings</p>
            </div>
        </div>
    </div>
    </div>
@endif

<!-- Employee Ratings Grid -->
<div class="employee-ratings-grid">
    @php
        // Group ratings by employee
        $employeeRatings = $ratings->groupBy('employee_id');
    @endphp

    @if($employeeRatings->count() > 0)
        @foreach($employeeRatings as $employeeId => $employeeRatingGroup)
            @php
                $employeeData = $employeeRatingGroup->first()->employee;
                $totalRatings = $employeeRatingGroup->count();
                $averageRating = $employeeRatingGroup->avg('rating');
                $percentage = ($averageRating / 5) * 100;

                // FIX: Use case-insensitive filter for rater role
                $superAdminRatings = $employeeRatingGroup->filter(fn($r) => strtolower($r->rater->role ?? '') === 'super_admin')->count();
                $adminRatings = $employeeRatingGroup->filter(fn($r) => strtolower($r->rater->role ?? '') === 'admin')->count();
                $baRatings = $employeeRatingGroup->filter(fn($r) => strtolower($r->rater->role ?? '') === 'ba')->count();
                $qaRatings = $employeeRatingGroup->filter(fn($r) => strtolower($r->rater->role ?? '') === 'qa')->count();

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
                        <div class="employee-role">{{ ucfirst($employeeData->role ?? 'Unknown') }}</div>
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
                    <div class="rating-percentage">{{ round($percentage) }}%</div>
                </div>
            </div>
        @endforeach
    @else
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-star" style="color: var(--text-secondary); font-size: 1.5rem;"></i>
            </div>
            <h3 class="empty-title">No Ratings Yet</h3>
            <p class="empty-text">No employee ratings have been submitted yet.</p>
        </div>
    @endif
</div>

<!-- View Rating Modal -->
<div id="viewRatingModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Rating Details</h2>
            <button class="modal-close" onclick="closeModal('viewRatingModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <div class="rating-details">
                <div class="detail-section">
                    <h3>Employee Information</h3>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <label>Employee Name:</label>
                            <span id="modal-employee-name">John Doe</span>
                        </div>
                        <div class="detail-item">
                            <label>Email:</label>
                            <span id="modal-employee-email">john.doe@company.com</span>
                        </div>

                    </div>
                </div>

                <div class="detail-section">
                    <h3>Rating Information</h3>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <label>Rating:</label>
                            <div class="rating-display" id="modal-rating">
                                <i class="fas fa-star filled"></i>
                                <i class="fas fa-star filled"></i>
                                <i class="fas fa-star filled"></i>
                                <i class="fas fa-star filled"></i>
                                <i class="fas fa-star empty"></i>
                                <span class="rating-number">(4/5)</span>
                            </div>
                        </div>
                        <div class="detail-item">
                            <label>Rated By:</label>
                            <span id="modal-rater-name">Sarah Wilson</span>
                        </div>
                        <div class="detail-item">
                            <label>Date:</label>
                            <span id="modal-rating-date">January 15, 2024</span>
                        </div>
                    </div>
                </div>

                <div class="detail-section">
                    <h3>Comment</h3>
                    <div class="comment-full" id="modal-comment">
                        <p>Excellent work on the project. Very professional and dedicated to the task. Great communication skills and always meets deadlines.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal('viewRatingModal')">
                Close
            </button>
        </div>
    </div>
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
            <form id="rateEmployeeForm" action="{{ route('super_admin.employee_ratings.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="employee_id">Select Employee *</label>
                    <select name="employee_id" id="employee_id" class="form-control" required onchange="loadEmployeeRatings(this.value)">
                        <option value="">Choose an employee...</option>
                        @php
                            $employees = \App\Models\Employee::all();
                        @endphp
                        @if($employees->count() > 0)
                            @foreach($employees as $employee)
                                <option value="{{ $employee->employee_id }}">{{ $employee->employee_name }}</option>
                            @endforeach
                        @else
                            <option value="" disabled>No employees available</option>
                        @endif
                    </select>
                    @if($employees->count() == 0)
                        <small class="text-danger">No employees found in the system.</small>
                    @endif
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

<style>
/* Page Header */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding: 1.5rem;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 16px 0 rgba(220,38,38,0.07);
    border: none;
}

/* Main Content Background */
.content-area {
    background-color: var(--primary-light);
    min-height: 100vh;
    padding: 2rem;
}

.header-content h1 {
    font-size: 1.875rem;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.header-content p {
    color: var(--text-secondary);
    margin: 0.5rem 0 0 0;
}

.header-actions {
    display: flex;
    gap: 1rem;
}

/* Role-based Access Notice */
.role-notice {
    background: rgba(59, 130, 246, 0.1);
    padding: 1rem;
    border-radius: 0.5rem;
    margin-bottom: 1rem;
    border-left: 4px solid #3b82f6;
}
.role-notice-title {
    font-weight: 600;
    color: #1e40af;
    margin-bottom: 0.5rem;
}
.role-notice-text {
    color: var(--text-secondary);
    font-size: 0.875rem;
}

/* Rating Legend */
.rating-legend {
    background: #fff;
    padding: 1rem;
    border-radius: 0.5rem;
    margin-bottom: 1rem;
    border: 1px solid var(--border-light);
    box-shadow: 0 2px 16px 0 rgba(220,38,38,0.07);
}
.rating-legend-title {
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}
.rating-legend-items {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
}
.rating-legend-item {
    display: flex;
    align-items: center;
    font-size: 0.875rem;
    color: var(--text-secondary);
}
.rating-legend-color {
    width: 16px;
    height: 16px;
    border-radius: 3px;
    margin-right: 0.5rem;
}

/* Employee Rating Cards */
.employee-ratings-grid {
    display: grid;
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.employee-rating-card {
    background: #fff;
    border-radius: 0.75rem;
    padding: 1.5rem;
    border: none;
    box-shadow: 0 2px 16px 0 rgba(220,38,38,0.07);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.employee-rating-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 20px 25px -5px rgba(220,38,38,0.1);
}

.employee-info-section {
    display: flex;
    align-items: center;
    margin-bottom: 1rem;
}

.employee-avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    border: 2px solid var(--border-light);
    background: var(--gradient-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    font-weight: 600;
    color: white;
    margin-right: 1rem;
}

.employee-details {
    flex: 1;
}

.employee-name {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.25rem;
}

.employee-role {
    font-size: 0.875rem;
    color: var(--text-secondary);
}

/* Progress Bar */
.rating-progress-container {
    margin-bottom: 1rem;
}

.rating-progress-bar {
    width: 100%;
    height: 12px;
    background: var(--border-light);
    border-radius: 6px;
    overflow: hidden;
    position: relative;
}

.rating-progress-segments {
    display: flex;
    height: 100%;
}

.progress-segment {
    height: 100%;
    transition: all 0.3s ease;
}

.segment-super-admin { background-color: #DC2626; }
.segment-admin { background-color: #fbbf24; }
.segment-ba { background-color: #f97316; }
.segment-qa { background-color: #22c55e; }
.segment-empty { background-color: var(--border-light); }

.rating-percentage {
    text-align: right;
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-top: 0.5rem;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 2rem;
    color: var(--text-secondary);
    background: #fff;
    border-radius: 0.75rem;
    box-shadow: 0 2px 16px 0 rgba(220,38,38,0.07);
}
.empty-icon {
    width: 4rem;
    height: 4rem;
    background: var(--border-light);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
}
.empty-title {
    font-size: 1.125rem;
    font-weight: 500;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}
.empty-text {
    color: var(--text-secondary);
}

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    z-index: 1000;
}

.modal-content {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: #fff;
    border-radius: 12px;
    width: 90%;
    max-width: 600px;
    max-height: 90vh;
    overflow-y: auto;
}

.modal-header {
    padding: 1.5rem;
    border-bottom: 1px solid var(--border-light);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h2 {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 600;
}

.modal-close {
    background: none;
    border: none;
    font-size: 1.25rem;
    color: var(--text-secondary);
    cursor: pointer;
}

.modal-body {
    padding: 1.5rem;
}

.modal-footer {
    padding: 1.5rem;
    border-top: 1px solid var(--border-light);
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
}

/* Rating Details */
.rating-details {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.detail-section h3 {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0 0 1rem 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.detail-section h3::before {
    content: '';
    width: 4px;
    height: 1.125rem;
    background: var(--primary);
    border-radius: 2px;
}

.detail-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.detail-item {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.detail-item label {
    font-weight: 600;
    color: var(--text-secondary);
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.detail-item span {
    color: var(--text-primary);
    font-size: 0.875rem;
}

.comment-full {
    background: var(--bg-secondary);
    padding: 1rem;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    line-height: 1.6;
    color: var(--text-primary);
}

/* Buttons */
.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border-radius: 0.5rem;
    font-weight: 600;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.875rem;
}

.btn-primary {
    background: var(--primary);
    color: #fff;
}

.btn-primary:hover {
    background: var(--primary-dark);
    transform: translateY(-1px);
}

.btn-secondary {
    background: var(--bg-secondary);
    color: var(--text-primary);
    border: 1px solid var(--border-light);
}

.btn-secondary:hover {
    background: var(--border-light);
}

/* Form Styles */
.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: var(--text-primary);
    font-size: 0.875rem;
}

.form-control {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid var(--border-light);
    border-radius: 0.5rem;
    font-size: 0.875rem;
    transition: border-color 0.3s ease;
    background: #fff;
}

.form-control:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-control:invalid {
    border-color: #ef4444;
}

/* Star Rating */
.rating-input {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.star-rating {
    display: flex;
    flex-direction: row-reverse;
    gap: 0.25rem;
}

.star-rating input[type="radio"] {
    display: none;
}

.star-rating .star {
    font-size: 1.5rem;
    color: #ddd;
    cursor: pointer;
    transition: color 0.2s ease;
}

.star-rating .star:hover,
.star-rating .star:hover ~ .star,
.star-rating input[type="radio"]:checked ~ .star {
    color: #DC2626; /* Red for Super Admin ratings */
}

.rating-text {
    font-size: 0.875rem;
    color: var(--text-secondary);
    font-weight: 500;
}

/* Rating Summary Styles */
.rating-summary {
    background: var(--bg-secondary);
    border-radius: 0.5rem;
    padding: 1rem;
    margin-top: 0.5rem;
}

.summary-stats {
    display: flex;
    gap: 2rem;
    margin-bottom: 1rem;
}

.stat-item {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.stat-label {
    font-size: 0.75rem;
    color: var(--text-secondary);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.stat-value {
    font-size: 1rem;
    font-weight: 700;
    color: var(--text-primary);
}

.recent-ratings {
    border-top: 1px solid var(--border-light);
    padding-top: 1rem;
}

.recent-rating-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
    border-bottom: 1px solid var(--border-light);
}

.recent-rating-item:last-child {
    border-bottom: none;
}

.rating-info {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.rating-stars {
    display: flex;
    gap: 0.125rem;
}

.rating-stars .fas.fa-star {
    color: #ddd;
    font-size: 0.75rem;
}

.rating-stars .fas.fa-star.filled {
    color: #DC2626; /* Red for Super Admin ratings */
}

.rating-date {
    font-size: 0.75rem;
    color: var(--text-secondary);
}

.rating-comment {
    font-size: 0.875rem;
    color: var(--text-primary);
    max-width: 200px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* Rating Display */
.rating-display {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.rating-display .fas.fa-star {
    color: #ddd;
    font-size: 0.875rem;
}

.rating-display .fas.fa-star.filled {
    color: #DC2626;
}

.rating-number {
    font-weight: 600;
    color: var(--text-primary);
    font-size: 0.875rem;
}

/* Responsive */
@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }

    .employee-ratings-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

function openRateEmployeeModal() {

    const form = document.getElementById('rateEmployeeForm');
    form.reset();

    const ratingText = document.querySelector('.rating-text');
    if (ratingText) {
        ratingText.textContent = 'Select a rating';
    }

    const starInputs = document.querySelectorAll('.star-rating input[type="radio"]');
    starInputs.forEach(input => {
        input.checked = false;
    });

    // Hide employee rating summary
    const summaryDiv = document.getElementById('employee-rating-summary');
    if (summaryDiv) {
        summaryDiv.style.display = 'none';
    }

    // Show modal
    document.getElementById('rateEmployeeModal').style.display = 'block';
}

function submitRating() {
    const form = document.getElementById('rateEmployeeForm');
    const formData = new FormData(form);

    // Validate form
    if (!formData.get('employee_id')) {
        showNotification('Please select an employee', 'error');
        return;
    }

    if (!formData.get('rating')) {
        showNotification('Please select a rating', 'error');
        return;
    }

    // Disable submit button to prevent double submission
    const submitBtn = document.querySelector('#rateEmployeeModal .btn-primary');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';

    // Submit form
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            closeModal('rateEmployeeModal');
            // Show success message
            showNotification('Rating submitted successfully!', 'success');
            // Refresh the page to show new rating
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            showNotification(data.message || 'Error submitting rating', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error submitting rating. Please try again.', 'error');
    })
    .finally(() => {
        // Re-enable submit button
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
}

function viewRating(id) {
    // Sample data - in real app, fetch from server
    const ratingData = {
        id: id,
        employeeName: 'John Doe',
        employeeEmail: 'john.doe@company.com',
        employeeDept: 'Development',
        rating: 4,
        raterName: 'Sarah Wilson',
        raterRole: 'super_admin', // This would come from the server
        ratingDate: 'January 15, 2024',
        comment: 'Excellent work on the project. Very professional and dedicated to the task. Great communication skills and always meets deadlines.'
    };

    // Populate modal
    document.getElementById('modal-employee-name').textContent = ratingData.employeeName;
    document.getElementById('modal-employee-email').textContent = ratingData.employeeEmail;
    document.getElementById('modal-employee-dept').textContent = ratingData.employeeDept;
    document.getElementById('modal-rater-name').textContent = ratingData.raterName;
    document.getElementById('modal-rating-date').textContent = ratingData.ratingDate;
    document.getElementById('modal-comment').innerHTML = `<p>${ratingData.comment}</p>`;

    // Update rating stars
    const ratingDisplay = document.getElementById('modal-rating');
    ratingDisplay.innerHTML = '';
    for (let i = 1; i <= 5; i++) {
        const star = document.createElement('i');
        star.className = `fas fa-star ${i <= ratingData.rating ? 'filled' : 'empty'}`;
        if (i <= ratingData.rating) {
            // Use role-based colors
            const roleColor = ratingData.raterRole === 'super_admin' ? '#DC2626' :
                             ratingData.raterRole === 'admin' ? '#fbbf24' :
                             ratingData.raterRole === 'ba' ? '#f97316' :
                             ratingData.raterRole === 'qa' ? '#22c55e' : '#6b7280';
            star.style.color = roleColor;
        }
        ratingDisplay.appendChild(star);
    }
    const ratingNumber = document.createElement('span');
    ratingNumber.className = 'rating-number';
    ratingNumber.textContent = `(${ratingData.rating}/5)`;
    ratingDisplay.appendChild(ratingNumber);

    // Show modal
    document.getElementById('viewRatingModal').style.display = 'block';
}

function exportRatings() {
    console.log('Exporting ratings data...');
}

document.addEventListener('DOMContentLoaded', function() {
    const starInputs = document.querySelectorAll('.star-rating input[type="radio"]');
    const ratingText = document.querySelector('.rating-text');

    starInputs.forEach(input => {
        input.addEventListener('change', function() {
            const rating = this.value;
            const ratingLabels = {
                '1': 'Poor',
                '2': 'Fair',
                '3': 'Good',
                '4': 'Very Good',
                '5': 'Excellent'
            };
            ratingText.textContent = `${ratingLabels[rating]} (${rating}/5)`;
        });
    });
});

// Load employee ratings when employee is selected
function loadEmployeeRatings(employeeId) {
    if (!employeeId) {
        document.getElementById('employee-rating-summary').style.display = 'none';
        return;
    }

    // Show loading state
    const summaryDiv = document.getElementById('employee-rating-summary');
    summaryDiv.style.display = 'block';
    document.getElementById('avg-rating').textContent = 'Loading...';
    document.getElementById('total-ratings').textContent = 'Loading...';
    document.getElementById('recent-ratings-list').innerHTML = '<p>Loading recent ratings...</p>';

    // Fetch employee ratings
    fetch(`/super_admin/employee_ratings/employee/${employeeId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update summary stats
                document.getElementById('avg-rating').textContent = data.average_rating + '/5';
                document.getElementById('total-ratings').textContent = data.total_ratings;

                // Update recent ratings
                const recentRatingsList = document.getElementById('recent-ratings-list');
                if (data.recent_ratings && data.recent_ratings.length > 0) {
                    recentRatingsList.innerHTML = data.recent_ratings.map(rating => `
                        <div class="recent-rating-item">
                            <div class="rating-info">
                                <div class="rating-stars">
                                    ${Array.from({length: 5}, (_, i) => {
                                        const isFilled = i < rating.rating;
                                        const roleColor = rating.rater_role === 'super_admin' ? '#DC2626' :
                                                         rating.rater_role === 'admin' ? '#fbbf24' :
                                                         rating.rater_role === 'ba' ? '#f97316' :
                                                         rating.rater_role === 'qa' ? '#22c55e' : '#6b7280';
                                        return `<i class="fas fa-star ${isFilled ? 'filled' : ''}" style="color: ${isFilled ? roleColor : '#ddd'};"></i>`;
                                    }).join('')
                                }
                                </div>
                                <div class="rating-date">${rating.created_at}</div>
                            </div>
                            <div class="rating-comment" title="${rating.comment || 'No comment'}">
                                ${rating.comment || 'No comment'}
                            </div>
                        </div>
                    `).join('');
                } else {
                    recentRatingsList.innerHTML = '<p>No recent ratings found.</p>';
                }
            } else {
                summaryDiv.style.display = 'none';
            }
        })
        .catch(error => {
            console.error('Error loading employee ratings:', error);
            summaryDiv.style.display = 'none';
        });
}

// Notification function
function showNotification(message, type = 'info') {

    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 1rem 1.5rem;
        border-radius: 0.5rem;
        color: white;
        font-weight: 600;
        z-index: 10000;
        animation: slideIn 0.3s ease;
    `;

    const colors = {
        success: '#10b981',
        error: '#ef4444',
        warning: '#f59e0b',
        info: '#3b82f6'
    };
    notification.style.backgroundColor = colors[type] || colors.info;

    notification.textContent = message;
    document.body.appendChild(notification);

    // Remove notification after 3 seconds
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes slideOut {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
`;
document.head.appendChild(style);

window.onclick = function(event) {
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
}
</script>
</div>
@endsection
