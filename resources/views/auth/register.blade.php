<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RedCode Solutions - Create Account</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
        }

        * {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .register-container {
            background: var(--gradient-hero);
            background-size: 400% 400%;
            animation: gradientShift 18s ease infinite;
            min-height: 100vh;
            position: relative;
            overflow: hidden;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            25% { background-position: 100% 50%; }
            50% { background-position: 100% 100%; }
            75% { background-position: 0% 100%; }
            100% { background-position: 0% 50%; }
        }

        .register-container::before {
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
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            33% { transform: translateY(-20px) rotate(120deg); }
            66% { transform: translateY(10px) rotate(240deg); }
        }

        .register-split {
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 100vh;
            position: relative;
            z-index: 1;
        }

        .register-form-section {
            background: rgba(255, 255, 255, 0.97);
            backdrop-filter: blur(30px);
            border-right: 1px solid var(--border-light);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
            overflow-y: auto;
        }

        .register-form-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--gradient-glass);
            pointer-events: none;
        }

        .register-illustration {
            background: linear-gradient(135deg, rgba(220,38,38,0.1) 0%, rgba(153,27,27,0.1) 100%);
            backdrop-filter: blur(20px);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .register-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border-radius: 2.5rem;
            padding: 1.2rem 1.2rem;
            width: 100%;
            max-width: 720px;
            font-size: 0.9rem;
            box-shadow:
                0 32px 64px rgba(220,38,38,0.15),
                0 0 0 1px rgba(255,255,255,0.05),
                inset 0 1px 0 rgba(255,255,255,0.1);
            position: relative;
            z-index: 10;
            border: 1px solid var(--border-light);
            max-height: none; /* remove max-height */
            overflow-y: visible; /* remove scroll */
        }

        .register-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--gradient-glass);
            border-radius: 2.5rem;
            pointer-events: none;
        }

        .hrms-logo {
            text-align: center;
            margin-bottom: 1.5rem;
            position: relative;
        }

        .hrms-title {
            font-size: 2.8rem;
            font-weight: 900;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: 0.1em;
            text-shadow: 0 4px 20px rgba(220,38,38,0.18);
            position: relative;
        }

        .hrms-title::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 4px;
            background: var(--gradient-primary);
            border-radius: 2px;
        }

        .user-avatar {
            width: 70px;
            height: 70px;
            margin: 0 auto 1.5rem;
            background: var(--gradient-primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow:
                0 20px 40px rgba(220,38,38,0.2),
                0 0 0 4px rgba(255,255,255,0.1),
                inset 0 2px 0 rgba(255,255,255,0.2);
            position: relative;
            animation: pulse 3s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .register-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .register-title {
            font-size: 1.3rem;
            font-weight: 800;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
            letter-spacing: -0.025em;
        }

        .register-subtitle {
            color: var(--text-secondary);
            font-size: 0.8rem;
            font-weight: 500;
        }

        .form-group {
            margin-bottom: 1rem;
            position: relative;
        }

        .form-label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            color: var(--text-primary);
            font-size: 0.875rem;
            letter-spacing: 0.025em;
        }

        .form-input {
            width: 100%;
            padding: 7px 14px 7px 36px; /* reduced from 14px 20px 14px 48px */
            border: 2px solid var(--border-light);
            border-radius: 0.7rem; /* slightly reduced */
            font-size: 0.85rem; /* reduced font size */
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
                0 0 0 4px rgba(220,38,38,0.08),
                0 8px 25px rgba(220,38,38,0.12);
            transform: translateY(-2px);
        }

        .form-input:valid:not(:placeholder-shown) {
            border-color: var(--redcode-green);
            background: rgba(5, 150, 105, 0.05);
        }

        .form-input:invalid:not(:placeholder-shown) {
            border-color: var(--redcode-orange);
            background: rgba(217, 119, 6, 0.05);
        }

        .form-input::placeholder {
            color: var(--text-disabled);
            font-weight: 400;
        }

        .input-icon {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-disabled);
            transition: all 0.3s;
            z-index: 2;
        }

        .form-input:focus + .input-icon {
            color: var(--redcode-primary);
            transform: translateY(-50%) scale(1.1);
        }

        .form-select {
            width: 100%;
            padding: 7px 14px;
            border: 2px solid var(--border-light);
            border-radius: 0.7rem;
            font-size: 0.85rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: rgba(248, 250, 252, 0.5);
            backdrop-filter: blur(10px);
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 12px center;
            background-repeat: no-repeat;
            background-size: 14px;
            color: var(--text-primary);
            font-weight: 500;
        }

        .form-select:focus {
            outline: none;
            border-color: var(--redcode-primary);
            background-color: rgba(255, 255, 255, 0.9);
            box-shadow:
                0 0 0 4px rgba(220,38,38,0.08),
                0 8px 25px rgba(220,38,38,0.12);
            transform: translateY(-2px);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-bottom: 1rem;
        }

        .password-strength {
            margin-top: 6px;
            padding: 8px 12px;
            border-radius: 0.75rem;
            font-size: 0.75rem;
            font-weight: 600;
            transition: all 0.3s;
            backdrop-filter: blur(10px);
        }

        .strength-weak {
            background: rgba(217, 119, 6, 0.1);
            color: var(--redcode-orange);
            border: 1px solid rgba(217, 119, 6, 0.2);
        }

        .strength-medium {
            background: rgba(245, 158, 11, 0.1);
            color: var(--redcode-yellow);
            border: 1px solid rgba(245, 158, 11, 0.2);
        }

        .strength-strong {
            background: rgba(5,150,105,0.1);
            color: var(--redcode-green);
            border: 1px solid rgba(5,150,105,0.2);
        }

        .register-button {
            width: 100%;
            background: var(--gradient-primary);
            color: #fff;
            border: none;
            padding: 10px 18px; /* reduced from 16px 32px */
            border-radius: 0.7rem; /* reduced */
            font-size: 0.95rem;   /* reduced from 1.1rem */
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            margin: 1rem 0; /* reduced margin */
            box-shadow:
                0 8px 25px rgba(220,38,38,0.18),
                0 3px 10px rgba(153,27,27,0.12);
            letter-spacing: 0.025em;
        }

        .register-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .register-button:hover::before {
            left: 100%;
        }

        .register-button:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow:
                0 15px 35px rgba(220,38,38,0.22),
                0 5px 15px rgba(153,27,27,0.18);
        }

        .register-button:active {
            transform: translateY(-1px) scale(0.98);
        }

        .login-link {
            text-align: center;
            color: var(--text-secondary);
            font-size: 0.875rem;
            margin-bottom: 1rem;
            font-weight: 500;
        }

        .login-link a {
            color: var(--redcode-primary);
            text-decoration: none;
            font-weight: 700;
            margin-left: 4px;
            transition: all 0.3s;
            position: relative;
        }

        .login-link a:hover {
            color: var(--redcode-primary-dark);
        }

        .login-link a::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--gradient-primary);
            transition: width 0.3s;
        }

        .login-link a:hover::after {
            width: 100%;
        }

        .error-message {
            background: rgba(217, 119, 6, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(217, 119, 6, 0.2);
            color: var(--redcode-orange);
            padding: 16px 20px;
            border-radius: 1rem;
            font-size: 0.875rem;
            margin-top: 1rem;
            animation: slideIn 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 15px rgba(217, 119, 6, 0.1);
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
            font-size: 2.8rem;
            font-weight: 900;
            margin-bottom: 1.5rem;
            text-shadow: 0 4px 20px rgba(0,0,0,0.3);
            letter-spacing: -0.025em;
            background: linear-gradient(135deg, #fff 0%, rgba(255,255,255,0.8) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .illustration-subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
            margin-bottom: 3rem;
            font-weight: 500;
            text-shadow: 0 2px 10px rgba(0,0,0,0.2);
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
            padding: 2rem 1.5rem;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }

        .feature-card:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.25);
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

        .feature-title {
            font-weight: 700;
            margin-bottom: 0.5rem;
            font-size: 1rem;
        }

        .feature-description {
            font-size: 0.875rem;
            opacity: 0.9;
            font-weight: 500;
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
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            border-radius: 50%;
            border: 1px solid rgba(255,255,255,0.2);
            animation: floatShape 15s ease-in-out infinite;
        }

        .floating-shape:nth-child(1) {
            width: 120px;
            height: 120px;
            top: 15%;
            left: 8%;
            animation-delay: 0s;
        }

        .floating-shape:nth-child(2) {
            width: 180px;
            height: 180px;
            top: 65%;
            right: 12%;
            animation-delay: 5s;
        }

        .floating-shape:nth-child(3) {
            width: 100px;
            height: 100px;
            bottom: 15%;
            left: 15%;
            animation-delay: 10s;
        }

        .floating-shape:nth-child(4) {
            width: 140px;
            height: 140px;
            top: 35%;
            right: 25%;
            animation-delay: 2.5s;
        }

        .floating-shape:nth-child(5) {
            width: 90px;
            height: 90px;
            top: 80%;
            left: 60%;
            animation-delay: 7.5s;
        }

        @keyframes floatShape {
            0%, 100% {
                transform: translateY(0px) rotate(0deg) scale(1);
                opacity: 0.7;
            }
            33% {
                transform: translateY(-40px) rotate(120deg) scale(1.1);
                opacity: 0.9;
            }
            66% {
                transform: translateY(20px) rotate(240deg) scale(0.9);
                opacity: 0.8;
            }
        }

        /* Custom scrollbar */
        .register-card::-webkit-scrollbar {
            width: 8px;
        }

        .register-card::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.05);
            border-radius: 4px;
        }

        .register-card::-webkit-scrollbar-thumb {
            background: rgba(14,165,233,0.2);
            border-radius: 4px;
        }

        .register-card::-webkit-scrollbar-thumb:hover {
            background: rgba(14,165,233,0.4);
        }

        .footer-text {
            text-align: center;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border-light);
            margin-top: 1rem;
        }

        .footer-text p {
            font-size: 0.75rem;
            color: var(--text-disabled);
            font-weight: 500;
        }

        @media (max-width: 1024px) {
            .register-split {
                grid-template-columns: 1fr;
            }
            .register-illustration {
                display: none;
            }
            .register-form-section {
                background: linear-gradient(135deg, #DC2626 0%, #991B1B 25%, #B91C1C 50%, #DC2626 75%, #991B1B 100%);
                background-size: 400% 400%;
                animation: gradientShift 18s ease infinite;
            }
            .register-card {
                margin: 1rem;
                max-width: 100%;
            }
        }

        @media (max-width: 640px) {
            .register-card {
                padding: 2rem 1.5rem;
                border-radius: 2rem;
            }
            .hrms-title {
                font-size: 2.2rem;
            }
            .form-row {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            .feature-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            .register-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body class="register-container">
    <div class="register-split">
        <!-- Left Side - Registration Form -->
        <div class="register-form-section">
            <div class="register-card">
                <!-- RedCode Logo -->
                <div class="hrms-logo">
                    <h1 class="hrms-title">RedCode</h1>
                </div>

                <!-- User Avatar -->
                <div class="user-avatar">
                    <i class="fas fa-user-plus text-white text-xl"></i>
                </div>

                <!-- Header -->
                <div class="register-header">
                    <h2 class="register-title">Join Our Team</h2>
                    <p class="register-subtitle">Create your account to get started</p>
                </div>

                <!-- Registration Form -->
                <form method="POST" action="{{ route('register') }}" id="registerForm">
                    @csrf

                    <div class="form-row">
                        <!-- Name Field -->
                        <div class="form-group">
                            <label for="name" class="form-label">
                                <i class="fas fa-user mr-1"></i>Full Name
                            </label>
                            <div class="relative">
                                <input
                                    id="name"
                                    type="text"
                                    name="name"
                                    required
                                    value="{{ old('name') }}"
                                    class="form-input"
                                    placeholder="Enter your full name"
                                >
                                <i class="input-icon fas fa-user"></i>
                            </div>
                        </div>

                        <!-- Email Field -->
                        <div class="form-group">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope mr-1"></i>Email Address
                            </label>
                            <div class="relative">
                                <input
                                    id="email"
                                    type="email"
                                    name="email"
                                    required
                                    value="{{ old('email') }}"
                                    class="form-input"
                                    placeholder="Enter your email address"
                                >
                                <i class="input-icon fas fa-envelope"></i>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <!-- Contact Number Field -->
                        <div class="form-group">
                            <label for="contact_no" class="form-label">
                                <i class="fas fa-phone mr-1"></i>Contact Number
                            </label>
                            <div class="relative">
                                <input
                                    id="contact_no"
                                    type="tel"
                                    name="contact_no"
                                    required
                                    value="{{ old('contact_no') }}"
                                    class="form-input"
                                    placeholder="Enter your contact number"
                                    pattern="[0-9]{10,15}"
                                >
                                <i class="input-icon fas fa-phone"></i>
                            </div>
                        </div>

                        <!-- Role Selection -->
                        <div class="form-group">
                            <label for="role" class="form-label">
                                <i class="fas fa-user-tag mr-1"></i>Role
                            </label>
                            <select
                                id="role"
                                name="role"
                                required
                                class="form-select"
                            >
                                <option value="" disabled selected>Select your role</option>
                                <option value="employee" {{ old('role') == 'employee' ? 'selected' : '' }}>
                                    <i class="fas fa-user"></i> Employee - Basic Access
                                </option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>
                                    <i class="fas fa-user-tie"></i> Admin - Management Access
                                </option>
                                <option value="super_admin" {{ old('role') == 'super_admin' ? 'selected' : '' }}>
                                    <i class="fas fa-crown"></i> Super Admin - Full Access
                                </option>
                            </select>
                        </div>
                    </div>

                    <!-- Password Fields Row -->
                    <div class="form-row">
                        <!-- Password Field -->
                        <div class="form-group">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock mr-1"></i>Password
                            </label>
                            <div class="relative">
                                <input
                                    id="password"
                                    type="password"
                                    name="password"
                                    required
                                    class="form-input"
                                    placeholder="Create password"
                                    minlength="8"
                                >
                                <i class="input-icon fas fa-lock"></i>
                            </div>
                            <div id="passwordStrength" class="password-strength" style="display: none;"></div>
                        </div>

                        <!-- Confirm Password Field -->
                        <div class="form-group">
                            <label for="password_confirmation" class="form-label">
                                <i class="fas fa-check-circle mr-1"></i>Confirm Password
                            </label>
                            <div class="relative">
                                <input
                                    id="password_confirmation"
                                    type="password"
                                    name="password_confirmation"
                                    required
                                    class="form-input"
                                    placeholder="Confirm password"
                                >
                                <i class="input-icon fas fa-check-circle"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Register Button -->
                    <button type="submit" class="register-button">
                        <span class="relative z-10">
                            <i class="fas fa-user-plus mr-2"></i>Create Account
                        </span>
                    </button>
                </form>

                <!-- Login Link -->
                <div class="login-link">
                    Already have an account?
                    <a href="{{ route('login') }}">
                        <i class="fas fa-sign-in-alt mr-1"></i>Sign In
                    </a>
                </div>

                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="error-message">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Footer -->
                <div class="footer-text">
                    <p>© 2024 HRMS. All rights reserved.</p>
                </div>
            </div>
        </div>

        <!-- Right Side - Illustration -->
        <div class="register-illustration">
            <div class="floating-elements">
                <div class="floating-shape"></div>
                <div class="floating-shape"></div>
                <div class="floating-shape"></div>
                <div class="floating-shape"></div>
                <div class="floating-shape"></div>
            </div>
            <div class="illustration-content">
                <h2 class="illustration-title">Join RedCode Solutions</h2>
                <p class="illustration-subtitle">Employee Management System</p>
                <div class="feature-grid">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-users text-white text-xl"></i>
                        </div>
                        <h4 class="feature-title">Team Management</h4>
                        <p class="feature-description">Organize and manage your workforce efficiently with advanced tools</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-chart-line text-white text-xl"></i>
                        </div>
                        <h4 class="feature-title">Smart Analytics</h4>
                        <p class="feature-description">Data-driven insights for better business decisions</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt text-white text-xl"></i>
                        </div>
                        <h4 class="feature-title">Secure Access</h4>
                        <p class="feature-description">Role-based permissions and enterprise security</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-clock text-white text-xl"></i>
                        </div>
                        <h4 class="feature-title">Time Tracking</h4>
                        <p class="feature-description">Automated attendance and scheduling system</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Password strength checker
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strengthDiv = document.getElementById('passwordStrength');

            if (password.length === 0) {
                strengthDiv.style.display = 'none';
                return;
            }

            strengthDiv.style.display = 'block';
            let strength = 0;
            let feedback = [];

            // Length check
            if (password.length >= 8) strength++;
            else feedback.push('At least 8 characters');

            // Uppercase check
            if (/[A-Z]/.test(password)) strength++;
            else feedback.push('One uppercase letter');

            // Lowercase check
            if (/[a-z]/.test(password)) strength++;
            else feedback.push('One lowercase letter');

            // Number check
            if (/\d/.test(password)) strength++;
            else feedback.push('One number');

            // Special character check
            if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) strength++;
            else feedback.push('One special character');

            // Update strength display
            if (strength < 3) {
                strengthDiv.className = 'password-strength strength-weak';
                strengthDiv.innerHTML = '<i class="fas fa-exclamation-triangle mr-1"></i>Weak password. Need: ' + feedback.slice(0, 2).join(', ');
            } else if (strength < 5) {
                strengthDiv.className = 'password-strength strength-medium';
                strengthDiv.innerHTML = '<i class="fas fa-shield-alt mr-1"></i>Medium strength. Consider: ' + feedback.slice(0, 1).join(', ');
            } else {
                strengthDiv.className = 'password-strength strength-strong';
                strengthDiv.innerHTML = '<i class="fas fa-check-circle mr-1"></i>Strong password!';
            }
        });

        // Password confirmation validation
        document.getElementById('password_confirmation').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmation = this.value;

            if (confirmation && password !== confirmation) {
                this.style.borderColor = '#ef4444';
                this.style.background = 'rgba(239, 68, 68, 0.05)';
            } else if (confirmation && password === confirmation) {
                this.style.borderColor = '#22c55e';
                this.style.background = 'rgba(34, 197, 94, 0.05)';
            }
        });

        // Phone number formatting
        document.getElementById('contact_no').addEventListener('input', function() {
            let value = this.value.replace(/\D/g, '');
            if (value.length > 15) {
                value = value.substring(0, 15);
            }
            this.value = value;
        });

        // Enhanced form validation
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmation = document.getElementById('password_confirmation').value;
            const email = document.getElementById('email').value;
            const name = document.getElementById('name').value;
            const contact = document.getElementById('contact_no').value;
            const role = document.getElementById('role').value;

            // Check if passwords match
            if (password !== confirmation) {
                e.preventDefault();
                showNotification('Passwords do not match!', 'error');
                return false;
            }

            // Check password strength
            if (password.length < 8) {
                e.preventDefault();
                showNotification('Password must be at least 8 characters long!', 'error');
                return false;
            }

            // Check if all fields are filled
            if (!name || !email || !contact || !role || !password || !confirmation) {
                e.preventDefault();
                showNotification('Please fill in all required fields!', 'error');
                return false;
            }

            // Show success message
            showNotification('Creating your account...', 'success');
        });

        // Enhanced input interactions
        document.querySelectorAll('.form-input, .form-select').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('focused');
            });

            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('focused');
            });
        });

        // Notification system
        function showNotification(message, type) {
            const notification = document.createElement('div');
            notification.className = `error-message ${type === 'success' ? 'bg-green-100 border-green-200 text-green-700' : ''}`;
            notification.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} mr-2"></i>${message}`;

            // Insert after form
            document.getElementById('registerForm').appendChild(notification);

            // Remove after 4 seconds
            setTimeout(() => {
                notification.remove();
            }, 4000);
        }

        // Real-time email validation
        document.getElementById('email').addEventListener('input', function() {
            const email = this.value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (email && !emailRegex.test(email)) {
                this.style.borderColor = '#ef4444';
                this.style.background = 'rgba(239, 68, 68, 0.05)';
            } else if (email && emailRegex.test(email)) {
                this.style.borderColor = '#22c55e';
                this.style.background = 'rgba(34, 197, 94, 0.05)';
            }
        });
    </script>
</body>
</html>
