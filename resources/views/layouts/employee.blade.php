<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'EMS') }} - @yield('title', 'Employee Dashboard')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @if (!app()->environment('local'))
        <link rel="stylesheet" href="{{ asset('build/assets/app-CObZ5BOq.css') }}">
        <script type="module" src="{{ asset('build/assets/app-l0sNRNKZ.js') }}"></script>
    @endif

    <link rel="stylesheet" href="{{ asset('build/assets/app-CObZ5BOq.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Employee/employee_side_bar.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="sidebar">
        <div class="sidebar-brand">
            <h2>RedCode Solutions</h2>
            <div class="subtitle">Employee Portal</div>
        </div>

        <div class="sidebar-menu">
            <div class="sidebar-menu-section">
                <div class="sidebar-menu-title">Dashboard</div>
                <a href="{{ route('employee.dashboard') }}"
                    class="sidebar-menu-item {{ request()->routeIs('employee.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-pie"></i>
                    <span>Overview</span>
                </a>
            </div>

            <div class="sidebar-menu-section">
                <div class="sidebar-menu-title">Personal</div>
                <a href="{{ url('/employees/profile') }}" class="sidebar-menu-item">
                    <i class="fas fa-user"></i>
                    <span>My Profile</span>
                </a>
                <a href="{{ route('employee.documents') }}"
                    class="sidebar-menu-item {{ request()->routeIs('employee.documents') ? 'active' : '' }}">
                    <i class="fas fa-file-alt"></i>
                    <span>My Documents</span>
                </a>
            </div>

            <div class="sidebar-menu-section">
                <div class="sidebar-menu-title">Work</div>
                <a href="{{ route('employee.projects') }}"
                    class="sidebar-menu-item {{ request()->routeIs('employee.projects') ? 'active' : '' }}">
                    <i class="fas fa-project-diagram"></i>
                    <span>My Projects</span>
                </a>
                <a href="{{ route('employee.tasks') }}"
                    class="sidebar-menu-item {{ request()->routeIs('employee.tasks') ? 'active' : '' }}">
                    <i class="fas fa-tasks"></i>
                    <span>My Tasks</span>
                </a>
            </div>

            <div class="sidebar-menu-section">
                <div class="sidebar-menu-title">Attendance</div>
                <a href="{{ route('employee.attendance') }}"
                    class="sidebar-menu-item {{ request()->routeIs('employee.attendance') ? 'active' : '' }}">
                    <i class="fas fa-calendar-check"></i>
                    <span>Attendance</span>
                </a>
                <a href="{{ url('/employees/leaves') }}"
                    class="sidebar-menu-item {{ request()->routeIs('employee.leaves.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-times"></i>
                    <span>Leave Management</span>
                </a>
            </div>

            <div class="sidebar-menu-section">
                <div class="sidebar-menu-title">Communication</div>
                <a href="{{ route('employee.announcements.index') }}"
                    class="sidebar-menu-item {{ request()->routeIs('employee.announcements') ? 'active' : '' }}">
                    <i class="fas fa-bullhorn"></i>
                    <span>Announcements</span>
                </a>

                <a href="{{ route('employee.notifications.index') }}"
                    class="sidebar-menu-item {{ request()->routeIs('employee.notifications*') ? 'active' : '' }}">
                    <i class="fas fa-bell"></i>
                    <span>Notifications</span>
                    <span class="badge"
                        style="margin-left:auto;background: rgba(255, 255, 255, 0.2);color:white;border-radius:10px;padding:2px 8px;">{{ $notificationStats['unread'] ?? 0 }}</span>
                </a>
            </div>

            <div class="sidebar-menu-section">
                <div class="sidebar-menu-title">Performance</div>
                <a href="{{ route('employee.ratings.index') }}"
                    class="sidebar-menu-item {{ request()->routeIs('employee.ratings.*') ? 'active' : '' }}">
                    <i class="fas fa-star"></i>
                    <span>All Employee Ratings</span>
                </a>
            </div>
        </div>
    </div>

    <div class="main-content">
        <div class="top-nav">
            <button class="sidebar-toggle" onclick="toggleSidebar()" style="display:none;">
                <i class="fas fa-bars"></i>
            </button>
            <script>
                function handleSidebarToggleVisibility() {
                    const toggleBtn = document.querySelector('.sidebar-toggle');
                    if (window.innerWidth <= 1024) {
                        toggleBtn.style.display = 'inline-flex';
                    } else {
                        toggleBtn.style.display = 'none';
                    }
                }
                window.addEventListener('resize', handleSidebarToggleVisibility);
                window.addEventListener('DOMContentLoaded', handleSidebarToggleVisibility);
            </script>
            <div class="nav-title">
                <i class="fas fa-star"></i>
                @yield('title', 'Employee Dashboard')
            </div>
            <div class="nav-actions">
                <a href="{{ route('employee.notifications.latest') }}" class="nav-bell" title="Notifications"
                    id="navBellBtn" type="button">
                    <span class="nav-bell-icon">
                        <i class="fas fa-bell"></i>
                        <span class="nav-bell-dot"></span>
                    </span>
                </a>

                <a href="{{ route('employee.profile') }}" class="user-menu"
                    style="text-decoration: none; color: inherit;">
                    <div class="user-avatar">
                        {{ strtoupper(substr(auth()->user()?->name ?? 'E', 0, 1)) }}
                    </div>
                    <div class="user-info">
                        <h4>{{ auth()->user()?->name ?? 'Employee' }}</h4>
                        <p>{{ auth()->user()?->email ?? 'employee@company.com' }}</p>
                    </div>
                </a>


                <form method="POST" action="{{ route('logout') }}" style="display: inline;" id="logoutForm">
                    @csrf
                    <button type="button" class="btn btn-primary" onclick="confirmLogout()">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout
                    </button>
                </form>
            </div>

        </div>

        <div class="content-area">
            @yield('content')
        </div>
        <div id="notificationModalDrop" class="modal-dropdown" style="display:none;">
            <div class="modal-dropdown-bg"></div>
            <div class="modal-dropdown-content">
                <div class="modal-dropdown-header">
                    <h3><i class="fas fa-bell"></i> Notifications</h3>
                    <button class="modal-close" id="closenotificationModalDrop">&times;</button>
                    <button id="markAllBtn" class="btn btn-warning btn-sm" style="float:right;">
                        Mark All as Read
                    </button>
                </div>
                <div class="modal-dropdown-body" id="latestNotifications">
                    <p style="text-align:center; color: gray;">Loading...</p>
                </div>
            </div>
        </div>

    </div>

    <script>
        /* ============================
                                                           SIDEBAR TOGGLE
                                                        ============================ */
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('active');
        }

        document.addEventListener('click', function(e) {
            const sidebar = document.querySelector('.sidebar');
            const toggleButton = document.querySelector('.sidebar-toggle');

            if (window.innerWidth <= 1024 &&
                !sidebar.contains(e.target) &&
                !toggleButton?.contains(e.target)) {
                sidebar.classList.remove('active');
            }
        });


        // Load unread notifications
        function loadLatestNotifications() {
            let modal = document.getElementById('notificationModalDrop');
            let body = modal.querySelector('.modal-dropdown-body');

            body.innerHTML = `<p style="padding:10px;">Loading...</p>`;

            fetch("{{ route('employee.notifications.latest') }}")
                .then(res => res.json())
                .then(data => {
                    body.innerHTML = "";

                    if (!data.length) {
                        body.innerHTML =
                            `<p style="padding:10px; text-align:center; color:gray;">No unread notifications</p>`;
                        document.querySelector(".nav-bell-dot").style.display = "none";
                        return;
                    }

                    document.querySelector(".nav-bell-dot").style.display = "block";

                    data.forEach(n => {
                        body.innerHTML += `
                        <div class="notification-item unread" data-id="${n.notifi_id}">
                            <div>
                                <div class="notification-title">${n.type}</div>
                                <div class="notification-desc">${n.message}</div>
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
                });
        }


        // Mark one notification as read
        function markAsRead(id) {
            fetch(`/employee/notifications/${id}/mark-as-read`, {
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
        }


        // Delete notification
        function deleteNotification(id) {

            document.getElementById('notificationModalDrop').style.display = "none";

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

                    fetch(`/employee/notifications/${id}`, {
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
        }




        // Update bell dot
        function refreshBellDot() {
            fetch("{{ route('employee.notifications.latest') }}")
                .then(res => res.json())
                .then(data => {
                    const dot = document.querySelector(".nav-bell-dot");
                    dot.style.display = data.length > 0 ? "block" : "none";
                });
        }

        setInterval(refreshBellDot, 8000); // auto refresh



        /* ============================
           DOM READY EVENT
        ============================ */
        document.addEventListener('DOMContentLoaded', function() {

            // Notification icon click
            const bellBtn = document.getElementById('navBellBtn');
            const modal = document.getElementById('notificationModalDrop');
            const closeBtn = document.getElementById('closenotificationModalDrop');

            bellBtn.addEventListener('click', function(e) {
                e.preventDefault();

                if (modal.style.display === "flex") {
                    modal.style.display = "none";
                } else {
                    modal.style.display = "flex";
                    loadLatestNotifications();
                }
            });

            closeBtn.addEventListener("click", () => modal.style.display = "none");

            // Outside click close
            modal.addEventListener("click", function(e) {
                if (e.target.classList.contains("modal-dropdown-bg")) {
                    modal.style.display = "none";
                }
            });

            refreshBellDot();
        });



        /* ============================
           MARK ALL READ
        ============================ */
        document.getElementById("markAllBtn")?.addEventListener("click", function() {
            fetch("{{ route('employee.notifications.markAllAsRead') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                }
            }).then(() => {
                loadLatestNotifications();
                refreshBellDot();
            });
        });



        /* ============================
           LOGOUT CONFIRMATION
        ============================ */
        function confirmLogout() {
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
                    document.getElementById('logoutForm').submit();
                }
            });
        }
    </script>

    @stack('scripts')
</body>

</html>
