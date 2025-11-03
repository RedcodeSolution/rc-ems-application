@extends('layouts.employee')

@section('title', 'My Projects')
<link rel="stylesheet" href="{{ asset('css/Employee/myProjects.css') }}">
@section('content')
    <div class="projects-container">
        <div class="projects-header">
            <div class="header-content">
                <div class="header-info">
                    <h1><i class="fas fa-project-diagram"></i> My Projects</h1>
                    <p>Track your assigned projects and collaborate with teams</p>
                </div>
                <div class="header-actions">
                    <button class="btn btn-secondary" onclick="toggleViewMode()">
                        <i class="fas fa-th-large" id="viewModeIcon"></i>
                        <span id="viewModeText">Grid View</span>
                    </button>
                </div>
            </div>
        </div>

        <div class="projects-stats">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-tasks"></i>
                </div>
                <div class="stat-info">
                    <h3>12</h3>
                    <p>Active Projects</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-info">
                    <h3>8</h3>
                    <p>Completed</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-info">
                    <h3>3</h3>
                    <p>Pending</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="stat-info">
                    <h3>2</h3>
                    <p>Overdue</p>
                </div>
            </div>
        </div>

        <div class="project-status-filter">
            <h2>Project Status</h2>
            <div class="status-grid">
                <div class="status-card" data-status="active">
                    <div class="status-icon">
                        <i class="fas fa-play"></i>
                    </div>
                    <div class="status-info">
                        <h3>Active Projects</h3>
                        <p>12 projects</p>
                    </div>
                </div>
                <div class="status-card" data-status="completed">
                    <div class="status-icon">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="status-info">
                        <h3>Completed</h3>
                        <p>8 projects</p>
                    </div>
                </div>
                <div class="status-card" data-status="pending">
                    <div class="status-icon">
                        <i class="fas fa-pause"></i>
                    </div>
                    <div class="status-info">
                        <h3>Pending</h3>
                        <p>3 projects</p>
                    </div>
                </div>
                <div class="status-card" data-status="overdue">
                    <div class="status-icon">
                        <i class="fas fa-exclamation"></i>
                    </div>
                    <div class="status-info">
                        <h3>Overdue</h3>
                        <p>2 projects</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="projects-controls">
            <div class="search-section">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search projects..." id="searchInput">
                </div>
                <div class="filter-section">
                    <select id="statusFilter" class="filter-select">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="completed">Completed</option>
                        <option value="pending">Pending</option>
                        <option value="overdue">Overdue</option>
                    </select>
                    <select id="priorityFilter" class="filter-select">
                        <option value="">All Priority</option>
                        <option value="high">High Priority</option>
                        <option value="medium">Medium Priority</option>
                        <option value="low">Low Priority</option>
                    </select>
                    <select id="teamFilter" class="filter-select">
                        <option value="">All Teams</option>
                        <option value="development">Development</option>
                        <option value="design">Design</option>
                        <option value="marketing">Marketing</option>
                        <option value="qa">Quality Assurance</option>
                    </select>
                </div>
            </div>
        </div>


        <div class="projects-content">
            <div class="projects-grid" id="projectsGrid">
                @forelse($employeeProjects as $project)
                    @php
                        $progress = $project->progress ?? 0;
                        $priority = $progress >= 80 ? 'high' : ($progress >= 50 ? 'medium' : 'low');
                        $status = $project->pivot_status ?? 'Pending';
                        $deadline = $project->deadline
                            ? \Carbon\Carbon::parse($project->deadline)->format('M d, Y')
                            : 'No deadline';
                        $role = $project->role ?? 'N/A';
                        $teamName = $project->team_name ?? 'N/A';
                    @endphp

                    <div class="project-card" data-status="{{ strtolower($status) }}" data-priority="{{ $priority }}"
                        data-team="{{ strtolower($teamName) }}">
                        <div class="project-header">
                            <div class="project-title">
                                <h4>{{ $project->project_name }}</h4>
                                <span class="priority-badge {{ $priority }}">{{ ucfirst($priority) }} Priority</span>
                            </div>
                            <div class="project-status {{ strtolower($status) }}">
                                @if ($status == 'Active')
                                    <i class="fas fa-play"></i>
                                @elseif($status == 'Completed')
                                    <i class="fas fa-check"></i>
                                @elseif($status == 'On Hold')
                                    <i class="fas fa-pause"></i>
                                @else
                                    <i class="fas fa-exclamation"></i>
                                @endif
                                {{ $status }}
                            </div>
                        </div>

                        <div class="project-info">
                            <p>{{ $project->description ?? 'No description available.' }}</p>
                            <div class="project-meta">
                                <span><i class="fas fa-user"></i> Role: {{ $role }}</span>
                                <span><i class="fas fa-users"></i> Team: {{ $teamName }}</span>
                                <span><i class="fas fa-calendar"></i> Due: {{ $deadline }}</span>
                            </div>
                        </div>

                        <div style="margin: 1rem 0;">
                            <div style="font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem;">
                                Progress
                            </div>
                            <div style="background: var(--gray-200); height: 8px; border-radius: 4px; overflow: hidden;">
                                <div
                                    style="background: linear-gradient(90deg, var(--primary), var(--secondary)); height: 100%; width: {{ $progress }}%; transition: width 0.3s ease;">
                                </div>
                            </div>
                            <div style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 0.25rem;">
                                {{ $progress }}%
                            </div>
                        </div>


                        <div class="project-actions">
                            <button class="action-btn" onclick="viewProject('{{ $project->project_name }}')"
                                title="View Project">
                                <i class="fas fa-eye"></i>
                            </button>

                            <button class="action-btn" onclick="editProject('E-Commerce Platform')">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                    </div>
                @empty
                    <p>No projects assigned to you yet.</p>
                @endforelse
            </div>
        </div>



        <div id="createProjectModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3><i class="fas fa-plus"></i> Create New Project</h3>
                    <button class="close-btn" onclick="closeCreateProjectModal()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="projectName">Project Name</label>
                        <input type="text" id="projectName" class="form-control" placeholder="Enter project name...">
                    </div>
                    <div class="form-group">
                        <label for="projectDescription">Description</label>
                        <textarea id="projectDescription" class="form-control" rows="3" placeholder="Enter project description..."></textarea>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="projectPriority">Priority</label>
                            <select id="projectPriority" class="form-control">
                                <option value="high">High Priority</option>
                                <option value="medium">Medium Priority</option>
                                <option value="low">Low Priority</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="projectTeam">Team</label>
                            <select id="projectTeam" class="form-control">
                                <option value="development">Development</option>
                                <option value="design">Design</option>
                                <option value="marketing">Marketing</option>
                                <option value="qa">Quality Assurance</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="projectDeadline">Deadline</label>
                        <input type="date" id="projectDeadline" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" onclick="closeCreateProjectModal()">Cancel</button>
                    <button class="btn btn-primary" onclick="createProject()">
                        <i class="fas fa-plus"></i>
                        Create Project
                    </button>
                </div>
            </div>
        </div>

        <div id="viewProjectModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3><i class="fas fa-eye"></i> <span id="viewProjectTitle">Project Title</span></h3>
                    <button class="close-btn" onclick="closeViewProjectModal()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="viewProjectDescription"></p>
                    <div class="project-meta" style="margin-top:1rem;">
                        <span><i class="fas fa-user"></i> <strong id="viewProjectTeam">Team</strong></span>
                        <span style="display:block;margin-top:.5rem;"><i class="fas fa-calendar"></i> <strong
                                id="viewProjectDeadline">Due: --</strong></span>
                        <span style="display:block;margin-top:.5rem;"><i class="fas fa-flag"></i> <strong
                                id="viewProjectPriority">Priority</strong></span>
                    </div>
                    <div style="margin-top:1rem;">
                        <div class="progress-info">
                            <span>Progress</span>
                            <span id="viewProjectProgressText">0%</span>
                        </div>
                        <div class="progress-bar" style="height:10px;">
                            <div id="viewProjectProgressFill" class="progress-fill" style="width:0%; height:100%"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" onclick="closeViewProjectModal()">Close</button>
                    <button class="btn btn-primary" onclick="openEditFromView()">
                        <i class="fas fa-edit"></i>
                        Edit Project
                    </button>
                </div>
            </div>
        </div>

        <div id="editProjectModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3><i class="fas fa-edit"></i> Edit Project</h3>
                    <button class="close-btn" onclick="closeEditProjectModal()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="editProjectIdentifier">
                    <div class="form-group">
                        <label for="editProjectName">Project Name</label>
                        <input type="text" id="editProjectName" class="form-control"
                            placeholder="Enter project name...">
                    </div>
                    <div class="form-group">
                        <label for="editProjectDescription">Description</label>
                        <textarea id="editProjectDescription" class="form-control" rows="3"
                            placeholder="Enter project description..."></textarea>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="editProjectPriority">Priority</label>
                            <select id="editProjectPriority" class="form-control">
                                <option value="high">High Priority</option>
                                <option value="medium">Medium Priority</option>
                                <option value="low">Low Priority</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="editProjectTeam">Team</label>
                            <select id="editProjectTeam" class="form-control">
                                <option value="development">Development</option>
                                <option value="design">Design</option>
                                <option value="marketing">Marketing</option>
                                <option value="qa">Quality Assurance</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="editProjectDeadline">Deadline</label>
                        <input type="date" id="editProjectDeadline" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="editProjectProgress">Progress (%)</label>
                        <div class="range-wrapper" title="Scroll while hovering to change value">
                            <input type="range" id="editProjectProgress" class="form-control range-input"
                                min="0" max="100" step="1" value="0" />
                            <span id="editProjectProgressLabel" class="range-label">0%</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="editProjectStatus">Status</label>
                        <select id="editProjectStatus" class="form-control">
                            <option value="active">Active</option>
                            <option value="completed">Completed</option>
                            <option value="pending">Pending</option>
                            <option value="overdue">Overdue</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" onclick="closeEditProjectModal()">Cancel</button>
                    <button class="btn btn-primary" onclick="saveProjectEdits()">
                        <i class="fas fa-save"></i>
                        Save Changes
                    </button>
                </div>
            </div>
        </div>

    </div>

    <script>
        let currentViewMode = 'grid';

        function toggleViewMode() {
            const grid = document.getElementById('projectsGrid');
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

        function openCreateProjectModal() {
            document.getElementById('createProjectModal').classList.add('active');
        }

        function closeCreateProjectModal() {
            document.getElementById('createProjectModal').classList.remove('active');
        }

        function createProject() {
            const name = document.getElementById('projectName').value;
            const description = document.getElementById('projectDescription').value;
            const priority = document.getElementById('projectPriority').value;
            const team = document.getElementById('projectTeam').value;
            const deadline = document.getElementById('projectDeadline').value;

            if (!name || !description || !deadline) {
                alert('Please fill in all required fields.');
                return;
            }

            showMessage('Project created successfully!', 'success');
            closeCreateProjectModal();

            document.getElementById('projectName').value = '';
            document.getElementById('projectDescription').value = '';
            document.getElementById('projectDeadline').value = '';
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

        function viewProject(projectName) {
            const card = Array.from(document.querySelectorAll('.project-card')).find(c => {
                const h = c.querySelector('h4');
                return h && h.textContent.trim() === projectName;
            });

            if (!card) {
                showMessage(`Project "${projectName}" not found.`, 'info');
                return;
            }

            document.getElementById('viewProjectTitle').textContent = projectName;
            const desc = card.querySelector('.project-info p') ? card.querySelector('.project-info p').textContent.trim() :
                '';
            document.getElementById('viewProjectDescription').textContent = desc;

            document.getElementById('viewProjectTeam').textContent = (card.dataset.team || '').replace(/^\w/, c => c
                .toUpperCase());
            const deadlineSpan = Array.from(card.querySelectorAll('.project-meta span')).find(s => s.textContent
                .toLowerCase().includes('due') || s.textContent.toLowerCase().includes('completed'));
            document.getElementById('viewProjectDeadline').textContent = deadlineSpan ? deadlineSpan.textContent :
            'Due: --';

            const priorityBadge = card.querySelector('.priority-badge');
            document.getElementById('viewProjectPriority').textContent = priorityBadge ? priorityBadge.textContent.trim() :
                '';

            const progressText = card.querySelector('.progress-info span:nth-child(2)');
            const progressValue = progressText ? progressText.textContent.replace('%', '').trim() : '0';
            document.getElementById('viewProjectProgressText').textContent = progressValue + '%';
            const fill = document.getElementById('viewProjectProgressFill');
            fill.style.width = `${progressValue}%`;

            document.getElementById('editProjectIdentifier').value = projectName;

            document.getElementById('viewProjectModal').classList.add('active');
        }

        function closeViewProjectModal() {
            document.getElementById('viewProjectModal').classList.remove('active');
        }

        function editProject(projectName) {
            const card = Array.from(document.querySelectorAll('.project-card')).find(c => {
                const h = c.querySelector('h4');
                return h && h.textContent.trim() === projectName;
            });

            if (!card) {
                showMessage(`Project "${projectName}" not found.`, 'info');
                return;
            }

            populateEditModalFromCard(card);
            document.getElementById('editProjectModal').classList.add('active');
        }

        function openEditFromView() {
            const id = document.getElementById('editProjectIdentifier').value;
            closeViewProjectModal();
            if (id) {
                editProject(id);
            }
        }

        function populateEditModalFromCard(card) {
            const title = card.querySelector('h4') ? card.querySelector('h4').textContent.trim() : '';
            const desc = card.querySelector('.project-info p') ? card.querySelector('.project-info p').textContent.trim() :
                '';
            const priority = card.dataset.priority || '';
            const team = card.dataset.team || '';
            const progressText = card.querySelector('.progress-info span:nth-child(2)');
            const progressValue = progressText ? parseInt(progressText.textContent.replace('%', '').trim()) : 0;
            const status = card.dataset.status || '';
            const deadlineSpan = Array.from(card.querySelectorAll('.project-meta span')).find(s => s.textContent
                .toLowerCase().includes('due') || s.textContent.toLowerCase().includes('completed'));
            let deadlineVal = '';
            if (deadlineSpan) {
                const m = deadlineSpan.textContent.match(/(\w+\s+\d{1,2},\s*\d{4})/);
                if (m) {
                    deadlineVal = '';
                }
            }

            document.getElementById('editProjectIdentifier').value = title;
            document.getElementById('editProjectName').value = title;
            document.getElementById('editProjectDescription').value = desc;
            document.getElementById('editProjectPriority').value = priority;
            document.getElementById('editProjectTeam').value = team;
            document.getElementById('editProjectProgress').value = progressValue;
            document.getElementById('editProjectStatus').value = status;
            document.getElementById('editProjectDeadline').value = deadlineVal;
        }

        function saveProjectEdits() {
            const originalIdentifier = document.getElementById('editProjectIdentifier').value;
            const newName = document.getElementById('editProjectName').value.trim();
            const newDesc = document.getElementById('editProjectDescription').value.trim();
            const newPriority = document.getElementById('editProjectPriority').value;
            const newTeam = document.getElementById('editProjectTeam').value;
            const newDeadline = document.getElementById('editProjectDeadline').value;
            const newProgress = Math.min(100, Math.max(0, parseInt(document.getElementById('editProjectProgress').value ||
                0)));
            const newStatus = document.getElementById('editProjectStatus').value;

            if (!newName) {
                alert('Project name is required.');
                return;
            }

            const card = Array.from(document.querySelectorAll('.project-card')).find(c => {
                const h = c.querySelector('h4');
                return h && h.textContent.trim() === originalIdentifier;
            });

            if (!card) {
                showMessage('Original project card not found.', 'info');
                closeEditProjectModal();
                return;
            }

            if (card.querySelector('h4')) card.querySelector('h4').textContent = newName;
            if (card.querySelector('.project-info p')) card.querySelector('.project-info p').textContent = newDesc;

            const badge = card.querySelector('.priority-badge');
            if (badge) {
                badge.classList.remove('high', 'medium', 'low');
                badge.classList.add(newPriority);
                badge.textContent = `${newPriority.charAt(0).toUpperCase() + newPriority.slice(1)} Priority`;
            }

            card.dataset.priority = newPriority;
            card.dataset.team = newTeam;
            card.dataset.status = newStatus;

            const statusEl = card.querySelector('.project-status');
            if (statusEl) {
                statusEl.className = 'project-status ' + newStatus;
                const iconClass = newStatus === 'active' ? 'fa-play' : newStatus === 'completed' ? 'fa-check' :
                    newStatus === 'pending' ? 'fa-pause' : 'fa-exclamation';
                statusEl.innerHTML =
                    `<i class="fas ${iconClass}"></i> ${newStatus.charAt(0).toUpperCase() + newStatus.slice(1)}`;
            }

            const metaSpans = Array.from(card.querySelectorAll('.project-meta span'));
            if (metaSpans.length >= 2) {
                metaSpans[1].innerHTML =
                    `<i class="fas fa-calendar"></i> ${ newDeadline ? ('Due: ' + new Date(newDeadline).toLocaleDateString()) : 'Due: --' }`;
            }

            const progressText = card.querySelector('.progress-info span:nth-child(2)');
            if (progressText) progressText.textContent = `${newProgress}%`;
            const fill = card.querySelector('.progress-fill');
            if (fill) fill.style.width = `${newProgress}%`;

            showMessage('Project updated successfully!', 'success');
            closeEditProjectModal();
        }

        function closeEditProjectModal() {
            document.getElementById('editProjectModal').classList.remove('active');
        }

        function shareProject(projectName) {
            showMessage(`Sharing ${projectName}...`, 'info');
        }

        ['viewProjectModal', 'editProjectModal', 'createProjectModal'].forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.addEventListener('click', function(e) {
                    if (e.target === this) {
                        this.classList.remove('active');
                    }
                });
            }
        });

        document.getElementById('searchInput').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            filterProjects();
        });

        document.getElementById('statusFilter').addEventListener('change', filterProjects);
        document.getElementById('priorityFilter').addEventListener('change', filterProjects);
        document.getElementById('teamFilter').addEventListener('change', filterProjects);

        function filterProjects() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const statusFilter = document.getElementById('statusFilter').value;
            const priorityFilter = document.getElementById('priorityFilter').value;
            const teamFilter = document.getElementById('teamFilter').value;
            const projects = document.querySelectorAll('.project-card');

            projects.forEach(project => {
                const title = project.querySelector('h4').textContent.toLowerCase();
                const status = project.dataset.status;
                const priority = project.dataset.priority;
                const team = project.dataset.team;

                const matchesSearch = title.includes(searchTerm);
                const matchesStatus = !statusFilter || status === statusFilter;
                const matchesPriority = !priorityFilter || priority === priorityFilter;
                const matchesTeam = !teamFilter || team === teamFilter;

                if (matchesSearch && matchesStatus && matchesPriority && matchesTeam) {
                    project.style.display = 'block';
                } else {
                    project.style.display = 'none';
                }
            });
        }

        document.querySelectorAll('.status-card').forEach(card => {
            card.addEventListener('click', function() {
                const status = this.dataset.status;
                document.getElementById('statusFilter').value = status;
                filterProjects();

                document.querySelectorAll('.status-card').forEach(c => c.classList.remove('active'));
                this.classList.add('active');
            });
        });

        document.getElementById('createProjectModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeCreateProjectModal();
            }
        });

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

        .projects-grid.list-view {
            grid-template-columns: 1fr;
        }

        .projects-grid.list-view .project-card {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
        }

        .projects-grid.list-view .project-header {
            margin-bottom: 0;
        }

        .projects-grid.list-view .project-info {
            flex: 1;
        }

        .projects-grid.list-view .project-progress {
            margin-bottom: 0;
        }

        .projects-grid.list-view .project-actions {
            flex-shrink: 0;
        }
    `;
        document.head.appendChild(style);

        (function() {
            const slider = document.getElementById('editProjectProgress');
            const label = document.getElementById('editProjectProgressLabel');

            function setLabel(v) {
                if (label) label.textContent = `${v}%`;
            }

            if (slider) {
                slider.addEventListener('input', function(e) {
                    setLabel(e.target.value);
                });

                slider.addEventListener('wheel', function(e) {
                    e.preventDefault();
                    const step = e.shiftKey ? 10 : 1;
                    const delta = e.deltaY > 0 ? -step : step;
                    let val = parseInt(slider.value || 0, 10);
                    val = Math.min(100, Math.max(0, val + delta));
                    slider.value = val;
                    setLabel(val);
                    slider.dispatchEvent(new Event('input', {
                        bubbles: true
                    }));
                });

                slider.addEventListener('change', function(e) {
                    setLabel(e.target.value);
                });
            }

            const origPopulate = window.populateEditModalFromCard;
            if (typeof origPopulate === 'function') {
                window.populateEditModalFromCard = function(card) {
                    origPopulate(card);
                    const v = document.getElementById('editProjectProgress') ? document.getElementById(
                        'editProjectProgress').value : 0;
                    setLabel(v);
                };
            }

        })();
    </script>
@endsection
