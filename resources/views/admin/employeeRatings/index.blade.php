@extends('layouts.admin')

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
    <style>

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
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

        /* Stats Section */
        .stats-section {
            margin-bottom: 2rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .stat-card {
            background: #fff;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 16px 0 rgba(220,38,38,0.07);
            border: none;
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(220,38,38,0.1);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            background: var(--gradient-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
        }

        .stat-content h3 {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text-secondary);
            margin: 0 0 0.5rem 0;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
        }

        .stat-content p {
            font-size: 0.875rem;
            color: var(--text-secondary);
            margin: 0;
        }

        /* Employee Rating Cards */
        .employee-ratings-grid {
            display: grid;
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .employee-rating-card {
            background: #fff;
            border-radius: 12px;
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
            margin-bottom: 1.5rem;
            gap: 1rem;
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
            flex-shrink: 0;
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
            margin-bottom: 0.25rem;
        }

        .employee-email {
            font-size: 0.875rem;
            color: var(--text-light);
        }

        .employee-actions {
            display: flex;
            gap: 0.5rem;
            flex-shrink: 0;
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
            margin-bottom: 0.5rem;
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

        .rating-stats {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .rating-percentage {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .rating-details {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 0.25rem;
        }

        .rating-number {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .rating-count {
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: var(--text-secondary);
            background: #fff;
            border-radius: 12px;
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
            margin-bottom: 1.5rem;
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

        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.75rem;
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
            color: #fbbf24; /* Yellow for Admin ratings */
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
            color: #fbbf24; /* Yellow for Admin ratings */
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


        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }

            .employee-ratings-grid {
                grid-template-columns: 1fr;
            }

            .employee-info-section {
                flex-direction: column;
                align-items: flex-start;
                text-align: center;
            }

            .employee-actions {
                width: 100%;
                justify-content: center;
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


            document.getElementById('rateEmployeeModal').style.display = 'block';
        }

        function rateEmployee(employeeId) {

            document.getElementById('employee_id').value = employeeId;

            loadEmployeeRatings(employeeId);

            openRateEmployeeModal();
        }

        function viewEmployeeRatings(employeeId) {

            const modal = document.getElementById('viewEmployeeRatingsModal');
            const content = document.getElementById('employee-ratings-content');
            content.innerHTML = '<p>Loading employee ratings...</p>';
            modal.style.display = 'block';

            fetch(`/admin/employeeRatings/employee/${employeeId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        content.innerHTML = `
                    <div class="employee-rating-details">
                        <h3>${data.employee_name}</h3>
                        <p>${data.employee_email} - ${data.employee_department}</p>
                        <div class="rating-summary">
                            <div class="summary-stats">
                                <div class="stat-item">
                                    <span class="stat-label">Average Rating:</span>
                                    <span class="stat-value">${data.average_rating}/5</span>
                                </div>
                                <div class="stat-item">
                                    <span class="stat-label">Total Ratings:</span>
                                    <span class="stat-value">${data.total_ratings}</span>
                                </div>
                            </div>
                        </div>
                        <div class="ratings-list">
                            ${data.ratings.map(rating => `
                                <div class="rating-item">
                                    <div class="rating-header">
                                        <div class="rating-stars">
                                            ${Array.from({length: 5}, (_, i) => {
                            const isFilled = i < rating.rating;
                            return `<i class="fas fa-star ${isFilled ? 'filled' : ''}"></i>`;
                        }).join('')}
                                        </div>
                                        <div class="rating-meta">
                                            <span class="rater-name">${rating.rater_name}</span>
                                            <span class="rating-date">${rating.created_at}</span>
                                        </div>
                                    </div>
                                    ${rating.comment ? `<div class="rating-comment">${rating.comment}</div>` : ''}
                                </div>
                            `).join('')}
                        </div>
                    </div>
                `;
                    } else {
                        content.innerHTML = '<p>Error loading employee ratings.</p>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    content.innerHTML = '<p>Error loading employee ratings.</p>';
                });
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
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        closeModal('rateEmployeeModal');
                        showNotification(data.message, 'success');

                        setTimeout(() => {
                            location.reload();
                        }, 800);
                    } else {
                        showNotification(data.message || 'Error submitting rating', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Error submitting rating. Please try again.', 'error');
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                });
        }


        function exportRatings() {
            console.log('Exporting ratings data...');
            showNotification('Export feature coming soon!', 'info');
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


        function loadEmployeeRatings(employeeId) {
            if (!employeeId) {
                document.getElementById('employee-rating-summary').style.display = 'none';
                return;
            }

            const summaryDiv = document.getElementById('employee-rating-summary');
            summaryDiv.style.display = 'block';
            document.getElementById('avg-rating').textContent = 'Loading...';
            document.getElementById('total-ratings').textContent = 'Loading...';
            document.getElementById('recent-ratings-list').innerHTML = '<p>Loading recent ratings...</p>';

            fetch(`/admin/employeeRatings/employee/${employeeId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('avg-rating').textContent = data.average_rating + '/5';
                        document.getElementById('total-ratings').textContent = data.total_ratings;

                        const recentRatingsList = document.getElementById('recent-ratings-list');
                        if (data.ratings && data.ratings.length > 0) {
                            recentRatingsList.innerHTML = data.ratings.slice(0, 3).map(rating => `
                        <div class="recent-rating-item">
                            <div class="rating-info">
                                <div class="rating-stars">
                                    ${Array.from({length: 5}, (_, i) => {
                                const isFilled = i < rating.rating;
                                return `<i class="fas fa-star ${isFilled ? 'filled' : ''}"></i>`;
                            }).join('')}
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

            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);
        }

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
