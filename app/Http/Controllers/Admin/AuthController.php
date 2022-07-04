<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\AdminUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    private $adminUser;

    public function __construct(AdminUser $adminUser)
    {
        $this->adminUser = $adminUser;
    }

    public function login() {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard.index');
        }
        return view('admin.components.auth.login');
    }

    public function loginPost(Request $request)
    {
        $rules = [
            'email' => 'required|email:filter',
            'password' => 'required',
        ];

        $customMessages = [
            'email.required' => 'Vui lòng nhập Email',
            'email.email' => 'Email không đúng định dạng',
            'password.required' => 'Vui lòng nhập Mật khẩu',
        ];

        $this->validate($request, $rules, $customMessages);

        $data = $request->all();

        // Check user exist
        $getUser = $this->adminUser->where('email', $data['email'])->first();
        $isLoginSuccess = false;
        if (isset($getUser) && $getUser->count() > 0)
        {
            // Check user status
            if ($getUser->status == 1)
            {
                // Check email and password correct -> login
                if (Auth::guard('admin')->attempt([
                    'email' => $data['email'],
                    'password' => $data['password']
                ])) {
                    $isLoginSuccess = true;
                } else {
                    $isLoginSuccess = false;
                }
            }
            else
            {
                $isLoginSuccess = false;
            }
        }
        else
        {
            $isLoginSuccess = false;
        }

        if ($isLoginSuccess == true)
        {
            return redirect()->route('admin.dashboard.index');
        }
        else
        {
            Session::flash('login_error_message', 'Email hoặc Mật khẩu không chính xác');
            return redirect()->back()->withInput();
        }
    }

    public function logout() {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.auth.login');
    }
}
