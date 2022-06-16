<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckLogin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function index()
    {
        return  view('login');
    }


    public function Login(CheckLogin $request) {
        $username = $request->input('name');
        $password = $request->input('password');
        if (Auth::attempt(['name' => $username, 'password' => $password])){
            Session::put('name', $username);
            return view('dashboard');
        }

        else {
            return back()->with(['msg' => 'Tên tài khoản hoặc mật không không chính xác mời bạn nhập lại']);
        }

    }

    public function Signup() {
        return view('signup');
    }

    public function Register(Request $request) {
        $username = $request->input('name');
        $email = $request->input('email');
        $password = Hash::make($request->input('password'));
        User::create([
            'name' => $username,
            'email' => $email,
            'password' => $password,
        ]);

        return "Đăng ký thành công";
    }

    public function Logout() {
        Session::forget('name');
        return redirect()->route('login');
    }
}
