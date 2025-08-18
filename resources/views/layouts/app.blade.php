<!--<!DOCTYPE html>-->
<!--<html>-->
<!--<head>-->
<!--    <title>Employee Management System</title>-->
<!---->
<!--    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">-->
<!--</head>-->
<!--<body>-->
<!--<div class="container mt-4">-->
<!--    @yield('content')-->
<!--</div>-->
<!--</body>-->
<!--</html>-->
<!DOCTYPE html>
<html>
<head>
    <title>Employee Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>
<div class="container mt-4">
    <!-- Navbar with Notification Bell -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
        <a class="navbar-brand" href="#">EMS</a>
        <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown" style="list-style: none; display: inline-block;">
                <a class="nav-link" href="#" id="notificationBell" role="button">
                    <i class="bi bi-bell"></i>
                    <span id="notificationCount" class="badge bg-danger"></span>
                </a>
                <div id="notificationDropdown" class="dropdown-menu dropdown-menu-end" style="width: 300px; max-height: 400px; overflow-y: auto;">
                    <div id="notificationList" class="p-2">Loading...</div>
                </div>
            </li>
        </ul>
    </nav>
    @yield('content')
</div>

<!-- Notification JS -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const bell = document.getElementById('notificationBell');
        const dropdown = document.getElementById('notificationDropdown');
        const list = document.getElementById('notificationList');
        const count = document.getElementById('notificationCount');

        bell.addEventListener('click', function (e) {
            e.preventDefault();
            fetch('/api/notifications/me')
                .then(res => res.json())
                .then(data => {
                    list.innerHTML = '';
                    if (data.notifications.length === 0) {
                        list.innerHTML = '<div class="text-center text-muted">No notifications</div>';
                    } else {
                        data.notifications.forEach(n => {
                            list.innerHTML += `<div class="border-bottom py-2">
                            <div><strong>${n.type}</strong> - ${n.delivery_channel}</div>
                            <div>${n.message}</div>
                            <div class="small text-muted">${n.timestamp}</div>
                        </div>`;
                        });
                    }
                    count.textContent = data.notifications.length > 0 ? data.notifications.length : '';
                    dropdown.classList.toggle('show');
                });
        });

        // Hide dropdown when clicking outside
        document.addEventListener('click', function (e) {
            if (!bell.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.remove('show');
            }
        });
    });
</script>
</body>
</html>
