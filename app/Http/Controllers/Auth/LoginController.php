<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\SocialAccount;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */


    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    public function showLoginForm()
    {

        return view('public.auth.login');
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {

        $count = $request->cookie('loginAttempt');


        if ($count == null) {
            $count = 1;
        } else {
            $count = $count + 1;
        }


        if (isset($count) && $count > 4) {
            $this->validate($request, [
                'g-recaptcha-response' => 'required|recaptcha',
                'email' => 'required|email|max:100',
                'kodeord' => 'required',
            ]);
        } else {
            $this->validate($request, [
                'email' => 'required|email|max:100',
                'kodeord' => 'required',
            ]);
        }

        // set to true for remember as default!
        if (Auth::attempt(['email' => $request->email, 'password' => $request->kodeord, 'banned_at' => null], true)) {
            // Authentication passed...
            Cookie::queue(Cookie::forget('loginAttempt'));
            return redirect()->intended('/');
        }


        $user = User::where('email', $request->email)->first();
        if (isset($user) && $user->isBanned()) {
            return Redirect::back()->withInput()->with('flash_message', 'Account is Banned!')->cookie('loginAttempt', $count, 10);
        }


        return Redirect::back()->withInput()->with('flash_message', 'Username or password incorrect')->cookie('loginAttempt', $count, 10);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->flush();

        $request->session()->regenerate();

        return redirect('/');
    }
}
