<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\{Follow, User};

class Addfollow extends Component
{
    public $username;
    
    public function save()
    {
        if (!Auth::check()) {
            abort(403, 'Unauthorized');
        }

        $user = User::where('username', $this->username)->first();

        // cannot follow yourself
        if ($user->id == Auth::user()->id) {
            return back()->with('failure', 'You cannot follow yourself.');
        }

        // cannot follow someone already following
        $existCheck = Follow::where('user_id', Auth::user()->id)
            ->where('followeduser', $user->id)
            ->count();

        if ($existCheck) {
            return back()->with('failure', 'You are already following this user.');
        }

        $newFollow = new Follow;
        $newFollow->user_id = Auth::user()->id;
        $newFollow->followeduser = $user->id;
        $newFollow->save();

        session()->flash('Success', 'User successfully followed.');
        return $this->redirect("/profile/{$this->username}", navigate: true);
    }

    public function render()
    {
        return view('livewire.addfollow');
    }
}
