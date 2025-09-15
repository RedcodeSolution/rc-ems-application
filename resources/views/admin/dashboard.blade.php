@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<style>
    :root {
        /* RedCode Solutions Color Palette */
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
        --accent: var(--redcode-dark);
        --primary-light: var(--redcode-light);
        --secondary: var(--redcode-blue);
        --success: var(--redcode-green);
        --warning: var(--redcode-orange);
        --danger: var(--redcode-primary);
        --error: var(--redcode-primary);
        --info: var(--redcode-blue);
        --text-disabled: var(--text-light);
        --divider: var(--border-light);
    }

    /* Advanced Dashboard Styles */
    .dashboard-container {
        /* Remove blue gradient, use clean white background */
        background: var(--primary-light);
        min-height: 100vh;
        padding: 2rem;
        position: relative;
        overflow: hidden;
    }

    .dashboard-container::before {
        /* RedCode Solutions subtle pattern overlay */
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(220, 38, 38, 0.04)"/><circle cx="75" cy="75" r="1" fill="rgba(220, 38, 38, 0.04)"/><circle cx="50" cy="10" r="0.5" fill="rgba(220, 38, 38, 0.02)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        pointer-events: none;
        z-index: 1;
    }

    .dashboard-content {
        position: relative;
        z-index: 2;
    }

    /* Enhanced Header */
    .dashboard-header {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 8px 32px rgba(37,99,235,0.08), 0 1.5px 4px rgba(0,0,0,0.03);
        border: 1px solid var(--divider);
        position: relative;
        overflow: hidden;
    }

    .dashboard-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--redcode-primary), var(--redcode-blue), var(--redcode-dark));
        background-size: 300% 100%;
        animation: gradientShift 3s ease-in-out infinite;
    }

    @keyframes gradientShift {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }

    .dashboard-title {
        font-size: 2.5rem;
        font-weight: 800;
        background: linear-gradient(135deg, var(--redcode-primary) 0%, var(--redcode-dark) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        letter-spacing: 0.01em;
    }

    .dashboard-subtitle {
        color: var(--text-secondary);
        font-size: 1.1rem;
        font-weight: 500;
        letter-spacing: 0.01em;
    }

    .live-time {
        position: absolute;
        top: 2rem;
        right: 2rem;
        background: rgba(220, 38, 38, 0.08);
        padding: 1rem 1.5rem;
        border-radius: 15px;
        border: 1px solid rgba(220, 38, 38, 0.18);
        text-align: center;
        box-shadow: 0 2px 8px rgba(220, 38, 38, 0.10);
    }

    .live-time .time {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--redcode-primary);
        margin-bottom: 0.25rem;
    }

    .live-time .date {
        font-size: 0.875rem;
        color: var(--text-secondary);
    }

    /* Enhanced Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2.5rem;
    }

    .stat-card {
        background: #fff;
        border: 1px solid var(--divider);
        border-radius: 20px;
        padding: 2rem;
        position: relative;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 2px 12px 0 rgba(37,99,235,0.04);
        cursor: pointer;
        group: hover;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, transparent 0%, rgba(220, 38, 38, 0.05) 100%);
        opacity: 0;
        transition: opacity 0.4s ease;
    }

    .stat-card:hover::before {
        opacity: 1;
    }

    .stat-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 8px 32px 0 rgba(37,99,235,0.10);
    }

    .stat-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1.5rem;
    }

    .stat-card-icon {
        width: 60px;
        height: 60px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .stat-card-icon::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: inherit;
        filter: blur(10px);
        opacity: 0.3;
        transform: scale(1.2);
    }

    .stat-card-icon i {
        position: relative;
        z-index: 1;
    }

    .stat-card-employees .stat-card-icon {
        background: linear-gradient(135deg, var(--redcode-primary) 0%, var(--redcode-dark) 100%);
    }

    .stat-card-projects .stat-card-icon {
        background: linear-gradient(135deg, var(--redcode-blue) 0%, var(--redcode-primary) 100%);
    }

    .stat-card-tasks .stat-card-icon {
        background: linear-gradient(135deg, var(--redcode-blue) 0%, var(--redcode-dark) 100%);
    }

    .stat-card-revenue .stat-card-icon {
        background: linear-gradient(135deg, var(--redcode-green) 0%, var(--redcode-dark) 100%);
    }

    .stat-card-efficiency .stat-card-icon {
        background: linear-gradient(135deg, var(--redcode-orange) 0%, var(--redcode-dark) 100%);
    }

    .stat-card-departments .stat-card-icon {
        background: linear-gradient(135deg, var(--redcode-blue) 0%, var(--redcode-primary) 100%);
    }

    .stat-card-admins .stat-card-icon {
        background: linear-gradient(135deg, var(--redcode-primary) 0%, var(--redcode-accent) 100%);
    }

    .stat-card-notifications .stat-card-icon {
        background: linear-gradient(135deg, var(--redcode-orange) 0%, var(--redcode-primary) 100%);
    }

    .stat-card-joinings .stat-card-icon {
        background: linear-gradient(135deg, var(--redcode-green) 0%, var(--redcode-primary) 100%);
    }

    .stat-card-leaves .stat-card-icon {
        background: linear-gradient(135deg, var(--redcode-primary) 0%, var(--redcode-accent) 100%);
    }

    .stat-card-title {
        color: var(--text-secondary);
        font-size: 0.875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .stat-card-value {
        color: var(--text-primary);
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
        position: relative;
        letter-spacing: 0.01em;
    }

    .stat-card-change {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        font-weight: 600;
    }

    .stat-card-change.positive {
        color: var(--success);
    }

    .stat-card-change.negative {
        color: var(--danger);
    }

    .stat-card-change.neutral {
        color: var(--warning);
    }

    .stat-card-change.warning {
        color: var(--warning);
    }

    .stat-card-progress {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--divider);
        overflow: hidden;
    }

    .stat-card-progress-bar {
        height: 100%;
        background: linear-gradient(90deg, var(--redcode-primary) 60%, var(--redcode-dark) 100%);
        transition: width 2s ease-in-out;
        position: relative;
    }

    .stat-card-progress-bar::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
        animation: shimmer 2s infinite;
    }

    @keyframes shimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }

    /* Charts Section */
    .charts-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
        margin-bottom: 2.5rem;
    }

    .chart-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 20px;
        padding: 2rem;
        border: 1px solid #e5e7eb;
        box-shadow: 0 2px 12px 0 rgba(37,99,235,0.04);
        position: relative;
        overflow: hidden;
    }

    .chart-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(90deg, #2563eb, #1d4ed8);
    }

    .chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .chart-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e293b;
        letter-spacing: 0.01em;
    }

    .chart-controls {
        display: flex;
        gap: 0.5rem;
    }

    .chart-control-btn {
        padding: 0.5rem 1rem;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        background: rgba(102, 126, 234, 0.1);
        color: #667eea;
    }

    .chart-control-btn.active {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        color: #fff;
        border: 1px solid #2563eb;
    }

    .chart-control-btn:hover {
        transform: translateY(-2px);
    }

    /* Activity Feed */
    .activity-section {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
        margin-bottom: 2.5rem;
    }

    .activity-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 20px;
        padding: 2rem;
        border: 1px solid #e5e7eb;
        box-shadow: 0 2px 12px 0 rgba(37,99,235,0.04);
    }

    .activity-item {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        padding: 1rem;
        border-radius: 15px;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        background: #f9fafb;
        border: 1px solid #e5e7eb;
    }

    .activity-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(220, 38, 38, 0.05) 0%, rgba(31, 41, 55, 0.05) 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .activity-item:hover::before {
        opacity: 1;
    }

    .activity-item:hover {
        transform: translateX(8px);
    }

    .activity-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1rem;
        position: relative;
        z-index: 1;
    }

    .activity-content {
        flex: 1;
        position: relative;
        z-index: 1;
    }

    .activity-title {
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.25rem;
    }

    .activity-description {
        font-size: 0.875rem;
        color: #64748b;
        margin-bottom: 0.5rem;
    }

    .activity-time {
        font-size: 0.75rem;
        color: #94a3b8;
    }

    /* Quick Actions */
    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .quick-action {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 15px;
        padding: 1.5rem;
        text-align: center;
        text-decoration: none;
        color: inherit;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid #e5e7eb;
        box-shadow: 0 1px 4px 0 rgba(37,99,235,0.04);
        position: relative;
        overflow: hidden;
    }

    .quick-action::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(220, 38, 38, 0.1) 0%, rgba(31, 41, 55, 0.1) 100%);
        opacity: 0;
        transition: opacity 0.4s ease;
    }

    .quick-action:hover::before {
        opacity: 1;
    }

    .quick-action:hover {
        transform: translateY(-5px) scale(1.05);
        box-shadow: 0 8px 24px 0 rgba(37,99,235,0.10);
    }

    .quick-action-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        font-size: 1.25rem;
        color: white;
        position: relative;
        z-index: 1;
    }

    .quick-action-title {
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.5rem;
        position: relative;
        z-index: 1;
    }

    .quick-action-description {
        font-size: 0.875rem;
        color: #64748b;
        position: relative;
        z-index: 1;
    }

    /* Enhanced Table */
    .enhanced-table {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 20px;
        overflow: hidden;
        border: 1px solid #e5e7eb;
        box-shadow: 0 2px 12px 0 rgba(37,99,235,0.04);
    }

    .enhanced-table .table {
        margin: 0;
    }

    .enhanced-table .table th {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        font-weight: 700;
        color: #334155;
        padding: 1.5rem;
        border: none;
        position: relative;
        border-bottom: 2px solid #e5e7eb;
    }

    .enhanced-table .table th::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(90deg, #667eea, #764ba2);
    }

    .enhanced-table .table td {
        padding: 1.5rem;
        border: none;
        border-bottom: 1px solid #e5e7eb;
        position: relative;
    }

    .enhanced-table .table tbody tr {
        transition: all 0.3s ease;
    }

    .enhanced-table .table tbody tr:hover {
        background: #f3f4f6;
        transform: scale(1.01);
    }

    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        position: relative;
        overflow: hidden;
        box-shadow: 0 1px 4px 0 rgba(37,99,235,0.04);
    }

    .status-badge::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: left 0.5s ease;
    }

    .status-badge:hover::before {
        left: 100%;
    }

    .status-success {
        background: linear-gradient(135deg, var(--success) 0%, #059669 100%);
    }

    .status-warning {
        background: linear-gradient(135deg, var(--warning) 0%, #f59e42 100%);
    }

    .status-info {
        background: linear-gradient(135deg, var(--info) 0%, #0369a1 100%);
    }

    .status-primary {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
    }

    /* Animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
    }

    .animate-fade-in-up {
        animation: fadeInUp 0.6s ease-out;
    }

    .animate-pulse {
        animation: pulse 2s infinite;
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
        .charts-grid {
            grid-template-columns: 1fr;
        }

        .activity-section {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .dashboard-container {
            padding: 1rem;
        }

        .dashboard-header {
            padding: 1.5rem;
        }

        .dashboard-title {
            font-size: 2rem;
        }

        .live-time {
            position: static;
            margin-top: 1rem;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .stat-card {
            padding: 1.5rem;
        }
    }

    /* Sidebar Styles */
    .admin-sidebar {
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        width: 220px;
        background: var(--redcode-dark);
        color: #fff;
        z-index: 100;
        box-shadow: 2px 0 8px rgba(0,0,0,0.04);
        display: flex;
        flex-direction: column;
    }

    .admin-sidebar .sidebar-header {
        padding: 2rem 1rem;
        font-size: 1.5rem;
        font-weight: 800;
        letter-spacing: 0.05em;
        background: var(--redcode-primary);
        text-align: center;
    }

    .admin-sidebar nav {
        flex: 1;
        padding: 1rem 0;
    }

    .admin-sidebar ul {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .admin-sidebar li {
        margin-bottom: 1rem;
    }

    .admin-sidebar a {
        color: #fff;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        transition: background 0.3s ease;
    }

    .admin-sidebar a:hover {
        background: rgba(255, 255, 255, 0.1);
    }

    .admin-sidebar .active {
        background: rgba(255, 255, 255, 0.2);
    }

    /* Meeting Section Styles */
    .meeting-section {
        margin-bottom: 2rem;
    }

    .meeting-header-section {
        margin-bottom: 1.5rem;
    }

    .meeting-section-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 0.5rem;
    }

    .meeting-section-title i {
        color: var(--redcode-primary);
    }

    .meeting-section-subtitle {
        color: var(--text-secondary);
        font-size: 1rem;
        margin: 0;
    }

    .meetings-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 1.5rem;
    }

    .meeting-card {
        background: white;
        border-radius: 1rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        padding: 1.5rem;
        border: 1px solid var(--border-light);
        transition: all 0.3s ease;
    }

    .meeting-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    }

    .meeting-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .meeting-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .meeting-title i {
        color: var(--redcode-primary);
    }

    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 2rem;
        font-size: 0.875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .status-badge.scheduled {
        background: var(--redcode-yellow);
        color: #92400e;
    }

    .status-badge.ongoing {
        background: var(--redcode-green);
        color: white;
    }

    .meeting-content {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 2rem;
        align-items: center;
    }

    .meeting-info {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .meeting-time, .meeting-duration {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 1rem;
        color: var(--text-secondary);
    }

    .meeting-time i, .meeting-duration i {
        color: var(--redcode-primary);
        width: 1rem;
    }

    .meeting-link-section {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .meeting-link-display {
        display: flex;
        gap: 0.75rem;
        align-items: center;
    }

    .meeting-link-input {
        flex: 1;
        padding: 0.75rem 1rem;
        border: 2px solid var(--border-medium);
        border-radius: 0.5rem;
        font-size: 0.875rem;
        background: var(--gray-50);
        color: var(--text-primary);
        font-family: 'Courier New', monospace;
    }

    .copy-btn {
        padding: 0.75rem 1rem;
        background: var(--gray-600);
        color: white;
        border: none;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .copy-btn:hover {
        background: var(--gray-700);
        transform: translateY(-1px);
    }

    .join-meeting-btn {
        padding: 1rem 1.5rem;
        background: linear-gradient(135deg, var(--redcode-primary) 0%, var(--redcode-accent) 100%);
        color: white;
        text-decoration: none;
        border-radius: 0.5rem;
        font-weight: 600;
        text-align: center;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        box-shadow: 0 4px 15px rgba(220, 38, 38, 0.3);
    }

    .join-meeting-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(220, 38, 38, 0.4);
        color: white;
    }

    @media (max-width: 768px) {
        .meeting-content {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .meeting-link-display {
            flex-direction: column;
        }
    }
</style>

<div class="dashboard-container">
    <div class="dashboard-content">
        <!-- Enhanced Header -->
        <div class="dashboard-header animate-fade-in-up">
            <div class="dashboard-title">
                <i class="fas fa-tachometer-alt"></i>
                Admin Dashboard
            </div>
            <div class="dashboard-subtitle">
                Welcome back, {{ auth()->user()?->name ?? 'Admin' }}
                        <i class="fas fa-tasks"></i>
                    </div>
                </div>
                <div class="stat-card-progress">
                    <div class="stat-card-progress-bar" style="width: 60%"></div>
                </div>
            </div>

            <div class="stat-card stat-card-revenue animate-fade-in-up" style="animation-delay: 0.4s">
                <div class="stat-card-header">
                    <div>
</style>
                        <div class="stat-card-value">{{ $revenue ?? '$847K' }}</div>
                        <div class="stat-card-change positive">
                            <i class="fas fa-arrow-up"></i>
                            <span>+18% from last month</span>
                        </div>
                    </div>
                    <div class="stat-card-icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>
                <div class="stat-card-progress">
                    <div class="stat-card-progress-bar" style="width: 92%"></div>
                </div>
            </div>

            <div class="stat-card stat-card-efficiency animate-fade-in-up" style="animation-delay: 0.5s">
                <div class="stat-card-header">
                    <div>
                        <div class="stat-card-title">Overall Efficiency</div>
                        <div class="stat-card-value">{{ $efficiency ?? '94.2%' }}</div>
                        <div class="stat-card-change positive">
                            <i class="fas fa-arrow-up"></i>
                            <span>+2.4% improvement</span>
                        </div>
                    </div>
                    <div class="stat-card-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
                <div class="stat-card-progress">
                    <div class="stat-card-progress-bar" style="width: 94%"></div>
                </div>
            </div>

            <div class="stat-card stat-card-departments animate-fade-in-up" style="animation-delay: 0.6s">
                <div class="stat-card-header">
                    <div>
                        <div class="stat-card-title">Departments</div>
                        <div class="stat-card-value counter" data-target="12">0</div>
                        <div class="stat-card-change positive">
                            <i class="fas fa-arrow-up"></i>
                            <span>+2 new departments</span>
                        </div>
                    </div>
                    <div class="stat-card-icon">
                        <i class="fas fa-building"></i>
                    </div>
                </div>
                <div class="stat-card-progress">
                    <div class="stat-card-progress-bar" style="width: 88%"></div>
                </div>
            </div>

            <div class="stat-card stat-card-admins animate-fade-in-up" style="animation-delay: 0.7s">
                <div class="stat-card-header">
                    <div>
                        <div class="stat-card-title">Admin Staff</div>
                        <div class="stat-card-value counter" data-target="8">0</div>
                        <div class="stat-card-change positive">
                            <i class="fas fa-arrow-up"></i>
                            <span>Active admins</span>
                        </div>
                    </div>
                    <div class="stat-card-icon">
                        <i class="fas fa-user-shield"></i>
                    </div>
                </div>
                <div class="stat-card-progress">
                    <div class="stat-card-progress-bar" style="width: 75%"></div>
                </div>
            </div>

            <div class="stat-card stat-card-notifications animate-fade-in-up" style="animation-delay: 0.8s">
                <div class="stat-card-header">
                    <div>
                        <div class="stat-card-title">Notifications</div>
                        <div class="stat-card-value counter" data-target="23">0</div>
                        <div class="stat-card-change warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            <span>7 unread</span>
                        </div>
                    </div>
                    <div class="stat-card-icon">
                        <i class="fas fa-bell"></i>
                    </div>
                </div>
                <div class="stat-card-progress">
                    <div class="stat-card-progress-bar" style="width: 65%"></div>
                </div>
            </div>

            <div class="stat-card stat-card-joinings animate-fade-in-up" style="animation-delay: 0.6s">
                <div class="stat-card-header">
                    <div>
                        <div class="stat-card-title">New Joinings</div>
                        <div class="stat-card-value counter" data-target="{{ $newJoinings ?? 8 }}">0</div>
                        <div class="stat-card-change positive">
                            <i class="fas fa-user-plus"></i>
                            <span>This month</span>
                        </div>
                    </div>
                    <div class="stat-card-icon">
                        <i class="fas fa-user-plus"></i>
                    </div>
                </div>
                <div class="stat-card-progress">
                    <div class="stat-card-progress-bar" style="width: 67%"></div>
                </div>
            </div>

            <div class="stat-card stat-card-leaves animate-fade-in-up" style="animation-delay: 0.7s">
                <div class="stat-card-header">
                    <div>
                        <div class="stat-card-title">Pending Leaves</div>
                        <div class="stat-card-value counter" data-target="{{ $pendingLeaves ?? 12 }}">0</div>
                        <div class="stat-card-change neutral">
                            <i class="fas fa-clock"></i>
                            <span>Awaiting approval</span>
                        </div>
                    </div>
                    <div class="stat-card-icon">
                        <i class="fas fa-calendar-times"></i>
                    </div>
                </div>
                <div class="stat-card-progress">
                    <div class="stat-card-progress-bar" style="width: 45%"></div>
                </div>
            </div>
        </div>

        <!-- Today's Meetings Section -->
        @if(isset($todayMeetings) && $todayMeetings->count() > 0)
        <div class="meeting-section animate-fade-in-up" style="animation-delay: 0.8s; margin-bottom: 2rem;">
            <div class="meeting-header-section">
                <h2 class="meeting-section-title">
                    <i class="fas fa-video"></i>
                    Today's Stand-up Meetings
                </h2>
                <p class="meeting-section-subtitle">Morning and Evening meetings for all team members</p>
            </div>

            <div class="meetings-grid">
                @foreach($todayMeetings as $meeting)
                <div class="meeting-card">
                    <div class="meeting-header">
                        <div class="meeting-title">
                            <i class="fas fa-{{ str_contains(strtolower($meeting->title), 'morning') ? 'sun' : 'moon' }}"></i>
                            {{ $meeting->title }}
                        </div>
                        <div class="meeting-status">
                            <span class="status-badge {{ $meeting->status === 'ongoing' ? 'ongoing' : 'scheduled' }}">
                                {{ ucfirst($meeting->status) }}
                            </span>
                        </div>
                    </div>
                    <div class="meeting-content">
                        <div class="meeting-info">
                            <div class="meeting-time">
                                <i class="fas fa-clock"></i>
                                {{ $meeting->getFormattedTime() }}
                            </div>
                            <div class="meeting-duration">
                                <i class="fas fa-hourglass-half"></i>
                                {{ $meeting->getDuration() }} minutes
                            </div>
                        </div>
                        <div class="meeting-link-section">
                            <div class="meeting-link-display">
                                <input type="text" value="{{ $meeting->meeting_link }}"
                                       class="meeting-link-input" readonly>
                                <button onclick="copyToClipboard('{{ $meeting->meeting_link }}')"
                                        class="copy-btn">
                                    <i class="fas fa-copy"></i> Copy
                                </button>
                            </div>
                            <a href="{{ route('meetings.join', $meeting) }}"
                               class="join-meeting-btn">
                                <i class="fas fa-external-link-alt"></i>
                                Join Meeting
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Enhanced Charts Section -->
        <div class="charts-grid">
            <div class="chart-card animate-fade-in-up" style="animation-delay: 0.8s">
                <div class="chart-header">
                    <div class="chart-title">Performance Analytics</div>
                    <div class="chart-controls">
                        <button class="chart-control-btn active">6M</button>
                        <button class="chart-control-btn">1Y</button>
                        <button class="chart-control-btn">All</button>
                    </div>
                </div>
                <div style="height: 400px;">
                    <canvas id="performanceChart"></canvas>
                </div>
            </div>

            <div class="chart-card animate-fade-in-up" style="animation-delay: 0.9s">
                <div class="chart-header">
                    <div class="chart-title">Department Distribution</div>
                </div>
                <div style="height: 400px;">
                    <canvas id="departmentChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Activity and Quick Actions -->
        <div class="activity-section">
            <div class="activity-card animate-fade-in-up" style="animation-delay: 1.0s">
                <div class="chart-header">
                    <div class="chart-title">Recent Activities</div>
                    <a href="{{ route('admin.notifications') }}" class="btn btn-primary">View All</a>
                </div>

                @php
                    $activities = [
                        ['icon' => 'user-plus', 'title' => 'New Employee Onboarded', 'desc' => 'John Doe joined Development Team', 'time' => '2 hours ago', 'bg' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)'],
                        ['icon' => 'check-circle', 'title' => 'Project Milestone Reached', 'desc' => 'Website Redesign - 75% completed', 'time' => '4 hours ago', 'bg' => 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)'],
                        ['icon' => 'calendar', 'title' => 'Leave Request Submitted', 'desc' => 'Sarah Wilson - Medical Leave (3 days)', 'time' => '6 hours ago', 'bg' => 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)'],
                        ['icon' => 'file-alt', 'title' => 'Document Uploaded', 'desc' => 'Q4 Financial Report uploaded', 'time' => '8 hours ago', 'bg' => 'linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)'],
                        ['icon' => 'bell', 'title' => 'System Notification', 'desc' => 'Daily backup completed successfully', 'time' => '12 hours ago', 'bg' => 'linear-gradient(135deg, #fa709a 0%, #fee140 100%)'],
                    ];
                @endphp

                @foreach($activities as $activity)
                <div class="activity-item">
                    <div class="activity-icon" style="background: {{ $activity['bg'] }}">
                        <i class="fas fa-{{ $activity['icon'] }}"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">{{ $activity['title'] }}</div>
                        <div class="activity-description">{{ $activity['desc'] }}</div>
                        <div class="activity-time">{{ $activity['time'] }}</div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="activity-card animate-fade-in-up" style="animation-delay: 1.1s">
                <div class="chart-header">
                    <div class
                </div>
                <div class="quick-actions">
                    <a href="{{ route('employees.create') }}" class="quick-action">
                        <div class="quick-action-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%)">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div class="quick-action-title">Add Employee</div>
                        <div class="quick-action-description">Create new employee profile</div>
                    </a>

                    <a href="{{ route('admin.projects.create') }}" class="quick-action">
                        <div class="quick-action-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%)">
                            <i class="fas fa-project-diagram"></i>
                        </div>
                        <div class="quick-action-title">New Project</div>
                        <div class="quick-action-description">Start a new project</div>
                    </a>

                    <a href="{{ route('leaves.index') }}" class="quick-action">
                        <div class="quick-action-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="quick-action-title">Review Leaves</div>
                        <div class="quick-action-description">Approve/Reject requests</div>
                    </a>

                    <a href="{{ route('admin.reports') }}" class="quick-action">
                        <div class="quick-action-icon" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <div class="quick-action-title">View Reports</div>
                        <div class="quick-action-description">Analytics & insights</div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Enhanced Recent Activities Table -->
        <div class="enhanced-table animate-fade-in-up" style="animation-delay: 1.2s">
            <div class="card-header">
                <h2><i class="fas fa-history"></i> Recent Activities</h2>
                <div class="chart-controls">
                    <button class="chart-control-btn active">Today</button>
                    <button class="chart-control-btn">Week</button>
                    <button class="chart-control-btn">Month</button>
                </div>
            </div>
            <div class="card-body" style="padding: 0;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date & Time</th>
                            <th>User</th>
                            <th>Activity</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div style="font-weight: 600;">2024-06-01</div>
                                <div style="font-size: 0.875rem; color: #64748b;">14:30 PM</div>
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <div style="width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 0.875rem;">
                                        JD
                                    </div>
                                    <div>
                                        <div style="font-weight: 600;">Jane Doe</div>
                                        <div style="font-size: 0.875rem; color: #64748b;">HR Manager</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div style="font-weight: 600;">Added new employee</div>
                                <div style="font-size: 0.875rem; color: #64748b;">Created profile for John Smith</div>
                            </td>
                            <td><span class="status-badge status-success">Success</span></td>
                            <td>
                                <button class="btn btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.875rem;">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div style="font-weight: 600;">2024-06-01</div>
                                <div style="font-size: 0.875rem; color: #64748b;">13:15 PM</div>
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <div style="width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 0.875rem;">
                                        JS
                                    </div>
                                    <div>
                                        <div style="font-weight: 600;">John Smith</div>
                                        <div style="font-size: 0.875rem; color: #64748b;">Developer</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div style="font-weight: 600;">Requested leave</div>
                                <div style="font-size: 0.875rem; color: #64748b;">Annual leave for 5 days</div>
                            </td>
                            <td><span class="status-badge status-warning">Pending</span></td>
                            <td>
                                <button class="btn btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.875rem;">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div style="font-weight: 600;">2024-05-31</div>
                                <div style="font-size: 0.875rem; color: #64748b;">16:45 PM</div>
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <div style="width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 0.875rem;">
                                        EC
                                    </div>
                                    <div>
                                        <div style="font-weight: 600;">Emily Clark</div>
                                        <div style="font-size: 0.875rem; color: #64748b;">Project Manager</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div style="font-weight: 600;">Uploaded document</div>
                                <div style="font-size: 0.875rem; color: #64748b;">Project requirements document</div>
                            </td>
                            <td><span class="status-badge status-info">Info</span></td>
                            <td>
                                <button class="btn btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.875rem;">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div style="font-weight: 600;">2024-05-30</div>
                                <div style="font-size: 0.875rem; color: #64748b;">11:20 AM</div>
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <div style="width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 0.875rem;">
                                        ML
                                    </div>
                                    <div>
                                        <div style="font-weight: 600;">Michael Lee</div>
                                        <div style="font-size: 0.875rem; color: #64748b;">Team Lead</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div style="font-weight: 600;">Project completed</div>
                                <div style="font-size: 0.875rem; color: #64748b;">Mobile app development finished</div>
                            </td>
                            <td><span class="status-badge status-primary">Completed</span></td>
                            <td>
                                <button class="btn btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.875rem;">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Live time update
function updateTime() {
    const now = new Date();
    const timeElement = document.getElementById('currentTime');
    if (timeElement) {
        timeElement.textContent = now.toLocaleTimeString('en-US', {
            hour12: false,
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });
    }
}
setInterval(updateTime, 1000);

// Counter animation
function animateCounters() {
    const counters = document.querySelectorAll('.counter');
    counters.forEach(counter => {
        const target = parseInt(counter.getAttribute('data-target'));
        const duration = 2000;
        const step = target / (duration / 16);
        let current = 0;

        const timer = setInterval(() => {
            current += step;
            if (current >= target) {
                counter.textContent = target;
                clearInterval(timer);
            } else {
                counter.textContent = Math.floor(current);
            }
        }, 16);
    });
}

// Initialize animations when page loads
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(animateCounters, 500);
});

// Enhanced Performance Chart
const performanceCtx = document.getElementById('performanceChart').getContext('2d');
const performanceGradient = performanceCtx.createLinearGradient(0, 0, 0, 400);
performanceGradient.addColorStop(0, 'rgba(220, 38, 38, 0.3)');
performanceGradient.addColorStop(1, 'rgba(220, 38, 38, 0.05)');

const performanceGradient2 = performanceCtx.createLinearGradient(0, 0, 0, 400);
performanceGradient2.addColorStop(0, 'rgba(37, 99, 235, 0.3)');
performanceGradient2.addColorStop(1, 'rgba(37, 99, 235, 0.05)');

new Chart(performanceCtx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [{
            label: 'Efficiency',
            data: [85, 88, 90, 92, 89, 94, 96, 93, 95, 97, 94, 96],
            borderColor: '#DC2626',
            backgroundColor: performanceGradient,
            borderWidth: 3,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#DC2626',
            pointBorderColor: '#ffffff',
            pointBorderWidth: 3,
            pointRadius: 6,
            pointHoverRadius: 8
        }, {
            label: 'Revenue Growth',
            data: [70, 75, 78, 82, 85, 88, 90, 87, 92, 95, 93, 98],
            borderColor: '#2563EB',
            backgroundColor: performanceGradient2,
            borderWidth: 3,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#2563EB',
            pointBorderColor: '#ffffff',
            pointBorderWidth: 3,
            pointRadius: 6,
            pointHoverRadius: 8
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: {
            intersect: false,
            mode: 'index'
        },
        plugins: {
            legend: {
                position: 'top',
                labels: {
                    usePointStyle: true,
                    padding: 20,
                    font: {
                        size: 14,
                        weight: '600'
                    }
                }
            },
            tooltip: {
                backgroundColor: 'rgba(255, 255, 255, 0.95)',
                titleColor: '#1e293b',
                bodyColor: '#64748b',
                borderColor: '#e2e8f0',
                borderWidth: 1,
                cornerRadius: 12,
                displayColors: true,
                titleFont: {
                    size: 14,
                    weight: 'bold'
                },
                bodyFont: {
                    size: 13
                },
                padding: 12
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                max: 100,
                grid: {
                    color: 'rgba(0, 0, 0, 0.05)',
                    drawBorder: false
                },
                ticks: {
                    padding: 10,
                    font: {
                        size: 12
                    },
                    callback: function(value) {
                        return value + '%';
                    }
                }
            },
            x: {
                grid: {
                    display: false,
                    drawBorder: false
                },
                ticks: {
                    padding: 10,
                    font: {
                        size: 12
                    }
                }
            }
        }
    }
});

// Enhanced Department Chart
const departmentCtx = document.getElementById('departmentChart').getContext('2d');
new Chart(departmentCtx, {
    type: 'doughnut',
    data: {
        labels: ['Engineering', 'Marketing', 'Sales', 'HR', 'Finance', 'Operations'],
        datasets: [{
            data: [89, 34, 28, 15, 22, 31],
            backgroundColor: [
                '#DC2626',  // RedCode Primary
                '#2563EB',  // RedCode Blue
                '#059669',  // RedCode Green
                '#D97706',  // RedCode Orange
                '#1F2937',  // RedCode Dark
                '#B91C1C'   // RedCode Accent
            ],
            borderWidth: 0,
            hoverOffset: 15
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '60%',
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    usePointStyle: true,
                    padding: 20,
                    font: {
                        size: 12,
                        weight: '600'
                    }
                }
            },
            tooltip: {
                backgroundColor: 'rgba(255, 255, 255, 0.95)',
                titleColor: '#1e293b',
                bodyColor: '#64748b',
                borderColor: '#e2e8f0',
                borderWidth: 1,
                cornerRadius: 12,
                displayColors: true,
                titleFont: {
                    size: 14,
                    weight: 'bold'
                },
                bodyFont: {
                    size: 13
                },
                padding: 12,
                callbacks: {
                    label: function(context) {
                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                        const percentage = ((context.parsed / total) * 100).toFixed(1);
                        return `${context.label}: ${context.parsed} (${percentage}%)`;
                    }
                }
            }
        }
    }
});

// Chart control buttons
document.querySelectorAll('.chart-control-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        // Remove active class from siblings
        this.parentNode.querySelectorAll('.chart-control-btn').forEach(sibling => {
            sibling.classList.remove('active');
        });
        // Add active class to clicked button
        this.classList.add('active');
    });
});

// Add hover effects to stat cards
document.querySelectorAll('.stat-card').forEach(card => {
    card.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-8px) scale(1.02)';
    });

    card.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0) scale(1)';
    });
});

// Smooth scroll for quick actions
document.querySelectorAll('.quick-action').forEach(action => {
    action.addEventListener('click', function(e) {
        // Add a subtle animation before navigation
        this.style.transform = 'scale(0.95)';
        setTimeout(() => {
            this.style.transform = 'scale(1)';
        }, 150);
    });
});

// Copy to clipboard function for meeting links
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Show success message
        const copyBtn = event.target.closest('.copy-btn');
        if (copyBtn) {
            const originalText = copyBtn.innerHTML;
            copyBtn.innerHTML = '<i class="fas fa-check"></i> Copied!';
            copyBtn.style.background = '#059669';
            setTimeout(() => {
                copyBtn.innerHTML = originalText;
                copyBtn.style.background = '';
            }, 2000);
        }
    }).catch(function(err) {
        console.error('Could not copy text: ', err);
        alert('Failed to copy meeting link. Please copy manually.');
    });
}
</script>
@endpush
@endsection
