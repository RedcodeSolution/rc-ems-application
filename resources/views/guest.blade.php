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
    <style>
        :root {
            /* Primary Red Color Palette */
            --primary: #E53935;
            --primary-dark: #B71C1C;
            --primary-light: #EF5350;
            --secondary: #D32F2F;
            --accent: #E53935;
            --success: #43A047;
            --warning: #FFB300;
            --danger: #E53935;
            --info: #1E88E5;

            /* Background Colors */
            --bg-light: #F5F5F5;
            --surface: #FFFFFF;

            /* Text Colors */
            --text-primary: #212121;
            --text-secondary: #757575;

            /* Border Colors */
            --border-color: #E0E0E0;

            /* Gray Scale */
            --gray-50: #FAFAFA;
            --gray-100: #F5F5F5;
            --gray-200: #EEEEEE;
            --gray-300: #E0E0E0;
            --gray-400: #BDBDBD;
            --gray-500: #9E9E9E;
            --gray-600: #757575;
            --gray-700: #616161;
            --gray-800: #424242;
            --gray-900: #212121;

            /* Red Color Variations */
            --primary-50: #FFEBEE;
            --primary-100: #FFCDD2;
            --primary-200: #EF9A9A;
            --primary-300: #E57373;
            --primary-400: #EF5350;
            --primary-500: #F44336;
            --primary-600: #E53935;
            --primary-700: #D32F2F;
            --primary-800: #C62828;
            --primary-900: #B71C1C;

            /* Secondary Red Variations */
            --secondary-50: #FFEBEE;
            --secondary-100: #FFCDD2;
            --secondary-200: #EF9A9A;
            --secondary-300: #E57373;
            --secondary-400: #EF5350;
            --secondary-500: #F44336;
            --secondary-600: #E53935;
            --secondary-700: #D32F2F;
            --secondary-800: #C62828;
            --secondary-900: #B71C1C;

            /* Advanced shadows */
            --shadow-xs: 0 1px 2px 0 rgba(229, 57, 53, 0.05);
            --shadow-sm: 0 1px 3px 0 rgba(229, 57, 53, 0.1), 0 1px 2px 0 rgba(229, 57, 53, 0.06);
            --shadow-md: 0 4px 6px -1px rgba(229, 57, 53, 0.1), 0 2px 4px -1px rgba(229, 57, 53, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(229, 57, 53, 0.1), 0 4px 6px -2px rgba(229, 57, 53, 0.05);
            --shadow-xl: 0 20px 25px -5px rgba(229, 57, 53, 0.1), 0 10px 10px -5px rgba(229, 57, 53, 0.04);
            --shadow-2xl: 0 25px 50px -12px rgba(229, 57, 53, 0.25);
            --shadow-inner: inset 0 2px 4px 0 rgba(229, 57, 53, 0.06);

            /* Professional gradients */
            --gradient-primary: linear-gradient(135deg, var(--primary-600) 0%, var(--primary-700) 50%, var(--primary-800) 100%);
            --gradient-secondary: linear-gradient(135deg, var(--secondary-600) 0%, var(--secondary-700) 50%, var(--secondary-800) 100%);
            --gradient-accent: linear-gradient(135deg, var(--accent) 0%, #D32F2F 50%, #B71C1C 100%);
            --gradient-success: linear-gradient(135deg, var(--success) 0%, #388E3C 50%, #2E7D32 100%);
            --gradient-hero: linear-gradient(135deg, var(--primary-600) 0%, var(--primary-700) 25%, var(--secondary-600) 75%, var(--secondary-700) 100%);
            --gradient-glass: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);

            /* Border radius scale */
            --radius-xs: 0.125rem;
            --radius-sm: 0.25rem;
            --radius-md: 0.375rem;
            --radius-lg: 0.5rem;
            --radius-xl: 0.75rem;
            --radius-2xl: 1rem;
            --radius-3xl: 1.5rem;
            --radius-4xl: 2rem;
            --radius-full: 9999px;

            /* Spacing scale */
            --space-xs: 0.25rem;
            --space-sm: 0.5rem;
            --space-md: 1rem;
            --space-lg: 1.5rem;
            --space-xl: 2rem;
            --space-2xl: 3rem;
            --space-3xl: 4rem;
            --space-4xl: 6rem;
            --space-5xl: 8rem;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: var(--text-primary);
            overflow-x: hidden;
            background-color: var(--bg-light);
            font-feature-settings: 'kern' 1, 'liga' 1, 'calt' 1;
            text-rendering: optimizeLegibility;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        html {
            scroll-behavior: smooth;
            scroll-padding-top: 80px;
        }

        /* Advanced Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInLeft {
            from {
                opacity: 0;
                transform: translateX(-40px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(40px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-20px);
            }
        }

        @keyframes floatSlow {
            0%, 100% {
                transform: translateY(0px) rotate(0deg);
            }
            50% {
                transform: translateY(-30px) rotate(5deg);
            }
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                opacity: 1;
            }
            50% {
                transform: scale(1.05);
                opacity: 0.8;
            }
        }

        @keyframes slideInFromTop {
            from {
                opacity: 0;
                transform: translateY(-100px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes glow {
            0%, 100% {
                box-shadow: 0 0 20px rgba(229, 57, 53, 0.3);
            }
            50% {
                box-shadow: 0 0 40px rgba(229, 57, 53, 0.6);
            }
        }

        @keyframes rotate {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }

        @keyframes bounce {
            0%, 20%, 53%, 80%, 100% {
                transform: translate3d(0, 0, 0);
            }
            40%, 43% {
                transform: translate3d(0, -30px, 0);
            }
            70% {
                transform: translate3d(0, -15px, 0);
            }
            90% {
                transform: translate3d(0, -4px, 0);
            }
        }

        /* Animation Classes */
        .animate-fadeInUp {
            animation: fadeInUp 0.8s ease-out forwards;
        }

        .animate-fadeInDown {
            animation: fadeInDown 0.8s ease-out forwards;
        }

        .animate-fadeInLeft {
            animation: fadeInLeft 0.8s ease-out forwards;
        }

        .animate-fadeInRight {
            animation: fadeInRight 0.8s ease-out forwards;
        }

        .animate-scaleIn {
            animation: scaleIn 0.6s ease-out forwards;
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        .animate-floatSlow {
            animation: floatSlow 8s ease-in-out infinite;
        }

        .animate-pulse-custom {
            animation: pulse 2s ease-in-out infinite;
        }

        .animate-slideInFromTop {
            animation: slideInFromTop 0.6s ease-out forwards;
        }

        .animate-glow {
            animation: glow 2s ease-in-out infinite;
        }

        .animate-bounce {
            animation: bounce 2s infinite;
        }

        /* Professional Gradient Backgrounds */
        .gradient-primary {
            background: var(--gradient-primary);
        }

        .gradient-secondary {
            background: var(--gradient-secondary);
        }

        .gradient-accent {
            background: var(--gradient-accent);
        }

        .gradient-success {
            background: var(--gradient-success);
        }

        .gradient-hero {
            background: var(--gradient-hero);
        }

        .gradient-glass {
            background: var(--gradient-glass);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .gradient-text {
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 700;
        }

        /* Professional Button Styles */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: var(--space-sm);
            padding: var(--space-md) var(--space-xl);
            border-radius: var(--radius-xl);
            font-weight: 600;
            font-size: 1rem;
            line-height: 1.5;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            border: none;
            cursor: pointer;
            user-select: none;
            white-space: nowrap;
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
            color: white;
            box-shadow: var(--shadow-lg);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-xl);
        }

        .btn-primary:active {
            transform: translateY(0);
            box-shadow: var(--shadow-md);
        }

        .btn-secondary {
            background: transparent;
            color: var(--primary-600);
            border: 2px solid var(--primary-600);
            box-shadow: var(--shadow-sm);
        }

        .btn-secondary:hover {
            background: var(--primary-600);
            color: white;
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn-outline {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
        }

        .btn-outline:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.5);
            transform: translateY(-2px);
        }

        .btn-lg {
            padding: var(--space-lg) var(--space-2xl);
            font-size: 1.125rem;
            border-radius: var(--radius-2xl);
        }

        .btn-sm {
            padding: var(--space-sm) var(--space-lg);
            font-size: 0.875rem;
            border-radius: var(--radius-lg);
        }

        /* Professional Card Styles */
        .card {
            background: var(--surface);
            border-radius: var(--radius-2xl);
            box-shadow: var(--shadow-sm);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid var(--border-color);
            position: relative;
            overflow: hidden;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-primary);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .card:hover::before {
            transform: scaleX(1);
        }

        .card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-xl);
            border-color: var(--primary-200);
        }

        .feature-card {
            padding: var(--space-2xl);
            background: var(--surface);
            border-radius: var(--radius-2xl);
            box-shadow: var(--shadow-sm);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid var(--border-color);
            position: relative;
            overflow: hidden;
            height: 100%;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-primary);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .feature-card:hover::before {
            transform: scaleX(1);
        }

        .feature-card:hover {
            transform: translateY(-12px);
            box-shadow: var(--shadow-2xl);
            border-color: var(--primary-300);
        }

        .feature-icon {
            width: 72px;
            height: 72px;
            border-radius: var(--radius-xl);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin-bottom: var(--space-lg);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .feature-icon::before {
            content: '';
            position: absolute;
            inset: 0;
            background: var(--gradient-glass);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .feature-card:hover .feature-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .feature-card:hover .feature-icon::before {
            opacity: 1;
        }

        /* Professional Stats Cards */
        .stats-card {
            background: var(--surface);
            padding: var(--space-2xl);
            border-radius: var(--radius-2xl);
            text-align: center;
            box-shadow: var(--shadow-sm);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid var(--border-color);
            position: relative;
            overflow: hidden;
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-primary);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .stats-card:hover::before {
            transform: scaleX(1);
        }

        .stats-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-xl);
            border-color: var(--primary-200);
        }

        .stats-number {
            font-size: 3.5rem;
            font-weight: 900;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1;
            margin-bottom: var(--space-sm);
            font-family: 'Poppins', sans-serif;
        }

        .stats-label {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* Professional Navigation */
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border-color);
            transition: all 0.3s ease;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }

        .navbar.scrolled {
            background: rgba(255, 255, 255, 0.98);
            box-shadow: var(--shadow-lg);
            border-bottom-color: var(--primary-200);
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: var(--space-md);
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--text-primary);
            text-decoration: none;
        }

        .navbar-brand-icon {
            width: 48px;
            height: 48px;
            background: var(--gradient-primary);
            border-radius: var(--radius-xl);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            box-shadow: var(--shadow-md);
        }

        .navbar-nav {
            display: flex;
            align-items: center;
            gap: var(--space-2xl);
        }

        .nav-link {
            color: var(--text-secondary);
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
            padding: var(--space-sm) 0;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--gradient-primary);
            transition: width 0.3s ease;
        }

        .nav-link:hover {
            color: var(--primary-600);
        }

        .nav-link:hover::after {
            width: 100%;
        }

        /* Professional Hero Section */
        .hero-section {
            background: var(--gradient-hero);
            position: relative;
            overflow: hidden;
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding-top: 80px;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.5;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-title {
            font-size: clamp(2.5rem, 5vw, 4rem);
            font-weight: 900;
            line-height: 1.1;
            color: white;
            margin-bottom: var(--space-lg);
            font-family: 'Poppins', sans-serif;
        }

        .hero-subtitle {
            font-size: 1.25rem;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: var(--space-2xl);
            line-height: 1.6;
            max-width: 600px;
        }

        .hero-features {
            display: flex;
            flex-wrap: wrap;
            gap: var(--space-lg);
            margin-top: var(--space-2xl);
        }

        .hero-feature {
            display: flex;
            align-items: center;
            gap: var(--space-sm);
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.875rem;
            font-weight: 500;
        }

        .hero-feature i {
            color: var(--warning);
            font-size: 1rem;
        }

        /* Floating Elements */
        .floating-element {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .floating-1 {
            width: 120px;
            height: 120px;
            top: 15%;
            left: 8%;
            animation: floatSlow 8s ease-in-out infinite;
        }

        .floating-2 {
            width: 180px;
            height: 180px;
            top: 60%;
            right: 10%;
            animation: floatSlow 10s ease-in-out infinite reverse;
        }

        .floating-3 {
            width: 100px;
            height: 100px;
            bottom: 20%;
            left: 15%;
            animation: floatSlow 12s ease-in-out infinite;
        }

        .floating-4 {
            width: 60px;
            height: 60px;
            top: 30%;
            right: 25%;
            animation: floatSlow 6s ease-in-out infinite;
        }

        /* Professional Dashboard Preview */
        .dashboard-preview {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: var(--radius-3xl);
            padding: var(--space-2xl);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: var(--shadow-2xl);
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: var(--space-lg);
            margin-bottom: var(--space-xl);
        }

        .dashboard-stat {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: var(--radius-xl);
            padding: var(--space-lg);
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .dashboard-stat-number {
            font-size: 1.75rem;
            font-weight: 800;
            color: white;
            margin-bottom: var(--space-xs);
        }

        .dashboard-stat-label {
            font-size: 0.875rem;
            color: rgba(255, 255, 255, 0.8);
            font-weight: 500;
        }

        .dashboard-chart {
            text-align: center;
            color: white;
        }

        .dashboard-chart i {
            font-size: 3rem;
            color: var(--warning);
            margin-bottom: var(--space-md);
        }

        /* Professional Testimonials */
        .testimonial-card {
            background: var(--surface);
            padding: var(--space-2xl);
            border-radius: var(--radius-2xl);
            box-shadow: var(--shadow-sm);
            position: relative;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid var(--border-color);
            height: 100%;
        }

        .testimonial-card::before {
            content: '"';
            position: absolute;
            top: -10px;
            left: var(--space-xl);
            font-size: 4rem;
            color: var(--primary-200);
            opacity: 0.5;
            font-family: serif;
            line-height: 1;
        }

        .testimonial-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-xl);
            border-color: var(--primary-200);
        }

        .testimonial-content {
            font-style: italic;
            color: var(--text-secondary);
            margin-bottom: var(--space-lg);
            line-height: 1.7;
            font-size: 1.1rem;
        }

        .testimonial-author {
            display: flex;
            align-items: center;
            gap: var(--space-md);
            margin-bottom: var(--space-md);
        }

        .testimonial-avatar {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--primary-100);
        }

        .testimonial-info h4 {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: var(--space-xs);
        }

        .testimonial-info p {
            color: var(--text-secondary);
            font-size: 0.875rem;
        }

        .testimonial-rating {
            display: flex;
            gap: var(--space-xs);
            color: var(--warning);
        }

        /* Professional Contact Section */
        .contact-info-card {
            background: var(--gray-50);
            padding: var(--space-2xl);
            border-radius: var(--radius-2xl);
            border: 1px solid var(--border-color);
            height: 100%;
        }

        .contact-info-item {
            display: flex;
            align-items: flex-start;
            gap: var(--space-lg);
            margin-bottom: var(--space-2xl);
        }

        .contact-info-item:last-child {
            margin-bottom: 0;
        }

        .contact-info-icon {
            width: 56px;
            height: 56px;
            background: var(--gradient-primary);
            border-radius: var(--radius-xl);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
            flex-shrink: 0;
            box-shadow: var(--shadow-md);
        }

        .contact-info-content h3 {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: var(--space-sm);
            font-size: 1.125rem;
        }

        .contact-info-content p {
            color: var(--text-secondary);
            line-height: 1.6;
        }

        /* Professional Form Styles */
        .form-group {
            margin-bottom: var(--space-lg);
        }

        .form-label {
            display: block;
            margin-bottom: var(--space-sm);
            font-weight: 600;
            color: var(--text-secondary);
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .form-control {
            width: 100%;
            padding: var(--space-lg);
            border: 2px solid var(--border-color);
            border-radius: var(--radius-xl);
            font-size: 1rem;
            transition: all 0.3s ease;
            background-color: var(--surface);
            font-family: inherit;
        }

        .form-control:focus {
            border-color: var(--primary-500);
            box-shadow: 0 0 0 4px rgba(229, 57, 53, 0.1);
            outline: none;
        }

        .form-control::placeholder {
            color: var(--gray-400);
        }

        /* Professional CTA Section */
        .cta-section {
            background: var(--gradient-hero);
            position: relative;
            overflow: hidden;
        }

        .cta-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M20 20c0-11.046-8.954-20-20-20v20h20z'/%3E%3C/g%3E%3C/svg%3E");
        }

        .cta-content {
            position: relative;
            z-index: 2;
            text-align: center;
            color: white;
        }

        .cta-title {
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 800;
            margin-bottom: var(--space-lg);
            font-family: 'Poppins', sans-serif;
        }

        .cta-subtitle {
            font-size: 1.25rem;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: var(--space-2xl);
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
            line-height: 1.6;
        }

        .cta-buttons {
            display: flex;
            flex-direction: column;
            gap: var(--space-lg);
            align-items: center;
            margin-bottom: var(--space-xl);
        }

        @media (min-width: 640px) {
            .cta-buttons {
                flex-direction: row;
                justify-content: center;
            }
        }

        .cta-note {
            font-size: 0.875rem;
            color: rgba(255, 255, 255, 0.8);
            font-weight: 500;
        }

        /* Professional Footer */
        .footer {
            background: var(--gray-900);
            color: white;
            padding-top: var(--space-4xl);
            padding-bottom: var(--space-2xl);
        }

        .footer-brand {
            display: flex;
            align-items: center;
            gap: var(--space-md);
            margin-bottom: var(--space-lg);
        }

        .footer-brand-icon {
            width: 48px;
            height: 48px;
            background: var(--gradient-primary);
            border-radius: var(--radius-xl);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
        }

        .footer-brand-text {
            font-size: 1.5rem;
            font-weight: 800;
        }

        .footer-description {
            color: var(--gray-400);
            margin-bottom: var(--space-lg);
            line-height: 1.6;
        }

        .footer-social {
            display: flex;
            gap: var(--space-md);
        }

        .footer-social-link {
            width: 44px;
            height: 44px;
            background: var(--gray-800);
            border-radius: var(--radius-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gray-400);
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .footer-social-link:hover {
            background: var(--primary-600);
            color: white;
            transform: translateY(-2px);
        }

        .footer-section h3 {
            font-size: 1.125rem;
            font-weight: 600;
            margin-bottom: var(--space-lg);
            color: white;
        }

        .footer-links {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: var(--space-md);
        }

        .footer-links a {
            color: var(--gray-400);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-links a:hover {
            color: var(--primary-400);
        }

        .footer-bottom {
            border-top: 1px solid var(--gray-800);
            margin-top: var(--space-2xl);
            padding-top: var(--space-xl);
            display: flex;
            flex-direction: column;
            gap: var(--space-lg);
            align-items: center;
            text-align: center;
        }

        @media (min-width: 768px) {
            .footer-bottom {
                flex-direction: row;
                justify-content: space-between;
                text-align: left;
            }
        }

        .footer-copyright {
            color: var(--gray-400);
            font-size: 0.875rem;
        }

        .footer-legal {
            display: flex;
            gap: var(--space-lg);
        }

        .footer-legal a {
            color: var(--gray-400);
            text-decoration: none;
            font-size: 0.875rem;
            transition: color 0.3s ease;
        }

        .footer-legal a:hover {
            color: var(--primary-400);
        }

        /* Back to Top Button */
        .back-to-top {
            position: fixed;
            bottom: var(--space-xl);
            right: var(--space-xl);
            width: 56px;
            height: 56px;
            background: var(--gradient-primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            box-shadow: var(--shadow-lg);
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .back-to-top.visible {
            opacity: 1;
            visibility: visible;
        }

        .back-to-top:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-xl);
        }

        /* Mobile Menu */
        .mobile-menu {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border-top: 1px solid var(--border-color);
            padding: var(--space-lg);
        }

        .mobile-menu-item {
            display: block;
            padding: var(--space-md) 0;
            color: var(--text-secondary);
            text-decoration: none;
            font-weight: 500;
            border-bottom: 1px solid var(--gray-100);
            transition: color 0.3s ease;
        }

        .mobile-menu-item:hover {
            color: var(--primary-600);
        }

        .mobile-menu-item:last-child {
            border-bottom: none;
        }

        /* Scroll Reveal Animation */
        .reveal {
            opacity: 0;
            transform: translateY(40px);
            transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }

        /* Loading Animation */
        .loading-dots::after {
            content: '';
            animation: dots 2s infinite;
        }

        @keyframes dots {
            0%, 20% { content: ''; }
            40% { content: '.'; }
            60% { content: '..'; }
            80%, 100% { content: '...'; }
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--gray-100);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--gradient-primary);
            border-radius: var(--radius-full);
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-700);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem !important;
            }
            .feature-card {
                padding: var(--space-lg);
            }
            .stats-number {
                font-size: 2.5rem;
            }
            .navbar-nav {
                display: none;
            }
            .hero-features {
                flex-direction: column;
                gap: var(--space-md);
            }
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Enhanced Hover Effects */
        .hover-lift {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .hover-lift:hover {
            transform: translateY(-4px);
        }

        /* Gradient Border Animation */
        .gradient-border {
            position: relative;
            background: var(--surface);
            border-radius: var(--radius-2xl);
        }

        .gradient-border::before {
            content: '';
            position: absolute;
            inset: 0;
            padding: 2px;
            background: var(--gradient-primary);
            border-radius: inherit;
            mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            mask-composite: exclude;
        }

        /* Professional Section Spacing */
        .section {
            padding: var(--space-4xl) 0;
        }

        .section-sm {
            padding: var(--space-3xl) 0;
        }

        .section-lg {
            padding: var(--space-5xl) 0;
        }

        /* Professional Typography */
        .text-gradient {
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .section-title {
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 800;
            color: var(--text-primary);
            margin-bottom: var(--space-lg);
            font-family: 'Poppins', sans-serif;
            line-height: 1.2;
        }

        .section-subtitle {
            font-size: 1.25rem;
            color: var(--text-secondary);
            margin-bottom: var(--space-2xl);
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
            line-height: 1.6;
        }

        /* Professional Grid System */
        .grid-auto-fit {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: var(--space-2xl);
        }

        .grid-auto-fill {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: var(--space-xl);
        }

        /* Professional Utilities */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 var(--space-lg);
        }

        .container-lg {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 var(--space-lg);
        }

        .text-center {
            text-align: center;
        }

        .mb-0 { margin-bottom: 0; }
        .mb-1 { margin-bottom: var(--space-xs); }
        .mb-2 { margin-bottom: var(--space-sm); }
        .mb-3 { margin-bottom: var(--space-md); }
        .mb-4 { margin-bottom: var(--space-lg); }
        .mb-5 { margin-bottom: var(--space-xl); }
        .mb-6 { margin-bottom: var(--space-2xl); }
        .mt-0 { margin-top: 0; }
        .mt-1 { margin-top: var(--space-xs); }
        .mt-2 { margin-top: var(--space-sm); }
        .mt-3 { margin-top: var(--space-md); }
        .mt-4 { margin-top: var(--space-lg); }
        .mt-5 { margin-top: var(--space-xl); }
        .mt-6 { margin-top: var(--space-2xl); }
        .d-flex { display: flex; }
        .align-items-center { align-items: center; }
        .justify-content-center { justify-content: center; }
        .justify-content-between { justify-content: space-between; }
        .flex-column { flex-direction: column; }
        .flex-wrap { flex-wrap: wrap; }
        .gap-1 { gap: var(--space-xs); }
        .gap-2 { gap: var(--space-sm); }
        .gap-3 { gap: var(--space-md); }
        .gap-4 { gap: var(--space-lg); }
        .gap-5 { gap: var(--space-xl); }
        .w-100 { width: 100%; }
        .h-100 { height: 100%; }
        .text-white { color: white; }
        .text-primary { color: var(--primary-600); }
        .text-secondary { color: var(--secondary-600); }
        .text-success { color: var(--success); }
        .text-warning { color: var(--warning); }
        .text-danger { color: var(--danger); }
        .bg-white { background-color: var(--surface); }
        .bg-gray-50 { background-color: var(--gray-50); }
        .bg-gray-100 { background-color: var(--gray-100); }
        .d-none { display: none; }
        .d-block { display: block; }
        .d-inline { display: inline; }
        .d-inline-block { display: inline-block; }
        @media (min-width: 768px) {
            .d-md-block { display: block; }
            .d-md-none { display: none; }
            .d-md-flex { display: flex; }
        }
        @media (max-width: 767px) {
            .d-mobile-none { display: none; }
            .d-mobile-block { display: block; }
        }
    </style>
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
                    <span class="gradient-text">HRMS Pro</span>
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
                            Streamline your workforce management with our comprehensive, AI-powered HRMS solution.
                            Manage employees, projects, payroll, and performance analytics all in one powerful platform.
                        </p>
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
                            <div class="hero-feature">
                                <i class="fas fa-check-circle"></i>
                                <span>30-day free trial</span>
                            </div>
                            <div class="hero-feature">
                                <i class="fas fa-check-circle"></i>
                                <span>No credit card required</span>
                            </div>
                            <div class="hero-feature">
                                <i class="fas fa-check-circle"></i>
                                <span>24/7 premium support</span>
                            </div>
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
                    <div class="feature-icon gradient-primary">
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
                    <div class="feature-icon gradient-secondary">
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
                    <div class="feature-icon gradient-accent">
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
                    <div class="feature-icon" style="background: linear-gradient(135deg, #E53935 0%, #D32F2F 100%); color: white;">
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
                    <div class="feature-icon" style="background: linear-gradient(135deg, #E53935 0%, #B71C1C 100%); color: white;">
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
                            <div style="width: 64px; height: 64px; background: var(--gradient-success); border-radius: var(--radius-xl); display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: var(--shadow-md);">
                                <i class="fas fa-chart-line" style="color: white; font-size: 1.5rem;"></i>
                            </div>
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
                            <h4>John Smith</h4>
                            <p>CEO, TechCorp Industries</p>
                        </div>
                    </div>
                    <div class="testimonial-content">
                        "This HRMS has completely transformed how we manage our 1000+ employees. The automation features and AI insights have saved us countless hours every week and improved our decision-making process significantly."
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
                            <h4>Sarah Johnson</h4>
                            <p>HR Director, InnovateLab</p>
                        </div>
                    </div>
                    <div class="testimonial-content">
                        "The reporting capabilities are absolutely outstanding. We can now make data-driven decisions about our workforce with complete confidence. The real-time analytics have been a game-changer for our organization."
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
                            <h4>Mike Chen</h4>
                            <p>Operations Manager, GlobalTech</p>
                        </div>
                    </div>
                    <div class="testimonial-content">
                        "Implementation was seamless and the support team is exceptional. Our employees love the self-service features and mobile accessibility. It's made our HR processes so much more efficient."
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
                                <p>123 Business Avenue, Tech District<br>New York, NY 10001, USA</p>
                            </div>
                        </div>
                        <div class="contact-info-item">
                            <div class="contact-info-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="contact-info-content">
                                <h3>Phone</h3>
                                <p>+1 (555) 123-4567<br>+1 (555) 987-6543</p>
                            </div>
                        </div>
                        <div class="contact-info-item">
                            <div class="contact-info-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="contact-info-content">
                                <h3>Email</h3>
                                <p>info@hrmspro.com<br>support@hrmspro.com</p>
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
                    <a href="{{ route('register') }}" class="btn btn-lg" style="background: white; color: var(--primary-600); font-weight: 700;">
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
                        <span class="footer-brand-text">HRMS Pro</span>
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
                    &copy; {{ date('Y') }} HRMS Pro. All rights reserved.
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

        // Smooth scrolling for navigation links
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

        // Navbar scroll effect
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

        // Form submission
        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault();
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
            // Simulate form submission
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

        // Add some interactive effects
        document.querySelectorAll('.feature-card, .stats-card, .testimonial-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-12px) scale(1.02)';
            });
            card.addEventListener('mouseleave', function() {
                this.style.transform = '';
            });
        });

        // Initialize animations on load
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
