@extends('layouts.master')

@section('css')
<!--Chartist Chart CSS -->
<link rel="stylesheet" href="{{ URL::asset('plugins/chartist/css/chartist.min.css') }}">
<style>
    @import url('https://fonts.googleapis.com/css2?family=Fraunces:wght@600;700&family=Manrope:wght@400;500;600;700&display=swap');

    :root {
        --ink: #14121a;
        --muted: #6f7285;
        --primary: #1f6feb;
        --primary-soft: rgba(31, 111, 235, 0.12);
        --accent: #f4b942;
        --accent-soft: rgba(244, 185, 66, 0.2);
        --success: #18a36a;
        --danger: #e5484d;
        --surface: #ffffff;
        --surface-alt: #f7f5fb;
        --border: #e6e3ef;
    }

    .admin-dashboard {
        font-family: "Manrope", system-ui, -apple-system, sans-serif;
        color: var(--ink);
    }

    .dash-hero {
        background: linear-gradient(120deg, #f8f6ff 0%, #eef4ff 50%, #fff7e1 100%);
        border: 1px solid rgba(255, 255, 255, 0.7);
        border-radius: 20px;
        padding: 24px;
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
        margin-bottom: 24px;
    }

    .dash-hero h2 {
        font-family: "Fraunces", "Manrope", serif;
        font-size: 26px;
        margin: 0 0 6px;
    }

    .dash-hero p {
        margin: 0;
        color: var(--muted);
    }

    .hero-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .hero-actions a {
        border-radius: 999px;
        padding: 8px 16px;
        font-weight: 600;
        border: 1px solid var(--border);
        background: var(--surface);
        color: var(--ink);
    }

    .hero-actions a.primary {
        background: var(--primary);
        color: #fff;
        border-color: var(--primary);
    }

    .stat-grid .card {
        border-radius: 18px;
        border: 1px solid var(--border);
        box-shadow: 0 16px 32px rgba(15, 23, 42, 0.06);
        overflow: hidden;
    }

    .stat-card {
        padding: 20px;
        background: var(--surface);
        position: relative;
    }

    .stat-card .stat-icon {
        width: 42px;
        height: 42px;
        border-radius: 14px;
        display: grid;
        place-items: center;
        font-size: 18px;
        margin-bottom: 14px;
        background: var(--primary-soft);
        color: var(--primary);
    }

    .stat-card h4 {
        font-size: 24px;
        margin: 4px 0 6px;
        font-weight: 700;
    }

    .stat-card small {
        text-transform: uppercase;
        letter-spacing: 0.08em;
        font-size: 11px;
        color: var(--muted);
    }

    .stat-meta {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 12px;
        color: var(--muted);
        font-size: 13px;
    }

    .dash-card {
        border-radius: 20px;
        border: 1px solid var(--border);
        box-shadow: 0 16px 32px rgba(15, 23, 42, 0.06);
    }

    .dash-card .card-body {
        padding: 22px;
    }

    .dashboard-section-title {
        font-weight: 700;
        color: var(--ink);
        font-size: 16px;
    }

    .dashboard-chart {
        min-height: 260px;
    }

    .dashboard-chart .ct-label {
        font-size: 11px;
        color: #7f8aa3;
    }

    .dashboard-chart .ct-label.ct-horizontal {
        transform: translateY(6px);
    }

    .dashboard-chart .ct-grids .ct-grid {
        stroke: rgba(15, 23, 42, 0.06);
    }

    .dashboard-chart .ct-series-a .ct-line {
        stroke-width: 3px;
    }

    .dashboard-chart .ct-series-a .ct-area {
        fill-opacity: 0.12;
    }

    .stat-summary {
        background: var(--surface-alt);
        border-radius: 16px;
        padding: 14px;
        border: 1px dashed var(--border);
        text-align: center;
    }

    .stat-summary h4 {
        margin: 6px 0 4px;
    }

    .status-list .wid-peity {
        padding-bottom: 10px;
        border-bottom: 1px dashed var(--border);
        margin-bottom: 12px;
    }

    .status-list .wid-peity:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }
</style>
@endsection

@section('breadcrumb')
<div class="col-sm-6 text-left" >
     <h4 class="page-title">{{ __('global.dashboard') }}</h4>
     <ol class="breadcrumb">
         <li class="breadcrumb-item active">{{ __('global.welcome') }}</li>
     </ol>
</div>
@endsection

