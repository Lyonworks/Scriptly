<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller {
    public function registerForm() { return view('auth'); }
    public function loginForm() { return view('auth'); }

    public function register(Request $request) {
        $request->validate(['name'=>'required','email'=>'required|email|unique:users','password'=>'required|min:6',]);
        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
        ]);
        Auth::login($user);
        return redirect('/');
    }

    public function login(Request $request) {
        if(Auth::attempt($request->only('email','password'))){
            return redirect('/');
        }
        return back()->withErrors(['email'=>'Invalid credentials']);
    }

    public function logout() {
        Auth::logout();
        return redirect('/');
    }
}
