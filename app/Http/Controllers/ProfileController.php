<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        $employee = Employee::where('email', $user->email)->first();

        return view('user.profile')->with([
            'user' => $user,
            'employee' => $employee,
        ]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:25'],
            'address' => ['required', 'string', 'max:500'],
            'birth_date' => ['required', 'date'],
            'institution' => ['required', 'string', 'max:255'],
            'position' => ['required', 'string', 'max:255'],
            'major' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        $oldEmail = $user->email;

        $user->name = $request->name;
        $user->phone_number = $request->phone_number;
        $user->address = $request->address;
        $user->birth_date = $request->birth_date;
        $user->institution = $request->institution;
        $user->email = $request->email;
        $user->save();

        $employee = Employee::where('email', $oldEmail)->first();
        if (!$employee) {
            $employee = Employee::where('email', $user->email)->first();
        }
        if (!$employee) {
            $employee = new Employee();
        }

        $employee->name = $request->name;
        $employee->email = $request->email;
        $employee->phone_number = $request->phone_number;
        $employee->address = $request->address;
        $employee->birth_date = $request->birth_date;
        $employee->institution = $request->institution;
        $employee->position = $request->position;
        $employee->major = $request->major;
        $employee->save();

        flash()->success('Berhasil', 'Profil berhasil diperbarui.');
        return redirect()->route('user.profile');
    }
}
