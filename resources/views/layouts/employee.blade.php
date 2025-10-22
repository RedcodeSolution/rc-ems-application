<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'EMS') }} - @yield('title', 'Employee Dashboard')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Fallback for when Vite is not running -->
    @if (!app()->environment('local'))
        <link rel="stylesheet" href="{{ asset('build/assets/app-CObZ5BOq.css') }}">
        <script type="module" src="{{ asset('build/assets/app-l0sNRNKZ.js') }}"></script>
    @endif

    <link rel="stylesheet" href="{{ asset('build/assets/app-CObZ5BOq.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Employee/employee_side_bar.css') }}">
</head>

<body>
    <div class="sidebar">
        <div class="sidebar-brand">
            <h2>RedCode Solutions</h2>
            <div class="subtitle">Employee Portal</div>
        </div>

        <div class="sidebar-menu">
            <div class="sidebar-menu-section">
                <div class="sidebar-menu-title">Dashboard</div>
                <a href="{{ route('employee.dashboard') }}"
                    class="sidebar-menu-item {{ request()->routeIs('employee.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-pie"></i>
                    <span>Overview</span>
                </a>
            </div>

            <div class="sidebar-menu-section">
                <div class="sidebar-menu-title">Personal</div>
                <a href="{{ url('/employees/profile') }}" class="sidebar-menu-item">
                    <i class="fas fa-user"></i>
                    <span>My Profile</span>
                </a>
                <a href="{{ route('employees.documents.index') }}"
                    class="sidebar-menu-item {{ request()->routeIs('employee.documents') ? 'active' : '' }}">
                    <i class="fas fa-file-alt"></i>
                    <span>My Documents</span>
                </a>
            </div>

            <div class="sidebar-menu-section">
                <div class="sidebar-menu-title">Work</div>
                <a href="{{ route('employee.projects') }}"
                    class="sidebar-menu-item {{ request()->routeIs('employee.projects') ? 'active' : '' }}">
                    <i class="fas fa-project-diagram"></i>
                    <span>My Projects</span>
                </a>
                <a href="{{ route('employee.tasks') }}"
                    class="sidebar-menu-item {{ request()->routeIs('employee.tasks') ? 'active' : '' }}">
                    <i class="fas fa-tasks"></i>
                    <span>My Tasks</span>
                </a>
            </div>

            <div class="sidebar-menu-section">
                <div class="sidebar-menu-title">Attendance</div>
                <a href="{{ route('employee.attendance') }}"
                    class="sidebar-menu-item {{ request()->routeIs('employee.attendance') ? 'active' : '' }}">
                    <i class="fas fa-calendar-check"></i>
                    <span>Attendance</span>
                </a>
                <a href="{{ url('/employees/leaves') }}"
                    class="sidebar-menu-item {{ request()->routeIs('employee.leaves.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-times"></i>
                    <span>Leave Management</span>
                </a>
            </div>

            <div class="sidebar-menu-section">
                <div class="sidebar-menu-title">Communication</div>
                <a href="{{ route('employee.announcements.index') }}"
                    class="sidebar-menu-item {{ request()->routeIs('employee.announcements') ? 'active' : '' }}">
                    <i class="fas fa-bullhorn"></i>
                    <span>Announcements</span>
                </a>
            </div>

            <div class="sidebar-menu-section">
                <div class="sidebar-menu-title">Performance</div>
                <a href="{{ route('employee.ratings.index') }}"
                    class="sidebar-menu-item {{ request()->routeIs('employee.ratings.*') ? 'active' : '' }}">
                    <i class="fas fa-star"></i>
                    <span>All Employee Ratings</span>
                </a>
            </div>
        </div>
    </div>

    <div class="main-content">
        <div class="top-nav">
            <button class="sidebar-toggle" onclick="toggleSidebar()" style="display:none;">
                <i class="fas fa-bars"></i>
            </button>
            <script>
                // Show/hide sidebar toggle button based on screen size
                function handleSidebarToggleVisibility() {
                    const toggleBtn = document.querySelector('.sidebar-toggle');
                    if (window.innerWidth <= 1024) {
                        toggleBtn.style.display = 'inline-flex';
                    } else {
                        toggleBtn.style.display = 'none';
                    }
                }
                window.addEventListener('resize', handleSidebarToggleVisibility);
                window.addEventListener('DOMContentLoaded', handleSidebarToggleVisibility);
            </script>
            <div class="nav-title">
                <i class="fas fa-star"></i>
                @yield('title', 'Employee Dashboard')
            </div>
            <div class="nav-actions">
                <a href="#" class="nav-bell">
                    <div class="nav-bell-icon">
                        <i class="fas fa-bell"></i>
                        <span class="nav-bell-dot"></span>
                    </div>
                </a>

                <div class="user-menu">
                    <div class="user-avatar">
                        {{ strtoupper(substr(auth()->user()?->name ?? 'E', 0, 1)) }}
                    </div>
                    <div class="user-info">
                        <h4>{{ auth()->user()?->name ?? 'Employee' }}</h4>
                        <p>{{ auth()->user()?->email ?? 'employee@company.com' }}</p>
                    </div>
                    <i class="fas fa-chevron-down"></i>
                </div>

                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout
                    </button>
                </form>
            </div>
        </div>

        <div class="content-area">
            @yield('content')
        </div>
    </div>

    <script>
        // Mobile menu toggle functionality
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('active');
        }
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(e) {
            const sidebar = document.querySelector('.sidebar');
            const toggleButton = document.querySelector('.sidebar-toggle');

            if (window.innerWidth <= 1024 && !sidebar.contains(e.target) && !toggleButton?.contains(e.target)) {
                sidebar.classList.remove('active');
            }
        });
    </script>
</body>

</html>
