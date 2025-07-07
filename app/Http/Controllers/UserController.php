<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserController extends Controller
{
    public function showCorrectHomepage()
    {
        if (Auth::check()) {
            return view('homepage-feed');
        } 

        return view('homepage');
    }
    public function register(Request $request) 
    {
        $incomingFields = $request->validate([
            'username' => ['required', 'min:3', 'max:20', Rule::unique('users', 'username')],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'min:4', 'confirmed']
        ]);

        $user = User::create($incomingFields);
        Auth::login($user);

        return redirect('/')->with('success', 'Thank you for registering.');
    }

    public function login(Request $request) 
    {
        $incomingFields = $request->validate([
            'loginusername' => 'required',
            'loginpassword' => 'required'
        ]);

        $fields = [
            'username' => $incomingFields['loginusername'],
            'password' => $incomingFields['loginpassword']
        ];

        if (Auth::attempt($fields, true)) {
            $request->session()->regenerate();
            return redirect('/')->with('success', 'You have successfully logged in.');
        }

        return redirect('/')->with('failure', 'Invalid login.');
    }

    public function logout() {
        Auth::logout();

        return redirect('/')->with('success', 'You are now logged out.');
    }
    
    public function profile(User $user)
    {
        return view('profile-posts', [
            'username' => $user->username,
            'posts' => $user->posts()->get(),
            'postCount' => $user->posts()->count()
        ]);
    }
}
