<?php



use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FingerDevicesControlller;

if (env('APP_DEMO_MODE')) {
    require __DIR__ . '/demo.php';
    return;
}

Route::get('/', function () {
    return redirect()->route('login');
})->name('welcome');
Route::get('attended/{user_id}', '\App\Http\Controllers\AttendanceController@attended' )->name('attended');
Route::get('attended-before/{user_id}', '\App\Http\Controllers\AttendanceController@attendedBefore' )->name('attendedBefore');
Auth::routes(['register' => true, 'reset' => false]);

Route::group(['middleware' => ['auth', 'Role'], 'roles' => ['admin']], function () {
    Route::resource('/employees', '\App\Http\Controllers\EmployeeController');
    Route::resource('/employees', '\App\Http\Controllers\EmployeeController');
    Route::get('/attendance', '\App\Http\Controllers\AttendanceController@index')->name('attendance');
  
    Route::get('/latetime', '\App\Http\Controllers\AttendanceController@indexLatetime')->name('indexLatetime');
    Route::get('/leave', '\App\Http\Controllers\LeaveController@index')->name('leave');
    Route::get('/overtime', '\App\Http\Controllers\LeaveController@indexOvertime')->name('indexOvertime');

    Route::get('/admin', '\App\Http\Controllers\AdminController@index')->name('admin');

    Route::resource('/schedule', '\App\Http\Controllers\ScheduleController');

    Route::get('/check', '\App\Http\Controllers\CheckController@index')->name('check');
    Route::get('/sheet-report', '\App\Http\Controllers\CheckController@sheetReport')->name('sheet-report');
    Route::get('/sheet-report/export', '\App\Http\Controllers\CheckController@sheetReportExport')->name('sheet-report.export');
    Route::get('/sheet-report/export-user', '\App\Http\Controllers\CheckController@sheetReportExportUser')->name('sheet-report.export-user');
    Route::post('check-store','\App\Http\Controllers\CheckController@CheckStore')->name('check_store');
    Route::get('/final-report', '\App\Http\Controllers\FinalReportController@index')->name('final-report.index');
    Route::get('/final-report/export/{month}', '\App\Http\Controllers\FinalReportController@exportMonthZip')->name('final-report.export');
    Route::get('/monthly-report', '\App\Http\Controllers\MonthlyReportController@index')->name('monthly-report.index');
    Route::get('/monthly-report/export/{month}', '\App\Http\Controllers\MonthlyReportController@exportMonthZip')->name('monthly-report.export');
    

});

Route::group(['middleware' => ['auth']], function () {
    Route::get('/home', '\App\Http\Controllers\UserAttendanceController@index')->name('home');
    Route::post('/home/time-in', '\App\Http\Controllers\UserAttendanceController@store')->name('home.timein.store');
    Route::post('/home/time-out', '\App\Http\Controllers\UserAttendanceController@storeLeave')->name('home.timeout.store');
    Route::post('/final-report', '\App\Http\Controllers\FinalReportController@store')->name('final-report.store');
    Route::get('/final-report/{finalReport}/download', '\App\Http\Controllers\FinalReportController@download')->name('final-report.download');
    Route::get('/user/final-report', '\App\Http\Controllers\FinalReportController@userIndex')->name('user.final-report');
    Route::post('/monthly-report', '\App\Http\Controllers\MonthlyReportController@store')->name('monthly-report.store');
    Route::get('/monthly-report/{monthlyReport}/download', '\App\Http\Controllers\MonthlyReportController@download')->name('monthly-report.download');
    Route::get('/user/monthly-report', '\App\Http\Controllers\MonthlyReportController@userIndex')->name('user.monthly-report');
    Route::get('/user/profile', '\App\Http\Controllers\ProfileController@edit')->name('user.profile');
    Route::post('/user/profile', '\App\Http\Controllers\ProfileController@update')->name('user.profile.update');

    Route::get('/user/attendance', function () {
        return redirect()->route('home');
    })->name('user.attendance.index');
    Route::post('/user/attendance', '\App\Http\Controllers\UserAttendanceController@store')->name('user.attendance.store');
});

Route::get('lang/{locale}', function ($locale) {
    $allowed = ['id'];
    if (!in_array($locale, $allowed, true)) {
        abort(404);
    }
    session(['locale' => $locale]);
    return back();
})->name('lang.switch');

// Route::get('/attendance/assign', function () {
//     return view('attendance_leave_login');
// })->name('attendance.login');

// Route::post('/attendance/assign', '\App\Http\Controllers\AttendanceController@assign')->name('attendance.assign');


// Route::get('/leave/assign', function () {
//     return view('attendance_leave_login');
// })->name('leave.login');

// Route::post('/leave/assign', '\App\Http\Controllers\LeaveController@assign')->name('leave.assign');


// Route::get('{any}', 'App\http\controllers\VeltrixController@index');
