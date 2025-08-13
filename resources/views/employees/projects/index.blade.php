@extends('layouts.employee')

@section('title', 'My Projects')

@section('content')
<div class="projects-container">
    <!-- Projects Header -->
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

    <!-- Projects Statistics -->
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

    <!-- Project Status Filter -->
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

    <!-- Search and Filter -->
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

    <!-- Projects Grid -->
    <div class="projects-content">
        <div class="projects-grid" id="projectsGrid">
            <!-- Active Projects -->
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

            <!-- Completed Projects -->
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

            <!-- Pending Projects -->
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

            <!-- Overdue Projects -->
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

    <!-- Create Project Modal -->
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

<style>
    .projects-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0;
    }

    .projects-header {
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

    .projects-header::before {
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

    .projects-stats {
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

    .project-status-filter {
        margin-bottom: 2rem;
    }

    .project-status-filter h2 {
        color: var(--text-primary);
        margin-bottom: 1rem;
        font-size: 1.5rem;
        font-weight: 600;
    }

    .status-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
    }

    .status-card {
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

    .status-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-xl);
        border-color: var(--redcode-primary);
    }

    .status-card.active {
        border-color: var(--redcode-primary);
        background: var(--redcode-primary-light);
    }

    .status-icon {
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

    .status-info h3 {
        margin: 0;
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--text-primary);
    }

    .status-info p {
        margin: 0.25rem 0 0 0;
        color: var(--text-secondary);
        font-size: 0.875rem;
    }

    .projects-controls {
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

    .projects-content {
        background: white;
        padding: 2rem;
        border-radius: 1rem;
        box-shadow: var(--shadow-lg);
        border: 1px solid var(--border-light);
    }

    .projects-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 1.5rem;
    }

    .project-card {
        background: var(--bg-primary);
        border: 2px solid var(--border-light);
        border-radius: 1rem;
        padding: 1.5rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        box-shadow: var(--shadow-sm);
    }

    .project-card::before {
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

    .project-card:hover::before {
        transform: scaleY(1);
    }

    .project-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
        border-color: var(--redcode-primary);
    }

    .project-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }

    .project-title h4 {
        margin: 0 0 0.5rem 0;
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--text-primary);
    }

    .priority-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .priority-badge.high {
        background: rgba(220, 38, 38, 0.1);
        color: #DC2626;
    }

    .priority-badge.medium {
        background: rgba(245, 158, 11, 0.1);
        color: #F59E0B;
    }

    .priority-badge.low {
        background: rgba(34, 197, 94, 0.1);
        color: #22C55E;
    }

    .project-status {
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .project-status.active {
        background: rgba(34, 197, 94, 0.1);
        color: #22C55E;
    }

    .project-status.completed {
        background: rgba(59, 130, 246, 0.1);
        color: #3B82F6;
    }

    .project-status.pending {
        background: rgba(245, 158, 11, 0.1);
        color: #F59E0B;
    }

    .project-status.overdue {
        background: rgba(220, 38, 38, 0.1);
        color: #DC2626;
    }

    .project-info p {
        margin: 0 0 1rem 0;
        color: var(--text-secondary);
        font-size: 0.875rem;
        line-height: 1.5;
    }

    .project-meta {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
        margin-bottom: 1rem;
    }

    .project-meta span {
        color: var(--text-light);
        font-size: 0.8rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .project-progress {
        margin-bottom: 1rem;
    }

    .progress-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--text-primary);
    }

    .progress-bar {
        height: 8px;
        background: var(--bg-secondary);
        border-radius: 4px;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        background: var(--gradient-hero);
        background-size: 400% 400%;
        animation: gradientShift 18s ease infinite;
        transition: width 0.3s ease;
    }

    .progress-fill.completed {
        background: linear-gradient(135deg, #22C55E, #16A34A);
    }

    .progress-fill.pending {
        background: linear-gradient(135deg, #F59E0B, #D97706);
    }

    .progress-fill.overdue {
        background: linear-gradient(135deg, #DC2626, #B91C1C);
    }

    .project-actions {
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
        max-width: 600px;
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

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
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

        .projects-stats {
            grid-template-columns: 1fr;
        }

        .status-grid {
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

        .projects-grid {
            grid-template-columns: 1fr;
        }

        .form-row {
            grid-template-columns: 1fr;
        }
    }
</style>

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
        
        // Show success message
        showMessage('Project created successfully!', 'success');
        closeCreateProjectModal();
        
        // Reset form
        document.getElementById('projectName').value = '';
        document.getElementById('projectDescription').value = '';
        document.getElementById('projectDeadline').value = '';
    }

    function viewProject(projectName) {
        showMessage(`Opening ${projectName}...`, 'info');
    }

    function editProject(projectName) {
        showMessage(`Editing ${projectName}...`, 'info');
    }

    function shareProject(projectName) {
        showMessage(`Sharing ${projectName}...`, 'info');
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
        filterProjects();
    });

    // Filter functionality
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

    // Status card click functionality
    document.querySelectorAll('.status-card').forEach(card => {
        card.addEventListener('click', function() {
            const status = this.dataset.status;
            document.getElementById('statusFilter').value = status;
            filterProjects();
            
            // Update active state
            document.querySelectorAll('.status-card').forEach(c => c.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // Close modal when clicking outside
    document.getElementById('createProjectModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeCreateProjectModal();
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
