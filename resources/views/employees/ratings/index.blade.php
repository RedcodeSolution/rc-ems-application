@extends('layouts.employee')

@section('title', 'Employee Ratings')

@section('content')
<style>
    /* Main Container */
    .ratings-container { 
        min-height: 100vh; 
        background-color: var(--primary-light); 
        padding: 1.5rem; 
        color: var(--text-primary);
    }
    
    /* Header Section */
    .ratings-header { 
        background: #fff; 
        border-radius: 0.75rem; 
        box-shadow: 0 2px 16px 0 rgba(220,38,38,0.07); 
        padding: 1.5rem; 
        margin-bottom: 1.5rem; 
        border: none;
    }
    .ratings-title { 
        font-size: 1.875rem; 
        font-weight: 700; 
        color: var(--text-primary); 
        margin-bottom: 0.5rem; 
    }
    .ratings-subtitle { 
        color: var(--text-secondary); 
        margin-bottom: 1rem;
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
    
    /* Rate Employee Button (only for BA and QA) */
    .rate-employee-btn { 
        background: var(--primary); 
        color: white; 
        padding: 0.75rem 1.5rem; 
        border-radius: 0.5rem; 
        border: none; 
        font-weight: 600; 
        cursor: pointer; 
        margin-bottom: 1rem;
        box-shadow: 0 4px 6px -1px rgba(220,38,38,0.2);
        transition: all 0.3s ease;
    }
    .rate-employee-btn:hover { 
        transform: translateY(-1px); 
        box-shadow: 0 10px 15px -3px rgba(220,38,38,0.3); 
        background: var(--primary-dark);
    }
    
    /* Rating Modal Styles */
    .rating-modal { 
        display: none; 
        position: fixed; 
        z-index: 1000; 
        left: 0; 
        top: 0; 
        width: 100%; 
        height: 100%; 
        background-color: rgba(0,0,0,0.5); 
    }
    .rating-modal-content { 
        background-color: #fff; 
        margin: 5% auto; 
        padding: 2rem; 
        border-radius: 0.75rem; 
        width: 90%; 
        max-width: 500px; 
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); 
        border: none;
    }
    .rating-modal-header { 
        display: flex; 
        justify-content: space-between; 
        align-items: center; 
        margin-bottom: 1.5rem; 
    }
    .rating-modal-title { 
        font-size: 1.5rem; 
        font-weight: 600; 
        color: var(--text-primary); 
    }
    .rating-modal-close { 
        background: none; 
        border: none; 
        font-size: 1.5rem; 
        cursor: pointer; 
        color: var(--text-secondary); 
    }
    .rating-form-group { 
        margin-bottom: 1.5rem; 
    }
    .rating-form-label { 
        display: block; 
        font-weight: 500; 
        color: var(--text-primary); 
        margin-bottom: 0.5rem; 
    }
    .rating-form-select { 
        width: 100%; 
        padding: 0.75rem; 
        border: 1px solid var(--border-light); 
        border-radius: 0.375rem; 
        background-color: #fff; 
        color: var(--text-primary);
    }
    .rating-form-textarea { 
        width: 100%; 
        padding: 0.75rem; 
        border: 1px solid var(--border-light); 
        border-radius: 0.375rem; 
        min-height: 100px; 
        resize: vertical; 
        background-color: #fff;
        color: var(--text-primary);
    }
    .star-rating { 
        display: flex; 
        flex-direction: row-reverse; 
        justify-content: flex-end; 
    }
    .star-rating input { 
        display: none; 
    }
    .star-rating label { 
        cursor: pointer; 
        font-size: 1.5rem; 
        color: #ddd; 
        margin-right: 0.25rem; 
    }
    .star-rating input:checked ~ label { 
        color: #fbbf24; 
    }
    .star-rating label:hover, 
    .star-rating label:hover ~ label { 
        color: #fbbf24; 
    }
    .rating-form-buttons { 
        display: flex; 
        gap: 1rem; 
        justify-content: flex-end; 
        margin-top: 2rem; 
    }
    .btn { 
        padding: 0.75rem 1.5rem; 
        border-radius: 0.375rem; 
        font-weight: 500; 
        cursor: pointer; 
        border: none; 
    }
    .btn-primary { 
        background-color: var(--primary); 
        color: white; 
    }
    .btn-secondary { 
        background-color: var(--text-secondary); 
        color: white; 
    }
    .btn:hover { 
        opacity: 0.9; 
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
</style>

<div class="ratings-container">
    <!-- Header Section -->
    <div class="ratings-header">
        <h1 class="ratings-title">
            <i class="fas fa-star" style="color: #fbbf24; margin-right: 0.75rem;"></i>
            Employee Performance Ratings
        </h1>
        <p class="ratings-subtitle">Visual overview of employee ratings across the organization</p>
        
        <!-- Role-based Access Notice -->
        <div class="role-notice">
            <div class="role-notice-title">
                <i class="fas fa-info-circle" style="margin-right: 0.5rem;"></i>
                Access Level: {{ ucfirst($employee->role) }}
            </div>
            <div class="role-notice-text">
                @if(in_array($employee->role, ['ba', 'qa']))
                    ✅ You can view all employee ratings and rate other employees
                @elseif($employee->role === 'developer')
                    👁️ You can view all employee ratings (rating functionality not available for developers)
                @else
                    👁️ You can view all employee ratings
                @endif
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

        <!-- Rate Employee Button (only for BA and QA) -->
        @if(in_array($employee->role, ['ba', 'qa']))
            <button class="rate-employee-btn" onclick="openRateEmployeeModal()">
                <i class="fas fa-star" style="margin-right: 0.5rem;"></i>
                Rate Employee
            </button>
        @endif
    </div>

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
                        <div class="employee-avatar">
                            {{ strtoupper(substr($employeeData->employee_name ?? 'E', 0, 1)) }}
                        </div>
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
</div>

<!-- Rating Modal (only for BA and QA) -->
@if(in_array($employee->role, ['ba', 'qa']))
<div id="ratingModal" class="rating-modal">
    <div class="rating-modal-content">
        <div class="rating-modal-header">
            <h3 class="rating-modal-title">Rate Employee</h3>
            <button class="rating-modal-close" onclick="closeRateEmployeeModal()">&times;</button>
        </div>
        
        <form id="ratingForm">
            @csrf
            <div class="rating-form-group">
                <label class="rating-form-label">Select Employee:</label>
                <select name="employee_id" class="rating-form-select" required>
                    <option value="">Choose an employee to rate...</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->employee_id }}">{{ $emp->employee_name }} ({{ $emp->role }})</option>
                    @endforeach
                </select>
            </div>
            
            <div class="rating-form-group">
                <label class="rating-form-label">Rating:</label>
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
            </div>
            
            <div class="rating-form-group">
                <label class="rating-form-label">Comment (Optional):</label>
                <textarea name="comment" class="rating-form-textarea" placeholder="Add your feedback here..."></textarea>
            </div>
            
            <div class="rating-form-buttons">
                <button type="button" class="btn btn-secondary" onclick="closeRateEmployeeModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Submit Rating</button>
            </div>
        </form>
    </div>
</div>
@endif

<script>
// Modal functions (only for BA and QA)
@if(in_array($employee->role, ['ba', 'qa']))
function openRateEmployeeModal() {
    document.getElementById('ratingModal').style.display = 'block';
}

function closeRateEmployeeModal() {
    document.getElementById('ratingModal').style.display = 'none';
    document.getElementById('ratingForm').reset();
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('ratingModal');
    if (event.target === modal) {
        closeRateEmployeeModal();
    }
}

// Handle form submission
document.getElementById('ratingForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('{{ route("employee.ratings.store") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            closeRateEmployeeModal();
            location.reload(); // Refresh to show new rating
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while submitting the rating.');
    });
});
@endif
</script>
@endsection
