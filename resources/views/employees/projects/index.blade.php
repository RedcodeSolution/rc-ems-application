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
                <button class="btn btn-primary" onclick="openCreateProjectModal()">
                    <i class="fas fa-plus"></i>
                    Create Project
                </button>
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
            <div class="project-card" data-status="active" data-priority="high" data-team="development">
                <div class="project-header">
                    <div class="project-title">
                        <h4>E-Commerce Platform</h4>
                        <span class="priority-badge high">High Priority</span>
                    </div>
                    <div class="project-status active">
                        <i class="fas fa-play"></i>
                        Active
                    </div>
                </div>
                <div class="project-info">
                    <p>Building a comprehensive e-commerce solution with modern features and secure payment integration.</p>
                    <div class="project-meta">
                        <span><i class="fas fa-user"></i> Development Team</span>
                        <span><i class="fas fa-calendar"></i> Due: Mar 15, 2024</span>
                    </div>
                </div>
                <div class="project-progress">
                    <div class="progress-info">
                        <span>Progress</span>
                        <span>75%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 75%"></div>
                    </div>
                </div>
                <div class="project-actions">
                    <button class="action-btn" onclick="viewProject('E-Commerce Platform')">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="action-btn" onclick="editProject('E-Commerce Platform')">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="action-btn" onclick="shareProject('E-Commerce Platform')">
                        <i class="fas fa-share"></i>
                    </button>
                </div>
            </div>

            <div class="project-card" data-status="active" data-priority="medium" data-team="design">
                <div class="project-header">
                    <div class="project-title">
                        <h4>Mobile App UI/UX</h4>
                        <span class="priority-badge medium">Medium Priority</span>
                    </div>
                    <div class="project-status active">
                        <i class="fas fa-play"></i>
                        Active
                    </div>
                </div>
                <div class="project-info">
                    <p>Designing user interface and user experience for the new mobile application.</p>
                    <div class="project-meta">
                        <span><i class="fas fa-user"></i> Design Team</span>
                        <span><i class="fas fa-calendar"></i> Due: Apr 10, 2024</span>
                    </div>
                </div>
                <div class="project-progress">
                    <div class="progress-info">
                        <span>Progress</span>
                        <span>60%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 60%"></div>
                    </div>
                </div>
                <div class="project-actions">
                    <button class="action-btn" onclick="viewProject('Mobile App UI/UX')">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="action-btn" onclick="editProject('Mobile App UI/UX')">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="action-btn" onclick="shareProject('Mobile App UI/UX')">
                        <i class="fas fa-share"></i>
                    </button>
                </div>
            </div>

            <div class="project-card" data-status="completed" data-priority="high" data-team="development">
                <div class="project-header">
                    <div class="project-title">
                        <h4>Customer Portal</h4>
                        <span class="priority-badge high">High Priority</span>
                    </div>
                    <div class="project-status completed">
                        <i class="fas fa-check"></i>
                        Completed
                    </div>
                </div>
                <div class="project-info">
                    <p>Developed a comprehensive customer portal for account management and support.</p>
                    <div class="project-meta">
                        <span><i class="fas fa-user"></i> Development Team</span>
                        <span><i class="fas fa-calendar"></i> Completed: Feb 28, 2024</span>
                    </div>
                </div>
                <div class="project-progress">
                    <div class="progress-info">
                        <span>Progress</span>
                        <span>100%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill completed" style="width: 100%"></div>
                    </div>
                </div>
                <div class="project-actions">
                    <button class="action-btn" onclick="viewProject('Customer Portal')">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="action-btn" onclick="editProject('Customer Portal')">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="action-btn" onclick="shareProject('Customer Portal')">
                        <i class="fas fa-share"></i>
                    </button>
                </div>
            </div>

            <div class="project-card" data-status="pending" data-priority="low" data-team="marketing">
                <div class="project-header">
                    <div class="project-title">
                        <h4>Marketing Campaign</h4>
                        <span class="priority-badge low">Low Priority</span>
                    </div>
                    <div class="project-status pending">
                        <i class="fas fa-pause"></i>
                        Pending
                    </div>
                </div>
                <div class="project-info">
                    <p>Planning and executing digital marketing campaign for product launch.</p>
                    <div class="project-meta">
                        <span><i class="fas fa-user"></i> Marketing Team</span>
                        <span><i class="fas fa-calendar"></i> Due: May 20, 2024</span>
                    </div>
                </div>
                <div class="project-progress">
                    <div class="progress-info">
                        <span>Progress</span>
                        <span>25%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill pending" style="width: 25%"></div>
                    </div>
                </div>
                <div class="project-actions">
                    <button class="action-btn" onclick="viewProject('Marketing Campaign')">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="action-btn" onclick="editProject('Marketing Campaign')">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="action-btn" onclick="shareProject('Marketing Campaign')">
                        <i class="fas fa-share"></i>
                    </button>
                </div>
            </div>

            <div class="project-card" data-status="overdue" data-priority="high" data-team="qa">
                <div class="project-header">
                    <div class="project-title">
                        <h4>Quality Assurance</h4>
                        <span class="priority-badge high">High Priority</span>
                    </div>
                    <div class="project-status overdue">
                        <i class="fas fa-exclamation"></i>
                        Overdue
                    </div>
                </div>
                <div class="project-info">
                    <p>Comprehensive testing and quality assurance for the main application.</p>
                    <div class="project-meta">
                        <span><i class="fas fa-user"></i> QA Team</span>
                        <span><i class="fas fa-calendar"></i> Due: Jan 30, 2024</span>
                    </div>
                </div>
                <div class="project-progress">
                    <div class="progress-info">
                        <span>Progress</span>
                        <span>85%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill overdue" style="width: 85%"></div>
                    </div>
                </div>
                <div class="project-actions">
                    <button class="action-btn" onclick="viewProject('Quality Assurance')">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="action-btn" onclick="editProject('Quality Assurance')">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="action-btn" onclick="shareProject('Quality Assurance')">
                        <i class="fas fa-share"></i>
                    </button>
                </div>
            </div>

            <div class="project-card" data-status="active" data-priority="medium" data-team="development">
                <div class="project-header">
                    <div class="project-title">
                        <h4>API Integration</h4>
                        <span class="priority-badge medium">Medium Priority</span>
                    </div>
                    <div class="project-status active">
                        <i class="fas fa-play"></i>
                        Active
                    </div>
                </div>
                <div class="project-info">
                    <p>Integrating third-party APIs for enhanced functionality and data synchronization.</p>
                    <div class="project-meta">
                        <span><i class="fas fa-user"></i> Development Team</span>
                        <span><i class="fas fa-calendar"></i> Due: Apr 25, 2024</span>
                    </div>
                </div>
                <div class="project-progress">
                    <div class="progress-info">
                        <span>Progress</span>
                        <span>40%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 40%"></div>
                    </div>
                </div>
                <div class="project-actions">
                    <button class="action-btn" onclick="viewProject('API Integration')">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="action-btn" onclick="editProject('API Integration')">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="action-btn" onclick="shareProject('API Integration')">
                        <i class="fas fa-share"></i>
                    </button>
                </div>
            </div>
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

    function viewProject(projectName) {
        showMessage(Opening ${projectName}..., 'info');
    }

    function editProject(projectName) {
        showMessage(Editing ${projectName}..., 'info');
    }

    function shareProject(projectName) {
        showMessage(Sharing ${projectName}..., 'info');
    }

    function showMessage(message, type) {
        const messageDiv = document.createElement('div');
        messageDiv.className = message message-${type};
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
</script>
@endsection
