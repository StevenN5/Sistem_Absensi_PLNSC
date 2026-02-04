@extends('layouts.master-blank')

@section('content')
@section('css')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Fraunces:wght@500;700&family=Manrope:wght@400;500;600;700&display=swap');

        :root {
            --ink: #14121a;
            --muted: #6f7285;
            --primary: #1f6feb;
            --primary-soft: rgba(31, 111, 235, 0.12);
            --accent: #f4b942;
            --accent-soft: rgba(244, 185, 66, 0.18);
            --danger: #e5484d;
            --surface: #ffffff;
            --surface-alt: #f7f5fb;
            --border: #e6e3ef;
        }

        body {
            background: radial-gradient(circle at 10% 10%, #fff4d8 0%, transparent 40%),
                radial-gradient(circle at 80% 0%, #dce8ff 0%, transparent 45%),
                linear-gradient(180deg, #f3f4fb 0%, #f9f7ff 100%);
            color: var(--ink);
            font-family: "Manrope", system-ui, -apple-system, sans-serif;
        }

        .attendance-page {
            min-height: 100vh;
            padding: 32px 16px 48px;
            position: relative;
            overflow: hidden;
        }

        .attendance-page::before,
        .attendance-page::after {
            content: "";
            position: absolute;
            width: 340px;
            height: 340px;
            border-radius: 48% 52% 58% 42%;
            background: rgba(31, 111, 235, 0.08);
            filter: blur(0px);
            z-index: 0;
        }

        .attendance-page::before {
            top: -120px;
            right: -80px;
            transform: rotate(12deg);
        }

        .attendance-page::after {
            bottom: -140px;
            left: -90px;
            background: rgba(244, 185, 66, 0.16);
            transform: rotate(-8deg);
        }

        .attendance-shell {
            max-width: 1120px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .attendance-topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 24px;
        }

        .attendance-greeting {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .attendance-greeting span {
            color: var(--muted);
            font-size: 13px;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .attendance-greeting h1 {
            font-family: "Fraunces", "Manrope", serif;
            font-size: 28px;
            margin: 0;
        }

        .attendance-logout {
            border: 1px solid var(--border);
            background: var(--surface);
            color: var(--ink);
            border-radius: 999px;
            padding: 8px 18px;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .attendance-logout:hover {
            border-color: var(--primary);
            color: var(--primary);
            box-shadow: 0 8px 20px rgba(31, 111, 235, 0.12);
        }

        .user-nav {
            display: flex;
            gap: 10px;
            margin-top: 12px;
            flex-wrap: wrap;
        }

        .user-nav a {
            padding: 6px 14px;
            border-radius: 999px;
            font-weight: 600;
            font-size: 13px;
            text-decoration: none;
            border: 1px solid var(--border);
            background: var(--surface);
            color: var(--ink);
        }

        .user-nav a.active {
            border-color: var(--primary);
            color: var(--primary);
            background: var(--primary-soft);
        }

        .hero-card {
            background: var(--surface);
            border-radius: 24px;
            padding: 28px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 24px;
            box-shadow: 0 20px 50px rgba(15, 23, 42, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.6);
        }

        .hero-title {
            font-family: "Fraunces", "Manrope", serif;
            font-size: 24px;
            margin-bottom: 6px;
        }

        .hero-subtitle {
            color: var(--muted);
            margin-bottom: 18px;
        }

        .clock-panel {
            background: var(--surface-alt);
            border-radius: 18px;
            padding: 18px;
            display: flex;
            flex-direction: column;
            gap: 6px;
            border: 1px dashed var(--border);
        }

        .clock-time {
            font-size: 34px;
            font-weight: 700;
            letter-spacing: 0.08em;
        }

        .status-chips {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 8px;
        }

        .chip {
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            background: var(--primary-soft);
            color: var(--primary);
        }

        .chip.accent {
            background: var(--accent-soft);
            color: #8a5b00;
        }

        .actions-grid {
            margin-top: 24px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 18px;
        }

        .action-card {
            background: var(--surface);
            border-radius: 20px;
            padding: 20px;
            border: 1px solid var(--border);
            box-shadow: 0 14px 30px rgba(15, 23, 42, 0.06);
        }

        .action-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
        }

        .action-title {
            font-weight: 700;
            font-size: 16px;
        }

        .action-status {
            font-size: 12px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 999px;
        }

        .action-status.done {
            background: rgba(16, 185, 129, 0.16);
            color: #0f766e;
        }

        .action-status.pending {
            background: rgba(239, 68, 68, 0.12);
            color: var(--danger);
        }

        .action-form label {
            font-size: 12px;
            font-weight: 600;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .action-form .form-control {
            border-radius: 12px;
            border: 1px solid var(--border);
            background: #fff;
        }

        .action-button {
            width: 100%;
            border-radius: 12px;
            font-weight: 700;
            padding: 10px 14px;
        }

        .note-status {
            font-size: 13px;
            margin-top: 10px;
        }

        .logs-card {
            margin-top: 28px;
            background: var(--surface);
            border-radius: 22px;
            padding: 22px;
            border: 1px solid var(--border);
            box-shadow: 0 16px 32px rgba(15, 23, 42, 0.06);
        }

        .logs-title {
            font-weight: 700;
            font-size: 18px;
            margin-bottom: 14px;
        }

        .logs-table thead th {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--muted);
            border-top: none;
        }

        .logs-table tbody td {
            vertical-align: middle;
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .attendance-topbar {
                flex-direction: column;
                align-items: flex-start;
            }

            .clock-time {
                font-size: 28px;
            }
        }
    </style>
@endsection
@php
    if (!function_exists('format_duration')) {
        function format_duration($seconds)
        {
            $seconds = (int) $seconds;
            if ($seconds <= 0) {
                return '0 detik';
            }
            $hours = (int) floor($seconds / 3600);
            $minutes = (int) floor(($seconds % 3600) / 60);
            $secs = (int) ($seconds % 60);
            $parts = [];
            if ($hours > 0) {
                $parts[] = $hours . ' jam';
            }
            if ($minutes > 0) {
                $parts[] = $minutes . ' menit';
            }
            if ($secs > 0) {
                $parts[] = $secs . ' detik';
            }
            return implode(' ', $parts);
        }
    }
@endphp
    <div class="attendance-page">
        <div class="attendance-shell">
            <div class="attendance-topbar">
                <div class="attendance-greeting">
                    <span>Selamat datang kembali</span>
                    <h1>{{ auth()->user()->name }}</h1>
                    <div class="user-nav">
                        <a class="active" href="{{ route('home') }}">Kehadiran</a>
                        <a href="{{ route('user.monthly-report') }}">Monthly Report</a>
                        <a href="{{ route('user.final-report') }}">Final Report</a>
                        <a href="{{ route('user.profile') }}">Profil</a>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="attendance-logout" type="submit">Keluar</button>
                </form>
            </div>

            <div class="hero-card">
                <div>
                    <div class="hero-title">{{ __('global.attendance') }}</div>
                    <div class="hero-subtitle">{{ __('global.record_attendance') }}</div>
                    <div class="status-chips">
                        <span class="chip">Masuk 08:00</span>
                        <span class="chip accent">Pulang 16:30</span>
                    </div>
                </div>
                <div class="clock-panel">
                    <div class="clock-time" id="clock">--:--:--</div>
                    <div class="text-muted">{{ __('global.current_time') }}</div>
                    @if ($lastAttendance)
                        <div class="small text-muted">
                            {{ __('global.last_time_in') }}: {{ $lastAttendance->attendance_date }} {{ $lastAttendance->attendance_time }}
                        </div>
                    @endif
                    @if ($lastLeave)
                        <div class="small text-muted">
                            {{ __('global.last_time_out') }}: {{ $lastLeave->leave_date }} {{ $lastLeave->leave_time }}
                        </div>
                    @endif
                </div>
            </div>

            <div class="actions-grid">
                <div class="action-card">
                    <div class="action-header">
                        <div class="action-title">Masuk</div>
                        <span class="action-status {{ $hasAttendance ? 'done' : 'pending' }}">
                            {{ $hasAttendance ? 'Tercatat' : 'Belum' }}
                        </span>
                    </div>
                    @if ($hasAttendance)
                        <div class="alert alert-info mb-2">
                            {{ __('global.time_in_recorded') }}
                        </div>
                        @php
                            $timeIn = '08:00:00';
                            $attendanceTime = date('H:i:s', strtotime($lastAttendance->attendance_time ?? '00:00:00'));
                            $scheduleTs = strtotime(($lastAttendance->attendance_date ?? date('Y-m-d')) . ' ' . $timeIn);
                            $attendanceTs = strtotime(($lastAttendance->attendance_date ?? date('Y-m-d')) . ' ' . $attendanceTime);
                            $diffSeconds = abs($attendanceTs - $scheduleTs);
                            $isLate = $attendanceTs > $scheduleTs;
                        @endphp
                        @if ($isLate)
                            <div class="note-status text-danger">Terlambat {{ format_duration($diffSeconds) }}</div>
                        @else
                            <div class="note-status text-success">
                                @if ($diffSeconds > 0)
                                    Lebih cepat {{ format_duration($diffSeconds) }}
                                @else
                                    Tepat waktu
                                @endif
                            </div>
                        @endif
                    @else
                        <form method="POST" action="{{ route('home.timein.store') }}" class="action-form">
                            @csrf
                            <div class="form-group">
                                <label for="note_time_in">{{ __('global.note_optional') }}</label>
                                <input type="text" class="form-control" id="note_time_in" name="note"
                                    placeholder="Contoh: macet / izin">
                            </div>
                            <button class="btn btn-primary action-button" type="submit">
                                {{ __('global.time_in') }}
                            </button>
                        </form>
                        <div id="realtime-timein" class="note-status"></div>
                    @endif
                </div>

                <div class="action-card">
                    <div class="action-header">
                        <div class="action-title">Pulang</div>
                        <span class="action-status {{ $hasLeave ? 'done' : 'pending' }}">
                            {{ $hasLeave ? 'Tercatat' : 'Belum' }}
                        </span>
                    </div>
                    @if ($hasLeave)
                        <div class="alert alert-info mb-2">
                            {{ __('global.time_out_recorded') }}
                        </div>
                        @php
                            $timeOut = '16:30:00';
                            $leaveTime = date('H:i:s', strtotime($lastLeave->leave_time ?? '00:00:00'));
                            $scheduleTs = strtotime(($lastLeave->leave_date ?? date('Y-m-d')) . ' ' . $timeOut);
                            $leaveTs = strtotime(($lastLeave->leave_date ?? date('Y-m-d')) . ' ' . $leaveTime);
                            $diffSeconds = abs($leaveTs - $scheduleTs);
                            $isEarly = $leaveTs < $scheduleTs;
                        @endphp
                        @if ($isEarly)
                            <div class="note-status text-danger">Pulang lebih cepat {{ format_duration($diffSeconds) }}</div>
                        @else
                            <div class="note-status text-success">
                                @if ($diffSeconds > 0)
                                    Pulang lebih lama {{ format_duration($diffSeconds) }}
                                @else
                                    Tepat waktu
                                @endif
                            </div>
                        @endif
                    @else
                        <form method="POST" action="{{ route('home.timeout.store') }}" class="action-form">
                            @csrf
                            <div class="form-group">
                                <label for="note_time_out">{{ __('global.note_optional') }}</label>
                                <input type="text" class="form-control" id="note_time_out" name="note"
                                    placeholder="Contoh: izin / keperluan mendadak">
                            </div>
                            <button class="btn btn-danger action-button" type="submit">
                                {{ __('global.time_out') }}
                            </button>
                        </form>
                        <div id="realtime-timeout" class="note-status"></div>
                    @endif
                </div>

            </div>

            <div class="logs-card">
                <div class="logs-title">{{ __('global.my_attendance_logs') }}</div>
                @if ($logs->isEmpty())
                    <div class="text-muted">{{ __('global.no_logs') }}</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-striped mb-0 logs-table">
                            <thead>
                                <tr>
                                    <th>{{ __('global.date') }}</th>
                                    <th>{{ __('global.type') }}</th>
                                    <th>{{ __('global.note') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($groupedLogs as $weekKey => $weekLogs)
                                    @php
                                        $weekNumber = (int) explode('-', $weekKey)[1];
                                        $weekYear = (int) explode('-', $weekKey)[0];
                                    @endphp
                                    <tr class="table-active">
                                        <td colspan="3"><strong>Minggu ke-{{ $weekNumber }}</strong> ({{ $weekYear }})</td>
                                    </tr>
                                    @foreach ($weekLogs as $log)
                                        <tr>
                                            <td>{{ $log['datetime'] }}</td>
                                            <td>{{ $log['type'] }}</td>
                                            <td>{{ $log['note'] ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
    (function () {
        var clock = document.getElementById('clock');
        var realtimeTimeIn = document.getElementById('realtime-timein');
        var realtimeTimeOut = document.getElementById('realtime-timeout');

        function formatDuration(seconds) {
            seconds = Math.max(0, Math.floor(seconds));
            var hours = Math.floor(seconds / 3600);
            var minutes = Math.floor((seconds % 3600) / 60);
            var secs = seconds % 60;
            var parts = [];
            if (hours > 0) {
                parts.push(hours + ' jam');
            }
            if (minutes > 0) {
                parts.push(minutes + ' menit');
            }
            if (secs > 0) {
                parts.push(secs + ' detik');
            }
            return parts.length ? parts.join(' ') : '0 detik';
        }

        function updateRealtimeStatus(now) {
            if (realtimeTimeIn) {
                var startIn = new Date(now);
                startIn.setHours(8, 0, 0, 0);
                var diffIn = Math.abs(now - startIn) / 1000;
                if (now > startIn) {
                    realtimeTimeIn.textContent = 'Terlambat ' + formatDuration(diffIn);
                    realtimeTimeIn.className = 'text-danger small mt-2';
                } else if (now.getTime() === startIn.getTime()) {
                    realtimeTimeIn.textContent = 'Tepat waktu';
                    realtimeTimeIn.className = 'text-success small mt-2';
                } else {
                    realtimeTimeIn.textContent = 'Masih bisa on time (' + formatDuration(diffIn) + ' lagi)';
                    realtimeTimeIn.className = 'text-success small mt-2';
                }
            }

            if (realtimeTimeOut) {
                var startOut = new Date(now);
                startOut.setHours(16, 30, 0, 0);
                var diffOut = Math.abs(now - startOut) / 1000;
                if (now >= startOut) {
                    realtimeTimeOut.textContent = 'Boleh pulang (' + formatDuration(diffOut) + ' lewat)';
                    realtimeTimeOut.className = 'text-success small mt-2';
                } else {
                    realtimeTimeOut.textContent = 'Belum waktu pulang (' + formatDuration(diffOut) + ' lagi)';
                    realtimeTimeOut.className = 'text-danger small mt-2';
                }
            }
        }

        function tick() {
            var now = new Date();
            var h = String(now.getHours()).padStart(2, '0');
            var m = String(now.getMinutes()).padStart(2, '0');
            var s = String(now.getSeconds()).padStart(2, '0');
            clock.textContent = h + ':' + m + ':' + s;
            updateRealtimeStatus(now);
        }
        tick();
        setInterval(tick, 1000);
    })();
</script>
@endsection
