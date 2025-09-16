<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RedCode Solutions - Employee Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            /* RedCode Solutions Color Palette */
            /* Primary Red Colors - Core Brand */
            --redcode-primary: #DC2626;
            /* RedCode Brand Red */
            --redcode-primary-dark: #991B1B;
            /* Deep Red */
            --redcode-primary-light: #FEE2E2;
            /* Light Red Background */
            --redcode-accent: #B91C1C;
            /* Accent Red */

            /* Secondary Colors - Professional Palette */
            --redcode-dark: #1F2937;
            /* Charcoal for headers/nav */
            --redcode-gray: #6B7280;
            /* Medium Gray for text */
            --redcode-light: #F9FAFB;
            /* Light Background */
            --redcode-white: #FFFFFF;
            /* Pure White */

            /* Status Colors */
            --redcode-blue: #2563EB;
            /* Links, buttons */
            --redcode-green: #059669;
            /* Success states */
            --redcode-orange: #D97706;
            /* Warnings */
            --redcode-yellow: #F59E0B;
            /* Alerts */

            /* Text Colors */
            --text-primary: #111827;
            /* Almost Black */
            --text-secondary: #6B7280;
            /* Medium Gray */
            --text-light: #9CA3AF;
            /* Light Gray */
            --text-white: #FFFFFF;
            /* White Text */

            /* Background Colors */
            --bg-primary: #FFFFFF;
            /* White Background */
            --bg-secondary: #F9FAFB;
            /* Light Gray Background */
            --bg-dark: #1F2937;
            /* Dark Background */

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
            --gradient-glass: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);

            /* RedCode Shadows */
            --shadow-xs: 0 1px 2px 0 rgba(220, 38, 38, 0.05);
            --shadow-sm: 0 1px 3px 0 rgba(220, 38, 38, 0.1), 0 1px 2px 0 rgba(220, 38, 38, 0.06);
            --shadow-md: 0 4px 6px -1px rgba(220, 38, 38, 0.1), 0 2px 4px -1px rgba(220, 38, 38, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(220, 38, 38, 0.1), 0 4px 6px -2px rgba(220, 38, 38, 0.05);
            --shadow-xl: 0 20px 25px -5px rgba(220, 38, 38, 0.1), 0 10px 10px -5px rgba(220, 38, 38, 0.04);
            --shadow-2xl: 0 25px 50px -12px rgba(220, 38, 38, 0.25);
        }

        * {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .login-container {
            background: var(--gradient-hero);
            background-size: 400% 400%;
            animation: gradientShift 18s ease infinite;
            min-height: 100vh;
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

        .login-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background:
                radial-gradient(circle at 25% 75%, rgba(220, 38, 38, 0.18) 0%, transparent 50%),
                radial-gradient(circle at 75% 25%, rgba(31, 41, 55, 0.18) 0%, transparent 50%),
                radial-gradient(circle at 50% 50%, rgba(153, 27, 27, 0.10) 0%, transparent 50%);
            animation: floatBackground 25s ease-in-out infinite;
        }

        @keyframes floatBackground {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            33% {
                transform: translateY(-20px) rotate(120deg);
            }

            66% {
                transform: translateY(10px) rotate(240deg);
            }
        }

        .login-split {
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 100vh;
            position: relative;
            z-index: 1;
        }

        .login-form-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(30px);
            border-right: 1px solid rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem 1rem;
            position: relative;
        }

        .login-form-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
            pointer-events: none;
        }

        .login-illustration {
            background: linear-gradient(135deg, rgba(220, 38, 38, 0.1) 0%, rgba(153, 27, 27, 0.1) 100%);
            backdrop-filter: blur(20px);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border-radius: 1.5rem;
            padding: 1.2rem 1.2rem;
            width: 100%;
            max-width: 480px;
            font-size: 0.85rem;
            box-shadow:
                0 32px 64px rgba(220, 38, 38, 0.12),
                0 0 0 1px rgba(255, 255, 255, 0.05),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
            position: relative;
            z-index: 10;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
            border-radius: 1.5rem;
            pointer-events: none;
        }

        .hrms-logo {
            text-align: center;
            margin-bottom: 2rem;
            position: relative;
        }

        .hrms-title {
            font-size: 3rem;
            font-weight: 900;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: 0.1em;
            text-shadow: 0 4px 20px rgba(220, 38, 38, 0.3);
            position: relative;
        }

        .hrms-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 4px;
            background: var(--gradient-primary);
            border-radius: 2px;
        }

        .user-avatar {
            width: 80px;
            height: 80px;
            background: var(--gradient-primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            box-shadow:
                0 20px 40px rgba(220, 38, 38, 0.2),
                0 0 0 4px rgba(255, 255, 255, 0.1),
                inset 0 2px 0 rgba(255, 255, 255, 0.2);
            position: relative;
            animation: pulse 3s ease-in-out infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }
        }

        .signin-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .signin-title {
            font-size: 1.2rem;
            font-weight: 800;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
            letter-spacing: -0.025em;
        }

        .signin-subtitle {
            color: var(--text-secondary);
            font-size: 0.7rem;
            font-weight: 500;
        }

        .role-tabs {
            display: flex;
            background: rgba(248, 250, 252, 0.8);
            backdrop-filter: blur(10px);
            border-radius: 1rem;
            padding: 2px;
            margin-bottom: 1rem;
            border: 1px solid var(--border-color);
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.06);
            min-height: 36px;
        }

        .role-tab {
            flex: 1;
            padding: 6px 10px;
            text-align: center;
            border-radius: 0.75rem;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-weight: 600;
            font-size: 0.8rem;
            letter-spacing: 0.025em;
            position: relative;
            overflow: hidden;
            min-height: 28px;
            line-height: 1.2;
        }

        .role-tab::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: left 0.5s;
        }

        .role-tab.active {
            background: var(--gradient-primary);
            color: #fff;
            box-shadow:
                0 8px 25px rgba(220, 38, 38, 0.3),
                0 3px 10px rgba(220, 38, 38, 0.2);
            transform: translateY(-2px);
        }

        .role-tab.active::before {
            left: 100%;
        }

        .role-tab:not(.active) {
            color: var(--text-secondary);
            background: transparent;
        }

        .role-tab:not(.active):hover {
            background: rgba(220, 38, 38, 0.1);
            color: var(--redcode-primary);
            transform: translateY(-1px);
        }

        .form-group {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .form-input {
            width: 100%;
            padding: 8px 18px 8px 44px;
            border: 2px solid var(--border-color);
            border-radius: 0.7rem;
            font-size: 0.95rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: rgba(248, 250, 252, 0.5);
            backdrop-filter: blur(10px);
            color: var(--text-primary);
            font-weight: 500;
            position: relative;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--redcode-primary);
            background: rgba(255, 255, 255, 0.9);
            box-shadow:
                0 0 0 4px rgba(220, 38, 38, 0.1),
                0 8px 25px rgba(220, 38, 38, 0.15);
            transform: translateY(-2px);
        }

        .form-input::placeholder {
            color: var(--gray-400);
            font-weight: 400;
        }

        .input-icon {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-400);
            transition: all 0.3s;
            z-index: 2;
        }

        .form-input:focus+.input-icon {
            color: var(--redcode-primary);
            transform: translateY(-50%) scale(1.1);
        }

        .login-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .checkbox-wrapper {
            display: flex;
            align-items: center;
            cursor: pointer;
            user-select: none;
        }

        .checkbox-custom {
            appearance: none;
            width: 22px;
            height: 22px;
            border: 2px solid var(--gray-300);
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.8);
            position: relative;
            cursor: pointer;
            transition: all 0.3s;
            margin-right: 12px;
        }

        .checkbox-custom:checked {
            background: var(--gradient-primary);
            border-color: var(--redcode-primary);
            transform: scale(1.05);
        }

        .checkbox-custom:checked::after {
            content: '✓';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #fff;
            font-size: 14px;
            font-weight: bold;
        }

        .checkbox-label {
            font-size: 0.875rem;
            color: var(--text-secondary);
            font-weight: 500;
        }

        .forgot-link {
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 600;
            transition: all 0.3s;
            position: relative;
        }

        .forgot-link:hover {
            color: var(--redcode-primary);
            transform: translateY(-1px);
        }

        .forgot-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--gradient-primary);
            transition: width 0.3s;
        }

        .forgot-link:hover::after {
            width: 100%;
        }

        .login-button {
            width: 100%;
            background: var(--gradient-primary);
            color: #fff;
            border: none;
            padding: 10px 18px;
            border-radius: 0.7rem;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            margin-bottom: 2rem;
            box-shadow:
                0 8px 25px rgba(220, 38, 38, 0.3),
                0 3px 10px rgba(220, 38, 38, 0.2);
            letter-spacing: 0.025em;
        }

        .login-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .login-button:hover::before {
            left: 100%;
        }

        .login-button:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow:
                0 15px 35px rgba(220, 38, 38, 0.4),
                0 5px 15px rgba(220, 38, 38, 0.3);
        }

        .login-button:active {
            transform: translateY(-1px) scale(0.98);
        }

        .register-link {
            text-align: center;
            color: var(--text-secondary);
            font-size: 0.875rem;
            font-weight: 500;
        }

        .register-link a {
            color: var(--redcode-primary);
            text-decoration: none;
            font-weight: 700;
            margin-left: 4px;
            transition: all 0.3s;
            position: relative;
        }

        .register-link a:hover {
            color: var(--redcode-primary-dark);
        }

        .register-link a::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--gradient-primary);
            transition: width 0.3s;
        }

        .register-link a:hover::after {
            width: 100%;
        }

        .error-message {
            background: rgba(220, 38, 38, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(220, 38, 38, 0.2);
            color: var(--redcode-primary-dark);
            padding: 16px 20px;
            border-radius: 1rem;
            font-size: 0.875rem;
            margin-top: 1rem;
            animation: slideIn 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 15px rgba(220, 38, 38, 0.1);
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .illustration-content {
            text-align: center;
            color: #fff;
            z-index: 10;
            position: relative;
            padding: 2rem;
        }

        .illustration-title {
            font-size: 3rem;
            font-weight: 900;
            margin-bottom: 1.5rem;
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            letter-spacing: -0.025em;
            background: linear-gradient(135deg, #fff 0%, rgba(255, 255, 255, 0.8) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .illustration-subtitle {
            font-size: 1.25rem;
            opacity: 0.9;
            margin-bottom: 3rem;
            font-weight: 500;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        .feature-list {
            list-style: none;
            padding: 0;
            text-align: left;
            max-width: 400px;
            margin: 0 auto;
        }

        .feature-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            max-width: 500px;
            margin: 0 auto;
        }

        .feature-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(15px);
            border-radius: 1.5rem;
            padding: 1.5rem 1rem;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .feature-card:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }

        .feature-title {
            font-weight: 700;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            color: #fff;
        }

        .feature-description {
            font-size: 0.75rem;
            opacity: 0.9;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.8);
        }

        .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
            font-size: 1.1rem;
            font-weight: 600;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s;
        }

        .feature-item:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateX(10px);
        }

        .feature-icon {
            width: 48px;
            height: 48px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s;
        }

        .feature-card:hover .feature-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .floating-elements {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            pointer-events: none;
        }

        .floating-shape {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 50%;
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: floatShape 12s ease-in-out infinite;
        }

        .floating-shape:nth-child(1) {
            width: 120px;
            height: 120px;
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }

        .floating-shape:nth-child(2) {
            width: 180px;
            height: 180px;
            top: 60%;
            right: 15%;
            animation-delay: 4s;
        }

        .floating-shape:nth-child(3) {
            width: 100px;
            height: 100px;
            bottom: 20%;
            left: 20%;
            animation-delay: 8s;
        }

        .floating-shape:nth-child(4) {
            width: 140px;
            height: 140px;
            top: 40%;
            right: 30%;
            animation-delay: 2s;
        }

        @keyframes floatShape {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg) scale(1);
                opacity: 0.7;
            }

            33% {
                transform: translateY(-30px) rotate(120deg) scale(1.1);
                opacity: 0.9;
            }

            66% {
                transform: translateY(15px) rotate(240deg) scale(0.9);
                opacity: 0.8;
            }
        }

        @media (max-width: 1024px) {
            .login-split {
                grid-template-columns: 1fr;
            }

            .login-illustration {
                display: none;
            }

            .login-form-section {
                background: var(--gradient-hero);
                background-size: 400% 400%;
                animation: gradientShift 18s ease infinite;
            }

            .login-card {
                margin: 1rem;
                max-width: 100%;
            }
        }

        @media (max-width: 640px) {
            .login-card {
                padding: 1.2rem 0.8rem;
                border-radius: 1rem;
            }

            .hrms-title {
                font-size: 2.5rem;
            }

            .role-tabs {
                flex-direction: column;
                gap: 6px;
            }

            .role-tab {
                padding: 14px 20px;
            }

            .feature-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
        }
    </style>
