@extends('layouts.admin')

<style>
:root {
    --primary: #D32F2F;
    --accent: #212121;
    --primary-light: #F5F5F5;
    --secondary: #3F51B5;
    --success: #43A047;
    --warning: #FFA000;
    --danger: #E64A19;
    --error: #E64A19;
    --info: #0097A7;
    --text-primary: #212121;
    --text-secondary: #757575;
    --text-disabled: #BDBDBD;
    --divider: #E0E0E0;
}
/* Modern Document Management Styles */
.card {
    border-radius: 1rem;
    box-shadow: 0 2px 16px 0 rgba(0,0,0,0.07);
    border: none;
    background: #fff;
}
.card-header {
    border-bottom: 1px solid var(--divider);
    background: linear-gradient(90deg, var(--primary-light) 60%, #fff 100%);
    border-radius: 1rem 1rem 0 0;
    padding: 1.5rem 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.card-body {
    padding: 2rem;
}
.btn {
    border-radius: 0.75rem;
    font-weight: 500;
    transition: background 0.2s, color 0.2s, box-shadow 0.2s;
    box-shadow: 0 1px 4px 0 rgba(0,0,0,0.04);
}
.btn-primary {
    background: linear-gradient(90deg, var(--primary) 60%, var(--secondary) 100%);
    color: #fff;
    border: none;
}
.btn-primary:hover {
    background: linear-gradient(90deg, var(--secondary) 60%, var(--primary) 100%);
}
.btn-secondary {
    background: var(--primary-light);
    color: var(--text-secondary);
    border: none;
}
.btn-secondary:hover {
    background: var(--divider);
}
.btn-warning {
    background: var(--warning);
    color: #fff;
    border: none;
}
.btn-warning:hover {
    background: #ffb300;
}
.btn-danger {
    background: var(--danger);
    color: #fff;
    border: none;
}
.btn-danger:hover {
    background: #d84315;
}
.badge {
    font-weight: 600;
    letter-spacing: 0.02em;
    display: inline-block;
}
.form-input, .form-select {
    border-radius: 0.5rem;
    border: 1px solid var(--divider);
    background: var(--primary-light);
    padding: 0.5rem 1rem;
    font-size: 1rem;
    transition: border 0.2s, box-shadow 0.2s;
}
.form-input:focus, .form-select:focus {
    border-color: var(--primary);
    outline: none;
    box-shadow: 0 0 0 2px #d32f2f22;
}
.flex {
    display: flex;
}
.gap-1 { gap: 0.25rem; }
.gap-2 { gap: 0.75rem; }
.gap-3 { gap: 1.25rem; }
.justify-between { justify-content: space-between; }
.items-center { align-items: center; }
.text-center { text-align: center; }
.mt-4 { margin-top: 1.5rem; }
.mb-4 { margin-bottom: 1.5rem; }
.card-body > div[style*="display: grid"] > .card {
    border: 1px solid var(--divider);
    box-shadow: 0 1px 8px 0 rgba(0,0,0,0.03);
    transition: box-shadow 0.2s;
}
.card-body > div[style*="display: grid"] > .card:hover {
    box-shadow: 0 4px 24px 0 rgba(37,99,235,0.08);
}
.card-body h4 {
    color: var(--text-primary);
    margin: 0;
    font-size: 1rem;
    font-weight: 600;
}
.card-body p {
    color: var(--text-secondary);
    margin-bottom: 1rem;
    line-height: 1.7;
}
.card-body .badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.75rem;
    border-radius: 1rem;
}
.card-body > div[style*="display: grid"] > .card .card-body > div[style*="width: 3rem"] {
    box-shadow: 0 2px 8px 0 rgba(37,99,235,0.06);
}
@media (max-width: 900px) {
    .card-body, .card-header { padding: 1rem; }
    .card-body h4 { font-size: 0.95rem; }
}
::-webkit-scrollbar {
    height: 8px;
    background: #f3f4f6;
    border-radius: 4px;
}
::-webkit-scrollbar-thumb {
    background: #e5e7eb;
    border-radius: 4px;
}

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
    backdrop-filter: blur(4px);

}

.modal.show {
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 1;
}

.modal-content {
    background-color: #fff;
    border-radius: 1rem;
    width: 90%;
    max-width: 600px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 20px 60px rgba(0,0,0,0.15);
    animation: modalSlideIn 0.3s ease-out;
}

@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: translateY(-30px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.modal-header {
    padding: 1.5rem 2rem;
    border-bottom: 1px solid var(--divider);
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: linear-gradient(90deg, var(--primary-light) 60%, #fff 100%);
    border-radius: 1rem 1rem 0 0;
}

.modal-header h3 {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--text-primary);
}

.modal-body {
    padding: 2rem;
}

.close {
    color: var(--text-secondary);
    font-size: 1.5rem;
    font-weight: bold;
    cursor: pointer;
    border: none;
    background: none;
    padding: 0.5rem;
    border-radius: 0.5rem;
    transition: all 0.2s;
}

.close:hover {
    color: var(--primary);
    background: var(--primary-light);
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: var(--text-primary);
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1px solid var(--divider);
    border-radius: 0.5rem;
    font-size: 1rem;
    transition: all 0.2s;
    background: var(--primary-light);
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 2px rgba(211, 47, 47, 0.1);
}

.form-group textarea {
    resize: vertical;
    min-height: 100px;
}

.input-icon {
    position: relative;
}

.input-icon i {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-secondary);
    font-size: 0.875rem;
}

.input-icon input {
    padding-left: 2.5rem;
}

.file-upload-area {
    border: 2px dashed var(--divider);
    border-radius: 0.5rem;
    padding: 2rem;
    text-align: center;
    background: var(--primary-light);
    transition: all 0.2s;
    cursor: pointer;
}

.file-upload-area:hover {
    border-color: var(--primary);
    background: rgba(211, 47, 47, 0.05);
}

.file-upload-area.dragover {
    border-color: var(--primary);
    background: rgba(211, 47, 47, 0.1);
}

