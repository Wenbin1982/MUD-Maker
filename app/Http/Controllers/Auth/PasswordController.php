<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\ForgotPassRequest;
use App\Mail\MailManager;
use App\User;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class PasswordController extends Controller
{
    use ResetsPasswords, ValidatesRequests;

    protected $redirectTo = '/site';
    private $passMessage = 'Link was used';

    public function __construct()
    {
        $this->middleware('guest');
    }

    private function sendEmailRestPassword($forgotPassRequest, $url)
    {
        $email = $forgotPassRequest->email;

        $user = User::where([
            ['email', '=', $email],
            ['registration_key', '=', NULL]
        ])->first();


        if (!$user) {
            return abort(400, 'Account was not activated or not found');
        }

        $forgotPasswordKey = uniqid();
        $user->forgot_password_key = $forgotPasswordKey;

        $data['url'] = URL::to($url . $forgotPasswordKey);

        //send email to user
        Mail::to($email)->queue(new MailManager($data,
            'Reset password',
            'emails.user.resetPassword'));

        $user->save();

    }

    public function postResetPassword(ForgotPassRequest $forgotPassRequest)
    {
        $this->sendEmailRestPassword($forgotPassRequest, 'reset-password-user/');
    }


    public function show($key) {
        $user = User::where('forgot_password_key', $key)->first();
        if (!isset($user)) return Redirect::to('/login');
        return view('main.index');
    }

    public function getAccessUser(Request $request, $key)
    {
        $user = User::where('forgot_password_key', $key)->first();
        if (!isset($user)) return Redirect::to('/login');
        $user->password = Hash::make($request->password);
        $user->forgot_password_key = NULL;
        $user->update();

        return response()->json($user->forgot_password_key);
    }

    public function searchRegisteredUser(&$key, &$user)
    {
        $key = Input::get('key');
        $user = User::where('forgot_password_key', '=', $key)->first();

        return isset($user);
    }

    public function sendResetLinkEmail(ForgotPassRequest $forgotPassRequest)
    {
        $this->sendEmailRestPassword($forgotPassRequest, 'api/password/reset-form?key=');
        return Redirect()->route('password.request')->with('status', 'We have e-mailed your password reset link!');
    }

    public function showResetForm()
    {
        $key = Input::get('key');
        return view('auth.passwords.reset', [
            'pass_key' => $key
        ]);
    }

    public function resetAdminPass(ForgotPassRequest $forgotPassRequest)
    {
        $user = User::where('forgot_password_key', '=', $forgotPassRequest->key)->first();
        $user->forgot_password_key = NULL;
        $user->password = bcrypt($forgotPassRequest->password);
        $user->save();
        Auth::login($user);
        return Redirect::to('users');
    }
}
