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
                    <h3>{{ $totalTasks }}</h3>
                    <p>Total Tasks</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ $completed }}</h3>
                    <p>Completed</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ $inProgress }}</h3>
                    <p>In Progress</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ $overdue }}</h3>
                    <p>Overdue</p>
                </div>
            </div>
        </div>

        <!-- Task Status Filter -->
        <div class="task-status-filter">
            <h2>Task Status</h2>
            <div class="status-grid">
                <div class="status-card" data-status="todo">
                    <div class="status-icon"><i class="fas fa-hourglass-start"></i></div>
                    <div class="status-info">
                        <h3>To Do</h3>
                        <p id="todoCount">{{ $todo }} tasks</p>
                    </div>
                </div>
                <div class="status-card" data-status="in_progress">
                    <div class="status-icon"><i class="fas fa-spinner"></i></div>
                    <div class="status-info">
                        <h3>In Progress</h3>
                        <p id="inProgressCount">{{ $inProgress }} tasks</p>
                    </div>
                </div>
                <div class="status-card" data-status="completed">
                    <div class="status-icon"><i class="fas fa-check"></i></div>
                    <div class="status-info">
                        <h3>Completed</h3>
                        <p id="completedCount">{{ $completed }} tasks</p>
                    </div>
                </div>
                <div class="status-card" data-status="overdue">
                    <div class="status-icon"><i class="fas fa-exclamation"></i></div>
                    <div class="status-info">
                        <h3>Overdue</h3>
                        <p id="overdueCount">{{ $overdue }} tasks</p>
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
                        <option value="on_hold">On Hold</option>
                    </select>

                    <select id="priorityFilter" class="filter-select">
                        <option value="">All Priority</option>
                        <option value="high">High Priority</option>
                        <option value="medium">Medium Priority</option>
                        <option value="low">Low Priority</option>
                    </select>
                    <select id="projectFilter" class="filter-select">
                        <option value="">All Projects</option>
                        @foreach ($projects as $project)
                            <option value="{{ $project->project_id }}">
                                {{ $project->project_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="tasks-content">
            <div class="tasks-board" id="tasksBoard">

                <!-- Tasks Section -->
                <div class="tasks-section">
                    <div class="section-header">
                        <h3><i class="fas fa-calendar-day"></i> All Tasks</h3>
                        <span class="task-count">{{ $tasks->count() }} tasks</span>
                    </div>
                    <div class="tasks-list">
                        @forelse ($tasks as $task)
                            <div class="task-card 
                        @if ($task->priority === 'high') urgent 
                        @elseif($task->priority === 'medium') medium 
                        @else low @endif"
                                data-status="{{ $task->status ?? 'todo' }}" data-priority="{{ $task->priority }}"
                                data-project="{{ $task->project_id }}">
                                <div class="task-header">
                                    <div class="task-priority {{ $task->priority }}">
                                        @if ($task->priority === 'high')
                                            <i class="fas fa-exclamation-circle"></i> High Priority
                                        @elseif ($task->priority === 'medium')
                                            <i class="fas fa-minus-circle"></i> Medium Priority
                                        @else
                                            <i class="fas fa-check-circle"></i> Low Priority
                                        @endif
                                    </div>
                                    <div class="task-status {{ $task->status ?? 'todo' }}">
                                        <i class="fas fa-spinner"></i> {{ ucfirst($task->status ?? 'To Do') }}
                                    </div>
                                </div>

                                <div class="task-content">
                                    <h4>{{ $task->title }}</h4>
                                    <p>{{ $task->description }}</p>
                                    <div class="task-meta">
                                        <span><i class="fas fa-project-diagram"></i>
                                            {{ $task->project->name ?? $task->project_id }}</span>
                                        <span><i class="fas fa-clock"></i> Due:
                                            {{ \Carbon\Carbon::parse($task->due_date)->format('M d, Y h:i A') }}</span>
                                        <span><i class="fas fa-user"></i> Assigned by:
                                            {{ $task->assignedBy?->employee_name ?? 'N/A' }}</span>
                                    </div>
                                </div>

                                <div class="task-progress">
                                    <div class="progress-info">
                                        <span>Progress</span>
                                        <span>{{ $task->progress ?? 0 }}%</span>
                                    </div>
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width: {{ $task->progress ?? 0 }}%"></div>
                                    </div>
                                </div>
                                <div class="task-actions">
                                    <button class="action-btn" onclick="viewTask('{{ $task->task_id }}')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @if ($task->status !== 'completed')
                                        <button class="action-btn"
                                            onclick="updateTaskStatus('{{ $task->task_id }}', 'completed')">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    @endif
                                    <button class="action-btn" onclick="editTask('{{ $task->task_id }}')">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="action-btn" onclick="addComment('{{ $task->task_id }}')">
                                        <i class="fas fa-comment"></i>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <p>No tasks found for your projects.</p>
                        @endforelse
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
                                <option value="">All Project</option>
                                @foreach ($projects as $project)
                                    <option value="{{ $project->project_id }}">
                                        {{ $project->project_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="taskDueDate">Due Date</label>
                            <input type="datetime-local" id="taskDueDate" class="form-control">
                        </div>
                        {{-- <div class="form-group">
                            <label for="taskAssignee">Assigned By</label>
                            <input type="text" id="taskAssignee" class="form-control" placeholder="Manager name...">
                        </div> --}}
                    </div>

                    <div class="form-group">
                        <label for="taskNotes">Personal Notes</label>
                        <textarea id="taskNotes" class="form-control" rows="2" placeholder="Add personal notes or updates..."></textarea>
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
                                <option value="overdue">Overdue</option>
                                <option value="on_hold">On Hold</option>
                            </select>
                        </div>

                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="editTaskProject">Project</label>
                            <select id="editTaskProject" class="form-control">
                                @foreach ($projects as $project)
                                    <option value="{{ $project->project_id }}">
                                        {{ $project->project_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="editTaskProgress">Progress (%)</label>
                            <input type="range" id="editTaskProgress" class="form-control" min="0"
                                max="100" value="0">
                            <span id="progressValue">0%</span>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="editTaskDueDate">Due Date</label>
                            <input type="datetime-local" id="editTaskDueDate" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="editTaskAssignee">Created By</label>
                            <input type="text" id="editTaskAssignee" class="form-control"
                                placeholder="Manager name..." readonly>
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
            const title = document.getElementById('taskTitle').value.trim();
            const description = document.getElementById('taskDescription').value.trim();
            const priority = document.getElementById('taskPriority').value;
            const project_id = document.getElementById('taskProject').value;
            const due_date = document.getElementById('taskDueDate').value;
            const personal_notes = document.getElementById('taskNotes').value.trim();
            const assigned_by = document.getElementById('taskAssignee') ?
                document.getElementById('taskAssignee').value :
                null;

            if (!title || !due_date) {
                alert('Please fill in all required fields.');
                return;
            }

            fetch('/employees/tasks', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        title,
                        description,
                        priority,
                        project_id,
                        due_date,
                        assigned_by,
                        personal_notes
                    })
                })
                .then(res => {
                    if (!res.ok) throw new Error(`Server returned ${res.status}`);
                    return res.json();
                })
                .then(data => {
                    if (data.success) {
                        showMessage(data.message, 'success');
                        closeCreateTaskModal();
                        location.reload(); // optional refresh
                    } else {
                        showMessage(data.message || 'Something went wrong!', 'error');
                    }
                })
                .catch(err => {
                    console.error(err);
                    showMessage('Server error while creating task.', 'error');
                });
        }

        async function updateTaskStatus(taskId, newStatus) {
            try {
                const response = await fetch(`/employees/tasks/${taskId}/status`, {
                    method: "PATCH",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        status: newStatus
                    })
                });

                const result = await response.json();

                if (result.success) {
                    showMessage(`Task status updated to ${newStatus.replace('_', ' ')}!`, 'success');
                    // Optionally update UI
                    updateTaskCard(taskId, result.task);
                } else {
                    showMessage('Failed to update task status.', 'error');
                }
            } catch (error) {
                console.error(error);
                showMessage('An error occurred while updating task status.', 'error');
            }
        }


        function formatDateForInput(dateString) {
            if (!dateString) return '';
            const date = new Date(dateString);
            // pad numbers to two digits
            const pad = (n) => n.toString().padStart(2, '0');
            return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}T${pad(date.getHours())}:${pad(date.getMinutes())}`;
        }

        async function editTask(taskId) {
            // Wait for the backend response
            const taskData = await getTaskData(taskId);
            console.log(taskData); // Now you'll see the actual object

            // Populate the edit form with current task data
            document.getElementById('editTaskTitle').value = taskData.title || '';
            document.getElementById('editTaskDescription').value = taskData.description || '';
            document.getElementById('editTaskPriority').value = taskData.priority || 'low';
            document.getElementById('editTaskStatus').value = taskData.status || 'todo';
            document.getElementById('editTaskProject').value = taskData.project || '';
            document.getElementById('editTaskProgress').value = taskData.progress || 0;
            document.getElementById('progressValue').textContent = (taskData.progress || 0) + '%';
            document.getElementById('editTaskDueDate').value = formatDateForInput(taskData.dueDate);
            document.getElementById('editTaskAssignee').value = taskData.assignedBy || '';
            document.getElementById('editTaskNotes').value = taskData.personal_notes || '';

            const projectSelect = document.getElementById('editTaskProject');
            if (taskData.project_id) {
                projectSelect.value = taskData.project_id;
            } else {
                projectSelect.selectedIndex = 0; // fallback
            }

            currentTaskId = taskId;
            document.getElementById('editTaskModal').classList.add('active');
        }

        function closeEditTaskModal() {
            document.getElementById('editTaskModal').classList.remove('active');
            currentTaskId = null;
        }

        async function updateTask() {
            if (!currentTaskId) return;
            console.log(currentTaskId);

            document.getElementById('editTaskProgress').addEventListener('input', function() {
                document.getElementById('progressValue').innerText = this.value + '%';
            });

            const title = document.getElementById('editTaskTitle').value;
            const description = document.getElementById('editTaskDescription').value;
            const priority = document.getElementById('editTaskPriority').value;
            const status = document.getElementById('editTaskStatus').value;
            const project_id = document.getElementById('editTaskProject').value;
            const progress = document.getElementById('editTaskProgress').value;
            const due_date = document.getElementById('editTaskDueDate').value;
            const personal_notes = document.getElementById('editTaskNotes').value;
            console.log(title + description + priority + status + project_id + progress + due_date + personal_notes);

            if (!title || !description || !due_date) {
                alert('Please fill in all required fields.');
                return;
            }

            try {
                const response = await fetch(`/employees/tasks/${currentTaskId}`, {
                    method: "PUT",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        title,
                        description,
                        priority,
                        status,
                        project_id,
                        progress,
                        due_date,
                        personal_notes
                    })
                });


                const result = await response.json();

                if (result.success) {
                    showMessage(`Task "${title}" updated successfully!`, 'success');
                    updateTaskCard(currentTaskId, result.task); // refresh UI with updated task
                    closeEditTaskModal();
                } else {
                    showMessage('Failed to update task.', 'error');
                }
            } catch (error) {
                console.error(error);
                showMessage('An error occurred while updating the task.', 'error');
            }
        }


        function updateTaskCard(taskId, taskData) {
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

        async function getTaskData(taskId) {
            try {
                const response = await fetch(`/employees/tasks/${taskId}`);
                if (!response.ok) throw new Error('Failed to fetch task');
                const task = await response.json();

                return {
                    title: task.title,
                    priority: task.priority,
                    status: task.status,
                    project_id: task.project_id,
                    project: task.project || 'N/A',
                    assignedBy: task.assigned_by_employee || 'N/A',
                    dueDate: task.due_date,
                    personal_notes: task.personal_notes,
                    createdDate: task.created_at,
                    progress: task.progress || 0,
                    description: task.description || '<p>No description available.</p>',
                    comments: task.comments || []
                };
            } catch (error) {
                console.error(error);
                return {
                    title: 'Error',
                    description: '<p>Could not load task details.</p>',
                    comments: []
                };
            }
        }

        async function viewTask(taskId) {
            const modal = document.getElementById('viewTaskModal');
            const modalTitle = document.getElementById('viewTaskTitle');
            const modalContent = document.getElementById('viewTaskContent');

            const taskData = await getTaskData(taskId);
            console.log(taskData);

            modalTitle.textContent = taskData.title || 'Task Details';
            const due = new Date(taskData.dueDate);
            const created = new Date(taskData.createdDate);

            // Build task details
            modalContent.innerHTML = `
        <div class="task-view">
            <div class="task-view-header">
                <div class="task-badges">
                    <div class="priority-badge ${taskData.priority}">
                        <i class="fas fa-flag"></i>
                        <span>${taskData.priority ? taskData.priority.charAt(0).toUpperCase() + taskData.priority.slice(1) : 'Unknown'} Priority</span>
                    </div>
                    <div class="status-badge ${taskData.status}">
                        <i class="fas fa-${getStatusIcon(taskData.status)}"></i>
                        <span>${formatStatus(taskData.status)}</span>
                    </div>
                </div>
            </div>
            
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
                        <span>${due.toLocaleString('en-US', {
                            month: 'short', day: '2-digit', year: 'numeric',
                            hour: '2-digit', minute: '2-digit'
                        })}</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-clock"></i>
                        <strong>Created:</strong>
                        <span>${created.toLocaleString('en-US', {
                            month: 'short', day: '2-digit', year: 'numeric',
                            hour: '2-digit', minute: '2-digit'
                        })}</span>
                    </div>
                </div>
            </div>

            <div class="task-description-view">
                <h4>Description</h4>
                <div class="description-text">
                    ${taskData.description || '<em>No description provided.</em>'}
                </div>
            </div>

            <div class="task-description-view">
                <h4>Personal Notes</h4>
                <div class="description-text">
                    ${taskData.personal_notes || '<em>No Personal Notes provided.</em>'}
                </div>
            </div>

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

            ${taskData.additionalDetails ? `
                                                                                                    <div class="task-additional">
                                                                                                        <h4>Additional Information</h4>
                                                                                                        <div class="additional-content">
                                                                                                            ${taskData.additionalDetails}
                                                                                                        </div>
                                                                                                    </div>
                                                                                                ` : ''}

            ${taskData.comments && taskData.comments.length > 0 ? `
                                                                                                    <div class="task-comments-view">
                                                                                                        <h4>Recent Comments</h4>
                                                                                                        <div class="comments-list">
                                                                                                            ${taskData.comments.map(comment => {
                                                                                                                const commentDate = new Date(comment.created_at);
                                                                                                                return `
                                <div class="comment-item">
                                    <div class="comment-header">
                                        <strong>${comment.employee.employee_name}</strong>
                                        <span class="comment-date">
                                            ${commentDate.toLocaleString('en-US', {
                                                month: 'short', day: '2-digit', year: 'numeric',
                                                hour: '2-digit', minute: '2-digit'
                                            })}
                                        </span>
                                    </div>
                                    <div class="comment-text">${comment.comment}</div>
                                </div>
                            `;
                                                                                                            }).join('')}
                                                                                                        </div>
                                                                                                    </div>
                                                                                                ` : ''}
        </div>
    `;

            // Show modal
            modal.classList.add('active');
            modal.dataset.currentTaskId = taskId;

            showMessage('Task details loaded!', 'success');
        }

        function getStatusIcon(status) {
            const icons = {
                'todo': 'hourglass-start',
                'in_progress': 'spinner',
                'completed': 'check-circle',
                'overdue': 'exclamation-triangle',
                'on_hold': 'pause-circle'
            };
            return icons[status] || 'question-circle';
        }

        function formatStatus(status) {
            const statusMap = {
                'todo': 'To Do',
                'in_progress': 'In Progress',
                'completed': 'Completed',
                'overdue': 'Overdue',
                'on_hold': 'On Hold'
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

        async function addTaskComment() {
            const comment = document.getElementById('commentText').value;
            console.log(currentTaskId);

            if (!comment.trim()) {
                alert('Please enter a comment.');
                return;
            }

            try {
                const response = await fetch(`/employees/tasks/${currentTaskId}/comments`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        comment
                    })
                });

                const result = await response.json();

                if (result.success) {
                    showMessage('Comment added successfully!', 'success');
                    closeCommentModal();
                    document.getElementById('commentText').value = '';
                    console.log("New Comment:", result.comment);
                } else {
                    showMessage('Failed to add comment.', 'error');
                }
            } catch (error) {
                console.error(error);
                showMessage('An error occurred while adding the comment.', 'error');
            }
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
        filterTasks();

        function filterTasks() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const statusFilter = document.getElementById('statusFilter').value;
            const priorityFilter = document.getElementById('priorityFilter').value;
            const projectFilter = document.getElementById('projectFilter').value;
            const tasks = document.querySelectorAll('.task-card');

            let todoCount = 0;
            let inProgressCount = 0;
            let completedCount = 0;
            let overdueCount = 0;

            tasks.forEach(task => {
                const title = task.querySelector('h4').textContent.toLowerCase();
                const description = task.querySelector('p').textContent.toLowerCase();
                const status = task.dataset.status;
                const priority = task.dataset.priority;
                const project = task.dataset.project;
                const dueDate = task.querySelector('.task-meta i.fa-clock')
                    ?.parentElement.textContent || '';

                const matchesSearch = title.includes(searchTerm) || description.includes(searchTerm);
                const matchesStatus = !statusFilter || status === statusFilter;
                const matchesPriority = !priorityFilter || priority === priorityFilter;
                const matchesProject = !projectFilter || project === projectFilter;

                if (matchesSearch && matchesStatus && matchesPriority && matchesProject) {
                    task.style.display = 'block';

                    // Count tasks by status dynamically
                    if (status === 'todo') todoCount++;
                    else if (status === 'in_progress') inProgressCount++;
                    else if (status === 'completed') completedCount++;

                    // Overdue check (simple: status not completed + past due date)
                    if ((status === 'todo' || status === 'in_progress') &&
                        task.querySelector('.task-meta i.fa-clock')) {
                        const dueText = task.querySelector('.task-meta i.fa-clock')
                            .parentElement.textContent.replace('Due:', '').trim();
                        const dueDate = new Date(dueText);
                        if (!isNaN(dueDate) && dueDate < new Date()) {
                            overdueCount++;
                        }
                    }

                } else {
                    task.style.display = 'none';
                }
            });

            // Update counts in status cards
            document.getElementById('todoCount').textContent = `${todoCount} tasks`;
            document.getElementById('inProgressCount').textContent = `${inProgressCount} tasks`;
            document.getElementById('completedCount').textContent = `${completedCount} tasks`;
            document.getElementById('overdueCount').textContent = `${overdueCount} tasks`;
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
