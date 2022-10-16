<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(){
        $this->data['headline'] = 'login';
        return view('login.form',$this->data);
    }


    public function confirm(LoginRequest $request)
    {
        // $credentials = $request->validate([
        //     'email' => ['required', 'email'],
        //     'password' => ['required'],
        // ]);
        // $data = $request->only('email','password');
 
        // if (Auth::attempt($data)) {
        //     // $request->session()->regenerate();
        //     return redirect()->intended('dashboard');
        // }
 
        // return back()->withErrors([
        //     'email' => 'The provided credentials do not match our records.',
        // ])->onlyInput('email');
        $data = $request->only('email', 'password');

    	if (Auth::attempt($data)) {
    		return redirect()->intended('dashboard');
    	} else {
    		return redirect()->route('login')->withErrors(['Invalid username and password']);
    	}
    
    }
}
