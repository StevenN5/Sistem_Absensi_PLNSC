@extends('layouts.master')

@section('css')
    <style>
        .timetable-card {
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(18, 38, 63, 0.08);
        }
        .timetable-header {
            background: linear-gradient(90deg, #2b5876, #4e4376);
            color: #fff;
            border-radius: 12px 12px 0 0;
            font-weight: 600;
            letter-spacing: 0.2px;
        }
        .timetable-month {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 16px;
            border-radius: 999px;
            background: #f3f5fb;
            border: 1px solid #e1e6f0;
            color: #2b5876;
            font-weight: 600;
        }
        .timetable-wrap {
            overflow: auto;
            border-radius: 10px;
            border: 1px solid #e6e9f0;
        }
        .timetable-wrap::-webkit-scrollbar {
            height: 10px;
        }
        .timetable-wrap::-webkit-scrollbar-track {
            background: #eef1f6;
            border-radius: 999px;
        }
        .timetable-wrap::-webkit-scrollbar-thumb {
            background: linear-gradient(90deg, #4e4376, #2b5876);
            border-radius: 999px;
        }
        .timetable-wrap::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(90deg, #5e4f88, #356587);
        }
        .timetable {
            min-width: 1200px;
            border-collapse: collapse;
            table-layout: auto;
        }
        .timetable thead th {
            position: sticky;
            top: 0;
            z-index: 2;
            background: #f7f8fc;
            color: #3b3f5c;
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
            border: 1px solid #e1e6f0;
            white-space: nowrap;
            word-break: normal;
            text-align: center;
        }
        .timetable tbody td {
            font-size: 13px;
            vertical-align: middle;
            border: 1px solid #e1e6f0;
            white-space: nowrap;
            word-break: normal;
        }
        .timetable tbody td {
            padding: 10px 8px;
        }
        .timetable tbody tr:nth-child(odd) {
            background: #fafbff;
        }
        .timetable .cell-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 6px 10px;
            border-radius: 999px;
            background: #f3f4f8;
        }
        .timetable .cell-badge i {
            font-size: 14px;
            width: 16px;
            text-align: center;
        }
        .timetable .cell-time {
            font-size: 12px;
            font-weight: 600;
            color: #2b5876;
            margin-left: 6px;
        }
        .timetable .cell-status {
            font-size: 12px;
            font-weight: 600;
        }
        .timetable td .cell-badge i + i {
            margin-left: 6px;
        }
        .timetable .legend {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            font-size: 12px;
            color: #6c757d;
            margin-bottom: 10px;
            padding: 10px 12px;
            background: #f6f8ff;
            border: 1px dashed #dfe4f0;
            border-radius: 10px;
        }
        .timetable .legend span {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 8px;
            border-radius: 999px;
            background: #fff;
            border: 1px solid #e6e9f0;
        }
        .timetable .legend i {
            font-size: 12px;
        }
        .timetable .date-col {
            text-align: center;
            padding: 8px 6px;
            min-width: 140px;
        }
        .timetable .sub-col {
            text-align: center;
            font-size: 11px;
            color: #6c757d;
            background: #fbfcff;
            min-width: 90px;
        }
        .timetable .status-col {
            min-width: 120px;
        }
        .timetable .note-col {
            min-width: 160px;
        }
        .timetable .name-col {
            min-width: 180px;
        }
        .timetable .pos-col {
            min-width: 140px;
        }
        .timetable .id-col {
            min-width: 80px;
        }
    </style>
@endsection

@section('content')

    @php
        $today = today();
        $monthLabel = $today->translatedFormat('F Y');
        $demoMode = $demoMode ?? false;
    @endphp
    <div class="mb-3">
        <span class="timetable-month">{{ __('global.sheet_report_title') }} — {{ $monthLabel }}</span>
    </div>
    <div class="card timetable-card">
        <div class="card-header timetable-header">
            {{ __('global.sheet_report_title') }}
        </div>
        <div class="card-body">
            <div class="legend">
                <span><i class="fa fa-check text-success"></i> {{ __('global.hadir_tepat') }}</span>
                <span><i class="fa fa-check text-danger"></i> {{ __('global.telat_pulang_cepat') }}</span>
                <span><i class="fas fa-times text-danger"></i> {{ __('global.no_data') }}</span>
            </div>
            <div class="timetable-actions d-flex justify-content-between align-items-center mb-2 flex-wrap">
                <form class="form-inline mb-2 mb-md-0" id="export-user-form">
                    <label class="mr-2 font-weight-bold" for="export_user_id">{{ __('global.employees') }}</label>
                    <select class="form-control form-control-sm mr-2" id="export_user_id">
                        <option value="">{{ __('global.pleaseSelect') }}</option>
                        @foreach ($employees as $employee)
                            <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                        @endforeach
                    </select>
                    <button type="button" class="btn btn-outline-primary btn-sm" id="export-user-btn">
                        <i class="fa fa-file-excel-o mr-1"></i> Ekspor Per User
                    </button>
                </form>
                <a class="btn btn-success btn-sm timetable-export mb-2 mb-md-0" href="{{ route('sheet-report.export', ['month' => $today->format('Y-m')]) }}">
                    <i class="fa fa-file-excel-o mr-1"></i> Ekspor Semua
                </a>
            </div>
            <div class="timetable-wrap">
                <table class="table table-sm timetable" id="printTable"
                    data-export-name="SheetReport-All-{{ $today->format('Y-m') }}">
                    <thead>
                        <tr>
                            <th class="name-col" rowspan="2">{{ __('global.employee_name') }}</th>
                            <th class="pos-col" rowspan="2">{{ __('global.employee_position') }}</th>
                            <th class="id-col" rowspan="2">{{ __('global.employee_id') }}</th>
                            @php
                                $dates = [];
                                
                                for ($i = 1; $i < $today->daysInMonth + 1; ++$i) {
                                    $dates[] = \Carbon\Carbon::createFromDate($today->year, $today->month, $i)->format('Y-m-d');
                                }
                                
                            @endphp
                            @foreach ($dates as $date)
                                <th class="date-col" colspan="4">{{ $date }}</th>
                            @endforeach

                        </tr>
                        <tr>
                            @foreach ($dates as $date)
                                <th class="sub-col">{{ __('global.in') }}</th>
                                <th class="sub-col">{{ __('global.out') }}</th>
                                <th class="sub-col status-col">{{ __('global.status') }}</th>
                                <th class="sub-col note-col">{{ __('global.note') }}</th>
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
                                    @php
                                        $timeInText = isset($check_attd) && $check_attd->attendance_time
                                            ? \Carbon\Carbon::parse($check_attd->attendance_time)->format('H:i')
                                            : '-';
                                        $timeOutText = isset($check_leave) && $check_leave->leave_time
                                            ? \Carbon\Carbon::parse($check_leave->leave_time)->format('H:i')
                                            : '-';
                                    @endphp
                                    @php
                                        $statusLabel = '-';
                                        if (isset($check_attd) && $check_attd->status_type) {
                                            if ($check_attd->status_type === 'sakit') {
                                                $statusLabel = __('global.sick');
                                            } elseif ($check_attd->status_type === 'izin') {
                                                $statusLabel = __('global.permission');
                                            } elseif ($check_attd->status_type === 'tanpa_keterangan') {
                                                $statusLabel = __('global.without_note');
                                            }
                                        } elseif (isset($check_attd)) {
                                            $statusLabel = $check_attd->status === 0
                                                ? __('global.telat_pulang_cepat')
                                                : __('global.hadir_tepat');
                                        } elseif (isset($check_leave)) {
                                            $statusLabel = $check_leave->status === 0
                                                ? __('global.telat_pulang_cepat')
                                                : __('global.hadir_tepat');
                                        }
                                        $noteText = '-';
                                        if (isset($check_attd) && $check_attd->note) {
                                            $noteText = $check_attd->note;
                                        } elseif (isset($check_leave) && $check_leave->note) {
                                            $noteText = $check_leave->note;
                                        }
                                    @endphp
                                    <td data-export="{{ $timeInText }}">
                                        <span class="cell-badge">
                                            @if (isset($check_attd))
                                                @if ($check_attd->status === 1)
                                                    <i class="fa fa-check text-success"></i>
                                                @elseif ($check_attd->status === 0)
                                                    <i class="fa fa-check text-danger"></i>
                                                @else
                                                    <i class="fa fa-check text-success"></i>
                                                @endif
                                                <span class="cell-time">{{ $timeInText }}</span>
                                            @else
                                            <i class="fas fa-times text-danger"></i>
                                            <span class="cell-time">-</span>
                                            @endif
                                        </span>
                                    </td>
                                    <td data-export="{{ $timeOutText }}">
                                        <span class="cell-badge">
                                            @if (isset($check_leave))
                                                @if ($check_leave->status === 1)
                                                    <i class="fa fa-check text-success"></i>
                                                @elseif ($check_leave->status === 0)
                                                    <i class="fa fa-check text-danger"></i>
                                                @else
                                                    <i class="fa fa-check text-success"></i>
                                                @endif
                                                <span class="cell-time">{{ $timeOutText }}</span>
                                       @else
                                       <i class="fas fa-times text-danger"></i>
                                       <span class="cell-time">-</span>
                                       @endif
                                        </span>

                                    </td>
                                    <td data-export="{{ $statusLabel }}" class="text-center">
                                        {{ $statusLabel }}
                                    </td>
                                    <td data-export="{{ $noteText }}">
                                        {{ $noteText }}
                                    </td>

                                @endfor
                            </tr>
                        @endforeach





                    </tbody>


                </table>
            </div>
        </div>
    </div>
@endsection

@section('script-bottom')
    <script>
        $(function () {
            var $table = $('#printTable');
            if (!$table.length || !$.fn.DataTable) {
                return;
            }

            if ($.fn.DataTable.isDataTable($table)) {
                $table.DataTable().destroy();
            }

            $table.DataTable({
                lengthChange: false,
                paging: false,
                searching: false,
                ordering: false,
                info: false,
                scrollX: true
            });

            $('#export-user-btn').on('click', function () {
                var userId = $('#export_user_id').val();
                if (!userId) {
                    alert('{{ __('global.pleaseSelect') }}');
                    return;
                }
                var url = "{{ route('sheet-report.export-user') }}" +
                    "?month={{ $today->format('Y-m') }}&user_id=" + encodeURIComponent(userId);
                window.location.href = url;
            });
        });
    </script>
@endsection


