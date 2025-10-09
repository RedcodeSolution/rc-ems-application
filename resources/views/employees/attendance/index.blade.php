@extends('layouts.employee')

@section('title', 'My Attendance')

@section('content')
    <div class="attendance-container">
        <!-- Attendance Header -->
        <div class="attendance-header">
            <div class="header-content">
                <div class="header-info">
                    <h1><i class="fas fa-calendar-check"></i> My Attendance</h1>
                    <p>Track your daily attendance and work hours</p>
                </div>
                <div class="header-actions">
                    <button id="clockInBtn" class="btn btn-success" onclick="clockIn()"
                        style="{{ $isClockedIn ? 'display:none;' : 'display:inline-flex;' }}">
                        <i class="fas fa-sign-in-alt"></i> Clock In
                    </button>
                    <button id="clockOutBtn" class="btn btn-danger" onclick="clockOut()"
                        style="{{ $isClockedIn ? 'display:inline-flex;' : 'display:none;' }}">
                        <i class="fas fa-sign-out-alt"></i> Clock Out
                    </button>
                    <button class="btn btn-secondary" onclick="toggleViewMode()">
                        <i class="fas fa-th-large" id="viewModeIcon"></i>
                        <span id="viewModeText">Grid View</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Attendance Statistics -->
        <div class="attendance-stats">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-calendar-day"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ $presentDays }}</h3>
                    <p>Present Days</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ $totalHours }}</h3>
                    <p>Total Hours</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-calendar-times"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ $absentDays }}</h3>
                    <p>Absent Days</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-user-clock"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ $lateArrivals }}</h3>
                    <p>Late Arrivals</p>
                </div>
            </div>
        </div>


        <!-- Current Status -->
        <div class="current-status">
            <h2>Today's Status</h2>
            <div class="status-grid">
                <div class="status-card active" id="todayStatus">
                    <div class="status-icon">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div class="status-info">
                        <h3>{{ $status }}</h3>

                        @if ($checkInTimeFormatted)
                            <p>Clocked in at {{ $checkInTimeFormatted }}</p>
                            <div class="work-hours">
                                <span>Work Hours: <strong>{{ $workingHoursNow }}</strong></span>
                            </div>
                        @else
                            <p>No clock-in data for today</p>
                        @endif
                    </div>
                </div>
                <div class="status-card">
                    <div class="status-icon">
                        <i class="fas fa-coffee"></i>
                    </div>
                    <div class="status-info">
                        <h3>Break Status</h3>
                        <p>Not on break</p>
                        <div class="break-actions">
                            <button class="btn-sm btn-break" onclick="startBreak()">Start Break</button>
                        </div>
                    </div>
                </div>
                <div class="status-card">
                    <div class="status-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="status-info">
                        <h3>Emergency Status</h3>
                        <p>No emergency breaks</p>
                        <div class="emergency-actions">
                            <button class="btn-sm btn-emergency" onclick="reportEmergency()">Report Emergency</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Search and Filter -->
        <div class="attendance-controls">
            <div class="search-section">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search attendance records..." id="searchInput">
                </div>
                <div class="filter-section">
                    <select id="monthFilter" class="filter-select">
                        <option value="">All Months</option>
                        <option value="january">January 2024</option>
                        <option value="february">February 2024</option>
                        <option value="march">March 2024</option>
                        <option value="april">April 2024</option>
                    </select>
                    <select id="statusFilter" class="filter-select">
                        <option value="">All Status</option>
                        <option value="present">Present</option>
                        <option value="absent">Absent</option>
                        <option value="late">Late</option>
                        <option value="early_leave">Early Leave</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Attendance Records -->
        {{-- <div class="attendance-content">
            <div class="attendance-grid" id="attendanceGrid">
                <!-- Present Day Record -->
                <div class="attendance-card present" data-status="present" data-month="january">
                    <div class="attendance-header">
                        <div class="attendance-date">
                            <h4>January 15, 2024</h4>
                            <p>Monday</p>
                        </div>
                        <div class="attendance-status present">
                            <i class="fas fa-check-circle"></i>
                            Present
                        </div>
                    </div>
                    <div class="attendance-details">
                        <div class="time-details">
                            <div class="time-entry">
                                <span class="time-label">Clock In:</span>
                                <span class="time-value">9:00 AM</span>
                            </div>
                            <div class="time-entry">
                                <span class="time-label">Clock Out:</span>
                                <span class="time-value">6:00 PM</span>
                            </div>
                            <div class="time-entry">
                                <span class="time-label">Break Time:</span>
                                <span class="time-value">1h 00m</span>
                            </div>
                            <div class="time-entry total-hours">
                                <span class="time-label">Total Hours:</span>
                                <span class="time-value">8h 00m</span>
                            </div>
                        </div>
                        <div class="attendance-notes">
                            <p><i class="fas fa-sticky-note"></i> Regular working day</p>
                        </div>
                    </div>
                    <div class="attendance-actions">
                        <button class="action-btn" onclick="viewAttendanceDetails('2024-01-15')">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="action-btn" onclick="editAttendance('2024-01-15')">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="action-btn" onclick="downloadReport('2024-01-15')">
                            <i class="fas fa-download"></i>
                        </button>
                    </div>
                </div>

                <!-- Late Arrival Record -->
                <div class="attendance-card late" data-status="late" data-month="january">
                    <div class="attendance-header">
                        <div class="attendance-date">
                            <h4>January 14, 2024</h4>
                            <p>Sunday</p>
                        </div>
                        <div class="attendance-status late">
                            <i class="fas fa-clock"></i>
                            Late
                        </div>
                    </div>
                    <div class="attendance-details">
                        <div class="time-details">
                            <div class="time-entry">
                                <span class="time-label">Clock In:</span>
                                <span class="time-value">9:30 AM</span>
                            </div>
                            <div class="time-entry">
                                <span class="time-label">Clock Out:</span>
                                <span class="time-value">6:30 PM</span>
                            </div>
                            <div class="time-entry">
                                <span class="time-label">Break Time:</span>
                                <span class="time-value">1h 00m</span>
                            </div>
                            <div class="time-entry total-hours">
                                <span class="time-label">Total Hours:</span>
                                <span class="time-value">8h 00m</span>
                            </div>
                        </div>
                        <div class="attendance-notes">
                            <p><i class="fas fa-exclamation-triangle"></i> 30 minutes late - Traffic delay</p>
                        </div>
                    </div>
                    <div class="attendance-actions">
                        <button class="action-btn" onclick="viewAttendanceDetails('2024-01-14')">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="action-btn" onclick="editAttendance('2024-01-14')">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="action-btn" onclick="downloadReport('2024-01-14')">
                            <i class="fas fa-download"></i>
                        </button>
                    </div>
                </div>

                <!-- Absent Day Record -->
                <div class="attendance-card absent" data-status="absent" data-month="january">
                    <div class="attendance-header">
                        <div class="attendance-date">
                            <h4>January 13, 2024</h4>
                            <p>Saturday</p>
                        </div>
                        <div class="attendance-status absent">
                            <i class="fas fa-times-circle"></i>
                            Absent
                        </div>
                    </div>
                    <div class="attendance-details">
                        <div class="time-details">
                            <div class="time-entry">
                                <span class="time-label">Clock In:</span>
                                <span class="time-value">-</span>
                            </div>
                            <div class="time-entry">
                                <span class="time-label">Clock Out:</span>
                                <span class="time-value">-</span>
                            </div>
                            <div class="time-entry">
                                <span class="time-label">Break Time:</span>
                                <span class="time-value">-</span>
                            </div>
                            <div class="time-entry total-hours">
                                <span class="time-label">Total Hours:</span>
                                <span class="time-value">0h 00m</span>
                            </div>
                        </div>
                        <div class="attendance-notes">
                            <p><i class="fas fa-user-times"></i> Sick leave approved</p>
                        </div>
                    </div>
                    <div class="attendance-actions">
                        <button class="action-btn" onclick="viewAttendanceDetails('2024-01-13')">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="action-btn" onclick="editAttendance('2024-01-13')">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="action-btn" onclick="downloadReport('2024-01-13')">
                            <i class="fas fa-download"></i>
                        </button>
                    </div>
                </div>

                <!-- Early Leave Record -->
                <div class="attendance-card early-leave" data-status="early_leave" data-month="january">
                    <div class="attendance-header">
                        <div class="attendance-date">
                            <h4>January 12, 2024</h4>
                            <p>Friday</p>
                        </div>
                        <div class="attendance-status early-leave">
                            <i class="fas fa-sign-out-alt"></i>
                            Early Leave
                        </div>
                    </div>
                    <div class="attendance-details">
                        <div class="time-details">
                            <div class="time-entry">
                                <span class="time-label">Clock In:</span>
                                <span class="time-value">9:00 AM</span>
                            </div>
                            <div class="time-entry">
                                <span class="time-label">Clock Out:</span>
                                <span class="time-value">4:00 PM</span>
                            </div>
                            <div class="time-entry">
                                <span class="time-label">Break Time:</span>
                                <span class="time-value">1h 00m</span>
                            </div>
                            <div class="time-entry total-hours">
                                <span class="time-label">Total Hours:</span>
                                <span class="time-value">6h 00m</span>
                            </div>
                        </div>
                        <div class="attendance-notes">
                            <p><i class="fas fa-calendar-alt"></i> Early leave approved - Personal appointment</p>
                        </div>
                    </div>
                    <div class="attendance-actions">
                        <button class="action-btn" onclick="viewAttendanceDetails('2024-01-12')">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="action-btn" onclick="editAttendance('2024-01-12')">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="action-btn" onclick="downloadReport('2024-01-12')">
                            <i class="fas fa-download"></i>
                        </button>
                    </div>
                </div>

                <!-- Normal Working Day -->
                <div class="attendance-card present" data-status="present" data-month="january">
                    <div class="attendance-header">
                        <div class="attendance-date">
                            <h4>January 11, 2024</h4>
                            <p>Thursday</p>
                        </div>
                        <div class="attendance-status present">
                            <i class="fas fa-check-circle"></i>
                            Present
                        </div>
                    </div>
                    <div class="attendance-details">
                        <div class="time-details">
                            <div class="time-entry">
                                <span class="time-label">Clock In:</span>
                                <span class="time-value">8:45 AM</span>
                            </div>
                            <div class="time-entry">
                                <span class="time-label">Clock Out:</span>
                                <span class="time-value">5:45 PM</span>
                            </div>
                            <div class="time-entry">
                                <span class="time-label">Break Time:</span>
                                <span class="time-value">1h 00m</span>
                            </div>
                            <div class="time-entry total-hours">
                                <span class="time-label">Total Hours:</span>
                                <span class="time-value">8h 00m</span>
                            </div>
                        </div>
                        <div class="attendance-notes">
                            <p><i class="fas fa-thumbs-up"></i> Early arrival - Good performance</p>
                        </div>
                    </div>
                    <div class="attendance-actions">
                        <button class="action-btn" onclick="viewAttendanceDetails('2024-01-11')">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="action-btn" onclick="editAttendance('2024-01-11')">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="action-btn" onclick="downloadReport('2024-01-11')">
                            <i class="fas fa-download"></i>
                        </button>
                    </div>
                </div>

                <!-- Overtime Day -->
                <div class="attendance-card overtime" data-status="present" data-month="january">
                    <div class="attendance-header">
                        <div class="attendance-date">
                            <h4>January 10, 2024</h4>
                            <p>Wednesday</p>
                        </div>
                        <div class="attendance-status overtime">
                            <i class="fas fa-clock"></i>
                            Overtime
                        </div>
                    </div>
                    <div class="attendance-details">
                        <div class="time-details">
                            <div class="time-entry">
                                <span class="time-label">Clock In:</span>
                                <span class="time-value">9:00 AM</span>
                            </div>
                            <div class="time-entry">
                                <span class="time-label">Clock Out:</span>
                                <span class="time-value">8:00 PM</span>
                            </div>
                            <div class="time-entry">
                                <span class="time-label">Break Time:</span>
                                <span class="time-value">1h 00m</span>
                            </div>
                            <div class="time-entry total-hours">
                                <span class="time-label">Total Hours:</span>
                                <span class="time-value">10h 00m</span>
                            </div>
                        </div>
                        <div class="attendance-notes">
                            <p><i class="fas fa-business-time"></i> Overtime approved - Project deadline</p>
                        </div>
                    </div>
                    <div class="attendance-actions">
                        <button class="action-btn" onclick="viewAttendanceDetails('2024-01-10')">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="action-btn" onclick="editAttendance('2024-01-10')">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="action-btn" onclick="downloadReport('2024-01-10')">
                            <i class="fas fa-download"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div> --}}

        @php
            use Carbon\Carbon;
        @endphp
        <div class="attendance-content">
            <div class="attendance-grid" id="attendanceGrid">
                @forelse ($attendances as $attendance)
                    @php
                        $date = Carbon::parse($attendance->date);
                        $dayName = $date->format('l');
                        $formattedDate = $date->format('F d, Y');

                        // Status color class
                        $statusClass = match ($attendance->status) {
                            'present' => 'present',
                            'absent' => 'absent',
                            'late' => 'late',
                            'early_leave' => 'early-leave',
                            'overtime' => 'overtime',
                            default => '',
                        };

                        // Format times
                        $checkIn = $attendance->check_in_time
                            ? Carbon::parse($attendance->check_in_time)->format('g:i A')
                            : '-';
                        $checkOut = $attendance->check_out_time
                            ? Carbon::parse($attendance->check_out_time)->format('g:i A')
                            : '-';

                        // Break time format
                        $breakDuration = $attendance->break_duration
                            ? sprintf(
                                '%dh %02dm',
                                floor(($attendance->break_duration * 60) / 60),
                                ($attendance->break_duration * 60) % 60,
                            )
                            : '-';

                        // Hours worked
                        $hoursWorked = $attendance->hours_worked
                            ? sprintf(
                                '%dh %02dm',
                                floor(($attendance->hours_worked * 60) / 60),
                                ($attendance->hours_worked * 60) % 60,
                            )
                            : '0h 00m';

                        // Notes
                        $notes = $attendance->notes ?? 'No notes';
                    @endphp

                    <div class="attendance-card {{ $statusClass }}" data-status="{{ $attendance->status }}"
                        data-month="{{ strtolower($date->format('F')) }}">
                        <div class="attendance-header">
                            <div class="attendance-date">
                                <h4>{{ $formattedDate }}</h4>
                                <p>{{ $dayName }}</p>
                            </div>
                            <div class="attendance-status {{ $statusClass }}">
                                @switch($attendance->status)
                                    @case('present')
                                        <i class="fas fa-check-circle"></i> Present
                                    @break

                                    @case('absent')
                                        <i class="fas fa-times-circle"></i> Absent
                                    @break

                                    @case('late')
                                        <i class="fas fa-clock"></i> Late
                                    @break

                                    @case('early_leave')
                                        <i class="fas fa-sign-out-alt"></i> Early Leave
                                    @break

                                    @case('overtime')
                                        <i class="fas fa-business-time"></i> Overtime
                                    @break

                                    @default
                                        <i class="fas fa-calendar-day"></i> Working Day
                                @endswitch
                            </div>
                        </div>

                        <div class="attendance-details">
                            <div class="time-details">
                                <div class="time-entry">
                                    <span class="time-label">Clock In:</span>
                                    <span class="time-value">{{ $checkIn }}</span>
                                </div>
                                <div class="time-entry">
                                    <span class="time-label">Clock Out:</span>
                                    <span class="time-value">{{ $checkOut }}</span>
                                </div>
                                <div class="time-entry">
                                    <span class="time-label">Break Time:</span>
                                    <span class="time-value">{{ $breakDuration }}</span>
                                </div>
                                <div class="time-entry total-hours">
                                    <span class="time-label">Total Hours:</span>
                                    <span class="time-value">{{ $hoursWorked }}</span>
                                </div>
                            </div>
                            <div class="attendance-notes">
                                <p><i class="fas fa-sticky-note"></i> {{ $notes }}</p>
                            </div>
                        </div>

                        <div class="attendance-actions">
                            <button class="action-btn" onclick="viewAttendanceDetails('{{ $attendance->date }}')">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="action-btn" onclick="editAttendance('{{ $attendance->date }}')">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="action-btn" onclick="downloadReport('{{ $attendance->date }}')">
                                <i class="fas fa-download"></i>
                            </button>
                        </div>
                    </div>
                    @empty
                        <p class="text-center text-gray-500">No attendance records found.</p>
                    @endforelse
                </div>
            </div>


            <!-- Clock In/Out Modal -->
            <div id="clockModal" class="modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3><i class="fas fa-clock" id="clockModalIcon"></i> <span id="clockModalTitle">Clock In</span></h3>
                        <button class="close-btn" onclick="closeClockModal()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="clock-display">
                            <div class="current-time" id="currentTime">
                                10:30 AM
                            </div>
                            <div class="current-date" id="currentDate">
                                Monday, January 15, 2024
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="clockNotes">Notes (Optional)</label>
                            <textarea id="clockNotes" class="form-control" rows="3" placeholder="Add any notes about your attendance..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" onclick="closeClockModal()">Cancel</button>
                        <button class="btn btn-primary" onclick="confirmClock()" id="confirmClockBtn">
                            <i class="fas fa-check"></i>
                            Confirm Clock In
                        </button>
                    </div>
                </div>
            </div>

            <!-- Attendance Details Modal -->
            <div id="attendanceModal" class="modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3><i class="fas fa-calendar-day"></i> Attendance Details</h3>
                        <button class="close-btn" onclick="closeAttendanceModal()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="attendance-summary" id="attendanceSummary">
                            <!-- Dynamic content will be loaded here -->
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" onclick="closeAttendanceModal()">Close</button>
                        <button class="btn btn-primary" onclick="downloadDetailedReport()">
                            <i class="fas fa-download"></i>
                            Download Report
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <style>
            .attendance-container {
                max-width: 1200px;
                margin: 0 auto;
                padding: 0;
            }

            .attendance-header {
                background: var(--gradient-hero);
                background-size: 400% 400%;
                animation: gradientShift 18s ease infinite;
                color: white;
                padding: 2rem;
                border-radius: 1rem;
                margin-bottom: 2rem;
                box-shadow: var(--shadow-xl);
                position: relative;
                overflow: hidden;
            }

            @keyframes gradientShift {
                0% {
                    background-position: 0% 50%;
                }

                25% {
                    background-position: 100% 50%;
                }

                50% {
                    background-position: 100% 100%;
                }

                75% {
                    background-position: 0% 100%;
                }

                100% {
                    background-position: 0% 50%;
                }
            }

            .attendance-header::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(255, 255, 255, 0.05);
                backdrop-filter: blur(10px);
            }

            .header-content {
                display: flex;
                justify-content: space-between;
                align-items: center;
                position: relative;
                z-index: 1;
            }

            .header-info h1 {
                margin: 0;
                font-size: 2rem;
                font-weight: 700;
                text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                display: flex;
                align-items: center;
                gap: 0.75rem;
            }

            .header-info p {
                margin: 0.5rem 0 0 0;
                color: rgba(255, 255, 255, 0.9);
                font-size: 1.1rem;
            }

            .header-actions {
                display: flex;
                gap: 1rem;
            }

            .header-actions .btn {
                background: rgba(255, 255, 255, 0.2);
                border: 2px solid rgba(255, 255, 255, 0.3);
                color: white;
                padding: 0.75rem 1.5rem;
                border-radius: 0.5rem;
                font-weight: 600;
                backdrop-filter: blur(10px);
                transition: all 0.3s ease;
            }

            .header-actions .btn:hover {
                background: rgba(255, 255, 255, 0.3);
                transform: translateY(-2px);
            }

            .attendance-stats {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 1.5rem;
                margin-bottom: 2rem;
            }

            .stat-card {
                background: white;
                padding: 1.5rem;
                border-radius: 1rem;
                box-shadow: var(--shadow-lg);
                border: 1px solid var(--border-light);
                display: flex;
                align-items: center;
                gap: 1rem;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .stat-card:hover {
                transform: translateY(-4px);
                box-shadow: var(--shadow-xl);
                border-color: var(--redcode-primary);
            }

            .stat-icon {
                width: 60px;
                height: 60px;
                background: var(--gradient-hero);
                background-size: 400% 400%;
                animation: gradientShift 18s ease infinite;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-size: 1.5rem;
                box-shadow: var(--shadow-sm);
            }

            .stat-info h3 {
                margin: 0;
                font-size: 1.5rem;
                font-weight: 700;
                color: var(--text-primary);
            }

            .stat-info p {
                margin: 0.25rem 0 0 0;
                color: var(--text-secondary);
                font-size: 0.875rem;
            }

            .current-status {
                margin-bottom: 2rem;
            }

            .current-status h2 {
                color: var(--text-primary);
                margin-bottom: 1rem;
                font-size: 1.5rem;
                font-weight: 600;
            }

            .status-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 1.5rem;
            }

            .status-card {
                background: white;
                padding: 1.5rem;
                border-radius: 1rem;
                box-shadow: var(--shadow-lg);
                border: 1px solid var(--border-light);
                display: flex;
                align-items: center;
                gap: 1rem;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .status-card:hover {
                transform: translateY(-4px);
                box-shadow: var(--shadow-xl);
                border-color: var(--redcode-primary);
            }

            .status-card.active {
                border-color: var(--redcode-primary);
                background: var(--redcode-primary-light);
            }

            .status-icon {
                width: 50px;
                height: 50px;
                background: var(--gradient-hero);
                background-size: 400% 400%;
                animation: gradientShift 18s ease infinite;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-size: 1.25rem;
                box-shadow: var(--shadow-sm);
            }

            .status-info h3 {
                margin: 0;
                font-size: 1.1rem;
                font-weight: 600;
                color: var(--text-primary);
            }

            .status-info p {
                margin: 0.25rem 0 0.5rem 0;
                color: var(--text-secondary);
                font-size: 0.875rem;
            }

            .work-hours {
                font-size: 0.875rem;
                color: var(--text-primary);
            }

            .break-actions {
                margin-top: 0.5rem;
            }

            .btn-sm {
                padding: 0.5rem 1rem;
                font-size: 0.875rem;
                border-radius: 0.5rem;
                border: none;
                cursor: pointer;
                transition: all 0.3s ease;
            }

            .btn-break {
                background: var(--redcode-primary);
                color: white;
            }

            .btn-break:hover {
                background: var(--redcode-primary-dark);
            }

            .btn-emergency {
                background: #dc2626;
                color: white;
                margin-left: 0.5rem;
            }

            .btn-emergency:hover {
                background: #b91c1c;
                transform: scale(1.05);
            }

            .emergency-actions {
                margin-top: 0.5rem;
            }

            .progress-bar {
                height: 6px;
                background: var(--bg-secondary);
                border-radius: 3px;
                overflow: hidden;
                margin-top: 0.5rem;
            }

            .progress-fill {
                height: 100%;
                background: var(--gradient-hero);
                background-size: 400% 400%;
                animation: gradientShift 18s ease infinite;
                transition: width 0.3s ease;
            }

            .attendance-controls {
                background: white;
                padding: 1.5rem;
                border-radius: 1rem;
                box-shadow: var(--shadow-lg);
                border: 1px solid var(--border-light);
                margin-bottom: 2rem;
            }

            .search-section {
                display: flex;
                gap: 1rem;
                align-items: center;
            }

            .search-box {
                flex: 1;
                position: relative;
            }

            .search-box i {
                position: absolute;
                left: 1rem;
                top: 50%;
                transform: translateY(-50%);
                color: var(--text-secondary);
            }

            .search-box input {
                width: 100%;
                padding: 0.875rem 1rem 0.875rem 2.5rem;
                border: 2px solid var(--border-light);
                border-radius: 0.75rem;
                font-size: 1rem;
                background: var(--bg-primary);
                transition: all 0.3s ease;
            }

            .search-box input:focus {
                outline: none;
                border-color: var(--redcode-primary);
                box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.1);
            }

            .filter-section {
                display: flex;
                gap: 1rem;
            }

            .filter-select {
                padding: 0.875rem 1rem;
                border: 2px solid var(--border-light);
                border-radius: 0.75rem;
                font-size: 1rem;
                background: var(--bg-primary);
                transition: all 0.3s ease;
                min-width: 150px;
            }

            .filter-select:focus {
                outline: none;
                border-color: var(--redcode-primary);
                box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.1);
            }

            .attendance-content {
                background: white;
                padding: 2rem;
                border-radius: 1rem;
                box-shadow: var(--shadow-lg);
                border: 1px solid var(--border-light);
            }

            .attendance-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
                gap: 1.5rem;
            }

            .attendance-grid.list-view {
                grid-template-columns: 1fr;
            }

            .attendance-grid.list-view .attendance-card {
                display: flex;
                align-items: center;
                gap: 1.5rem;
                padding: 1.5rem;
            }

            .attendance-grid.list-view .attendance-details {
                flex: 1;
            }

            .attendance-grid.list-view .attendance-actions {
                flex-shrink: 0;
            }

            .attendance-card {
                background: var(--bg-primary);
                border: 2px solid var(--border-light);
                border-radius: 1rem;
                padding: 1.5rem;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                position: relative;
                overflow: hidden;
                box-shadow: var(--shadow-sm);
            }

            .attendance-card::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 4px;
                height: 100%;
                background: var(--redcode-primary);
                transform: scaleY(0);
                transition: transform 0.3s ease;
            }

            .attendance-card:hover::before {
                transform: scaleY(1);
            }

            .attendance-card:hover {
                transform: translateY(-2px);
                box-shadow: var(--shadow-lg);
                border-color: var(--redcode-primary);
            }

            .attendance-card.present {
                border-color: #22C55E;
                background: linear-gradient(135deg, rgba(34, 197, 94, 0.05), rgba(34, 197, 94, 0.02));
            }

            .attendance-card.late {
                border-color: #F59E0B;
                background: linear-gradient(135deg, rgba(245, 158, 11, 0.05), rgba(245, 158, 11, 0.02));
            }

            .attendance-card.absent {
                border-color: #DC2626;
                background: linear-gradient(135deg, rgba(220, 38, 38, 0.05), rgba(220, 38, 38, 0.02));
            }

            .attendance-card.early-leave {
                border-color: #8B5CF6;
                background: linear-gradient(135deg, rgba(139, 92, 246, 0.05), rgba(139, 92, 246, 0.02));
            }

            .attendance-card.overtime {
                border-color: #06B6D4;
                background: linear-gradient(135deg, rgba(6, 182, 212, 0.05), rgba(6, 182, 212, 0.02));
            }

            .attendance-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 1rem;
            }

            .attendance-date h4 {
                margin: 0;
                font-size: 1.1rem;
                font-weight: 600;
                color: var(--text-primary);
            }

            .attendance-date p {
                margin: 0.25rem 0 0 0;
                color: var(--text-secondary);
                font-size: 0.875rem;
            }

            .attendance-status {
                padding: 0.25rem 0.75rem;
                border-radius: 1rem;
                font-size: 0.75rem;
                font-weight: 600;
                text-transform: uppercase;
                display: flex;
                align-items: center;
                gap: 0.25rem;
            }

            .attendance-status.present {
                background: rgba(34, 197, 94, 0.1);
                color: #22C55E;
            }

            .attendance-status.late {
                background: rgba(245, 158, 11, 0.1);
                color: #F59E0B;
            }

            .attendance-status.absent {
                background: rgba(220, 38, 38, 0.1);
                color: #DC2626;
            }

            .attendance-status.early-leave {
                background: rgba(139, 92, 246, 0.1);
                color: #8B5CF6;
            }

            .attendance-status.overtime {
                background: rgba(6, 182, 212, 0.1);
                color: #06B6D4;
            }

            .attendance-details {
                margin-bottom: 1rem;
            }

            .time-details {
                display: flex;
                flex-direction: column;
                gap: 0.5rem;
                margin-bottom: 1rem;
            }

            .time-entry {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 0.25rem 0;
                border-bottom: 1px solid var(--border-light);
            }

            .time-entry.total-hours {
                border-bottom: none;
                font-weight: 600;
                color: var(--text-primary);
            }

            .time-label {
                color: var(--text-secondary);
                font-size: 0.875rem;
            }

            .time-value {
                color: var(--text-primary);
                font-size: 0.875rem;
                font-weight: 600;
            }

            .attendance-notes {
                background: var(--bg-secondary);
                padding: 0.75rem;
                border-radius: 0.5rem;
                border: 1px solid var(--border-light);
            }

            .attendance-notes p {
                margin: 0;
                color: var(--text-secondary);
                font-size: 0.875rem;
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }

            .attendance-actions {
                display: flex;
                gap: 0.5rem;
                justify-content: flex-end;
            }

            .action-btn {
                width: 35px;
                height: 35px;
                border: none;
                border-radius: 50%;
                background: var(--redcode-primary-light);
                color: var(--redcode-primary);
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                transition: all 0.3s ease;
                font-size: 0.875rem;
            }

            .action-btn:hover {
                background: var(--redcode-primary);
                color: white;
                transform: scale(1.1);
            }

            /* Modal Styles */
            .modal {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                z-index: 1000;
                backdrop-filter: blur(5px);
            }

            .modal.active {
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .modal-content {
                background: white;
                border-radius: 1rem;
                width: 90%;
                max-width: 500px;
                max-height: 90vh;
                overflow-y: auto;
                box-shadow: var(--shadow-xl);
                animation: modalSlideIn 0.3s ease;
            }

            @keyframes modalSlideIn {
                from {
                    transform: translateY(-50px);
                    opacity: 0;
                }

                to {
                    transform: translateY(0);
                    opacity: 1;
                }
            }

            .modal-header {
                padding: 1.5rem;
                border-bottom: 1px solid var(--border-light);
                display: flex;
                justify-content: space-between;
                align-items: center;
                background: var(--gradient-hero);
                background-size: 400% 400%;
                animation: gradientShift 18s ease infinite;
                color: white;
                border-radius: 1rem 1rem 0 0;
            }

            .modal-header h3 {
                margin: 0;
                font-size: 1.25rem;
                font-weight: 600;
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }

            .close-btn {
                background: none;
                border: none;
                color: white;
                font-size: 1.25rem;
                cursor: pointer;
                padding: 0.5rem;
                border-radius: 50%;
                transition: all 0.3s ease;
            }

            .close-btn:hover {
                background: rgba(255, 255, 255, 0.2);
            }

            .modal-body {
                padding: 1.5rem;
            }

            .clock-display {
                text-align: center;
                margin-bottom: 1.5rem;
                padding: 1rem;
                background: var(--bg-secondary);
                border-radius: 1rem;
                border: 1px solid var(--border-light);
            }

            .current-time {
                font-size: 2rem;
                font-weight: 700;
                color: var(--redcode-primary);
                margin-bottom: 0.5rem;
            }

            .current-date {
                font-size: 1rem;
                color: var(--text-secondary);
            }

            .form-group {
                margin-bottom: 1rem;
            }

            .form-group label {
                display: block;
                margin-bottom: 0.5rem;
                font-weight: 600;
                color: var(--text-primary);
            }

            .form-control {
                width: 100%;
                padding: 0.875rem 1rem;
                border: 2px solid var(--border-light);
                border-radius: 0.75rem;
                font-size: 1rem;
                background: var(--bg-primary);
                transition: all 0.3s ease;
            }

            .form-control:focus {
                outline: none;
                border-color: var(--redcode-primary);
                box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.1);
            }

            .modal-footer {
                padding: 1.5rem;
                border-top: 1px solid var(--border-light);
                display: flex;
                justify-content: flex-end;
                gap: 1rem;
                background: var(--bg-secondary);
                border-radius: 0 0 1rem 1rem;
            }

            .btn {
                padding: 0.75rem 1.5rem;
                border: none;
                border-radius: 0.5rem;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.3s ease;
                display: flex;
                align-items: center;
                gap: 0.5rem;
                text-decoration: none;
                font-size: 0.875rem;
            }

            .btn-primary {
                background: var(--gradient-primary);
                color: white;
                box-shadow: var(--shadow-sm);
            }

            .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: var(--shadow-md);
            }

            .btn-secondary {
                background: var(--gradient-secondary);
                color: white;
                box-shadow: var(--shadow-sm);
            }

            .btn-secondary:hover {
                background: var(--redcode-dark);
                transform: translateY(-2px);
                box-shadow: var(--shadow-md);
            }

            .attendance-summary {
                display: flex;
                flex-direction: column;
                gap: 1rem;
            }

            .summary-item {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 0.75rem;
                background: var(--bg-secondary);
                border-radius: 0.5rem;
                border: 1px solid var(--border-light);
            }

            .summary-label {
                font-weight: 600;
                color: var(--text-primary);
            }

            .summary-value {
                color: var(--text-secondary);
            }

            @media (max-width: 768px) {
                .header-content {
                    flex-direction: column;
                    gap: 1rem;
                }

                .attendance-stats {
                    grid-template-columns: 1fr;
                }

                .status-grid {
                    grid-template-columns: 1fr;
                }

                .search-section {
                    flex-direction: column;
                    gap: 1rem;
                }

                .filter-section {
                    width: 100%;
                }

                .filter-select {
                    flex: 1;
                }

                .attendance-grid {
                    grid-template-columns: 1fr;
                }
            }
        </style>

        <!-- Emergency Modal -->
        <div id="emergencyModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3><i class="fas fa-exclamation-triangle"></i> Emergency Report</h3>
                    <button class="modal-close" onclick="closeEmergencyModal()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="emergencyType">Emergency Type:</label>
                        <select id="emergencyType" class="form-control" required>
                            <option value="">Select Emergency Type</option>
                            <option value="Power Interruption">Power Interruption</option>
                            <option value="Network Issues">Network Issues</option>
                            <option value="System Maintenance">System Maintenance</option>
                            <option value="Emergency Situation">Emergency Situation</option>
                            <option value="Health Issue">Health Issue</option>
                            <option value="Fire Alarm">Fire Alarm</option>
                            <option value="Medical Emergency">Medical Emergency</option>
                            <option value="Security Issue">Security Issue</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="emergencyDescription">Description (Optional):</label>
                        <textarea id="emergencyDescription" class="form-control" rows="3"
                            placeholder="Provide additional details about the emergency..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" onclick="closeEmergencyModal()">Cancel</button>
                    <button class="btn btn-danger" onclick="submitEmergencyReport()">Submit Report</button>
                </div>
            </div>
        </div>

        <script>
            let currentViewMode = 'grid';
            let isOnBreak = false;
            let clockedIn = false;

            // Update current time
            function updateCurrentTime() {
                const now = new Date();
                const timeOptions = {
                    hour: '2-digit',
                    minute: '2-digit'
                };
                const dateOptions = {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                };

                document.getElementById('currentTime').textContent = now.toLocaleTimeString([], timeOptions);
                document.getElementById('currentDate').textContent = now.toLocaleDateString([], dateOptions);
            }

            // Update time every second
            setInterval(updateCurrentTime, 1000);
            updateCurrentTime();

            function toggleViewMode() {
                const grid = document.getElementById('attendanceGrid');
                const icon = document.getElementById('viewModeIcon');
                const text = document.getElementById('viewModeText');

                if (currentViewMode === 'grid') {
                    grid.classList.add('list-view');
                    icon.className = 'fas fa-columns';
                    text.textContent = 'Board View';
                    currentViewMode = 'list';
                } else {
                    grid.classList.remove('list-view');
                    icon.className = 'fas fa-th-large';
                    text.textContent = 'List View';
                    currentViewMode = 'grid';
                }
            }

            // function clockIn() {
            //     document.getElementById('clockModalIcon').className = 'fas fa-sign-in-alt';
            //     document.getElementById('clockModalTitle').textContent = 'Clock In';
            //     document.getElementById('confirmClockBtn').innerHTML = '<i class="fas fa-check"></i> Confirm Clock In';
            //     document.getElementById('clockModal').classList.add('active');
            // }

            // function clockOut() {
            //     document.getElementById('clockModalIcon').className = 'fas fa-sign-out-alt';
            //     document.getElementById('clockModalTitle').textContent = 'Clock Out';
            //     document.getElementById('confirmClockBtn').innerHTML = '<i class="fas fa-check"></i> Confirm Clock Out';
            //     document.getElementById('clockModal').classList.add('active');
            // }


            // function confirmClock() {
            //     const notes = document.getElementById('clockNotes').value;
            //     const isClockingIn = document.getElementById('clockModalTitle').textContent === 'Clock In';

            //     if (isClockingIn) {
            //         // Clock in logic
            //         document.getElementById('clockInBtn').style.display = 'none';
            //         document.getElementById('clockOutBtn').style.display = 'inline-flex';
            //         clockedIn = true;
            //         showMessage('Successfully clocked in!', 'success');
            //     } else {
            //         // Clock out logic
            //         document.getElementById('clockInBtn').style.display = 'inline-flex';
            //         document.getElementById('clockOutBtn').style.display = 'none';
            //         clockedIn = false;
            //         showMessage('Successfully clocked out!', 'success');
            //     }

            //     closeClockModal();
            // }

            function clockIn() {
                document.getElementById('clockModalIcon').className = 'fas fa-sign-in-alt';
                document.getElementById('clockModalTitle').textContent = 'Clock In';
                document.getElementById('confirmClockBtn').setAttribute('data-action', 'clock-in');
                document.getElementById('clockModal').classList.add('active');
            }

            function clockOut() {
                document.getElementById('clockModalIcon').className = 'fas fa-sign-out-alt';
                document.getElementById('clockModalTitle').textContent = 'Clock Out';
                document.getElementById('confirmClockBtn').setAttribute('data-action', 'clock-out');
                document.getElementById('clockModal').classList.add('active');
            }

            function closeClockModal() {
                document.getElementById('clockModal').classList.remove('active');
                document.getElementById('clockNotes').value = '';
            }

            async function confirmClock() {
                const notes = document.getElementById('clockNotes').value.trim();
                const action = document.getElementById('confirmClockBtn').getAttribute('data-action');

                // Get correct route based on action
                const url = action === 'clock-in' ?
                    "{{ route('employee.clockIn') }}" :
                    "{{ route('employee.clockOut') }}";

                try {
                    const response = await fetch(url, {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Accept": "application/json",
                            "Content-Type": "application/json",
                        },
                        body: JSON.stringify({
                            notes
                        })
                    });

                    const data = await response.json();
                    console.log(data);

                    if (data.success) {
                        console.log(action);

                        if (action === 'clock-in') {
                            // Hide Clock In and show Clock Out
                            document.getElementById('clockInBtn').style.display = 'none';
                            document.getElementById('clockOutBtn').style.display = 'inline-flex';
                            clockedIn = true;
                            showMessage('Successfully clocked in!', 'success');
                        } else {
                            // Hide Clock Out and show Clock In
                            document.getElementById('clockOutBtn').style.display = 'none';
                            document.getElementById('clockInBtn').style.display = 'inline-flex';
                            clockedIn = false;
                            showMessage('Successfully clocked out!', 'success');
                        }
                    } else {
                        showMessage(data.message || 'Something went wrong.', 'error');
                    }
                } catch (error) {
                    console.error(error);
                    showMessage('Server error, please try again later.', 'error');
                }

                closeClockModal();
            }

            function showMessage(message, type = 'info') {
                const alertBox = document.createElement('div');
                alertBox.className = `alert alert-${type}`;
                alertBox.textContent = message;
                document.body.appendChild(alertBox);

                setTimeout(() => alertBox.remove(), 3000);
            }

            document.addEventListener('DOMContentLoaded', () => {
                checkBreakStatus();
                updateEmergencyStatus();
            });

            function checkBreakStatus() {
                fetch("{{ route('employee.attendance.getBreakStatus') }}")
                    .then(res => res.json())
                    .then(data => {
                        if (data.success && data.on_break) {
                            isOnBreak = true;
                            document.querySelector('.btn-break').textContent = 'End Break';
                            document.querySelector('.btn-break').onclick = endBreak;
                            document.querySelector('.status-info p').textContent =
                                `On break since ${data.break_start_time}`;
                        } else {
                            isOnBreak = false;
                            document.querySelector('.btn-break').textContent = 'Start Break';
                            document.querySelector('.btn-break').onclick = startBreak;
                            document.querySelector('.status-info p').textContent = 'Not on break';
                        }
                    })
                    .catch(err => console.error('Error fetching break status:', err));
            }

            function startBreak() {
                fetch("{{ route('employee.attendance.startbreak') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        alert(data.message);
                        if (data.success) {
                            isOnBreak = true;
                            document.querySelector('.btn-break').textContent = 'End Break';
                            document.querySelector('.btn-break').onclick = endBreak;
                            document.querySelector('.status-info p').textContent =
                                `On break since ${data.data.break_start_time}`;
                        }
                    });
            }

            function endBreak() {
                fetch("{{ route('employee.attendance.endbreak') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        alert(data.message);
                        if (data.success) {
                            isOnBreak = false;
                            document.querySelector('.btn-break').textContent = 'Start Break';
                            document.querySelector('.btn-break').onclick = startBreak;
                            document.querySelector('.status-info p').textContent =
                                `Last break ended at ${data.data.break_end_time}`;
                        }
                    });
            }

            function startEmergencyBreak() {
                // Reset form
                document.getElementById('emergencyType').value = '';
                document.getElementById('emergencyDescription').value = '';

                // Show modal
                document.getElementById('emergencyModal').classList.add('active');
            }

            function endEmergencyBreak() {
                fetch('/employees/attendance/emergency/end', {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showMessage('✅ Emergency break ended successfully.', 'success');
                            updateEmergencyStatus(false);
                        } else {
                            showMessage(data.message, 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error ending emergency:', error);
                        showMessage('Failed to end emergency. Please try again.', 'error');
                    });
            }

            function reportEmergency() {
                // Reset form
                document.getElementById('emergencyType').value = '';
                document.getElementById('emergencyDescription').value = '';

                // Show modal
                document.getElementById('emergencyModal').classList.add('active');
            }

            //     function updateEmergencyStatus() {

            //         const emergencyStatusElement = document.querySelector('.status-card:last-child .status-info p');
            //         const emergencyActionsElement = document.querySelector('.emergency-actions');

            //         if (window.emergencyBreaks && window.emergencyBreaks.length > 0) {
            //             const activeEmergency = window.emergencyBreaks.find(eb => !eb.endTime);
            //             if (activeEmergency) {
            //                 emergencyStatusElement.textContent = `Emergency break active - ${activeEmergency.reason}`;
            //                 emergencyActionsElement.innerHTML = `
    // <button class="btn-sm btn-emergency" onclick="endEmergencyBreak()">End Emergency Break</button>
    // `;
            //             } else {
            //                 const totalEmergencies = window.emergencyBreaks.length;
            //                 emergencyStatusElement.textContent = `${totalEmergencies} emergency break(s) recorded`;
            //                 emergencyActionsElement.innerHTML = `
    // <button class="btn-sm btn-emergency" onclick="reportEmergency()">Report Emergency</button>
    // `;
            //             }
            //         } else {
            //             emergencyStatusElement.textContent = 'No emergency breaks';
            //             emergencyActionsElement.innerHTML = `
    // <button class="btn-sm btn-emergency" onclick="reportEmergency()">Report Emergency</button>
    // `;
            //         }
            //     }

            function updateEmergencyStatus() {
                const emergencyStatusElement = document.querySelector('.status-card:last-child .status-info p');
                const emergencyActionsElement = document.querySelector('.emergency-actions');

                fetch('/employees/attendance/emergency/status', {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                        },
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const emergency = data.emergency;
                            console.log(emergency);

                            if (emergency.is_active) {
                                emergencyStatusElement.textContent = `🚨 Emergency break active - ${emergency.reason}`;
                                emergencyActionsElement.innerHTML = `
                <button class="btn-sm btn-emergency" onclick="endEmergencyBreak()">End Emergency Break</button>
            `;
                            } else if (emergency && emergency.total > 0) {
                                emergencyStatusElement.textContent = `${emergency.total} emergency break(s) recorded`;
                                emergencyActionsElement.innerHTML = `
                <button class="btn-sm btn-emergency" onclick="reportEmergency()">Report Emergency</button>
            `;
                            } else {
                                emergencyStatusElement.textContent = 'No emergency breaks';
                                emergencyActionsElement.innerHTML = `
                <button class="btn-sm btn-emergency" onclick="reportEmergency()">Report Emergency</button>
            `;
                            }
                        } else {
                            emergencyStatusElement.textContent = 'Error loading emergency status';
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching emergency status:', error);
                        emergencyStatusElement.textContent = 'Unable to load emergency status';
                        emergencyActionsElement.innerHTML = `
        <button class="btn-sm btn-emergency" onclick="reportEmergency()">Report Emergency</button>
    `;
                    });
            }

            function closeEmergencyModal() {
                document.getElementById('emergencyModal').classList.remove('active');
            }

            function submitEmergencyReport() {
                const emergencyType = document.getElementById('emergencyType').value;
                const emergencyDescription = document.getElementById('emergencyDescription').value;

                if (!emergencyType) {
                    showMessage('⚠️ Please select an emergency type.', 'error');
                    return;
                }

                // Prepare data for API
                const data = {
                    emergency_type: emergencyType,
                    emergency_description: emergencyDescription
                };

                // Send to backend
                fetch('/employees/attendance/emergency/start', {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify(data)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showMessage(`🚨 Emergency started: ${emergencyType}`, 'warning');
                            updateEmergencyStatus(true, emergencyType);
                        } else {
                            showMessage(data.message, 'error');
                        }
                        closeEmergencyModal();
                    })
                    .catch(error => {
                        console.error('Error reporting emergency:', error);
                        showMessage('Failed to start emergency. Please try again.', 'error');
                    });
            }

            // Load stored emergency data on page load
            document.addEventListener('DOMContentLoaded', function() {
                // Load stored emergency data
                const storedReports = localStorage.getItem('emergencyReports');
                const storedBreaks = localStorage.getItem('emergencyBreaks');

                if (storedReports) {
                    window.emergencyReports = JSON.parse(storedReports);
                }
                if (storedBreaks) {
                    window.emergencyBreaks = JSON.parse(storedBreaks);
                    updateEmergencyStatus();
                }
            });

            function viewAttendanceDetails(date) {
                const summaryData = getAttendanceDetails(date);
                const summaryHtml = `
    <div class="summary-item">
        <span class="summary-label">Date:</span>
        <span class="summary-value">${summaryData.date}</span>
    </div>
    <div class="summary-item">
        <span class="summary-label">Status:</span>
        <span class="summary-value">${summaryData.status}</span>
    </div>
    <div class="summary-item">
        <span class="summary-label">Clock In:</span>
        <span class="summary-value">${summaryData.clockIn}</span>
    </div>
    <div class="summary-item">
        <span class="summary-label">Clock Out:</span>
        <span class="summary-value">${summaryData.clockOut}</span>
    </div>
    <div class="summary-item">
        <span class="summary-label">Break Time:</span>
        <span class="summary-value">${summaryData.breakTime}</span>
    </div>
    <div class="summary-item">
        <span class="summary-label">Total Hours:</span>
        <span class="summary-value">${summaryData.totalHours}</span>
    </div>
    <div class="summary-item">
        <span class="summary-label">Notes:</span>
        <span class="summary-value">${summaryData.notes}</span>
    </div>
    `;

                document.getElementById('attendanceSummary').innerHTML = summaryHtml;
                document.getElementById('attendanceModal').classList.add('active');
            }

            function closeAttendanceModal() {
                document.getElementById('attendanceModal').classList.remove('active');
            }

            function editAttendance(date) {
                showMessage(`Opening attendance for ${date} for editing...`, 'info');
            }

            function downloadReport(date) {
                showMessage(`Downloading attendance report for ${date}...`, 'info');
            }

            function downloadDetailedReport() {
                showMessage('Downloading detailed attendance report...', 'info');
            }

            function getAttendanceDetails(date) {
                const attendanceData = {
                    '2024-01-15': {
                        date: 'January 15, 2024',
                        status: 'Present',
                        clockIn: '9:00 AM',
                        clockOut: '6:00 PM',
                        breakTime: '1h 00m',
                        totalHours: '8h 00m',
                        notes: 'Regular working day'
                    },
                    '2024-01-14': {
                        date: 'January 14, 2024',
                        status: 'Late',
                        clockIn: '9:30 AM',
                        clockOut: '6:30 PM',
                        breakTime: '1h 00m',
                        totalHours: '8h 00m',
                        notes: '30 minutes late - Traffic delay'
                    },
                    '2024-01-13': {
                        date: 'January 13, 2024',
                        status: 'Absent',
                        clockIn: '-',
                        clockOut: '-',
                        breakTime: '-',
                        totalHours: '0h 00m',
                        notes: 'Sick leave approved'
                    },
                    '2024-01-12': {
                        date: 'January 12, 2024',
                        status: 'Early Leave',
                        clockIn: '9:00 AM',
                        clockOut: '4:00 PM',
                        breakTime: '1h 00m',
                        totalHours: '6h 00m',
                        notes: 'Early leave approved - Personal appointment'
                    },
                    '2024-01-11': {
                        date: 'January 11, 2024',
                        status: 'Present',
                        clockIn: '8:45 AM',
                        clockOut: '5:45 PM',
                        breakTime: '1h 00m',
                        totalHours: '8h 00m',
                        notes: 'Early arrival - Good performance'
                    },
                    '2024-01-10': {
                        date: 'January 10, 2024',
                        status: 'Overtime',
                        clockIn: '9:00 AM',
                        clockOut: '8:00 PM',
                        breakTime: '1h 00m',
                        totalHours: '10h 00m',
                        notes: 'Overtime approved - Project deadline'
                    }
                };

                return attendanceData[date] || {
                    date: date,
                    status: 'Unknown',
                    clockIn: '-',
                    clockOut: '-',
                    breakTime: '-',
                    totalHours: '-',
                    notes: 'No data available'
                };
            }

            function showMessage(message, type) {
                const messageDiv = document.createElement('div');
                messageDiv.className = `message message-${type}`;
                messageDiv.innerHTML = `
    <i class="fas fa-${type === 'success' ? 'check-circle' : 'info-circle'}"></i>
    ${message}
    `;
                messageDiv.style.cssText = `
    position: fixed;
    top: 20px;
    right: 20px;
    background: ${type === 'success' ? 'var(--redcode-green)' : 'var(--redcode-blue)'};
    color: white;
    padding: 1rem 1.5rem;
    border-radius: 0.5rem;
    box-shadow: var(--shadow-lg);
    z-index: 1001;
    animation: slideIn 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    `;

                document.body.appendChild(messageDiv);

                setTimeout(() => {
                    messageDiv.remove();
                }, 3000);
            }

            // Search functionality
            document.getElementById('searchInput').addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                filterAttendance();
            });

            // Filter functionality
            document.getElementById('monthFilter').addEventListener('change', filterAttendance);
            document.getElementById('statusFilter').addEventListener('change', filterAttendance);

            function filterAttendance() {
                const searchTerm = document.getElementById('searchInput').value.toLowerCase();
                const monthFilter = document.getElementById('monthFilter').value;
                const statusFilter = document.getElementById('statusFilter').value;
                const cards = document.querySelectorAll('.attendance-card');

                cards.forEach(card => {
                    const date = card.querySelector('h4').textContent.toLowerCase();
                    const month = card.dataset.month;
                    const status = card.dataset.status;

                    const matchesSearch = date.includes(searchTerm);
                    const matchesMonth = !monthFilter || month === monthFilter;
                    const matchesStatus = !statusFilter || status === statusFilter;

                    if (matchesSearch && matchesMonth && matchesStatus) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            }

            // Close modal when clicking outside
            document.getElementById('clockModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeClockModal();
                }
            });

            document.getElementById('attendanceModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeAttendanceModal();
                }
            });

            // Initialize clock buttons based on current status
            if (clockedIn) {
                document.getElementById('clockInBtn').style.display = 'none';
                document.getElementById('clockOutBtn').style.display = 'inline-flex';
            }

            // Add slide-in animation keyframes
            const style = document.createElement('style');
            style.textContent = `
    @keyframes slideIn {
    from {
    transform: translateX(100%);
    opacity: 0;
    }
    to {
    transform: translateX(0);
    opacity: 1;
    }
    }
    `;
            document.head.appendChild(style);
        </script>
    @endsection
