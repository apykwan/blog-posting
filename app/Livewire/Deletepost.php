<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Deletepost extends Component
{
    public $post;

    public function delete()
    {
        $this->authorize('delete', $this->post);
        $this->post->delete();
        session()->flash('success', 'Post successfully delete.');
        return $this->redirect('/profile/' . Auth::user()->username, navigate: true);
    }

    public function render()
    {
        return view('livewire.deletepost');
    }
}
