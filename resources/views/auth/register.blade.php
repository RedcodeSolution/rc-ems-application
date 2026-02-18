<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RedCode Solutions - Create Account</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/auth/registerPage.css') }}">
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
