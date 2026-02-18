@extends('layouts.admin')

@section('title', 'Management Overview')

@section('content')
<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-tasks"></i> Management Overview</h2>
        <button class="btn btn-secondary">
            <i class="fas fa-chart-line"></i>
            View Analytics
        </button>
    </div>
    <div class="card-body">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
            <!-- Projects Management -->
            <div class="card">
                <div class="card-header">
                    <h3 style="font-size: 1.125rem; font-weight: 600; color: var(--gray-800);">
                        <i class="fas fa-project-diagram" style="color: var(--primary);"></i>
                        Projects
                    </h3>
                    <a href="{{ route('projects.index') }}" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.75rem;">
                        <i class="fas fa-arrow-right"></i>
                        Manage
                    </a>
                </div>
                <div class="card-body">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                        <div class="text-center">
                            <div style="font-size: 1.5rem; font-weight: 700; color: var(--primary);">24</div>
                            <div style="font-size: 0.75rem; color: var(--gray-600);">Active Projects</div>
                        </div>
                        <div class="text-center">
                            <div style="font-size: 1.5rem; font-weight: 700; color: var(--success);">12</div>
                            <div style="font-size: 0.75rem; color: var(--gray-600);">Completed</div>
                        </div>
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <div style="font-size: 0.875rem; color: var(--gray-600); margin-bottom: 0.5rem;">Recent Activity</div>
                        <div style="font-size: 0.875rem; line-height: 1.5;">Project Alpha completed successfully. 3 new projects started this week.</div>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('projects.create') }}" class="btn btn-success" style="padding: 0.5rem 1rem; font-size: 0.75rem; flex: 1;">
                            <i class="fas fa-plus"></i>
                            New Project
                        </a>
                    </div>
                </div>
            </div>

            <!-- Teams Management -->
            <div class="card">
                <div class="card-header">
                    <h3 style="font-size: 1.125rem; font-weight: 600; color: var(--gray-800);">
                        <i class="fas fa-users-cog" style="color: var(--secondary);"></i>
                        Teams
                    </h3>
                    <a href="{{ route('teams.index') }}" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.75rem;">
                        <i class="fas fa-arrow-right"></i>
                        Manage
                    </a>
                </div>
                <div class="card-body">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                        <div class="text-center">
                            <div style="font-size: 1.5rem; font-weight: 700; color: var(--secondary);">8</div>
                            <div style="font-size: 0.75rem; color: var(--gray-600);">Active Teams</div>
                        </div>
                        <div class="text-center">
                            <div style="font-size: 1.5rem; font-weight: 700; color: var(--info);">67</div>
                            <div style="font-size: 0.75rem; color: var(--gray-600);">Team Members</div>
                        </div>
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <div style="font-size: 0.875rem; color: var(--gray-600); margin-bottom: 0.5rem;">Recent Activity</div>
                        <div style="font-size: 0.875rem; line-height: 1.5;">Development team expanded. New cross-functional team formed.</div>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('teams.create') }}" class="btn btn-success" style="padding: 0.5rem 1rem; font-size: 0.75rem; flex: 1;">
                            <i class="fas fa-plus"></i>
                            New Team
                        </a>
                    </div>
                </div>
            </div>

            <!-- Leave Management -->
            <div class="card">
                <div class="card-header">
                    <h3 style="font-size: 1.125rem; font-weight: 600; color: var(--gray-800);">
                        <i class="fas fa-calendar-times" style="color: var(--warning);"></i>
                        Leave Management
                    </h3>
                    <a href="{{ route('leaves.index') }}" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.75rem;">
                        <i class="fas fa-arrow-right"></i>
                        Manage
                    </a>
                </div>
                <div class="card-body">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                        <div class="text-center">
                            <div style="font-size: 1.5rem; font-weight: 700; color: var(--warning);">7</div>
                            <div style="font-size: 0.75rem; color: var(--gray-600);">Pending Requests</div>
                        </div>
                        <div class="text-center">
                            <div style="font-size: 1.5rem; font-weight: 700; color: var(--danger);">3</div>
                            <div style="font-size: 0.75rem; color: var(--gray-600);">Urgent Reviews</div>
                        </div>
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <div style="font-size: 0.875rem; color: var(--gray-600); margin-bottom: 0.5rem;">Recent Activity</div>
                        <div style="font-size: 0.875rem; line-height: 1.5;">5 leave requests approved today. 2 requests need immediate attention.</div>
                    </div>
                    <div class="flex gap-2">
                        <button class="btn btn-warning" style="padding: 0.5rem 1rem; font-size: 0.75rem; flex: 1;">
                            <i class="fas fa-clock"></i>
                            Review Pending
                        </button>
                    </div>
                </div>
            </div>

            <!-- Performance Management -->
            <div class="card">
                <div class="card-header">
                    <h3 style="font-size: 1.125rem; font-weight: 600; color: var(--gray-800);">
                        <i class="fas fa-chart-line" style="color: var(--info);"></i>
                        Performance
                    </h3>
                    <button class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.75rem;">
                        <i class="fas fa-arrow-right"></i>
                        Manage
                    </button>
                </div>
                <div class="card-body">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                        <div class="text-center">
                            <div style="font-size: 1.5rem; font-weight: 700; color: var(--info);">85%</div>
                            <div style="font-size: 0.75rem; color: var(--gray-600);">Avg Performance</div>
                        </div>
                        <div class="text-center">
                            <div style="font-size: 1.5rem; font-weight: 700; color: var(--success);">12</div>
                            <div style="font-size: 0.75rem; color: var(--gray-600);">Top Performers</div>
                        </div>
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <div style="font-size: 0.875rem; color: var(--gray-600); margin-bottom: 0.5rem;">Recent Activity</div>
                        <div style="font-size: 0.875rem; line-height: 1.5;">Q4 reviews completed. Performance improvement plans initiated.</div>
                    </div>
                    <div class="flex gap-2">
                        <button class="btn btn-info" style="padding: 0.5rem 1rem; font-size: 0.75rem; flex: 1;">
                            <i class="fas fa-chart-bar"></i>
                            View Reports
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="card" style="margin-top: 2rem;">
    <div class="card-header">
        <h2><i class="fas fa-bolt"></i> Quick Actions</h2>
    </div>
    <div class="card-body">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
            <a href="{{ route('employees.create') }}" class="btn btn-primary" style="padding: 1rem; text-align: center; flex-direction: column; height: auto;">
                <i class="fas fa-user-plus" style="font-size: 1.5rem; margin-bottom: 0.5rem;"></i>
                <span>Add Employee</span>
            </a>
            <a href="{{ route('departments.create') }}" class="btn btn-secondary" style="padding: 1rem; text-align: center; flex-direction: column; height: auto;">
                <i class="fas fa-building" style="font-size: 1.5rem; margin-bottom: 0.5rem;"></i>
                <span>Add Department</span>
            </a>
            <a href="{{ route('projects.create') }}" class="btn btn-success" style="padding: 1rem; text-align: center; flex-direction: column; height: auto;">
                <i class="fas fa-project-diagram" style="font-size: 1.5rem; margin-bottom: 0.5rem;"></i>
                <span>New Project</span>
            </a>
            <a href="{{ route('teams.create') }}" class="btn btn-info" style="padding: 1rem; text-align: center; flex-direction: column; height: auto;">
                <i class="fas fa-users-cog" style="font-size: 1.5rem; margin-bottom: 0.5rem;"></i>
                <span>Create Team</span>
            </a>
            <a href="{{ route('reports.create') }}" class="btn btn-warning" style="padding: 1rem; text-align: center; flex-direction: column; height: auto;">
                <i class="fas fa-chart-bar" style="font-size: 1.5rem; margin-bottom: 0.5rem;"></i>
                <span>Generate Report</span>
            </a>
            <a href="{{ route('announcements.create') }}" class="btn btn-danger" style="padding: 1rem; text-align: center; flex-direction: column; height: auto;">
                <i class="fas fa-bullhorn" style="font-size: 1.5rem; margin-bottom: 0.5rem;"></i>
                <span>New Announcement</span>
            </a>
        </div>
    </div>
