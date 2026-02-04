@extends('layouts.master')

@section('css')
    <!-- Table css -->
    <link href="{{ URL::asset('plugins/RWD-Table-Patterns/dist/css/rwd-table.min.css') }}" rel="stylesheet"
        type="text/css" media="screen">
    <style>
        .admin-card {
            border-radius: 16px;
            border: 1px solid #e6e3ef;
            box-shadow: 0 16px 32px rgba(15, 23, 42, 0.06);
        }
        .admin-card .card-body {
            padding: 20px;
        }
        .admin-table thead th {
            background: #f7f5fb;
            color: #5b6073;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            border-bottom: 1px solid #e6e3ef;
        }
        .admin-table tbody td {
            vertical-align: middle;
        }
    </style>
@endsection

@section('breadcrumb')
    <div class="col-sm-6">
        <h4 class="page-title text-left">{{ __('global.leave') }}</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">{{ __('global.home') }}</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0);">{{ __('global.leave') }}</a></li>


        </ol>
    </div>
@endsection
@section('button')
    <a href="leave/assign" class="btn btn-primary btn-sm btn-flat"><i
            class="mdi mdi-plus mr-2"></i>{{ __('global.add_new') }}</a>


@endsection

@section('content')
@include('includes.flash')
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

    <div class="row">
        <div class="col-12">
            <div class="card admin-card">
                <div class="card-body">

                    <div class="table-rep-plugin">
                        <div class="table-responsive mb-0" data-pattern="priority-columns">
                            <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap admin-table" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        
                                <thead>
                                    <tr>
                                        <th data-priority="1">{{ __('global.date') }}</th>
                                        <th data-priority="2">{{ __('global.employee_id') }}</th>
                                        <th data-priority="3">{{ __('global.name') }}</th>
                                     
                                        <th data-priority="5">{{ __('global.leave') }}</th>
                                        <th data-priority="6">{{ __('global.time_in') }}</th>
                                        <th data-priority="7">{{ __('global.time_out') }}</th>
                                        <th data-priority="8">{{ __('global.note') }}</th>


                                    </tr>
                                </thead>
                                
                            <tbody>
                                @foreach( $leaves as $leave)

                                <tr>
                                    <td>{{$leave->leave_date}}</td>
                                    <td>{{$leave->emp_id}}</td>
                                    <td>{{$leave->employee->name}}</td>
                                    <td>{{$leave->leave_time}}
                                        @php
                                            $scheduleTime = '16:30:00';
                                            $leaveTime = date('H:i:s', strtotime($leave->leave_time));
                                            $diffSeconds = 0;
                                            $isEarly = false;
                                            $scheduleTs = strtotime($leave->leave_date . ' ' . $scheduleTime);
                                            $leaveTs = strtotime($leave->leave_date . ' ' . $leaveTime);
                                            $diffSeconds = abs($leaveTs - $scheduleTs);
                                            $isEarly = $leaveTs < $scheduleTs;
                                        @endphp
                                        @if ($isEarly)
                                        <span class="badge badge-danger badge-pill float-right">{{ __('global.too_fast') }}</span>
                                        <div class="text-danger small">Pulang lebih cepat {{ format_duration($diffSeconds) }}</div>
                                        @else
                                        <span class="badge badge-primary badge-pill float-right">{{ __('global.on_time') }}</span>
                                            @if ($diffSeconds > 0)
                                                <div class="text-success small">Pulang lebih lama {{ format_duration($diffSeconds) }}</div>
                                            @else
                                                <div class="text-success small">Tepat waktu</div>
                                            @endif
                                        @endif
                                    </td>
                                    <td>{{$leave->employee->schedules->first()->time_in}} </td>
                                    <td>{{$leave->employee->schedules->first()->time_out}}</td>
                                    <td>{{ $leave->note ?? '-' }}</td>
                                </tr>

                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection
