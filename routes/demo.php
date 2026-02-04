<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Collection;
use Carbon\Carbon;

if (!function_exists('demo_object')) {
    function demo_object(array $data)
    {
        return (object) $data;
    }
}

if (!function_exists('demo_schedule')) {
    function demo_schedule(int $id, string $slug, string $timeIn, string $timeOut)
    {
        return demo_object([
            'id' => $id,
            'slug' => $slug,
            'time_in' => $timeIn,
            'time_out' => $timeOut,
        ]);
    }
}

if (!function_exists('demo_employee')) {
    function demo_employee(int $id, string $name, Collection $schedules)
    {
        return demo_object([
            'id' => $id,
            'name' => $name,
            'phone_number' => '0812-0000-0000',
            'address' => 'Jakarta',
            'birth_date' => '1998-06-04',
            'institution' => 'Teknik Informatika',
            'position' => 'Admin',
            'major' => 'Sistem Informasi',
            'email' => 'steven@example.com',
            'created_at' => now()->subMonths(3)->format('Y-m-d'),
            'schedules' => $schedules,
        ]);
    }
}

$demoMode = true;

$scheduleA = demo_schedule(1, 'Shift A', '08:00', '17:00');
$scheduleB = demo_schedule(2, 'Shift B', '09:00', '18:00');
$schedules = collect([$scheduleA, $scheduleB]);

$employeeSteven = demo_employee(1, 'Steven', collect([$scheduleA]));
$employees = collect([$employeeSteven]);

$demoUser = demo_object([
    'id' => 1,
    'name' => 'Steven',
    'email' => 'steven@example.com',
    'phone_number' => '0812-0000-0000',
    'address' => 'Jakarta',
    'birth_date' => '1998-06-04',
    'institution' => 'Teknik Informatika',
]);

$weekKey = now()->format('Y-W');
$groupedLogs = [
    $weekKey => [
        [
            'date' => now()->subDays(2)->format('Y-m-d'),
            'emp_id' => 'EMP-001',
            'name' => 'Steven',
            'type' => 'Time In',
            'time' => '08:07',
            'status' => 'Late',
            'diff_seconds' => 420,
            'duration' => null,
            'note' => 'Macet',
        ],
        [
            'date' => now()->subDays(2)->format('Y-m-d'),
            'emp_id' => 'EMP-001',
            'name' => 'Steven',
            'type' => 'Time Out',
            'time' => '17:08',
            'status' => 'On Time',
            'diff_seconds' => 480,
            'duration' => null,
            'note' => null,
        ],
    ],
];

$demoUserLogs = collect([
    demo_object([
        'datetime' => now()->subDays(1)->format('Y-m-d') . ' 08:02',
        'type' => 'Time In',
        'note' => 'Macet',
    ]),
    demo_object([
        'datetime' => now()->subDays(1)->format('Y-m-d') . ' 17:03',
        'type' => 'Time Out',
        'note' => null,
    ]),
    demo_object([
        'datetime' => now()->subDays(2)->format('Y-m-d') . ' 08:00',
        'type' => 'Time In',
        'note' => null,
    ]),
]);

$demoUserGroupedLogs = [
    now()->format('Y-W') => [
        [
            'datetime' => now()->subDays(1)->format('Y-m-d') . ' 08:02',
            'type' => 'Time In',
            'note' => 'Macet',
        ],
        [
            'datetime' => now()->subDays(1)->format('Y-m-d') . ' 17:03',
            'type' => 'Time Out',
            'note' => null,
        ],
    ],
];

$latetimes = collect([
    demo_object([
        'latetime_date' => now()->subDays(1)->format('Y-m-d'),
        'emp_id' => 'EMP-001',
        'duration' => '00:12:00',
        'employee' => $employeeSteven,
    ]),
]);

$leaves = collect([
    demo_object([
        'leave_date' => now()->subDays(1)->format('Y-m-d'),
        'emp_id' => 'EMP-001',
        'leave_time' => '16:40:00',
        'note' => 'Pulang lebih awal',
        'employee' => $employeeSteven,
    ]),
]);

