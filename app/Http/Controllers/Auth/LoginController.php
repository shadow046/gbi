<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\UserLog;
use App\Models\VerifyUser;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Auth;
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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
   
    
    public function authenticated(Request $request, $user) {
        if(auth()->user()->status != 1) {
            Auth::logout();
            return redirect('login')->withErrors(['Your account is inactive']);
        }
        $log = new UserLog;
        $log->activity = "Sign-in.";
        $log->user_id = auth()->user()->id;
        $log->fullname = auth()->user()->name;
        $log->save();
        if (Hash::check('uwuqQ6NP?3E4',auth()->user()->password)) {
            return redirect()->to('change-password')->with('success', 'Please create a new password for your account.');
        }
    }
}
