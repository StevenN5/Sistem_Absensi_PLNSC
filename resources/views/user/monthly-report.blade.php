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

        .action-card {
            background: var(--surface);
            border-radius: 20px;
            padding: 20px;
            border: 1px solid var(--border);
            box-shadow: 0 14px 30px rgba(15, 23, 42, 0.06);
            margin-top: 22px;
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
        }
    </style>
@endsection
@php
    if (!function_exists('format_filesize')) {
        function format_filesize($bytes)
        {
            $bytes = (int) $bytes;
            if ($bytes <= 0) {
                return '-';
            }
            $units = ['B', 'KB', 'MB', 'GB'];
            $i = 0;
            while ($bytes >= 1024 && $i < count($units) - 1) {
                $bytes /= 1024;
                $i++;
            }
            return number_format($bytes, 2) . ' ' . $units[$i];
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
                        <a href="{{ route('home') }}">Kehadiran</a>
                        <a class="active" href="{{ route('user.monthly-report') }}">Monthly Report</a>
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
                    <div class="hero-title">Monthly Report</div>
                    <div class="hero-subtitle">Unggah dokumen monthly report per bulan.</div>
                    @if ($errors->any())
                        <div class="alert alert-danger mt-3">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
                <div>
                    <div class="text-muted">Maksimal 5MB, format PDF.</div>
                </div>
            </div>

            <div class="action-card">
                <div class="action-header">
                    <div class="action-title">Upload Monthly Report</div>
                    <span class="action-status {{ $reports->isEmpty() ? 'pending' : 'done' }}">
                        {{ $reports->isEmpty() ? 'Belum' : 'Tercatat' }}
                    </span>
                </div>
                <form method="POST" action="{{ route('monthly-report.store') }}" class="action-form" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="report_month">Bulan Laporan (YYYY-MM)</label>
                        <input type="month" class="form-control" id="report_month" name="report_month" required>
                    </div>
                    <div class="form-group">
                        <label for="monthly_report">Upload Monthly Report (PDF)</label>
                        <input type="file" class="form-control" id="monthly_report" name="monthly_report" accept=".pdf" required>
                    </div>
                    <button class="btn btn-success action-button" type="submit">
                        Upload Dokumen
                    </button>
                </form>
            </div>

            <div class="logs-card">
                <div class="logs-title">Histori Monthly Report</div>
                @if ($reports->isEmpty())
                    <div class="text-muted">Belum ada histori.</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-striped mb-0 logs-table">
                            <thead>
                                <tr>
                                    <th>Bulan</th>
                                    <th>Tanggal</th>
                                    <th>Dokumen</th>
                                    <th>Ukuran</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reports as $report)
                                    <tr>
                                        <td>{{ $report->report_month }}</td>
                                        <td>{{ optional($report->created_at)->format('Y-m-d H:i') }}</td>
                                        <td>
                                            <a href="{{ route('monthly-report.download', $report->id) }}">
                                                {{ $report->file_name }}
                                            </a>
                                        </td>
                                        <td>{{ format_filesize($report->file_size) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
