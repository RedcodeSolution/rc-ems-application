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
                $deadline = $project->deadline ? \Carbon\Carbon::parse($project->deadline)->format('M d, Y') : 'No deadline';
                $role = $project->role ?? 'N/A';
                $teamName = $project->team_name ?? 'N/A';
                @endphp

                <div class="project-card" data-status="{{ strtolower($status) }}" data-priority="{{ $priority }}" data-team="{{ strtolower($teamName) }}">
                    <div class="project-header">
                        <div class="project-title">
                            <h4>{{ $project->project_name }}</h4>
                            <span class="priority-badge {{ $priority }}">{{ ucfirst($priority) }} Priority</span>
                        </div>
                        <div class="project-status {{ strtolower($status) }}">
                            @if($status == 'Active') <i class="fas fa-play"></i>
                            @elseif($status == 'Completed') <i class="fas fa-check"></i>
                            @elseif($status == 'On Hold') <i class="fas fa-pause"></i>
                            @else <i class="fas fa-exclamation"></i>
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
                            <div style="background: linear-gradient(90deg, var(--primary), var(--secondary)); height: 100%; width: {{ $progress }}%; transition: width 0.3s ease;">
                            </div>
                        </div>
                        <div style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 0.25rem;">
                            {{ $progress }}%
                        </div>
                    </div>



                    <div class="project-actions">
                        <button class="action-btn" onclick="viewProject('{{ $project->project_id }}')" title="View Project">
                            <i class="fas fa-eye"></i>
                        </button>

                        <button class="action-btn" onclick="shareProject('{{ $project->project_name }}')"><i class="fas fa-share"></i></button>
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
        <!-- View Project Modal -->
        <div id="viewProjectModal" class="modal" style="display: none;">
            <div class="modal-content">
                <div class="modal-header">
                    <h3><i class="fas fa-eye"></i> Project Details</h3>
                    <button class="close" onclick="closeViewProjectModal()">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Project Name</label>
                        <div id="view_project_name"></div>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <div id="view_description"></div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Team</label>
                            <div id="view_team_name"></div>
                        </div>
                        <div class="form-group">
                            <label>Role</label>
                            <div id="view_role"></div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Deadline</label>
                            <div id="view_deadline"></div>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <div id="view_status"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Progress</label>
                        <div class="progress-section">
                            <div class="progress-info">
                                <span>Completion</span>
                                <span id="view_progress_value">0%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" id="view_progress_fill" style="width: 0%;"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" onclick="closeViewProjectModal()">Close</button>
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



        let currentProjectId = null;

        function viewProject(projectId) {
            console.log("Clicked Project ID:", projectId);
            fetch(`/employee/projects/${projectId}`)
                .then(res => {
                    if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
                    return res.json();
                })
                .then(data => {
                    if (!data.project) {
                        alert('Project not found');
                        return;
                    }

                    const project = data.project;

                    document.getElementById('view_project_name').textContent = project.project_name || 'N/A';
                    document.getElementById('view_description').textContent = project.description || 'No description';
                    document.getElementById('view_team_name').textContent = project.team.team_name || 'N/A';
                    document.getElementById('view_role').textContent = project.role || 'N/A';
                    document.getElementById('view_deadline').textContent = project.deadline || 'N/A';
                    document.getElementById('view_status').textContent = project.status || 'N/A';
                    document.getElementById('view_progress_value').textContent = project.progress + '%';
                    document.getElementById('view_progress_fill').style.width = project.progress + '%';

                    // Show modal
                    const modal = document.getElementById('viewProjectModal');
                    modal.style.display = 'block';
                    document.body.style.overflow = 'hidden';
                })
                .catch(err => {
                    console.error('Error fetching project:', err);
                    alert('Error fetching project. Check console for details.');
                });
        }

        function closeViewModal() {
            const modal = document.getElementById('viewProjectModal');
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }

        document.addEventListener('click', function(e) {
            const modals = document.getElementById('viewProjectModal');
            modals.forEach(modalId => {
                const modal = document.getElementById(modalId);
                if (modal && e.target === modal) {
                    modal.classList.remove('show');
                    document.body.style.overflow = 'auto';
                }
            });
        });

        // function shareProject(projectName) {
        //     showMessage(Sharing $ {
        //         projectName
        //     }..., 'info');
        // }
        

        function showMessage(message, type) {
            const messageDiv = document.createElement('div');
            messageDiv.className = message message - $ {
                type
            };
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
