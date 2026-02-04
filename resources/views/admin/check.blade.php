@extends('layouts.master')

@section('css')
    <!-- Table css -->
    <link href="{{ URL::asset('plugins/RWD-Table-Patterns/dist/css/rwd-table.min.css') }}" rel="stylesheet"
        type="text/css" media="screen">
    <style>
        .check-card {
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(18, 38, 63, 0.08);
        }
        .check-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 12px 16px;
            background: linear-gradient(90deg, #2b5876, #4e4376);
            color: #fff;
            border-radius: 12px 12px 0 0;
            font-weight: 600;
        }
        .check-legend {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            font-size: 12px;
        }
        .check-legend span {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255, 255, 255, 0.15);
            padding: 4px 8px;
            border-radius: 999px;
        }
        .check-wrap {
            overflow: auto;
            border-radius: 10px;
            border: 1px solid #e6e9f0;
        }
        .check-wrap::-webkit-scrollbar {
            height: 10px;
        }
        .check-wrap::-webkit-scrollbar-track {
            background: #eef1f6;
            border-radius: 999px;
        }
        .check-wrap::-webkit-scrollbar-thumb {
            background: linear-gradient(90deg, #4e4376, #2b5876);
            border-radius: 999px;
        }
        .check-wrap::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(90deg, #5e4f88, #356587);
        }
        .check-table {
            min-width: 1200px;
        }
        .check-table thead th {
            position: sticky;
            top: 0;
            z-index: 2;
            background: #f7f8fc;
            color: #3b3f5c;
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
            border-bottom: 1px solid #e6e9f0;
            white-space: nowrap;
        }
        .check-table thead .sub-col {
            font-size: 11px;
            text-transform: none;
            color: #6c757d;
            background: #fbfcff;
        }
        .check-table tbody td {
            font-size: 13px;
            vertical-align: middle;
            border-top: 1px solid #eef1f6;
            white-space: nowrap;
            padding: 10px 8px;
        }
        .check-table tbody tr:nth-child(odd) {
            background: #fafbff;
        }
        .check-date-col {
            text-align: center;
            padding: 8px 6px;
            min-width: 120px;
        }
        .check-cell {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 8px;
            border-radius: 999px;
            background: #f3f4f8;
        }
        .status-select {
            min-width: 120px;
        }
        .check-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
        }
    </style>
@endsection


@section('content')

    @php
        $demoMode = $demoMode ?? false;
    @endphp

    <div class="card check-card">
        <div class="check-header">
            <div>{{ __('global.attendance_sheet') }}</div>
            <div class="check-legend">
                <span><i class="fa fa-check text-success"></i> {{ __('global.in') }}</span>
                <span><i class="fa fa-check text-danger"></i> {{ __('global.out') }}</span>
            </div>
        </div>

        <div class="card-body">
            <form action="{{ route('check_store') }}" method="post">
                <div class="check-actions">
                    <button type="submit" class="btn btn-success">{{ __('global.submit') }}</button>
                </div>
                @csrf
                <div class="check-wrap">
                    <table class="table table-bordered table-sm check-table">
                    <thead>
                        <tr>

                            <th rowspan="2">{{ __('global.employee_name') }}</th>
                            <th rowspan="2">{{ __('global.employee_position') }}</th>
                            <th rowspan="2">{{ __('global.employee_id') }}</th>
                            @php
                                $today = today();
                                $dates = [];
                                
                                for ($i = 1; $i < $today->daysInMonth + 1; ++$i) {
                                    $dates[] = \Carbon\Carbon::createFromDate($today->year, $today->month, $i)->format('Y-m-d');
                                }
                                
                            @endphp
                            @foreach ($dates as $date)
                                <th class="check-date-col" colspan="3">{{ $date }}</th>

                            @endforeach

                        </tr>
                        <tr>
                            @foreach ($dates as $date)
                                <th class="sub-col">{{ __('global.in') }}</th>
                                <th class="sub-col">{{ __('global.out') }}</th>
                                <th class="sub-col">{{ __('global.status') }}</th>
                            @endforeach
                        </tr>
                    </thead>

                    <tbody>

                            @foreach ($employees as $employee)

                                <input type="hidden" name="emp_id" value="{{ $employee->id }}">

                                <tr>
                                    <td>{{ $employee->name }}</td>
                                    <td>{{ $employee->position }}</td>
                                    <td>{{ $employee->id }}</td>






                                    @for ($i = 1; $i < $today->daysInMonth + 1; ++$i)


                                    @php
                                        $date_picker = \Carbon\Carbon::createFromDate($today->year, $today->month, $i)->format('Y-m-d');

                                        if ($demoMode) {
                                            $demoAttd = $demoAttendance[$employee->id][$date_picker] ?? null;
                                            $demoLeave = $demoLeaves[$employee->id][$date_picker] ?? null;
                                            $check_attd = $demoAttd ? (object) $demoAttd : null;
                                            $check_leave = $demoLeave ? (object) $demoLeave : null;
                                        } else {
                                            $check_attd = \App\Models\Attendance::query()
                                                ->where('emp_id', $employee->id)
                                                ->where('attendance_date', $date_picker)
                                                ->first();

                                            $check_leave = \App\Models\Leave::query()
                                                ->where('emp_id', $employee->id)
                                                ->where('leave_date', $date_picker)
                                                ->first();
                                        }
                                    @endphp
                                        <td>

                                            <div class="form-check form-check-inline check-cell">
                                                <input class="form-check-input" id="check_box"
                                                    name="attd[{{ $date_picker }}][{{ $employee->id }}]" type="checkbox"
                                                    @if (isset($check_attd))  checked @endif id="inlineCheckbox1" value="1">

                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check form-check-inline check-cell">
                                                <input class="form-check-input" id="check_box"
                                                    name="leave[{{ $date_picker }}][{{ $employee->id }}]" type="checkbox"
                                                    @if (isset($check_leave))  checked @endif id="inlineCheckbox2" value="1">

                                            </div>

                                        </td>
                                        <td>
                                            <select class="form-control form-control-sm status-select"
                                                name="status_type[{{ $date_picker }}][{{ $employee->id }}]">
                                                @php
                                                    $statusType = $check_attd ? $check_attd->status_type : null;
                                                @endphp
                                                <option value="" {{ empty($statusType) ? 'selected' : '' }}>-</option>
                                                <option value="hadir" {{ $statusType === 'hadir' ? 'selected' : '' }}>Hadir</option>
                                                <option value="sakit" {{ $statusType === 'sakit' ? 'selected' : '' }}>Sakit</option>
                                                <option value="izin" {{ $statusType === 'izin' ? 'selected' : '' }}>Izin</option>
                                                <option value="tanpa_keterangan" {{ $statusType === 'tanpa_keterangan' ? 'selected' : '' }}>Tanpa Ket.</option>
                                            </select>
                                        </td>

                                    @endfor
                            </tr>
                        @endforeach


                    </tbody>


                </table>
                </div>
            </form>
        </div>
    </div>
@endsection