$overtimes = collect([
    demo_object([
        'overtime_date' => now()->subDays(3)->format('Y-m-d'),
        'emp_id' => 'EMP-001',
        'duration' => '01:20:00',
        'employee' => $employeeSteven,
    ]),
]);

$thisMonthTotal = 120;
$thisMonthOnTime = 98;
$thisMonthLate = 14;
$thisMonthSick = 4;
$thisMonthLeave = 2;
$thisMonthNoNote = 2;
$lastMonthTotal = 110;
$data = [$employees->count(), 14, 6, 82];
$chartLabels = ['1', '5', '10', '15', '20', '25', '30'];
$chartSeries = [3, 6, 5, 9, 10, 8, 12];

$reportEmployee = $employeeSteven;
$reportUploader = demo_object([
    'name' => 'Steven',
]);

$reports = collect([
    demo_object([
        'id' => 1,
        'created_at' => now()->subDays(5),
        'employee' => $reportEmployee,
        'uploadedBy' => $reportUploader,
        'file_name' => 'Laporan-Februari-2026.pdf',
        'file_size' => 834532,
        'report_month' => now()->format('Y-m'),
    ]),
]);

$userReports = collect([
    demo_object([
        'id' => 1,
        'created_at' => now()->subDays(10),
        'file_name' => 'Monthly-Report-Steven-Feb-2026.pdf',
        'file_size' => 632144,
        'report_month' => now()->format('Y-m'),
    ]),
]);

$finalReports = collect([
    demo_object([
        'id' => 1,
        'created_at' => now()->subDays(4),
        'file_name' => 'Final-Report-Steven.pdf',
        'file_size' => 1322144,
    ]),
]);

$lastFinalReport = $finalReports->first();

$lastAttendance = demo_object([
    'attendance_date' => now()->format('Y-m-d'),
    'attendance_time' => '08:02:00',
]);
$lastLeave = demo_object([
    'leave_date' => now()->format('Y-m-d'),
    'leave_time' => '17:03:00',
]);
$hasAttendance = true;
$hasLeave = false;

$demoDates = [
    now()->startOfMonth()->addDays(1)->format('Y-m-d'),
    now()->startOfMonth()->addDays(3)->format('Y-m-d'),
    now()->startOfMonth()->addDays(6)->format('Y-m-d'),
];

$demoAttendance = [
    $employeeSteven->id => [
        $demoDates[0] => [
            'status' => 1,
            'attendance_time' => '08:00:00',
            'status_type' => 'hadir',
            'note' => '',
        ],
        $demoDates[1] => [
            'status' => 0,
            'attendance_time' => '08:12:00',
            'status_type' => null,
            'note' => 'Macet',
        ],
        $demoDates[2] => [
            'status' => 1,
            'attendance_time' => '08:01:00',
            'status_type' => 'sakit',
            'note' => 'Flu ringan',
        ],
    ],
];

$demoLeaves = [
    $employeeSteven->id => [
        $demoDates[0] => [
            'status' => 1,
            'leave_time' => '17:05:00',
            'note' => '',
        ],
        $demoDates[1] => [
            'status' => 0,
            'leave_time' => '16:45:00',
            'note' => 'Izin pulang cepat',
        ],
    ],
];

$fingerDevices = collect([
    demo_object([
        'id' => 1,
        'name' => 'Device Lab',
        'ip' => '192.168.1.20',
        'serialNumber' => 'SN-STE-001',
        'status' => true,
    ]),
]);

$fingerDevice = $fingerDevices->first();

Route::get('/', function () {
    return redirect('/admin');
});

