<?php

namespace App\Http\Controllers\Auth;

use App\SocialAccount;
use App\User;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

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

    public function showRegistrationForm()
    {
        return view('public.auth.register');
    }

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
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'brugernavn' => 'required|max:63|unique:users,username',
            'email' => 'required|email|max:100|unique:users',
            'kodeord' => 'required|min:6|confirmed',
//           'Recaptcha' => 'required|recaptcha',
        ]);
    }
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return User
     */
    protected function create(array $data)
    {
//
            $user = User::create([
                'username' => $data['brugernavn'],
                'email' => $data['email'],
                'password' => bcrypt($data['kodeord']),
            ]);

            return $user;
    }
}