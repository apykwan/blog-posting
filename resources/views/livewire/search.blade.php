<div>
    <input type="text" wire:model.debounce.500ms="searchTerm" />

    @if (count($results) > 0)
    <ul>
        @foreach($results as $post)
        <li>{{$post->title}}</li>
        @endforeach
    </ul>
    @endif
</div>