</div>
@endsection

<style>
/* Modern Management Overview Styles */
.card {
    border-radius: 1rem;
    box-shadow: 0 2px 16px 0 rgba(0,0,0,0.07);
    border: none;
    background: #fff;
}
.card-header {
    border-bottom: 1px solid #f1f1f1;
    background: linear-gradient(90deg, #f8fafc 60%, #e0e7ef 100%);
    border-radius: 1rem 1rem 0 0;
    padding: 1.5rem 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.card-body {
    padding: 2rem;
}
.btn {
    border-radius: 0.75rem;
    font-weight: 500;
    transition: background 0.2s, color 0.2s, box-shadow 0.2s;
    box-shadow: 0 1px 4px 0 rgba(0,0,0,0.04);
}
.btn-primary {
    background: linear-gradient(90deg, #2563eb 60%, #1d4ed8 100%);
    color: #fff;
    border: none;
}
.btn-primary:hover {
    background: linear-gradient(90deg, #1d4ed8 60%, #2563eb 100%);
}
.btn-secondary {
    background: #f3f4f6;
    color: #374151;
    border: none;
}
.btn-secondary:hover {
    background: #e5e7eb;
}
.btn-warning {
    background: #fbbf24;
    color: #fff;
    border: none;
}
.btn-warning:hover {
    background: #f59e42;
}
.btn-danger {
    background: #ef4444;
    color: #fff;
    border: none;
}
.btn-danger:hover {
    background: #dc2626;
}
.btn-success {
    background: #10b981;
    color: #fff;
    border: none;
}
.btn-success:hover {
    background: #059669;
}
.btn-info {
    background: #0ea5e9;
    color: #fff;
    border: none;
}
.btn-info:hover {
    background: #0369a1;
}
.badge {
    font-weight: 600;
    letter-spacing: 0.02em;
    display: inline-block;
}
.flex {
    display: flex;
}
.gap-1 { gap: 0.25rem; }
.gap-2 { gap: 0.75rem; }
.gap-3 { gap: 1.25rem; }
.justify-between { justify-content: space-between; }
.items-center { align-items: center; }
.text-center { text-align: center; }
.mt-4 { margin-top: 1.5rem; }
.mb-4 { margin-bottom: 1.5rem; }
.card-header h3, .card-header h2 {
    margin: 0;
    font-weight: 700;
    color: #1e293b;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.card-body > div[style*="display: grid"] > .card {
    border: 1px solid #f3f4f6;
    box-shadow: 0 1px 8px 0 rgba(0,0,0,0.03);
    transition: box-shadow 0.2s;
}
.card-body > div[style*="display: grid"] > .card:hover {
    box-shadow: 0 4px 24px 0 rgba(37,99,235,0.08);
}
.card-body h3, .card-body h2 {
    margin: 0;
    font-weight: 700;
    color: #1e293b;
}
.card-body p {
    color: #64748b;
    margin-bottom: 1rem;
    line-height: 1.7;
}
a.btn, button.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 1rem;
}
a.btn[style*="flex-direction: column"], button.btn[style*="flex-direction: column"] {
    flex-direction: column !important;
    font-size: 1rem;
}
@media (max-width: 900px) {
    .card-body, .card-header { padding: 1rem; }
    .card-header h3, .card-header h2 { font-size: 1rem; }
}
::-webkit-scrollbar {
    height: 8px;
    background: #f3f4f6;
    border-radius: 4px;
}
::-webkit-scrollbar-thumb {
    background: #e5e7eb;
    border-radius: 4px;
}
                            <tr>
                                <td>
                                    <div style="font-weight: 600;">Monthly Attendance Report</div>
                                    <div style="font-size: 0.75rem; color: var(--gray-500);">October 2024</div>
                                </td>
                                <td>
                                    <span class="badge" style="background: rgba(59, 130, 246, 0.1); color: var(--primary); padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem;">Employee</span>
                                </td>
                                <td>Admin User</td>
                                <td>Nov 1, 2024</td>
                                <td>
                                    <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: var(--success); padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem;">Completed</span>
                                </td>
                                <td>
                                    <div class="flex gap-1">
                                        <button class="btn btn-secondary" style="padding: 0.5rem;">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-primary" style="padding: 0.5rem;">
                                            <i class="fas fa-download"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div style="font-weight: 600;">Department Performance Analysis</div>
                                    <div style="font-size: 0.75rem; color: var(--gray-500);">Q3 2024</div>
                                </td>
                                <td>
                                    <span class="badge" style="background: rgba(99, 102, 241, 0.1); color: var(--secondary); padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem;">Department</span>
                                </td>
                                <td>HR Manager</td>
                                <td>Oct 28, 2024</td>
                                <td>
                                    <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: var(--success); padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem;">Completed</span>
                                </td>
                                <td>
                                    <div class="flex gap-1">
                                        <button class="btn btn-secondary" style="padding: 0.5rem;">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-primary" style="padding: 0.5rem;">
                                            <i class="fas fa-download"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div style="font-weight: 600;">Project Timeline Report</div>
                                    <div style="font-size: 0.75rem; color: var(--gray-500);">All Active Projects</div>
                                </td>
                                <td>
                                    <span class="badge" style="background: rgba(245, 158, 11, 0.1); color: var(--warning); padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem;">Project</span>
                                </td>
                                <td>Project Manager</td>
                                <td>Oct 25, 2024</td>
                                <td>
                                    <span class="badge" style="background: rgba(245, 158, 11, 0.1); color: var(--warning); padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem;">Processing</span>
                                </td>
                                <td>
                                    <div class="flex gap-1">
                                        <button class="btn btn-secondary" style="padding: 0.5rem;">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-secondary" style="padding: 0.5rem;" disabled>
                                            <i class="fas fa-download"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

```blade file="resources/views/admin/system/index.blade.php"
@extends('layouts.admin')

@section('title', 'System Settings')

@section('content')
<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-cog"></i> System Settings</h2>
        <button class="btn btn-secondary">
            <i class="fas fa-save"></i>
            Save All Changes
        </button>
    </div>
    <div class="card-body">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 1.5rem;">
            <!-- General Settings -->
            <div class="card">
                <div class="card-header">
                    <h3 style="font-size: 1.125rem; font-weight: 600; color: var(--gray-800);">
                        <i class="fas fa-sliders-h" style="color: var(--primary);"></i>
                        General Settings
                    </h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label">Company Name</label>
                        <input type="text" class="form-input" value="Your Company Name" placeholder="Enter company name">
                    </div>
                    <div class="form-group">
                        <label class="form-label">System Timezone</label>
                        <select class="form-select">
                            <option>UTC</option>
                            <option selected>America/New_York</option>
                            <option>Europe/London</option>
                            <option>Asia/Tokyo</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Date Format</label>
                        <select class="form-select">
                            <option>MM/DD/YYYY</option>
                            <option selected>DD/MM/YYYY</option>
                            <option>YYYY-MM-DD</option>
                        </select>
                    </div>
                    <div class="form-group mb-0">
                        <label class="form-label">Working Hours</label>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem;">
                            <input type="time" class="form-input" value="09:00">
                            <input type="time" class="form-input" value="17:00">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notification Settings -->
            <div class="card">
                <div class="card-header">
                    <h3 style="font-size: 1.125rem; font-weight: 600; color: var(--gray-800);">
                        <i class="fas fa-bell" style="color: var(--warning);"></i>
                        Notifications
                    </h3>
                </div>
                <div class="card-body">
                    <div style="margin-bottom: 1rem;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                            <label style="font-weight: 500;">Email Notifications</label>
                            <input type="checkbox" checked style="transform: scale(1.2);">
                        </div>
                        <div style="font-size: 0.875rem; color: var(--gray-600);">Send email notifications for important events</div>
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                            <label style="font-weight: 500;">SMS Notifications</label>
                            <input type="checkbox" style="transform: scale(1.2);">
                        </div>
                        <div style="font-size: 0.875rem; color: var(--gray-600);">Send SMS for urgent notifications</div>
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                            <label style="font-weight: 500;">Push Notifications</label>
                            <input type="checkbox" checked style="transform: scale(1.2);">
                        </div>
                        <div style="font-size: 0.875rem; color: var(--gray-600);">Browser push notifications</div>
                    </div>
                    <div class="form-group mb-0">
                        <label class="form-label">Notification Email</label>
                        <input type="email" class="form-input" value="admin@company.com" placeholder="Enter notification email">
                    </div>
                </div>
            </div>

            <!-- Security Settings -->
            <div class="card">
                <div class="card-header">
                    <h3 style="font-size: 1.125rem; font-weight: 600; color: var(--gray-800);">
                        <i class="fas fa-shield-alt" style="color: var(--danger);"></i>
                        Security
                    </h3>
                </div>
                <div class="card-body">
                    <div style="margin-bottom: 1rem;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                            <label style="font-weight: 500;">Two-Factor Authentication</label>
                            <input type="checkbox" style="transform: scale(1.2);">
                        </div>
                        <div style="font-size: 0.875rem; color: var(--gray-600);">Require 2FA for admin accounts</div>
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                            <label style="font-weight: 500;">Session Timeout</label>
                            <select style="padding: 0.25rem 0.5rem; border: 1px solid var(--gray-300); border-radius: 0.25rem;">
                                <option>30 minutes</option>
                                <option selected>1 hour</option>
                                <option>2 hours</option>
                                <option>4 hours</option>
                            </select>
                        </div>
                        <div style="font-size: 0.875rem; color: var(--gray-600);">Auto logout after inactivity</div>
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                            <label style="font-weight: 500;">Password Complexity</label>
                            <input type="checkbox" checked style="transform: scale(1.2);">
                        </div>
                        <div style="font-size: 0.875rem; color: var(--gray-600);">Enforce strong password requirements</div>
                    </div>
                    <div class="form-group mb-0">
                        <label class="form-label">Max Login Attempts</label>
                        <input type="number" class="form-input" value="5" min="3" max="10">
                    </div>
                </div>
            </div>

            <!-- Backup Settings -->
            <div class="card">
                <div class="card-header">
                    <h3 style="font-size: 1.125rem; font-weight: 600; color: var(--gray-800);">
                        <i class="fas fa-database" style="color: var(--info);"></i>
                        Backup & Maintenance
                    </h3>
                </div>
                <div class="card-body">
                    <div style="margin-bottom: 1rem;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                            <label style="font-weight: 500;">Auto Backup</label>
                            <input type="checkbox" checked style="transform: scale(1.2);">
                        </div>
                        <div style="font-size: 0.875rem; color: var(--gray-600);">Automatic daily database backups</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Backup Frequency</label>
                        <select class="form-select">
                            <option selected>Daily</option>
                            <option>Weekly</option>
                            <option>Monthly</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Backup Retention (days)</label>
                        <input type="number" class="form-input" value="30" min="7" max="365">
                    </div>
                    <div class="flex gap-2">
                        <button class="btn btn-info" style="flex: 1;">
                            <i class="fas fa-download"></i>
                            Create Backup
                        </button>
                        <button class="btn btn-secondary" style="flex: 1;">
                            <i class="fas fa-upload"></i>
                            Restore
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Information -->
        <div class="card" style="margin-top: 2rem;">
            <div class="card-header">
                <h3 style="font-size: 1.125rem; font-weight: 600; color: var(--gray-800);">
                    <i class="fas fa-info-circle"></i>
                    System Information
                </h3>
            </div>
            <div class="card-body">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                    <div>
                        <div style="font-size: 0.875rem; color: var(--gray-600); margin-bottom: 0.25rem;">System Version</div>
                        <div style="font-weight: 600;">EMS v2.1.0</div>
                    </div>
                    <div>
                        <div style="font-size: 0.875rem; color: var(--gray-600); margin-bottom: 0.25rem;">Laravel Version</div>
                        <div style="font-weight: 600;">10.x</div>
                    </div>
                    <div>
                        <div style="font-size: 0.875rem; color: var(--gray-600); margin-bottom: 0.25rem;">PHP Version</div>
                        <div style="font-weight: 600;">8.2.0</div>
                    </div>
                    <div>
                        <div style="font-size: 0.875rem; color: var(--gray-600); margin-bottom: 0.25rem;">Database</div>
                        <div style="font-weight: 600;">MySQL 8.0</div>
                    </div>
                    <div>
                        <div style="font-size: 0.875rem; color: var(--gray-600); margin-bottom: 0.25rem;">Last Backup</div>
                        <div style="font-weight: 600; color: var(--success);">Today, 2:00 AM</div>
                    </div>
                    <div>
                        <div style="font-size: 0.875rem; color: var(--gray-600); margin-bottom: 0.25rem;">System Status</div>
                        <div style="font-weight: 600; color: var(--success);">
                            <i class="fas fa-check-circle"></i> Healthy
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

```blade file="resources/views/admin/other/index.blade.php"
@extends('layouts.admin')

@section('title', 'Help & Support')

@section('content')
<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-question-circle"></i> Help & Support</h2>
        <button class="btn btn-primary">
            <i class="fas fa-ticket-alt"></i>
            Create Support Ticket
        </button>
    </div>
    <div class="card-body">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
            &lt;!-- Documentation -->
            <div class="card">
                <div class="card-header">
                    <h3 style="font-size: 1.125rem; font-weight: 600; color: var(--gray-800);">
                        <i class="fas fa-book" style="color: var(--primary);"></i>
                        Documentation
                    </h3>
                </div>
                <div class="card-body">
                    <ul style="list-style: none; padding: 0;">
                        <li style="margin-bottom: 0.75rem;">
                            <a href="#" style="text-decoration: none; color: var(--gray-700); display: flex; justify-content: space-between; align-items: center; padding: 0.5rem; border-radius: 0.5rem; transition: background 0.3s ease;">
                                <span><i class="fas fa-rocket" style="margin-right: 0.5rem; color: var(--success);"></i>Getting Started</span>
                                <i class="fas fa-external-link-alt" style="color: var(--gray-400);"></i>
                            </a>
                        </li>
                        <li style="margin-bottom: 0.75rem;">
                            <a href="#" style="text-decoration: none; color: var(--gray-700); display: flex; justify-content: space-between; align-items: center; padding: 0.5rem; border-radius: 0.5rem; transition: background 0.3s ease;">
                                <span><i class="fas fa-users" style="margin-right: 0.5rem; color: var(--info);"></i>User Management</span>
                                <i class="fas fa-external-link-alt" style="color: var(--gray-400);"></i>
                            </a>
                        </li>
                        <li style="margin-bottom: 0.75rem;">
                            <a href="#" style="text-decoration: none; color: var(--gray-700); display: flex; justify-content: space-between; align-items: center; padding: 0.5rem; border-radius: 0.5rem; transition: background 0.3s ease;">
                                <span><i class="fas fa-chart-bar" style="margin-right: 0.5rem; color: var(--warning);"></i>Reports & Analytics</span>
                                <i class="fas fa-external-link-alt" style="color: var(--gray-400);"></i>
                            </a>
                        </li>
                        <li style="margin-bottom: 0.75rem;">
                            <a href="#" style="text-decoration: none; color: var(--gray-700); display: flex; justify-content: space-between; align-items: center; padding: 0.5rem; border-radius: 0.5rem; transition: background 0.3s ease;">
                                <span><i class="fas fa-cog" style="margin-right: 0.5rem; color: var(--secondary);"></i>System Configuration</span>
                                <i class="fas fa-external-link-alt" style="color: var(--gray-400);"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            &lt;!-- FAQ -->
            <div class="card">
                <div class="card-header">
                    <h3 style="font-size: 1.125rem; font-weight: 600; color: var(--gray-800);">
                        <i class="fas fa-question" style="color: var(--warning);"></i>
                        Frequently Asked Questions
                    </h3>
                </div>
                <div class="card-body">
                    <div style="margin-bottom: 1rem;">
                        <div style="font-weight: 600; margin-bottom: 0.5rem; color: var(--gray-800);">How do I add a new employee?</div>
                        <div style="font-size: 0.875rem; color: var(--gray-600); line-height: 1.5;">Navigate to Employees → Add Employee and fill in the required information.</div>
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <div style="font-weight: 600; margin-bottom: 0.5rem; color: var(--gray-800);">How can I generate reports?</div>
                        <div style="font-size: 0.875rem; color: var(--gray-600); line-height: 1.5;">Go to Reports section and select the type of report you want to generate.</div>
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <div style="font-weight: 600; margin-bottom: 0.5rem; color: var(--gray-800);">How do I manage leave requests?</div>
                        <div style="font-size: 0.875rem; color: var(--gray-600); line-height: 1.5;">Access the Leave Management section to approve or reject leave requests.</div>
                    </div>
                    <a href="#" class="btn btn-secondary" style="width: 100%;">
                        <i class="fas fa-plus"></i>
                        View All FAQs
                    </a>
                </div>
            </div>

            &lt;!-- Contact Support -->
            <div class="card">
                <div class="card-header">
                    <h3 style="font-size: 1.125rem; font-weight: 600; color: var(--gray-800);">
                        <i class="fas fa-headset" style="color: var(--success);"></i>
                        Contact Support
                    </h3>
                </div>
                <div class="card-body">
                    <div style="margin-bottom: 1rem;">
                        <div style="font-size: 0.875rem; color: var(--gray-600); margin-bottom: 0.5rem;">Email Support</div>
                        <div style="font-weight: 600;">support@yourcompany.com</div>
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <div style="font-size: 0.875rem; color: var(--gray-600); margin-bottom: 0.5rem;">Phone Support</div>
                        <div style="font-weight: 600;">+1 (555) 123-4567</div>
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <div style="font-size: 0.875rem; color: var(--gray-600); margin-bottom: 0.5rem;">Support Hours</div>
                        <div style="font-weight: 600;">Mon-Fri: 9:00 AM - 6:00 PM</div>
                    </div>
                    <div class="flex gap-2">
                        <button class="btn btn-success" style="flex: 1;">
                            <i class="fas fa-envelope"></i>
                            Email Us
                        </button>
                        <button class="btn btn-info" style="flex: 1;">
                            <i class="fas fa-phone"></i>
                            Call Us
                        </button>
                    </div>
                </div>
            </div>
        </div>

        &lt;!-- System Status -->
        <div class="card" style="margin-top: 2rem;">
            <div class="card-header">
                <h3 style="font-size: 1.125rem; font-weight: 600; color: var(--gray-800);">
                    <i class="fas fa-server"></i>
                    System Status
                </h3>
            </div>
            <div class="card-body">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                    <div style="text-align: center; padding: 1rem; border-radius: 0.5rem; background: rgba(16, 185, 129, 0.1);">
                        <div style="font-size: 1.5rem; color: var(--success); margin-bottom: 0.5rem;">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div style="font-weight: 600; margin-bottom: 0.25rem;">Database</div>
                        <div style="font-size: 0.875rem; color: var(--success);">Operational</div>
                    </div>
                    <div style="text-align: center; padding: 1rem; border-radius: 0.5rem; background: rgba(16, 185, 129, 0.1);">
                        <div style="font-size: 1.5rem; color: var(--success); margin-bottom: 0.5rem;">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div style="font-weight: 600; margin-bottom: 0.25rem;">API Services</div>
                        <div style="font-size: 0.875rem; color: var(--success);">Operational</div>
                    </div>
                    <div style="text-align: center; padding: 1rem; border-radius: 0.5rem; background: rgba(16, 185, 129, 0.1);">
                        <div style="font-size: 1.5rem; color: var(--success); margin-bottom: 0.5rem;">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div style="font-weight: 600; margin-bottom: 0.25rem;">Email Service</div>
                        <div style="font-size: 0.875rem; color: var(--success);">Operational</div>
                    </div>
                    <div style="text-align: center; padding: 1rem; border-radius: 0.5rem; background: rgba(245, 158, 11, 0.1);">
                        <div style="font-size: 1.5rem; color: var(--warning); margin-bottom: 0.5rem;">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div style="font-weight: 600; margin-bottom: 0.25rem;">Backup Service</div>
                        <div style="font-size: 0.875rem; color: var(--warning);">Maintenance</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
