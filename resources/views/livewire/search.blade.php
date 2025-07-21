<div x-data="{ isOpen: false }">
    <button
        x-on:click="isOpen = true; setTimeout(() => document.querySelector('#live-search-field').focus(), 50)"
        style="background: none; border: none; padding: 0; margin: 0; outline: none; cursorpointer;" 
        class="text-white mr-2 header-search-icon" 
        title="Search" 
        data-toggle="tooltip" 
        data-placement="bottom"
    >
        <i class="fas fa-search"></i>
    </button>
    <div class="search-overlay" x-bind:class="isOpen ? 'search-overlay--visible' : ''">
        <div class="search-overlay-top shadow-sm">
        <div class="container container--narrow">
            <label for="live-search-field" class="search-overlay-icon"><i class="fas fa-search"></i></label>
            <input autocomplete="off" type="text" id="live-search-field" class="live-search-field" placeholder="What are you interested in?">
            <span x-on:click="isOpen = false;" class="close-live-search">
                <i class="fas fa-times-circle"></i>
            </span>
        </div>
        </div>

        <div class="search-overlay-bottom">
        <div class="container container--narrow py-3">
            <div class="circle-loader"></div>
            <div class="live-search-results"></div>
        </div>
        </div>
    </div>
    {{-- <input type="text" wire:model.live="searchTerm" placeholder="...search"/>

    @if (count($results) > 0)
    <ul>
        @foreach($results as $post)
        <li>{{$post->title}}</li>
        @endforeach
    </ul>
    @endif --}}
</div>
