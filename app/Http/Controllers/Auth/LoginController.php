<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Soap\Soap;
use Illuminate\Support\Facades\Auth;

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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }
        
        if($this->checkUser($request)) {

            if ($this->attemptLogin($request)) {
                return $this->sendLoginResponse($request);
            }


            // Auth::attempt();
            // Auth::login($user);
            // Auth::setUser($user);
            // $request->session()->regenerate();
            // $this->clearLoginAttempts($request);
            // return redirect(route('root'));

            // return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    public function checkUser(Request $request)
    {
        $soap = new Soap("CP_User");
        $user = $soap->ReadMultiple(1, ['Email' => $request->email]);
        if (empty($user->ReadMultiple_Result->CP_User)) {
            return false;
        }
        $user = $user->ReadMultiple_Result->CP_User;

        $modelUser = User::where('email', $request->email)->first();
        if ($modelUser == null) {
            if (!password_verify($request->password, $user->Password)) {
                return false;
            } else {
                $modelUser = new User();
                $modelUser->id = $user->Id;
                $modelUser->name = $user->Name;
                $modelUser->email = $user->Email;
                $modelUser->password = $user->Password;
                $modelUser->admin = $user->Admin;
                $modelUser->save();
            }
        }
        
        return true;
    }
}