@section('content')
<div class="admin-dashboard">
    <div class="dash-hero">
        <div>
            <h2>{{ __('global.dashboard') }}</h2>
            <p>{{ __('global.monthly_report') }} & {{ __('global.attendance_logs') }}</p>
        </div>
        <div class="hero-actions">
            <a class="primary" href="/attendance">{{ __('global.attendance') }}</a>
            <a href="/employees">{{ __('global.employees') }}</a>
            <a href="/sheet-report">{{ __('global.sheet_report') }}</a>
        </div>
    </div>

    <div class="row stat-grid">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card">
                <div class="stat-card">
                    <div class="stat-icon"><span class="ti-id-badge"></span></div>
                    <small>{{ __('global.employees') }}</small>
                    <h4>{{ $data[0] }}</h4>
                    <div class="stat-meta">
                        <span>{{ __('global.more_info') }}</span>
                        <a href="/employees"><i class="mdi mdi-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card">
                <div class="stat-card">
                    <div class="stat-icon"><i class="ti-alarm-clock"></i></div>
                    <small>{{ __('global.on_time') }} %</small>
                    <h4>{{ $data[3] }}%</h4>
                    <div class="stat-meta">
                        <span>{{ __('global.more_info') }}</span>
                        <a href="/attendance"><i class="mdi mdi-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card">
                <div class="stat-card">
                    <div class="stat-icon"><i class="ti-check-box"></i></div>
                    <small>{{ __('global.on_time') }} {{ __('global.today') }}</small>
                    <h4>{{ $data[1] }}</h4>
                    <div class="stat-meta">
                        <span>{{ __('global.more_info') }}</span>
                        <a href="/attendance"><i class="mdi mdi-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card">
                <div class="stat-card">
                    <div class="stat-icon" style="background: rgba(229, 72, 77, 0.12); color: var(--danger);">
                        <i class="ti-alert"></i>
                    </div>
                    <small>{{ __('global.late') }} {{ __('global.today') }}</small>
                    <h4>{{ $data[2] }}</h4>
                    <div class="stat-meta">
                        <span>{{ __('global.more_info') }}</span>
                        <a href="/attendance"><i class="mdi mdi-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-9 mb-4">
            <div class="card dash-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="dashboard-section-title mb-0">{{ __('global.monthly_report') }}</h4>
                        <span class="text-muted">{{ __('global.this_month') }}</span>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-lg-7">
                            <div id="chart-with-area" class="ct-chart earning ct-golden-section dashboard-chart"></div>
                        </div>
                        <div class="col-lg-5">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="stat-summary">
                                        <p class="text-muted mb-2">{{ __('global.this_month') }}</p>
                                        <h4>{{ $thisMonthTotal }}</h4>
                                        <p class="text-muted mb-3">{{ __('global.total_attendance_this_month') }}</p>
                                        <span class="peity-donut" data-peity='{ "fill": ["#1f6feb", "#eaeefb"], "innerRadius": 28, "radius": 32 }' data-width="72" data-height="72">{{ $thisMonthOnTime }}/{{ max($thisMonthTotal, 1) }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="stat-summary">
                                        <p class="text-muted mb-2">{{ __('global.last_month') }}</p>
                                        <h4>{{ $lastMonthTotal }}</h4>
                                        <p class="text-muted mb-3">{{ __('global.total_attendance_last_month') }}</p>
                                        <span class="peity-donut" data-peity='{ "fill": ["#f4b942", "#f1eee5"], "innerRadius": 28, "radius": 32 }' data-width="72" data-height="72">{{ $lastMonthTotal }}/{{ max($lastMonthTotal, 1) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 mb-4">
            <div class="card dash-card">
                <div class="card-body status-list">
                    <h4 class="dashboard-section-title mb-4">{{ __('global.monthly_status') }}</h4>
                    <div class="wid-peity">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="text-muted">{{ __('global.on_time') }}</p>
                                <h5 class="mb-3">{{ $thisMonthOnTime }}</h5>
                            </div>
                            <div class="col-md-6">
                                <span class="peity-line" data-width="100%" data-peity='{ "fill": ["rgba(31, 111, 235,0.3)"],"stroke": ["rgba(31, 111, 235,0.9)"]}' data-height="60">{{ $thisMonthOnTime }},{{ $thisMonthLate }},{{ $thisMonthOnTime }},{{ $thisMonthLate }},{{ $thisMonthOnTime }},{{ $thisMonthLate }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="wid-peity">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="text-muted">{{ __('global.late') }}</p>
                                <h5 class="mb-3">{{ $thisMonthLate }}</h5>
                            </div>
                            <div class="col-md-6">
                                <span class="peity-line" data-width="100%" data-peity='{ "fill": ["rgba(229, 72, 77,0.3)"],"stroke": ["rgba(229, 72, 77,0.9)"]}' data-height="60">{{ $thisMonthLate }},{{ $thisMonthOnTime }},{{ $thisMonthLate }},{{ $thisMonthOnTime }},{{ $thisMonthLate }},{{ $thisMonthOnTime }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="wid-peity">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="text-muted">{{ __('global.sick') }} / {{ __('global.permission') }} / {{ __('global.without_note') }}</p>
                                <h5>{{ $thisMonthSick + $thisMonthLeave + $thisMonthNoNote }}</h5>
                            </div>
                            <div class="col-md-6">
                                <span class="peity-line" data-width="100%" data-peity='{ "fill": ["rgba(24, 163, 106,0.3)"],"stroke": ["rgba(24, 163, 106,0.9)"]}' data-height="60">{{ $thisMonthSick }},{{ $thisMonthLeave }},{{ $thisMonthNoNote }},{{ $thisMonthSick }},{{ $thisMonthLeave }},{{ $thisMonthNoNote }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<!--Chartist Chart-->
<script src="{{ URL::asset('plugins/chartist/js/chartist.min.js') }}"></script>
<script src="{{ URL::asset('plugins/chartist/js/chartist-plugin-tooltip.min.js') }}"></script>
<!-- peity JS -->
<script src="{{ URL::asset('plugins/peity-chart/jquery.peity.min.js') }}"></script>
<script>
    new Chartist.Line('#chart-with-area', {
        labels: @json($chartLabels),
        series: [@json($chartSeries)]
    }, {
        low: 0,
        showArea: true,
        axisX: {
            labelInterpolationFnc: function (value, index) {
                return index % 2 === 0 ? value : null;
            }
        },
        axisY: {
            onlyInteger: true
        },
        plugins: [
            Chartist.plugins.tooltip()
        ]
    });

    $('.peity-donut').each(function () {
        $(this).peity("donut", $(this).data());
    });

    $('.peity-line').each(function() {
        $(this).peity("line", $(this).data());
    });
</script>
@endsection
