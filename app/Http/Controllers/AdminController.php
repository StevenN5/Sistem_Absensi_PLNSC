<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use App\Models\Latetime;
use App\Models\Attendance;
use Carbon\Carbon;


class AdminController extends Controller
{

 
    public function index()
    {
        //Dashboard statistics 
        $today = Carbon::today();
        $totalEmp =  count(Employee::all());
        $AllAttendance = count(Attendance::whereAttendance_date($today->toDateString())->get());
        $ontimeEmp = count(Attendance::whereAttendance_date($today->toDateString())->whereStatus('1')->get());
        $latetimeEmp = count(Attendance::whereAttendance_date($today->toDateString())->whereStatus('0')->get());
            
        if($AllAttendance > 0){
                $percentageOntime = str_split(($ontimeEmp/ $AllAttendance)*100, 4)[0];
            }else {
                $percentageOntime = 0 ;
            }

        $monthStart = $today->copy()->startOfMonth()->toDateString();
        $monthEnd = $today->copy()->endOfMonth()->toDateString();
        $lastMonthStart = $today->copy()->subMonthNoOverflow()->startOfMonth()->toDateString();
        $lastMonthEnd = $today->copy()->subMonthNoOverflow()->endOfMonth()->toDateString();

        $thisMonthTotal = Attendance::whereBetween('attendance_date', [$monthStart, $monthEnd])->count();
        $lastMonthTotal = Attendance::whereBetween('attendance_date', [$lastMonthStart, $lastMonthEnd])->count();
        $thisMonthOnTime = Attendance::whereBetween('attendance_date', [$monthStart, $monthEnd])->whereStatus(1)->count();
        $thisMonthLate = Attendance::whereBetween('attendance_date', [$monthStart, $monthEnd])->whereStatus(0)->count();

        $thisMonthSick = Attendance::whereBetween('attendance_date', [$monthStart, $monthEnd])->where('status_type', 'sakit')->count();
        $thisMonthLeave = Attendance::whereBetween('attendance_date', [$monthStart, $monthEnd])->where('status_type', 'izin')->count();
        $thisMonthNoNote = Attendance::whereBetween('attendance_date', [$monthStart, $monthEnd])->where('status_type', 'tanpa_keterangan')->count();

        $daysInMonth = $today->daysInMonth;
        $dailyCounts = Attendance::selectRaw('attendance_date, COUNT(*) as total')
            ->whereBetween('attendance_date', [$monthStart, $monthEnd])
            ->groupBy('attendance_date')
            ->pluck('total', 'attendance_date')
            ->toArray();

        $chartLabels = [];
        $chartSeries = [];
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::createFromDate($today->year, $today->month, $day)->format('Y-m-d');
            $chartLabels[] = $day;
            $chartSeries[] = isset($dailyCounts[$date]) ? (int) $dailyCounts[$date] : 0;
        }

        $data = [$totalEmp, $ontimeEmp, $latetimeEmp, $percentageOntime];
        
        return view('admin.index')->with([
            'data' => $data,
            'chartLabels' => $chartLabels,
            'chartSeries' => $chartSeries,
            'thisMonthTotal' => $thisMonthTotal,
            'lastMonthTotal' => $lastMonthTotal,
            'thisMonthOnTime' => $thisMonthOnTime,
            'thisMonthLate' => $thisMonthLate,
            'thisMonthSick' => $thisMonthSick,
            'thisMonthLeave' => $thisMonthLeave,
            'thisMonthNoNote' => $thisMonthNoNote,
        ]);
    }

}
