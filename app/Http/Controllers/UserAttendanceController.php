<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\FinalReport;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UserAttendanceController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $employee = Employee::where('email', $user->email)->first();

        $today = now()->toDateString();
        $hasAttendance = false;
        $lastAttendance = null;
        $hasLeave = false;
        $lastLeave = null;
        $logs = collect();

        if ($employee) {
            $hasAttendance = Attendance::where('emp_id', $employee->id)
                ->whereDate('attendance_date', $today)
                ->exists();
            $lastAttendance = Attendance::where('emp_id', $employee->id)
                ->orderBy('attendance_date', 'desc')
                ->orderBy('attendance_time', 'desc')
                ->first();

            $hasLeave = \App\Models\Leave::where('emp_id', $employee->id)
                ->whereDate('leave_date', $today)
                ->exists();
            $lastLeave = \App\Models\Leave::where('emp_id', $employee->id)
                ->orderBy('leave_date', 'desc')
                ->orderBy('leave_time', 'desc')
                ->first();

            $attendanceLogs = Attendance::where('emp_id', $employee->id)
                ->orderBy('attendance_date', 'desc')
                ->orderBy('attendance_time', 'desc')
                ->get()
                ->map(function ($attendance) {
                    $datetime = $attendance->attendance_date . ' ' . $attendance->attendance_time;
                    return [
                        'datetime' => $datetime,
                        'timestamp' => strtotime($datetime),
                        'type' => 'Time In',
                        'note' => $attendance->note,
                    ];
                });

            $leaveLogs = \App\Models\Leave::where('emp_id', $employee->id)
                ->orderBy('leave_date', 'desc')
                ->orderBy('leave_time', 'desc')
                ->get()
                ->map(function ($leave) {
                    $datetime = $leave->leave_date . ' ' . $leave->leave_time;
                    return [
                        'datetime' => $datetime,
                        'timestamp' => strtotime($datetime),
                        'type' => 'Time Out',
                        'note' => $leave->note,
                    ];
                });

            $finalReportLogs = FinalReport::where('emp_id', $employee->id)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($report) {
                    $datetime = $report->created_at ? $report->created_at->format('Y-m-d H:i:s') : '-';
                    return [
                        'datetime' => $datetime,
                        'timestamp' => $report->created_at ? $report->created_at->timestamp : 0,
                        'type' => 'Final Report',
                        'note' => $report->file_name,
                    ];
                });

            $logs = $attendanceLogs
                ->merge($leaveLogs)
                ->merge($finalReportLogs)
                ->sortByDesc('timestamp')
                ->values()
                ->take(20);
        }

        $groupedLogs = $logs->groupBy(function ($log) {
            $date = $log['datetime'] ?? null;
            if (!$date) {
                return 'unknown';
            }
            $carbon = Carbon::parse($date);
            return $carbon->isoWeekYear . '-' . str_pad($carbon->isoWeek, 2, '0', STR_PAD_LEFT);
        });

        return view('user.attendance', [
            'employee' => $employee,
            'hasAttendance' => $hasAttendance,
            'lastAttendance' => $lastAttendance,
            'hasLeave' => $hasLeave,
            'lastLeave' => $lastLeave,
            'logs' => $logs,
            'groupedLogs' => $groupedLogs,
        ]);
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $employee = Employee::where('email', $user->email)->first();

        if (!$employee) {
            $employee = new Employee();
            $employee->name = $user->name;
            $employee->email = $user->email;
            $employee->position = 'User';
            $employee->save();
        }

        $today = now()->toDateString();
        $alreadyRecorded = Attendance::where('emp_id', $employee->id)
            ->whereDate('attendance_date', $today)
            ->exists();

        if ($alreadyRecorded) {
            flash()->error('Gagal', 'Kehadiran hari ini sudah tercatat.');
            return redirect()->route('home');
        }

        $attendance = new Attendance();
        $attendance->uid = 0;
        $attendance->emp_id = $employee->id;
        $attendance->state = 0;
        $attendance->attendance_time = now()->toTimeString();
        $attendance->attendance_date = $today;
        $timeIn = '08:00:00';
        $attendanceTime = date('H:i:s', strtotime($attendance->attendance_time));
        $attendance->status = $attendanceTime <= $timeIn ? 1 : 0;
        if ($attendance->status === 0 && $request->filled('note')) {
            $attendance->note = $request->note;
        }
        $attendance->type = 0;
        $attendance->save();

        flash()->success('Berhasil', 'Kehadiran berhasil dicatat.');
        return redirect()->route('home');
    }

    public function storeLeave(Request $request)
    {
        $user = auth()->user();
        $employee = Employee::where('email', $user->email)->first();

        if (!$employee) {
            flash()->error('Gagal', 'Data karyawan tidak ditemukan.');
            return redirect()->route('home');
        }

        $today = now()->toDateString();
        $alreadyLeave = \App\Models\Leave::where('emp_id', $employee->id)
            ->whereDate('leave_date', $today)
            ->exists();

        if ($alreadyLeave) {
            flash()->error('Gagal', 'Jam pulang hari ini sudah tercatat.');
            return redirect()->route('home');
        }

        $leave = new \App\Models\Leave();
        $leave->uid = 0;
        $leave->emp_id = $employee->id;
        $leave->state = 0;
        $leave->leave_time = now()->toTimeString();
        $leave->leave_date = $today;
        $timeOut = '16:30:00';
        $leaveTime = date('H:i:s', strtotime($leave->leave_time));
        $leave->status = $leaveTime >= $timeOut ? 1 : 0;
        if ($leave->status === 0 && $request->filled('note')) {
            $leave->note = $request->note;
        }
        $leave->type = 1;
        $leave->save();

        flash()->success('Berhasil', 'Jam pulang berhasil dicatat.');
        return redirect()->route('home');
    }
}
