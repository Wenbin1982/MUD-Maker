<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\UserRequest;
use App\Jobs\SendEmail;
use App\Mail\MailManager;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Domain;

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

    private $activatedMessage = 'The user is already activated or not found';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
        $this->adminEmail = 's.ivankorobchuk@gmail.com';
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
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration (custom implementation).
     *
     * @param Request $request
     */
    public function store(UserRequest $userRequest)
    {
        $data = $userRequest->all();

        $user = User::create([
            'name' => $userRequest->name,
            'email' => $userRequest->email,
            'isAdmin' => 0,
            'password' => bcrypt($userRequest->password),
            'api_token' => str_random(60),
            'registration_key' => base64_encode($userRequest->email),
            'registration_company' => str_random(60),
            'forgot_password_key' => null
        ]);

        $adminEmail = $this->adminEmail;

        if ($this->isRestrictedDomain($userRequest->email)) {
            $data['url'] = URL::to('users/confirmation?key=' . $user->registration_key);
            //send email to user
            $this->dispatch(new SendEmail($data, 'Verify your email address', 'emails.user.welcome'));
        } else {

            $data['confirm_url'] = URL::to('users/register-confirm-admin?key=' . $user->registration_key);
            $data['deny_url'] = URL::to('users/register-deny-admin?key=' . $user->registration_key);
            $data['email'] = $userRequest->email;

            //send email to user
            $this->dispatch(new SendEmail($data, 'Welcome to Mud Maker', 'emails.user.waitActivation'));

            //send email to admin
            $data['email'] = $adminEmail;
            $data['email_user'] = $userRequest->email;
            $this->dispatch(new SendEmail($data, 'New user registration', 'emails.admin.userActivationManager'));

        }

        return [
            'status' => 201,
            'user' => $user
        ];
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    public function isRestrictedDomain($email)
    {
        $is = false;
        $_emailParts = preg_split('/[@]/', $email);
        $domain = Domain::where('domain', 'LIKE', '%' . $_emailParts[1] . '%')->first();
        if ($domain) $is = true;
        return $is;
    }

    public function confirmationUser()
    {
        if (!$this->searchRegisteredUser($key, $user)) {
            return $this->activatedMessage;
        }

        $user->registration_key = null;
        $user->save();
        Auth::login($user);

        return Redirect::to("/");
    }

    public function getRegisterConfirmAdmin()
    {
        if (!$this->searchRegisteredUser($key, $user)) {
            return $this->activatedMessage;
        }

        $user->registration_key = NULL;

        $user->save();

        //send email to user
        $this->dispatch(new SendEmail($user, 'Account activation confirmed', 'emails.user.activation'));

        //send email to admin
        $data['email'] = $this->adminEmail;
        $data['email_user'] = $user->email;
        $this->dispatch(new SendEmail($data, 'Account activation confirmed', 'emails.admin.userActivation'));

        return "User has been successfully activated. Confirmation notice e-mail sent.";
    }

    public function getRegisterDenyAdmin()
    {
        if (!$this->searchRegisteredUser($key, $user)) {
            return $this->activatedMessage;
        }

        //send email to user
        $this->dispatch(new SendEmail($user, 'Account activation denied', 'emails.user.userDenyRegister'));

        //send email to admin
        Mail::to($this->adminEmail)->queue(new MailManager($user, 'Account activation denied', 'emails.admin.userDenyRegister'));


        return "Deny notice e-mail sent.";
    }

    public function searchRegisteredUser(&$key, &$user)
    {
        $key = Input::get('key');
        $user = User::where('registration_key', '=', $key)->first();

        return isset($user);
    }
}
