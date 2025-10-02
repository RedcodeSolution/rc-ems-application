<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'EMS') }} - @yield('title', 'Admin Dashboard')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}">
</head>

<body>
    <div id="app">
        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="sidebar-brand">
                <h2><i class="fas fa-building mr-2"></i>RedCode Solutions</h2>
                <div class="subtitle">Employee Management System</div>
            </div>

            <div class="sidebar-menu">
                <div class="sidebar-menu-section">
                    <div class="sidebar-menu-title">Dashboard</div>
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-menu-item {{ request()->routeIs('admin.dashboard') ? 'active'  : '' }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard Overview</span>
                    </a>
                </div>

                <div class="sidebar-menu-section">
                    <div class="sidebar-menu-title">Employee Management</div>
                    <a href="{{ route('admin.employees') }}" class="sidebar-menu-item {{ request()->routeIs('admin.employees') ? 'active' : '' }}">
                        <i class="fas fa-users"></i>
                        <span>Employees</span>
                    </a>
                    <a href="{{ route('admin.departments.index') }}" class="sidebar-menu-item {{ request()->routeIs('departments.*') ? 'active' : '' }}">
                        <i class="fas fa-building"></i>
                        <span>Departments</span>
                    </a>
                    <a href="{{ route('admin.teams') }}" class="sidebar-menu-item {{ request()->routeIs('admin.teams') ? 'active' : '' }}">
                        <i class="fas fa-users-cog"></i>
                        <span>Teams</span>
                    </a>
                    <a href="{{ route('admin.employeeRatings.index') }}" class="sidebar-menu-item {{ request()->routeIs('admin.employeeRatings.*') ? 'active' : '' }}">
                        <i class="fas fa-star"></i>
                        <span>Employee Ratings</span>
                    </a>
                </div>

                <div class="sidebar-menu-section">
                    <div class="sidebar-menu-title">Project Management</div>
                    <a href="{{ route('admin.projects.index') }}" class="sidebar-menu-item {{ request()->routeIs('admin.projects') ? 'active' : '' }}">
                        <i class="fas fa-project-diagram"></i>
                        <span>Projects</span>
                    </a>
                    <a href="{{ route('admin.leaves') }}" class="sidebar-menu-item {{ request()->routeIs('admin.leaves') ? 'active' : '' }}">
                        <i class="fas fa-calendar-times"></i>
                        <span>Leave Management</span>
                    </a>
                </div>

                <div class="sidebar-menu-section">
                    <div class="sidebar-menu-title">Reports & Analytics</div>
                    <a href="{{ route('admin.reports.index') }}" class="sidebar-menu-item {{ request()->routeIs('admin.reports') ? 'active' : '' }}">
                        <i class="fas fa-chart-bar"></i>
                        <span>Reports</span>
                    </a>
                    <a href="{{ route('admin.announcements') }}" class="sidebar-menu-item {{ request()->routeIs('admin.announcements') ? 'active' : '' }}">
                        <i class="fas fa-bullhorn"></i>
                        <span>Announcements</span>
                    </a>
                </div>

                <div class="sidebar-menu-section">
                    <div class="sidebar-menu-title">Administration</div>
                    <a href="{{ route('admin.profile') }}" class="sidebar-menu-item {{ request()->routeIs('admin.profile') ? 'active' : '' }}">
                        <i class="fas fa-user-shield"></i>
                        <span>Admin Profile</span>
                    </a>
                    <a href="{{ route('admin.documents.index') }}" class="sidebar-menu-item {{ request()->routeIs('admin.documents') ? 'active' : '' }}">
                        <i class="fas fa-file-alt"></i>
                        <span>Documents</span>
                    </a>
                    <a href="{{ route('admin.notifications') }}" class="sidebar-menu-item {{ request()->routeIs('admin.notifications') ? 'active' : '' }}">
                        <i class="fas fa-bell"></i>
                        <span>Notifications</span>
                        <span class="badge">7</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Navigation -->
            <div class="top-nav">
                <div class="nav-title">
                    <button class="mobile-menu-btn" onclick="toggleSidebar()">
                        <i class="fas fa-bars"></i>
                    </button>
                    <i class="fas fa-tachometer-alt"></i>
                    @yield('title', 'Dashboard')
                </div>

                <div class="nav-actions">
                    <div class="user-menu">
                        <div class="user-avatar">
                            {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                        </div>
                        <div class="user-info">
                            <h4>{{ Auth::user()->name ?? 'Admin User' }}</h4>
                            <p>{{ ucfirst(Auth::user()->role ?? 'admin') }}</p>
                        </div>
                        <i class="fas fa-chevron-down" style="color: var(--gray-400);"></i>
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

            <!-- Content Area -->
            <div class="content-area">
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ session('error') }}
                    </div>
                @endif

                @if(session('warning'))
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        {{ session('warning') }}
                    </div>
                @endif

                @if(session('info'))
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        {{ session('info') }}
                    </div>
                @endif

                <!-- Page Content -->
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Notification Modal (Dropdown Style) -->
<div id="notificationModal" class="modal-dropdown" style="display:none;">
    <div class="modal-dropdown-bg"></div>
    <div class="modal-dropdown-content">
        <div class="modal-dropdown-header">
            <h3><i class="fas fa-bell"></i> Notifications</h3>
            <button class="modal-close" id="closeNotificationModal">&times;</button>
        </div>
        <div class="modal-dropdown-body">
            <!-- Only show first three notifications -->
            <div class="notification-item">
                <div>
                    <div class="notification-title">New Employee Registration</div>
                    <div class="notification-desc">John Doe has registered and is pending approval.</div>
                    <div class="notification-meta"><i class="fas fa-clock"></i> 5 min ago</div>
                </div>
                <div class="notification-actions">
                    <button class="btn btn-success btn-sm">Approve</button>
                    <button class="btn btn-danger btn-sm">Cancel</button>
                </div>
            </div>

            <div class="notification-item">
                <div>
                    <div class="notification-title">System Maintenance</div>
                    <div class="notification-desc">Scheduled for Sunday, 2:00 AM - 4:00 AM.</div>
                    <div class="notification-meta"><i class="fas fa-clock"></i> 2 days ago</div>
                </div>
                <div class="notification-actions">
                    <button class="btn btn-secondary btn-sm">OK</button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* ...existing code... */

