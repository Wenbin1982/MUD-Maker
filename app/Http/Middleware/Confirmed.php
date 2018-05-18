<?php

namespace App\Http\Middleware;

use App\Domain;
use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class Confirmed
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
        $user = User::where('email', $request->email)->first();

        if (!is_null($user)) {
            if ($user->registration_key !== null) {
                return response(['noConfirm' => true], 401);
            }
            if($user->forgot_password_key !== null) {
                return response(['changePass' => true], 401);
            }
        }

        return $next($request);
    }
}
