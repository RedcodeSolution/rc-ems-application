@extends('layouts.admin')

@section('title', 'Employee Rating Details')

@section('content')
<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-star"></i> Employee Rating Details</h2>
        <div style="display: flex; gap: 0.5rem;">
            <a href="{{ route('admin.employeeRatings.edit', $employeeRating) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit Rating
            </a>
            <a href="{{ route('admin.employeeRatings.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Ratings
            </a>
        </div>
    </div>
    <div class="card-body">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
            <!-- Employee Information -->
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-user"></i> Employee Information</h3>
                </div>
                <div class="card-body">
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
                        <div style="width: 4rem; height: 4rem; background: var(--gradient-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 1.5rem;">
                            {{ substr($employeeRating->employee->name, 0, 1) }}
                        </div>
                        <div>
                            <h4 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 0.25rem;">{{ $employeeRating->employee->name }}</h4>
                            <p style="color: var(--text-secondary); margin-bottom: 0.25rem;">{{ $employeeRating->employee->email }}</p>
                            <span style="padding: 0.25rem 0.75rem; background: rgba(59, 130, 246, 0.1); color: #1e40af; border-radius: 9999px; font-size: 0.875rem; font-weight: 500;">
                                {{ $employeeRating->employee->department->name ?? 'No Department' }}
                            </span>
                        </div>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div>
                            <label style="font-weight: 600; color: var(--text-secondary); font-size: 0.875rem;">Employee ID</label>
                            <p style="font-weight: 600;">{{ $employeeRating->employee->id }}</p>
                        </div>
                        <div>
                            <label style="font-weight: 600; color: var(--text-secondary); font-size: 0.875rem;">Position</label>
                            <p style="font-weight: 600;">{{ $employeeRating->employee->position ?? 'Not specified' }}</p>
                        </div>
                        <div>
                            <label style="font-weight: 600; color: var(--text-secondary); font-size: 0.875rem;">Contact</label>
                            <p style="font-weight: 600;">{{ $employeeRating->employee->contact_no ?? 'Not specified' }}</p>
                        </div>
                        <div>
                            <label style="font-weight: 600; color: var(--text-secondary); font-size: 0.875rem;">Hire Date</label>
                            <p style="font-weight: 600;">{{ $employeeRating->employee->hire_date ? $employeeRating->employee->hire_date->format('M d, Y') : 'Not specified' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rating Information -->
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-star"></i> Rating Information</h3>
                </div>
                <div class="card-body">
                    <div style="text-align: center; margin-bottom: 2rem;">
                        <div style="display: flex; justify-content: center; gap: 0.25rem; margin-bottom: 1rem;">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star" style="font-size: 2rem; color: {{ $i <= $employeeRating->rating ? '#fbbf24' : '#d1d5db' }};"></i>
                            @endfor
                        </div>
                        <h4 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem;">{{ $employeeRating->rating }}/5 Stars</h4>
                        <p style="color: var(--text-secondary);">Rating given on {{ $employeeRating->created_at->format('F d, Y \a\t g:i A') }}</p>
                    </div>

                    @if($employeeRating->comment)
                        <div style="margin-bottom: 1.5rem;">
                            <label style="font-weight: 600; color: var(--text-secondary); font-size: 0.875rem; margin-bottom: 0.5rem; display: block;">Comment</label>
                            <div style="background: var(--gray-100); padding: 1rem; border-radius: 0.5rem; border-left: 4px solid var(--primary);">
                                <p style="margin: 0; line-height: 1.6;">{{ $employeeRating->comment }}</p>
                            </div>
                        </div>
                    @endif

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div>
                            <label style="font-weight: 600; color: var(--text-secondary); font-size: 0.875rem;">Rated By</label>
                            <div style="display: flex; align-items: center; gap: 0.5rem; margin-top: 0.25rem;">
                                <div style="width: 2rem; height: 2rem; background: var(--gradient-secondary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 0.875rem;">
                                    {{ substr($employeeRating->rater->name ?? 'A', 0, 1) }}
                                </div>
                                <span style="font-weight: 600;">{{ $employeeRating->rater->name ?? 'Admin' }}</span>
                            </div>
                        </div>
                        <div>
                            <label style="font-weight: 600; color: var(--text-secondary); font-size: 0.875rem;">Rating ID</label>
                            <p style="font-weight: 600;">#{{ $employeeRating->id }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="card mt-4">
            <div class="card-header">
                <h3><i class="fas fa-cogs"></i> Actions</h3>
            </div>
            <div class="card-body">
                <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                    <a href="{{ route('admin.employeeRatings.edit', $employeeRating) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit Rating
                    </a>
                    <form action="{{ route('admin.employeeRatings.destroy', $employeeRating) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this rating? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Delete Rating
                        </button>
                    </form>
                    <a href="{{ route('admin.employeeRatings.index') }}" class="btn btn-secondary">
                        <i class="fas fa-list"></i> View All Ratings
                    </a>
                    <a href="{{ route('admin.employees') }}" class="btn btn-primary">
                        <i class="fas fa-users"></i> View Employee
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.grid {
    display: grid;
}
.grid-template-columns-1fr-1fr {
    grid-template-columns: 1fr 1fr;
}
.gap-2 {
    gap: 0.5rem;
}
.gap-1 {
    gap: 0.25rem;
}
.gap-1rem {
    gap: 1rem;
}
.mt-4 {
    margin-top: 1rem;
}
.mb-4 {
    margin-bottom: 1rem;
}
.mb-2 {
    margin-bottom: 0.5rem;
}
.mb-1 {
    margin-bottom: 0.25rem;
}
.mb-1\.5 {
    margin-bottom: 0.375rem;
}
.mt-0\.25 {
    margin-top: 0.0625rem;
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
.inline {
    display: inline;
}
.w-4 {
    width: 1rem;
}
.h-4 {
    height: 1rem;
}
.w-2 {
    width: 0.5rem;
}
.h-2 {
    height: 0.5rem;
}
.text-2xl {
    font-size: 1.5rem;
}
.text-lg {
    font-size: 1.125rem;
}
.text-sm {
    font-size: 0.875rem;
}
.font-bold {
    font-weight: 700;
}
.font-semibold {
    font-weight: 600;
}
.font-medium {
    font-weight: 500;
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
.px-3 {
    padding-left: 0.75rem;
    padding-right: 0.75rem;
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
@endsection 