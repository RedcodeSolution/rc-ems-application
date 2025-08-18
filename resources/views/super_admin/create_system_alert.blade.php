@extends('layouts.super_admin')

@section('title', 'Create System Alert')

@section('content')
<div class="system-alerts-container">
    <div class="system-alerts-header">
        <div class="header-content">
            <h1><i class="fas fa-exclamation-triangle"></i> Create System Alert</h1>
            <p>Manually create a new system alert for the organization</p>
        </div>
    </div>
    <div class="alert-form-card" style="background: white; border-radius: 16px; padding: 2rem; max-width: 600px; margin: 2rem auto; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
        <form method="POST" action="{{ route('super_admin.system_alerts.store') }}">
            @csrf
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label for="title" style="font-weight: 600;">Title</label>
                <input type="text" name="title" id="title" class="form-control" required maxlength="255" value="{{ old('title') }}" style="width: 100%; padding: 0.75rem; border-radius: 8px; border: 1px solid #E5E7EB;">
                @error('title')<div class="text-danger">{{ $message }}</div>@enderror
            </div>
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label for="message" style="font-weight: 600;">Message</label>
                <textarea name="message" id="message" class="form-control" required rows="4" style="width: 100%; padding: 0.75rem; border-radius: 8px; border: 1px solid #E5E7EB;">{{ old('message') }}</textarea>
                @error('message')<div class="text-danger">{{ $message }}</div>@enderror
            </div>
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label for="severity" style="font-weight: 600;">Severity</label>
                <select name="severity" id="severity" class="form-control" required style="width: 100%; padding: 0.75rem; border-radius: 8px; border: 1px solid #E5E7EB;">
                    <option value="">Select Severity</option>
                    <option value="critical" {{ old('severity') == 'critical' ? 'selected' : '' }}>Critical</option>
                    <option value="warning" {{ old('severity') == 'warning' ? 'selected' : '' }}>Warning</option>
                    <option value="info" {{ old('severity') == 'info' ? 'selected' : '' }}>Information</option>
                </select>
                @error('severity')<div class="text-danger">{{ $message }}</div>@enderror
            </div>
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label for="category" style="font-weight: 600;">Category</label>
                <select name="category" id="category" class="form-control" required style="width: 100%; padding: 0.75rem; border-radius: 8px; border: 1px solid #E5E7EB;">
                    <option value="">Select Category</option>
                    <option value="security" {{ old('category') == 'security' ? 'selected' : '' }}>Security</option>
                    <option value="database" {{ old('category') == 'database' ? 'selected' : '' }}>Database</option>
                    <option value="infrastructure" {{ old('category') == 'infrastructure' ? 'selected' : '' }}>Infrastructure</option>
                    <option value="performance" {{ old('category') == 'performance' ? 'selected' : '' }}>Performance</option>
                    <option value="backup" {{ old('category') == 'backup' ? 'selected' : '' }}>Backup</option>
                    <option value="application" {{ old('category') == 'application' ? 'selected' : '' }}>Application</option>
                </select>
                @error('category')<div class="text-danger">{{ $message }}</div>@enderror
            </div>
            <div class="form-group" style="margin-bottom: 2rem;">
                <label for="assigned_to" style="font-weight: 600;">Assigned To (optional)</label>
                <input type="text" name="assigned_to" id="assigned_to" class="form-control" maxlength="255" value="{{ old('assigned_to') }}" style="width: 100%; padding: 0.75rem; border-radius: 8px; border: 1px solid #E5E7EB;">
                @error('assigned_to')<div class="text-danger">{{ $message }}</div>@enderror
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 1rem;">
                <a href="{{ route('super_admin.system_alerts') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Create Alert</button>
            </div>
        </form>
    </div>
</div>
@endsection 