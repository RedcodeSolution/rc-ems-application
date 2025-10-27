@extends('layouts.super_admin')

@section('title', 'Attendance Count')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/Employee/attendance.css') }}">

    <div class="card">
        <div class="card-header">
            <h2><i class="fas fa-calendar-check"></i> Attendance Count</h2>
            <div class="flex gap-2">
                <a href="#" class="btn btn-secondary" id="exportAttendanceBtn"><i class="fas fa-download"></i> Export</a>
            </div>
        </div>

        <div class="card-body" style="padding:1rem;">
            <div style="display:flex;justify-content:space-between;align-items:center;gap:1rem;margin-bottom:1rem;">
                <div>
                    <p style="margin:0;color:var(--text-secondary);">
                        Super Admin view — name, check in, check out, status, total hours, date
                    </p>
                </div>
                <div style="display:flex;gap:0.5rem;align-items:center;">
                    <input id="superSearch" class="form-control" placeholder="Search name or date..."
                        style="min-width:220px;">
                    <select id="superStatus" class="filter-select">
                        <option value="">All Status</option>
                        <option value="present">Present</option>
                        <option value="absent">Absent</option>
                        <option value="late">Late</option>
                        <option value="early_leave">Early Leave</option>
                        <option value="overtime">Overtime</option>
                    </select>
                    <button class="btn btn-secondary" id="superReloadBtn"><i class="fas fa-sync"></i> Refresh</button>
                </div>
            </div>

            <div style="overflow:auto;">
                <table class="table" id="superAttendanceTable"
                    style="width:100%; border-collapse:collapse; min-width:900px;">
                    <thead>
                        <tr style="text-align:left;border-bottom:1px solid var(--border-light);">
                            <th style="padding:0.75rem;">Name</th>
                            <th style="padding:0.75rem;">Check In</th>
                            <th style="padding:0.75rem;">Check Out</th>
                            <th style="padding:0.75rem;">Status</th>
                            <th style="padding:0.75rem;">Total Hours</th>
                            <th style="padding:0.75rem;">Date</th>
                            <th style="padding:0.75rem;text-align:right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="superAttendanceTbody">
                        <tr>
                            <td colspan="7" style="padding:1rem;text-align:center;color:var(--text-secondary);">
                                Loading...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Details modal -->
    <div id="superAttendanceModal" class="modal" aria-hidden="true">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-calendar-day"></i> Attendance Details</h3>
                <button class="close-btn" onclick="closeSuperModal()"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body" id="superModalBody"></div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeSuperModal()">Close</button>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            (function() {
                const API = '/super-admin/attendances';
                const tbody = document.getElementById('superAttendanceTbody');
                const searchEl = document.getElementById('superSearch');
                const statusEl = document.getElementById('superStatus');
                const reloadBtn = document.getElementById('superReloadBtn');

                async function load() {
                    tbody.innerHTML =
                        `<tr><td colspan="7" style="padding:1rem;text-align:center;color:var(--text-secondary);">Loading...</td></tr>`;
                    try {
                        const res = await fetch(API, {
                            headers: {
                                Accept: 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });
                        if (!res.ok) throw new Error('Network error');
                        const data = await res.json();
                        const items = data.attendances || []; // ✅ use your Laravel key
                        render(items);
                    } catch (e) {
                        console.error(e);
                        tbody.innerHTML =
                            `<tr><td colspan="7" style="padding:1rem;text-align:center;color:var(--danger);">Unable to load data</td></tr>`;
                    }
                }

                function render(items) {
                    if (!items.length) {
                        tbody.innerHTML =
                            `<tr><td colspan="7" style="padding:1rem;text-align:center;color:var(--text-secondary);">No records found</td></tr>`;
                        return;
                    }

                    const rows = items.map(a => `
            <tr data-name="${a.employee_name}" data-status="${a.status}">
                <td>${a.employee_name}</td>
                <td>${a.check_in_time ?? '-'}</td>
                <td>${a.check_out_time ?? '-'}</td>
                <td><span class="attendance-status ${a.status}">${capitalize(a.status)}</span></td>
                <td>${a.hours_worked ?? '-'}</td>
                <td>${a.date ?? '-'}</td>
                <td style="text-align:right;">
                    <button class="btn btn-secondary" onclick="openSuperDetails('${a.id}')"><i class="fas fa-eye"></i></button>
                </td>
            </tr>
        `).join('');

                    tbody.innerHTML = rows;
                    applyFilters();
                }

                function capitalize(s) {
                    if (!s) return '-';
                    return s.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase());
                }

                function applyFilters() {
                    const q = searchEl.value.toLowerCase().trim();
                    const status = statusEl.value.toLowerCase();

                    document.querySelectorAll('#superAttendanceTbody tr').forEach(tr => {
                        const name = (tr.dataset.name || '').toLowerCase();
                        const rowStatus = (tr.dataset.status || '').toLowerCase();
                        const matchesQ = !q || name.includes(q);
                        const matchesStatus = !status || rowStatus === status;
                        tr.style.display = (matchesQ && matchesStatus) ? '' : 'none';
                    });
                }

                window.openSuperDetails = function(id) {
                    const row = document.querySelector(`#superAttendanceTbody tr[data-id="${id}"]`);
                    const modalBody = document.getElementById('superModalBody');
                    if (row) {
                        modalBody.innerHTML = `
                <p><strong>Name:</strong> ${row.dataset.name}</p>
                <p><strong>Status:</strong> ${row.dataset.status}</p>
            `;
                    } else {
                        modalBody.innerHTML = `<p>No details found.</p>`;
                    }
                    document.getElementById('superAttendanceModal').classList.add('active');
                };

                window.closeSuperModal = function() {
                    document.getElementById('superAttendanceModal').classList.remove('active');
                };

                searchEl.addEventListener('input', applyFilters);
                statusEl.addEventListener('change', applyFilters);
                reloadBtn.addEventListener('click', load);

                load();
            })();
        </script>
    @endpush
@endsection
