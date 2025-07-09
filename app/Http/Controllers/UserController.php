<?php

namespace App\Http\Controllers;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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
            'avatar' => $user->avatar,
            'username' => $user->username,
            'posts' => $user->posts()->get(),
            'postCount' => $user->posts()->count()
        ]);
    }

    public function showAvatarForm(User $user)
    {
        return view('avatar-form');
    }

    public function storeAvatarForm(Request $request) 
    {
        if ($request->hasFile('avatar')) {
            $request->validate([
                'avatar' => 'required|image|image:5000'
            ]);

            // $request->file('avatar')->store('avatars', 'public');
            $manager = new ImageManager(new Driver());
            $image = $manager->read($request->file('avatar'));
            $imgData = $image->cover(120, 120)->toJpeg();

            $user = Auth::user();
            $filename =  $user->id . "-" . uniqid() . ".jpg";
            Storage::disk('public')->put('avatars/' . $filename, $imgData);

            $oldAvatar = $user->avatar;

            $user->avatar = $filename;
            $user->save();

            if ($oldAvatar && $oldAvatar != '/fallback-avatar.jpg') {
                $relativePath = str_replace(asset('storage/'), '', $oldAvatar);
                Storage::disk('public')->delete($relativePath);
            }

            return redirect('/profile/john')->with('success', 'Avatar updated.');
        }
        return redirect('/manage-avatar')->with('failure', 'Avatar update failed.');
    }
}