</head>

<body class="login-container">
    <div class="login-split">
        <!-- Left Side - Login Form -->
        <div class="login-form-section">
            <div class="login-card">
                <!-- RedCode Logo -->
                <div class="hrms-logo">
                    <h1 class="hrms-title">RedCode</h1>
                </div>
                <!-- User Avatar -->
                <div class="user-avatar">
                    <i class="fas fa-user text-white text-2xl"></i>
                </div>
                <!-- Sign In Header -->
                <div class="signin-header">
                    <h2 class="signin-title">Welcome Back</h2>
                    <p class="signin-subtitle">Sign in to your account to continue</p>
                </div>
                <!-- Role Selection Tabs -->
                <div class="role-tabs">
                    <div class="role-tab" data-role="employee">
                        <i class="fas fa-user mr-2"></i>Employee
                    </div>
                    <div class="role-tab active" data-role="admin">
                        <i class="fas fa-user-tie mr-2"></i>Admin
                    </div>
                    <div class="role-tab" data-role="super_admin">
                        <i class="fas fa-crown mr-2"></i>Super Admin
                    </div>
                </div>
                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" id="loginForm">
                    @csrf
                    <input type="hidden" name="role" id="selectedRole" value="admin">
                    <!-- Email Field -->
                    <div class="form-group">
                        <input id="email" type="email" name="email" required value="{{ old('email') }}"
                            class="form-input" placeholder="Enter your email address">
                        <i class="input-icon fas fa-envelope"></i>
                    </div>
                    <!-- Password Field -->
                    <div class="form-group">
                        <input id="password" type="password" name="password" required class="form-input"
                            placeholder="Enter your password">
                        <i class="input-icon fas fa-lock"></i>
                    </div>
                    <!-- Login Options -->
                    <div class="login-options">
                        <label class="checkbox-wrapper">
                            <input type="checkbox" name="remember" class="checkbox-custom">
                            <span class="checkbox-label">Remember me</span>
                        </label>
                        <a href="{{ route('password.request') }}" class="forgot-link">
                            Forgot Password?
                        </a>
                    </div>
                    <!-- Login Button -->
                    <button type="submit" class="login-button">
                        <span class="relative z-10">
                            <i class="fas fa-sign-in-alt mr-2"></i>Sign In
                        </span>
                    </button>
                </form>
                <!-- Register Link -->
                <div class="register-link">
                    Don't have an account?
                    <a href="{{ route('register') }}">
                        <i class="fas fa-user-plus mr-1"></i>Create Account
                    </a>
                </div>
                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="error-message">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        @foreach ($errors->all() as $error)
                            {{ $error }}<br>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
        <!-- Right Side - Illustration -->
        <div class="login-illustration">
            <div class="floating-elements">
                <div class="floating-shape"></div>
                <div class="floating-shape"></div>
                <div class="floating-shape"></div>
                <div class="floating-shape"></div>
            </div>
            <div class="illustration-content">
                <h2 class="illustration-title">Welcome to RedCode Solutions</h2>
                <p class="illustration-subtitle">Advanced Employee Management System</p>
                <div class="feature-grid">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-users text-white"></i>
                        </div>
                        <div class="feature-title">Employee Management</div>
                        <div class="feature-description">Comprehensive workforce control and organization</div>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-chart-line text-white"></i>
                        </div>
                        <div class="feature-title">Advanced Analytics</div>
                        <div class="feature-description">Real-time insights & detailed reporting</div>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt text-white"></i>
                        </div>
                        <div class="feature-title">Secure Access Control</div>
                        <div class="feature-description">Role-based permissions & enterprise security</div>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-clock text-white"></i>
                        </div>
                        <div class="feature-title">Time Management</div>
                        <div class="feature-description">Automated attendance & scheduling system</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Role tab functionality
        document.querySelectorAll('.role-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                // Remove active class from all tabs
                document.querySelectorAll('.role-tab').forEach(t => t.classList.remove('active'));
                // Add active class to clicked tab
                this.classList.add('active');
                // Update hidden input value
                document.getElementById('selectedRole').value = this.dataset.role;
            });
        });

        // Form validation with enhanced UX
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            if (!email || !password) {
                e.preventDefault();
                // Create custom notification
                const notification = document.createElement('div');
                notification.className = 'error-message';
                notification.innerHTML =
                '<i class="fas fa-exclamation-triangle mr-2"></i>Please fill in all fields';
                // Insert after form
                this.appendChild(notification);
                // Remove after 3 seconds
                setTimeout(() => {
                    notification.remove();
                }, 3000);
                return false;
            }
        });

        // Enhanced input interactions
        document.querySelectorAll('.form-input').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('focused');
            });
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('focused');
            });
        });
    </script>
</body>

</html>
input.addEventListener('blur', function() {
this.parentElement.classList.remove('focused');
});
</script>
</body>

</html>
