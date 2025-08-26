@extends('layouts.admin')

@section('title', 'Edit Employee Rating')

@section('content')
<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-edit"></i> Edit Employee Rating</h2>
        <div style="display: flex; gap: 0.5rem;">
            <a href="{{ route('admin.employeeRatings.show', $employeeRating) }}" class="btn btn-secondary">
                <i class="fas fa-eye"></i> View Details
            </a>
            <a href="{{ route('admin.employeeRatings.index') }}" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i> Back to Ratings
            </a>
        </div>
    </div>
    <div class="card-body">
        <!-- Current Rating Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h3><i class="fas fa-info-circle"></i> Current Rating Information</h3>
            </div>
            <div class="card-body">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
                    <div>
                        <h4 style="margin-bottom: 1rem; color: var(--text-primary);">Employee Details</h4>
                        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                            <div style="width: 3rem; height: 3rem; background: var(--gradient-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 1.25rem;">
                                {{ substr($employeeRating->employee->employee_name, 0, 1) }}
                            </div>
                            <div>
                                <h5 style="font-weight: 700; margin-bottom: 0.25rem;">{{ $employeeRating->employee->employee_name }}</h5>
                                <p style="color: var(--text-secondary); margin-bottom: 0.25rem;">{{ $employeeRating->employee->email ?? 'No email' }}</p>
                                <span style="padding: 0.25rem 0.5rem; background: rgba(59, 130, 246, 0.1); color: #1e40af; border-radius: 9999px; font-size: 0.75rem; font-weight: 500;">
                                    {{ $employeeRating->employee->department->name ?? 'No Department' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <h4 style="margin-bottom: 1rem; color: var(--text-primary);">Current Rating</h4>
                        <div style="text-align: center;">
                            <div style="display: flex; justify-content: center; gap: 0.25rem; margin-bottom: 0.5rem;">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star" style="font-size: 1.5rem; color: {{ $i <= $employeeRating->rating ? '#fbbf24' : '#d1d5db' }};"></i>
                                @endfor
                            </div>
                            <p style="font-size: 1.25rem; font-weight: 700; margin-bottom: 0.25rem;">{{ $employeeRating->rating }}/5 Stars</p>
                            <p style="color: var(--text-secondary); font-size: 0.875rem;">Rated on {{ $employeeRating->created_at->format('F d, Y \a\t g:i A') }}</p>
                        </div>
                    </div>
                </div>
                
                @if($employeeRating->comment)
                    <div style="margin-top: 1rem;">
                        <h4 style="margin-bottom: 0.5rem; color: var(--text-primary);">Current Comment</h4>
                        <div style="background: var(--gray-100); padding: 1rem; border-radius: 0.5rem; border-left: 4px solid var(--primary);">
                            <p style="margin: 0; line-height: 1.6;">{{ $employeeRating->comment }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Edit Rating Form -->
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-edit"></i> Update Rating</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.employeeRatings.update', $employeeRating) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; margin-bottom: 2rem;">
                        <div class="form-group">
                            <label for="employee_id" class="form-label">Employee</label>
                            <select name="employee_id" id="employee_id" class="form-select" required>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->employee_id }}" {{ $employeeRating->employee_id == $employee->employee_id ? 'selected' : '' }}>
                                        {{ $employee->employee_name }} - {{ $employee->department->name ?? 'No Department' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('employee_id')
                                <div class="alert alert-error mt-2">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Rating</label>
                            <div class="star-rating">
                                @for($i=1; $i<=5; $i++)
                                    <input type="radio" name="rating" id="star{{ $i }}" value="{{ $i }}" {{ $employeeRating->rating == $i ? 'checked' : '' }}>
                                    <label for="star{{ $i }}" class="star-label">
                                        <i class="fas fa-star"></i>
                                    </label>
                                @endfor
                            </div>
                            <p style="font-size: 0.875rem; color: var(--text-secondary); margin-top: 0.5rem;">Click on a star to select the rating</p>
                            @error('rating')
                                <div class="alert alert-error mt-2">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="comment" class="form-label">Comment</label>
                        <textarea name="comment" id="comment" class="form-input" rows="4" placeholder="Enter your updated feedback about the employee's performance...">{{ old('comment', $employeeRating->comment) }}</textarea>
                        <p style="font-size: 0.875rem; color: var(--text-secondary); margin-top: 0.5rem;">Provide detailed feedback to help the employee improve their performance.</p>
                        @error('comment')
                            <div class="alert alert-error mt-2">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    <div style="display: flex; gap: 1rem; flex-wrap: wrap; margin-top: 2rem;">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Rating
                        </button>
                        <a href="{{ route('admin.employeeRatings.show', $employeeRating) }}" class="btn btn-secondary">
                            <i class="fas fa-eye"></i> View Details
                        </a>
                        <a href="{{ route('admin.employeeRatings.index') }}" class="btn btn-warning">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                        <form action="{{ route('admin.employeeRatings.destroy', $employeeRating) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this rating? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash"></i> Delete Rating
                            </button>
                        </form>
                    </div>
                </form>
            </div>
        </div>

        <!-- Rating History -->
        <div class="card mt-4">
            <div class="card-header">
                <h3><i class="fas fa-history"></i> Rating History for {{ $employeeRating->employee->employee_name }}</h3>
            </div>
            <div class="card-body">
                @php
                    $employeeRatings = \App\Models\EmployeeRating::where('employee_id', $employeeRating->employee_id)
                        ->with(['rater'])
                        ->orderBy('created_at', 'desc')
                        ->get();
                @endphp
                
                @if($employeeRatings->count() > 1)
                    <div style="display: grid; gap: 1rem;">
                        @foreach($employeeRatings as $rating)
                            @if($rating->id != $employeeRating->id)
                                <div style="border: 1px solid var(--divider); border-radius: 0.5rem; padding: 1rem; background: var(--gray-50);">
                                    <div style="display: flex; justify-content: between; align-items: center; margin-bottom: 0.5rem;">
                                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star" style="color: {{ $i <= $rating->rating ? '#fbbf24' : '#d1d5db' }}; font-size: 0.875rem;"></i>
                                            @endfor
                                            <span style="font-weight: 600;">{{ $rating->rating }}/5</span>
                                        </div>
                                        <span style="font-size: 0.875rem; color: var(--text-secondary);">
                                            {{ $rating->created_at->format('M d, Y') }}
                                        </span>
                                    </div>
                                    @if($rating->comment)
                                        <p style="font-size: 0.875rem; margin: 0;">{{ Str::limit($rating->comment, 150) }}</p>
                                    @endif
                                    <div style="margin-top: 0.5rem; font-size: 0.75rem; color: var(--text-secondary);">
                                        Rated by: {{ $rating->rater->name ?? 'Admin' }}
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @else
                    <p style="color: var(--text-secondary); text-align: center;">No previous ratings found for this employee.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.star-rating {
    display: flex;
    flex-direction: row-reverse;
    gap: 0.25rem;
}

.star-rating input[type="radio"] {
    display: none;
}

.star-label {
    cursor: pointer;
    font-size: 1.5rem;
    color: #d1d5db;
    transition: color 0.2s;
}

.star-rating input[type="radio"]:checked ~ .star-label,
.star-rating input[type="radio"]:checked ~ .star-label ~ .star-label {
    color: #fbbf24;
}

.star-label:hover,
.star-label:hover ~ .star-label {
    color: #fbbf24;
}

.grid {
    display: grid;
}

.gap-1 {
    gap: 0.25rem;
}

.gap-2 {
    gap: 0.5rem;
}

.mt-4 {
    margin-top: 1rem;
}

.mb-4 {
    margin-bottom: 1rem;
}

.mb-1 {
    margin-bottom: 0.25rem;
}

.mb-2 {
    margin-bottom: 0.5rem;
}

.mt-2 {
    margin-top: 0.5rem;
}

.text-center {
    text-align: center;
}

.flex {
    display: flex;
}

.flex-wrap {
    flex-wrap: wrap;
}

.items-center {
    align-items: center;
}

.justify-center {
    justify-content: center;
}

.justify-between {
    justify-content: space-between;
}

.inline {
    display: inline;
}

.w-3 {
    width: 0.75rem;
}

.h-3 {
    height: 0.75rem;
}

.text-sm {
    font-size: 0.875rem;
}

.text-lg {
    font-size: 1.125rem;
}

.font-bold {
    font-weight: 700;
}

.font-semibold {
    font-weight: 600;
}

.rounded-full {
    border-radius: 9999px;
}

.rounded-lg {
    border-radius: 0.5rem;
}

.p-1 {
    padding: 0.25rem;
}

.px-2 {
    padding-left: 0.5rem;
    padding-right: 0.5rem;
}

.py-1 {
    padding-top: 0.25rem;
    padding-bottom: 0.25rem;
}

.p-4 {
    padding: 1rem;
}

.border-left-4 {
    border-left-width: 4px;
}

.border-solid {
    border-style: solid;
}

.border-primary {
    border-color: var(--primary);
}

@media (max-width: 768px) {
    .grid-template-columns-1fr-1fr {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
// Star rating functionality
document.addEventListener('DOMContentLoaded', function() {
    const starLabels = document.querySelectorAll('.star-label');
    starLabels.forEach((label, index) => {
        label.addEventListener('click', function() {
            // Uncheck all stars
            document.querySelectorAll('input[name="rating"]').forEach(input => {
                input.checked = false;
            });
            // Check the clicked star and all stars before it
            for (let i = 0; i <= index; i++) {
                document.getElementById(`star${5-i}`).checked = true;
            }
        });
    });
});

// Form validation
document.querySelector('form').addEventListener('submit', function(e) {
    const rating = document.querySelector('input[name="rating"]:checked');
    if (!rating) {
        e.preventDefault();
        alert('Please select a rating before submitting.');
        return false;
    }
});
</script>
@endsection 