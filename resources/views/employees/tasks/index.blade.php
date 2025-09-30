@extends('layouts.employee')

@section('title', 'My Tasks')
<link rel="stylesheet" href="{{ asset('css/Employee/myTasks.css') }}">
@section('content')
<div class="tasks-container">
    <!-- Tasks Header -->
    <div class="tasks-header">
        <div class="header-content">
            <div class="header-info">
                <h1><i class="fas fa-tasks"></i> My Tasks</h1>
                <p>Manage and track your daily tasks and assignments</p>
            </div>
            <div class="header-actions">
                <button class="btn btn-primary" onclick="openCreateTaskModal()">
                    <i class="fas fa-plus"></i>
                    Add Task
                </button>
                <button class="btn btn-secondary" onclick="toggleViewMode()">
                    <i class="fas fa-list" id="viewModeIcon"></i>
                    <span id="viewModeText">List View</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Tasks Statistics -->
    <div class="tasks-stats">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-list-ul"></i>
            </div>
            <div class="stat-info">
                <h3>28</h3>
                <p>Total Tasks</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-info">
                <h3>15</h3>
                <p>Completed</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-info">
                <h3>8</h3>
                <p>In Progress</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="stat-info">
                <h3>5</h3>
                <p>Overdue</p>
            </div>
        </div>
    </div>

    <!-- Task Status Filter -->
    <div class="task-status-filter">
        <h2>Task Status</h2>
        <div class="status-grid">
            <div class="status-card" data-status="todo">
                <div class="status-icon">
                    <i class="fas fa-hourglass-start"></i>
                </div>
                <div class="status-info">
                    <h3>To Do</h3>
                    <p>5 tasks</p>
                </div>
            </div>
            <div class="status-card" data-status="in_progress">
                <div class="status-icon">
                    <i class="fas fa-spinner"></i>
                </div>
                <div class="status-info">
                    <h3>In Progress</h3>
                    <p>8 tasks</p>
                </div>
            </div>
            <div class="status-card" data-status="completed">
                <div class="status-icon">
                    <i class="fas fa-check"></i>
                </div>
                <div class="status-info">
                    <h3>Completed</h3>
                    <p>15 tasks</p>
                </div>
            </div>
            <div class="status-card" data-status="overdue">
                <div class="status-icon">
                    <i class="fas fa-exclamation"></i>
                </div>
                <div class="status-info">
                    <h3>Overdue</h3>
                    <p>5 tasks</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="tasks-controls">
        <div class="search-section">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Search tasks..." id="searchInput">
            </div>
            <div class="filter-section">
                <select id="statusFilter" class="filter-select">
                    <option value="">All Status</option>
                    <option value="todo">To Do</option>
                    <option value="in_progress">In Progress</option>
                    <option value="completed">Completed</option>
                    <option value="overdue">Overdue</option>
                </select>
                <select id="priorityFilter" class="filter-select">
                    <option value="">All Priority</option>
                    <option value="high">High Priority</option>
                    <option value="medium">Medium Priority</option>
                    <option value="low">Low Priority</option>
                </select>
                <select id="projectFilter" class="filter-select">
                    <option value="">All Projects</option>
                    <option value="ecommerce">E-Commerce Platform</option>
                    <option value="mobile">Mobile App</option>
                    <option value="api">API Integration</option>
                    <option value="qa">Quality Assurance</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Tasks Board -->
    <div class="tasks-content">
        <div class="tasks-board" id="tasksBoard">
            <!-- Today's Tasks Section -->
            <div class="tasks-section">
                <div class="section-header">
                    <h3><i class="fas fa-calendar-day"></i> Today's Tasks</h3>
                    <span class="task-count">3 tasks</span>
                </div>
                <div class="tasks-list">
                    <!-- High Priority Task -->
                    <div class="task-card urgent" data-status="in_progress" data-priority="high" data-project="ecommerce">
                        <div class="task-header">
                            <div class="task-priority high">
                                <i class="fas fa-exclamation-circle"></i>
                                High Priority
                            </div>
                            <div class="task-status in_progress">
                                <i class="fas fa-spinner"></i>
                                In Progress
                            </div>
                        </div>
                        <div class="task-content">
                            <h4>Fix Payment Gateway Bug</h4>
                            <p>Resolve critical issue with payment processing that's affecting customer checkout flow.</p>
                            <div class="task-meta">
                                <span><i class="fas fa-project-diagram"></i> E-Commerce Platform</span>
                                <span><i class="fas fa-clock"></i> Due: Today, 5:00 PM</span>
                                <span><i class="fas fa-user"></i> Assigned by: Sarah Martinez</span>
                            </div>
                        </div>
                        <div class="task-progress">
                            <div class="progress-info">
                                <span>Progress</span>
                                <span>60%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 60%"></div>
                            </div>
                        </div>
                        <div class="task-actions">
                            <button class="action-btn" onclick="viewTask('payment-bug')">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="action-btn" onclick="updateTaskStatus('payment-bug', 'completed')">
                                <i class="fas fa-check"></i>
                            </button>
                            <button class="action-btn" onclick="editTask('payment-bug')">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="action-btn" onclick="addComment('payment-bug')">
                                <i class="fas fa-comment"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Medium Priority Task -->
                    <div class="task-card" data-status="todo" data-priority="medium" data-project="mobile">
                        <div class="task-header">
                            <div class="task-priority medium">
                                <i class="fas fa-minus-circle"></i>
                                Medium Priority
                            </div>
                            <div class="task-status todo">
                                <i class="fas fa-hourglass-start"></i>
                                To Do
                            </div>
                        </div>
                        <div class="task-content">
                            <h4>Design Mobile Login Screen</h4>
                            <p>Create wireframes and mockups for the mobile app login interface with modern design patterns.</p>
                            <div class="task-meta">
                                <span><i class="fas fa-project-diagram"></i> Mobile App</span>
                                <span><i class="fas fa-clock"></i> Due: Today, 6:00 PM</span>
                                <span><i class="fas fa-user"></i> Assigned by: Mike Johnson</span>
                            </div>
                        </div>
                        <div class="task-progress">
                            <div class="progress-info">
                                <span>Progress</span>
                                <span>0%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 0%"></div>
                            </div>
                        </div>
                        <div class="task-actions">
                            <button class="action-btn" onclick="viewTask('mobile-login')">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="action-btn" onclick="updateTaskStatus('mobile-login', 'in_progress')">
                                <i class="fas fa-play"></i>
                            </button>
                            <button class="action-btn" onclick="editTask('mobile-login')">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="action-btn" onclick="addComment('mobile-login')">
                                <i class="fas fa-comment"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Low Priority Task -->
                    <div class="task-card" data-status="completed" data-priority="low" data-project="api">
                        <div class="task-header">
                            <div class="task-priority low">
                                <i class="fas fa-check-circle"></i>
                                Low Priority
                            </div>
                            <div class="task-status completed">
                                <i class="fas fa-check"></i>
                                Completed
                            </div>
                        </div>
                        <div class="task-content">
                            <h4>Update API Documentation</h4>
                            <p>Review and update the API documentation with recent changes and new endpoints.</p>
                            <div class="task-meta">
                                <span><i class="fas fa-project-diagram"></i> API Integration</span>
                                <span><i class="fas fa-clock"></i> Completed: Today, 2:00 PM</span>
                                <span><i class="fas fa-user"></i> Assigned by: David Chen</span>
                            </div>
                        </div>
                        <div class="task-progress">
                            <div class="progress-info">
                                <span>Progress</span>
                                <span>100%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill completed" style="width: 100%"></div>
                            </div>
                        </div>
                        <div class="task-actions">
                            <button class="action-btn" onclick="viewTask('api-docs')">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="action-btn" onclick="editTask('api-docs')">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="action-btn" onclick="addComment('api-docs')">
                                <i class="fas fa-comment"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Upcoming Tasks Section -->
            <div class="tasks-section">
                <div class="section-header">
                    <h3><i class="fas fa-calendar-week"></i> Upcoming Tasks</h3>
                    <span class="task-count">4 tasks</span>
                </div>
                <div class="tasks-list">
                    <!-- Overdue Task -->
                    <div class="task-card overdue" data-status="overdue" data-priority="high" data-project="qa">
                        <div class="task-header">
                            <div class="task-priority high">
                                <i class="fas fa-exclamation-circle"></i>
                                High Priority
                            </div>
                            <div class="task-status overdue">
                                <i class="fas fa-exclamation-triangle"></i>
                                Overdue
                            </div>
                        </div>
                        <div class="task-content">
                            <h4>Complete Security Testing</h4>
                            <p>Perform comprehensive security testing on the main application modules.</p>
                            <div class="task-meta">
                                <span><i class="fas fa-project-diagram"></i> Quality Assurance</span>
                                <span><i class="fas fa-clock"></i> Due: Yesterday</span>
                                <span><i class="fas fa-user"></i> Assigned by: Lisa Anderson</span>
                            </div>
                        </div>
                        <div class="task-progress">
                            <div class="progress-info">
                                <span>Progress</span>
                                <span>75%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill overdue" style="width: 75%"></div>
                            </div>
                        </div>
                        <div class="task-actions">
                            <button class="action-btn" onclick="updateTaskStatus('security-test', 'completed')">
                                <i class="fas fa-check"></i>
                            </button>
                            <button class="action-btn" onclick="editTask('security-test')">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="action-btn" onclick="addComment('security-test')">
                                <i class="fas fa-comment"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Future Tasks -->
                    <div class="task-card" data-status="todo" data-priority="medium" data-project="ecommerce">
                        <div class="task-header">
                            <div class="task-priority medium">
                                <i class="fas fa-minus-circle"></i>
                                Medium Priority
                            </div>
                            <div class="task-status todo">
                                <i class="fas fa-hourglass-start"></i>
                                To Do
                            </div>
                        </div>
                        <div class="task-content">
                            <h4>Implement Shopping Cart Feature</h4>
                            <p>Develop the shopping cart functionality with add, remove, and update quantity options.</p>
                            <div class="task-meta">
                                <span><i class="fas fa-project-diagram"></i> E-Commerce Platform</span>
                                <span><i class="fas fa-clock"></i> Due: Tomorrow</span>
                                <span><i class="fas fa-user"></i> Assigned by: Sarah Martinez</span>
                            </div>
                        </div>
                        <div class="task-progress">
                            <div class="progress-info">
                                <span>Progress</span>
                                <span>0%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 0%"></div>
                            </div>
                        </div>
                        <div class="task-actions">
                            <button class="action-btn" onclick="updateTaskStatus('shopping-cart', 'in_progress')">
                                <i class="fas fa-play"></i>
                            </button>
                            <button class="action-btn" onclick="editTask('shopping-cart')">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="action-btn" onclick="addComment('shopping-cart')">
                                <i class="fas fa-comment"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Task Modal -->
    <div id="createTaskModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-plus"></i> Create New Task</h3>
                <button class="close-btn" onclick="closeCreateTaskModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="taskTitle">Task Title</label>
                    <input type="text" id="taskTitle" class="form-control" placeholder="Enter task title...">
                </div>
                <div class="form-group">
                    <label for="taskDescription">Description</label>
                    <textarea id="taskDescription" class="form-control" rows="3" placeholder="Enter task description..."></textarea>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="taskPriority">Priority</label>
                        <select id="taskPriority" class="form-control">
                            <option value="high">High Priority</option>
                            <option value="medium">Medium Priority</option>
                            <option value="low">Low Priority</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="taskProject">Project</label>
                        <select id="taskProject" class="form-control">
                            <option value="ecommerce">E-Commerce Platform</option>
                            <option value="mobile">Mobile App</option>
                            <option value="api">API Integration</option>
                            <option value="qa">Quality Assurance</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="taskDueDate">Due Date</label>
                        <input type="datetime-local" id="taskDueDate" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="taskAssignee">Assigned By</label>
                        <input type="text" id="taskAssignee" class="form-control" placeholder="Manager name...">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeCreateTaskModal()">Cancel</button>
                <button class="btn btn-primary" onclick="createTask()">
                    <i class="fas fa-plus"></i>
                    Create Task
                </button>
            </div>
        </div>
    </div>

    <!-- Task Comment Modal -->
    <div id="commentModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-comment"></i> Add Comment</h3>
                <button class="close-btn" onclick="closeCommentModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="commentText">Comment</label>
                    <textarea id="commentText" class="form-control" rows="4" placeholder="Enter your comment..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeCommentModal()">Cancel</button>
                <button class="btn btn-primary" onclick="addTaskComment()">
                    <i class="fas fa-comment"></i>
                    Add Comment
                </button>
            </div>
        </div>
    </div>

    <!-- Edit Task Modal -->
    <div id="editTaskModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-edit"></i> Edit Task</h3>
                <button class="close-btn" onclick="closeEditTaskModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="editTaskTitle">Task Title</label>
                    <input type="text" id="editTaskTitle" class="form-control" placeholder="Enter task title...">
                </div>
                <div class="form-group">
                    <label for="editTaskDescription">Description</label>
                    <textarea id="editTaskDescription" class="form-control" rows="3" placeholder="Enter task description..."></textarea>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="editTaskPriority">Priority</label>
                        <select id="editTaskPriority" class="form-control">
                            <option value="high">High Priority</option>
                            <option value="medium">Medium Priority</option>
                            <option value="low">Low Priority</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editTaskStatus">Status</label>
                        <select id="editTaskStatus" class="form-control">
                            <option value="todo">To Do</option>
                            <option value="in_progress">In Progress</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="editTaskProject">Project</label>
                        <select id="editTaskProject" class="form-control">
                            <option value="ecommerce">E-Commerce Platform</option>
                            <option value="mobile">Mobile App</option>
                            <option value="api">API Integration</option>
                            <option value="qa">Quality Assurance</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editTaskProgress">Progress (%)</label>
                        <input type="range" id="editTaskProgress" class="form-control" min="0" max="100" value="0">
                        <span id="progressValue">0%</span>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="editTaskDueDate">Due Date</label>
                        <input type="datetime-local" id="editTaskDueDate" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="editTaskAssignee">Assigned By</label>
                        <input type="text" id="editTaskAssignee" class="form-control" placeholder="Manager name..." readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label for="editTaskNotes">Personal Notes</label>
                    <textarea id="editTaskNotes" class="form-control" rows="2" placeholder="Add personal notes or updates..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeEditTaskModal()">Cancel</button>
                <button class="btn btn-primary" onclick="updateTask()">
                    <i class="fas fa-save"></i>
                    Update Task
                </button>
            </div>
        </div>
    </div>
