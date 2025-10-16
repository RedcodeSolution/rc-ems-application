@extends('layouts.admin')
<link rel="stylesheet" href="{{ asset('css/admin/announcements.css') }}">

@section('title', 'Announcements')

@section('content')
    <div class="card">
        <div class="card-header">
            <h2><i class="fas fa-bullhorn"></i> Announcements</h2>
            <div class="flex gap-2">
                <button onclick="openAnnouncementModal()" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    New Announcement
                </button>
                <button class="btn btn-secondary">
                    <i class="fas fa-download"></i>
                    Export
                </button>
            </div>
        </div>
        <div class="card-body">
            <!-- Search and Filter Section -->
            <div class="flex justify-between items-center mb-4">
                <div class="flex gap-2">
                    <input type="text" id="searchInput" placeholder="Search announcements..." class="form-input"
                        style="width: 300px;">
                    <select id="categoryFilter" class="form-select" style="width: 200px;">
                        <option value="all">All Categories</option>
                        <option value="general">General</option>
                        <option value="hr updates">HR Updates</option>
                        <option value="policy changes">Policy Changes</option>
                        <option value="events">Events</option>
                        <option value="system updates">System Updates</option>
                    </select>
                </div>
                <button class="btn btn-secondary">
                    <i class="fas fa-filter"></i>
                    Filter
                </button>
            </div>

            <!-- Announcements List -->
            <div id="announcementsList" style="display: flex; flex-direction: column; gap: 1.5rem;">
                @forelse($announcements as $announcement)
                    <div class="card announcement-item" data-category="{{ strtolower($announcement->category) }}">
                        <div class="card-body">
                            <div
                                style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                                <div style="flex: 1;">
                                    <!-- Title + Badges -->
                                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0.5rem;">
                                        <h3 class="announcement-title"
                                            style="font-size: 1.25rem; font-weight: 700; color: var(--gray-800); margin: 0;">
                                            {{ $announcement->title }}
                                        </h3>

                                        <!-- Category Badge -->
                                        <span class="badge"
                                            style="background: rgba(59, 130, 246, 0.1); color: var(--primary);
                                         padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem;">
                                            {{ ucfirst($announcement->category) }}
                                        </span>

                                        <!-- Status Badge -->
                                        <span class="badge"
                                            style="background: {{ $announcement->status === 'active' ? 'rgba(16, 185, 129, 0.1)' : 'rgba(245, 158, 11, 0.1)' }};
                                         color: {{ $announcement->status === 'active' ? 'var(--success)' : 'var(--warning)' }};
                                         padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem;">
                                            {{ ucfirst($announcement->status) }}
                                        </span>
                                    </div>

                                    <!-- Description -->
                                    <p class="announcement-content"
                                        style="color: var(--gray-600); margin-bottom: 1rem; line-height: 1.6;">
                                        {{ $announcement->content }}
                                    </p>

                                    <!-- Meta Info -->
                                    <div
                                        style="display: flex; align-items: center; gap: 1rem; font-size: 0.875rem; color: var(--gray-500);">
                                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                                            <i class="fas fa-user"></i>
                                            <span>Posted by {{ $announcement->posted_by ?? 'Admin' }}</span>
                                        </div>
                                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                                            <i class="fas fa-calendar"></i>
                                            <span>{{ \Carbon\Carbon::parse($announcement->created_at)->format('M d, Y') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex gap-1">
                                    <button class="btn btn-secondary" style="padding: 0.5rem;"
                                        onclick="openViewAnnouncementModal(
                                    {{ $announcement->announcement_id }},
                                    '{{ addslashes($announcement->title) }}',
                                    '{{ $announcement->priority }}',
                                    '{{ $announcement->category }}',
                                    '{{ addslashes($announcement->content) }}',
                                    '{{ $announcement->expires_at }}',
                                    {{ json_encode($announcement->audience ?? ['all']) }}
                                )">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    <button class="btn btn-warning" style="padding: 0.5rem;"
                                        onclick="openEditAnnouncementModal(
                                    {{ $announcement->announcement_id }},
                                    '{{ addslashes($announcement->title) }}',
                                    '{{ $announcement->priority }}',
                                    '{{ $announcement->category }}',
                                    '{{ addslashes($announcement->content) }}',
                                    '{{ $announcement->expires_at }}',
                                    {{ json_encode($announcement->audience ?? ['all']) }}
                                )">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <form method="POST"
                                        action="{{ route('admin.announcements.destroy', $announcement->announcement_id) }}"
                                        onsubmit="return confirm('Delete this announcement?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" style="padding: 0.5rem;">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <p style="color: var(--gray-500);">No announcements available.</p>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="flex justify-between items-center mt-4">
                <div style="color: var(--gray-600); font-size: 0.875rem;">
                    Showing {{ $announcements->firstItem() }} to {{ $announcements->lastItem() }}
                    of {{ $announcements->total() }} announcements
                </div>

                <div class="flex gap-1">
                    {{-- Previous Page --}}
                    @if ($announcements->onFirstPage())
                        <button class="btn btn-secondary" style="padding: 0.5rem 0.75rem;" disabled>
                            <i class="fas fa-chevron-left"></i>
                        </button>
                    @else
                        <a href="{{ $announcements->previousPageUrl() }}" class="btn btn-secondary"
                            style="padding: 0.5rem 0.75rem;">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    @endif

                    {{-- Page Numbers --}}
                    @foreach ($announcements->getUrlRange(1, $announcements->lastPage()) as $page => $url)
                        @if ($page == $announcements->currentPage())
                            <button class="btn btn-primary" style="padding: 0.5rem 0.75rem;">{{ $page }}</button>
                        @else
                            <a href="{{ $url }}" class="btn btn-secondary"
                                style="padding: 0.5rem 0.75rem;">{{ $page }}</a>
                        @endif
                    @endforeach

                    {{-- Next Page --}}
                    @if ($announcements->hasMorePages())
                        <a href="{{ $announcements->nextPageUrl() }}" class="btn btn-secondary"
                            style="padding: 0.5rem 0.75rem;">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    @else
                        <button class="btn btn-secondary" style="padding: 0.5rem 0.75rem;" disabled>
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Announcement Statistics -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-top: 2rem;">
        <div class="card">
            <div class="card-body text-center">
                <div style="font-size: 2rem; font-weight: 700; color: var(--primary); margin-bottom: 0.5rem;">
                    {{ $totalAnnouncements }}
                </div>
                <div style="color: var(--gray-600); font-weight: 500;">Total Announcements</div>
            </div>
        </div>
        <div class="card">
            <div class="card-body text-center">
                <div style="font-size: 2rem; font-weight: 700; color: var(--success); margin-bottom: 0.5rem;">
                    {{ $publishedCount }}
                </div>
                <div style="color: var(--gray-600); font-weight: 500;">Published</div>
            </div>
        </div>
        <div class="card">
            <div class="card-body text-center">
                <div style="font-size: 2rem; font-weight: 700; color: var(--warning); margin-bottom: 0.5rem;">
                    {{ $scheduledCount }}
                </div>
                <div style="color: var(--gray-600); font-weight: 500;">Scheduled</div>
            </div>
        </div>
    </div>


    <!-- Announcement Creation Modal -->
    <div id="announcementModal" class="modal-overlay">
        <div class="modal-container">
            <div class="modal-header">
                <h3 class="modal-title">
                    <i class="fas fa-bullhorn"></i>
                    Create New Announcement
                </h3>
                <p class="modal-subtitle">Share important updates with your team</p>
                <button class="modal-close" onclick="closeAnnouncementModal()" type="button">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="announcementForm" action="{{ route('admin.announcements.store') }}" method="POST">
                    @csrf
                    <div class="form-container">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="title" class="form-label">
                                    <i class="fas fa-heading"></i>
                                    Title
                                </label>
                                <input type="text" id="title" name="title" class="form-input"
                                    placeholder="Enter announcement title" required>
                            </div>
                            <div class="form-group">
                                <label for="priority" class="form-label">
                                    <i class="fas fa-flag"></i>
                                    Priority Level
                                </label>
                                <select id="priority" name="priority" class="form-select" required>
                                    <option value="">Select Priority</option>
                                    <option value="low">🟢 Low</option>
                                    <option value="medium">🟡 Medium</option>
                                    <option value="high">🔴 High</option>
                                    <option value="urgent">⚠️ Urgent</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="category" class="form-label">
                                    <i class="fas fa-tag"></i>
                                    Category
                                </label>
                                <select id="category" name="category" class="form-select" required>
                                    <option value="">Select Category</option>
                                    <option value="general">📢 General</option>
                                    <option value="hr">👥 HR Updates</option>
                                    <option value="policy">📋 Policy Changes</option>
                                    <option value="events">🎉 Events</option>
                                    <option value="system">💻 System Updates</option>
                                    <option value="finance">💰 Finance</option>
                                    <option value="operations">⚙️ Operations</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="expires_at" class="form-label">
                                    <i class="fas fa-calendar-alt"></i>
                                    Expires At (Optional)
                                </label>
                                <input type="datetime-local" id="expires_at" name="expires_at" class="form-input">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="content" class="form-label">
                                <i class="fas fa-file-text"></i>
                                Content
                            </label>
                            <textarea id="content" name="content" class="form-textarea" rows="6"
                                placeholder="Enter announcement content..." required></textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-users"></i>
                                Target Audience
                            </label>
                            <div class="form-checkbox">
                                <input type="checkbox" id="all_employees" name="target_audience[]" value="all"
                                    checked>
                                <label for="all_employees">All Employees</label>
                            </div>
                            <div class="form-checkbox">
                                <input type="checkbox" id="managers" name="target_audience[]" value="managers">
                                <label for="managers">Managers Only</label>
                            </div>
                            <div class="form-checkbox">
                                <input type="checkbox" id="department_heads" name="target_audience[]"
                                    value="department_heads">
                                <label for="department_heads">Department Heads</label>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="button" class="btn btn-secondary" onclick="closeAnnouncementModal()">
                                <i class="fas fa-times"></i>
                                Cancel
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i>
                                Create Announcement
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Announcement Edit Modal -->
    <div id="editAnnouncementModal" class="modal-overlay">
        <div class="modal-container">
            <div class="modal-header">
                <h3 class="modal-title">
                    <i class="fas fa-edit"></i>
                    Edit Announcement
                </h3>
                <p class="modal-subtitle">Update the announcement details below</p>
                <button class="modal-close" onclick="closeEditAnnouncementModal()" type="button">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="editAnnouncementForm"
                    action="{{ route('admin.announcements.update', ':id') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-container">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="edit_title" class="form-label">
                                    <i class="fas fa-heading"></i>
                                    Title
                                </label>
                                <input type="text" id="edit_title" name="title" class="form-input"
                                    placeholder="Enter announcement title" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_priority" class="form-label">
                                    <i class="fas fa-flag"></i>
                                    Priority Level
                                </label>
                                <select id="edit_priority" name="priority" class="form-select" required>
                                    <option value="">Select Priority</option>
                                    <option value="low">🟢 Low</option>
                                    <option value="medium">🟡 Medium</option>
                                    <option value="high">🔴 High</option>
                                    <option value="urgent">⚠️ Urgent</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="edit_category" class="form-label">
                                    <i class="fas fa-tag"></i>
                                    Category
                                </label>
                                <select id="edit_category" name="category" class="form-select" required>
                                    <option value="">Select Category</option>
                                    <option value="general">📢 General</option>
                                    <option value="hr">👥 HR Updates</option>
                                    <option value="policy">📋 Policy Changes</option>
                                    <option value="events">🎉 Events</option>
                                    <option value="system">💻 System Updates</option>
                                    <option value="finance">💰 Finance</option>
                                    <option value="operations">⚙️ Operations</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="edit_expires_at" class="form-label">
                                    <i class="fas fa-calendar-alt"></i>
                                    Expires At (Optional)
                                </label>
                                <input type="datetime-local" id="edit_expires_at" name="expires_at" class="form-input">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="edit_content" class="form-label">
                                <i class="fas fa-file-text"></i>
                                Content
                            </label>
                            <textarea id="edit_content" name="content" class="form-textarea" rows="6"
                                placeholder="Enter announcement content..." required></textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-users"></i>
                                Target Audience
                            </label>
                            <div class="form-checkbox">
                                <input type="checkbox" id="edit_all_employees" name="target_audience[]" value="all"
                                    checked>
                                <label for="edit_all_employees">All Employees</label>
                            </div>
                            <div class="form-checkbox">
                                <input type="checkbox" id="edit_managers" name="target_audience[]" value="managers">
                                <label for="edit_managers">Managers Only</label>
                            </div>
                            <div class="form-checkbox">
                                <input type="checkbox" id="edit_department_heads" name="target_audience[]"
                                    value="department_heads">
                                <label for="edit_department_heads">Department Heads</label>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="button" class="btn btn-secondary" onclick="closeEditAnnouncementModal()">
                                <i class="fas fa-times"></i>
                                Cancel
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i>
                                Update Announcement
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Announcement View Modal -->
    <div id="viewAnnouncementModal" class="modal-overlay">
        <div class="modal-container">
            <div class="modal-header">
                <h3 class="modal-title">
                    <i class="fas fa-eye"></i>
                    Announcement Details
                </h3>
                <p class="modal-subtitle">View complete announcement information</p>
                <button class="modal-close" onclick="closeViewAnnouncementModal()" type="button">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-container">
                    <!-- Basic Information Row -->
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-heading"></i>
                                Title
                            </label>
                            <div style="position: relative;">
                                <i class="input-icon fas fa-heading"></i>
                                <div class="view-field" id="view_title"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-flag"></i>
                                Priority Level
                            </label>
                            <div style="position: relative;">
                                <i class="input-icon fas fa-flag"></i>
                                <div class="view-field" id="view_priority"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Category and Expiry Row -->
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-tag"></i>
                                Category
                            </label>
                            <div style="position: relative;">
                                <i class="input-icon fas fa-tag"></i>
                                <div class="view-field" id="view_category"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-calendar-alt"></i>
                                Expires At
                            </label>
                            <div style="position: relative;">
                                <i class="input-icon fas fa-calendar-alt"></i>
                                <div class="view-field" id="view_expires_at"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-file-text"></i>
                            Content
                        </label>
                        <div style="position: relative;">
                            <i class="input-icon fas fa-file-text"></i>
                            <div class="view-field view-textarea" id="view_content"></div>
                        </div>
                    </div>

                    <!-- Target Audience -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-users"></i>
                            Target Audience
                        </label>
                        <div style="position: relative;">
                            <i class="input-icon fas fa-users"></i>
                            <div class="view-field" id="view_target_audience"></div>
                        </div>
                    </div>

                    <!-- Modal Actions -->
                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" onclick="closeViewAnnouncementModal()">
                            <i class="fas fa-times"></i> Close
                        </button>
                        <button type="button" class="btn btn-primary" onclick="openEditAnnouncementModalFromView()">
                            <i class="fas fa-edit"></i> Edit Announcement
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Modal Functions
        function openAnnouncementModal() {
            const modal = document.getElementById('announcementModal');
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';

            // Focus on first input
            setTimeout(() => {
                document.getElementById('title').focus();
            }, 300);
        }

        function closeAnnouncementModal() {
            const modal = document.getElementById('announcementModal');
            modal.classList.remove('active');
            document.body.style.overflow = 'auto';

            // Reset form
            document.getElementById('announcementForm').reset();

            // Reset checkboxes to default
            document.getElementById('all_employees').checked = true;
            document.getElementById('managers').checked = false;
            document.getElementById('department_heads').checked = false;
        }

        // Edit Modal Functions
        function openEditAnnouncementModal(id, title, priority, category, content, expiresAt, targetAudience) {
            console.log('Opening edit modal with data:', {
                id,
                title,
                priority,
                category,
                content,
                expiresAt,
                targetAudience
            });

            const modal = document.getElementById('editAnnouncementModal');
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';

            // Update form action with the announcement ID
            const form = document.getElementById('editAnnouncementForm');
            form.action = form.action.replace(':id', id);

            // Populate form fields
            document.getElementById('edit_title').value = title || '';
            document.getElementById('edit_priority').value = priority || '';
            document.getElementById('edit_category').value = category || '';
            document.getElementById('edit_content').value = content || '';
            document.getElementById('edit_expires_at').value = expiresAt || '';

            // Handle target audience checkboxes
            const allEmployees = document.getElementById('edit_all_employees');
            const managers = document.getElementById('edit_managers');
            const departmentHeads = document.getElementById('edit_department_heads');

            // Reset all checkboxes first
            allEmployees.checked = false;
            managers.checked = false;
            departmentHeads.checked = false;

            // Set checkboxes based on target audience
            if (targetAudience) {
                const audiences = Array.isArray(targetAudience) ? targetAudience : targetAudience.split(',');
                audiences.forEach(audience => {
                    switch (audience.trim()) {
                        case 'all':
                            allEmployees.checked = true;
                            break;
                        case 'managers':
                            managers.checked = true;
                            break;
                        case 'department_heads':
                            departmentHeads.checked = true;
                            break;
                    }
                });
            }
            // Focus on first input
            setTimeout(() => {
                document.getElementById('edit_title').focus();
            }, 300);
        }

        function closeEditAnnouncementModal() {
            const modal = document.getElementById('editAnnouncementModal');
            modal.classList.remove('active');
            document.body.style.overflow = 'auto';

            // Reset form
            document.getElementById('editAnnouncementForm').reset();

            // Reset checkboxes to default
            document.getElementById('edit_all_employees').checked = true;
            document.getElementById('edit_managers').checked = false;
            document.getElementById('edit_department_heads').checked = false;
        }

        // View Modal Functions
        function openViewAnnouncementModal(id, title, priority, category, content, expiresAt, targetAudience) {
            console.log('Opening view modal with data:', {
                id,
                title,
                priority,
                category,
                content,
                expiresAt,
                targetAudience
            });

            const modal = document.getElementById('viewAnnouncementModal');
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';

            // Populate view fields
            document.getElementById('view_title').textContent = title || 'N/A';

            // Format priority display
            let priorityDisplay = priority || 'N/A';
            switch (priority) {
                case 'low':
                    priorityDisplay = '🟢 Low';
                    break;
                case 'medium':
                    priorityDisplay = '🟡 Medium';
                    break;
                case 'high':
                    priorityDisplay = '🔴 High';
                    break;
                case 'urgent':
                    priorityDisplay = '⚠️ Urgent';
                    break;
            }
            document.getElementById('view_priority').textContent = priorityDisplay;

            // Format category display
            let categoryDisplay = category || 'N/A';
            switch (category) {
                case 'general':
                    categoryDisplay = '📢 General';
                    break;
                case 'hr':
                    categoryDisplay = '👥 HR Updates';
                    break;
                case 'policy':
                    categoryDisplay = '📋 Policy Changes';
                    break;
                case 'events':
                    categoryDisplay = '🎉 Events';
                    break;
                case 'system':
                    categoryDisplay = '💻 System Updates';
                    break;
                case 'finance':
                    categoryDisplay = '💰 Finance';
                    break;
                case 'operations':
                    categoryDisplay = '⚙️ Operations';
                    break;
            }
            document.getElementById('view_category').textContent = categoryDisplay;

            document.getElementById('view_content').textContent = content || 'N/A';

            // Format expiry date
            document.getElementById('view_expires_at').textContent = expiresAt ? new Date(expiresAt).toLocaleDateString(
                'en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                }) : 'No expiry date';

            // Format target audience
            let audienceDisplay = 'N/A';
            if (targetAudience) {
                const audiences = Array.isArray(targetAudience) ? targetAudience : targetAudience.split(',');
                const audienceLabels = audiences.map(audience => {
                    switch (audience.trim()) {
                        case 'all':
                            return 'All Employees';
                        case 'managers':
                            return 'Managers Only';
                        case 'department_heads':
                            return 'Department Heads';
                        default:
                            return audience.trim();
                    }
                });
                audienceDisplay = audienceLabels.join(', ');
            }
            document.getElementById('view_target_audience').textContent = audienceDisplay;

            // Store data for potential edit modal opening
            window.currentAnnouncementData = {
                id,
                title,
                priority,
                category,
                content,
                expiresAt,
                targetAudience
            };
        }

        function closeViewAnnouncementModal() {
            const modal = document.getElementById('viewAnnouncementModal');
            modal.classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        // View Modal Functions
        function openViewAnnouncementModal(id, title, priority, category, content, expiresAt, targetAudience) {
            console.log('Opening view modal with data:', {
                id,
                title,
                priority,
                category,
                content,
                expiresAt,
                targetAudience
            });

            const modal = document.getElementById('viewAnnouncementModal');
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';

            // Populate view fields
            document.getElementById('view_title').textContent = title || 'N/A';

            // Format priority display
            let priorityDisplay = priority || 'N/A';
            switch (priority) {
                case 'low':
                    priorityDisplay = '🟢 Low';
                    break;
                case 'medium':
                    priorityDisplay = '🟡 Medium';
                    break;
                case 'high':
                    priorityDisplay = '🔴 High';
                    break;
                case 'urgent':
                    priorityDisplay = '⚠️ Urgent';
                    break;
            }
            document.getElementById('view_priority').textContent = priorityDisplay;

            // Format category display
            let categoryDisplay = category || 'N/A';
            switch (category) {
                case 'general':
                    categoryDisplay = '📢 General';
                    break;
                case 'hr':
                    categoryDisplay = '👥 HR Updates';
                    break;
                case 'policy':
                    categoryDisplay = '📋 Policy Changes';
                    break;
                case 'events':
                    categoryDisplay = '🎉 Events';
                    break;
                case 'system':
                    categoryDisplay = '💻 System Updates';
                    break;
                case 'finance':
                    categoryDisplay = '💰 Finance';
                    break;
                case 'operations':
                    categoryDisplay = '⚙️ Operations';
                    break;
            }
            document.getElementById('view_category').textContent = categoryDisplay;

            document.getElementById('view_content').textContent = content || 'N/A';

            // Format expiry date
            document.getElementById('view_expires_at').textContent = expiresAt ? new Date(expiresAt).toLocaleDateString(
                'en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                }) : 'No expiry date';

            // Format target audience
            let audienceDisplay = 'N/A';
            if (targetAudience) {
                const audiences = Array.isArray(targetAudience) ? targetAudience : targetAudience.split(',');
                const audienceLabels = audiences.map(audience => {
                    switch (audience.trim()) {
                        case 'all':
                            return 'All Employees';
                        case 'managers':
                            return 'Managers Only';
                        case 'department_heads':
                            return 'Department Heads';
                        default:
                            return audience.trim();
                    }
                });
                audienceDisplay = audienceLabels.join(', ');
            }
            document.getElementById('view_target_audience').textContent = audienceDisplay;

            // Store data for potential edit modal opening
            window.currentAnnouncementData = {
                id,
                title,
                priority,
                category,
                content,
                expiresAt,
                targetAudience
            };
        }

        function closeViewAnnouncementModal() {
            const modal = document.getElementById('viewAnnouncementModal');
            modal.classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        function openEditAnnouncementModalFromView() {
            // Close view modal first
            closeViewAnnouncementModal();

            // Open edit modal with stored data
            const data = window.currentAnnouncementData;
            if (data) {
                setTimeout(() => {
                    openEditAnnouncementModal(data.id, data.title, data.priority, data.category,
                        data.content, data.expiresAt, data.targetAudience);
                }, 300); // Small delay to allow view modal to close
            }
        }

        // Close modal when clicking outside
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('announcementModal');
            if (modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        closeAnnouncementModal();
                    }
                });
            }

            // Edit modal close on outside click
            const editModal = document.getElementById('editAnnouncementModal');
            if (editModal) {
                editModal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        closeEditAnnouncementModal();
                    }
                });
            }

            // View modal close on outside click
            const viewModal = document.getElementById('viewAnnouncementModal');
            if (viewModal) {
                viewModal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        closeViewAnnouncementModal();
                    }
                });
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const modal = document.getElementById('announcementModal');
                const editModal = document.getElementById('editAnnouncementModal');
                const viewModal = document.getElementById('viewAnnouncementModal');

                if (modal && modal.classList.contains('active')) {
                    closeAnnouncementModal();
                }
                if (editModal && editModal.classList.contains('active')) {
                    closeEditAnnouncementModal();
                }
                if (viewModal && viewModal.classList.contains('active')) {
                    closeViewAnnouncementModal();
                }
            }
        });

        // Handle form submission
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('announcementForm');
            if (form) {
                form.addEventListener('submit', async function(e) {
                    e.preventDefault();

                    const formData = new FormData(this);
                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalText = submitBtn.innerHTML;

                    // Show loading state
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating...';

                    try {
                        const response = await fetch(this.action, {
                            method: this.method,
                            body: formData,
                            headers: {
                                "X-Requested-With": "XMLHttpRequest",
                                "X-CSRF-TOKEN": form.querySelector('input[name="_token"]').value
                            }
                        });

                        if (!response.ok) throw new Error("Failed to create announcement");

                        const data = await response.json();

                        // ✅ Show success message
                        showNotification('Announcement created successfully!', 'success');

                        // ✅ Close modal + reset form
                        form.reset();
                        closeAnnouncementModal();

                        // ✅ Optionally reload page or update list dynamically
                        setTimeout(() => location.reload(), 1000);

                    } catch (error) {
                        console.error(error);
                        showNotification('Error creating announcement!', 'error');
                    } finally {
                        // Reset button state
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    }
                });
            }

            // Handle edit form submission
            const editForm = document.getElementById('editAnnouncementForm');
            if (editForm) {
                editForm.addEventListener('submit', async function(e) {
                    e.preventDefault();

                    const formData = new FormData(this);
                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalText = submitBtn.innerHTML;

                    // Show loading state
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';

                    try {
                        const response = await fetch(this.action, {
                            method: "POST", // Laravel requires POST with _method=PUT for updates
                            body: formData,
                            headers: {
                                "X-Requested-With": "XMLHttpRequest",
                                "X-CSRF-TOKEN": this.querySelector('input[name="_token"]').value
                            }
                        });

                        if (!response.ok) throw new Error("Failed to update announcement");

                        const data = await response.json();

                        // ✅ Show success message
                        showNotification('Announcement updated successfully!', 'success');

                        // ✅ Close modal
                        closeEditAnnouncementModal();

                        // ✅ Optionally reload or dynamically update DOM
                        setTimeout(() => location.reload(), 1000);

                    } catch (error) {
                        console.error(error);
                        showNotification('Error updating announcement!', 'error');
                    } finally {
                        // Reset button state
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    }
                });
            }


        });

        // Notification function
        function showNotification(message, type = 'info') {
            // Remove existing notifications
            const existingNotifications = document.querySelectorAll('.notification');
            existingNotifications.forEach(notif => notif.remove());

            // Create notification element
            const notification = document.createElement('div');
            notification.className = `notification notification-${type}`;
            notification.innerHTML = `
        <div class="notification-content">
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
            <span>${message}</span>
        </div>
    `;

            // Add notification styles
            notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#3b82f6'};
        color: white;
        padding: 12px 20px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 10000;
        transform: translateX(100%);
        transition: transform 0.3s ease;
        font-weight: 500;
        font-size: 14px;
    `;

            document.body.appendChild(notification);

            // Animate in
            setTimeout(() => {
                notification.style.transform = 'translateX(0)';
            }, 100);

            // Remove after 3 seconds
            setTimeout(() => {
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 300);
            }, 3000);
        }

        // Enhanced form interactions
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-expand textarea
            const textarea = document.getElementById('content');
            if (textarea) {
                textarea.addEventListener('input', function() {
                    this.style.height = 'auto';
                    this.style.height = this.scrollHeight + 'px';
                });
            }

            // Handle "All Employees" checkbox logic
            const allEmployeesCheckbox = document.getElementById('all_employees');
            const managersCheckbox = document.getElementById('managers');
            const departmentHeadsCheckbox = document.getElementById('department_heads');

            if (allEmployeesCheckbox && managersCheckbox && departmentHeadsCheckbox) {
                allEmployeesCheckbox.addEventListener('change', function() {
                    if (this.checked) {
                        managersCheckbox.checked = false;
                        departmentHeadsCheckbox.checked = false;
                    }
                });

                managersCheckbox.addEventListener('change', function() {
                    if (this.checked) {
                        allEmployeesCheckbox.checked = false;
                    }
                });

                departmentHeadsCheckbox.addEventListener('change', function() {
                    if (this.checked) {
                        allEmployeesCheckbox.checked = false;
                    }
                });
            }

            // Handle "All Employees" checkbox logic for edit modal
            const editAllEmployeesCheckbox = document.getElementById('edit_all_employees');
            const editManagersCheckbox = document.getElementById('edit_managers');
            const editDepartmentHeadsCheckbox = document.getElementById('edit_department_heads');

            if (editAllEmployeesCheckbox && editManagersCheckbox && editDepartmentHeadsCheckbox) {
                editAllEmployeesCheckbox.addEventListener('change', function() {
                    if (this.checked) {
                        editManagersCheckbox.checked = false;
                        editDepartmentHeadsCheckbox.checked = false;
                    }
                });

                editManagersCheckbox.addEventListener('change', function() {
                    if (this.checked) {
                        editAllEmployeesCheckbox.checked = false;
                    }
                });

                editDepartmentHeadsCheckbox.addEventListener('change', function() {
                    if (this.checked) {
                        editAllEmployeesCheckbox.checked = false;
                    }
                });
            }

            // Set minimum datetime to current time
            const expiresInput = document.getElementById('expires_at');
            if (expiresInput) {
                const now = new Date();
                const offset = now.getTimezoneOffset();
                const localTime = new Date(now.getTime() - (offset * 60000));
                expiresInput.min = localTime.toISOString().slice(0, 16);
            }

            // Form validation
            const form = document.getElementById('announcementForm');
            if (form) {
                const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
                inputs.forEach(input => {
                    input.addEventListener('blur', function() {
                        if (this.value.trim() === '') {
                            this.style.borderColor = '#ef4444';
                        } else {
                            this.style.borderColor = '#10b981';
                        }
                    });

                    input.addEventListener('input', function() {
                        if (this.value.trim() !== '') {
                            this.style.borderColor = '#10b981';
                        }
                    });
                });
            }
        });

        function filterAnnouncements() {
            const query = document.getElementById('searchInput').value.toLowerCase();
            const category = document.getElementById('categoryFilter').value.toLowerCase();
            const items = document.querySelectorAll('#announcementsList .announcement-item');
            const noResults = document.getElementById('noResults');
            let visibleCount = 0;

            items.forEach(item => {
                const title = item.querySelector('.announcement-title').innerText.toLowerCase();
                const content = item.querySelector('.announcement-content').innerText.toLowerCase();
                const itemCategory = item.getAttribute('data-category');

                // check search + category
                const matchesSearch = title.includes(query) || content.includes(query);
                const matchesCategory = (category === 'all' || itemCategory === category);

                if (matchesSearch && matchesCategory) {
                    item.style.display = 'block';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });

            // toggle "No results"
            if (noResults) {
                noResults.style.display = (visibleCount === 0 ? 'block' : 'none');
            }
        }

        // Bind events
        document.getElementById('searchInput').addEventListener('keyup', filterAnnouncements);
        document.getElementById('categoryFilter').addEventListener('change', filterAnnouncements);
    </script>

    <style>
        .notification-content {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .notification-content i {
            font-size: 16px;
        }

        /* Additional modal enhancements */
        .modal-container::-webkit-scrollbar {
            width: 8px;
        }

        .modal-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .modal-container::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }

        .modal-container::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        /* View Modal Styles */
        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--redcode-green);
            font-size: 16px;
            z-index: 1;
            pointer-events: none;
            transition: all 0.3s ease;
        }

        /* Special positioning for view modal textarea icons */
        .form-group:has(.view-textarea) .input-icon {
            top: 24px;
            transform: translateY(0);
        }

        .view-field {
            width: 100%;
            padding: 12px 16px 12px 48px;
            border: 2px solid var(--border-light);
            border-radius: 0.75rem;
            font-size: 0.9rem;
            background: rgba(248, 250, 252, 0.8);
            backdrop-filter: blur(10px);
            color: var(--text-primary);
            font-weight: 500;
            box-sizing: border-box;
            min-height: 48px;
            display: flex;
            align-items: center;
            border-color: rgba(16, 185, 129, 0.3);
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.08);
        }

        .view-textarea {
            min-height: 100px;
            align-items: flex-start;
            padding-top: 16px;
            padding-bottom: 16px;
            white-space: pre-wrap;
            word-wrap: break-word;
            line-height: 1.5;
        }

        .view-field:empty::before {
            content: 'No data available';
            color: var(--text-light);
            font-style: italic;
        }

        /* Status badge styling in view modal */
        .view-field.status-badge {
            background: rgba(16, 185, 129, 0.1);
            border-color: rgba(16, 185, 129, 0.3);
            color: var(--redcode-green);
            font-weight: 600;
        }

        .view-field.status-badge.in-progress {
            background: rgba(59, 130, 246, 0.1);
            border-color: rgba(59, 130, 246, 0.3);
            color: #3b82f6;
        }

        .view-field.status-badge.completed {
            background: rgba(16, 185, 129, 0.1);
            border-color: rgba(16, 185, 129, 0.3);
            color: var(--redcode-green);
        }

        .view-field.status-badge.on-hold {
            background: rgba(245, 158, 11, 0.1);
            border-color: rgba(245, 158, 11, 0.3);
            color: #f59e0b;
        }

        .view-field.status-badge.cancelled {
            background: rgba(239, 68, 68, 0.1);
            border-color: rgba(239, 68, 68, 0.3);
            color: #ef4444;
        }

        /* CSS Variables for consistent theming */
        :root {
            --redcode-green: #10b981;
            --border-light: #e5e7eb;
            --text-primary: #374151;
            --text-light: #9ca3af;
        }
    </style>
@endpush
