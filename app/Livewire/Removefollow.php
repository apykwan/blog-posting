<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\{Follow, User};

class Removefollow extends Component
{
    public $username;

    public function remove()
    {
        $user = User::where('username', $this->username)->first();
        Follow::where('user_id', Auth::user()->id)->where('followeduser', $user->id)->delete();

        session()->flash('success', 'User successfully unfollowed.');
        return $this->redirect("/profile/{$this->username}", navigate: true);
    }

    public function render()
    {
        return view('livewire.removefollow');
    }
}