</div>

<!-- View Task Details Modal -->
<div id="viewTaskModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="viewTaskTitle">Task Details</h3>
            <button class="close-btn" onclick="closeViewTaskModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <div id="viewTaskContent">
                <!-- Dynamic task details will be loaded here -->
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeViewTaskModal()">Close</button>
            <button class="btn btn-primary" onclick="editTaskFromView()">
                <i class="fas fa-edit"></i>
                Edit Task
            </button>
        </div>
    </div>
</div>


<script>
    let currentViewMode = 'grid';
    let currentTaskId = null;

    function toggleViewMode() {
        const board = document.getElementById('tasksBoard');
        const icon = document.getElementById('viewModeIcon');
        const text = document.getElementById('viewModeText');

        if (currentViewMode === 'grid') {
            // Switch to list view
            board.classList.remove('grid-view');
            board.classList.add('list-view');
            icon.className = 'fas fa-th-large';
            text.textContent = 'Grid View';
            currentViewMode = 'list';
        } else {
            // Switch to grid view
            board.classList.remove('list-view');
            board.classList.add('grid-view');
            icon.className = 'fas fa-list';
            text.textContent = 'List View';
            currentViewMode = 'grid';
        }
    }

    // Initialize with grid view
    document.addEventListener('DOMContentLoaded', function() {
        const board = document.getElementById('tasksBoard');
        board.classList.add('grid-view');
    });

    function openCreateTaskModal() {
        document.getElementById('createTaskModal').classList.add('active');
    }

    function closeCreateTaskModal() {
        document.getElementById('createTaskModal').classList.remove('active');
    }

    function openCommentModal(taskId) {
        currentTaskId = taskId;
        document.getElementById('commentModal').classList.add('active');
    }

    function closeCommentModal() {
        document.getElementById('commentModal').classList.remove('active');
        currentTaskId = null;
    }

    function createTask() {
        const title = document.getElementById('taskTitle').value;
        const description = document.getElementById('taskDescription').value;
        const priority = document.getElementById('taskPriority').value;
        const project = document.getElementById('taskProject').value;
        const dueDate = document.getElementById('taskDueDate').value;
        const assignee = document.getElementById('taskAssignee').value;

        if (!title || !description || !dueDate) {
            alert('Please fill in all required fields.');
            return;
        }

        // Show success message
        showMessage('Task created successfully!', 'success');
        closeCreateTaskModal();

        // Reset form
        document.getElementById('taskTitle').value = '';
        document.getElementById('taskDescription').value = '';
        document.getElementById('taskDueDate').value = '';
        document.getElementById('taskAssignee').value = '';
    }

    function updateTaskStatus(taskId, newStatus) {
        showMessage(`Task status updated to ${newStatus.replace('_', ' ')}!`, 'success');
    }

    function editTask(taskId) {
        // Sample task data - in a real app, this would come from your backend
        const taskData = getTaskData(taskId);

        // Populate the edit form with current task data
        document.getElementById('editTaskTitle').value = taskData.title;
        document.getElementById('editTaskDescription').value = taskData.description;
        document.getElementById('editTaskPriority').value = taskData.priority;
        document.getElementById('editTaskStatus').value = taskData.status;
        document.getElementById('editTaskProject').value = taskData.project;
        document.getElementById('editTaskProgress').value = taskData.progress;
        document.getElementById('progressValue').textContent = taskData.progress + '%';
        document.getElementById('editTaskDueDate').value = taskData.dueDate;
        document.getElementById('editTaskAssignee').value = taskData.assignee;
        document.getElementById('editTaskNotes').value = taskData.notes || '';

        // Store the current task ID for updating
        currentTaskId = taskId;

        // Show the edit modal
        document.getElementById('editTaskModal').classList.add('active');
    }

    function closeEditTaskModal() {
        document.getElementById('editTaskModal').classList.remove('active');
        currentTaskId = null;
    }

    function updateTask() {
        if (!currentTaskId) return;

        const title = document.getElementById('editTaskTitle').value;
        const description = document.getElementById('editTaskDescription').value;
        const priority = document.getElementById('editTaskPriority').value;
        const status = document.getElementById('editTaskStatus').value;
        const project = document.getElementById('editTaskProject').value;
        const progress = document.getElementById('editTaskProgress').value;
        const dueDate = document.getElementById('editTaskDueDate').value;
        const notes = document.getElementById('editTaskNotes').value;

        if (!title || !description || !dueDate) {
            alert('Please fill in all required fields.');
            return;
        }

        // Here you would typically send the updated data to your backend
        // For now, we'll just show a success message
        showMessage(`Task "${title}" updated successfully!`, 'success');

        // Update the task card in the DOM (simplified example)
        updateTaskCard(currentTaskId, {
            title,
            description,
            priority,
            status,
            project,
            progress,
            dueDate,
            notes
        });

        closeEditTaskModal();
    }

    function getTaskData(taskId) {
        // Sample task data - in a real app, this would come from your backend
        const taskDatabase = {
            'payment-bug': {
                title: 'Fix Payment Gateway Bug',
                description: 'Resolve critical issue with payment processing that\'s affecting customer checkout flow.',
                priority: 'high',
                status: 'in_progress',
                project: 'ecommerce',
                progress: 60,
                dueDate: '2024-01-15T17:00',
                assignee: 'Sarah Martinez',
                notes: 'Working on the Stripe integration issue. Need to test with different payment methods.'
            },
            'mobile-login': {
                title: 'Design Mobile Login Screen',
                description: 'Create wireframes and mockups for the mobile app login interface with modern design patterns.',
                priority: 'medium',
                status: 'todo',
                project: 'mobile',
                progress: 0,
                dueDate: '2024-01-15T18:00',
                assignee: 'Mike Johnson',
                notes: 'Research material design patterns for mobile authentication.'
            },
            'api-docs': {
                title: 'Update API Documentation',
                description: 'Review and update the API documentation with recent changes and new endpoints.',
                priority: 'low',
                status: 'completed',
                project: 'api',
                progress: 100,
                dueDate: '2024-01-15T14:00',
                assignee: 'David Chen',
                notes: 'Added new endpoint documentation and updated examples.'
            },
            'security-test': {
                title: 'Complete Security Testing',
                description: 'Perform comprehensive security testing on the main application modules.',
                priority: 'high',
                status: 'overdue',
                project: 'qa',
                progress: 75,
                dueDate: '2024-01-14T17:00',
                assignee: 'Lisa Anderson',
                notes: 'Found 3 vulnerabilities, 2 fixed, 1 remaining in authentication module.'
            },
            'shopping-cart': {
                title: 'Implement Shopping Cart Feature',
                description: 'Develop the shopping cart functionality with add, remove, and update quantity options.',
                priority: 'medium',
                status: 'todo',
                project: 'ecommerce',
                progress: 0,
                dueDate: '2024-01-16T17:00',
                assignee: 'Sarah Martinez',
                notes: 'Need to design database schema for cart items.'
            }
        };

        return taskDatabase[taskId] || {
            title: 'Unknown Task',
            description: 'Task not found',
            priority: 'medium',
            status: 'todo',
            project: 'ecommerce',
            progress: 0,
            dueDate: '',
            assignee: 'Unknown',
            notes: ''
        };
    }

    function updateTaskCard(taskId, taskData) {
        // This is a simplified example of updating the task card in the DOM
        // In a real application, you would refresh the data from the server
        const taskCard = document.querySelector(`[data-task-id="${taskId}"]`);
        if (taskCard) {
            const titleElement = taskCard.querySelector('h4');
            const descriptionElement = taskCard.querySelector('p');
            const progressElement = taskCard.querySelector('.progress-fill');
            const progressText = taskCard.querySelector('.progress-info span:last-child');

            if (titleElement) titleElement.textContent = taskData.title;
            if (descriptionElement) descriptionElement.textContent = taskData.description;
            if (progressElement) progressElement.style.width = taskData.progress + '%';
            if (progressText) progressText.textContent = taskData.progress + '%';
        }
    }

    function viewTask(taskId) {
        const modal = document.getElementById('viewTaskModal');
        const modalTitle = document.getElementById('viewTaskTitle');
        const modalContent = document.getElementById('viewTaskContent');

        // Get task data (in a real app, this would be an API call)
        const taskData = getTaskData(taskId);

        // Set modal title
        modalTitle.textContent = taskData.title;

        // Create detailed content
        modalContent.innerHTML = `
            <div class="task-view">
                <!-- Task Header with Status and Priority -->
                <div class="task-view-header">
                    <div class="task-badges">
                        <div class="priority-badge ${taskData.priority}">
                            <i class="fas fa-flag"></i>
                            <span>${taskData.priority.charAt(0).toUpperCase() + taskData.priority.slice(1)} Priority</span>
                        </div>
                        <div class="status-badge ${taskData.status}">
                            <i class="fas fa-${getStatusIcon(taskData.status)}"></i>
                            <span>${formatStatus(taskData.status)}</span>
                        </div>
                    </div>
                </div>

                <!-- Task Meta Information -->
                <div class="task-meta-info">
                    <div class="meta-row">
                        <div class="meta-item">
                            <i class="fas fa-project-diagram"></i>
                            <strong>Project:</strong>
                            <span>${taskData.project}</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-user"></i>
                            <strong>Assigned by:</strong>
                            <span>${taskData.assignedBy}</span>
                        </div>
                    </div>
                    <div class="meta-row">
                        <div class="meta-item">
                            <i class="fas fa-calendar"></i>
                            <strong>Due Date:</strong>
                            <span>${taskData.dueDate}</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-clock"></i>
                            <strong>Created:</strong>
                            <span>${taskData.createdDate}</span>
                        </div>
                    </div>
                </div>

                <!-- Task Description -->
                <div class="task-description-view">
                    <h4>Description</h4>
                    <div class="description-text">
                        ${taskData.description}
                    </div>
                </div>

                <!-- Task Progress -->
                <div class="task-progress-view">
                    <h4>Progress</h4>
                    <div class="progress-container">
                        <div class="progress-info">
                            <span>Completion</span>
                            <span>${taskData.progress}%</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: ${taskData.progress}%"></div>
                        </div>
                    </div>
                </div>

                <!-- Additional Details -->
                ${taskData.additionalDetails ? `
                <div class="task-additional">
                    <h4>Additional Information</h4>
                    <div class="additional-content">
                        ${taskData.additionalDetails}
                    </div>
                </div>
                ` : ''}

                <!-- Task Comments -->
                ${taskData.comments && taskData.comments.length > 0 ? `
                <div class="task-comments-view">
                    <h4>Recent Comments</h4>
                    <div class="comments-list">
                        ${taskData.comments.map(comment => `
                            <div class="comment-item">
                                <div class="comment-header">
                                    <strong>${comment.author}</strong>
                                    <span class="comment-date">${comment.date}</span>
                                </div>
                                <div class="comment-text">${comment.text}</div>
                            </div>
                        `).join('')}
                    </div>
                </div>
                ` : ''}
            </div>
        `;

        // Show modal
        modal.classList.add('active');

        // Store current task ID for actions
        modal.dataset.currentTaskId = taskId;

        showMessage('Loading task details...', 'info');
    }

    // Get task data (mock function - replace with actual API call)
    function getTaskData(taskId) {
        const taskDatabase = {
            'payment-bug': {
                title: 'Fix Payment Gateway Bug',
                priority: 'high',
                status: 'in_progress',
                project: 'E-Commerce Platform',
                assignedBy: 'Sarah Martinez',
                dueDate: 'Today, 5:00 PM',
                createdDate: 'Nov 25, 2024',
                progress: 60,
                description: `
                    <p>Critical issue affecting the payment processing system that's preventing customers from completing their checkout flow. This bug is causing a significant impact on sales and customer satisfaction.</p>

                    <h5>Problem Details:</h5>
                    <ul>
                        <li>Payment gateway timeout errors occurring randomly</li>
                        <li>Credit card validation failing for certain card types</li>
                        <li>Transaction rollback not working properly</li>
                        <li>Error logging insufficient for debugging</li>
                    </ul>

                    <h5>Expected Solution:</h5>
                    <p>Implement proper error handling, fix timeout issues, and improve transaction reliability. Add comprehensive logging for better debugging capabilities.</p>
                `,
                additionalDetails: `
                    <p><strong>Technical Requirements:</strong></p>
                    <ul>
                        <li>Review payment gateway API integration</li>
                        <li>Test with different payment methods</li>
                        <li>Implement proper error handling</li>
                        <li>Add transaction monitoring</li>
                    </ul>
                `,
                comments: [
                    {
                        author: 'John Doe',
                        date: '2 hours ago',
                        text: 'I\'ve identified the root cause. Working on the fix now.'
                    },
                    {
                        author: 'Sarah Martinez',
                        date: '4 hours ago',
                        text: 'Please prioritize this as it\'s affecting our sales metrics.'
                    }
                ]
            },
            'api-docs': {
                title: 'Update API Documentation',
                priority: 'medium',
                status: 'completed',
                project: 'API Integration',
                assignedBy: 'Tech Lead',
                dueDate: 'Dec 1, 2024',
                createdDate: 'Nov 20, 2024',
                progress: 100,
                description: `
                    <p>Comprehensive update of API documentation to reflect recent changes and new endpoints. This includes updating examples, adding new authentication methods, and improving overall documentation structure.</p>

                    <h5>Scope of Work:</h5>
                    <ul>
                        <li>Update all endpoint descriptions</li>
                        <li>Add new authentication examples</li>
                        <li>Include error response documentation</li>
                        <li>Add code samples in multiple languages</li>
                    </ul>
                `,
                comments: [
                    {
                        author: 'Jane Smith',
                        date: '1 day ago',
                        text: 'Documentation looks great! All endpoints are properly documented.'
                    }
                ]
            }
        };

        // Return data for the specific task or default task
        return taskDatabase[taskId] || {
            title: 'Task Details',
            priority: 'medium',
            status: 'todo',
            project: 'General',
            assignedBy: 'Manager',
            dueDate: 'TBD',
            createdDate: 'Today',
            progress: 0,
            description: '<p>Task description not available.</p>',
            comments: []
        };
    }

    // Helper functions
    function getStatusIcon(status) {
        const icons = {
            'todo': 'hourglass-start',
            'in_progress': 'spinner',
            'completed': 'check-circle',
            'overdue': 'exclamation-triangle'
        };
        return icons[status] || 'question-circle';
    }

    function formatStatus(status) {
        const statusMap = {
            'todo': 'To Do',
            'in_progress': 'In Progress',
            'completed': 'Completed',
            'overdue': 'Overdue'
        };
        return statusMap[status] || status;
    }

    function closeViewTaskModal() {
        document.getElementById('viewTaskModal').classList.remove('active');
    }

    function editTaskFromView() {
        const modal = document.getElementById('viewTaskModal');
        const taskId = modal.dataset.currentTaskId;
        closeViewTaskModal();
        editTask(taskId);
    }

    function addComment(taskId) {
        openCommentModal(taskId);
    }

    function addTaskComment() {
        const comment = document.getElementById('commentText').value;

        if (!comment.trim()) {
            alert('Please enter a comment.');
            return;
        }

        showMessage('Comment added successfully!', 'success');
        closeCommentModal();

        // Reset comment field
        document.getElementById('commentText').value = '';
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
        filterTasks();
    });

    // Filter functionality
    document.getElementById('statusFilter').addEventListener('change', filterTasks);
    document.getElementById('priorityFilter').addEventListener('change', filterTasks);
    document.getElementById('projectFilter').addEventListener('change', filterTasks);

    function filterTasks() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const statusFilter = document.getElementById('statusFilter').value;
        const priorityFilter = document.getElementById('priorityFilter').value;
        const projectFilter = document.getElementById('projectFilter').value;
        const tasks = document.querySelectorAll('.task-card');

        tasks.forEach(task => {
            const title = task.querySelector('h4').textContent.toLowerCase();
            const description = task.querySelector('p').textContent.toLowerCase();
            const status = task.dataset.status;
            const priority = task.dataset.priority;
            const project = task.dataset.project;

            const matchesSearch = title.includes(searchTerm) || description.includes(searchTerm);
            const matchesStatus = !statusFilter || status === statusFilter;
            const matchesPriority = !priorityFilter || priority === priorityFilter;
            const matchesProject = !projectFilter || project === projectFilter;

            if (matchesSearch && matchesStatus && matchesPriority && matchesProject) {
                task.style.display = 'block';
            } else {
                task.style.display = 'none';
            }
        });
    }

    // Status card click functionality
    document.querySelectorAll('.status-card').forEach(card => {
        card.addEventListener('click', function() {
            const status = this.dataset.status;
            document.getElementById('statusFilter').value = status;
            filterTasks();

            // Update active state
            document.querySelectorAll('.status-card').forEach(c => c.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // Close modal when clicking outside
    document.getElementById('createTaskModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeCreateTaskModal();
        }
    });

    document.getElementById('commentModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeCommentModal();
        }
    });

    document.getElementById('editTaskModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeEditTaskModal();
        }
    });

    document.getElementById('viewTaskModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeViewTaskModal();
        }
    });

    // Progress slider event listener
    document.getElementById('editTaskProgress').addEventListener('input', function(e) {
        document.getElementById('progressValue').textContent = e.target.value + '%';
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
    `;
    document.head.appendChild(style);
</script>
@endsection
