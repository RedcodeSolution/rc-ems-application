<form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="container mt-4">
        <h2>Create Document</h2>
        <div class="mb-3">
            <label for="document_id" class="form-label">Document ID</label>
            <input type="text" class="form-control @error('document_id') is-invalid @enderror" id="document_id" name="document_id" value="{{ old('document_id') }}" required>
            @error('document_id')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="employee_id" class="form-label">Employee</label>
            <select class="form-select @error('employee_id') is-invalid @enderror" id="employee_id" name="employee_id" required>
                <option value="">Select Employee</option>
                @foreach($employees as $employee)
                <option value="{{ $employee->employee_id }}" {{ old('employee_id') == $employee->employee_id ? 'selected' : '' }}>
                {{ $employee->employee_name }}
                </option>
                @endforeach
            </select>
            @error('employee_id')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="doc_path" class="form-label">Document File</label>
            <input type="file" class="form-control @error('doc_path') is-invalid @enderror" id="doc_path" name="doc_path" required>
            @error('doc_path')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="version" class="form-label">Version</label>
            <input type="text" class="form-control @error('version') is-invalid @enderror" id="version" name="version" value="{{ old('version') }}" required>
            @error('version')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="review_date" class="form-label">Review Date</label>
            <input type="date" class="form-control @error('review_date') is-invalid @enderror" id="review_date" name="review_date" value="{{ old('review_date') }}" required>
            @error('review_date')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="access_permission" class="form-label">Access Permission</label>
            <input type="text" class="form-control @error('access_permission') is-invalid @enderror" id="access_permission" name="access_permission" value="{{ old('access_permission') }}" required>
            @error('access_permission')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="project_id" class="form-label">Project (optional)</label>
            <select class="form-select @error('project_id') is-invalid @enderror" id="project_id" name="project_id">
                <option value="">Select Project</option>
                @foreach($projects as $project)
                <option value="{{ $project->project_id }}" {{ old('project_id') == $project->project_id ? 'selected' : '' }}>
                {{ $project->project_name }}
                </option>
                @endforeach
            </select>
            @error('project_id')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Create</button>
        <a href="{{ route('documents.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
</form>
