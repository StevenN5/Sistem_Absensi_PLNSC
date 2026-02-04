<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\Employee;
use App\Models\Role;
use App\Models\Schedule;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    protected function redirectTo()
    {
        return '/home';
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:25'],
            'address' => ['required', 'string', 'max:500'],
            'birth_date' => ['required', 'date'],
            'institution' => ['required', 'string', 'max:255'],
            'position' => ['required', 'string', 'max:255'],
            'major' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone_number' => $data['phone_number'],
            'address' => $data['address'],
            'birth_date' => $data['birth_date'],
            'institution' => $data['institution'],
            'password' => Hash::make($data['password']),
        ]);

        // Default new registrations to user role (non-admin).
        $role = Role::firstOrCreate(
            ['slug' => 'user'],
            ['name' => 'User']
        );
        $user->roles()->syncWithoutDetaching([$role->id]);

        $employee = Employee::where('email', $data['email'])->first();
        if (!$employee) {
            $employee = new Employee();
            $employee->email = $data['email'];
        }
        $employee->name = $data['name'];
        $employee->position = $data['position'];
        $employee->major = $data['major'];
        $employee->phone_number = $data['phone_number'];
        $employee->address = $data['address'];
        $employee->birth_date = $data['birth_date'];
        $employee->institution = $data['institution'];
        $employee->save();

        $defaultSchedule = Schedule::where('time_in', '08:00')
            ->where('time_out', '16:30')
            ->first();
        if (!$defaultSchedule) {
            $defaultSchedule = Schedule::first();
        }
        if (!$defaultSchedule) {
            $defaultSchedule = new Schedule();
            $defaultSchedule->slug = 'Default 08:00-16:30';
            $defaultSchedule->time_in = '08:00';
            $defaultSchedule->time_out = '16:30';
            $defaultSchedule->save();
        }
        $employee->schedules()->sync([$defaultSchedule->id]);

        return $user;
    }
}
