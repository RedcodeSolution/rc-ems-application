@extends('layouts.admin')

@section('title', 'Attendance Count')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/Employee/attendance.css') }}">

    <div class="card">
        <div class="card-header">
            <h2><i class="fas fa-calendar-check"></i> Attendance Count</h2>
        </div>

        <div class="card-body" style="padding:1rem;">
            <div class="admin-header">
                <div class="admin-header-left">
                    <p>Admin view hours, date</p>
                </div>

                <div class="admin-header-right" style="display: flex; flex-direction: column; align-items: flex-end; gap: 0.5rem;">
                    <input id="adminSearch" class="form-control" placeholder="Search name or date..."
                        style="min-width:220px;">

                    <div style="display: flex; gap: 0.5rem;">
                        <select id="adminStatus" class="filter-select">
                            <option value="">All Status</option>
                            <option value="present">Present</option>
                            <option value="absent">Absent</option>
                            <option value="late">Late</option>
                            <option value="halfday">Half Day</option>
                            <option value="overtime">Overtime</option>
                        </select>

                        <button class="btn btn-secondary" id="reloadBtn">
                            <i class="fas fa-sync"></i> Refresh
                        </button>
                    </div>
                </div>
            </div>


            <div style="overflow:auto;">
                <table class="table" id="attendanceTable" style="width:100%; border-collapse:collapse; min-width:900px;">
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
                    <tbody id="attendanceTbody">
                        <tr>
                            <td colspan="7" style="padding:1rem;text-align:center;color:var(--text-secondary);">
                                Loading...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="adminAttendanceModal" class="modal" aria-hidden="true">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-calendar-day"></i> Attendance Details</h3>
                <button class="close-btn" onclick="closeAdminModal()"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body" id="adminModalBody">
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeAdminModal()">Close</button>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            (function() {
                const API = '/admin/attendances';
                const tbody = document.getElementById('attendanceTbody');
                const searchEl = document.getElementById('adminSearch');
                const statusEl = document.getElementById('adminStatus');
                const reloadBtn = document.getElementById('reloadBtn');

                async function load() {
                    tbody.innerHTML =
                        `<tr><td colspan="7" style="padding:1rem;text-align:center;color:var(--text-secondary);">Loading...</td></tr>`;
                    try {
                        const res = await fetch(API, {
                            headers: {
                                Accept: 'application/json'
                            }
                        });
                        if (!res.ok) throw new Error('Network error');
                        const data = await res.json();
                        const items = Array.isArray(data) ? data : (data.attendances || []);
                        render(items);
                    } catch (e) {
                        console.error(e);
                        tbody.innerHTML =
                            `<tr><td colspan="7" style="padding:1rem;text-align:center;color:var(--danger);">Unable to load data</td></tr>`;
                    }
                }

                function render(items) {
                    if (!items || items.length === 0) {
                        tbody.innerHTML =
                            `<tr><td colspan="7" style="padding:1rem;text-align:center;color:var(--text-secondary);">No records found</td></tr>`;
                        return;
                    }

                    const rows = items.map(rec => {
                        const name = rec.employee_name ?? rec.name ?? (rec.employee?.name ?? 'Unknown');
                        const checkIn = rec.check_in_time ? fmtTime(rec.check_in_time) : '-';
                        const checkOut = rec.check_out_time ? fmtTime(rec.check_out_time) : '-';
                        const status = rec.status ? capitalize(rec.status) : '-';
                        const hours = rec.hours_worked ?? rec.total_hours ?? '-';
                        const date = rec.date ? fmtDate(rec.date) : (rec.attendance_date ?? '-');
                        const id = rec.id ?? '';

                        return `<tr data-name="${escapeHtml(name)}" data-status="${escapeHtml(rec.status ?? '')}">
                <td style="padding:0.75rem;vertical-align:middle;">${escapeHtml(name)}</td>
                <td style="padding:0.75rem;vertical-align:middle;">${escapeHtml(checkIn)}</td>
                <td style="padding:0.75rem;vertical-align:middle;">${escapeHtml(checkOut)}</td>
                <td style="padding:0.75rem;vertical-align:middle;"><span class="attendance-status ${escapeHtml(rec.status ?? '')}">${escapeHtml(status)}</span></td>
                <td style="padding:0.75rem;vertical-align:middle;">${escapeHtml(formatHours(hours))}</td>
                <td style="padding:0.75rem;vertical-align:middle;">${escapeHtml(date)}</td>
                <td style="padding:0.75rem;vertical-align:middle;text-align:right;"><button class="btn btn-secondary" onclick="openAdminDetails('${escapeJs(id)}')"><i class="fas fa-eye"></i></button></td>
            </tr>`;
                    }).join('');

                    tbody.innerHTML = rows;
                    applyFilters();
                }

                function fmtTime(v) {
                    try {
                        const d = new Date(v);
                        if (isNaN(d)) return v;
                        return d.toLocaleTimeString([], {
                            hour: 'numeric',
                            minute: '2-digit'
                        });
                    } catch {
                        return v;
                    }
                }

                function fmtDate(v) {
                    try {
                        const d = new Date(v);
                        if (isNaN(d)) return v;
                        return d.toLocaleDateString([], {
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        });
                    } catch {
                        return v;
                    }
                }

                function capitalize(s) {
                    if (!s) return '';
                    return String(s).replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                }

                function escapeHtml(s) {
                    if (s == null) return '';
                    return String(s).replaceAll('&', '&amp;').replaceAll('<', '&lt;').replaceAll('>', '&gt;').replaceAll(
                        '"', '&quot;').replaceAll("'", '&#39;');
                }

                function escapeJs(s) {
                    if (s == null) return '';
                    return String(s).replace(/'/g, "\\'").replace(/"/g, '\\"');
                }

                function formatHours(h) {
                    if (h == null) return '-';
                    if (typeof h === 'number') {
                        const mins = Math.round(h * 60);
                        const hh = Math.floor(mins / 60);
                        const mm = mins % 60;
                        return `${hh}h ${String(mm).padStart(2,'0')}m`;
                    }
                    return h;
                }

                function applyFilters() {
                    const q = (searchEl.value || '').toLowerCase().trim();
                    const status = (statusEl.value || '').toLowerCase();
                    document.querySelectorAll('#attendanceTbody tr').forEach(tr => {
                        const name = (tr.dataset.name || '').toLowerCase();
                        const rowStatus = (tr.dataset.status || '').toLowerCase();
                        const matchesQ = !q || tr.innerText.toLowerCase().includes(q) || name.includes(q);
                        const matchesStatus = !status || rowStatus === status;
                        tr.style.display = (matchesQ && matchesStatus) ? '' : 'none';
                    });
                }


                window.openAdminDetails = function(id) {
                    const row = Array.from(document.querySelectorAll('#attendanceTbody tr'))
                        .find(r => r.style.display !== 'none' && (id === '' || r.innerHTML.includes(id)));

                    if (row) {
                        const cells = row.querySelectorAll('td');
                        const html = `
            <div class="summary-item"><span class="summary-label">Name:</span><span class="summary-value">${escapeHtml(cells[0].innerText)}</span></div>
            <div class="summary-item"><span class="summary-label">Check In:</span><span class="summary-value">${escapeHtml(cells[1].innerText)}</span></div>
            <div class="summary-item"><span class="summary-label">Check Out:</span><span class="summary-value">${escapeHtml(cells[2].innerText)}</span></div>
            <div class="summary-item"><span class="summary-label">Status:</span><span class="summary-value">${escapeHtml(cells[3].innerText)}</span></div>
            <div class="summary-item"><span class="summary-label">Total Hours:</span><span class="summary-value">${escapeHtml(cells[4].innerText)}</span></div>
            <div class="summary-item"><span class="summary-label">Date:</span><span class="summary-value">${escapeHtml(cells[5].innerText)}</span></div>
        `;
                        document.getElementById('adminModalBody').innerHTML = html;
                    } else {
                        document.getElementById('adminModalBody').innerHTML =
                            `<p style="color:var(--text-secondary)">No details available locally.</p>`;
                    }

                    const modal = document.getElementById('adminAttendanceModal');

                    // FIX: ensure modal is displayed
                    modal.style.display = "flex";

                    // allow CSS transition to apply
                    setTimeout(() => {
                        modal.classList.add('active');
                    }, 10);
                };



                window.closeAdminModal = function() {
                    const modal = document.getElementById('adminAttendanceModal');
                    modal.classList.remove('active');

                    setTimeout(() => {
                        modal.style.display = "none";
                    }, 250);
                };


                searchEl.addEventListener('input', applyFilters);
                statusEl.addEventListener('change', applyFilters);
                reloadBtn.addEventListener('click', load);

                load();
            })();
        </script>
    @endpush
@endsection
