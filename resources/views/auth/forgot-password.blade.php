<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RedCode Solutions - Forgot Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/auth/forgotPasswordPage.css') }}">
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
