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
    <link rel="stylesheet" href="{{ asset('css/auth/loginPage.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="login-container">
    <div class="login-split">

        <!-- Left Side - Login Form -->
        <div class="login-form-section">
            <a href="{{ url('/') }}"
                class="fixed top-4 left-4 bg-red-600 text-white px-4 py-2 rounded-lg shadow-lg flex items-center gap-2 hover:bg-red-700 transition">
                <i class="fas fa-arrow-left"></i>
                Back
            </a>
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
                    <div class="form-group password-group">
                        <input id="password" type="password" name="password" required class="form-input"
                            placeholder="Enter your password">
                        <i class="input-icon fas fa-lock"></i>

                        <span class="toggle-password" onclick="togglePassword()">
                            <i id="toggleIcon" class="fas fa-eye"></i>
                        </span>
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
        <!-- Right Side - Illustration (desktop/tablet only) -->
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
    <!-- Mobile Illustration (shown below form on mobile only) -->
    <div class="mobile-illustration">
        <div class="illustration-title">Welcome to RedCode Solutions</div>
        <div class="illustration-subtitle">Advanced Employee Management System</div>
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
    @if (session('success'))
        <script>
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });

            Toast.fire({
                icon: 'success',
                title: '{{ session('success') }}'
            });
        </script>
    @endif

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

        function togglePassword() {
            const passInput = document.getElementById("password");
            const toggleIcon = document.getElementById("toggleIcon");

            if (passInput.type === "password") {
                passInput.type = "text";
                toggleIcon.classList.remove("fa-eye");
                toggleIcon.classList.add("fa-eye-slash");
            } else {
                passInput.type = "password";
                toggleIcon.classList.remove("fa-eye-slash");
                toggleIcon.classList.add("fa-eye");
            }
        }
    </script>
</body>

</html>
