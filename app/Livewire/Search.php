<?php

namespace App\Livewire;

use App\Models\Post;
use Livewire\Component;

class Search extends Component
{
    public $searchTerm = '';

    public function render()
    {
        $results = $this->searchTerm === ''
            ? collect()
            : Post::where('title', 'like', '%' . $this->searchTerm . '%')->orWhere(
            'body',
            'like',
            '%' . $this->searchTerm . '%')->get();

        return view('livewire.search', [
            'results' => $results
        ]);
    }
}
