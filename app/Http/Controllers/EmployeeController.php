<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use App\Models\Role;
use App\Models\Schedule;
use App\Http\Requests\EmployeeRec;
use RealRashid\SweetAlert\Facades\Alert;

class EmployeeController extends Controller
{
   
    public function index()
    {
        $employees = Employee::all();
        $usersByEmail = User::all()->keyBy('email');

        foreach ($employees as $employee) {
            $user = $employee->email ? ($usersByEmail[$employee->email] ?? null) : null;
            if (!$user) {
                continue;
            }

            $needsUpdate = false;
            if (!$employee->phone_number && $user->phone_number) {
                $employee->phone_number = $user->phone_number;
                $needsUpdate = true;
            }
            if (!$employee->address && $user->address) {
                $employee->address = $user->address;
                $needsUpdate = true;
            }
            if (!$employee->birth_date && $user->birth_date) {
                $employee->birth_date = $user->birth_date;
                $needsUpdate = true;
            }
            if (!$employee->institution && $user->institution) {
                $employee->institution = $user->institution;
                $needsUpdate = true;
            }

            if ($needsUpdate) {
                $employee->save();
            }
        }

        return view('admin.employee')->with(['employees'=> $employees, 'schedules'=>Schedule::all()]);
    }

    public function store(EmployeeRec $request)
    {
        $request->validated();

        $employee = new Employee;
        $employee->name = $request->name;
        $employee->phone_number = $request->phone_number;
        $employee->address = $request->address;
        $employee->birth_date = $request->birth_date;
        $employee->institution = $request->institution;
        $employee->position = $request->position;
        $employee->major = $request->major;
        $employee->email = $request->email;
        $employee->pin_code = bcrypt($request->pin_code);
        $employee->save();

        if($request->schedule){

            $schedule = Schedule::whereSlug($request->schedule)->first();

            $employee->schedules()->attach($schedule);
        }

        // $role = Role::whereSlug('emp')->first();

        // $employee->roles()->attach($role);

        flash()->success('Berhasil','Data karyawan berhasil dibuat.');

        return redirect()->route('employees.index')->with('success');
    }

 
    public function update(EmployeeRec $request, Employee $employee)
    {
        $request->validated();

        $employee->name = $request->name;
        $employee->phone_number = $request->phone_number;
        $employee->address = $request->address;
        $employee->birth_date = $request->birth_date;
        $employee->institution = $request->institution;
        $employee->position = $request->position;
        $employee->major = $request->major;
        $employee->email = $request->email;
        $employee->pin_code = bcrypt($request->pin_code);
        $employee->save();

        if ($request->schedule) {

            $employee->schedules()->detach();

            $schedule = Schedule::whereSlug($request->schedule)->first();

            $employee->schedules()->attach($schedule);
        }

        flash()->success('Berhasil','Data karyawan berhasil diperbarui.');

        return redirect()->route('employees.index')->with('success');
    }


    public function destroy(Employee $employee)
    {
        $employee->delete();
        flash()->success('Berhasil','Data karyawan berhasil dihapus.');
        return redirect()->route('employees.index')->with('success');
    }
}
