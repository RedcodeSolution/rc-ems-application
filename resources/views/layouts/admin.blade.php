<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'EMS') }} - @yield('title', 'Admin Dashboard')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div id="app">
        <div class="sidebar" id="sidebar">
            <div class="sidebar-brand">
                <h2><i class="fas fa-building mr-2"></i>RedCode Solutions</h2>
                <div class="subtitle">Employee Management System</div>
            </div>

            <div class="sidebar-menu">
                <div class="sidebar-menu-section">
                    <div class="sidebar-menu-title">Dashboard</div>
                    <a href="{{ route('admin.dashboard') }}"
                        class="sidebar-menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard Overview</span>
                    </a>
                </div>

                <div class="sidebar-menu-section">
                    <div class="sidebar-menu-title">Employee Management</div>
                    <a href="{{ route('admin.employees') }}"
                        class="sidebar-menu-item {{ request()->routeIs('admin.employees') ? 'active' : '' }}">
                        <i class="fas fa-users"></i>
                        <span>Employees</span>
                    </a>
                    <a href="{{ route('admin.departments.index') }}"
                        class="sidebar-menu-item {{ request()->routeIs('departments.*') ? 'active' : '' }}">
                        <i class="fas fa-building"></i>
                        <span>Departments</span>
                    </a>
                    <a href="{{ route('admin.attendance.index') }}"
                        class="sidebar-menu-item {{ request()->routeIs('admin.attendance.*') ? 'active' : '' }}">
                        <i class="fas fa-calendar-check"></i>
                        <span>Attendance Count</span>
                    </a>
                    <a href="{{ route('admin.teams') }}"
                        class="sidebar-menu-item {{ request()->routeIs('admin.teams') ? 'active' : '' }}">
                        <i class="fas fa-users-cog"></i>
                        <span>Teams</span>
                    </a>
                    <a href="{{ route('admin.employeeRatings.index') }}"
                        class="sidebar-menu-item {{ request()->routeIs('admin.employeeRatings.*') ? 'active' : '' }}">
                        <i class="fas fa-star"></i>
                        <span>Employee Ratings</span>
                    </a>
                </div>

                <div class="sidebar-menu-section">
                    <div class="sidebar-menu-title">Project Management</div>
                    <a href="{{ route('admin.projects.index') }}"
                        class="sidebar-menu-item {{ request()->routeIs('admin.projects') ? 'active' : '' }}">
                        <i class="fas fa-project-diagram"></i>
                        <span>Projects</span>
                    </a>
                    <a href="{{ url('/admin/leaves') }}"
                        class="sidebar-menu-item {{ request()->routeIs('admin.leaves') ? 'active' : '' }}">
                        <i class="fas fa-calendar-times"></i>
                        <span>Leave Management</span>
                    </a>
                </div>

                <div class="sidebar-menu-section">
                    <div class="sidebar-menu-title">Reports & Analytics</div>
                    <a href="{{ route('admin.reports.index') }}"
                        class="sidebar-menu-item {{ request()->routeIs('admin.reports') ? 'active' : '' }}">
                        <i class="fas fa-chart-bar"></i>
                        <span>Reports</span>
                    </a>
                    <a href="{{ route('admin.announcements') }}"
                        class="sidebar-menu-item {{ request()->routeIs('admin.announcements') ? 'active' : '' }}">
                        <i class="fas fa-bullhorn"></i>
                        <span>Announcements</span>
                    </a>
                </div>

                <div class="sidebar-menu-section">
                    <div class="sidebar-menu-title">Administration</div>
                    <a href="{{ route('admin.profile.index') }}"
                        class="sidebar-menu-item {{ request()->routeIs('admin.profile') ? 'active' : '' }}">
                        <i class="fas fa-user-shield"></i>
                        <span>Admin Profile</span>
                    </a>
                    <a href="{{ route('admin.documents.index') }}"
                        class="sidebar-menu-item {{ request()->routeIs('admin.documents') ? 'active' : '' }}">
                        <i class="fas fa-file-alt"></i>
                        <span>Documents</span>
                    </a>
                    <a href="{{ route('admin.notifications') }}"
                        class="sidebar-menu-item {{ request()->routeIs('admin.notifications') ? 'active' : '' }}">
                        <i class="fas fa-bell"></i>
                        <span>Notifications</span>
                        <span class="badge">{{ $notificationStats['unread'] ?? 0 }}</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="main-content">

            <div class="top-nav">
                <div class="nav-title">
                    <button class="mobile-menu-btn" onclick="toggleSidebar()">
                        <i class="fas fa-bars"></i>
                    </button>
                    <i class="fas fa-tachometer-alt"></i>
                    @yield('title', 'Dashboard')
                </div>

                <div class="nav-actions">
                    <a href="{{ route('admin.notifications.latest') }}" class="nav-bell" title="Notifications"
                        id="navBellBtn" type="button">
                        <span class="nav-bell-icon">
                            <i class="fas fa-bell"></i>
                            @if (($notificationStats['unread'] ?? 0) > 0)
                                <span class="nav-bell-dot"></span>
                            @endif
                        </span>
                    </a>
                    <div class="user-menu" id="userMenuBtn">
                        <div class="user-avatar">
                            {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                        </div>
                        <div class="user-info">
                            <h4>{{ Auth::user()->name ?? 'Admin User' }}</h4>
                            <p>{{ ucfirst(Auth::user()->role ?? 'admin') }}</p>
                        </div>
                        <i class="fas fa-chevron-down" style="color: var(--gray-400);"></i>
                        
                        <div class="user-dropdown" id="userDropdown">
                            <a href="{{ route('admin.profile.index') }}" class="dropdown-item">
                                <i class="fas fa-user-shield"></i> Profile
                            </a>
                            <div class="dropdown-divider"></div>
                            <form method="POST" action="{{ route('logout') }}" id="logout-form-dropdown">
                                @csrf
                                <button type="button" class="dropdown-item text-danger" onclick="confirmLogout(event, 'logout-form-dropdown')">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Mobile Profile Button (Visible only on mobile) -->
                    <button class="mobile-profile-btn" id="mobileProfileBtn" style="display: none;">
                        <div class="user-avatar">
                            {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                        </div>
                    </button>

                </div>
            </div>


            <div class="content-area">
                @if (session('success'))
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ session('error') }}
                    </div>
                @endif

                @if (session('warning'))
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        {{ session('warning') }}
                    </div>
                @endif

                @if (session('info'))
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        {{ session('info') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <div id="notificationModalDrop" class="modal-dropdown" style="display:none;">
        <div class="modal-dropdown-bg"></div>
        <div class="modal-dropdown-content">
            <div class="modal-dropdown-header">
                <h3><i class="fas fa-bell"></i> Notifications</h3>
                <div style="display: flex; align-items: center; gap: 15px;">
                    <button id="markAllBtn" class="mark-all-btn">
                        <i class="fas fa-check-double"></i> Mark all read
                    </button>
                    <button class="modal-close" id="closenotificationModalDrop">&times;</button>
                </div>
            </div>
            <div class="modal-dropdown-body" id="latestNotifications">
                <p style="text-align:center; color: gray;">Loading...</p>
            </div>
        </div>
    </div>

    <!-- Mobile Profile Modal Overlay -->
    <div id="mobileProfileModal" class="mobile-profile-modal" style="display: none;">
        <div class="mobile-profile-bg"></div>
        <div class="mobile-profile-content">
            <div class="mobile-profile-header">
                <div class="user-avatar large">
                    {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                </div>
                <div class="mobile-user-info">
                    <h4>{{ Auth::user()->name ?? 'Admin User' }}</h4>
                    <p>{{ ucfirst(Auth::user()->role ?? 'admin') }}</p>
                </div>
                <!-- Close button removed -->
            </div>
            <div class="mobile-profile-body">
                <a href="{{ route('admin.profile.index') }}" class="mobile-menu-item">
                    <i class="fas fa-user-shield"></i> Admin Profile
                </a>
                <form method="POST" action="{{ route('logout') }}" id="logout-form-mobile">
                    @csrf
                    <button type="button" class="mobile-menu-item text-danger" onclick="confirmMobileLogout()">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Mobile Sidebar Toggle
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('open');
            // Prevent body scroll when sidebar is open on mobile
            if (window.innerWidth <= 450) {
                if (sidebar.classList.contains('open')) {
                    document.body.style.overflow = 'hidden';
                } else {
                    document.body.style.overflow = '';
                }
            }
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(e) {
            const sidebar = document.getElementById('sidebar');
            const mobileBtn = document.querySelector('.mobile-menu-btn');
            if (
                window.innerWidth <= 450 &&
                sidebar.classList.contains('open') &&
                !sidebar.contains(e.target) &&
                !mobileBtn.contains(e.target)
            ) {
                sidebar.classList.remove('open');
                document.body.style.overflow = '';
            }
        });

        // Close sidebar on mobile when a sidebar link is clicked and allow navigation
        document.querySelectorAll('.sidebar-menu-item').forEach(function(link) {
            link.addEventListener('click', function(e) {
                const sidebar = document.getElementById('sidebar');
                if (window.innerWidth <= 450 && sidebar.classList.contains('open')) {
                    sidebar.classList.remove('open');
                    document.body.style.overflow = '';
                    // Use a short timeout to allow sidebar to close before navigation
                    if (link.tagName.toLowerCase() === 'a' && link.href) {
                        e.preventDefault();
                        setTimeout(() => {
                            window.location = link.href;
                        }, 120);
                    }
                }
                // For desktop or if sidebar not open, let navigation proceed normally
            });
        });

        // Auto-hide flash messages
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-10px)';
                setTimeout(() => alert.remove(), 300);
            });
        }, 5000);

        // Add loading state to buttons
        document.querySelectorAll('.btn').forEach(btn => {
            btn.addEventListener('click', function() {
                if (this.type === 'submit' || this.href) {
                    this.classList.add('loading');
                    const originalText = this.innerHTML;
                    this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';

                    setTimeout(() => {
                        this.innerHTML = originalText;
                        this.classList.remove('loading');
                    }, 2000);
                }
            });
        });

        /* ============================
           MOBILE PROFILE MODAL
        ============================ */
        document.addEventListener('DOMContentLoaded', function() {
            const mobileBtn = document.getElementById('mobileProfileBtn');
            const mobileModal = document.getElementById('mobileProfileModal');
            const mobileBg = mobileModal ? mobileModal.querySelector('.mobile-profile-bg') : null;

            if (mobileBtn && mobileModal) {
                mobileBtn.addEventListener('click', function() {
                    mobileModal.style.display = 'flex';
                });

                const closeModal = () => {
                    mobileModal.style.display = 'none';
                };

                if (mobileBg) mobileBg.addEventListener('click', closeModal);
            }
        });

        function confirmMobileLogout() {
            Swal.fire({
                title: "Are you sure?",
                text: "You will be logged out.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DC2626",
                cancelButtonColor: "#6B7280",
                confirmButtonText: "Logout",
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form-mobile').submit();
                }
            });
        }

        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
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

        document.addEventListener('DOMContentLoaded', function() {
            var bellBtn = document.getElementById('navBellBtn');
            var modal = document.getElementById('notificationModalDrop');
            var closeBtn = document.getElementById('closenotificationModalDrop');
            var bg = modal ? modal.querySelector('.modal-dropdown-bg') : null;
            var body = modal ? modal.querySelector('.modal-dropdown-body') : null;

            // Sidebar Toggle Function
            window.toggleSidebar = function() {
                const sidebar = document.getElementById('sidebar');
                const mainContent = document.querySelector('.main-content');
                sidebar.classList.toggle('active');
                
                // Create overlay if it doesn't exist
                let overlay = document.getElementById('sidebar-overlay');
                if (!overlay) {
                    overlay = document.createElement('div');
                    overlay.id = 'sidebar-overlay';
                    overlay.style.cssText = `
                        position: fixed;
                        top: 0;
                        left: 0;
                        width: 100%;
                        height: 100%;
                        background: rgba(0,0,0,0.5);
                        z-index: 999;
                        display: none;
                        opacity: 0;
                        transition: opacity 0.3s ease;
                    `;
                    document.body.appendChild(overlay);
                    
                    overlay.addEventListener('click', function() {
                        sidebar.classList.remove('active');
                        overlay.style.opacity = '0';
                        setTimeout(() => {
                            overlay.style.display = 'none';
                        }, 300);
                    });
                }

                if (sidebar.classList.contains('active')) {
                    overlay.style.display = 'block';
                    setTimeout(() => {
                        overlay.style.opacity = '1';
                    }, 10);
                } else {
                    overlay.style.opacity = '0';
                    setTimeout(() => {
                        overlay.style.display = 'none';
                    }, 300);
                }
            };

            // Logout Confirmation
            window.confirmLogout = function(event, formId) {
                event.preventDefault();
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You will be logged out of the system.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, logout!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById(formId).submit();
                    }
                });
            };

            function closeModal() {
                if (modal) modal.style.display = 'none';
            }

            function loadLatestNotifications() {
                if (!body) return;

                body.innerHTML = `<p style="padding:10px;">Loading...</p>`; // loader

                fetch("{{ route('admin.notifications.latest') }}")
                    .then(res => res.json())
                    .then(data => {
                        body.innerHTML = "";

                        if (!data.length) {
                            body.innerHTML = `<p style="padding:10px; text-align:center; color:gray;">No unread notifications</p>`;
                            if (document.querySelector(".nav-bell-dot")) {
                                document.querySelector(".nav-bell-dot").style.display = "none";
                            }
                            return;
                        }

                        if (document.querySelector(".nav-bell-dot")) {
                            document.querySelector(".nav-bell-dot").style.display = "block";
                        }

                        data.forEach(n => {
                            body.innerHTML += `
                        <div class="notification-item unread" data-id="${n.notifi_id}">
                            <div>
                                <div class="notification-title">${n.type ?? 'Notification'}</div>
                                <div class="notification-desc">${n.message ?? ''}</div>
                                <div class="notification-meta">
                                    <i class="fas fa-clock"></i>
                                    ${new Date(n.created_at).toLocaleString()}
                                </div>
                            </div>
                            <div class="notification-actions">
                                <button class="icon-btn mark-btn" title="Mark as Read"
                                    onclick="markAsRead('${n.notifi_id}')">
                                    <i class="fas fa-check"></i>
                                </button>

                                <button class="icon-btn delete-btn" title="Delete"
                                   onclick="deleteNotification('${n.notifi_id}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    `;
                        });
                    })
                    .catch(err => {
                        console.error("Error fetching notifications:", err);
                        body.innerHTML = `<p style="padding:10px; color:red;">Error loading notifications</p>`;
                    });
            }

            // Mark one notification as read
            window.markAsRead = function(id) {
                fetch(`/admin/notifications/${id}/mark-as-read`, {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        }
                    })
                    .then(() => {
                        loadLatestNotifications();
                        refreshBellDot();
                    })
                    .catch(err => console.error("Mark as read error:", err));
            };

            // Delete notification
            window.deleteNotification = function(id) {
                // Close modal temporarily if needed, or just keep it open
                // document.getElementById('notificationModalDrop').style.display = "none";

                Swal.fire({
                    title: "Delete Notification?",
                    text: "This action cannot be undone.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DC2626",
                    cancelButtonColor: "#6B7280",
                    confirmButtonText: "Yes, Delete",
                    cancelButtonText: "Cancel"
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/admin/notifications/${id}`, {
                                method: "DELETE",
                                headers: {
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                }
                            })
                            .then(res => res.json())
                            .then(() => {
                                loadLatestNotifications();
                                refreshBellDot();
                                Swal.fire({
                                    icon: "success",
                                    title: "Deleted!",
                                    text: "Notification has been deleted.",
                                    timer: 1200,
                                    showConfirmButton: false
                                });
                            });
                    }
                });
            };

            // Mark All as Read
            const markAllBtn = document.getElementById("markAllBtn");
            if (markAllBtn) {
                markAllBtn.addEventListener("click", function() {
                    fetch("{{ route('admin.notifications.markAllAsRead') }}", {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        }
                    }).then(() => {
                        loadLatestNotifications();
                        refreshBellDot();
                    });
                });
            }

            // Update bell dot
            function refreshBellDot() {
                fetch("{{ route('admin.notifications.latest') }}")
                    .then(res => res.json())
                    .then(data => {
                        const dot = document.querySelector(".nav-bell-dot");
                        if (dot) {
                            dot.style.display = data.length > 0 ? "block" : "none";
                        }
                    });
            }
            
            // Auto refresh
            setInterval(refreshBellDot, 15000);

            if (bellBtn && modal && closeBtn) {
                bellBtn.addEventListener('click', function(e) {
                    e.preventDefault();

                    // Toggle dropdown
                    if (modal.style.display === 'none' || modal.style.display === '') {
                        modal.style.display = 'flex';
                        loadLatestNotifications();
                    } else {
                        closeModal();
                    }
                });

                closeBtn.addEventListener('click', closeModal);

                if (bg) {
                    bg.addEventListener('click', closeModal);
                }

                document.addEventListener('mousedown', function(e) {
                    if (
                        modal.style.display === 'flex' &&
                        !modal.querySelector('.modal-dropdown-content').contains(e.target) &&
                        e.target !== bellBtn &&
                        !bellBtn.contains(e.target)
                    ) {
                        closeModal();
                    }
                });
            }

            // User Dropdown Toggle
            const userMenuBtn = document.getElementById('userMenuBtn');
            const userDropdown = document.getElementById('userDropdown');

            if (userMenuBtn && userDropdown) {
                userMenuBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    userDropdown.classList.toggle('show');
                });

                document.addEventListener('click', function(e) {
                    if (!userMenuBtn.contains(e.target)) {
                        userDropdown.classList.remove('show');
                    }
                });
            }
            // Global Copy to Clipboard function
            window.copyToClipboard = function(text, event) {
                if (event) event.preventDefault();
                
                navigator.clipboard.writeText(text).then(() => {
                    // Visual feedback on button
                    if (event && event.currentTarget) {
                        const btn = event.currentTarget;
                        const originalHtml = btn.innerHTML;
                        const originalWidth = btn.offsetWidth;
                        
                        btn.style.width = `${originalWidth}px`; // Prevent layout shift
                        btn.innerHTML = '<i class="fas fa-check"></i>';
                        
                        setTimeout(() => {
                            btn.innerHTML = originalHtml;
                            btn.style.width = '';
                        }, 2000);
                    }

                    // Toast feedback
                    if (typeof Swal !== 'undefined') {
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.addEventListener('mouseenter', Swal.stopTimer)
                                toast.addEventListener('mouseleave', Swal.resumeTimer)
                            }
                        });
                        Toast.fire({
                            icon: 'success',
                            title: 'Link copied to clipboard'
                        });
                    }
                }).catch(err => {
                    console.error('Failed to copy:', err);
                    // Fallback
                    const textArea = document.createElement("textarea");
                    textArea.value = text;
                    document.body.appendChild(textArea);
                    textArea.select();
                    try {
                        document.execCommand('copy');
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Link copied',
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 2000
                            });
                        }
                    } catch (err) {
                        console.error('Fallback copy failed', err);
                    }
                    document.body.removeChild(textArea);
                });
            };
        });
    </script>

    @stack('scripts')
</body>

</html>
