<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    public function login()
    {
        return view('admin.pages.login');
    }

    public function dashboard()
    {
        if (Auth::check()) {
            return view('admin.layout.index');
        } else {
            Session::flash('error', 'Please log in to access the dashboard.');
            return redirect()->route('login');
        }
    }
    public function login_process(Request $request)
    {
        try {
            $credentials = $request->only("email", "password");
            if (Auth::attempt($credentials) && Auth::user()->user_type == 'admin') {
                Session::flash('message', 'Login successful.');
                return redirect()->intended('/dashboard');
            } else {
                Session::flash('error', 'Your email or password is incorrect.');
                return redirect()->back();
            }
        } catch (\Throwable $th) {
            return back()->with("error", $th->getMessage());
        }
    }
    public function signout()
    {
        $logout = Auth::logout();
        Session::flash('message', 'You have been logged out.');
        return redirect('/login');
    }
}