.file-upload-area input[type="file"] {
    display: none;
}

.file-upload-icon {
    font-size: 3rem;
    color: var(--text-secondary);
    margin-bottom: 1rem;
}

.file-upload-text {
    color: var(--text-secondary);
    font-size: 0.875rem;
    margin-bottom: 0.5rem;
}

.file-upload-subtext {
    color: var(--text-disabled);
    font-size: 0.75rem;
}

.modal-footer {
    padding: 1.5rem 2rem;
    border-top: 1px solid var(--divider);
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
}

.btn-cancel {
    background: var(--primary-light);
    color: var(--text-secondary);
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 0.5rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-cancel:hover {
    background: var(--divider);
}

.btn-submit {
    background: var(--primary);
    color: #fff;
    border: none;
    padding: 0.75rem 2rem;
    border-radius: 0.5rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-submit:hover {
    background: var(--secondary);
}

.btn-submit:disabled {
    background: var(--text-disabled);
    cursor: not-allowed;
}

@media (max-width: 600px) {
    .modal-content {
        width: 95%;
        margin: 1rem;
    }

    .modal-header,
    .modal-body,
    .modal-footer {
        padding: 1rem;
    }

    .modal-footer {
        flex-direction: column;
    }

    .modal-footer button {
        width: 100%;
    }
}
</style>

@section('title', 'Document Management')

@section('content')
<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-file-alt"></i> Document Management</h2>
        <div class="flex gap-2">
            <button class="btn btn-primary" onclick="openAddDocumentModal()">
                <i class="fas fa-plus"></i>
                Upload Document
            </button>
            <button class="btn btn-secondary">
                <i class="fas fa-download"></i>
                Bulk Download
            </button>
        </div>
    </div>
    <div class="card-body">
        <!-- Search and Filter Section -->
        <div class="flex justify-between items-center mb-4">
            <div class="flex gap-2">
                <input type="text" id="documentSearch" placeholder="Search documents by title..." class="form-input" style="width: 300px;">

                <select id="categoryFilter" class="form-select" style="width: 200px;">
                    <option value="">All Categories</option>
                    <option value="policies">Policies</option>
                    <option value="forms">Forms</option>
                    <option value="contracts">Contracts</option>
                    <option value="reports">Reports</option>
                    <option value="training">Training</option>
                </select>

                <select id="typeFilter" class="form-select" style="width: 200px;">
                    <option value="">All Types</option>
                    <option value="pdf">PDF</option>
                    <option value="doc">DOC</option>
                    <option value="xls">XLS</option>
                    <option value="ppt">PPT</option>
                </select>
            </div>

            <button id="applyFilter" class="btn btn-secondary">
                <i class="fas fa-filter"></i> Filter
            </button>
        </div>

        <div id="documentsGrid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 1.5rem;">
            @forelse($documents as $document)
            @php
            $ext = strtolower(pathinfo($document->file_path, PATHINFO_EXTENSION));
            $icon = 'fas fa-file';
            $iconColor = '#6b7280';
            $bgColor = '#f3f4f6';

            switch ($ext) {
            case 'pdf': $icon='fas fa-file-pdf'; $iconColor='#ef4444'; $bgColor='#fee2e2'; break;
            case 'doc': case 'docx': $icon='fas fa-file-word'; $iconColor='#2563eb'; $bgColor='#dbeafe'; break;
            case 'xls': case 'xlsx': $icon='fas fa-file-excel'; $iconColor='#059669'; $bgColor='#d1fae5'; break;
            case 'ppt': case 'pptx': $icon='fas fa-file-powerpoint'; $iconColor='#d97706'; $bgColor='#fef3c7'; break;
            case 'csv': $icon='fas fa-file-csv'; $iconColor='#0891b2'; $bgColor='#cffafe'; break;
            case 'txt': $icon='fas fa-file-alt'; $iconColor='#6b7280'; $bgColor='#f3f4f6'; break;
            }
            @endphp

            <div class="document-card card p-4 border rounded" data-title="{{ strtolower($document->title) }}">
                <div class="card-header flex justify-between items-start mb-2">
                    <div style="width: 3rem; height: 3rem; background: {{ $bgColor }}; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; color: {{ $iconColor }};">
                        <i class="{{ $icon }}" style="font-size: 1.25rem;"></i>
                    </div>
                    <div class="flex gap-1">
                        <button class="btn btn-secondary" style="padding: 0.5rem;" onclick="viewDocument('{{ $document->document_id }}')" title="View Document">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button id="download-btn-{{ $document->document_id }}" class="btn btn-secondary" style="padding: 0.5rem;" data-title="{{ $document->title }}" onclick="downloadDocument('{{ $document->document_id }}')" title="Download Document">
                            <i class="fas fa-download"></i>
                        </button>
                        <button class="btn btn-warning" style="padding: 0.5rem;" onclick="openEditDocumentModal('{{ $document->document_id }}')" title="Edit Document">
                            <i class="fas fa-edit"></i>
                        </button>
                        <form action="{{ route('admin.documents.destroy', $document->document_id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="padding: 0.5rem;" title="Delete Document">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>

                <div class="card-body">

                    <h4 style="font-size: 1rem; font-weight: 600; color: var(--gray-800); margin-bottom: 0.5rem;">
                        {{ $document->title }}
                    </h4>
                    <p style="font-size: 0.875rem; color: var(--gray-600); margin-bottom: 1rem; line-height: 1.5;">
                        {{ $document->description }}
                    </p>

                    @php
                    // Define colors for each category
                    $categoryColors = [
                    'policies'   => ['bg' => 'rgba(59,130,246,0.1)', 'text' => '#3B82F6'],   // Blue
                    'forms'      => ['bg' => 'rgba(16,185,129,0.1)', 'text' => '#10B981'],   // Green
                    'contracts'  => ['bg' => 'rgba(234,179,8,0.1)',  'text' => '#EAB308'],   // Yellow
                    'reports'    => ['bg' => 'rgba(239,68,68,0.1)',  'text' => '#EF4444'],   // Red
                    'training'   => ['bg' => 'rgba(168,85,247,0.1)', 'text' => '#A855F7'],   // Purple
                    'templates'  => ['bg' => 'rgba(6,182,212,0.1)', 'text' => '#06B6D4'],    // Cyan
                    ];

                    $catKey = strtolower($document->category ?? '');
                    $bgColor = $categoryColors[$catKey]['bg'] ?? 'rgba(156,163,175,0.1)'; // default gray
                    $textColor = $categoryColors[$catKey]['text'] ?? '#9CA3AF';
                    @endphp

                    @php
                    if (!function_exists('formatFileSize')) {
                    function formatFileSize($size) {
                    if ($size === null) return '—';
                    if ($size < 1024) return $size . ' B';
                    if ($size < 1048576) return round($size / 1024, 2) . ' KB';
                    if ($size < 1073741824) return round($size / 1048576, 2) . ' MB';
                    return round($size / 1073741824, 2) . ' GB';
                    }
                    }
                    @endphp


                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                        <span class="badge" style="background: {{ $bgColor }}; color: {{ $textColor }}; padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem;">
                         {{ $document->category }}
                        </span>
                        @php
                        $size = null;

                        if (!empty($document->file_path)) {
                        // Adjust the path depending on where your file is stored
                        $fullPath = storage_path('app/public/' . $document->file_path);

                        if (file_exists($fullPath)) {
                        $size = filesize($fullPath); // size in bytes
                        }
                        }

                        if (!function_exists('formatFileSize')) {
                        function formatFileSize($size) {
                        if ($size === null) return '—';
                        if ($size < 1024) return $size . ' B';
                        if ($size < 1048576) return round($size / 1024, 2) . ' KB';
                        if ($size < 1073741824) return round($size / 1048576, 2) . ' MB';
                        return round($size / 1073741824, 2) . ' GB';
                        }
                        }
                        @endphp

                        <div style="font-size: 0.875rem; color: var(--gray-500);">
                            {{ formatFileSize($size) }}
                        </div>

                    </div>

                    <div style="font-size: 0.75rem; color: var(--gray-500);">
                        <div>
                            Uploaded by
                            {{ $document->department->department_name ?? ($document->uploaded_by ?? 'System') }}
                        </div>
                        <div>
                            {{ \Carbon\Carbon::parse($document->created_at)->format('M d, Y') }}
                            • <span id="downloads-count-{{ $document->document_id }}">
                                         {{ $document->downloads ?? 0 }}
                              </span> downloads
                        </div>
                    </div>

                </div>
            </div>
            @empty
            <p style="color: var(--gray-500);">No documents found.</p>
            @endforelse
        </div>

<!-- paginate-->
        <div class="flex justify-between items-center mt-4">
            <div style="color: var(--gray-600); font-size: 0.875rem;">
                Showing {{ $documents->firstItem() }} to {{ $documents->lastItem() }} of {{ $documents->total() }} documents
            </div>

            <div class="flex gap-1">

                <a href="{{ $documents->previousPageUrl() }}"
                   class="btn btn-secondary"
                   style="padding: 0.5rem 0.75rem;"
                   @if(!$documents->onFirstPage()) @else disabled @endif>
                    <i class="fas fa-chevron-left"></i>
                </a>

                @foreach ($documents->getUrlRange(1, $documents->lastPage()) as $page => $url)
                <a href="{{ $url }}"
                   class="btn {{ $documents->currentPage() == $page ? 'btn-primary' : 'btn-secondary' }}"
                   style="padding: 0.5rem 0.75rem;">
                    {{ $page }}
                </a>
                @endforeach

                <a href="{{ $documents->nextPageUrl() }}"
                   class="btn btn-secondary"
                   style="padding: 0.5rem 0.75rem;"
                   @if($documents->hasMorePages()) @else disabled @endif>
                    <i class="fas fa-chevron-right"></i>
                </a>
            </div>
        </div>

    </div>
</div>

<!-- Document Statistics -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-top: 2rem;">
    <div class="card">
        <div class="card-body text-center">
            <div style="font-size: 2rem; font-weight: 700; color: var(--primary); margin-bottom: 0.5rem;">{{ $documents->count() }}</div>
            <div style="color: var(--gray-600); font-weight: 500;">Total Documents</div>
        </div>
    </div>

    @php
    $totalDownloads = $documents->sum('downloads');
    @endphp

    <div class="card">
        <div class="card-body text-center">
            <div id="total-downloads"
                 style="font-size: 2rem; font-weight: 700; color: var(--success); margin-bottom: 0.5rem;">
                {{ $totalDownloads }}
            </div>
            <div style="color: var(--gray-600); font-weight: 500;">
                Total Downloads
            </div>
        </div>
    </div>


    @php
    use Illuminate\Support\Facades\File;

    $totalSizeBytes = 0;

    foreach ($documents as $doc) {
    if (!empty($doc->file_path)) {
    $fullPath = storage_path('app/public/' . $doc->file_path);

    if (File::exists($fullPath)) {
    $totalSizeBytes += File::size($fullPath);
    }
    }
    }

    // Convert total to MB
    $totalSizeMB = $totalSizeBytes > 0 ? round($totalSizeBytes / 1048576, 2) : 0;

    @endphp

    <div class="card">
        <div class="card-body text-center">
            <div style="font-size: 2rem; font-weight: 700; color: var(--warning); margin-bottom: 0.5rem;">
                {{ $totalSizeMB }}
            </div>
            <div style="color: var(--gray-600); font-weight: 500;">
                Total Size (MB)
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body text-center">
            <div style="font-size: 2rem; font-weight: 700; color: var(--info); margin-bottom: 0.5rem;">
                {{ $documents->whereNotNull('category')->pluck('category')->unique()->count() }}
            </div>
            <div style="color: var(--gray-600); font-weight: 500;">Categories</div>
        </div>
    </div>
</div>

<!-- Add Document Modal -->
<div id="addDocumentModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-upload"></i> Upload New Document</h3>
            <button class="close" onclick="closeAddDocumentModal()">&times;</button>
        </div>
        <form id="addDocumentForm" method="POST" action="{{ route('admin.documents.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label for="document_title">Document Title *</label>
                    <div class="input-icon">
                        <i class="fas fa-file-alt"></i>
                        <input type="text" id="document_title" name="title" required placeholder="Enter document title">
                    </div>
                </div>

                <div class="form-group">
                    <label for="document_description">Description</label>
                    <div class="input-icon">
                        <i class="fas fa-align-left"></i>
                        <textarea id="document_description" name="description" placeholder="Enter document description"></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label for="document_category">Category *</label>
                    <div class="input-icon">
                        <i class="fas fa-folder"></i>
                        <select id="document_category" name="category" required>
                            <option value="">Select Category</option>
                            <option value="policies">Policies</option>
                            <option value="forms">Forms</option>
                            <option value="contracts">Contracts</option>
                            <option value="reports">Reports</option>
                            <option value="training">Training</option>
                            <option value="templates">Templates</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="document_department">Department</label>
                    <div class="input-icon">
                        <i class="fas fa-building"></i>
                        <select id="department_filter" name="department_id" class="form-select">
                            <option value="">All Departments</option>
                            @foreach($departments as $department)
                            <option value="{{ $department->department_id }}">
                                {{ $department->department_name }}
                            </option>
                            @endforeach
                        </select>

                    </div>
                </div>

                <div class="form-group">
                    <label for="document_access">Access Level *</label>
                    <div class="input-icon">
                        <i class="fas fa-lock"></i>
                        <select id="document_access" name="access_level" required>
                            <option value="">Select Access Level</option>
                            <option value="public">Public - All Employees</option>
                            <option value="department">Department Only</option>
                            <option value="admin">Admin Only</option>
                            <option value="restricted">Restricted</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="document_tags">Tags</label>
                    <div class="input-icon">
                        <i class="fas fa-tags"></i>
                        <input type="text" id="document_tags" name="tags" placeholder="Enter tags separated by commas">
                    </div>
                </div>

                <div class="form-group">
                    <label for="document_file">Document File *</label>
                    <div class="file-upload-area" onclick="document.getElementById('document_file').click()">
                        <input type="file" id="document_file" name="file" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.zip,.rar" required>
                        <div class="file-upload-icon">
                            <i class="fas fa-cloud-upload-alt"></i>
                        </div>
                        <div class="file-upload-text">
                            Click to upload or drag and drop
                        </div>
                        <div class="file-upload-subtext">
                            Supported formats: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, TXT, ZIP, RAR (Max: 10MB)
                        </div>
                    </div>
                    <div id="file-preview" style="margin-top: 1rem; display: none;">
                        <div style="display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem; background: var(--primary-light); border-radius: 0.5rem;">
                            <i class="fas fa-file" style="color: var(--primary);"></i>
                            <span id="file-name"></span>
                            <button type="button" onclick="removeFile()" style="margin-left: auto; background: none; border: none; color: var(--danger); cursor: pointer;">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                        <input type="checkbox" id="document_notify" name="notify_users" value="1">
                        <span>Notify users about this document</span>
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeAddDocumentModal()">Cancel</button>
                <button type="submit" class="btn-submit">
                    <i class="fas fa-upload"></i> Upload Document
                </button>
            </div>
        </form>
    </div>
</div>

<!-- View Document Modal -->
<div id="viewDocumentModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-eye"></i> View Document</h3>
            <button class="close" onclick="closeViewDocumentModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div style="display: flex; gap: 1rem; margin-bottom: 1.5rem;">
                <div style="width: 4rem; height: 4rem; background: rgba(59, 130, 246, 0.1); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; color: var(--primary);">
                    <i id="viewDocumentIcon" class="fas fa-file-alt" style="font-size: 1.5rem;"></i>
                </div>
                <div style="flex: 1;">
                    <h4 id="viewDocumentTitle" style="margin: 0 0 0.5rem 0; font-size: 1.25rem; font-weight: 600;">Document Title</h4>
                    <div style="display: flex; gap: 1rem; margin-bottom: 0.5rem;">
                        <span id="viewDocumentCategory" class="badge" style="background: rgba(59, 130, 246, 0.1); color: var(--primary); padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem;">Category</span>
                        <span id="viewDocumentSize" style="color: var(--text-secondary); font-size: 0.875rem;"></span>
                    </div>
                    <div style="color: var(--text-secondary); font-size: 0.875rem;">
                        <div id="viewDocumentUploader">Uploaded by: User</div>
                        <div id="viewDocumentDate"></div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Description</label>
                <div id="viewDocumentDescription" style="padding: 0.75rem; background: var(--primary-light); border-radius: 0.5rem; min-height: 80px; color: var(--text-secondary);">

                </div>
            </div>

            <div class="form-group">
                <label>Department</label>
                <div id="viewDocumentDepartment" style="padding: 0.75rem; background: var(--primary-light); border-radius: 0.5rem; color: var(--text-secondary);">

                </div>
            </div>

            <div class="form-group">
                <label>Access Level</label>
                <div id="viewDocumentAccess" style="padding: 0.75rem; background: var(--primary-light); border-radius: 0.5rem; color: var(--text-secondary);">

                </div>
            </div>

            <div class="form-group">
                <label>Tags</label>
                <div id="viewDocumentTags" style="padding: 0.75rem; background: var(--primary-light); border-radius: 0.5rem; color: var(--text-secondary);">

                </div>
            </div>

            <div class="form-group">
                <label>Statistics</label>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem; margin-top: 0.5rem;">
                    <div style="text-align: center; padding: 1rem; background: var(--primary-light); border-radius: 0.5rem;">
                        <div id="viewDocumentDownloads" style="font-size: 1.5rem; font-weight: 600; color: var(--primary);">234</div>
                        <div style="color: var(--text-secondary); font-size: 0.875rem;">Downloads</div>
                    </div>
                    <div style="text-align: center; padding: 1rem; background: var(--primary-light); border-radius: 0.5rem;">
                        <div id="viewDocumentViews" style="font-size: 1.5rem; font-weight: 600; color: var(--success);">456</div>
                        <div style="color: var(--text-secondary); font-size: 0.875rem;">Views</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-cancel" onclick="closeViewDocumentModal()">Close</button>
            <button type="button" class="btn-submit" onclick="downloadCurrentDocument()">
                <i class="fas fa-download"></i> Download
            </button>
        </div>
    </div>
</div>

<!-- Edit Document Modal -->
<div id="editDocumentModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-edit"></i> Edit Document</h3>
            <button class="close" onclick="closeEditDocumentModal()">&times;</button>
        </div>
        <form id="editDocumentForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="form-group">
                    <label for="edit_document_title">Document Title *</label>
                    <div class="input-icon">
                        <i class="fas fa-file-alt"></i>
                        <input type="text" id="edit_document_title" name="title" required placeholder="Enter document title">
                    </div>
                </div>

                <div class="form-group">
                    <label for="edit_document_description">Description</label>
                    <div class="input-icon">
                        <i class="fas fa-align-left"></i>
                        <textarea id="edit_document_description" name="description" placeholder="Enter document description"></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label for="edit_document_category">Category *</label>
                    <div class="input-icon">
                        <i class="fas fa-folder"></i>
                        <select id="edit_document_category" name="category" required>
                            <option value="">Select Category</option>
                            <option value="policies">Policies</option>
                            <option value="forms">Forms</option>
                            <option value="contracts">Contracts</option>
                            <option value="reports">Reports</option>
                            <option value="training">Training</option>
                            <option value="templates">Templates</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="edit_document_department">Department</label>
                    <div class="input-icon">
                        <i class="fas fa-building"></i>
                        <select id="edit_department_id" name="department_id" class="form-select" required>
                            <option value="">Select Department</option>
                            @foreach($departments as $department)
                            <option value="{{ $department->department_id }}">{{ $department->department_name ?? $department->department_id }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="edit_document_access">Access Level *</label>
                    <div class="input-icon">
                        <i class="fas fa-lock"></i>
                        <select id="edit_document_access" name="access_level" required>
                            <option value="">Select Access Level</option>
                            <option value="public">Public - All Employees</option>
                            <option value="department">Department Only</option>
                            <option value="admin">Admin Only</option>
                            <option value="restricted">Restricted</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="edit_document_tags">Tags</label>
                    <div class="input-icon">
                        <i class="fas fa-tags"></i>
                        <input type="text" id="edit_document_tags" name="tags" placeholder="Enter tags separated by commas">
                    </div>
                </div>

                <div class="form-group">
                    <label for="edit_document_file">Replace Document File (Optional)</label>
                    <div class="file-upload-area" onclick="document.getElementById('edit_document_file').click()">
                        <input type="file" id="edit_document_file" name="file" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.zip,.rar">
                        <div class="file-upload-icon">
                            <i class="fas fa-cloud-upload-alt"></i>
                        </div>
                        <div class="file-upload-text">
                            Click to upload a new file or drag and drop
                        </div>
                        <div class="file-upload-subtext">
                            Leave empty to keep the current file
                        </div>
                    </div>
                    <div id="edit-file-preview" style="margin-top: 1rem; display: none;">
                        <div style="display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem; background: var(--primary-light); border-radius: 0.5rem;">
                            <i class="fas fa-file" style="color: var(--primary);"></i>
                            <span id="edit-file-name"></span>
                            <button type="button" onclick="removeEditFile()" style="margin-left: auto; background: none; border: none; color: var(--danger); cursor: pointer;">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeEditDocumentModal()">Cancel</button>
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> Update Document
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteDocumentModal" class="modal">
    <div class="modal-content" style="max-width: 400px;">
        <div class="modal-header">
            <h3><i class="fas fa-trash"></i> Delete Document</h3>
            <button class="close" onclick="closeDeleteDocumentModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div style="text-align: center; margin-bottom: 1.5rem;">
                <div style="width: 4rem; height: 4rem; background: rgba(239, 68, 68, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: var(--danger);">
                    <i class="fas fa-exclamation-triangle" style="font-size: 1.5rem;"></i>
                </div>
                <h4 style="margin: 0 0 0.5rem 0; color: var(--text-primary);">Are you sure?</h4>
                <p style="color: var(--text-secondary); margin: 0;">This action cannot be undone. The document will be permanently deleted.</p>
            </div>

            <div style="background: var(--primary-light); padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <i id="deleteDocumentIcon" class="fas fa-file-alt" style="color: var(--danger); font-size: 1.25rem;"></i>
                    <div>
                        <div id="deleteDocumentTitle" style="font-weight: 600; color: var(--text-primary);">Document Title</div>
                        <div id="deleteDocumentInfo" style="color: var(--text-secondary); font-size: 0.875rem;">Category • Size</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-cancel" onclick="closeDeleteDocumentModal()">Cancel</button>
            <button type="button" class="btn-submit" onclick="confirmDeleteDocument()" style="background: var(--danger);">
                <i class="fas fa-trash"></i> Delete Document
            </button>
        </div>
    </div>
</div>

<script>

    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('documentSearch');
        const categorySelect = document.getElementById('categoryFilter');
        const typeSelect = document.getElementById('typeFilter');
        const filterButton = document.getElementById('applyFilter');
        const cards = document.querySelectorAll('#documentsGrid .document-card');

        function filterDocuments() {
            const query = searchInput.value.toLowerCase().trim();
            const selectedCategory = categorySelect.value.toLowerCase();
            const selectedType = typeSelect.value.toLowerCase();

            cards.forEach(card => {
                const title = card.dataset.title;
                const category = card.dataset.category;
                const type = card.dataset.type;

                const matchesTitle = title.includes(query);
                const matchesCategory = !selectedCategory || category === selectedCategory;
                const matchesType = !selectedType || type === selectedType;

                // Use 'block' for grid cards
                card.style.display = (matchesTitle && matchesCategory && matchesType) ? 'block' : 'none';
            });
        }

        // Filter on button click
        filterButton.addEventListener('click', filterDocuments);

        // Optional: live filter while typing or selecting dropdowns
        searchInput.addEventListener('keyup', filterDocuments);
        categorySelect.addEventListener('change', filterDocuments);
        typeSelect.addEventListener('change', filterDocuments);
    });



    document.getElementById('documentSearch').addEventListener('keyup', function() {
        const searchValue = this.value.toLowerCase().trim();
        const cards = document.querySelectorAll('#documentsGrid .document-card');

        cards.forEach(card => {
            const title = card.dataset.title; // title saved in data-title
            if (title.includes(searchValue)) {
                card.style.display = "block";
            } else {
                card.style.display = "none";
            }
        });
    });



    let currentDocumentId = null;

// Add Document Modal Functions
function openAddDocumentModal() {
    document.getElementById('addDocumentModal').classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeAddDocumentModal() {
    document.getElementById('addDocumentModal').classList.remove('show');
    document.body.style.overflow = 'auto';
    document.getElementById('addDocumentForm').reset();
    const filePreview = document.getElementById('file-preview');
    if (filePreview) filePreview.style.display = 'none';
}

function viewDocumentIcon(filePath) {
    if (!filePath) return { icon: 'fas fa-file', color: '#6b7280', bgColor: '#f3f4f6' };

    const ext = filePath.split('.').pop().toLowerCase();

    switch (ext) {
        case 'pdf': return { icon: 'fas fa-file-pdf', color: '#ef4444', bgColor: '#fee2e2' };
        case 'doc':
        case 'docx': return { icon: 'fas fa-file-word', color: '#2563eb', bgColor: '#dbeafe' };
        case 'xls':
        case 'xlsx': return { icon: 'fas fa-file-excel', color: '#059669', bgColor: '#d1fae5' };
        case 'ppt':
        case 'pptx': return { icon: 'fas fa-file-powerpoint', color: '#d97706', bgColor: '#fef3c7' };
        case 'csv': return { icon: 'fas fa-file-csv', color: '#0891b2', bgColor: '#cffafe' };
        case 'txt': return { icon: 'fas fa-file-alt', color: '#6b7280', bgColor: '#f3f4f6' };
        default: return { icon: 'fas fa-file', color: '#6b7280', bgColor: '#f3f4f6' };
    }
}

function formatFileSize(bytes) {
    if (!bytes || isNaN(bytes)) return '—';
    if (bytes < 1024) return bytes + ' B';
    if (bytes < 1048576) return (bytes / 1024).toFixed(2) + ' KB';
    if (bytes < 1073741824) return (bytes / 1048576).toFixed(2) + ' MB';
    return (bytes / 1073741824).toFixed(2) + ' GB';
}

function viewDocument(documentId) {
    fetch(`/admin/documents/${documentId}`)
        .then(res => {
            if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
            return res.json();
        })
        .then(data => {
            if (!data.document) {
                alert('Document not found');
                return;
            }

            const doc = data.document;

            // Set modal fields
            document.getElementById('viewDocumentTitle').textContent = doc.title || 'N/A';
            document.getElementById('viewDocumentDescription').textContent = doc.description || 'No description';
            document.getElementById('viewDocumentCategory').textContent = doc.category ? doc.category.charAt(0).toUpperCase() + doc.category.slice(1) : 'N/A';
            document.getElementById('viewDocumentDepartment').textContent = doc.department || 'N/A';
            document.getElementById('viewDocumentAccess').textContent = doc.access ? doc.access.charAt(0).toUpperCase() + doc.access.slice(1) : 'N/A';
            document.getElementById('viewDocumentTags').textContent = doc.tags || 'No tags';
            document.getElementById('viewDocumentSize').textContent = formatFileSize(doc.size);
            document.getElementById('viewDocumentUploader').textContent = `Uploaded by: ${doc.uploader || ''}`;
            document.getElementById('viewDocumentDate').textContent = `Date: ${doc.date || 'N/A'}`;
            document.getElementById('viewDocumentDownloads').textContent = doc.downloads ?? 0;
            document.getElementById('viewDocumentViews').textContent = doc.views ?? 0;
            document.getElementById('viewDocumentIcon').className = doc.icon || 'fas fa-file';

            const iconData = viewDocumentIcon(doc.file_path);
            const iconEl = document.getElementById('viewDocumentIcon');
            iconEl.className = iconData.icon;
            iconEl.style.color = iconData.color;
            iconEl.parentElement.style.background = iconData.bgColor;


            currentDocumentId = documentId;

            // Show modal
            document.getElementById('viewDocumentModal').classList.add('show');
            document.body.style.overflow = 'hidden';
        })
        .catch(err => {
            console.error('Error fetching document:', err);
            alert('Error fetching document. Check console for details.');
        });
}

// Close modal function
function closeViewDocumentModal() {
    document.getElementById('viewDocumentModal').classList.remove('show');
    document.body.style.overflow = 'auto';
}

// Optional: Download current document
function downloadCurrentDocument() {
    if (!currentDocumentId) return;
    window.location.href = `/admin/documents/download/${currentDocumentId}`;
}



// Edit Document Modal Functions
function openEditDocumentModal(documentId) {
    if (!documentId) return alert('Document ID missing!');

    const modal = document.getElementById('editDocumentModal');
    const form = document.getElementById('editDocumentForm');
    if (!modal || !form) return alert('Modal or form not found!');

    modal.classList.add('show'); // show modal (using CSS class for centering)
    form.reset();

    // helper to set value safely
    const setValue = (selector, value) => {
        const el = form.querySelector(selector);
        if (el) el.value = value ?? '';
    };

    fetch(`/documents/${documentId}/edit`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
        .then(res => res.json())
        .then(data => {
            if (!data.success || !data.document) {
                throw new Error(data.message || 'Failed to fetch document data');
            }

            const doc = data.document;
            form.action = `/documents/${documentId}`;

            setValue('#edit_document_title', doc.title);
            setValue('#edit_document_description', doc.description);
            setValue('#edit_document_category', doc.category);
            setValue('#edit_document_department', doc.department_id ?? '');
            setValue('#edit_document_access', doc.access_level);
            setValue('#edit_document_tags', doc.tags);

            const departmentSelect = form.querySelector('#edit_department_id');
            if (departmentSelect) {
                departmentSelect.value = doc.department_id ?? '';
            }

            // Show current file name if exists
            if (doc.file_path) {
                const parts = doc.file_path.split('/');
                const fileName = parts[parts.length - 1];
                document.getElementById('edit-file-name').textContent = fileName;
                document.getElementById('edit-file-preview').style.display = 'flex';
            } else {
                document.getElementById('edit-file-preview').style.display = 'none';
            }
        })
        .catch(err => {
            console.error('Error:', err);
            alert('Failed to load document: ' + err.message);
            closeEditDocumentModal();
        });
}

// File Upload Handling for Edit Document
const fileInput = document.getElementById('edit_document_file');
if (fileInput) {
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Check file size (10MB limit)
            if (file.size > 10 * 1024 * 1024) {
                alert('File size must be less than 10MB');
                e.target.value = '';
                return;
            }

            // Show file preview
            document.getElementById('edit-file-name').textContent = file.name;
            document.getElementById('edit-file-preview').style.display = 'flex';
        }
    });
}

