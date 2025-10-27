<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'EMS') }} - @yield('title', 'Super Admin Dashboard')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/SuperAdmin/superAdmin.css') }}">
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-brand">
            <h2>RedCode Solutions</h2>
            <div class="subtitle">Super Admin Panel</div>
        </div>

        <div class="sidebar-menu">
            <div class="sidebar-menu-section">
                <div class="sidebar-menu-title">Dashboard</div>
                <a href="{{ route('super_admin.dashboard') }}"
                    class="sidebar-menu-item {{ request()->routeIs('super_admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Overview</span>
                </a>
            </div>

            <div class="sidebar-menu-section">
                <div class="sidebar-menu-title">Admins</div>
                <a href="{{ route('super_admin.admins') }}"
                    class="sidebar-menu-item {{ request()->routeIs('super_admin.admins') ? 'active' : '' }}">
                    <i class="fas fa-user-shield"></i>
                    <span>Admin Management</span>
                </a>
                <a href="{{ route('super_admin.super_admin_accounts') }}"
                    class="sidebar-menu-item {{ request()->routeIs('super_admin.super_admin_accounts') ? 'active' : '' }}">
                    <i class="fas fa-user-cog"></i>
                    <span> Account Management</span>
                </a>
            </div>

            <div class="sidebar-menu-section">
                <div class="sidebar-menu-title">Admin Management</div>
                <a href="{{ route('super_admin.admin_leaves.index') }}"
                    class="sidebar-menu-item {{ request()->routeIs('super_admin.admin_leaves.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-check"></i>
                    <span>Admin Leave Management</span>
                </a>
            </div>

            <div class="sidebar-menu-section">
                <div class="sidebar-menu-title">Events</div>
                <a href="{{ route('super_admin.events.index') }}"
                    class="sidebar-menu-item {{ request()->routeIs('super_admin.events.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Events Management</span>
                </a>
            </div>

            <div class="sidebar-menu-section">
                <div class="sidebar-menu-title">Notifications</div>
                <a href="{{ route('super_admin.notifications') }}"
                    class="sidebar-menu-item {{ request()->routeIs('super_admin.notifications') ? 'active' : '' }}">
                    <i class="fas fa-bell"></i>
                    <span>All Notifications</span>
                    <span class="badge">{{ $notificationStats['unread'] ?? 0 }}</span>
                </a>
                <a href="{{ route('super_admin.employee_ratings') }}"
                    class="sidebar-menu-item {{ request()->routeIs('super_admin.employee_ratings') ? 'active' : '' }}">
                    <i class="fas fa-star"></i>
                    <span>Employee Ratings</span>
                </a>
                <a href="{{ route('super_admin.attendance.index') }}"
                    class="sidebar-menu-item {{ request()->routeIs('super_admin.attendance.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-check"></i>
                    <span>Attendance Count</span>
                </a>
            </div>
        </div>
    </div>


    <div class="main-content">

        <div class="top-nav">
            <div class="nav-title">
                <button class="sidebar-toggle" onclick="toggleSidebar()" aria-label="Toggle sidebar">
                    <i class="fas fa-bars"></i>
                </button>
                <i class="fas fa-crown"></i>
                @yield('title', 'Super Admin Dashboard')
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
                        {{ strtoupper(substr(auth()->user()?->name ?? 'SA', 0, 1)) }}
                    </div>
                    <div class="user-info">
                        <h4>{{ auth()->user()?->name ?? 'Super Admin' }}</h4>
                        <p>{{ auth()->user()?->email ?? 'superadmin@company.com' }}</p>
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
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('active');
        }


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
