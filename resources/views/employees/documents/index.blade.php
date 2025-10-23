@extends('layouts.employee')

@section('title', 'My Documents')
<link rel="stylesheet" href="{{ asset('css/Employee/myDocuments.css') }}">
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
                <h3>24</h3>
                <p>Total Documents</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-cloud-upload-alt"></i>
            </div>
            <div class="stat-info">
                <h3>2.4 GB</h3>
                <p>Storage Used</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-info">
                <h3>3</h3>
                <p>Recent Uploads</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-share-alt"></i>
            </div>
            <div class="stat-info">
                <h3>5</h3>
                <p>Shared Files</p>
            </div>
        </div>
    </div>

    <!-- Document Categories -->
    <div class="document-categories">
        <h2>Document Categories</h2>
        <div class="categories-grid">
            <div class="category-card" data-category="personal">
                <div class="category-icon">
                    <i class="fas fa-user"></i>
                </div>
                <div class="category-info">
                    <h3>Personal Documents</h3>
                    <p>12 files</p>
                </div>
            </div>
            <div class="category-card" data-category="contracts">
                <div class="category-icon">
                    <i class="fas fa-file-contract"></i>
                </div>
                <div class="category-info">
                    <h3>Contracts & Agreements</h3>
                    <p>5 files</p>
                </div>
            </div>
            <div class="category-card" data-category="certificates">
                <div class="category-icon">
                    <i class="fas fa-certificate"></i>
                </div>
                <div class="category-info">
                    <h3>Certificates</h3>
                    <p>4 files</p>
                </div>
            </div>
            <div class="category-card" data-category="reports">
                <div class="category-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="category-info">
                    <h3>Reports</h3>
                    <p>3 files</p>
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
        <div class="documents-grid" id="documentsGrid">
            <!-- Personal Documents -->
            <div class="document-card" data-category="personal" data-type="pdf">
                <div class="document-icon">
                    <i class="fas fa-file-pdf"></i>
                </div>
                <div class="document-info">
                    <h4>Employment_Contract.pdf</h4>
                    <p>Personal Documents</p>
                    <div class="document-meta">
                        <span><i class="fas fa-calendar"></i> Jan 15, 2024</span>
                        <span><i class="fas fa-database"></i> 2.4 MB</span>
                    </div>
                </div>
                <div class="document-actions">
                    <button class="action-btn" onclick="viewDocument('Employment_Contract.pdf')">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="action-btn" onclick="downloadDocument('Employment_Contract.pdf')">
                        <i class="fas fa-download"></i>
                    </button>
                    <button class="action-btn" onclick="shareDocument('Employment_Contract.pdf')">
                        <i class="fas fa-share"></i>
                    </button>
                </div>
            </div>

            <div class="document-card" data-category="personal" data-type="img">
                <div class="document-icon">
                    <i class="fas fa-file-image"></i>
                </div>
                <div class="document-info">
                    <h4>Profile_Photo.jpg</h4>
                    <p>Personal Documents</p>
                    <div class="document-meta">
                        <span><i class="fas fa-calendar"></i> Jan 10, 2024</span>
                        <span><i class="fas fa-database"></i> 1.2 MB</span>
                    </div>
                </div>
                <div class="document-actions">
                    <button class="action-btn" onclick="viewDocument('Profile_Photo.jpg')">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="action-btn" onclick="downloadDocument('Profile_Photo.jpg')">
                        <i class="fas fa-download"></i>
                    </button>
                    <button class="action-btn" onclick="shareDocument('Profile_Photo.jpg')">
                        <i class="fas fa-share"></i>
                    </button>
                </div>
            </div>

            <!-- Contracts -->
            <div class="document-card" data-category="contracts" data-type="pdf">
                <div class="document-icon">
                    <i class="fas fa-file-contract"></i>
                </div>
                <div class="document-info">
                    <h4>NDA_Agreement.pdf</h4>
                    <p>Contracts & Agreements</p>
                    <div class="document-meta">
                        <span><i class="fas fa-calendar"></i> Dec 20, 2023</span>
                        <span><i class="fas fa-database"></i> 1.8 MB</span>
                    </div>
                </div>
                <div class="document-actions">
                    <button class="action-btn" onclick="viewDocument('NDA_Agreement.pdf')">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="action-btn" onclick="downloadDocument('NDA_Agreement.pdf')">
                        <i class="fas fa-download"></i>
                    </button>
                    <button class="action-btn" onclick="shareDocument('NDA_Agreement.pdf')">
                        <i class="fas fa-share"></i>
                    </button>
                </div>
            </div>

            <!-- Certificates -->
            <div class="document-card" data-category="certificates" data-type="pdf">
                <div class="document-icon">
                    <i class="fas fa-certificate"></i>
                </div>
                <div class="document-info">
                    <h4>AWS_Certificate.pdf</h4>
                    <p>Certificates</p>
                    <div class="document-meta">
                        <span><i class="fas fa-calendar"></i> Nov 15, 2023</span>
                        <span><i class="fas fa-database"></i> 3.2 MB</span>
                    </div>
                </div>
                <div class="document-actions">
                    <button class="action-btn" onclick="viewDocument('AWS_Certificate.pdf')">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="action-btn" onclick="downloadDocument('AWS_Certificate.pdf')">
                        <i class="fas fa-download"></i>
                    </button>
                    <button class="action-btn" onclick="shareDocument('AWS_Certificate.pdf')">
                        <i class="fas fa-share"></i>
                    </button>
                </div>
            </div>

            <!-- Reports -->
            <div class="document-card" data-category="reports" data-type="xls">
                <div class="document-icon">
                    <i class="fas fa-file-excel"></i>
                </div>
                <div class="document-info">
                    <h4>Performance_Report.xlsx</h4>
                    <p>Reports</p>
                    <div class="document-meta">
                        <span><i class="fas fa-calendar"></i> Oct 30, 2023</span>
                        <span><i class="fas fa-database"></i> 890 KB</span>
                    </div>
                </div>
                <div class="document-actions">
                    <button class="action-btn" onclick="viewDocument('Performance_Report.xlsx')">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="action-btn" onclick="downloadDocument('Performance_Report.xlsx')">
                        <i class="fas fa-download"></i>
                    </button>
                    <button class="action-btn" onclick="shareDocument('Performance_Report.xlsx')">
                        <i class="fas fa-share"></i>
                    </button>
                </div>
            </div>

            <div class="document-card" data-category="personal" data-type="doc">
                <div class="document-icon">
                    <i class="fas fa-file-word"></i>
                </div>
                <div class="document-info">
                    <h4>Resume_2024.docx</h4>
                    <p>Personal Documents</p>
                    <div class="document-meta">
                        <span><i class="fas fa-calendar"></i> Jan 05, 2024</span>
                        <span><i class="fas fa-database"></i> 1.5 MB</span>
                    </div>
                </div>
                <div class="document-actions">
                    <button class="action-btn" onclick="viewDocument('Resume_2024.docx')">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="action-btn" onclick="downloadDocument('Resume_2024.docx')">
                        <i class="fas fa-download"></i>
                    </button>
                    <button class="action-btn" onclick="shareDocument('Resume_2024.docx')">
                        <i class="fas fa-share"></i>
                    </button>
                </div>
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
            <div class="modal-body">
                <div class="upload-area" id="uploadArea">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <h4>Drag & Drop files here</h4>
                    <p>or click to browse</p>
                    <input type="file" id="fileInput" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.xlsx,.xls">
                </div>
                <div class="upload-form">
                    <div class="form-group">
                        <label for="documentCategory">Category</label>
                        <select id="documentCategory" class="form-control">
                            <option value="personal">Personal Documents</option>
                            <option value="contracts">Contracts & Agreements</option>
                            <option value="certificates">Certificates</option>
                            <option value="reports">Reports</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="documentDescription">Description (Optional)</label>
                        <textarea id="documentDescription" class="form-control" rows="3" placeholder="Enter document description..."></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeUploadModal()">Cancel</button>
                <button class="btn btn-primary" onclick="uploadDocument()">
                    <i class="fas fa-upload"></i>
                    Upload
                </button>
            </div>
        </div>
    </div>
</div>

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

    function uploadDocument() {
        const fileInput = document.getElementById('fileInput');
        const category = document.getElementById('documentCategory').value;
        const description = document.getElementById('documentDescription').value;

        if (fileInput.files.length === 0) {
            alert('Please select at least one file to upload.');
            return;
        }

        // Show success message
        showMessage('Documents uploaded successfully!', 'success');
        closeUploadModal();

        // Reset form
        fileInput.value = '';
        document.getElementById('documentDescription').value = '';
    }

    function viewDocument(filename) {
        showMessage(`Opening ${filename}...`, 'info');
    }

    function downloadDocument(filename) {
        showMessage(`Downloading ${filename}...`, 'info');
    }

    function shareDocument(filename) {
        showMessage(`Sharing ${filename}...`, 'info');
    }

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
    const uploadArea = document.getElementById('uploadArea');

    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('dragover');
    });

    uploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
    });

    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
        const files = e.dataTransfer.files;
        document.getElementById('fileInput').files = files;
    });

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