// Close modal function
function closeEditDocumentModal() {
    const modal = document.getElementById('editDocumentModal');
    if (modal) modal.classList.remove('show');
}


function deleteDocument(id, title = '', category = '', size = '') {
    currentDocumentId = id;

    // Populate modal
    document.getElementById('deleteDocumentTitle').textContent = title || 'Document';
    document.getElementById('deleteDocumentInfo').textContent = category ? `${category} • ${size || ''}` : '';
    document.getElementById('deleteDocumentIcon').className = 'fas fa-file-alt'; // customize if needed

    document.getElementById('deleteDocumentModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeDeleteDocumentModal() {
    document.getElementById('deleteDocumentModal').style.display = 'none';
    document.body.style.overflow = 'auto';
    currentDocumentId = null;
}

function confirmDeleteDocument() {
    if (!currentDocumentId) return;

    const deleteBtn = document.querySelector('#deleteDocumentModal .btn-submit');
    const originalText = deleteBtn.innerHTML;

    deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Deleting...';
    deleteBtn.disabled = true;

    fetch(`/admin/documents/${currentDocumentId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // alert(data.message);
                // Redirect to index page
                window.location.href = data.redirect;
            } else {
                alert('Failed to delete document: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(err => {
            console.error(err);
            alert('Error deleting document: ' + err.message);
        })
        .finally(() => {
            deleteBtn.innerHTML = originalText;
            deleteBtn.disabled = false;
            closeDeleteDocumentModal();
        });
}

async function downloadDocument(documentId) {
    const token = document.querySelector('meta[name="csrf-token"]').content;

    try {
        let res = await fetch(`/admin/documents/increment-download/${documentId}`, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": token,
                "Accept": "application/json"
            }
        });

        if (res.ok) {
            let data = await res.json();

            let docCountEl = document.getElementById(`downloads-count-${documentId}`);
            if (docCountEl) docCountEl.textContent = data.downloads;

            let totalEl = document.getElementById("total-downloads");
            if (totalEl) {
                totalEl.textContent = parseInt(totalEl.textContent) + 1;
            }
        }
    } catch (e) {
        console.error("Error incrementing download:", e);
    }

    let link = document.createElement('a');
    link.href = `/admin/documents/download/${documentId}`;
    link.setAttribute("download", "");
    document.body.appendChild(link);
    link.click();
    link.remove();
}


document.getElementById('document_file').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        // Check file size (10MB limit)
        if (file.size > 10 * 1024 * 1024) {
            alert('File size must be less than 10MB');
            e.target.value = '';
            return;
        }

        // Show file preview
        document.getElementById('file-name').textContent = file.name;
        document.getElementById('file-preview').style.display = 'block';

        // Update upload area
        const uploadArea = document.querySelector('.file-upload-area');
        uploadArea.style.borderColor = 'var(--success)';
        uploadArea.style.background = 'rgba(67, 160, 71, 0.1)';

        // Update icon and text
        const icon = uploadArea.querySelector('.file-upload-icon i');
        icon.className = 'fas fa-check-circle';
        icon.style.color = 'var(--success)';

        const text = uploadArea.querySelector('.file-upload-text');
        text.textContent = 'File selected successfully';
        text.style.color = 'var(--success)';
    }
});

// File Upload Handling for Edit Document
document.getElementById('edit_document_file').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        // Check file size (10MB limit)
        if (file.size > 10 * 1024 * 1024) {
            alert('File size must be less than 10MB');
            e.target.value = '';
            return;
        }

        // Show file preview
        document.getElementById('edit-file-name').textContent = file.name;
        document.getElementById('edit-file-preview').style.display = 'block';
    }
});

function removeFile() {
    document.getElementById('document_file').value = '';
    document.getElementById('file-preview').style.display = 'none';

    // Reset upload area
    const uploadArea = document.querySelector('.file-upload-area');
    uploadArea.style.borderColor = 'var(--divider)';
    uploadArea.style.background = 'var(--primary-light)';

    // Reset icon and text
    const icon = uploadArea.querySelector('.file-upload-icon i');
    icon.className = 'fas fa-cloud-upload-alt';
    icon.style.color = 'var(--text-secondary)';

    const text = uploadArea.querySelector('.file-upload-text');
    text.textContent = 'Click to upload or drag and drop';
    text.style.color = 'var(--text-secondary)';
}

function removeEditFile() {
    document.getElementById('edit_document_file').value = '';
    document.getElementById('edit-file-preview').style.display = 'none';
}

// Drag and Drop Functionality
const fileUploadArea = document.querySelector('.file-upload-area');
if (fileUploadArea) {
    fileUploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('dragover');
    });

    fileUploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
    });

    fileUploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');

        const files = e.dataTransfer.files;
        if (files.length > 0) {
            document.getElementById('document_file').files = files;
            document.getElementById('document_file').dispatchEvent(new Event('change'));
        }
    });
}

// Close Modals on Outside Click
document.addEventListener('click', function(e) {
    const modals = ['addDocumentModal', 'viewDocumentModal', 'editDocumentModal', 'deleteDocumentModal'];

    modals.forEach(modalId => {
        const modal = document.getElementById(modalId);
        if (modal && e.target === modal) {
            modal.classList.remove('show');
            document.body.style.overflow = 'auto';
        }
    });
});

// Close Modals with Escape Key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modals = ['addDocumentModal', 'viewDocumentModal', 'editDocumentModal', 'deleteDocumentModal'];

        modals.forEach(modalId => {
            const modal = document.getElementById(modalId);
            if (modal && modal.classList.contains('show')) {
                modal.classList.remove('show');
                document.body.style.overflow = 'auto';
            }
        });
    }
});

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    const buttons = document.querySelectorAll('[title]');
    buttons.forEach(button => {
        button.addEventListener('mouseenter', function(e) {
            const tooltip = document.createElement('div');
            tooltip.className = 'tooltip';
            tooltip.textContent = this.getAttribute('title');
            tooltip.style.cssText = `
                position: absolute;
                background: rgba(0,0,0,0.8);
                color: white;
                padding: 0.5rem;
                border-radius: 0.25rem;
                font-size: 0.75rem;
                z-index: 1000;
                pointer-events: none;
                white-space: nowrap;
            `;

            document.body.appendChild(tooltip);

            const rect = this.getBoundingClientRect();
            tooltip.style.left = rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2) + 'px';
            tooltip.style.top = rect.top - tooltip.offsetHeight - 5 + 'px';

            this.tooltipElement = tooltip;
        });

        button.addEventListener('mouseleave', function() {
            if (this.tooltipElement) {
                this.tooltipElement.remove();
                this.tooltipElement = null;
            }
        });
    });
});

function renderDocuments(documentsToRender) {
    const grid = document.getElementById('documentsGrid');

    if (!documentsToRender || documentsToRender.length === 0) {
        grid.innerHTML = `
            <div style="grid-column: 1 / -1; text-align: center; padding: 3rem; color: var(--text-secondary);">
                <i class="fas fa-search" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.3;"></i>
                <h3 style="margin: 0 0 0.5rem 0; font-size: 1.25rem;">No Documents Found</h3>
                <p style="margin: 0; font-size: 1rem;">Try adjusting your search terms or clear the search to see all documents.</p>
            </div>
        `;
        return;
    }

    let html = '';

    documentsToRender.forEach(document => {
        // Determine file icon based on extension
        let ext = (document.file_name || '').split('.').pop().toLowerCase();
        let icon = 'fas fa-file';
        let iconColor = 'var(--secondary)';
        let bgColor = 'rgba(99,102,241,0.1)';

        switch (ext) {
            case 'pdf': icon = 'fas fa-file-pdf'; iconColor = 'var(--danger)'; bgColor = 'rgba(239,68,68,0.1)'; break;
            case 'doc': case 'docx': icon = 'fas fa-file-word'; iconColor = 'var(--primary)'; bgColor = 'rgba(59,130,246,0.1)'; break;
            case 'xls': case 'xlsx': icon = 'fas fa-file-excel'; iconColor = 'var(--success)'; bgColor = 'rgba(16,185,129,0.1)'; break;
            case 'ppt': case 'pptx': icon = 'fas fa-file-powerpoint'; iconColor = 'var(--warning)'; bgColor = 'rgba(245,158,11,0.1)'; break;
            case 'csv': icon = 'fas fa-file-csv'; iconColor = 'var(--info)'; bgColor = 'rgba(6,182,212,0.1)'; break;
            case 'zip': case 'rar': icon = 'fas fa-file-archive'; iconColor = 'var(--warning)'; bgColor = 'rgba(245,158,11,0.1)'; break;
        }

        html += `
        <div class="card">
            <div class="card-header flex justify-between items-start">
                <div style="width: 3rem; height: 3rem; background: ${bgColor}; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; color: ${iconColor};">
                    <i class="${icon}" style="font-size: 1.25rem;"></i>
                </div>
                <div class="flex gap-1">
                    <button class="btn btn-secondary" style="padding: 0.5rem;" onclick="viewDocument('${document.document_id}')" title="View Document">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn btn-secondary" style="padding: 0.5rem;" onclick="downloadDocument('${document.document_id}')" title="Download Document">
                        <i class="fas fa-download"></i>
                    </button>
                    <button class="btn btn-warning" style="padding: 0.5rem;" onclick="editDocument('${document.document_id}')" title="Edit Document">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-danger" style="padding: 0.5rem;" onclick="deleteDocument('${document.document_id}')" title="Delete Document">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <h4 style="font-size: 1rem; font-weight: 600; color: var(--text-primary); margin-bottom: 0.5rem;">
                    ${document.title || 'Untitled Document'}
                </h4>
                <p style="font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 1rem; line-height: 1.5;">
                    ${document.description || 'No description available'}
                </p>

                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                    <span class="badge" style="background: rgba(59,130,246,0.1); color: var(--primary); padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem;">
                        ${document.category || 'Uncategorized'}
                    </span>
                    <div style="font-size: 0.875rem; color: var(--gray-500);">
                        ${document.file_size || '—'}
                    </div>
                </div>

                <div style="font-size: 0.75rem; color: var(--gray-500);">
                    <div>Uploaded by ${document.uploaded_by || 'System'}</div>
                    <div>${document.created_at || ''} • ${document.downloads || 0} downloads</div>
                </div>
            </div>
        </div>
        `;
    });

    grid.innerHTML = html;
}
</script>

@endsection
