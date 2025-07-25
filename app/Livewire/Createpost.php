<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use App\Mail\NewPostEmail;
use App\Models\Post;

class Createpost extends Component
{
    public $title;
    public $body;

    public function create()
    {
        if (!Auth::check()) {
            abort(403, 'Unauthorized');
        }

        $incomingFields = $this->validate([
            'title' => 'required',
            'body' => 'required'
        ]);

        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);
        $incomingFields['user_id'] = Auth::id();

        $newPost = Post::create($incomingFields);

        Mail::to(Auth::user()->email)->send(new NewPostEmail([
            'name' => Auth::user()->username,
            'title' => $newPost->title
        ]));

        session()->flash('success', 'New post successfully created.');

        return $this->redirect("/post/{$newPost->id}", navigate: true);
    }

    public function render()
    {
        return view('livewire.createpost');
    }
}
