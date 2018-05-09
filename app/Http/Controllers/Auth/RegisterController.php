<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Worker;
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
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
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
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:worker',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Worker
     */
    protected function create(array $data)
    {
        $worker = Worker::create([
            'name' => $data['name'],
            'surname' => $data['surname'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'birthdate' => $data['birthdate'],
        ]);
        if (isset($data['skills']) && count($data['skills'])) {
            foreach ($data['skills'] as $skill) {
                if ($skill)
                    $worker->skills()->attach($skill, ['value' => 2.5]);
            }
        }
        return $worker;
    }

    public function showRegistrationForm()
    {
        $skills = \App\Skill::all();
        $data = [
            'skills' => $skills,
        ];

        return view('auth.register')->with($data);
    }
}
