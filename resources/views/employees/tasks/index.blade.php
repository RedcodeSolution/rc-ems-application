@extends('layouts.employee')

@section('title', 'My Tasks')

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
                                <option value="">All Priority</option>
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
                                <option value="pending">Pending</option>
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
                            <label for="editTaskAssignee">Assigned By</label>
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

    <style>
        .tasks-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0;
        }

        .tasks-header {
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

        .tasks-header::before {
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

        .tasks-stats {
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

        .task-status-filter {
            margin-bottom: 2rem;
        }

        .task-status-filter h2 {
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

        .tasks-controls {
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

        .tasks-content {
            background: white;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--border-light);
        }

        .tasks-section {
            margin-bottom: 2rem;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--border-light);
        }

        .section-header h3 {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .task-count {
            background: var(--gradient-hero);
            background-size: 400% 400%;
            animation: gradientShift 18s ease infinite;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.875rem;
            font-weight: 600;
        }

        .tasks-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .task-card {
            background: var(--bg-primary);
            border: 2px solid var(--border-light);
            border-radius: 1rem;
            padding: 1.5rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-sm);
        }

        .task-card::before {
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

        .task-card:hover::before {
            transform: scaleY(1);
        }

        .task-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
            border-color: var(--redcode-primary);
        }

        .task-card.urgent {
            border-color: #DC2626;
            background: linear-gradient(135deg, rgba(220, 38, 38, 0.05), rgba(220, 38, 38, 0.02));
        }

        .task-card.overdue {
            border-color: #DC2626;
            background: linear-gradient(135deg, rgba(220, 38, 38, 0.1), rgba(220, 38, 38, 0.05));
        }

        .task-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .task-priority {
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .task-priority.high {
            background: rgba(220, 38, 38, 0.1);
            color: #DC2626;
        }

        .task-priority.medium {
            background: rgba(245, 158, 11, 0.1);
            color: #F59E0B;
        }

        .task-priority.low {
            background: rgba(34, 197, 94, 0.1);
            color: #22C55E;
        }

        .task-status {
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .task-status.todo {
            background: rgba(156, 163, 175, 0.1);
            color: #6B7280;
        }

        .task-status.in_progress {
            background: rgba(59, 130, 246, 0.1);
            color: #3B82F6;
        }

        .task-status.completed {
            background: rgba(34, 197, 94, 0.1);
            color: #22C55E;
        }

        .task-status.overdue {
            background: rgba(220, 38, 38, 0.1);
            color: #DC2626;
        }

        .task-content h4 {
            margin: 0 0 0.5rem 0;
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .task-content p {
            margin: 0 0 1rem 0;
            color: var(--text-secondary);
            font-size: 0.875rem;
            line-height: 1.5;
        }

        .task-meta {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
            margin-bottom: 1rem;
        }

        .task-meta span {
            color: var(--text-light);
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .task-progress {
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
            height: 6px;
            background: var(--bg-secondary);
            border-radius: 3px;
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

        .progress-fill.overdue {
            background: linear-gradient(135deg, #DC2626, #B91C1C);
        }

        .task-actions {
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

        .form-control[type="range"] {
            padding: 0.5rem 0;
            background: transparent;
            border: none;
            appearance: none;
            height: 20px;
        }

        .form-control[type="range"]::-webkit-slider-track {
            background: var(--bg-secondary);
            height: 6px;
            border-radius: 3px;
        }

        .form-control[type="range"]::-webkit-slider-thumb {
            appearance: none;
            width: 20px;
            height: 20px;
            background: var(--redcode-primary);
            border-radius: 50%;
            cursor: pointer;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
        }

        .form-control[type="range"]::-webkit-slider-thumb:hover {
            background: var(--redcode-primary-dark);
            transform: scale(1.1);
        }

        .form-control[type="range"]::-moz-range-track {
            background: var(--bg-secondary);
            height: 6px;
            border-radius: 3px;
            border: none;
        }

        .form-control[type="range"]::-moz-range-thumb {
            width: 20px;
            height: 20px;
            background: var(--redcode-primary);
            border-radius: 50%;
            cursor: pointer;
            border: none;
            box-shadow: var(--shadow-sm);
        }

        #progressValue {
            display: inline-block;
            margin-top: 0.5rem;
            font-weight: 600;
            color: var(--redcode-primary);
            font-size: 0.875rem;
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

            .tasks-stats {
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

            .form-row {
                grid-template-columns: 1fr;
            }

            .section-header {
                flex-direction: column;
                gap: 0.5rem;
            }

            .task-header {
                flex-direction: column;
                gap: 0.5rem;
                align-items: flex-start;
            }

            .task-meta {
                flex-direction: column;
            }
        }

        /* Grid View Styles */
        .tasks-board.grid-view .tasks-section {
            margin-bottom: 2rem;
        }

        .tasks-board.grid-view .tasks-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 1.5rem;
        }

        .tasks-board.grid-view .task-card {
            height: fit-content;
            min-height: 200px;
        }

        /* List View Styles */
        .tasks-board.list-view .tasks-section {
            margin-bottom: 1rem;
        }

        .tasks-board.list-view .tasks-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .tasks-board.list-view .task-card {
            display: flex;
            flex-direction: row;
            align-items: center;
            padding: 1rem 1.5rem;
            min-height: auto;
            height: auto;
        }

        .tasks-board.list-view .task-content {
            flex: 1;
            margin-right: 1rem;
        }

        .tasks-board.list-view .task-content h4 {
            margin-bottom: 0.5rem;
        }

        .tasks-board.list-view .task-content p {
            margin-bottom: 0.5rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .tasks-board.list-view .task-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 0;
        }

        .tasks-board.list-view .task-header {
            position: absolute;
            top: 1rem;
            right: 1rem;
            display: flex;
            gap: 0.5rem;
        }

        .tasks-board.list-view .task-progress {
            width: 200px;
            margin: 0 1rem;
        }

        .tasks-board.list-view .task-actions {
            margin-left: auto;
            display: flex;
            gap: 0.5rem;
        }

        .tasks-board.list-view .task-card {
            position: relative;
        }

        /* Responsive adjustments for grid/list views */
        @media (max-width: 768px) {
            .tasks-board.grid-view .tasks-list {
                grid-template-columns: 1fr;
            }

            .tasks-board.list-view .task-card {
                flex-direction: column;
                align-items: flex-start;
            }

            .tasks-board.list-view .task-progress {
                width: 100%;
                margin: 1rem 0;
            }

            .tasks-board.list-view .task-actions {
                margin-left: 0;
                margin-top: 1rem;
            }

            .tasks-board.list-view .task-header {
                position: static;
                margin-bottom: 1rem;
            }
        }

        /* View Task Modal Styles */
        .task-view {
            max-height: 70vh;
            overflow-y: auto;
        }

        .task-view-header {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e5e7eb;
        }

        .task-badges {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .priority-badge,
        .status-badge {
            display: flex;
            align-items: center;
            gap: 5px;
            padding: 8px 16px;
            border-radius: 25px;
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .priority-badge.high {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            color: #dc2626;
            border: 1px solid #f87171;
        }

        .priority-badge.medium {
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
            color: #2563eb;
            border: 1px solid #3b82f6;
        }

        .priority-badge.low {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            color: #059669;
            border: 1px solid #10b981;
        }

        .status-badge.todo {
            background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
            color: #6b7280;
            border: 1px solid #d1d5db;
        }

        .status-badge.in_progress {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            color: #d97706;
            border: 1px solid #f59e0b;
        }

        .status-badge.completed {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            color: #059669;
            border: 1px solid #10b981;
        }

        .status-badge.overdue {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            color: #dc2626;
            border: 1px solid #f87171;
        }

        .task-meta-info {
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            border: 1px solid #e2e8f0;
        }

        .meta-row {
            display: flex;
            gap: 30px;
            margin-bottom: 15px;
        }

        .meta-row:last-child {
            margin-bottom: 0;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
        }

        .meta-item i {
            color: #dc2626;
            width: 16px;
        }

        .meta-item strong {
            color: #374151;
            font-weight: 600;
        }

        .meta-item span {
            color: #6b7280;
        }

        .task-description-view {
            margin-bottom: 25px;
        }

        .task-description-view h4,
        .task-progress-view h4,
        .task-additional h4,
        .task-comments-view h4 {
            color: #dc2626;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #fee2e2;
        }

        .description-text,
        .additional-content {
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            line-height: 1.7;
        }

        .description-text h5 {
            color: #374151;
            font-weight: 600;
            margin: 20px 0 10px 0;
        }

        .description-text ul {
            margin: 15px 0;
            padding-left: 20px;
        }

        .description-text li {
            margin: 8px 0;
            color: #4b5563;
        }

        .description-text p {
            margin: 15px 0;
            color: #374151;
        }

        .task-progress-view {
            margin-bottom: 25px;
        }

        .progress-container {
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }

        .task-additional {
            margin-bottom: 25px;
        }

        .task-comments-view {
            margin-bottom: 20px;
        }

        .comments-list {
            background: #ffffff;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            overflow: hidden;
        }

        .comment-item {
            padding: 15px 20px;
            border-bottom: 1px solid #f3f4f6;
        }

        .comment-item:last-child {
            border-bottom: none;
        }

        .comment-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .comment-header strong {
            color: #374151;
            font-weight: 600;
        }

        .comment-date {
            color: #6b7280;
            font-size: 12px;
        }

        .comment-text {
            color: #4b5563;
            line-height: 1.6;
        }

        /* Responsive design for view modal */
        @media (max-width: 768px) {
            .task-badges {
                flex-direction: column;
                gap: 8px;
            }

            .meta-row {
                flex-direction: column;
                gap: 15px;
            }

            .task-view {
                max-height: 75vh;
            }
        }
    </style>

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
            const project_id = document.getElementById('taskProject').value;
            const due_date = document.getElementById('taskDueDate').value;
            const assigned_by = document.getElementById('taskAssignee').value;

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
                        assigned_by
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showMessage(data.message, 'success');
                        closeCreateTaskModal();

                        // Optionally refresh task list dynamically here
                    } else {
                        showMessage('Something went wrong!', 'error');
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

            // Store the current task ID for updating
            currentTaskId = taskId;

            // Show the edit modal
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
                    project: task.project || 'N/A',
                    assignedBy: task.assigned_by_employee || 'N/A',
                    dueDate: task.due_date,
                    notes: task.personal_notes,
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

            // Wait for task data
            const taskData = await getTaskData(taskId);
            console.log(taskData); // Now you'll see the actual object

            // Set modal title
            modalTitle.textContent = taskData.title || 'Task Details';
            const due = new Date(taskData.dueDate);
            const created = new Date(taskData.createdDate);
            // Create detailed content
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
                        <span>${due.toLocaleString('en-US', { month: 'short', day: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' })}</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-clock"></i>
                        <strong>Created:</strong>
                        <span>${created.toLocaleString('en-US', { month: 'short', day: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' })}</span>
                    </div>
                </div>
            </div>

            <div class="task-description-view">
                <h4>Description</h4>
                <div class="description-text">
                    ${taskData.description}
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

            ${taskData.comments && taskData.comments.length >= 0 ? `
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <div class="task-comments-view">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <h4>Recent Comments</h4>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <div class="comments-list">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    ${taskData.comments.map(comment =>{const commentDate = new Date(comment.created_at); return  `
                            <div class="comment-item">
                                <div class="comment-header">
                                    <strong>${comment.employee.employee_name}</strong>
                                    <span class="comment-date"> ${commentDate.toLocaleString('en-US', { 
                                    month: 'short', 
                                    day: '2-digit', 
                                    year: 'numeric', 
                                    hour: '2-digit', 
                                    minute: '2-digit' 
                                })}</span>
                                </div>
                                <div class="comment-text">${comment.comment}</div>
                            </div>
                        `}).join('')}
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
