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

        @media (max-width: 768px) {
            .attendance-topbar {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
@endsection
@php
    $demoMode = $demoMode ?? false;
    $displayUser = $demoMode ? $user : auth()->user();
@endphp
    <div class="attendance-page">
        <div class="attendance-shell">
            <div class="attendance-topbar">
                <div class="attendance-greeting">
                    <span>Selamat datang kembali</span>
                    <h1>{{ $displayUser->name }}</h1>
                    <div class="user-nav">
                        <a href="{{ route('home') }}">Kehadiran</a>
                        <a href="{{ route('user.monthly-report') }}">Monthly Report</a>
                        <a href="{{ route('user.final-report') }}">Final Report</a>
                        <a class="active" href="{{ route('user.profile') }}">Profil</a>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="attendance-logout" type="submit">Keluar</button>
                </form>
            </div>

            <div class="action-card">
                <div class="action-header">
                    <div class="action-title">Kelola Profil</div>
                </div>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form method="POST" action="{{ route('user.profile.update') }}" class="action-form">
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="name">{{ __('global.name') }}</label>
                            <input id="name" type="text" class="form-control" name="name"
                                value="{{ old('name', $displayUser->name) }}" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="phone_number">{{ __('global.phone_number') }}</label>
                            <input id="phone_number" type="text" class="form-control" name="phone_number"
                                value="{{ old('phone_number', $displayUser->phone_number) }}" required>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="address">{{ __('global.address') }}</label>
                            <input id="address" type="text" class="form-control" name="address"
                                value="{{ old('address', $displayUser->address) }}" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="birth_date">{{ __('global.birth_date') }}</label>
                            <input id="birth_date" type="date" class="form-control" name="birth_date"
                                value="{{ old('birth_date', $displayUser->birth_date) }}" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="institution">{{ __('global.institution') }}</label>
                            <input id="institution" type="text" class="form-control" name="institution"
                                value="{{ old('institution', $displayUser->institution) }}" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="position">{{ __('global.position') }}</label>
                            <input id="position" type="text" class="form-control" name="position"
                                value="{{ old('position', optional($employee)->position) }}" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="major">{{ __('global.major') }}</label>
                            <input id="major" type="text" class="form-control" name="major"
                                value="{{ old('major', optional($employee)->major) }}" required>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="email">{{ __('global.email') }}</label>
                            <input id="email" type="email" class="form-control" name="email"
                                value="{{ old('email', $displayUser->email) }}" required>
                        </div>
                    </div>
                    <button class="btn btn-primary action-button" type="submit">Simpan Profil</button>
                </form>
            </div>
        </div>
    </div>
@endsection
