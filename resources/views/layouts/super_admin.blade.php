<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'EMS') }} - @hasSection('browser_title') @yield('browser_title') @else @php echo strip_tags($__env->yieldContent('title', 'Super Admin Dashboard')); @endphp @endif</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/SuperAdmin/superAdmin.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body>
    <div class="sidebar">
        <div class="sidebar-brand">
            <h2>RedCode Solutions</h2>
            <div class="subtitle">Super Admin Panel</div>
        </div>

        <div class="sidebar-menu">
            <div class="sidebar-menu-section">
                <div class="sidebar-menu-title">Dashboard</div>
                <a href="{{ route('super_admin.dashboard') }}"
                    class="sidebar-menu-item {{ request()->routeIs('super_admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Overview</span>
                </a>
            </div>

            <div class="sidebar-menu-section">
                <div class="sidebar-menu-title">Admins</div>
                <a href="{{ route('super_admin.admins') }}"
                    class="sidebar-menu-item {{ request()->routeIs('super_admin.admins') ? 'active' : '' }}">
                    <i class="fas fa-user-shield"></i>
                    <span>Admin Management</span>
                </a>
                <a href="{{ route('super_admin.super_admin_accounts') }}"
                    class="sidebar-menu-item {{ request()->routeIs('super_admin.super_admin_accounts') ? 'active' : '' }}">
                    <i class="fas fa-user-cog"></i>
                    <span> Account Management</span>
                </a>
            </div>

            <div class="sidebar-menu-section">
                <div class="sidebar-menu-title">Admin Management</div>
                <a href="{{ route('super_admin.admin_leaves.index') }}"
                    class="sidebar-menu-item {{ request()->routeIs('super_admin.admin_leaves.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-check"></i>
                    <span>Admin Leave Management</span>
                </a>
            </div>

            <div class="sidebar-menu-section">
                <div class="sidebar-menu-title">Events</div>
                <a href="{{ route('super_admin.events.index') }}"
                    class="sidebar-menu-item {{ request()->routeIs('super_admin.events.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Events Management</span>
                </a>
            </div>

            <div class="sidebar-menu-section">
                <div class="sidebar-menu-title">Notifications</div>
                <a href="{{ route('super_admin.notifications') }}"
                    class="sidebar-menu-item {{ request()->routeIs('super_admin.notifications') ? 'active' : '' }}">
                    <i class="fas fa-bell"></i>
                    <span>All Notifications</span>
                    <span class="badge">{{ $notificationStats['unread'] ?? 0 }}</span>
                </a>
                <a href="{{ route('super_admin.employee_ratings') }}"
                    class="sidebar-menu-item {{ request()->routeIs('super_admin.employee_ratings') ? 'active' : '' }}">
                    <i class="fas fa-star"></i>
                    <span>Employee Ratings</span>
                </a>
                <a href="{{ route('super.attendance.index') }}"
                    class="sidebar-menu-item {{ request()->routeIs('super_admin.attendance.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-check"></i>
                    <span>Attendance Count</span>
                </a>
            </div>
        </div>
    </div>


    <div class="main-content">

        <div class="top-nav">
            <div class="nav-title">
                <button class="sidebar-toggle" onclick="toggleSidebar()" aria-label="Toggle sidebar">
                    <i class="fas fa-bars"></i>
                </button>
                <i class="fas fa-crown"></i>
                @yield('title', 'Super Admin Dashboard')
            </div>

            <div class="nav-actions">


                <a href="{{ route('super_admin.notifications') }}" class="nav-bell" id="navBellBtn">
                    <div class="nav-bell-icon">
                        <i class="fa-regular fa-bell"></i>
                        <span class="nav-bell-dot" style="display: none;"></span>
                    </div>
                </a>

                <div class="user-menu" id="userMenuBtn">
                    <div class="user-avatar">
                        @php
                            $loggedInSuperAdmin = \App\Models\SuperAdmin::where('super_admin_email', auth()->user()->email)->first();
                        @endphp
                        @if($loggedInSuperAdmin && $loggedInSuperAdmin->profile_image)
                             <img src="{{ asset('storage/' . $loggedInSuperAdmin->profile_image) }}" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                        @else
                            {{ strtoupper(substr(auth()->user()?->name ?? 'SA', 0, 1)) }}
                        @endif
                    </div>
                    <div class="user-info">
                        <h4>{{ explode(' ', auth()->user()?->name ?? 'Super Admin')[0] }}</h4>
                    </div>
                    <i class="fas fa-chevron-down"></i>

                    <div class="user-dropdown" id="userDropdown">
                        <a href="{{ route('super_admin.super_admin_accounts') }}" class="dropdown-item">
                            <i class="fas fa-user-shield"></i> Profile
                        </a>
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('logout') }}" id="logout-form">
                            @csrf
                            <button type="button" onclick="confirmLogout(event)" class="dropdown-item text-danger">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>


    <div class="content-area">
            @yield('content')
        </div>
    </div>

    <!-- Notification Modal -->
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

    <script>
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('active');
        }

        document.addEventListener('click', function(e) {
            const sidebar = document.querySelector('.sidebar');
            const toggleButton = document.querySelector('.sidebar-toggle');

            if (window.innerWidth <= 1024 && !sidebar.contains(e.target) && !toggleButton?.contains(e.target)) {
                sidebar.classList.remove('active');
            }
        });

        // User Dropdown
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

        // Notification System
        const bellBtn = document.getElementById('navBellBtn');
        const modal = document.getElementById('notificationModalDrop');
        const closeBtn = document.getElementById('closenotificationModalDrop');
        const body = document.getElementById('latestNotifications');

        // Initial Load
        document.addEventListener('DOMContentLoaded', () => {
             refreshBellDot();
             // loadLatestNotifications(); // Optional: preload
        });


        // Toggle Modal
        if (bellBtn && modal) {
            bellBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if (modal.style.display === 'none' || modal.style.display === '') {
                    modal.style.display = 'flex';
                    loadLatestNotifications();
                } else {
                    modal.style.display = 'none';
                }
            });
        }

        if (closeBtn) {
            closeBtn.addEventListener('click', function() {
                modal.style.display = 'none';
            });
        }

        // Close when clicking outside
        window.addEventListener('click', function(e) {
            const content = document.querySelector('.modal-dropdown-content');
            if (modal && modal.style.display !== 'none' && content && !content.contains(e.target) && !bellBtn.contains(e.target)) {
                modal.style.display = 'none';
            }
        });

        // Load Notifications
        function loadLatestNotifications() {
             if (!body) return;
             body.innerHTML = '<p style="padding:10px; text-align:center;">Loading...</p>';

             fetch("{{ route('super_admin.notifications.latest') }}")
                 .then(res => res.json())
                 .then(data => {
                     body.innerHTML = "";
                     refreshBellDot(); // sync dot

                     if (!data || data.length === 0) {
                         body.innerHTML = '<p style="padding:10px; text-align:center; color:gray;">No unread notifications</p>';
                         return;
                     }

                     data.forEach(n => {
                         let icon = 'fas fa-bell'; // default
                         // Simple icon toggle based on type if wanted, or server provided
                         if(n.type === 'leave') icon = 'fas fa-calendar';
                         if(n.type === 'system') icon = 'fas fa-cogs';

                         const item = `
                            <div class="notification-item unread">
                                <div>
                                    <div class="notification-title">
                                       <i class="${icon}"></i> ${n.type ?? 'Notification'}
                                    </div>
                                    <div class="notification-desc">${n.message ?? ''}</div>
                                    <div class="notification-meta">
                                        ${new Date(n.created_at).toLocaleString()}
                                    </div>
                                </div>
                                <div class="notification-actions">
                                    <button class="icon-btn mark-btn" title="Mark as Read" onclick="markAsRead('${n.notifi_id}')">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button class="icon-btn delete-btn" title="Delete" onclick="deleteNotification('${n.notifi_id}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                         `;
                         body.insertAdjacentHTML('beforeend', item);
                     });
                 })
                 .catch(err => {
                     console.error(err);
                     body.innerHTML = '<p style="padding:10px; color:red;">Error loading notifications</p>';
                 });
        }

        // Mark as Read
        window.markAsRead = function(id) {
            fetch(`/notifications/${id}/read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    loadLatestNotifications();
                }
            })
            .catch(err => console.error(err));
        };

        // Delete
        window.deleteNotification = function(id) {
             Swal.fire({
                title: 'Are you sure?',
                text: "Delete this notification?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
             }).then((result) => {
                if (result.isConfirmed) {
                     fetch(`/notifications/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if(data.success) {
                            loadLatestNotifications();
                            const Toast = Swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000
                            });
                            Toast.fire({
                                icon: 'success',
                                title: 'Notification deleted'
                            });
                        }
                    })
                    .catch(err => console.error(err));
                }
             });
        };

        // Global Helper for Form Deletes
        window.confirmDeleteForm = function(form) {
             Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
             }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
             });
             return false;
        };
        
        // Session Flash Messages
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: "{{ session('success') }}",
                timer: 3000,
                showConfirmButton: false
            });
        @endif
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: "{{ session('error') }}",
            });
        @endif

        // Mark All Read
        const markAllBtn = document.getElementById('markAllBtn');
        if(markAllBtn) {
            markAllBtn.addEventListener('click', function() {
                fetch("{{ route('notifications.readAll') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(res => res.json())
                .then(data => {
                     loadLatestNotifications();
                });
            });
        }

        // bell dot check
        function refreshBellDot() {
             const dot = document.querySelector('.nav-bell-dot');
             if(!dot) return;

             fetch("{{ route('super_admin.notifications.latest') }}")
                 .then(res => res.json())
                 .then(data => {
                     if(data && data.length > 0) {
                         dot.style.display = 'block';
                     } else {
                         dot.style.display = 'none';
                     }
                 })
                 .catch(e => console.error(e));
        }

        // Logout Confirmation
        window.confirmLogout = function(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Ready to Leave?',
                text: "You will be logged out of your session.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, Logout',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            });
        };

        // Auto refresh
        setInterval(refreshBellDot, 30000);
    </script>
</body>

</html>
