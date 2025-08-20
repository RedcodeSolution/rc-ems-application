@php
use Illuminate\Support\Facades\Storage;
@endphp

@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Document Details</h1>
    <p><strong>ID:</strong> {{ $document->document_id }}</p>
    <p><strong>Employee:</strong> {{ $document->employee->employee_name ?? 'N/A' }}</p>
    <p><strong>Path:</strong> {{ $document->doc_path }}</p>
    <p><strong>Version:</strong> {{ $document->version }}</p>
    <p><strong>Review Date:</strong> {{ $document->review_date }}</p>
    <p><strong>Access Permission:</strong> {{ $document->access_permission }}</p>
    <p><strong>Project:</strong> {{ $document->project->project_name ?? 'N/A' }}</p>

    @if($document->doc_path)<a href="{{ Storage::url($document->doc_path) }}" download>Download</a>

    @endif

    <a href="{{ route('documents.index') }}" class="btn btn-secondary">Back to List</a>
</div>

@endsection
