@extends('layouts.employee')
<link rel="stylesheet" href="{{ asset('css/Employee/myProfile.css') }}">
@section('title', 'My Profile')

@section('content')
    <div class="profile-container">

        <!-- Profile Header -->
        <div class="profile-header">
            <div class="profile-avatar-section">
                <div class="profile-avatar-large">
                    JD
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
                                <input type="text" id="joining_date" value="{{ $employee->created_at->format('Y-m-d') }}"
                                    class="form-control" readonly disabled>
                            </div>

                            <div class="form-group">
                                <label for="date_of_birth">Date of Birth</label>
                                <input type="date" id="date_of_birth" name="date_of_birth"
                                    value="{{ $employee->created_at }}" class="form-control" readonly>
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
                                            {{ strtoupper(substr($employee->department->manager->employee_name, 0, 1)) }}
                                            {{ strtoupper(substr($employee->department->manager->last_name, 0, 1)) }}
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
                        <a href="#" class="quick-action-btn">
                            <i class="fas fa-calendar-check"></i>
                            <span>View Attendance</span>
                            <div class="action-badge">12 days</div>
                        </a>
                        <a href="#" class="quick-action-btn">
                            <i class="fas fa-calendar-times"></i>
                            <span>Request Leave</span>
                            <div class="action-badge">5 left</div>
                        </a>
                        <a href="#" class="quick-action-btn">
                            <i class="fas fa-project-diagram"></i>
                            <span>My Projects</span>
                            <div class="action-badge">3 active</div>
                        </a>
                        <a href="#" class="quick-action-btn">
                            <i class="fas fa-file-alt"></i>
                            <span>My Documents</span>
                            <div class="action-badge">8 files</div>
                        </a>
                        <a href="#" class="quick-action-btn">
                            <i class="fas fa-chart-line"></i>
                            <span>Performance</span>
                            <div class="action-badge">94%</div>
                        </a>
                        <a href="#" class="quick-action-btn">
                            <i class="fas fa-graduation-cap"></i>
                            <span>Training</span>
                            <div class="action-badge">2 new</div>
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

    <style>
        .profile-container {
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

        .profile-header {
            background: var(--gradient-hero);
            background-size: 400% 400%;
            animation: gradientShift 18s ease infinite;
            color: white;
            padding: 2rem;
            border-radius: 1rem;
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: var(--shadow-xl);
            position: relative;
            overflow: hidden;
        }

        @keyframes gradientShift {
            0% {
                background-position: 0% 50%;
            }

            25% {
                background-position: 100% 50%;
            }

            50% {
                background-position: 100% 100%;
            }

            75% {
                background-position: 0% 100%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        .profile-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }

        .profile-avatar-section {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            position: relative;
            z-index: 1;
        }

        .profile-avatar-large {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            font-weight: 700;
            color: white;
            border: 3px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
        }

        .profile-header-info h1 {
            margin: 0;
            font-size: 2rem;
            font-weight: 700;
            color: #dc2626;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .profile-role {
            color: rgba(255, 255, 255, 0.9);
            font-size: 1.1rem;
            margin: 0.25rem 0;
        }

        .profile-id {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
            margin: 0;
        }

        .profile-actions {
            position: relative;
            z-index: 1;
        }

        .profile-actions .btn {
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            backdrop-filter: blur(10px);
        }

        .profile-actions .btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }

        .profile-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .profile-card {
            background: white;
            border-radius: 1rem;
            box-shadow: var(--shadow-lg);
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid var(--border-light);
        }

        .profile-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-xl);
            border-color: var(--redcode-primary);
        }

        .profile-card:nth-child(3),
        .profile-card:nth-child(4) {
            grid-column: 1 / -1;
        }

        .profile-card-header {
            background: var(--gradient-hero);
            background-size: 400% 400%;
            animation: gradientShift 18s ease infinite;
            color: white;
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-light);
            position: relative;
            overflow: hidden;
        }

        .profile-card-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
        }

        .profile-card-header h3 {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 600;
            color: #dc2626;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            position: relative;
            z-index: 1;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .profile-card-body {
            padding: 2rem;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .form-control {
            padding: 0.875rem 1rem;
            border: 2px solid var(--border-light);
            border-radius: 0.75rem;
            font-size: 1rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: var(--bg-primary);
            box-shadow: var(--shadow-sm);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--redcode-primary);
            box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.1);
            transform: translateY(-2px);
        }

        .form-control:read-only {
            background: var(--gray-50);
            color: var(--text-secondary);
            border-color: var(--border-medium);
        }

        .form-control:disabled {
            background: var(--gray-100);
            color: var(--text-light);
            cursor: not-allowed;
            border-color: var(--border-light);
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            font-size: 0.875rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
        }

        .status-badge:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .status-active {
            background: linear-gradient(135deg, var(--redcode-green) 0%, #047857 100%);
            color: white;
        }

        .status-paid {
            background: linear-gradient(135deg, var(--redcode-green) 0%, #047857 100%);
            color: white;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid var(--border-light);
        }

        .btn-secondary {
            background: var(--gradient-secondary);
            color: white;
            border: 2px solid var(--redcode-dark);
            box-shadow: var(--shadow-sm);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-secondary:hover {
            background: var(--redcode-dark);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        .info-item {
            display: flex;
            flex-direction: column;
        }

        .info-item label {
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .info-value {
            color: var(--text-primary);
            font-size: 1rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .department-badge {
            background: var(--gradient-hero);
            background-size: 400% 400%;
            animation: gradientShift 18s ease infinite;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 1rem;
            font-size: 0.875rem;
            font-weight: 600;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
        }

        .department-badge:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .team-badge {
            display: inline-block;
            background: var(--redcode-primary-light);
            color: var(--redcode-primary);
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.875rem;
            font-weight: 600;
            margin-right: 0.5rem;
            margin-bottom: 0.25rem;
            border: 1px solid var(--redcode-primary);
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
        }

        .team-badge:hover {
            background: var(--redcode-primary);
            color: white;
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .manager-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .manager-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--gradient-hero);
            background-size: 400% 400%;
            animation: gradientShift 18s ease infinite;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.875rem;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
        }

        .manager-avatar:hover {
            transform: scale(1.1);
            box-shadow: var(--shadow-md);
        }

        .manager-details {
            display: flex;
            flex-direction: column;
        }

        .manager-name {
            font-weight: 600;
            color: var(--text-primary);
        }

        .manager-title {
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        .skills-section {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .skill-category h4 {
            color: #dc2626;
            margin-bottom: 0.75rem;
            font-size: 1rem;
            font-weight: 600;
        }

        .skill-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .skill-tag {
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            font-size: 0.875rem;
            font-weight: 500;
            position: relative;
            overflow: hidden;
        }

        .skill-tag.expert {
            background: var(--gradient-hero);
            background-size: 400% 400%;
            animation: gradientShift 18s ease infinite;
            color: white;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
        }

        .skill-tag.expert:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .skill-tag.intermediate {
            background: linear-gradient(135deg, var(--redcode-orange) 0%, #B45309 100%);
            color: white;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
        }

        .skill-tag.intermediate:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .skill-tag.beginner {
            background: var(--gradient-secondary);
            color: white;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
        }

        .skill-tag.beginner:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        /* Skill editing styles */
        .skill-tag {
            position: relative;
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 1rem;
            background: var(--border-light);
            color: var(--text-primary);
            border-radius: 1rem;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .skill-remove-btn {
            position: absolute;
            top: -8px;
            right: -8px;
            width: 20px;
            height: 20px;
            border: none;
            border-radius: 50%;
            background: #ef4444;
            color: white;
            font-size: 0.75rem;
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 1;
        }

        .skill-remove-btn:hover {
            background: #dc2626;
            transform: scale(1.1);
        }

        .add-skill-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: var(--gradient-hero);
            color: white;
            border: none;
            border-radius: 1rem;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .add-skill-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
        }

        .skills-edit-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border-light);
        }

        .profile-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-light);
            background: var(--bg-card);
        }

        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }

        .btn-outline {
            background: transparent;
            color: var(--redcode-primary);
            border: 2px solid var(--redcode-primary);
        }

        .btn-outline:hover {
            background: var(--redcode-primary);
            color: white;
        }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
        }

        .modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: white;
            border-radius: 1rem;
            max-width: 500px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            animation: modalSlideIn 0.3s ease;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-light);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: var(--gradient-hero);
            color: white;
            border-radius: 1rem 1rem 0 0;
        }

        .modal-header h3 {
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.25rem;
            font-weight: 600;
            color: #dc2626;
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

        .modal-footer {
            padding: 1.5rem;
            border-top: 1px solid var(--border-light);
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            background: var(--bg-light);
            border-radius: 0 0 1rem 1rem;
        }

        .required {
            color: #ef4444;
        }

        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
        }

        .quick-action-btn {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1.25rem;
            background: var(--bg-primary);
            border: 2px solid var(--border-light);
            border-radius: 0.75rem;
            color: var(--text-primary);
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-weight: 500;
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-sm);
        }

        .quick-action-btn::before {
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

        .quick-action-btn:hover::before {
            transform: scaleY(1);
        }

        .quick-action-btn:hover {
            background: var(--redcode-primary-light);
            border-color: var(--redcode-primary);
            color: var(--redcode-primary);
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .quick-action-btn i {
            font-size: 1.25rem;
            color: var(--redcode-primary);
            flex-shrink: 0;
            transition: all 0.3s ease;
        }

        .quick-action-btn:hover i {
            transform: scale(1.2);
        }

        .quick-action-btn span {
            flex: 1;
        }

        .action-badge {
            background: var(--gradient-hero);
            background-size: 400% 400%;
            animation: gradientShift 18s ease infinite;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.75rem;
            font-weight: 600;
            margin-left: auto;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
        }

        .action-badge:hover {
            transform: scale(1.1);
            box-shadow: var(--shadow-md);
        }

        @media (max-width: 768px) {
            .profile-header {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
                padding: 1.5rem;
            }

            .profile-avatar-section {
                flex-direction: column;
                text-align: center;
            }

            .profile-content {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .form-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .info-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .quick-actions {
                grid-template-columns: 1fr;
            }

            .profile-card-body {
                padding: 1.5rem;
            }
        }

        @media (max-width: 480px) {
            .profile-container {
                padding: 0 1rem;
            }

            .profile-header {
                margin-bottom: 1rem;
            }

            .profile-avatar-large {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }

            .profile-header-info h1 {
                font-size: 1.5rem;
            }

            .profile-content {
                gap: 1rem;
            }
        }
    </style>

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
                input.style.background = '#fff';
                input.style.color = '#111827';
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
                input.style.background = '#f9fafb';
                input.style.color = '#6b7280';
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
            // Show success message
            const form = document.getElementById('profileForm');
            const successMessage = document.createElement('div');
            successMessage.className = 'success-message';
            successMessage.innerHTML = `
            <i class="fas fa-check-circle"></i>
            Profile updated successfully!
        `;
            successMessage.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: var(--redcode-green);
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 0.5rem;
            box-shadow: var(--shadow-lg);
            z-index: 1000;
            animation: slideIn 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        `;

            document.body.appendChild(successMessage);
            // Auto remove after 3 seconds

            setTimeout(() => {
                form.submit();
                successMessage.remove();
            }, 3000);

            // Exit edit mode
        }

        // Add keyframe animation for success message
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

        @keyframes slideInFromRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOutToRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }
    `;
        document.head.appendChild(style);
    </script>
@endsection
