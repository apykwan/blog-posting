<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Follow;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    public function createFollow(User $user)
    {
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

        return back()->with('success', 'User successfully follow.');
    }

    public function removeFollow()
    {

    }
}
