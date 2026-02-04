<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Leave;
use App\Exports\SheetReportExport;
use App\Exports\SheetReportUserExport;
use Maatwebsite\Excel\Facades\Excel;

class CheckController extends Controller
{
    public function index()
    {
        return view('admin.check')->with(['employees' => Employee::all()]);
    }

    public function CheckStore(Request $request)
    {
        if (isset($request->status_type)) {
            foreach ($request->status_type as $keys => $values) {
                foreach ($values as $key => $value) {
                    if (!$value || $value === 'hadir') {
                        continue;
                    }
                    $attendance = Attendance::whereAttendance_date($keys)
                        ->whereEmp_id($key)
                        ->whereType(0)
                        ->first();

                    if (!$attendance) {
                        $attendance = new Attendance();
                        $attendance->emp_id = $key;
                        $attendance->attendance_date = $keys;
                        $attendance->attendance_time = '00:00:00';
                        $attendance->type = 0;
                    }
                    $attendance->status_type = $value;
                    $attendance->save();
                }
            }
        }
        if (isset($request->attd)) {
            foreach ($request->attd as $keys => $values) {
                foreach ($values as $key => $value) {
                    if ($employee = Employee::whereId(request('emp_id'))->first()) {
                        if (
                            !Attendance::whereAttendance_date($keys)
                                ->whereEmp_id($key)
                                ->whereType(0)
                                ->first()
                        ) {
                            $data = new Attendance();
                            
                            $data->emp_id = $key;
                            $emp_req = Employee::whereId($data->emp_id)->first();
                            $data->attendance_time = date('H:i:s', strtotime($emp_req->schedules->first()->time_in));
                            $data->attendance_date = $keys;
                            $data->status_type = null;
                            
                            // $emps = date('H:i:s', strtotime($employee->schedules->first()->time_in));
                            // if (!($emps >= $data->attendance_time)) {
                            //     $data->status = 0;
                           
                            // }
                            $data->save();
                        }
                    }
                }
            }
        }
        if (isset($request->leave)) {
            foreach ($request->leave as $keys => $values) {
                foreach ($values as $key => $value) {
                    if ($employee = Employee::whereId(request('emp_id'))->first()) {
                        if (
                            !Leave::whereLeave_date($keys)
                                ->whereEmp_id($key)
                                ->whereType(1)
                                ->first()
                        ) {
                            $data = new Leave();
                            $data->emp_id = $key;
                            $emp_req = Employee::whereId($data->emp_id)->first();
                            $data->leave_time = $emp_req->schedules->first()->time_out;
                            $data->leave_date = $keys;
                            // if ($employee->schedules->first()->time_out <= $data->leave_time) {
                            //     $data->status = 1;
                                
                            // }
                            // 
                            $data->save();
                        }
                    }
                }
            }
        }
        flash()->success('Berhasil', 'Kehadiran berhasil disimpan.');
        return back();
    }
    public function sheetReport()
    {

    return view('admin.sheet-report')->with(['employees' => Employee::all()]);
    }

    public function sheetReportExport(Request $request)
    {
        $month = $request->query('month');
        $export = new SheetReportExport($month);
        $filename = $export->filename();

        return Excel::download($export, $filename);
    }

    public function sheetReportExportUser(Request $request)
    {
        $month = $request->query('month');
        $userId = $request->query('user_id');
        $employee = Employee::findOrFail($userId);

        $export = new SheetReportUserExport($month, $employee);
        $filename = $export->filename();

        return Excel::download($export, $filename);
    }
}
