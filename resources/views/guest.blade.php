@auth
    @php
        return redirect()->route('dashboard');
    @endphp
@endauth

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EMS - Advanced Employee Management System | Transform Your Workforce</title>
    <meta name="description" content="Professional Employee Management System for modern enterprises. Manage employees, projects, payroll, and performance with our comprehensive HRMS solution.">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/guestPage.css') }}">
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="navbar" id="navbar">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center" style="height: 80px;">
                <a href="#" class="navbar-brand">
                    <div class="navbar-brand-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <span class="gradient-text">RedCode Solution</span>
                </a>
                <!-- Desktop Navigation -->
                <div class="navbar-nav d-mobile-none">
                    <a href="#features" class="nav-link">Features</a>
                    <a href="#benefits" class="nav-link">Benefits</a>
                    <a href="#testimonials" class="nav-link">Testimonials</a>
                    <a href="#contact" class="nav-link">Contact</a>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('login') }}" class="nav-link hover-lift d-mobile-none">
                        <i class="fas fa-sign-in-alt"></i>
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-primary">
                        <i class="fas fa-rocket"></i>
                        Get Started
                    </a>
                </div>
                <!-- Mobile menu button -->
                <button id="mobile-menu-button" class="d-md-none" style="background: none; border: none; color: var(--text-secondary); font-size: 1.5rem; cursor: pointer;">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            <!-- Mobile Navigation -->
            <div id="mobile-menu" class="mobile-menu d-none">
                <a href="#features" class="mobile-menu-item">Features</a>
                <a href="#benefits" class="mobile-menu-item">Benefits</a>
                <a href="#testimonials" class="mobile-menu-item">Testimonials</a>
                <a href="#contact" class="mobile-menu-item">Contact</a>
                <a href="{{ route('login') }}" class="mobile-menu-item">
                    <i class="fas fa-sign-in-alt"></i> Login
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="floating-element floating-1"></div>
        <div class="floating-element floating-2"></div>
        <div class="floating-element floating-3"></div>
        <div class="floating-element floating-4"></div>
        <div class="container">
            <div class="d-flex align-items-center" style="min-height: 80vh;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-4xl); align-items: center; width: 100%;">
                    <div class="hero-content animate-fadeInLeft">
                        <h1 class="hero-title">
                            Transform Your Workforce with
                            <span style="color: #FFB300;">Intelligent HRMS</span>
                        </h1>
                        <p class="hero-subtitle">
                            At RedCode Solutions, our HR Management team is committed to building a culture of innovation, collaboration and continuous growth. We attract and nurture talented individuals who are passionate about delivering cutting-edge software solutions, aligning each person’s strengths with our mission to create real impact. By offering ongoing professional development, clear career pathways and an open, supportive work environment, we empower our team to excel and contribute to our clients’ success — today and for the long haul. </p>
                        <div class="d-flex gap-4 mb-5" style="flex-direction: column;">
                            <div style="display: flex; gap: var(--space-lg); flex-wrap: wrap;">
                                <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
                                    <i class="fas fa-rocket"></i>
                                    Start Free Trial
                                </a>
                                <a href="#demo" class="btn btn-outline btn-lg">
                                    <i class="fas fa-play"></i>
                                    Watch Demo
                                </a>
                            </div>
                        </div>
                        <div class="hero-features">