/* ...existing code... */
</style>

<script>
    // Mobile Sidebar Toggle
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('open');
        // Prevent body scroll when sidebar is open on mobile
        if (window.innerWidth <= 450) {
            if (sidebar.classList.contains('open')) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        }
    }

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(e) {
        const sidebar = document.getElementById('sidebar');
        const mobileBtn = document.querySelector('.mobile-menu-btn');
        if (
            window.innerWidth <= 450 &&
            sidebar.classList.contains('open') &&
            !sidebar.contains(e.target) &&
            !mobileBtn.contains(e.target)
        ) {
            sidebar.classList.remove('open');
            document.body.style.overflow = '';
        }
    });

    // Close sidebar on mobile when a sidebar link is clicked and allow navigation
    document.querySelectorAll('.sidebar-menu-item').forEach(function(link) {
        link.addEventListener('click', function(e) {
            const sidebar = document.getElementById('sidebar');
            if (window.innerWidth <= 450 && sidebar.classList.contains('open')) {
                sidebar.classList.remove('open');
                document.body.style.overflow = '';
                // Use a short timeout to allow sidebar to close before navigation
                if (link.tagName.toLowerCase() === 'a' && link.href) {
                    e.preventDefault();
                    setTimeout(() => {
                        window.location = link.href;
                    }, 120);
                }
            }
            // For desktop or if sidebar not open, let navigation proceed normally
        });
    });

    // Auto-hide flash messages
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-10px)';
            setTimeout(() => alert.remove(), 300);
        });
    }, 5000);

    // Add loading state to buttons
    document.querySelectorAll('.btn').forEach(btn => {
        btn.addEventListener('click', function() {
            if (this.type === 'submit' || this.href) {
                this.classList.add('loading');
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';

                setTimeout(() => {
                    this.innerHTML = originalText;
                    this.classList.remove('loading');
                }, 2000);
            }
        });
    });

    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Notification Modal Dropdown Logic
    document.addEventListener('DOMContentLoaded', function() {
        var bellBtn = document.getElementById('navBellBtn');
        var modal = document.getElementById('notificationModal');
        var closeBtn = document.getElementById('closeNotificationModal');
        var bg = modal ? modal.querySelector('.modal-dropdown-bg') : null;

        function closeModal() {
            if (modal) modal.style.display = 'none';
        }

        if (bellBtn && modal && closeBtn) {
            bellBtn.addEventListener('click', function(e) {
                e.preventDefault();
                // Toggle dropdown
                if (modal.style.display === 'none' || modal.style.display === '') {
                    modal.style.display = 'flex';
                } else {
                    closeModal();
                }
            });
            closeBtn.addEventListener('click', closeModal);
            // Close modal if click outside content (on blur bg)
            if (bg) {
                bg.addEventListener('click', closeModal);
            }
            document.addEventListener('mousedown', function(e) {
                if (
                    modal.style.display === 'flex' &&
                    !modal.querySelector('.modal-dropdown-content').contains(e.target) &&
                    e.target !== bellBtn &&
                    !bellBtn.contains(e.target)
                ) {
                    closeModal();
                }
            });
        }
    });
    // ...existing code...
</script>

    @stack('scripts')
</body>
</html>
