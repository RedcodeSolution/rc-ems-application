@extends('layouts.employee')
<link rel="stylesheet" href="{{ asset('css/Employee/myProfile.css') }}">
@section('title', 'My Profile')
@section('content-class', 'profile-page')

@section('content')
    <div class="profile-container">

        <!-- Profile Header -->
        <div class="profile-header">
            <div class="profile-avatar-section">
                <div class="profile-avatar-large">
                    @if($employee->profile_photo)
                        <img src="{{ asset('storage/' . $employee->profile_photo) }}" alt="{{ $employee->employee_name }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                    @else
                        {{ strtoupper(substr($employee->employee_name, 0, 1)) }}
                        @php
                            $parts = explode(' ', trim($employee->employee_name));
                            echo isset($parts[1]) ? strtoupper(substr($parts[1], 0, 1)) : '';
                        @endphp
                    @endif
                </div>

                <div class="profile-header-info">
                    <h1>{{ $employee->employee_name }}</h1>
                    <p class="profile-role">{{ $employee->role }}</p>
                    <p class="profile-id">{{ $employee->employee_id }}</p>
                </div>
            </div>
            <div class="profile-actions">
                <button class="btn btn-primary" onclick="toggleEditMode()">
                    <i class="fas fa-edit"></i>
                    Edit Profile
                </button>
            </div>
        </div>

        <!-- Profile Content -->
        <div class="profile-content">
            <!-- Personal Information Card -->
            <div class="profile-card">
                <div class="profile-card-header">
                    <h3>
                        <i class="fas fa-user"></i>
                        Personal Information
                    </h3>
                </div>
                <div class="profile-card-body">
                    <form id="profileForm" method="POST" action="{{ route('employee.profile.update') }}">
                        @csrf
                        @method('PUT')
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="employee_name">Full Name</label>
                                <input type="text" id="employee_name" name="employee_name"
                                    value="{{ $employee->employee_name }}" class="form-control" readonly>
                            </div>

                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" id="email" name="email" value="{{ $employee->email }}"
                                    class="form-control" readonly>
                            </div>

                            <div class="form-group">
                                <label for="contact_no">Contact Number</label>
                                <input type="text" id="contact_no" name="contact_no" value="{{ $employee->contact_no }}"
                                    class="form-control" readonly>
                            </div>

                            <div class="form-group">
                                <label for="employee_id">Employee ID</label>
                                <input type="text" id="employee_id" value="{{ $employee->employee_id }}"
                                    class="form-control" readonly disabled>
                            </div>

                            <div class="form-group">
                                <label for="employee_type">Employee Type</label>
                                <input type="text" id="employee_type" value="{{ $employee->employee_type }}"
                                    class="form-control" readonly disabled>
                            </div>

                            <div class="form-group">
                                <label for="employee_status">Status</label>
                                <span class="status-badge status-active">
                                    Active
                                </span>
                            </div>

                            <div class="form-group">
                                <label for="joining_date">Joining Date</label>
                                <input type="text" id="joining_date"
                                    value="{{ $employee->created_at->format('Y-m-d') }}" class="form-control" readonly
                                    disabled>
                            </div>


                        </div>

                        <div class="form-actions" id="formActions" style="display: none;">
                            <button type="button" class="btn btn-primary" onclick="saveProfile()">
                                <i class="fas fa-save"></i>
                                Save Changes
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="cancelEdit()">
                                <i class="fas fa-times"></i>
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Work Information Card -->
            <div class="profile-card">
                <div class="profile-card-header">
                    <h3>
                        <i class="fas fa-briefcase"></i>
                        Work Information
                    </h3>
                </div>
                <div class="profile-card-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <label>Department</label>
                            <div class="info-value">
                                <span class="department-badge">{{ $employee->department->department_name ?? 'N/A' }}</span>
                            </div>
                        </div>

                        <div class="info-item">
                            <label>Teams</label>
                            <div class="info-value">
                                @if ($employee->teams->count())
                                    @foreach ($employee->teams as $team)
                                        <span class="team-badge">{{ $team->team_name }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted">No Teams Assigned</span>
                                @endif
                            </div>
                        </div>

                        <div class="info-item">
                            <label>Reporting Manager</label>
                            <div class="info-value">
                                @if ($employee->department && $employee->department->manager)
                                    <div class="manager-info">
                                        <div class="manager-avatar">
                                            @if($employee->department->manager->profile_photo)
                                                <img src="{{ asset('storage/' . $employee->department->manager->profile_photo) }}" alt="{{ $employee->department->manager->employee_name }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                            @else
                                                {{ strtoupper(substr($employee->department->manager->employee_name, 0, 1)) }}
                                                {{-- Check if last name exists before accessing --}}
                                                {{ !empty($employee->department->manager->last_name) ? strtoupper(substr($employee->department->manager->last_name, 0, 1)) : '' }}
                                            @endif
                                        </div>
                                        <div class="manager-details">
                                            <span class="manager-name">
                                                {{ $employee->department->manager->employee_name }}
                                            </span>
                                            <span class="manager-title">Department Manager</span>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted">Not Assigned</span>
                                @endif
                            </div>
                        </div>


                        <div class="info-item">
                            <label>Office Location</label>
                            <div class="info-value">
                                <i class="fas fa-map-marker-alt"></i>
                                {{ $employee->department->location ?? 'N/A' }}
                            </div>
                        </div>

                        <div class="info-item">
                            <label>Work Schedule</label>
                            <div class="info-value">
                                <i class="fas fa-clock"></i>
                                9:00 AM - 5:00 PM (EST)
                            </div>
                        </div>

                        <div class="info-item">
                            <label>Payment Status</label>
                            <div class="info-value">
                                <span class="status-badge status-paid">
                                    <i class="fas fa-check-circle"></i>
                                    Paid
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Skills & Expertise Card -->

            <div class="profile-card">
                <div class="profile-card-header">
                    <h3>
                        <i class="fas fa-code"></i>
                        Skills & Expertise
                    </h3>
                    <button class="btn btn-sm btn-outline" onclick="toggleSkillsEditMode()" id="skillsEditBtn">
                        <i class="fas fa-edit"></i>
                        Edit Skills
                    </button>
                </div>
                <div class="profile-card-body">
                    <div class="skills-section" id="skillsSection">

                        @php
                            // Define your default categories
                            $defaultCategories = ['Programming Languages', 'Frameworks & Technologies'];

                            // Group employee’s existing skills
                            $skillsByCategory = $employee->skills->groupBy('skill_category');
                        @endphp

                        {{-- Loop through default categories first --}}
                        @foreach ($defaultCategories as $category)
                            <div class="skill-category">
                                <h4>{{ $category }}</h4>
                                <div class="skill-tags" data-category="{{ $category }}">
                                    @if (isset($skillsByCategory[$category]) && $skillsByCategory[$category]->count() > 0)
                                        @foreach ($skillsByCategory[$category] as $skill)
                                            <span class="skill-tag {{ strtolower($skill->skill_level) }}"
                                                data-skill="{{ $skill->skill_name }}">
                                                {{ $skill->skill_name }}
                                                <button class="skill-remove-btn"
                                                    onclick="removeSkill(this, '{{ $skill->id }}')"
                                                    style="display: none;">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </span>
                                        @endforeach
                                    @endif
                                    <button class="add-skill-btn" onclick="openAddSkillModal('{{ $category }}')"
                                        style="display: none;">
                                        <i class="fas fa-plus"></i> Add Skill
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="skills-edit-actions" id="skillsEditActions" style="display: none;">
                        <button class="btn btn-secondary" onclick="cancelSkillsEdit()">
                            <i class="fas fa-times"></i>
                            Cancel
                        </button>
                        <button class="btn btn-primary" onclick="saveSkills()">
                            <i class="fas fa-save"></i>
                            Save Changes
                        </button>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="profile-card">
                <div class="profile-card-header">
                    <h3>
                        <i class="fas fa-bolt"></i>
                        Quick Actions
                    </h3>
                </div>
                <div class="profile-card-body">
                    <div class="quick-actions">
                        <a href="{{ route('employee.attendance') }}" class="quick-action-btn">
                            <i class="fas fa-calendar-check"></i>
                            <span>View Attendance</span>
                            <div class="action-badge">{{ $attendanceCount }} days</div>
                        </a>
                        <a href="{{ url('/employees/leaves') }}" class="quick-action-btn">
                            <i class="fas fa-calendar-times"></i>
                            <span>Request Leave</span>
                            <div class="action-badge">{{ $leaveRemaining }} left</div>
                        </a>
                        <a href="{{ route('employee.projects') }}" class="quick-action-btn">
                            <i class="fas fa-project-diagram"></i>
                            <span>My Projects</span>
                            <div class="action-badge">{{ $projectCount }} active</div>
                        </a>
                        <a href="{{ route('employee.documents') }}" class="quick-action-btn">
                            <i class="fas fa-file-alt"></i>
                            <span>My Documents</span>
                            <div class="action-badge">{{ $documentCount }} files</div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Skill Modal -->
    <div id="addSkillModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-plus-circle"></i> Add New Skill</h3>
                <button class="close-btn" onclick="closeAddSkillModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="addSkillForm" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="skillName">Skill Name <span class="required">*</span></label>
                        <input type="text" id="skillName" name="skill_name" class="form-control"
                            placeholder="e.g., JavaScript, Laravel, Docker" required>
                    </div>
                    <div class="form-group">
                        <label for="skillLevel">Proficiency Level <span class="required">*</span></label>
                        <select id="skillLevel" name="skill_level" class="form-control" required>
                            <option value="">Select Level</option>
                            <option value="beginner">Beginner</option>
                            <option value="intermediate">Intermediate</option>
                            <option value="expert">Expert</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="skillCategory">Category <span class="required">*</span></label>
                        <select id="skillCategory" name="skill_category" class="form-control" required>
                            <option value="">Select Category</option>
                            <option value="Programming Languages">Programming Languages</option>
                            <option value="Frameworks & Technologies">Frameworks & Technologies</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeAddSkillModal()">Cancel</button>
                <button class="btn btn-primary" onclick="addNewSkill()">
                    <i class="fas fa-plus"></i> Add Skill
                </button>
            </div>
        </div>
    </div>

    <!-- Edit Skill Modal -->
    <div id="editSkillModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-edit"></i> Edit Skill</h3>
                <button class="close-btn" onclick="closeEditSkillModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="editSkillForm">
                    <div class="form-group">
                        <label for="editSkillName">Skill Name <span class="required">*</span></label>
                        <input type="text" id="editSkillName" name="skill_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="editSkillLevel">Proficiency Level <span class="required">*</span></label>
                        <select id="editSkillLevel" name="skill_level" class="form-control" required>
                            <option value="beginner">Beginner</option>
                            <option value="intermediate">Intermediate</option>
                            <option value="expert">Expert</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeEditSkillModal()">Cancel</button>
                <button class="btn btn-primary" onclick="updateSkill()">
                    <i class="fas fa-save"></i> Update Skill
                </button>
            </div>
        </div>
    </div>



    <script>
        // Skills editing functionality
        let isSkillsEditMode = false;
        let currentEditingSkill = null;
        let deletedSkills = [];
        let originalSkillsHTML = '';

        function toggleSkillsEditMode() {
            const editBtn = document.getElementById('skillsEditBtn');
            const editActions = document.getElementById('skillsEditActions');
            const removeButtons = document.querySelectorAll('.skill-remove-btn');
            const addButtons = document.querySelectorAll('.add-skill-btn');

            isSkillsEditMode = !isSkillsEditMode;

            if (isSkillsEditMode) {
                // Save current DOM snapshot for cancel restore
                originalSkillsHTML = document.getElementById('skillsSection').innerHTML;

                editBtn.style.display = 'none';
                editActions.style.display = 'flex';

                removeButtons.forEach(btn => (btn.style.display = 'block'));
                addButtons.forEach(btn => (btn.style.display = 'inline-flex'));

                // Make skill tags clickable for editing
                document.querySelectorAll('.skill-tag').forEach(tag => {
                    tag.style.cursor = 'pointer';
                    tag.addEventListener('click', editSkillHandler);
                });
            } else {
                editBtn.style.display = 'inline-flex';
                editActions.style.display = 'none';

                removeButtons.forEach(btn => (btn.style.display = 'none'));
                addButtons.forEach(btn => (btn.style.display = 'none'));

                // Remove click handlers
                document.querySelectorAll('.skill-tag').forEach(tag => {
                    tag.style.cursor = 'default';
                    tag.removeEventListener('click', editSkillHandler);
                });
            }
        }


        function editSkillHandler(event) {
            if (event.target.classList.contains('skill-remove-btn')) {
                return; // Don't edit when clicking remove button
            }

            const skillTag = event.currentTarget;
            const skillName = skillTag.dataset.skill;
            const skillLevel = skillTag.classList.contains('expert') ? 'expert' :
                skillTag.classList.contains('intermediate') ? 'intermediate' : 'beginner';

            currentEditingSkill = skillTag;

            // Populate edit modal
            document.getElementById('editSkillName').value = skillName;
            document.getElementById('editSkillLevel').value = skillLevel;

            // Show edit modal
            document.getElementById('editSkillModal').classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function openAddSkillModal(category) {
            document.getElementById('skillCategory').value = category;
            document.getElementById('addSkillModal').classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeAddSkillModal() {
            document.getElementById('addSkillModal').classList.remove('show');
            document.getElementById('addSkillForm').reset();
            document.body.style.overflow = 'auto';
        }

        function closeEditSkillModal() {
            document.getElementById('editSkillModal').classList.remove('show');
            document.getElementById('editSkillForm').reset();
            currentEditingSkill = null;
            document.body.style.overflow = 'auto';
        }

        function addNewSkill() {
            const form = document.getElementById('addSkillForm');
            const formData = new FormData(form);

            const skillName = formData.get('skill_name').trim();
            const skillLevel = formData.get('skill_level');
            const skillCategory = formData.get('skill_category');

            if (!skillName || !skillLevel || !skillCategory) {
                showMessage('Please fill in all required fields', 'error');
                return;
            }

            // Check if skill already exists
            const existingSkill = document.querySelector(`[data-skill="${skillName}"]`);
            if (existingSkill) {
                showMessage('This skill already exists', 'error');
                return;
            }

            // Create new skill tag
            const skillContainer = document.querySelector(`[data-category="${skillCategory}"]`);
            if (!skillContainer) {
                console.error(`Skill container not found for category: ${skillCategory}`);
                showMessage(
                    `Could not find the section for category "${skillCategory}". Please ensure it exists in the HTML.`,
                    'error');
                return; // Stop execution if the container is not found
            }
            const addButton = skillContainer.querySelector('.add-skill-btn');

            const newSkillTag = document.createElement('span');
            newSkillTag.className = `skill-tag ${skillLevel}`;
            newSkillTag.dataset.skill = skillName;
            newSkillTag.innerHTML = `
            ${skillName}
            <button class="skill-remove-btn" onclick="removeSkill(this)" style="display: ${isSkillsEditMode ? 'block' : 'none'};">
                <i class="fas fa-times"></i>
            </button>
        `;
            // Add click handler if in edit mode
            if (isSkillsEditMode) {
                newSkillTag.style.cursor = 'pointer';
                newSkillTag.addEventListener('click', editSkillHandler);
            }

            skillContainer.insertBefore(newSkillTag, addButton);

            closeAddSkillModal();

            showMessage('Skill added successfully!', 'success');
        }

        function updateSkill() {
            if (!currentEditingSkill) return;

            const form = document.getElementById('editSkillForm');
            const formData = new FormData(form);

            const skillName = formData.get('skill_name').trim();
            const skillLevel = formData.get('skill_level');

            if (!skillName || !skillLevel) {
                showMessage('Please fill in all required fields', 'error');
                return;
            }

            // Check if skill name already exists (excluding current skill)
            const existingSkill = document.querySelector(`[data-skill="${skillName}"]`);
            if (existingSkill && existingSkill !== currentEditingSkill) {
                showMessage('This skill name already exists', 'error');
                return;
            }

            // Update skill tag
            currentEditingSkill.dataset.skill = skillName;
            currentEditingSkill.className = `skill-tag ${skillLevel}`;
            currentEditingSkill.childNodes[0].textContent = skillName + ' ';

            closeEditSkillModal();
            showMessage('Skill updated successfully!', 'success');
        }

        function removeSkill(button) {
            if (confirm('Are you sure you want to remove this skill?')) {
                const skillTag = button.closest('.skill-tag');
                skillTag.remove();
                showMessage('Skill removed successfully!', 'success');
            }
        }

        function cancelSkillsEdit() {
            const skillsSection = document.getElementById('skillsSection');

            // Restore original DOM snapshot if available
            if (originalSkillsHTML) {
                skillsSection.innerHTML = originalSkillsHTML;
            }

            // Reset temporary edit data
            deletedSkills = [];
            currentEditingSkill = null;

            // Exit edit mode
            isSkillsEditMode = false;
            document.getElementById('skillsEditBtn').style.display = 'inline-flex';
            document.getElementById('skillsEditActions').style.display = 'none';

            // Clean up click listeners
            document.querySelectorAll('.skill-tag').forEach(tag => {
                tag.style.cursor = 'default';
                tag.removeEventListener('click', editSkillHandler);
            });

            showMessage('All unsaved skill changes have been discarded.', 'info');
        }



        async function saveSkills() {
            const skills = [];

            document.querySelectorAll('.skill-tag').forEach(tag => {
                const skillName = tag.dataset.skill;
                const skillLevel = tag.classList.contains('expert') ?
                    'expert' :
                    tag.classList.contains('intermediate') ?
                    'intermediate' :
                    'beginner';
                const category = tag.closest('[data-category]').dataset.category;

                skills.push({
                    name: skillName,
                    level: skillLevel,
                    category: category
                });
            });

            try {
                const response = await fetch('{{ route('employee.skills.save') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        skills
                    })
                });

                const text = await response.text();
                let data;

                try {
                    data = JSON.parse(text);
                } catch (e) {
                    console.error("Non-JSON response:", text);
                    showMessage('Unexpected response from server.', 'error');
                    return;
                }

                if (data.success) {
                    toggleSkillsEditMode();
                    showMessage(data.message, 'success');
                } else {
                    showMessage('Failed to save skills.', 'error');
                }
            } catch (error) {
                console.error('Error saving skills:', error);
                showMessage('An error occurred while saving.', 'error');
            }
        }


        function showMessage(message, type = 'info') {
            const messageDiv = document.createElement('div');
            messageDiv.className = `message-toast ${type}`;
            messageDiv.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' :
                                type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
            ${message}
        `;

            messageDiv.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${type === 'success' ? '#22c55e' :
                        type === 'error' ? '#ef4444' : '#3b82f6'};
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 0.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 1001;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
            animation: slideInFromRight 0.3s ease;
        `;

            document.body.appendChild(messageDiv);

            setTimeout(() => {
                messageDiv.style.animation = 'slideOutToRight 0.3s ease';
                setTimeout(() => messageDiv.remove(), 300);
            }, 3000);
        }

        // Close modals when clicking outside
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('modal')) {
                if (e.target.id === 'addSkillModal') {
                    closeAddSkillModal();
                } else if (e.target.id === 'editSkillModal') {
                    closeEditSkillModal();
                }
            }
        });

        // Close modals on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeAddSkillModal();
                closeEditSkillModal();
            }
        });

        function toggleEditMode() {
            const form = document.getElementById('profileForm');
            const inputs = form.querySelectorAll('input[readonly]:not([disabled])');
            const actions = document.getElementById('formActions');
            const editBtn = document.querySelector('.profile-actions .btn');

            inputs.forEach(input => {
                input.removeAttribute('readonly');
            });

            actions.style.display = 'flex';
            editBtn.style.display = 'none';
        }

        function cancelEdit() {
            const form = document.getElementById('profileForm');
            const inputs = form.querySelectorAll('input:not([disabled])');
            const actions = document.getElementById('formActions');
            const editBtn = document.querySelector('.profile-actions .btn');

            inputs.forEach(input => {
                input.setAttribute('readonly', 'readonly');
            });

            actions.style.display = 'none';
            editBtn.style.display = 'inline-flex';

            // Reset form values
            form.reset();

            // Restore original values
            document.getElementById('employee_name').value = '{{ $employee->employee_name }}';
            document.getElementById('email').value = '{{ $employee->email }}';
            document.getElementById('contact_no').value = '{{ $employee->contact_no }}';
            document.getElementById('date_of_birth').value = '{{ $employee->date_of_birth }}';
        }




        function saveProfile() {
            const form = document.getElementById('profileForm');

            // Get input elements and their trimmed values
            const nameInput = document.getElementById('employee_name');
            const nameValue = nameInput.value.trim();

            const emailInput = document.getElementById('email');
            const emailValue = emailInput.value.trim();

            const contactNoInput = document.getElementById('contact_no');
            const contactNoValue = contactNoInput.value.trim();

            // 1. Employee Name Validation (Must not be empty)
            if (nameValue === '') {
                showMessage('Full Name cannot be empty.', 'error');
                nameInput.focus();
                return; // Stop submission
            }


            // 2. Email Validation (Must not be empty and must be a valid format)
            if (emailValue === '') {
                showMessage('Email Address cannot be empty.', 'error');
                emailInput.focus();
                return; // Stop submission
            }

            // Regex for basic email format (user@domain.ext)
            // This is a simple check; server-side is always more robust.
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!emailRegex.test(emailValue)) {
                showMessage('Please enter a valid email address.', 'error');
                emailInput.focus();
                return; // Stop submission
            }

            // 3. Contact Number Validation (Must not be empty and must be a valid format)
            if (contactNoValue === '') {
                showMessage('Contact Number cannot be empty.', 'error');
                contactNoInput.focus();
                return; // Stop submission
            }

            const phoneRegex = /^[0-9\s\-\(\)]{10,20}$/;

            if (!phoneRegex.test(contactNoValue)) {
                showMessage('Please enter a valid contact number (10-20 characters, digits only).', 'error');
                contactNoInput.focus();
                return; // Stop submission
            }

            // Show success message
            showMessage('Profile updated successfully!', 'success');

            // Submit form after a brief delay
            setTimeout(() => {
                form.submit();
            }, 300);
        }

    </script>
@endsection
