@extends('layouts.super_admin')
<link rel="stylesheet" href="{{ asset('css/SuperAdmin/employeeRatings.css') }}">

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

        <div class="role-notice">
            <div class="role-notice-title">
                <i class="fas fa-info-circle" style="margin-right: 0.5rem;"></i>
                Access Level: Super Admin
            </div>
            
        </div>

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
    <style>
        .ratings-pagination-wrapper {
            width: 100%;
            grid-column: 1 / -1;
            display: flex;
            justify-content: center; /* Center content */
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

                        @php
                            // Get unique ratings keyed by rater to show latest rating per person
                            $uniqueRatings = $employeeRatingGroup->sortByDesc('created_at')->unique('rated_by')->filter(fn($r) => $r->rater);
                            $displayRatings = $uniqueRatings->take(4);
                            $remainingCount = $uniqueRatings->count() - 4;
                        @endphp
                        <div class="raters-info" style="margin-top: 8px; display: flex; align-items: center;">
                            <span style="font-size: 0.75rem; color: #64748b; margin-right: 8px;">Rated by:</span>
                            <div class="avatar-stack" style="display: flex;">
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
                    <div class="rating-percentage">{{ round($percentage) }}%</div>
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
                            <label>Rated By: </label>
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


    if (!formData.get('employee_id')) {
        showNotification('Please select an employee', 'error');
        return;
    }

    if (!formData.get('rating')) {
        showNotification('Please select a rating', 'error');
        return;
    }

    const submitBtn = document.querySelector('#rateEmployeeModal .btn-primary');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';

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

            showNotification('Rating submitted successfully!', 'success');
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

    document.getElementById('viewRatingModal').style.display = 'block';
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


let currentEmployeeId = null;

function loadEmployeeRatings(employeeId, page = 1) {
    if (!employeeId) {
        document.getElementById('employee-rating-summary').style.display = 'none';
        return;
    }

    currentEmployeeId = employeeId;
    const summaryDiv = document.getElementById('employee-rating-summary');
    summaryDiv.style.display = 'block';
    
    document.getElementById('recent-ratings-list').innerHTML = '<p class="text-center text-gray-500 py-2">Loading...</p>';

    if (page === 1) {
        document.getElementById('avg-rating').textContent = '...';
        document.getElementById('total-ratings').textContent = '...';
    }

    // Fetch employee ratings
    fetch(`/super_admin/employee_ratings/employee/${employeeId}?page=${page}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update summary stats
                document.getElementById('avg-rating').textContent = data.average_rating + '/5';
                document.getElementById('total-ratings').textContent = data.total_ratings;

                // Update recent ratings
                const recentRatingsList = document.getElementById('recent-ratings-list');
                const ratings = data.recent_ratings || data.ratings || [];

                if (ratings.length > 0) {
                    let html = ratings.map(rating => `
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
                                <span style="font-size:0.8rem;color:#666;margin-left:5px;">(${rating.rating}/5)</span>
                                </div>
                                <div class="rating-meta">
                                    <span class="rater-name" style="font-size:0.8rem;color:#666;margin-right:10px;">By: ${rating.rater_name || 'Unknown'}</span>
                                    <span class="rating-date" style="font-size:0.8rem;color:#999;">${rating.created_at}</span>
                                </div>
                            </div>
                            <div class="rating-comment" title="${rating.comment || 'No comment'}">
                                ${rating.comment || 'No comment'}
                            </div>
                        </div>
                    `).join('');

                    // Pagination Controls
                    if (data.pagination && data.pagination.last_page > 1) {
                        html += `
                            <div class="pagination-controls" style="display:flex; justify-content:center; gap:10px; margin-top:10px; align-items:center;">
                                <button type="button" class="btn btn-sm btn-secondary" 
                                    ${data.pagination.current_page === 1 ? 'disabled style="opacity:0.5;cursor:not-allowed;"' : ''}
                                    onclick="loadEmployeeRatings('${employeeId}', ${data.pagination.current_page - 1})">
                                    <i class="fas fa-chevron-left"></i> Prev
                                </button>
                                <span style="font-size:0.85rem; color:#6b7280;">
                                    Page ${data.pagination.current_page} of ${data.pagination.last_page}
                                </span>
                                <button type="button" class="btn btn-sm btn-secondary" 
                                    ${!data.pagination.has_more ? 'disabled style="opacity:0.5;cursor:not-allowed;"' : ''}
                                    onclick="loadEmployeeRatings('${employeeId}', ${data.pagination.current_page + 1})">
                                    Next <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>
                        `;
                    }
                    recentRatingsList.innerHTML = html;
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