Route::get('/admin', function () use (
    $demoMode,
    $data,
    $chartLabels,
    $chartSeries,
    $thisMonthTotal,
    $thisMonthOnTime,
    $thisMonthLate,
    $thisMonthSick,
    $thisMonthLeave,
    $thisMonthNoNote,
    $lastMonthTotal
) {
    return view('admin.index', compact(
        'demoMode',
        'data',
        'chartLabels',
        'chartSeries',
        'thisMonthTotal',
        'thisMonthOnTime',
        'thisMonthLate',
        'thisMonthSick',
        'thisMonthLeave',
        'thisMonthNoNote',
        'lastMonthTotal'
    ));
})->name('admin');

Route::get('/employees', function () use ($demoMode, $employees, $schedules) {
    return view('admin.employee', compact('demoMode', 'employees', 'schedules'));
})->name('employees.index');

Route::get('/schedule', function () use ($demoMode, $schedules) {
    return view('admin.schedule', compact('demoMode', 'schedules'));
})->name('schedule.index');

Route::get('/attendance', function () use ($demoMode, $groupedLogs) {
    return view('admin.attendance', compact('demoMode', 'groupedLogs'));
})->name('attendance');

Route::get('/latetime', function () use ($demoMode, $latetimes) {
    return view('admin.latetime', compact('demoMode', 'latetimes'));
})->name('indexLatetime');

Route::get('/leave', function () use ($demoMode, $leaves) {
    return view('admin.leave', compact('demoMode', 'leaves'));
})->name('leave');

Route::get('/overtime', function () use ($demoMode, $overtimes) {
    return view('admin.overtime', compact('demoMode', 'overtimes'));
})->name('indexOvertime');

Route::get('/check', function () use ($demoMode, $employees, $demoAttendance, $demoLeaves) {
    return view('admin.check', compact('demoMode', 'employees', 'demoAttendance', 'demoLeaves'));
})->name('check');

Route::post('/check-store', function () {
    return redirect('/check');
})->name('check_store');

Route::get('/sheet-report', function () use ($demoMode, $employees, $demoAttendance, $demoLeaves) {
    return view('admin.sheet-report', compact('demoMode', 'employees', 'demoAttendance', 'demoLeaves'));
})->name('sheet-report');

Route::get('/sheet-report/export', function () {
    return redirect('/sheet-report');
})->name('sheet-report.export');

Route::get('/sheet-report/export-user', function () {
    return redirect('/sheet-report');
})->name('sheet-report.export-user');

Route::get('/monthly-report', function () use ($demoMode, $reports) {
    return view('admin.monthly-report', compact('demoMode', 'reports'));
})->name('monthly-report.index');

Route::get('/monthly-report/{monthlyReport}/download', function () {
    return redirect('/monthly-report');
})->name('monthly-report.download');

Route::get('/monthly-report/export/{month}', function () {
    return redirect('/monthly-report');
})->name('monthly-report.export');

Route::get('/final-report', function () use ($demoMode, $reports) {
    return view('admin.final-report', compact('demoMode', 'reports'));
})->name('final-report.index');

Route::get('/final-report/{finalReport}/download', function () {
    return redirect('/final-report');
})->name('final-report.download');

Route::get('/final-report/export/{month}', function () {
    return redirect('/final-report');
})->name('final-report.export');

Route::get('/finger-devices', function () use ($demoMode, $fingerDevices) {
    return view('admin.fingerDevices.index', [
        'demoMode' => $demoMode,
        'devices' => $fingerDevices,
    ]);
})->name('finger_device.index');

Route::get('/finger-devices/create', function () {
    return view('admin.fingerDevices.create');
})->name('finger_device.create');

Route::get('/finger-devices/{fingerDevice}', function () use ($demoMode, $fingerDevice) {
    return view('admin.fingerDevices.show', compact('demoMode', 'fingerDevice'));
})->name('finger_device.show');

Route::get('/finger-devices/{fingerDevice}/edit', function () use ($demoMode, $fingerDevice) {
    return view('admin.fingerDevices.edit', compact('demoMode', 'fingerDevice'));
})->name('finger_device.edit');

