<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Request;


class isActiveUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        // if auth id is null then user is not logged in
        if(Auth::id() == null) {
            // if trying to access the admin location then show and 404 error page to confused randoms trying to find admin endpoints
            if($request->is('admin/*')) {
                abort(404);
            }
            // else show login if user is not logged in
            return redirect('login')->with('flash_message', 'Login');
        }

        // check if user is deleted
        $user = User::withTrashed()->where('id', Auth::id())->first();
        if ($user->deleted_at != null)
        {
            Auth::logout();
            $request->session()->flush();

            $request->session()->regenerate();

            return redirect('login')->with('flash_message', 'Your account '.$user->username.' has been deleted.');
        }

        if ($user->banned_at != null)
        {
            Auth::logout();
            $request->session()->flush();

            $request->session()->regenerate();

            return redirect('login')->with('flash_message', 'Your account '.$user->username.' has been banned.');
        }

        return $next($request);
    }
}
