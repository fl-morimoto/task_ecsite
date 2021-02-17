<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;            //add
use Illuminate\Support\Facades\Auth;    //add

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = 'admin/home';

    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
    }
    public function showLoginForm()
    {
        //login.blade.phpは共用
        return view('auth.login');
    }
    protected function guard()
    {
        return Auth::guard('admin');
    }
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        return redirect('admin/login');
    }
}

