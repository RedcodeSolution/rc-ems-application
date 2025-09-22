<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RedCode Solutions - Reset Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/auth/resetPasswordPage.css') }}">
</head>
<body>
    <div class="reset-container">
        <div class="reset-split">
            <!-- Left Side - Form -->
            <div class="reset-form-section">
                <div class="reset-card">
                    <!-- RedCode Logo -->
                    <div class="hrms-logo">
                        <h1 class="hrms-title">RedCode</h1>
                    </div>

                    <!-- Avatar -->
                    <div class="user-avatar">
                        <i class="fas fa-key text-white text-2xl"></i>
                    </div>

                    <!-- Form Header -->
                    <div class="reset-header">
                        <h2 class="reset-title">Reset Your Password</h2>
                        <p class="reset-subtitle">Create a new secure password for your account</p>
                    </div>

                    <!-- Error Messages -->
                    @if ($errors->any())
                        <div class="error-message">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <strong>Please fix the following errors:</strong>
                            <ul class="mt-2 ml-4 list-disc">
                                @foreach ($errors->all() as $error)
                                    <li class="text-sm">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Reset Form -->
                    <form method="POST" action="{{ route('password.store') }}">
                        @csrf

                        <!-- Password Reset Token -->
                        <input type="hidden" name="token" value="{{ request()->route('token') }}">

                        <!-- Email Address -->
                        <div class="form-group">
                            <label for="email" class="form-label">Email Address</label>
                            <div style="position: relative;">
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    value="{{ old('email', request()->email) }}"
                                    class="form-input"
                                    placeholder="Enter your email address"
                                    required
                                    autofocus
                                    autocomplete="username">
                                <i class="fas fa-envelope input-icon"></i>
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="form-group">
                            <label for="password" class="form-label">New Password</label>
                            <div class="password-input-wrapper" style="position: relative;">
                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    class="form-input"
                                    placeholder="Create a strong password"
                                    required
                                    autocomplete="new-password">
                                <i class="fas fa-lock input-icon"></i>
                                <button type="button" class="password-toggle" onclick="togglePassword('password')">
                                    <i class="fas fa-eye" id="password-icon"></i>
                                </button>
                            </div>
                            <div id="passwordStrength" class="password-strength" style="display: none;"></div>
                        </div>

                        <!-- Confirm Password -->
                        <div class="form-group">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <div class="password-input-wrapper" style="position: relative;">
                                <input
                                    type="password"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    class="form-input"
                                    placeholder="Confirm your new password"
                                    required
                                    autocomplete="new-password">
                                <i class="fas fa-lock input-icon"></i>
                                <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation')">
                                    <i class="fas fa-eye" id="password_confirmation-icon"></i>
                                </button>
                            </div>
                            <div id="passwordMatch" class="password-strength" style="display: none;"></div>
                        </div>

                        <!-- Reset Button -->
                        <button type="submit" class="reset-button">
                            <i class="fas fa-shield-alt mr-2"></i>
                            Reset Password
                        </button>

                        <!-- Back to Login -->
                        <div class="back-link">
                            Remember your password?
                            <a href="{{ route('login') }}">Sign in here</a>
                        </div>
                    </form>

                    <div class="footer-text">
                        <p>© 2024 RedCode Solutions. All rights reserved.</p>
                    </div>
                </div>
            </div>

            <!-- Right Side - Illustration -->
            <div class="reset-illustration">
                <div class="floating-elements">
                    <div class="floating-shape"></div>
                    <div class="floating-shape"></div>
                    <div class="floating-shape"></div>
                    <div class="floating-shape"></div>
                    <div class="floating-shape"></div>
                </div>
                <div class="illustration-content">
                    <h2 class="illustration-title">Secure Reset</h2>
                    <p class="illustration-subtitle">Your security is our priority</p>
                    <div class="feature-grid">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-shield-alt text-white text-xl"></i>
                            </div>
                            <h4 class="feature-title">Bank-Level Security</h4>
                            <p class="feature-description">Advanced encryption protects your sensitive data</p>
                        </div>
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-lock text-white text-xl"></i>
                            </div>
                            <h4 class="feature-title">Secure Encryption</h4>
                            <p class="feature-description">256-bit encryption keeps your information safe</p>
                        </div>
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-user-check text-white text-xl"></i>
                            </div>
                            <h4 class="feature-title">Identity Verification</h4>
                            <p class="feature-description">Multi-factor authentication for added protection</p>
                        </div>
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-bolt text-white text-xl"></i>
                            </div>
                            <h4 class="feature-title">Quick Access</h4>
                            <p class="feature-description">Fast and secure password recovery process</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Password visibility toggle
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(inputId + '-icon');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

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
            const confirmPassword = this.value;
            const matchDiv = document.getElementById('passwordMatch');

            if (confirmPassword.length === 0) {
                matchDiv.style.display = 'none';
                return;
            }

            matchDiv.style.display = 'block';

            if (password === confirmPassword) {
                matchDiv.className = 'password-strength strength-strong';
                matchDiv.innerHTML = '<i class="fas fa-check-circle mr-1"></i>Passwords match!';
            } else {
                matchDiv.className = 'password-strength strength-weak';
                matchDiv.innerHTML = '<i class="fas fa-exclamation-triangle mr-1"></i>Passwords do not match';
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
            }
        });
    </script>
</body>
</html>
