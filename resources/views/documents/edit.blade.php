@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Document</h1>
    <form action="{{ route('documents.update', $document->document_id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="employee_id" class="form-label">Employee</label>
            <select name="employee_id" class="form-select" required>
                <option value="">Select Employee</option>
                @foreach($employees as $employee)
                <option value="{{ $employee->employee_id }}" {{ $document->employee_id == $employee->employee_id ? 'selected' : '' }}>
                    {{ $employee->employee_name }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="doc_path" class="form-label">Document File</label>
            @if($document->doc_path)
            <span>{{ $document->doc_path }}</span>
            <a href="{{ Storage::url($document->doc_path) }}" download class="download-btn" data-tooltip="Download the file">
                <i class="bi bi-download"></i>
            </a>
            @endif
            <input type="file" class="form-control mt-2" id="doc_path" name="doc_path">
            <small class="form-text text-muted">Leave blank to keep the current file.</small>
        </div>
        <div class="mb-3">
            <label for="version" class="form-label">Version</label>
            <input type="text" class="form-control" name="version" value="{{ $document->version }}" required>
        </div>
        <div class="mb-3">
            <label for="review_date" class="form-label">Review Date</label>
            <input type="date" class="form-control" name="review_date" value="{{ $document->review_date }}" required>
        </div>
        <div class="mb-3">
            <label for="access_permission" class="form-label">Access Permission</label>
            <input type="text" class="form-control" name="access_permission" value="{{ $document->access_permission }}" required>
        </div>
        <div class="mb-3">
            <label for="project_id" class="form-label">Project (optional)</label>
            <select name="project_id" class="form-select">
                <option value="">Select Project (optional)</option>
                @foreach($projects as $project)
                <option value="{{ $project->project_id }}" {{ $document->project_id == $project->project_id ? 'selected' : '' }}>
                    {{ $project->project_name }}
                </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>

<style>
    .download-btn {
        display: inline-block;
        position: relative;
        color: #fff;
        background: #007bff;
        border-radius: 50%;
        width: 32px;
        height: 32px;
        text-align: center;
        line-height: 32px;
        font-size: 1.1rem;
        margin-left: 8px;
        transition: background 0.2s;
    }
    .download-btn:hover {
        background: #0056b3;
        color: #fff;
        text-decoration: none;
    }
    .download-btn[data-tooltip]:hover:after {
        content: attr(data-tooltip);
        position: absolute;
        left: 50%;
        bottom: 110%;
        transform: translateX(-50%);
        background: #343a40;
        color: #fff;
        padding: 6px 14px;
        border-radius: 6px;
        white-space: nowrap;
        font-size: 0.95rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        opacity: 1;
        pointer-events: none;
        z-index: 10;
        transition: opacity 0.2s;
    }
    .download-btn[data-tooltip]:after {
        opacity: 0;
        transition: opacity 0.2s;
    }
</style>
@endsection