Route::post('/finger-devices', function () {
    return redirect('/finger-devices');
})->name('finger_device.store');

Route::put('/finger-devices/{fingerDevice}', function () {
    return redirect('/finger-devices');
})->name('finger_device.update');

Route::delete('/finger-devices/{fingerDevice}', function () {
    return redirect('/finger-devices');
})->name('finger_device.destroy');

Route::get('/finger-devices/{fingerDevice}/clear', function () {
    return redirect('/finger-devices');
})->name('finger_device.clear.attendance');

Route::get('/finger-devices/{fingerDevice}/add-employee', function () {
    return redirect('/finger-devices');
})->name('finger_device.add.employee');

Route::get('/finger-devices/{fingerDevice}/get-attendance', function () {
    return redirect('/finger-devices');
})->name('finger_device.get.attendance');

Route::post('/finger-devices/mass-destroy', function () {
    return redirect('/finger-devices');
})->name('admin.finger_device.massDestroy');

Route::post('/employees', function () {
    return redirect('/employees');
})->name('employees.store');

Route::put('/employees/{employee}', function () {
    return redirect('/employees');
})->name('employees.update');

Route::delete('/employees/{employee}', function () {
    return redirect('/employees');
})->name('employees.destroy');

Route::post('/schedule', function () {
    return redirect('/schedule');
})->name('schedule.store');

Route::put('/schedule/{schedule}', function () {
    return redirect('/schedule');
})->name('schedule.update');

Route::delete('/schedule/{schedule}', function () {
    return redirect('/schedule');
})->name('schedule.destroy');

Route::post('/logout', function () {
    return redirect('/');
})->name('logout');

Route::get('/home', function () use (
    $demoMode,
    $demoUser,
    $lastAttendance,
    $lastLeave,
    $hasAttendance,
    $hasLeave,
    $demoUserLogs,
    $demoUserGroupedLogs
) {
    return view('user.attendance', [
        'demoMode' => $demoMode,
        'user' => $demoUser,
        'lastAttendance' => $lastAttendance,
        'lastLeave' => $lastLeave,
        'hasAttendance' => $hasAttendance,
        'hasLeave' => $hasLeave,
        'logs' => $demoUserLogs,
        'groupedLogs' => $demoUserGroupedLogs,
    ]);
})->name('home');

Route::post('/home/time-in', function () {
    return redirect('/home');
})->name('home.timein.store');

Route::post('/home/time-out', function () {
    return redirect('/home');
})->name('home.timeout.store');

Route::get('/user/profile', function () use ($demoMode, $demoUser, $employeeSteven) {
    return view('user.profile', [
        'demoMode' => $demoMode,
        'user' => $demoUser,
        'employee' => $employeeSteven,
    ]);
})->name('user.profile');

Route::post('/user/profile', function () {
    return redirect('/user/profile');
})->name('user.profile.update');

Route::get('/user/monthly-report', function () use ($demoMode, $demoUser, $userReports) {
    return view('user.monthly-report', [
        'demoMode' => $demoMode,
        'user' => $demoUser,
        'reports' => $userReports,
    ]);
})->name('user.monthly-report');

Route::post('/monthly-report', function () {
    return redirect('/user/monthly-report');
})->name('monthly-report.store');

Route::get('/monthly-report/{monthlyReport}/download', function () {
    return redirect('/user/monthly-report');
})->name('monthly-report.download');

Route::get('/user/final-report', function () use ($demoMode, $demoUser, $finalReports, $lastFinalReport) {
    return view('user.final-report', [
        'demoMode' => $demoMode,
        'user' => $demoUser,
        'reports' => $finalReports,
        'lastFinalReport' => $lastFinalReport,
    ]);
})->name('user.final-report');

Route::post('/final-report', function () {
    return redirect('/user/final-report');
})->name('final-report.store');

Route::get('/final-report/{finalReport}/download', function () {
    return redirect('/user/final-report');
})->name('final-report.download');