<!--                            <div class="hero-feature">-->
<!--                                <i class="fas fa-check-circle"></i>-->
<!--                                <span>30-day free trial</span>-->
<!--                            </div>-->
<!--                            <div class="hero-feature">-->
<!--                                <i class="fas fa-check-circle"></i>-->
<!--                                <span>No credit card required</span>-->
<!--                            </div>-->
<!--                            <div class="hero-feature">-->
<!--                                <i class="fas fa-check-circle"></i>-->
<!--                                <span>24/7 premium support</span>-->
<!--                            </div>-->
                        </div>
                    </div>
                    <div class="animate-fadeInRight">
                        <div class="dashboard-preview animate-float">
                            <div class="dashboard-grid">
                                <div class="dashboard-stat">
                                    <div class="dashboard-stat-number">2,500+</div>
                                    <div class="dashboard-stat-label">Active Employees</div>
                                </div>
                                <div class="dashboard-stat">
                                    <div class="dashboard-stat-number">99.9%</div>
                                    <div class="dashboard-stat-label">Uptime</div>
                                </div>
                                <div class="dashboard-stat">
                                    <div class="dashboard-stat-number">150+</div>
                                    <div class="dashboard-stat-label">Active Projects</div>
                                </div>
                                <div class="dashboard-stat">
                                    <div class="dashboard-stat-number">24/7</div>
                                    <div class="dashboard-stat-label">Support</div>
                                </div>
                            </div>
                            <div class="dashboard-chart">
                                <i class="fas fa-chart-line animate-pulse-custom"></i>
                                <p style="font-size: 1.25rem; font-weight: 600; margin: 0;">Real-time Analytics Dashboard</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="section bg-white">
        <div class="container">
            <div class="grid-auto-fill">
                <div class="stats-card reveal">
                    <div class="stats-number" data-count="25000">0</div>
                    <div class="stats-label">Companies Trust Us</div>
                </div>
                <div class="stats-card reveal">
                    <div class="stats-number" data-count="1000000">0</div>
                    <div class="stats-label">Employees Managed</div>
                </div>
                <div class="stats-card reveal">
                    <div class="stats-number" data-count="99">0</div>
                    <div class="stats-label">% Uptime Guarantee</div>
                </div>
                <div class="stats-card reveal">
                    <div class="stats-number" data-count="180">0</div>
                    <div class="stats-label">Countries Worldwide</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="section">
        <div class="container">
            <div class="text-center mb-6 reveal">
                <h2 class="section-title">
                    Powerful Features for <span class="text-gradient">Modern Enterprises</span>
                </h2>
                <p class="section-subtitle">
                    Everything you need to manage your workforce efficiently and drive business growth
                </p>
            </div>
            <div class="grid-auto-fit">
                <!-- Employee Management -->
                <div class="feature-card reveal">
                    <div class="feature-icon ">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 style="font-size: 1.5rem; font-weight: 700; color: var(--text-primary); margin-bottom: var(--space-md);">Employee Management</h3>
                    <p style="color: var(--text-secondary); margin-bottom: var(--space-lg); line-height: 1.6;">
                        Comprehensive employee profiles, automated onboarding workflows, and advanced performance tracking with AI-powered insights.
                    </p>
                    <ul style="list-style: none; color: var(--text-secondary); font-size: 0.875rem;">
                        <li style="margin-bottom: var(--space-sm);"><i class="fas fa-check" style="color: var(--primary-500); margin-right: var(--space-sm);"></i>Digital employee profiles</li>
                        <li style="margin-bottom: var(--space-sm);"><i class="fas fa-check" style="color: var(--primary-500); margin-right: var(--space-sm);"></i>Automated onboarding</li>
                        <li style="margin-bottom: var(--space-sm);"><i class="fas fa-check" style="color: var(--primary-500); margin-right: var(--space-sm);"></i>Performance analytics</li>
                        <li><i class="fas fa-check" style="color: var(--primary-500); margin-right: var(--space-sm);"></i>AI-powered insights</li>
                    </ul>
                </div>

                <!-- Project Management -->
                <div class="feature-card reveal">
                    <div class="feature-icon">
                        <i class="fas fa-project-diagram"></i>
                    </div>
                    <h3 style="font-size: 1.5rem; font-weight: 700; color: var(--text-primary); margin-bottom: var(--space-md);">Project Management</h3>
                    <p style="color: var(--text-secondary); margin-bottom: var(--space-lg); line-height: 1.6;">
                        Create, assign, and track projects with built-in collaboration tools, real-time progress monitoring, and resource optimization.
                    </p>
                    <ul style="list-style: none; color: var(--text-secondary); font-size: 0.875rem;">
                        <li style="margin-bottom: var(--space-sm);"><i class="fas fa-check" style="color: var(--primary-500); margin-right: var(--space-sm);"></i>Task assignment & tracking</li>
                        <li style="margin-bottom: var(--space-sm);"><i class="fas fa-check" style="color: var(--primary-500); margin-right: var(--space-sm);"></i>Real-time collaboration</li>
                        <li style="margin-bottom: var(--space-sm);"><i class="fas fa-check" style="color: var(--primary-500); margin-right: var(--space-sm);"></i>Resource optimization</li>
                        <li><i class="fas fa-check" style="color: var(--primary-500); margin-right: var(--space-sm);"></i>Progress analytics</li>
                    </ul>
                </div>

                <!-- Payroll & Finance -->
                <div class="feature-card reveal">
                    <div class="feature-icon ">
                        <i class="fas fa-calculator"></i>
                    </div>
                    <h3 style="font-size: 1.5rem; font-weight: 700; color: var(--text-primary); margin-bottom: var(--space-md);">Payroll & Finance</h3>
                    <p style="color: var(--text-secondary); margin-bottom: var(--space-lg); line-height: 1.6;">
                        Automated payroll processing, expense management, financial reporting with full tax compliance and multi-currency support.
                    </p>
                    <ul style="list-style: none; color: var(--text-secondary); font-size: 0.875rem;">
                        <li style="margin-bottom: var(--space-sm);"><i class="fas fa-check" style="color: var(--primary-500); margin-right: var(--space-sm);"></i>Automated payroll</li>
                        <li style="margin-bottom: var(--space-sm);"><i class="fas fa-check" style="color: var(--primary-500); margin-right: var(--space-sm);"></i>Expense tracking</li>
                        <li style="margin-bottom: var(--space-sm);"><i class="fas fa-check" style="color: var(--primary-500); margin-right: var(--space-sm);"></i>Tax compliance</li>
                        <li><i class="fas fa-check" style="color: var(--primary-500); margin-right: var(--space-sm);"></i>Multi-currency support</li>
                    </ul>
                </div>

                <!-- Leave Management -->
                <div class="feature-card reveal">
                    <div class="feature-icon gradient-success">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <h3 style="font-size: 1.5rem; font-weight: 700; color: var(--text-primary); margin-bottom: var(--space-md);">Leave Management</h3>
                    <p style="color: var(--text-secondary); margin-bottom: var(--space-lg); line-height: 1.6;">
                        Streamlined leave requests, intelligent approval workflows, automatic balance calculations, and comprehensive leave analytics.
                    </p>
                    <ul style="list-style: none; color: var(--text-secondary); font-size: 0.875rem;">
                        <li style="margin-bottom: var(--space-sm);"><i class="fas fa-check" style="color: var(--primary-500); margin-right: var(--space-sm);"></i>Smart leave requests</li>
                        <li style="margin-bottom: var(--space-sm);"><i class="fas fa-check" style="color: var(--primary-500); margin-right: var(--space-sm);"></i>Approval workflows</li>
                        <li style="margin-bottom: var(--space-sm);"><i class="fas fa-check" style="color: var(--primary-500); margin-right: var(--space-sm);"></i>Balance tracking</li>
                        <li><i class="fas fa-check" style="color: var(--primary-500); margin-right: var(--space-sm);"></i>Leave analytics</li>
                    </ul>
                </div>

                <!-- Analytics & Reports -->
                <div class="feature-card reveal">
                    <div class="feature-icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <h3 style="font-size: 1.5rem; font-weight: 700; color: var(--text-primary); margin-bottom: var(--space-md);">Analytics & Reports</h3>
                    <p style="color: var(--text-secondary); margin-bottom: var(--space-lg); line-height: 1.6;">
                        Comprehensive reporting and advanced analytics with AI-powered insights to make data-driven decisions about your workforce.
                    </p>
                    <ul style="list-style: none; color: var(--text-secondary); font-size: 0.875rem;">
                        <li style="margin-bottom: var(--space-sm);"><i class="fas fa-check" style="color: var(--primary-500); margin-right: var(--space-sm);"></i>Custom reports</li>
                        <li style="margin-bottom: var(--space-sm);"><i class="fas fa-check" style="color: var(--primary-500); margin-right: var(--space-sm);"></i>Real-time analytics</li>
                        <li style="margin-bottom: var(--space-sm);"><i class="fas fa-check" style="color: var(--primary-500); margin-right: var(--space-sm);"></i>Data visualization</li>
                        <li><i class="fas fa-check" style="color: var(--primary-500); margin-right: var(--space-sm);"></i>AI-powered insights</li>
                    </ul>
                </div>

                <!-- Security & Compliance -->
                <div class="feature-card reveal">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3 style="font-size: 1.5rem; font-weight: 700; color: var(--text-primary); margin-bottom: var(--space-md);">Security & Compliance</h3>
                    <p style="color: var(--text-secondary); margin-bottom: var(--space-lg); line-height: 1.6;">
                        Enterprise-grade security with role-based access control, compliance management, and advanced threat protection.
                    </p>
                    <ul style="list-style: none; color: var(--text-secondary); font-size: 0.875rem;">
                        <li style="margin-bottom: var(--space-sm);"><i class="fas fa-check" style="color: var(--primary-500); margin-right: var(--space-sm);"></i>Role-based access</li>
                        <li style="margin-bottom: var(--space-sm);"><i class="fas fa-check" style="color: var(--primary-500); margin-right: var(--space-sm);"></i>Data encryption</li>
                        <li style="margin-bottom: var(--space-sm);"><i class="fas fa-check" style="color: var(--primary-500); margin-right: var(--space-sm);"></i>Audit trails</li>
                        <li><i class="fas fa-check" style="color: var(--primary-500); margin-right: var(--space-sm);"></i>Compliance management</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Benefits Section -->
    <section id="benefits" class="section bg-white">
        <div class="container">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-4xl); align-items: center;">
                <div class="reveal">
                    <h2 class="section-title" style="text-align: left; margin-bottom: var(--space-xl);">
                        Why Choose Our <span class="text-gradient">HRMS Platform?</span>
                    </h2>
                    <div style="display: flex; flex-direction: column; gap: var(--space-xl);">
                        <div style="display: flex; align-items: flex-start; gap: var(--space-lg);">
                            <div style="width: 64px; height: 64px; background: var(--gradient-primary); border-radius: var(--radius-xl); display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: var(--shadow-md);">
                                <i class="fas fa-rocket" style="color: white; font-size: 1.5rem;"></i>
                            </div>
                            <div>
                                <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--text-primary); margin-bottom: var(--space-sm);">Increased Productivity</h3>
                                <p style="color: var(--text-secondary); line-height: 1.6;">Automate routine HR tasks and focus on strategic initiatives that drive business growth and employee satisfaction.</p>
                            </div>
                        </div>
                        <div style="display: flex; align-items: flex-start; gap: var(--space-lg);">
                            <div style="width: 64px; height: 64px; background: var(--gradient-secondary); border-radius: var(--radius-xl); display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: var(--shadow-md);">
                                <i class="fas fa-dollar-sign" style="color: white; font-size: 1.5rem;"></i>
                            </div>
                            <div>
                                <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--text-primary); margin-bottom: var(--space-sm);">Cost Reduction</h3>
                                <p style="color: var(--text-secondary); line-height: 1.6;">Reduce administrative costs by up to 60% with automated workflows, digital processes, and intelligent resource allocation.</p>
                            </div>
                        </div>
                        <div style="display: flex; align-items: flex-start; gap: var(--space-lg);">
                            <div style="width: 64px; height: 64px; background: var(--gradient-accent); border-radius: var(--radius-xl); display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: var(--shadow-md);">
                                <i class="fas fa-users" style="color: white; font-size: 1.5rem;"></i>
                            </div>
                            <div>
                                <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--text-primary); margin-bottom: var(--space-sm);">Better Employee Experience</h3>
                                <p style="color: var(--text-secondary); line-height: 1.6;">Empower employees with self-service portals, transparent communication channels, and mobile accessibility.</p>
                            </div>
                        </div>
                        <div style="display: flex; align-items: flex-start; gap: var(--space-lg);">
                            <div style="width: 64px; height: 64px; background: var(--gradient-secondary); border-radius: var(--radius-xl); display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: var(--shadow-md);">
                                <i class="fas fa-chart-line"  style="color: white; font-size: 1.5rem;"></i>
                            </div>
                            <div>
                            <div>
                                <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--text-primary); margin-bottom: var(--space-sm);">Data-Driven Decisions</h3>
                                <p style="color: var(--text-secondary); line-height: 1.6;">Make informed decisions with real-time analytics, comprehensive reporting tools, and AI-powered insights.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="reveal">
                    <div style="background: linear-gradient(135deg, var(--primary-50) 0%, var(--secondary-50) 100%); border-radius: var(--radius-3xl); padding: var(--space-2xl); border: 1px solid var(--primary-200);">
                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: var(--space-xl); margin-bottom: var(--space-2xl);">
                            <div style="text-align: center;">
                                <div style="font-size: 3rem; font-weight: 900; color: var(--primary-600); margin-bottom: var(--space-sm); font-family: 'Poppins', sans-serif;">60%</div>
                                <div style="font-size: 0.875rem; color: var(--text-secondary); font-weight: 600;">Cost Reduction</div>
                            </div>
                            <div style="text-align: center;">
                                <div style="font-size: 3rem; font-weight: 900; color: var(--secondary-600); margin-bottom: var(--space-sm); font-family: 'Poppins', sans-serif;">75%</div>
                                <div style="font-size: 0.875rem; color: var(--text-secondary); font-weight: 600;">Time Saved</div>
                            </div>
                            <div style="text-align: center;">
                                <div style="font-size: 3rem; font-weight: 900; color: var(--success); margin-bottom: var(--space-sm); font-family: 'Poppins', sans-serif;">98%</div>
                                <div style="font-size: 0.875rem; color: var(--text-secondary); font-weight: 600;">User Satisfaction</div>
                            </div>
                            <div style="text-align: center;">
                                <div style="font-size: 3rem; font-weight: 900; color: var(--warning); margin-bottom: var(--space-sm); font-family: 'Poppins', sans-serif;">24/7</div>
                                <div style="font-size: 0.875rem; color: var(--text-secondary); font-weight: 600;">Support</div>
                            </div>
                        </div>
                        <div style="text-align: center;">
                            <i class="fas fa-award" style="font-size: 4rem; color: var(--warning); margin-bottom: var(--space-lg);"></i>
                            <p style="font-size: 1.25rem; font-weight: 700; color: var(--gray-800); margin-bottom: var(--space-sm);">Award-Winning Platform</p>
                            <p style="font-size: 0.875rem; color: var(--text-secondary);">Recognized by industry leaders worldwide</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="section">
        <div class="container">
            <div class="text-center mb-6 reveal">
                <h2 class="section-title">
                    What Our <span class="text-gradient">Clients Say</span>
                </h2>
                <p class="section-subtitle">
                    Join thousands of satisfied customers worldwide who trust our platform
                </p>
            </div>
            <div class="grid-auto-fit">
                <div class="testimonial-card reveal">
                    <div class="testimonial-author">
                        <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=60&h=60&fit=crop&crop=face&auto=format" alt="John Smith" class="testimonial-avatar">
                        <div class="testimonial-info">
                            <h4>Dinesh Priyankara</h4>
                            <p>Manager Kokia Restaurant</p>
                        </div>
                    </div>
                    <div class="testimonial-content">
                        "It is a distinct pleasure for me to recommend Red Code Digital Marketing to any and all interested parties."
                    </div>
                    <div class="testimonial-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
                <div class="testimonial-card reveal">
                    <div class="testimonial-author">
                        <img src="https://images.unsplash.com/photo-1494790108755-2616b612b786?w=60&h=60&fit=crop&crop=face&auto=format" alt="Sarah Johnson" class="testimonial-avatar">
                        <div class="testimonial-info">
                            <h4>R.Tharindu</h4>
                            <p>CEO Teddy House</p>
                        </div>
                    </div>
                    <div class="testimonial-content">
                        "Professional, Creative, Attentive to Detail, Excellent Communication…If I had contracted with Red Code the first time I would not have had to do it twice."
                    </div>
                    <div class="testimonial-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
                <div class="testimonial-card reveal">
                    <div class="testimonial-author">
                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=60&h=60&fit=crop&crop=face&auto=format" alt="Mike Chen" class="testimonial-avatar">
                        <div class="testimonial-info">
                            <h4>Dr.Alanka</h4>
                            <p>CEO Mediplus Dental</p>
                        </div>
                    </div>
                    <div class="testimonial-content">
                        "These guys are true professionals in every aspect. They listened to my needs, offered relevant options, were prompt to respond to all of my questions, and most importantly, they never BS’d me about anything."
                    </div>
                    <div class="testimonial-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="section bg-white">
        <div class="container">
            <div class="text-center mb-6 reveal">
                <h2 class="section-title">
                    Get in <span class="text-gradient">Touch</span>
                </h2>
                <p class="section-subtitle">
                    Have questions? We're here to help you choose the best solution for your business needs.
                </p>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-4xl);">
                <div class="reveal">
                    <div class="contact-info-card">
                        <div class="contact-info-item">
                            <div class="contact-info-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="contact-info-content">
                                <h3>Our Location</h3>
                                <p>6/B, Poramba,<br>Ambalangoda, Sri Lanka.</p>
                            </div>
                        </div>
                        <div class="contact-info-item">
                            <div class="contact-info-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="contact-info-content">
                                <h3>Phone</h3>
                                <p>+94 76 881 0159</p>
                            </div>
                        </div>
                        <div class="contact-info-item">
                            <div class="contact-info-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="contact-info-content">
                                <h3>Email</h3>
                                <p>sameeraishan11@gmail.com<br>hr@redcodesolution.com</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="reveal">
                    <form style="display: flex; flex-direction: column; gap: var(--space-lg);">
                        <div class="form-group">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" id="name" name="name" class="form-control" placeholder="Enter your full name">
                        </div>
                        <div class="form-group">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email address">
                        </div>
                        <div class="form-group">
                            <label for="company" class="form-label">Company Name</label>
                            <input type="text" id="company" name="company" class="form-control" placeholder="Enter your company name">
                        </div>
                        <div class="form-group">
                            <label for="message" class="form-label">Message</label>
                            <textarea id="message" name="message" rows="4" class="form-control" placeholder="Tell us about your requirements"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-paper-plane"></i>
                            Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section section">
        <div class="container">
            <div class="cta-content reveal">
                <h2 class="cta-title">
                    Ready to Transform Your HR Management?
                </h2>
                <p class="cta-subtitle">
                    Join thousands of companies that have streamlined their workforce management with our HRMS platform.
                    Start your free trial today and experience the difference.
                </p>
                <div class="cta-buttons">
                    <a href="{{ route('register') }}" class="btn btn-outline btn-lg">
                        <i class="fas fa-rocket"></i>
                        Start Free Trial
                    </a>
                    <a href="#contact" class="btn btn-outline btn-lg">
                        <i class="fas fa-phone"></i>
                        Schedule Demo
                    </a>
                </div>
                <p class="cta-note">
                    No credit card required • 30-day free trial • Cancel anytime
                </p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: var(--space-2xl); margin-bottom: var(--space-2xl);">
                <div>
                    <div class="footer-brand">
                        <div class="footer-brand-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <span class="footer-brand-text">RedCode Solution</span>
                    </div>
                    <p class="footer-description">
                        The most comprehensive and intelligent employee management solution for modern enterprises worldwide.
                    </p>
                    <div class="footer-social">
                        <a href="#" class="footer-social-link">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="footer-social-link">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="footer-social-link">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a href="#" class="footer-social-link">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                </div>
                <div class="footer-section">
                    <h3>Product</h3>
                    <ul class="footer-links">
                        <li><a href="#">Features</a></li>
                        <li><a href="#">Pricing</a></li>
                        <li><a href="#">Integrations</a></li>
                        <li><a href="#">API Documentation</a></li>
                        <li><a href="#">Security</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Company</h3>
                    <ul class="footer-links">
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Careers</a></li>
                        <li><a href="#">Blog</a></li>
                        <li><a href="#">Press</a></li>
                        <li><a href="#">Partners</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Support</h3>
                    <ul class="footer-links">
                        <li><a href="#">Help Center</a></li>
                        <li><a href="#">Documentation</a></li>
                        <li><a href="#">Contact Us</a></li>
                        <li><a href="#">System Status</a></li>
                        <li><a href="#">Community</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p class="footer-copyright">
                    &copy; {{ date('Y') }} RedCode Solution. All rights reserved.
                </p>
                <div class="footer-legal">
                    <a href="#">Privacy Policy</a>
                    <a href="#">Terms of Service</a>
                    <a href="#">Cookie Policy</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <a href="#" class="back-to-top" id="backToTop">
        <i class="fas fa-arrow-up"></i>
    </a>

    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('d-none');
        });

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

        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }

            // Back to top button
            const backToTop = document.getElementById('backToTop');
            if (window.scrollY > 300) {
                backToTop.classList.add('visible');
            } else {
                backToTop.classList.remove('visible');
            }
        });

        // Back to top functionality
        document.getElementById('backToTop').addEventListener('click', function(e) {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // Scroll reveal animation
        const revealElements = document.querySelectorAll('.reveal');
        function checkReveal() {
            const windowHeight = window.innerHeight;
            const revealPoint = 150;
            revealElements.forEach(element => {
                const revealTop = element.getBoundingClientRect().top;
                if (revealTop < windowHeight - revealPoint) {
                    element.classList.add('active');
                }
            });
        }

        window.addEventListener('scroll', checkReveal);
        window.addEventListener('load', checkReveal);

        // Counter animation
        function animateCounters() {
            const counters = document.querySelectorAll('[data-count]');
            counters.forEach(counter => {
                const target = parseInt(counter.getAttribute('data-count'));
                const duration = 2000;
                const step = target / (duration / 16);
                let current = 0;
                const timer = setInterval(() => {
                    current += step;
                    if (current >= target) {
                        counter.textContent = target.toLocaleString();
                        clearInterval(timer);
                    } else {
                        counter.textContent = Math.floor(current).toLocaleString();
                    }
                }, 16);
            });
        }

        // Intersection Observer for counters
        const counterObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCounters();
                    counterObserver.disconnect();
                }
            });
        }, { threshold: 0.5 });

        const statsSection = document.querySelector('.stats-card');
        if (statsSection) {
            counterObserver.observe(statsSection);
        }

        // Loading animation for buttons
        document.querySelectorAll('.btn').forEach(button => {
            button.addEventListener('click', function(e) {
                if (this.href && (this.href.includes('register') || this.href.includes('login'))) {
                    const originalText = this.innerHTML;
                    this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
                    setTimeout(() => {
                        this.innerHTML = originalText;
                    }, 2000);
                }
            });
        });

        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault();
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
            setTimeout(() => {
                submitBtn.innerHTML = '<i class="fas fa-check"></i> Message Sent!';
                submitBtn.style.background = 'var(--gradient-success)';
                setTimeout(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.style.background = '';
                    this.reset();
                }, 2000);
            }, 1500);
        });

        document.querySelectorAll('.feature-card, .stats-card, .testimonial-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-12px) scale(1.02)';
            });
            card.addEventListener('mouseleave', function() {
                this.style.transform = '';
            });
        });

        window.addEventListener('load', function() {
            document.body.style.opacity = '1';
            checkReveal();
        });
    </script>

    @auth
        <div style="position: fixed; bottom: var(--space-lg); left: var(--space-lg); z-index: 1000;">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn" style="background: var(--gradient-accent); color: white; box-shadow: var(--shadow-lg);">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </button>
            </form>
        </div>
    @endauth
</body>
</html>
