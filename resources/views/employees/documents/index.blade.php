@extends('layouts.employee')

@section('title', 'My Documents')

@section('content')
<div class="documents-container">

    <!-- Documents Header -->
    <div class="documents-header">
        <div class="header-content">
            <div class="header-info">
                <h1><i class="fas fa-folder-open"></i> My Documents</h1>
                <p>Manage your personal documents and files</p>
            </div>
            <div class="header-actions">
                <button class="btn btn-primary" onclick="openUploadModal()">
                    <i class="fas fa-upload"></i>
                    Upload Document
                </button>
                <button class="btn btn-secondary" onclick="toggleViewMode()">
                    <i class="fas fa-th-large" id="viewModeIcon"></i>
                    <span id="viewModeText">Grid View</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Documents Statistics -->
    <div class="documents-stats">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-file-alt"></i>
            </div>
            <div class="stat-info">
                <h3>{{$documents->count()}}</h3>
                <p>Total Documents</p>
            </div>
        </div>

        @php
        // Sum all file_size in bytes
        $totalSizeBytes = $documents->sum('file_size');

        // Convert bytes to human-readable format
        function formatBytes($bytes, $precision = 2) {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
        }

        $totalStorage = formatBytes($totalSizeBytes);
        @endphp
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-cloud-upload-alt"></i>
            </div>
            <div class="stat-info">
                <h3> {{ $totalStorage }}</h3>
                <p>Storage Used</p>
            </div>
        </div>

        @php
        use Carbon\Carbon;

        // Define recent period (e.g., last 7 days)
        $recentDays = 7;
        $recentUploadsCount = $documents->filter(function($doc) use ($recentDays) {
        return $doc->created_at >= Carbon::now()->subDays($recentDays);
        })->count();
        @endphp

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $recentUploadsCount }}</h3>
                <p>Recent Uploads</p>
            </div>
        </div>

        @php
        // Ensure $documents is a Collection
        $documentsCollection = $documents instanceof \Illuminate\Support\Collection ? $documents : collect($documents);

        // Count shared files (assuming 'is_shared' = 1 means shared)
        $sharedFilesCount = $documentsCollection->where('is_shared', 1)->count();
        @endphp

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-share-alt"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $sharedFilesCount }}</h3>
                <p>Shared Files</p>
            </div>
        </div>
    </div>

    <!-- Document Categories -->
    <div class="document-categories">
        <h2>Document Categories</h2>
        <div class="categories-grid">

            @php
            $categoryName = 'personal'; // the category you want to count
            $categoryCount = $documents->where('category', $categoryName)->count();
            @endphp
            <div class="category-card" data-category="{{ $categoryName }}">
                <div class="category-icon">
                    <i class="fas fa-user"></i>
                </div>
                <div class="category-info">
                    <h3>Personal Documents</h3>
                    <p>{{ $categoryCount }} files</p>
                </div>
            </div>

            @php
            $categoryName = 'contracts'; // the category you want to count
            $categoryCount = $documents->where('category', $categoryName)->count();
            @endphp
            <div class="category-card" data-category="{{ $categoryName }}">
                <div class="category-icon">
                    <i class="fas fa-file-contract"></i>
                </div>
                <div class="category-info">
                    <h3>Contracts & Agreements</h3>
                    <p>{{ $categoryCount }}  files</p>
                </div>
            </div>

            @php
            $categoryName = 'certificates'; // the category you want to count
            $categoryCount = $documents->where('category', $categoryName)->count();
            @endphp
            <div class="category-card" data-category="{{ $categoryName }}">
                <div class="category-icon">
                    <i class="fas fa-certificate"></i>
                </div>
                <div class="category-info">
                    <h3>Certificates</h3>
                    <p>{{ $categoryCount }} files</p>
                </div>
            </div>

            @php
            $categoryName = 'reports'; // the category you want to count
            $categoryCount = $documents->where('category', $categoryName)->count();
            @endphp
            <div class="category-card" data-category="{{ $categoryName }}">
                <div class="category-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="category-info">
                    <h3>Reports</h3>
                    <p>{{ $categoryCount }} files</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="documents-controls">
        <div class="search-section">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Search documents..." id="searchInput">
            </div>
            <div class="filter-section">
                <select id="categoryFilter" class="filter-select">
                    <option value="">All Categories</option>
                    <option value="personal">Personal Documents</option>
                    <option value="contracts">Contracts & Agreements</option>
                    <option value="certificates">Certificates</option>
                    <option value="reports">Reports</option>
                </select>
                <select id="typeFilter" class="filter-select">
                    <option value="">All Types</option>
                    <option value="pdf">PDF</option>
                    <option value="doc">Word Document</option>
                    <option value="img">Image</option>
                    <option value="xls">Excel</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Documents Grid -->
    <div class="documents-content">
        <div id="documentsGrid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 1.5rem;">
            @forelse($documents as $document)
            @php
            // Get file extension
            $ext = strtolower(pathinfo($document->file_path, PATHINFO_EXTENSION));

            // Default icon and colors
            $icon = 'fas fa-file';
            $iconColor = '#6b7280';
            $bgColor = '#f3f4f6';

            switch ($ext) {
            case 'pdf': $icon='fas fa-file-pdf'; $iconColor='#ef4444'; $bgColor='#fee2e2'; break;
            case 'doc': case 'docx': $icon='fas fa-file-word'; $iconColor='#2563eb'; $bgColor='#dbeafe'; break;
            case 'xls': case 'xlsx': $icon='fas fa-file-excel'; $iconColor='#059669'; $bgColor='#d1fae5'; break;
            case 'ppt': case 'pptx': $icon='fas fa-file-powerpoint'; $iconColor='#d97706'; $bgColor='#fef3c7'; break;
            case 'jpg': case 'jpeg': case 'png': $icon='fas fa-file-image'; $iconColor='#f59e0b'; $bgColor='#fef3c7'; break;
            }

            // Category label colors
            $categoryColors = [
            'personal'     => ['bg' => 'rgba(59,130,246,0.1)', 'text' => '#3B82F6'],
            'contracts'    => ['bg' => 'rgba(234,179,8,0.1)', 'text' => '#EAB308'],
            'certificates' => ['bg' => 'rgba(16,185,129,0.1)', 'text' => '#10B981'],
            'reports'      => ['bg' => 'rgba(239,68,68,0.1)', 'text' => '#EF4444'],
            ];

            $catKey = strtolower($document->category ?? '');
            $catBg = $categoryColors[$catKey]['bg'] ?? 'rgba(156,163,175,0.1)';
            $catText = $categoryColors[$catKey]['text'] ?? '#9CA3AF';

            // File size
            $size = null;
            $fullPath = storage_path('app/public/' . $document->file_path);
            if (file_exists($fullPath)) {
            $size = filesize($fullPath);
            }


            // Helper function to format file size
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


            <div class="document-card card p-4 border rounded shadow-sm transition hover:shadow-lg" data-category="{{ $document->category }}" data-type="{{ $ext }}">
                <div class="flex justify-between items-start mb-3">
                    <div style="width: 3rem; height: 3rem; background: {{ $bgColor }}; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; color: {{ $iconColor }};">
                        <i class="{{ $icon }}" style="font-size: 1.25rem;"></i>
                    </div>
                </div>
                <!-- Document Icon and Actions -->
                <div class="document-info">
                    <!-- Display the actual file name -->
                    <h4>{{ $document->file_name ?? 'No file name' }}</h4>

                    <!-- Display category -->
                    <p>{{ ucfirst($document->category ?? 'Uncategorized') }}</p>

                    <!-- Display meta info: upload date and file size -->
                    <div class="document-meta">
                        <span><i class="fas fa-calendar"></i> {{ \Carbon\Carbon::parse($document->created_at)->format('M d, Y') }}</span>
                        <span> <i class="fas fa-database"></i> {{ formatFileSize($document->file_size) }} </span>
                    </div>
                </div>

                <div class="document-actions">
                    <button class="action-btn" onclick="viewDocument('{{ asset($document->file_path) }}')"
                            title="View Document">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="action-btn"   onclick="window.location='{{ route('employee.documents.download', $document->id) }}';"
                            title="Download File">
                        <i class="fas fa-download"></i>
                    </button>
                    <button class="action-btn"
                            onclick="window.open('{{ route('employee.documents.share', $document->id) }}', '_blank');"
                            title="Share Document">
                        <i class="fas fa-share"></i>
                    </button>


                </div>

            </div>
            @empty
            <p style="color: #9ca3af;">No documents found.</p>
            @endforelse
        </div>

        <div class="flex justify-between items-center mt-4">
            <!-- Showing X to Y of Z documents -->
            <div class="text-gray-600 text-sm">
                Showing {{ $documents->firstItem() ?? 0 }} to {{ $documents->lastItem() ?? 0 }} of {{ $documents->total() }} documents
            </div>

            <!-- Pagination Buttons -->
            <div class="flex gap-1">
                <!-- Previous Page -->
                <a href="{{ $documents->previousPageUrl() ?? '#' }}"
                   class="btn btn-secondary"
                   style="padding: 0.5rem 0.75rem;"
                   @if(!$documents->onFirstPage()) @else disabled @endif>
                    <i class="fas fa-chevron-left"></i>
                </a>

                <!-- Page Numbers -->
                @foreach ($documents->getUrlRange(1, $documents->lastPage()) as $page => $url)
                <a href="{{ $url }}"
                   class="btn {{ $documents->currentPage() == $page ? 'btn-primary' : 'btn-secondary' }}"
                   style="padding: 0.5rem 0.75rem;">
                    {{ $page }}
                </a>
                @endforeach

                <!-- Next Page -->
                <a href="{{ $documents->nextPageUrl() ?? '#' }}"
                   class="btn btn-secondary"
                   style="padding: 0.5rem 0.75rem;"
                   @if($documents->hasMorePages()) @else disabled @endif>
                    <i class="fas fa-chevron-right"></i>
                </a>
            </div>
        </div>

    </div>



    <!-- Upload Modal -->
    <div id="uploadModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-upload"></i> Upload Document</h3>
                <button class="close-btn" onclick="closeUploadModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="uploadDocumentForm" method="POST"  action="{{ route('employee.documents.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="document_file">Document File(s) *</label>
                        <div class="file-upload-area" onclick="document.getElementById('document_file').click()">
                            <input type="file" id="document_file" name="files[]" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png" multiple required>
                            <div class="file-upload-icon">
                                <i class="fas fa-cloud-upload-alt"></i>
                            </div>
                            <div class="file-upload-text">
                                Click to upload or drag and drop
                            </div>
                            <div class="file-upload-subtext">
                                Supported formats: PDF, DOC, DOCX, XLS, XLSX, JPG, JPEG, PNG (Max: 10MB per file)
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
                        <label for="documentCategory">Category *</label>
                        <select id="documentCategory" name="category" class="form-control" required>
                            <option value="personal">Personal Documents</option>
                            <option value="contracts">Contracts & Agreements</option>
                            <option value="certificates">Certificates</option>
                            <option value="reports">Reports</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="documentDescription">Description (Optional)</label>
                        <textarea id="documentDescription" name="description" class="form-control" rows="3" placeholder="Enter document description..."></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeUploadModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload"></i> Upload
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>

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

    .documents-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0;
    }

    .breadcrumb-nav {
        margin-bottom: 2rem;
    }

    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 1rem 1.5rem;
        background: white;
        border-radius: 0.75rem;
        box-shadow: var(--shadow-lg);
        border: 1px solid var(--border-light);
        transition: all 0.3s ease;
    }

    .breadcrumb:hover {
        box-shadow: var(--shadow-xl);
        transform: translateY(-2px);
    }

    .breadcrumb-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--text-secondary);
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.3s ease;
        padding: 0.25rem 0.5rem;
        border-radius: 0.5rem;
    }

    .breadcrumb-item:hover {
        color: var(--redcode-primary);
        background: var(--redcode-primary-light);
    }

    .breadcrumb-item.active {
        color: var(--redcode-primary);
        font-weight: 600;
        background: var(--redcode-primary-light);
    }

    .breadcrumb-separator {
        color: var(--text-light);
        font-size: 0.875rem;
    }

    .documents-header {
        background: var(--gradient-hero);
        background-size: 400% 400%;
        animation: gradientShift 18s ease infinite;
        color: white;
        padding: 2rem;
        border-radius: 1rem;
        margin-bottom: 2rem;
        box-shadow: var(--shadow-xl);
        position: relative;
        overflow: hidden;
    }

    @keyframes gradientShift {
        0% { background-position: 0% 50%; }
        25% { background-position: 100% 50%; }
        50% { background-position: 100% 100%; }
        75% { background-position: 0% 100%; }
        100% { background-position: 0% 50%; }
    }

    .documents-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
        z-index: 1;
    }

    .header-info h1 {
        margin: 0;
        font-size: 2rem;
        font-weight: 700;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .header-info p {
        margin: 0.5rem 0 0 0;
        color: rgba(255, 255, 255, 0.9);
        font-size: 1.1rem;
    }

    .header-actions {
        display: flex;
        gap: 1rem;
    }

    .header-actions .btn {
        background: rgba(255, 255, 255, 0.2);
        border: 2px solid rgba(255, 255, 255, 0.3);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        font-weight: 600;
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
    }

    .header-actions .btn:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-2px);
    }

    .documents-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 1rem;
        box-shadow: var(--shadow-lg);
        border: 1px solid var(--border-light);
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-xl);
        border-color: var(--redcode-primary);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        background: var(--gradient-hero);
        background-size: 400% 400%;
        animation: gradientShift 18s ease infinite;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        box-shadow: var(--shadow-sm);
    }

    .stat-info h3 {
        margin: 0;
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
    }

    .stat-info p {
        margin: 0.25rem 0 0 0;
        color: var(--text-secondary);
        font-size: 0.875rem;
    }

    .document-categories {
        margin-bottom: 2rem;
    }

    .document-categories h2 {
        color: var(--text-primary);
        margin-bottom: 1rem;
        font-size: 1.5rem;
        font-weight: 600;
    }

    .categories-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
    }

    .category-card {
        background: white;
        padding: 1.5rem;
        border-radius: 1rem;
        box-shadow: var(--shadow-lg);
        border: 1px solid var(--border-light);
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
    }

    .category-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-xl);
        border-color: var(--redcode-primary);
    }

    .category-card.active {
        border-color: var(--redcode-primary);
        background: var(--redcode-primary-light);
    }

    .category-icon {
        width: 50px;
        height: 50px;
        background: var(--gradient-hero);
        background-size: 400% 400%;
        animation: gradientShift 18s ease infinite;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.25rem;
        box-shadow: var(--shadow-sm);
    }

    .category-info h3 {
        margin: 0;
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--text-primary);
    }

    .category-info p {
        margin: 0.25rem 0 0 0;
        color: var(--text-secondary);
        font-size: 0.875rem;
    }

    .documents-controls {
        background: white;
        padding: 1.5rem;
        border-radius: 1rem;
        box-shadow: var(--shadow-lg);
        border: 1px solid var(--border-light);
        margin-bottom: 2rem;
    }

    .search-section {
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    .search-box {
        flex: 1;
        position: relative;
    }

    .search-box i {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-secondary);
    }

    .search-box input {
        width: 100%;
        padding: 0.875rem 1rem 0.875rem 2.5rem;
        border: 2px solid var(--border-light);
        border-radius: 0.75rem;
        font-size: 1rem;
        background: var(--bg-primary);
        transition: all 0.3s ease;
    }

    .search-box input:focus {
        outline: none;
        border-color: var(--redcode-primary);
        box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.1);
    }

    .filter-section {
        display: flex;
        gap: 1rem;
    }

    .filter-select {
        padding: 0.875rem 1rem;
        border: 2px solid var(--border-light);
        border-radius: 0.75rem;
        font-size: 1rem;
        background: var(--bg-primary);
        transition: all 0.3s ease;
        min-width: 150px;
    }

    .filter-select:focus {
        outline: none;
        border-color: var(--redcode-primary);
        box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.1);
    }

    .documents-content {
        background: white;
        padding: 2rem;
        border-radius: 1rem;
        box-shadow: var(--shadow-lg);
        border: 1px solid var(--border-light);
    }

    .documents-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
    }

    .document-card {
        background: var(--bg-primary);
        border: 2px solid var(--border-light);
        border-radius: 1rem;
        padding: 1.5rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        box-shadow: var(--shadow-sm);
    }

    .document-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--redcode-primary);
        transform: scaleY(0);
        transition: transform 0.3s ease;
    }

    .document-card:hover::before {
        transform: scaleY(1);
    }

    .document-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
        border-color: var(--redcode-primary);
    }

    .document-icon {
        width: 60px;
        height: 60px;
        background: var(--gradient-hero);
        background-size: 400% 400%;
        animation: gradientShift 18s ease infinite;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        margin-bottom: 1rem;
        box-shadow: var(--shadow-sm);
    }

    .document-info h4 {
        margin: 0 0 0.5rem 0;
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--text-primary);
        word-break: break-word;
    }

    .document-info p {
        margin: 0 0 1rem 0;
        color: var(--text-secondary);
        font-size: 0.875rem;
    }

    .document-meta {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
        margin-bottom: 1rem;
    }

    .document-meta span {
        color: var(--text-light);
        font-size: 0.8rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .document-actions {
        display: flex;
        gap: 0.5rem;
        justify-content: flex-end;
    }

    .action-btn {
        width: 35px;
        height: 35px;
        border: none;
        border-radius: 50%;
        background: var(--redcode-primary-light);
        color: var(--redcode-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 0.875rem;
    }

    .action-btn:hover {
        background: var(--redcode-primary);
        color: white;
        transform: scale(1.1);
    }

    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        backdrop-filter: blur(5px);
    }

    .modal.active {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        background: white;
        border-radius: 1rem;
        width: 90%;
        max-width: 500px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: var(--shadow-xl);
        animation: modalSlideIn 0.3s ease;
    }

    @keyframes modalSlideIn {
        from {
            transform: translateY(-50px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .modal-header {
        padding: 1.5rem;
        border-bottom: 1px solid var(--border-light);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: var(--gradient-hero);
        background-size: 400% 400%;
        animation: gradientShift 18s ease infinite;
        color: white;
        border-radius: 1rem 1rem 0 0;
    }

    .modal-header h3 {
        margin: 0;
        font-size: 1.25rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .close-btn {
        background: none;
        border: none;
        color: white;
        font-size: 1.25rem;
        cursor: pointer;
        padding: 0.5rem;
        border-radius: 50%;
        transition: all 0.3s ease;
    }

    .close-btn:hover {
        background: rgba(255, 255, 255, 0.2);
    }

    .modal-body {
        padding: 1.5rem;
    }

    .upload-area {
        border: 2px dashed var(--border-medium);
        border-radius: 1rem;
        padding: 2rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-bottom: 1.5rem;
        position: relative;
    }

    .upload-area:hover {
        border-color: var(--redcode-primary);
        background: var(--redcode-primary-light);
    }

    .upload-area.dragover {
        border-color: var(--redcode-primary);
        background: var(--redcode-primary-light);
    }

    .upload-area i {
        font-size: 3rem;
        color: var(--redcode-primary);
        margin-bottom: 1rem;
    }

    .upload-area h4 {
        margin: 0 0 0.5rem 0;
        color: var(--text-primary);
        font-weight: 600;
    }

    .upload-area p {
        margin: 0;
        color: var(--text-secondary);
    }

    .upload-area input[type="file"] {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }

    .form-group {
        margin-bottom: 1rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: var(--text-primary);
    }

    .form-control {
        width: 100%;
        padding: 0.875rem 1rem;
        border: 2px solid var(--border-light);
        border-radius: 0.75rem;
        font-size: 1rem;
        background: var(--bg-primary);
        transition: all 0.3s ease;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--redcode-primary);
        box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.1);
    }

    .modal-footer {
        padding: 1.5rem;
        border-top: 1px solid var(--border-light);
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        background: var(--bg-secondary);
        border-radius: 0 0 1rem 1rem;
    }

    .btn {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 0.5rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
        font-size: 0.875rem;
    }

    .btn-primary {
        background: var(--gradient-primary);
        color: white;
        box-shadow: var(--shadow-sm);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .btn-secondary {
        background: var(--gradient-secondary);
        color: white;
        box-shadow: var(--shadow-sm);
    }

    .btn-secondary:hover {
        background: var(--redcode-dark);
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    @media (max-width: 768px) {
        .header-content {
            flex-direction: column;
            gap: 1rem;
        }

        .documents-stats {
            grid-template-columns: 1fr;
        }

        .categories-grid {
            grid-template-columns: 1fr;
        }

        .search-section {
            flex-direction: column;
            gap: 1rem;
        }

        .filter-section {
            width: 100%;
        }

        .filter-select {
            flex: 1;
        }

        .documents-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<script>
    let currentViewMode = 'grid';

    function toggleViewMode() {
        const grid = document.getElementById('documentsGrid');
        const icon = document.getElementById('viewModeIcon');
        const text = document.getElementById('viewModeText');

        if (currentViewMode === 'grid') {
            grid.classList.add('list-view');
            icon.className = 'fas fa-th-list';
            text.textContent = 'List View';
            currentViewMode = 'list';
        } else {
            grid.classList.remove('list-view');
            icon.className = 'fas fa-th-large';
            text.textContent = 'Grid View';
            currentViewMode = 'grid';
        }
    }

    function openUploadModal() {
        document.getElementById('uploadModal').classList.add('active');
    }

    function closeUploadModal() {
        document.getElementById('uploadModal').classList.remove('active');
    }

    // function uploadDocument() {
    //     // Get selected files and input values
    //     let files = document.getElementById('fileInput').files;
    //     let category = document.getElementById('documentCategory').value;
    //     let description = document.getElementById('documentDescription').value;
    //
    //     if (files.length === 0) {
    //         alert('Please select at least one file.');
    //         return;
    //     }
    //
    //     // Create FormData object
    //     let formData = new FormData();
    //
    //     for (let i = 0; i < files.length; i++) {
    //         formData.append('files[]', files[i]); // multiple files
    //     }
    //
    //     formData.append('category', category);
    //     formData.append('description', description);
    //     formData.append('employee_id', document.querySelector('meta[name="user-id"]').content); // use meta tag for dynamic user id
    //
    //     // Send AJAX request to backend
    //     fetch('/employees/documents/store', { // adjust URL if different
    //         method: 'POST',
    //         headers: {
    //             'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    //         },
    //         body: formData
    //     })
    //         .then(async response => {
    //             if (!response.ok) {
    //                 const errText = await response.text();
    //                 throw new Error(errText);
    //             }
    //             return response.json();
    //         })
    //         .then(data => {
    //             if (data.success) {
    //                 alert(data.message);
    //                 closeUploadModal(); // close the modal
    //                 location.reload();  // reload page to show new documents
    //             } else {
    //                 alert(data.message || 'Upload failed.');
    //             }
    //         })
    //         .catch(err => {
    //             console.error('Upload error:', err);
    //             alert('An error occurred while uploading. Check console for details.');
    //         });
    // }

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

            // Update upload area styling
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

    // Remove selected file
    function removeFile() {
        const fileInput = document.getElementById('document_file');
        fileInput.value = '';
        document.getElementById('file-preview').style.display = 'none';

        const uploadArea = document.querySelector('.file-upload-area');
        uploadArea.style.borderColor = '';
        uploadArea.style.background = '';
        const icon = uploadArea.querySelector('.file-upload-icon i');
        icon.className = 'fas fa-cloud-upload-alt';
        icon.style.color = '';
        const text = uploadArea.querySelector('.file-upload-text');
        text.textContent = 'Click to upload or drag and drop';
        text.style.color = '';
    }


    function viewDocument(fileUrl) {
        // Open the document in a new browser tab
        window.open(fileUrl, '_blank');
    }

    // function shareDocument(documentId) {
    //     fetch(`/employee-documents/share/${documentId}`, {
    //         method: 'GET',
    //         credentials: 'same-origin',
    //         headers: { 'Accept': 'application/json' }
    //     })
    //         .then(response => {
    //             if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
    //             return response.json();
    //         })
    //         .then(data => {
    //             const shareUrl = data.share_url;
    //             navigator.clipboard.writeText(shareUrl)
    //                 .then(() => alert(`Share link copied:\n${shareUrl}`))
    //                 .catch(() => alert(`Share link: ${shareUrl}`));
    //         })
    //         .catch(err => {
    //             console.error(err);
    //             alert('Failed to generate share link. Check console.');
    //         });
    // }

    function showMessage(message, type) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `message message-${type}`;
        messageDiv.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'info-circle'}"></i>
            ${message}
        `;
        messageDiv.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${type === 'success' ? 'var(--redcode-green)' : 'var(--redcode-blue)'};
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 0.5rem;
            box-shadow: var(--shadow-lg);
            z-index: 1001;
            animation: slideIn 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        `;

        document.body.appendChild(messageDiv);

        setTimeout(() => {
            messageDiv.remove();
        }, 3000);
    }

    // Search functionality
    document.getElementById('searchInput').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        filterDocuments();
    });

    // Filter functionality
    document.getElementById('categoryFilter').addEventListener('change', filterDocuments);
    document.getElementById('typeFilter').addEventListener('change', filterDocuments);

    function filterDocuments() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const categoryFilter = document.getElementById('categoryFilter').value;
        const typeFilter = document.getElementById('typeFilter').value;
        const documents = document.querySelectorAll('.document-card');

        documents.forEach(doc => {
            const title = doc.querySelector('h4').textContent.toLowerCase();
            const category = doc.dataset.category;
            const type = doc.dataset.type;

            const matchesSearch = title.includes(searchTerm);
            const matchesCategory = !categoryFilter || category === categoryFilter;
            const matchesType = !typeFilter || type === typeFilter;

            if (matchesSearch && matchesCategory && matchesType) {
                doc.style.display = 'block';
            } else {
                doc.style.display = 'none';
            }
        });
    }

    // Category card click functionality
    document.querySelectorAll('.category-card').forEach(card => {
        card.addEventListener('click', function() {
            const category = this.dataset.category;
            document.getElementById('categoryFilter').value = category;
            filterDocuments();

            // Update active state
            document.querySelectorAll('.category-card').forEach(c => c.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // Drag and drop functionality
    // const uploadArea = document.getElementById('uploadArea');

    // uploadArea.addEventListener('dragover', function(e) {
    //     e.preventDefault();
    //     this.classList.add('dragover');
    // });
    //
    // uploadArea.addEventListener('dragleave', function(e) {
    //     e.preventDefault();
    //     this.classList.remove('dragover');
    // });
    //
    // uploadArea.addEventListener('drop', function(e) {
    //     e.preventDefault();
    //     this.classList.remove('dragover');
    //     const files = e.dataTransfer.files;
    //     document.getElementById('fileInput').files = files;
    // });

    // Close modal when clicking outside
    document.getElementById('uploadModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeUploadModal();
        }
    });

    // Add slide-in animation keyframes
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .documents-grid.list-view {
            grid-template-columns: 1fr;
        }

        .documents-grid.list-view .document-card {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
        }

        .documents-grid.list-view .document-icon {
            margin-bottom: 0;
            flex-shrink: 0;
        }

        .documents-grid.list-view .document-info {
            flex: 1;
        }

        .documents-grid.list-view .document-meta {
            display: flex;
            flex-direction: row;
            gap: 1rem;
            margin-bottom: 0;
        }

        .documents-grid.list-view .document-actions {
            flex-shrink: 0;
        }
    `;
    document.head.appendChild(style);
</script>
@endsection
