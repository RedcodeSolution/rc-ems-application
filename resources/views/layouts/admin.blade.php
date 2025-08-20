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

    <style>
        :root {
            /* RedCode Solutions Color Palette - Matching Register Page */
            /* Primary Red Colors - Core Brand */
            --redcode-primary: #DC2626; /* RedCode Brand Red */
            --redcode-primary-dark: #991B1B; /* Deep Red */
            --redcode-primary-light: #FEE2E2; /* Light Red Background */
            --redcode-accent: #B91C1C; /* Accent Red */

            /* Secondary Colors - Professional Palette */
            --redcode-dark: #1F2937; /* Charcoal for headers/nav */
            --redcode-gray: #6B7280; /* Medium Gray for text */
            --redcode-light: #F9FAFB; /* Light Background */
            --redcode-white: #FFFFFF; /* Pure White */

            /* Status Colors */
            --redcode-blue: #2563EB; /* Links, buttons */
            --redcode-green: #059669; /* Success states */
            --redcode-orange: #D97706; /* Warnings */
            --redcode-yellow: #F59E0B; /* Alerts */

            /* Text Colors */
            --text-primary: #111827; /* Almost Black */
            --text-secondary: #6B7280; /* Medium Gray */
            --text-light: #9CA3AF; /* Light Gray */
            --text-white: #FFFFFF; /* White Text */
            --text-disabled: #9CA3AF; /* Light Gray */

            /* Background Colors */
            --bg-primary: #FFFFFF; /* White Background */
            --bg-secondary: #F9FAFB; /* Light Gray Background */
            --bg-dark: #1F2937; /* Dark Background */

            /* Border Colors */
            --border-light: #E5E7EB;
            --border-medium: #D1D5DB;
            --border-dark: #6B7280;
            --divider: #E5E7EB; /* Light Gray */

            /* Gray Scale - RedCode Adapted */
            --gray-50: #F9FAFB;
            --gray-100: #F3F4F6;
            --gray-200: #E5E7EB;
            --gray-300: #D1D5DB;
            --gray-400: #9CA3AF;
            --gray-500: #6B7280;
            --gray-600: #4B5563;
            --gray-700: #374151;
            --gray-800: #1F2937;
            --gray-900: #111827;

            /* Legacy compatibility aliases */
            --primary: #DC2626;           /* RedCode Brand Red */
            --primary-dark: #1F2937;      /* Dark Charcoal */
            --primary-light: #F9FAFB;     /* Light Gray */
            --accent: #1F2937;            /* Dark Charcoal */

            /* Secondary / UI Colors */
            --secondary: #2563EB;         /* RedCode Blue */
            --success: #059669;           /* RedCode Green */
            --warning: #D97706;           /* RedCode Orange */
            --danger: #DC2626;            /* RedCode Primary */
            --error: #DC2626;             /* RedCode Primary */
            --info: #2563EB;              /* RedCode Blue */

            --shadow-sm: 0 1px 2px 0 rgba(220,38,38,0.04);
            --shadow: 0 1px 3px 0 rgba(220,38,38,0.1), 0 1px 2px 0 rgba(220,38,38,0.06);
            --shadow-md: 0 4px 6px -1px rgba(220,38,38,0.1), 0 2px 4px -1px rgba(220,38,38,0.06);
            --shadow-lg: 0 10px 15px -3px rgba(220,38,38,0.1), 0 4px 6px -2px rgba(220,38,38,0.05);
            --shadow-xl: 0 20px 25px -5px rgba(220,38,38,0.1), 0 10px 10px -5px rgba(220,38,38,0.04);

            /* RedCode Gradients */
            --gradient-primary: linear-gradient(135deg, #DC2626 0%, #991B1B 100%);
            --gradient-secondary: linear-gradient(135deg, #1F2937 0%, #374151 100%);
            --gradient-accent: linear-gradient(135deg, #DC2626 0%, #B91C1C 100%);
            --gradient-hero: linear-gradient(135deg, #DC2626 0%, #1F2937 50%, #991B1B 100%);
            --gradient-glass: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--primary-light);
            color: var(--text-primary);
            line-height: 1.6;
        }

        /* Sidebar Styles */
        .sidebar {
            background: var(--gradient-hero);
            background-size: 400% 400%;
            animation: gradientShift 18s ease infinite;
            width: 310px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            z-index: 1000;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow-y: auto;
            box-shadow: var(--shadow-xl);
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            25% { background-position: 100% 50%; }
            50% { background-position: 100% 100%; }
            75% { background-position: 0% 100%; }
            100% { background-position: 0% 50%; }
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 3px;
        }

        .sidebar-brand {
            padding: 2rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
        }

        .sidebar-brand h2 {
            color: #fff;
            font-size: 1.75rem;
            font-weight: 800;
            margin: 0;
            letter-spacing: 0.05em;
        }

        .sidebar-brand .subtitle {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.875rem;
            font-weight: 500;
            margin-top: 0.5rem;
        }

        .sidebar-menu {
            padding: 1.5rem 0;
        }

        .sidebar-menu-section {
            margin-bottom: 2rem;
        }

        .sidebar-menu-title {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            padding: 0 1.5rem;
            margin-bottom: 1rem;
        }

        .sidebar-menu-item {
            display: flex;
            align-items: center;
            padding: 0.875rem 1.5rem;
            color: #fff;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 0.75rem;
            margin: 0.25rem 0.75rem;
            position: relative;
            overflow: hidden;
        }

        .sidebar-menu-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 0;
            height: 100%;
            background: rgba(255, 255, 255, 0.15);
            transition: width 0.3s ease;
            border-radius: 0.75rem;
        }

        .sidebar-menu-item:hover::before,
        .sidebar-menu-item.active::before {
            width: 100%;
        }

        .sidebar-menu-item:hover,
        .sidebar-menu-item.active {
            color: #fff;
            transform: translateX(8px);
        }

        .sidebar-menu-item i {
            width: 24px;
            margin-right: 1rem;
            font-size: 1.125rem;
            position: relative;
            z-index: 1;
        }

        .sidebar-menu-item span {
            font-weight: 500;
            position: relative;
            z-index: 1;
            flex: 1;
        }

        .sidebar-menu-item .badge {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 1rem;
            margin-left: auto;
            position: relative;
            z-index: 1;
            font-weight: 600;
            min-width: 1.5rem;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            margin-left: 280px;
            min-height: 100vh;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Top Navigation */
        .top-nav {
            background: #fff;
            border-bottom: 1px solid var(--divider);
            padding: 1rem 2rem;
            box-shadow: var(--shadow-md);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .nav-title {
            color: var(--text-primary);
            font-size: 1.5rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .nav-title i {
            color: var(--primary);
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 1.25rem;
            position: relative;
        }

        .nav-bell {
            color: var(--text-secondary);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            position: relative;
            margin-right: 0.25rem;
            text-decoration: none;
            transition: color 0.2s;
        }

        .nav-bell:hover .nav-bell-icon {
            background: var(--gradient-primary);
            color: #fff;
            box-shadow: 0 2px 8px 0 rgba(59,130,246,0.10);
        }

        .nav-bell-icon {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            background: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            position: relative;
            transition: background 0.2s, color 0.2s, box-shadow 0.2s;
        }

        .nav-bell-dot {
            background: var(--danger);
            position: absolute;
            top: 0.7rem;
            right: 0.7rem;
            width: 0.6rem;
            height: 0.6rem;
            border-radius: 50%;
            border: 2px solid #fff;
            box-shadow: 0 0 0 2px #fff;
            animation: bell-pulse 1.5s infinite;
            display: inline-block;
        }

        @keyframes bell-pulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.3); opacity: 0.7; }
        }

        .user-menu {
            background: var(--primary-light);
            border: 1px solid var(--divider);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 1rem;
            border-radius: 0.75rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .user-menu:hover {
            background: #fff;
            box-shadow: var(--shadow-md);
        }

        .user-avatar {
            background: var(--gradient-primary);
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 1rem;
        }

        .user-info h4 {
            color: var(--text-primary);
            font-size: 0.875rem;
            font-weight: 600;
        }

        .user-info p {
            color: var(--text-secondary);
            font-size: 0.75rem;
        }

        /* Content Area */
        .content-area {
            padding: 2rem;
        }

        /* Cards */
        .card {
            background: #fff;
            border: 1px solid var(--divider);
            border-radius: 1rem;
            box-shadow: var(--shadow-md);
            margin-bottom: 1.5rem;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-light) 0%, #fff 100%);
            border-bottom: 1px solid var(--divider);
            padding: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-header h2 {
            color: var(--text-primary);
            font-size: 1.25rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .card-header h2::before {
            content: '';
            width: 4px;
            height: 1.5rem;
            background: var(--gradient-primary);
            border-radius: 2px;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-size: 0.875rem;
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn-primary {
            background: var(--gradient-primary);
            color: #fff;
            box-shadow: var(--shadow-sm);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .btn-secondary {
            background: var(--primary-light);
            color: var(--text-secondary);
            border: 1px solid var(--divider);
        }

        .btn-secondary:hover {
            background: var(--gray-200);
            transform: translateY(-1px);
        }

        .btn-success {
            background: linear-gradient(135deg, var(--success) 0%, #388e3c 100%);
            color: #fff;
        }

        .btn-warning {
            background: linear-gradient(135deg, var(--warning) 0%, #ffb300 100%);
            color: #fff;
        }

        .btn-danger {
            background: linear-gradient(135deg, var(--danger) 0%, #d84315 100%);
            color: #fff;
        }

        /* Tables */
        .table-container {
            background: #fff;
            border: 1px solid var(--divider);
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: var(--shadow-md);
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th {
            background: var(--primary-light);
            color: var(--text-secondary);
            border-bottom: 1px solid var(--divider);
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .table td {
            border-bottom: 1px solid var(--primary-light);
            color: var(--text-primary);
            padding: 1rem;
        }

        .table tbody tr:hover {
            background: var(--gray-100);
        }

        /* Forms */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--text-primary);
            font-size: 0.875rem;
        }

        .form-input, .form-select {
            border: 2px solid var(--divider);
            background: var(--primary-light);
            color: var(--text-primary);
            width: 100%;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            transition: all 0.3s ease;
        }

        .form-input:focus, .form-select:focus {
            border-color: var(--primary);
            background: #fff;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            outline: none;
        }

        /* Alerts */
        .alert {
            padding: 1rem 1.5rem;
            border-radius: 0.75rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 500;
        }

        .alert-success {
            background: rgba(67, 160, 71, 0.1);
            color: var(--success);
            border: 1px solid rgba(67, 160, 71, 0.2);
        }

        .alert-error {
            background: rgba(230, 74, 25, 0.1);
            color: var(--danger);
            border: 1px solid rgba(230, 74, 25, 0.2);
        }

        .alert-warning {
            background: rgba(255, 160, 0, 0.1);
            color: var(--warning);
            border: 1px solid rgba(255, 160, 0, 0.2);
        }

        .alert-info {
            background: rgba(0, 151, 167, 0.1);
            color: var(--info);
            border: 1px solid rgba(0, 151, 167, 0.2);
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .content-area {
                padding: 1rem;
            }

            .top-nav {
                padding: 1rem;
            }

            .user-info {
                display: none;
            }

            .card-header {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }
        }

        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            font-size: 1.25rem;
            color: var(--gray-600);
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .mobile-menu-btn:hover {
            background: var(--gray-100);
        }

        @media (max-width: 768px) {
            .mobile-menu-btn {
                display: block;
            }
        }

        /* Loading States */
        .loading {
            opacity: 0.6;
            pointer-events: none;
        }

        /* Utility Classes */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .mb-0 { margin-bottom: 0; }
        .mb-1 { margin-bottom: 0.25rem; }
        .mb-2 { margin-bottom: 0.5rem; }
        .mb-3 { margin-bottom: 0.75rem; }
        .mb-4 { margin-bottom: 1rem; }
        .mt-4 { margin-top: 1rem; }
        .p-0 { padding: 0; }
        .flex { display: flex; }
        .justify-between { justify-content: space-between; }
        .items-center { align-items: center; }
        .gap-2 { gap: 0.5rem; }
        .gap-4 { gap: 1rem; }
        .w-full { width: 100%; }
        .hidden { display: none; }
    </style>
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
                    <a href="{{ route('departments.index') }}" class="sidebar-menu-item {{ request()->routeIs('departments.*') ? 'active' : '' }}">
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
                    <a href="{{ route('admin.projects') }}" class="sidebar-menu-item {{ request()->routeIs('admin.projects') ? 'active' : '' }}">
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
                    <a href="{{ route('admin.reports') }}" class="sidebar-menu-item {{ request()->routeIs('admin.reports') ? 'active' : '' }}">
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
                    <a href="{{ route('admin.documents') }}" class="sidebar-menu-item {{ request()->routeIs('admin.documents') ? 'active' : '' }}">
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
                    <!-- Notification Bell -->
                    <a href="{{ route('admin.notifications') }}" class="nav-bell" title="Notifications" id="navBellBtn" type="button">
                        <span class="nav-bell-icon">
                            <i class="fas fa-bell"></i>
                            <span class="nav-bell-dot"></span>
                        </span>
                    </a>
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
.modal-dropdown {
    position: fixed;
    top: 70px;
    right: 2.5rem;
    z-index: 3000;
    display: flex;
    align-items: flex-start;
    justify-content: flex-end;
    background: transparent;
    pointer-events: none;
}
.modal-dropdown-bg {
    content: '';
    position: fixed;
    inset: 0;
    z-index: 2999;
    background: rgba(30,41,59,0.10);
    backdrop-filter: blur(2px);
    transition: opacity 0.2s;
    pointer-events: auto;
}
.modal-dropdown-content {
    background: #fff;
    border-radius: 1rem;
    box-shadow: 0 8px 32px 0 rgba(37,99,235,0.18), 0 1.5px 4px rgba(0,0,0,0.03);
    width: 370px;
    max-width: 95vw;
    padding: 0;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    animation: modalPop 0.2s;
    pointer-events: auto;
    z-index: 3001;
}
@keyframes modalPop {
    from { transform: translateY(-10px) scale(0.98); opacity: 0; }
    to { transform: translateY(0) scale(1); opacity: 1; }
}

.modal-dropdown-header {
    background: var(--gradient-primary);
    color: #fff;
    padding: 1rem 1.25rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.modal-dropdown-header h3 {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.modal-close {
    background: none;
    border: none;
    color: #fff;
    font-size: 1.3rem;
    cursor: pointer;
    transition: color 0.2s;
    margin-left: 1rem;
}
.modal-close:hover {
    color: #fbbf24;
}
.modal-dropdown-body {
    padding: 1rem 1.25rem;
    max-height: 55vh;
    overflow-y: auto;
}
.notification-item {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 1rem;
    padding: 1rem 0;
    border-bottom: 1px solid var(--gray-100);
}
.notification-item:last-child {
    border-bottom: none;
}
.notification-title {
    color: var(--primary-dark);
    font-weight: 700;
    margin-bottom: 0.25rem;
}
.notification-desc {
    color: var(--text-secondary);
    font-size: 0.95rem;
    margin-bottom: 0.25rem;
}
.notification-meta {
    color: var(--text-disabled);
    font-size: 0.85rem;
    display: flex;
    align-items: center;
    gap: 0.4rem;
}
.notification-actions {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    min-width: 90px;
}
.btn-sm {
    padding: 0.4rem 0.9rem;
    font-size: 0.85rem;
    border-radius: 0.4rem;
}
@media (max-width: 600px) {
    .modal-dropdown { right: 0.5rem; top: 60px; }
    .modal-dropdown-content { max-width: 98vw; }
    .modal-dropdown-header, .modal-dropdown-body { padding: 0.75rem; }
}
/* ...existing code... */
</style>

<script>
    // Mobile Sidebar Toggle
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('open');
    }

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(e) {
        const sidebar = document.getElementById('sidebar');
        const mobileBtn = document.querySelector('.mobile-menu-btn');

        if (window.innerWidth <= 768 &&
            !sidebar.contains(e.target) &&
            !mobileBtn.contains(e.target) &&
            sidebar.classList.contains('open')) {
            sidebar.classList.remove('open');
        }
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
