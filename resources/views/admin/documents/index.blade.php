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
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
    backdrop-filter: blur(4px);
}

.modal.show {
    display: flex;
    align-items: center;
    justify-content: center;
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
                <input type="text" placeholder="Search documents..." class="form-input" style="width: 300px;">
                <select class="form-select" style="width: 200px;">
                    <option>All Categories</option>
                    <option>Policies</option>
                    <option>Forms</option>
                    <option>Contracts</option>
                    <option>Reports</option>
                    <option>Training</option>
                </select>
                <select class="form-select" style="width: 200px;">
                    <option>All Types</option>
                    <option>PDF</option>
                    <option>DOC</option>
                    <option>XLS</option>
                    <option>PPT</option>
                </select>
            </div>
            <button class="btn btn-secondary">
                <i class="fas fa-filter"></i>
                Filter
            </button>
        </div>

        <!-- Documents Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem;">
            <div class="card">
                <div class="card-body">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                        <div style="width: 3rem; height: 3rem; background: rgba(239, 68, 68, 0.1); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; color: var(--danger);">
                            <i class="fas fa-file-pdf" style="font-size: 1.25rem;"></i>
                        </div>
                        <div class="flex gap-1">
                            <button class="btn btn-secondary" style="padding: 0.5rem;" onclick="viewDocument(1)" title="View Document">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-secondary" style="padding: 0.5rem;" onclick="downloadDocument(1)" title="Download Document">
                                <i class="fas fa-download"></i>
                            </button>
                            <button class="btn btn-warning" style="padding: 0.5rem;" onclick="editDocument(1)" title="Edit Document">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger" style="padding: 0.5rem;" onclick="deleteDocument(1)" title="Delete Document">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <h4 style="font-size: 1rem; font-weight: 600; color: var(--gray-800); margin-bottom: 0.5rem;">Employee Handbook 2024</h4>
                    <p style="font-size: 0.875rem; color: var(--gray-600); margin-bottom: 1rem; line-height: 1.5;">Comprehensive guide covering company policies, procedures, and employee benefits.</p>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                        <span class="badge" style="background: rgba(59, 130, 246, 0.1); color: var(--primary); padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem;">Policies</span>
                        <div style="font-size: 0.875rem; color: var(--gray-500);">2.4 MB</div>
                    </div>
                    <div style="font-size: 0.75rem; color: var(--gray-500);">
                        <div>Uploaded by HR Team</div>
                        <div>Nov 15, 2024 • 234 downloads</div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                        <div style="width: 3rem; height: 3rem; background: rgba(59, 130, 246, 0.1); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; color: var(--primary);">
                            <i class="fas fa-file-word" style="font-size: 1.25rem;"></i>
                        </div>
                        <div class="flex gap-1">
                            <button class="btn btn-secondary" style="padding: 0.5rem;" onclick="viewDocument(2)" title="View Document">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-secondary" style="padding: 0.5rem;" onclick="downloadDocument(2)" title="Download Document">
                                <i class="fas fa-download"></i>
                            </button>
                            <button class="btn btn-warning" style="padding: 0.5rem;" onclick="editDocument(2)" title="Edit Document">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger" style="padding: 0.5rem;" onclick="deleteDocument(2)" title="Delete Document">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <h4 style="font-size: 1rem; font-weight: 600; color: var(--gray-800); margin-bottom: 0.5rem;">Leave Application Form</h4>
                    <p style="font-size: 0.875rem; color: var(--gray-600); margin-bottom: 1rem; line-height: 1.5;">Standard form template for employee leave requests and approvals.</p>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                        <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: var(--success); padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem;">Forms</span>
                        <div style="font-size: 0.875rem; color: var(--gray-500);">156 KB</div>
                    </div>
                    <div style="font-size: 0.75rem; color: var(--gray-500);">
                        <div>Uploaded by Admin</div>
                        <div>Nov 10, 2024 • 89 downloads</div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                        <div style="width: 3rem; height: 3rem; background: rgba(16, 185, 129, 0.1); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; color: var(--success);">
                            <i class="fas fa-file-excel" style="font-size: 1.25rem;"></i>
                        </div>
                        <div class="flex gap-1">
                            <button class="btn btn-secondary" style="padding: 0.5rem;" onclick="viewDocument(3)" title="View Document">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-secondary" style="padding: 0.5rem;" onclick="downloadDocument(3)" title="Download Document">
                                <i class="fas fa-download"></i>
                            </button>
                            <button class="btn btn-warning" style="padding: 0.5rem;" onclick="editDocument(3)" title="Edit Document">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger" style="padding: 0.5rem;" onclick="deleteDocument(3)" title="Delete Document">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <h4 style="font-size: 1rem; font-weight: 600; color: var(--gray-800); margin-bottom: 0.5rem;">Employee Database Template</h4>
                    <p style="font-size: 0.875rem; color: var(--gray-600); margin-bottom: 1rem; line-height: 1.5;">Excel template for maintaining employee records and information.</p>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                        <span class="badge" style="background: rgba(245, 158, 11, 0.1); color: var(--warning); padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem;">Templates</span>
                        <div style="font-size: 0.875rem; color: var(--gray-500);">45 KB</div>
                    </div>
                    <div style="font-size: 0.75rem; color: var(--gray-500);">
                        <div>Uploaded by IT Team</div>
                        <div>Nov 5, 2024 • 67 downloads</div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                        <div style="width: 3rem; height: 3rem; background: rgba(245, 158, 11, 0.1); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; color: var(--warning);">
                            <i class="fas fa-file-powerpoint" style="font-size: 1.25rem;"></i>
                        </div>
                        <div class="flex gap-1">
                            <button class="btn btn-secondary" style="padding: 0.5rem;" onclick="viewDocument(4)" title="View Document">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-secondary" style="padding: 0.5rem;" onclick="downloadDocument(4)" title="Download Document">
                                <i class="fas fa-download"></i>
                            </button>
                            <button class="btn btn-warning" style="padding: 0.5rem;" onclick="editDocument(4)" title="Edit Document">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger" style="padding: 0.5rem;" onclick="deleteDocument(4)" title="Delete Document">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <h4 style="font-size: 1rem; font-weight: 600; color: var(--gray-800); margin-bottom: 0.5rem;">Onboarding Presentation</h4>
                    <p style="font-size: 0.875rem; color: var(--gray-600); margin-bottom: 1rem; line-height: 1.5;">New employee orientation presentation covering company culture and processes.</p>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                        <span class="badge" style="background: rgba(99, 102, 241, 0.1); color: var(--secondary); padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem;">Training</span>
                        <div style="font-size: 0.875rem; color: var(--gray-500);">8.7 MB</div>
                    </div>
                    <div style="font-size: 0.75rem; color: var(--gray-500);">
                        <div>Uploaded by HR Team</div>
                        <div>Oct 28, 2024 • 123 downloads</div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                        <div style="width: 3rem; height: 3rem; background: rgba(6, 182, 212, 0.1); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; color: var(--info);">
                            <i class="fas fa-file-contract" style="font-size: 1.25rem;"></i>
                        </div>
                        <div class="flex gap-1">
                            <button class="btn btn-secondary" style="padding: 0.5rem;" onclick="viewDocument(5)" title="View Document">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-secondary" style="padding: 0.5rem;" onclick="downloadDocument(5)" title="Download Document">
                                <i class="fas fa-download"></i>
                            </button>
                            <button class="btn btn-warning" style="padding: 0.5rem;" onclick="editDocument(5)" title="Edit Document">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger" style="padding: 0.5rem;" onclick="deleteDocument(5)" title="Delete Document">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <h4 style="font-size: 1rem; font-weight: 600; color: var(--gray-800); margin-bottom: 0.5rem;">Employment Contract Template</h4>
                    <p style="font-size: 0.875rem; color: var(--gray-600); margin-bottom: 1rem; line-height: 1.5;">Standard employment contract template with terms and conditions.</p>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                        <span class="badge" style="background: rgba(6, 182, 212, 0.1); color: var(--info); padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem;">Contracts</span>
                        <div style="font-size: 0.875rem; color: var(--gray-500);">234 KB</div>
                    </div>
                    <div style="font-size: 0.75rem; color: var(--gray-500);">
                        <div>Uploaded by Legal Team</div>
                        <div>Oct 20, 2024 • 45 downloads</div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                        <div style="width: 3rem; height: 3rem; background: rgba(99, 102, 241, 0.1); border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; color: var(--secondary);">
                            <i class="fas fa-file-chart-line" style="font-size: 1.25rem;"></i>
                        </div>
                        <div class="flex gap-1">
                            <button class="btn btn-secondary" style="padding: 0.5rem;" onclick="viewDocument(6)" title="View Document">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-secondary" style="padding: 0.5rem;" onclick="downloadDocument(6)" title="Download Document">
                                <i class="fas fa-download"></i>
                            </button>
                            <button class="btn btn-warning" style="padding: 0.5rem;" onclick="editDocument(6)" title="Edit Document">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger" style="padding: 0.5rem;" onclick="deleteDocument(6)" title="Delete Document">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <h4 style="font-size: 1rem; font-weight: 600; color: var(--gray-800); margin-bottom: 0.5rem;">Q3 Performance Report</h4>
                    <p style="font-size: 0.875rem; color: var(--gray-600); margin-bottom: 1rem; line-height: 1.5;">Quarterly performance analysis and employee evaluation summary.</p>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                        <span class="badge" style="background: rgba(99, 102, 241, 0.1); color: var(--secondary); padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem;">Reports</span>
                        <div style="font-size: 0.875rem; color: var(--gray-500);">1.8 MB</div>
                    </div>
                    <div style="font-size: 0.75rem; color: var(--gray-500);">
                        <div>Uploaded by Analytics Team</div>
                        <div>Oct 15, 2024 • 78 downloads</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="flex justify-between items-center mt-4">
            <div style="color: var(--gray-600); font-size: 0.875rem;">
                Showing 1 to 6 of 24 documents
            </div>
            <div class="flex gap-1">
                <button class="btn btn-secondary" style="padding: 0.5rem 0.75rem;">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="btn btn-primary" style="padding: 0.5rem 0.75rem;">1</button>
                <button class="btn btn-secondary" style="padding: 0.5rem 0.75rem;">2</button>
                <button class="btn btn-secondary" style="padding: 0.5rem 0.75rem;">3</button>
                <button class="btn btn-secondary" style="padding: 0.5rem 0.75rem;">4</button>
                <button class="btn btn-secondary" style="padding: 0.5rem 0.75rem;">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Document Statistics -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-top: 2rem;">
    <div class="card">
        <div class="card-body text-center">
            <div style="font-size: 2rem; font-weight: 700; color: var(--primary); margin-bottom: 0.5rem;">24</div>
            <div style="color: var(--gray-600); font-weight: 500;">Total Documents</div>
        </div>
    </div>
    <div class="card">
        <div class="card-body text-center">
            <div style="font-size: 2rem; font-weight: 700; color: var(--success); margin-bottom: 0.5rem;">836</div>
            <div style="color: var(--gray-600); font-weight: 500;">Total Downloads</div>
        </div>
    </div>
    <div class="card">
        <div class="card-body text-center">
            <div style="font-size: 2rem; font-weight: 700; color: var(--warning); margin-bottom: 0.5rem;">15.2</div>
            <div style="color: var(--gray-600); font-weight: 500;">Total Size (MB)</div>
        </div>
    </div>
    <div class="card">
        <div class="card-body text-center">
            <div style="font-size: 2rem; font-weight: 700; color: var(--info); margin-bottom: 0.5rem;">6</div>
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
        <form id="addDocumentForm" method="POST" action="{{ route('documents.store') }}" enctype="multipart/form-data">
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
                        <select id="document_department" name="department_id">
                            <option value="">All Departments</option>
                            <option value="1">Human Resources</option>
                            <option value="2">Finance</option>
                            <option value="3">IT</option>
                            <option value="4">Marketing</option>
                            <option value="5">Operations</option>
                            <option value="6">Legal</option>
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
                        <span id="viewDocumentSize" style="color: var(--text-secondary); font-size: 0.875rem;">Size</span>
                    </div>
                    <div style="color: var(--text-secondary); font-size: 0.875rem;">
                        <div id="viewDocumentUploader">Uploaded by: User</div>
                        <div id="viewDocumentDate">Date: Nov 15, 2024</div>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label>Description</label>
                <div id="viewDocumentDescription" style="padding: 0.75rem; background: var(--primary-light); border-radius: 0.5rem; min-height: 80px; color: var(--text-secondary);">
                    Document description will be displayed here...
                </div>
            </div>
            
            <div class="form-group">
                <label>Department</label>
                <div id="viewDocumentDepartment" style="padding: 0.75rem; background: var(--primary-light); border-radius: 0.5rem; color: var(--text-secondary);">
                    Department information
                </div>
            </div>
            
            <div class="form-group">
                <label>Access Level</label>
                <div id="viewDocumentAccess" style="padding: 0.75rem; background: var(--primary-light); border-radius: 0.5rem; color: var(--text-secondary);">
                    Access level information
                </div>
            </div>
            
            <div class="form-group">
                <label>Tags</label>
                <div id="viewDocumentTags" style="padding: 0.75rem; background: var(--primary-light); border-radius: 0.5rem; color: var(--text-secondary);">
                    No tags
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
                        <select id="edit_document_department" name="department_id">
                            <option value="">All Departments</option>
                            <option value="1">Human Resources</option>
                            <option value="2">Finance</option>
                            <option value="3">IT</option>
                            <option value="4">Marketing</option>
                            <option value="5">Operations</option>
                            <option value="6">Legal</option>
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
// Sample document data
const documents = {
    1: {
        title: "Employee Handbook 2024",
        description: "Comprehensive guide covering company policies, procedures, and employee benefits.",
        category: "policies",
        department: "Human Resources",
        access: "public",
        tags: "handbook, policies, benefits, procedures",
        size: "2.4 MB",
        icon: "fas fa-file-pdf",
        uploader: "HR Team",
        date: "Nov 15, 2024",
        downloads: 234,
        views: 456
    },
    2: {
        title: "Leave Application Form",
        description: "Standard form template for employee leave requests and approvals.",
        category: "forms",
        department: "Human Resources",
        access: "public",
        tags: "leave, form, application",
        size: "156 KB",
        icon: "fas fa-file-word",
        uploader: "Admin",
        date: "Nov 10, 2024",
        downloads: 89,
        views: 145
    },
    3: {
        title: "Employee Database Template",
        description: "Excel template for maintaining employee records and information.",
        category: "templates",
        department: "IT",
        access: "admin",
        tags: "database, template, excel",
        size: "45 KB",
        icon: "fas fa-file-excel",
        uploader: "IT Team",
        date: "Nov 5, 2024",
        downloads: 67,
        views: 123
    },
    4: {
        title: "Onboarding Presentation",
        description: "New employee orientation presentation covering company culture and processes.",
        category: "training",
        department: "Human Resources",
        access: "department",
        tags: "onboarding, training, presentation",
        size: "8.7 MB",
        icon: "fas fa-file-powerpoint",
        uploader: "HR Team",
        date: "Oct 28, 2024",
        downloads: 123,
        views: 234
    },
    5: {
        title: "Employment Contract Template",
        description: "Standard employment contract template with terms and conditions.",
        category: "contracts",
        department: "Legal",
        access: "restricted",
        tags: "contract, employment, legal",
        size: "234 KB",
        icon: "fas fa-file-contract",
        uploader: "Legal Team",
        date: "Oct 20, 2024",
        downloads: 45,
        views: 78
    },
    6: {
        title: "Q3 Performance Report",
        description: "Quarterly performance analysis and employee evaluation summary.",
        category: "reports",
        department: "Analytics",
        access: "admin",
        tags: "performance, report, quarterly",
        size: "1.8 MB",
        icon: "fas fa-file-chart-line",
        uploader: "Analytics Team",
        date: "Oct 15, 2024",
        downloads: 78,
        views: 156
    }
};

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

// View Document Modal Functions
function viewDocument(id) {
    const doc = documents[id];
    if (!doc) return;
    
    document.getElementById('viewDocumentTitle').textContent = doc.title;
    document.getElementById('viewDocumentDescription').textContent = doc.description;
    document.getElementById('viewDocumentCategory').textContent = doc.category.charAt(0).toUpperCase() + doc.category.slice(1);
    document.getElementById('viewDocumentDepartment').textContent = doc.department;
    document.getElementById('viewDocumentAccess').textContent = doc.access.charAt(0).toUpperCase() + doc.access.slice(1);
    document.getElementById('viewDocumentTags').textContent = doc.tags || 'No tags';
    document.getElementById('viewDocumentSize').textContent = doc.size;
    document.getElementById('viewDocumentUploader').textContent = `Uploaded by: ${doc.uploader}`;
    document.getElementById('viewDocumentDate').textContent = `Date: ${doc.date}`;
    document.getElementById('viewDocumentDownloads').textContent = doc.downloads;
    document.getElementById('viewDocumentViews').textContent = doc.views;
    document.getElementById('viewDocumentIcon').className = doc.icon;
    
    currentDocumentId = id;
    document.getElementById('viewDocumentModal').classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeViewDocumentModal() {
    document.getElementById('viewDocumentModal').classList.remove('show');
    document.body.style.overflow = 'auto';
    currentDocumentId = null;
}

function downloadCurrentDocument() {
    if (currentDocumentId) {
        downloadDocument(currentDocumentId);
    }
}

// Edit Document Modal Functions
function editDocument(id) {
    const doc = documents[id];
    if (!doc) return;
    
    document.getElementById('edit_document_title').value = doc.title;
    document.getElementById('edit_document_description').value = doc.description;
    document.getElementById('edit_document_category').value = doc.category;
    document.getElementById('edit_document_access').value = doc.access;
    document.getElementById('edit_document_tags').value = doc.tags;
    
    // Set department if available
    const departments = {
        'Human Resources': '1',
        'Finance': '2',
        'IT': '3',
        'Marketing': '4',
        'Operations': '5',
        'Legal': '6'
    };
    const deptId = departments[doc.department] || '';
    document.getElementById('edit_document_department').value = deptId;
    
    currentDocumentId = id;
    document.getElementById('editDocumentModal').classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeEditDocumentModal() {
    document.getElementById('editDocumentModal').classList.remove('show');
    document.body.style.overflow = 'auto';
    document.getElementById('editDocumentForm').reset();
    const filePreview = document.getElementById('edit-file-preview');
    if (filePreview) filePreview.style.display = 'none';
    currentDocumentId = null;
}

// Delete Document Modal Functions
function deleteDocument(id) {
    const doc = documents[id];
    if (!doc) return;
    
    document.getElementById('deleteDocumentTitle').textContent = doc.title;
    document.getElementById('deleteDocumentInfo').textContent = `${doc.category.charAt(0).toUpperCase() + doc.category.slice(1)} • ${doc.size}`;
    document.getElementById('deleteDocumentIcon').className = doc.icon;
    
    currentDocumentId = id;
    document.getElementById('deleteDocumentModal').classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeDeleteDocumentModal() {
    document.getElementById('deleteDocumentModal').classList.remove('show');
    document.body.style.overflow = 'auto';
    currentDocumentId = null;
}

function confirmDeleteDocument() {
    if (currentDocumentId) {
        // Show loading state
        const deleteBtn = document.querySelector('#deleteDocumentModal .btn-submit');
        const originalText = deleteBtn.innerHTML;
        deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Deleting...';
        deleteBtn.disabled = true;
        
        // Simulate deletion process
        setTimeout(() => {
            alert('Document deleted successfully!');
            closeDeleteDocumentModal();
            
            // Reset button
            deleteBtn.innerHTML = originalText;
            deleteBtn.disabled = false;
            
            // Remove document from display (in a real app, this would refresh the data)
            location.reload();
        }, 1500);
    }
}

// Download Document Function
function downloadDocument(id) {
    const doc = documents[id];
    if (!doc) return;
    
    // Show download notification
    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: var(--success);
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 0.5rem;
        z-index: 10000;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        animation: slideIn 0.3s ease-out;
    `;
    notification.innerHTML = `
        <i class="fas fa-download"></i> 
        Downloading "${doc.title}"...
    `;
    
    document.body.appendChild(notification);
    
    // Remove notification after 3 seconds
    setTimeout(() => {
        notification.remove();
    }, 3000);
    
    // In a real application, this would trigger the actual download
    console.log(`Downloading document: ${doc.title}`);
}

// File Upload Handling for Add Document
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

// Form Submissions
document.getElementById('addDocumentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = this.querySelector('.btn-submit');
    const originalText = submitBtn.innerHTML;
    
    // Show loading state
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Uploading...';
    submitBtn.disabled = true;
    
    // Simulate upload process
    setTimeout(() => {
        alert('Document uploaded successfully!');
        closeAddDocumentModal();
        
        // Reset button
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
        
        // Refresh page
        location.reload();
    }, 2000);
});

document.getElementById('editDocumentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = this.querySelector('.btn-submit');
    const originalText = submitBtn.innerHTML;
    
    // Show loading state
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
    submitBtn.disabled = true;
    
    // Simulate update process
    setTimeout(() => {
        alert('Document updated successfully!');
        closeEditDocumentModal();
        
        // Reset button
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
        
        // Refresh page
        location.reload();
    }, 2000);
});

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

// Search and Filter Functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('input[placeholder="Search documents..."]');
    const categoryFilter = document.querySelector('select option:first-child').parentNode;
    const typeFilter = categoryFilter.parentNode.lastElementChild;
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            // Implement search functionality
            console.log('Searching for:', this.value);
        });
    }
    
    if (categoryFilter) {
        categoryFilter.addEventListener('change', function() {
            // Implement category filter
            console.log('Category filter:', this.value);
        });
    }
    
    if (typeFilter) {
        typeFilter.addEventListener('change', function() {
            // Implement type filter
            console.log('Type filter:', this.value);
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
</script>

@endsection
