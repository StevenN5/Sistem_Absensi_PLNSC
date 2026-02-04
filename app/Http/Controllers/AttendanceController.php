<?php

namespace App\Http\Controllers;

use DateTime;
use Carbon\Carbon;
use App\Models\Employee;
use App\Models\Latetime;
use App\Models\Leave;
use App\Models\Attendance;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\AttendanceEmp;

class AttendanceController extends Controller
{   
    //show attendance 
    public function index()
    {
        $attendanceLogs = Attendance::with('employee')->get()->map(function ($attendance) {
            $date = $attendance->attendance_date;
            $time = $attendance->attendance_time;
            $timeIn = '08:00:00';
            $attendanceTs = strtotime($date . ' ' . $time);
            $scheduleTs = strtotime($date . ' ' . $timeIn);
            $diffSeconds = abs($attendanceTs - $scheduleTs);
            $isLate = $attendanceTs > $scheduleTs;

            return [
                'type' => 'Time In',
                'date' => $date,
                'time' => $time,
                'status' => $isLate ? 'Late' : 'On Time',
                'diff_seconds' => $diffSeconds,
                'duration' => null,
                'note' => $attendance->note,
                'emp_id' => $attendance->emp_id,
                'name' => $attendance->employee ? $attendance->employee->name : '-',
                'timestamp' => $attendanceTs,
            ];
        });

        $leaveLogs = Leave::with('employee')->get()->map(function ($leave) {
            $date = $leave->leave_date;
            $time = $leave->leave_time;
            $timeOut = '16:30:00';
            $leaveTs = strtotime($date . ' ' . $time);
            $scheduleTs = strtotime($date . ' ' . $timeOut);
            $diffSeconds = abs($leaveTs - $scheduleTs);
            $isEarly = $leaveTs < $scheduleTs;

            return [
                'type' => 'Time Out',
                'date' => $date,
                'time' => $time,
                'status' => $isEarly ? 'Too Fast' : 'On Time',
                'diff_seconds' => $diffSeconds,
                'duration' => null,
                'note' => $leave->note,
                'emp_id' => $leave->emp_id,
                'name' => $leave->employee ? $leave->employee->name : '-',
                'timestamp' => $leaveTs,
            ];
        });

        $lateLogs = Latetime::with('employee')->get()->map(function ($latetime) {
            $date = $latetime->latetime_date;
            $timestamp = strtotime($date . ' 00:00:00');

            return [
                'type' => 'Late Time',
                'date' => $date,
                'time' => '-',
                'status' => 'Late',
                'diff_seconds' => null,
                'duration' => $latetime->duration,
                'note' => null,
                'emp_id' => $latetime->emp_id,
                'name' => $latetime->employee ? $latetime->employee->name : '-',
                'timestamp' => $timestamp,
            ];
        });

        $logs = $attendanceLogs
            ->merge($leaveLogs)
            ->merge($lateLogs)
            ->sortByDesc('timestamp')
            ->values();

        $groupedLogs = $logs->groupBy(function ($log) {
            $date = $log['date'] ?? null;
            if (!$date) {
                return 'unknown';
            }
            $carbon = Carbon::parse($date);
            return $carbon->isoWeekYear . '-' . str_pad($carbon->isoWeek, 2, '0', STR_PAD_LEFT);
        });

        return view('admin.attendance')->with([
            'logs' => $logs,
            'groupedLogs' => $groupedLogs,
        ]);
    }

    //show late times
    public function indexLatetime()
    {
        return view('admin.latetime')->with(['latetimes' => Latetime::all()]);
    }

    

    // public static function lateTime(Employee $employee)
    // {
    //     $current_t = new DateTime(date('H:i:s'));
    //     $start_t = new DateTime($employee->schedules->first()->time_in);
    //     $difference = $start_t->diff($current_t)->format('%H:%I:%S');

    //     $latetime = new Latetime();
    //     $latetime->emp_id = $employee->id;
    //     $latetime->duration = $difference;
    //     $latetime->latetime_date = date('Y-m-d');
    //     $latetime->save();
    // }

    public static function lateTimeDevice($att_dateTime, Employee $employee)
    {
        $attendance_time = new DateTime($att_dateTime);
        $checkin = new DateTime($employee->schedules->first()->time_in);
        $difference = $checkin->diff($attendance_time)->format('%H:%I:%S');

        $latetime = new Latetime();
        $latetime->emp_id = $employee->id;
        $latetime->duration = $difference;
        $latetime->latetime_date = date('Y-m-d', strtotime($att_dateTime));
        $latetime->save();
    }
  
}
