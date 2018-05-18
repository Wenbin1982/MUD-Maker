<?php

namespace App\Http\Controllers\Auth;

use App\Domain;
use App\Http\Controllers\Controller;
use App\Http\Requests\LogInRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

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

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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
        $this->middleware('confirmed')->except('logout');
    }

    public function login(LogInRequest $logInRequest)
    {

        if (!Auth::attempt($logInRequest->only('email', 'password'))) {
            return response(['error' => 'the requested resource was not found'], 401);
        }

        $userCompany = explode("@", $logInRequest->email);
        $domain = Domain::where('domain', array_pop($userCompany))->get()->toArray();

        return response()->json(['user' => User::find(Auth::id()), 'domain'=> $domain]);
    }

}
