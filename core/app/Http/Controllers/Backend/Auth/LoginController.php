<?php

namespace App\Http\Controllers\Backend\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
class LoginController extends Controller
{
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
        $this->middleware('guest')->except('logout');
        $this->redirectTo =  route('backend.admin.dashboard') ;
    }
    private function except_role(){
        return array_keys(auth_guard());
    }
    public function showLoginForm()
    {
        return view('backend.auth.login');
    }
    public function username()
    {
        return 'username';
    }

    protected function credentials(Request $request)
    {
        // return $request->only($this->username(), 'password', );
        return ['username' => $request->{$this->username()}, 'password' => $request->password, 'status' => 1];
    }
    
    protected function guard(){
        return Auth::guard('admin');
    }
}
