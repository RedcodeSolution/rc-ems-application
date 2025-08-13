<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'EMS') }} - @yield('title', 'Employee Dashboard')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Fallback for when Vite is not running -->
    @if(!app()->environment('local'))
        <link rel="stylesheet" href="{{ asset('build/assets/app-CObZ5BOq.css') }}">
        <script type="module" src="{{ asset('build/assets/app-l0sNRNKZ.js') }}"></script>
    @endif
    
    <!-- Alternative: Always include built assets -->
    <link rel="stylesheet" href="{{ asset('build/assets/app-CObZ5BOq.css') }}">

    <style>
        :root {
            /* RedCode Solutions Color Palette - Matching Admin Dashboard */
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

            /* RedCode Gradients */
            --gradient-primary: linear-gradient(135deg, #DC2626 0%, #991B1B 100%);
            --gradient-secondary: linear-gradient(135deg, #1F2937 0%, #374151 100%);
            --gradient-accent: linear-gradient(135deg, #DC2626 0%, #B91C1C 100%);
            --gradient-hero: linear-gradient(135deg, #DC2626 0%, #1F2937 50%, #991B1B 100%);
            --gradient-glass: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);

            /* Legacy compatibility aliases */
            --primary: var(--redcode-primary);
            --primary-dark: var(--redcode-primary-dark);
            --primary-light: var(--redcode-primary-light);
            --accent: var(--redcode-dark);
            --secondary: var(--redcode-blue);
            --success: var(--redcode-green);
            --warning: var(--redcode-orange);
            --danger: var(--redcode-primary);
            --error: var(--redcode-primary);
            --info: var(--redcode-blue);
            --divider: var(--border-light);
            --shadow-sm: 0 1px 2px 0 rgba(220, 38, 38, 0.04);
            --shadow: 0 1px 3px 0 rgba(220, 38, 38, 0.1), 0 1px 2px 0 rgba(220, 38, 38, 0.06);
            --shadow-md: 0 4px 6px -1px rgba(220, 38, 38, 0.1), 0 2px 4px -1px rgba(220, 38, 38, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(220, 38, 38, 0.1), 0 4px 6px -2px rgba(220, 38, 38, 0.05);
            --shadow-xl: 0 20px 25px -5px rgba(220, 38, 38, 0.1), 0 10px 10px -5px rgba(220, 38, 38, 0.04);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-secondary);
            color: var(--text-primary);
            line-height: 1.6;
        }

        /* Employee Sidebar Styles */
        .sidebar {
            background: var(--gradient-hero);
            background-size: 400% 400%;
            animation: gradientShift 18s ease infinite;
            width: 280px;
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
            position: relative;
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

        /* Content Area */
        .content-area {
            padding: 2rem;
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
        }

        @media (max-width: 768px) {
            .top-nav {
                padding: 1rem;
            }
            
            .nav-title {
                font-size: 1.25rem;
            }
            
            .content-area {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-brand">
            <h2>RedCode Solutions</h2>
            <div class="subtitle">Employee Portal</div>
        </div>
        
        <div class="sidebar-menu">
            <div class="sidebar-menu-section">
                <div class="sidebar-menu-title">Dashboard</div>
                <a href="{{ route('employee.dashboard') }}" class="sidebar-menu-item {{ request()->routeIs('employee.dashboard') ? 'active' : '' }}">
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
                <a href="{{ route('employee.documents') }}" class="sidebar-menu-item {{ request()->routeIs('employee.documents') ? 'active' : '' }}">
                    <i class="fas fa-file-alt"></i>
                    <span>My Documents</span>
                </a>
            </div>
            
            <div class="sidebar-menu-section">
                <div class="sidebar-menu-title">Work</div>
                <a href="{{ route('employee.projects') }}" class="sidebar-menu-item {{ request()->routeIs('employee.projects') ? 'active' : '' }}">
                    <i class="fas fa-project-diagram"></i>
                    <span>My Projects</span>
                </a>
                <a href="{{ route('employee.tasks') }}" class="sidebar-menu-item {{ request()->routeIs('employee.tasks') ? 'active' : '' }}">
                    <i class="fas fa-tasks"></i>
                    <span>My Tasks</span>
                </a>
            </div>
            
            <div class="sidebar-menu-section">
                <div class="sidebar-menu-title">Attendance</div>
                <a href="{{ route('employee.attendance') }}" class="sidebar-menu-item {{ request()->routeIs('employee.attendance') ? 'active' : '' }}">
                    <i class="fas fa-calendar-check"></i>
                    <span>Attendance</span>
                </a>
                <a href="{{ route('employee.leaves.index') }}" class="sidebar-menu-item {{ request()->routeIs('employee.leaves.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-times"></i>
                    <span>Leave Management</span>
                </a>
            </div>
            
            <div class="sidebar-menu-section">
                <div class="sidebar-menu-title">Communication</div>
                <a href="{{ route('employee.announcements') }}" class="sidebar-menu-item {{ request()->routeIs('employee.announcements') ? 'active' : '' }}">
                    <i class="fas fa-bullhorn"></i>
                    <span>Announcements</span>
                </a>
            </div>
            
            <div class="sidebar-menu-section">
                <div class="sidebar-menu-title">Performance</div>
                <a href="{{ route('employee.ratings.index') }}" class="sidebar-menu-item {{ request()->routeIs('employee.ratings.*') ? 'active' : '' }}">
                    <i class="fas fa-star"></i>
                    <span>All Employee Ratings</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navigation -->
        <div class="top-nav">
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
                        {{ strtoupper(substr(auth()->user()->name ?? 'E', 0, 1)) }}
                    </div>
                    <div class="user-info">
                        <h4>{{ auth()->user()->name ?? 'Employee' }}</h4>
                        <p>{{ auth()->user()->email ?? 'employee@company.com' }}</p>
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

        <!-- Content Area -->
        <div class="content-area">
            @yield('content')
        </div>
    </div>

    <!-- Mobile Menu Toggle -->
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