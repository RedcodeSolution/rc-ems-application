<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RedCode Solutions - Forgot Password</title>
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

        .forgot-container {
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

        .forgot-container::before {
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

        .forgot-split {
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 100vh;
            position: relative;
            z-index: 1;
        }

        .forgot-form-section {
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

        .forgot-form-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--gradient-glass);
            pointer-events: none;
        }

        .forgot-illustration {
            background: linear-gradient(135deg, rgba(220,38,38,0.1) 0%, rgba(153,27,27,0.1) 100%);
            backdrop-filter: blur(20px);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .forgot-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border-radius: 2.5rem;
            padding: 3rem 2.5rem;
            width: 100%;
            max-width: 480px;
            box-shadow:
                0 32px 64px rgba(220,38,38,0.15),
                0 0 0 1px rgba(255,255,255,0.05),
                inset 0 1px 0 rgba(255,255,255,0.1);
            position: relative;
            z-index: 10;
            border: 1px solid var(--border-light);
        }

        .forgot-card::before {
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
            margin-bottom: 2rem;
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

        .forgot-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .forgot-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
            letter-spacing: -0.025em;
        }

        .forgot-subtitle {
            color: var(--text-secondary);
            font-size: 0.95rem;
            font-weight: 500;
            line-height: 1.6;
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--text-primary);
            font-size: 0.875rem;
            letter-spacing: 0.025em;
        }

        .form-input {
            width: 100%;
            padding: 1rem 1.25rem;
            border: 2px solid var(--border-light);
            border-radius: 1rem;
            font-size: 0.95rem;
            font-weight: 500;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            outline: none;
            position: relative;
        }

        .form-input:focus {
            border-color: var(--redcode-primary);
            background: rgba(255, 255, 255, 0.95);
            box-shadow:
                0 0 0 4px rgba(220,38,38,0.1),
                0 8px 25px rgba(220,38,38,0.1);
            transform: translateY(-2px);
        }

        .form-input::placeholder {
            color: var(--text-light);
            font-weight: 500;
        }

        .forgot-button {
            width: 100%;
            padding: 1rem;
            background: var(--gradient-primary);
            color: white;
            border: none;
            border-radius: 1rem;
            font-weight: 700;
            font-size: 1rem;
            letter-spacing: 0.025em;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow:
                0 10px 25px rgba(220,38,38,0.3),
                inset 0 1px 0 rgba(255,255,255,0.1);
            position: relative;
            overflow: hidden;
        }

        .forgot-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.6s;
        }

        .forgot-button:hover::before {
            left: 100%;
        }

        .forgot-button:hover {
            transform: translateY(-2px);
            box-shadow:
                0 15px 35px rgba(220,38,38,0.4),
                inset 0 1px 0 rgba(255,255,255,0.2);
        }

        .forgot-button:active {
            transform: translateY(0);
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-secondary);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.875rem;
            padding: 0.75rem 1rem;
            border-radius: 0.75rem;
            transition: all 0.3s;
            margin-top: 1rem;
        }

        .back-link:hover {
            color: var(--redcode-primary);
            background: rgba(220,38,38,0.05);
        }

        .success-message {
            background: rgba(5, 150, 105, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(5, 150, 105, 0.2);
            color: var(--redcode-green);
            padding: 1rem 1.25rem;
            border-radius: 1rem;
            font-size: 0.875rem;
            margin-bottom: 1.5rem;
            animation: slideIn 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .error-message {
            background: rgba(217, 119, 6, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(217, 119, 6, 0.2);
            color: var(--redcode-orange);
            padding: 1rem 1.25rem;
            border-radius: 1rem;
            font-size: 0.875rem;
            margin-bottom: 1.5rem;
            animation: slideIn 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .illustration-content {
            text-align: center;
            color: white;
            max-width: 600px;
            padding: 2rem;
            position: relative;
            z-index: 10;
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

        @media (max-width: 1024px) {
            .forgot-split {
                grid-template-columns: 1fr;
            }
            .forgot-illustration {
                display: none;
            }
            .forgot-form-section {
                background: linear-gradient(135deg, #DC2626 0%, #991B1B 25%, #B91C1C 50%, #DC2626 75%, #991B1B 100%);
                background-size: 400% 400%;
                animation: gradientShift 18s ease infinite;
            }
            .forgot-card {
                margin: 1rem;
                max-width: 100%;
            }
        }

        @media (max-width: 640px) {
            .forgot-card {
                padding: 2rem 1.5rem;
                border-radius: 2rem;
            }
            .feature-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            .illustration-title {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="forgot-container">
        <div class="forgot-split">
            <!-- Left Side - Form -->
            <div class="forgot-form-section">
                <div class="forgot-card">
                    <!-- RedCode Logo -->
                    <div class="hrms-logo">
                        <h1 class="hrms-title">RedCode</h1>
                    </div>

                    <!-- Avatar -->
                    <div class="user-avatar">
                        <i class="fas fa-envelope text-white text-2xl"></i>
                    </div>

                    <!-- Form Header -->
                    <div class="forgot-header">
                        <h2 class="forgot-title">Forgot Password?</h2>
                        <p class="forgot-subtitle">No worries! Enter your email address and we'll send you a link to reset your password.</p>
                    </div>

                    <!-- Session Status -->
                    @if (session('status'))
                        <div class="success-message">
                            <i class="fas fa-check-circle mr-2"></i>
                            {{ session('status') }}
                        </div>
                    @endif

                    <!-- Forgot Password Form -->
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <!-- Email Address -->
                        <div class="form-group">
                            <label for="email" class="form-label">Email Address</label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                value="{{ old('email') }}"
                                class="form-input" 
                                placeholder="Enter your email address"
                                required 
                                autofocus 
                                autocomplete="username">
                            @error('email')
                                <div class="error-message" style="margin-top: 0.5rem; margin-bottom: 0;">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="forgot-button">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Send Reset Link
                        </button>

                        <!-- Back to Login -->
                        <div class="text-center">
                            <a href="{{ route('login') }}" class="back-link">
                                <i class="fas fa-arrow-left"></i>
                                Back to Login
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right Side - Illustration -->
            <div class="forgot-illustration">
                <div class="floating-elements">
                    <div class="floating-shape"></div>
                    <div class="floating-shape"></div>
                    <div class="floating-shape"></div>
                    <div class="floating-shape"></div>
                    <div class="floating-shape"></div>
                </div>
                <div class="illustration-content">
                    <h2 class="illustration-title">Password Recovery</h2>
                    <p class="illustration-subtitle">Quick and secure account recovery</p>
                    <div class="feature-grid">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-envelope-circle-check text-white text-xl"></i>
                            </div>
                            <h4 class="feature-title">Email Verification</h4>
                            <p class="feature-description">Secure email-based password recovery system</p>
                        </div>
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-clock text-white text-xl"></i>
                            </div>
                            <h4 class="feature-title">Quick Recovery</h4>
                            <p class="feature-description">Fast and efficient password reset process</p>
                        </div>
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-shield-alt text-white text-xl"></i>
                            </div>
                            <h4 class="feature-title">Secure Process</h4>
                            <p class="feature-description">Protected with industry-standard security</p>
                        </div>
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-user-check text-white text-xl"></i>
                            </div>
                            <h4 class="feature-title">Identity Safe</h4>
                            <p class="feature-description">Your personal information stays protected</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Real-time email validation
        document.getElementById('email').addEventListener('input', function() {
            const email = this.value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (email && !emailRegex.test(email)) {
                this.style.borderColor = '#D97706';
                this.style.background = 'rgba(217, 119, 6, 0.05)';
            } else if (email && emailRegex.test(email)) {
                this.style.borderColor = '#059669';
                this.style.background = 'rgba(5, 150, 105, 0.05)';
            } else {
                this.style.borderColor = '#E5E7EB';
                this.style.background = 'rgba(255, 255, 255, 0.9)';
            }
        });

        // Enhanced form submission
        document.querySelector('form').addEventListener('submit', function(e) {
            const submitButton = this.querySelector('.forgot-button');
            const originalText = submitButton.innerHTML;
            
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Sending...';
            submitButton.disabled = true;
            
            // Re-enable after 3 seconds if still on page
            setTimeout(() => {
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
            }, 3000);
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
