<div>
    <form wire:submit="update" >
        <p><small><strong><a href="/post/{{$post->id}}">&laquo; Back to post permalink</a></strong></small></p>
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="post-title" class="text-muted mb-1"><small>Title</small></label>
            <input wire:model="title" value="{{ old('title', $post->title) }}" required name="title" id="post-title" class="form-control form-control-lg form-control-title" type="text" placeholder="" autocomplete="off" />
            @error('title')
            <p class="m-0 small alert alert-danger shadow-sm">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="post-body" class="text-muted mb-1"><small>Body Content</small></label>
            <textarea wire:model="body" required name="body" id="post-body" class="body-content tall-textarea form-control" type="text">{{ old('body', $post->body) }}</textarea>
            @error('body')
            <p class="m-0 small alert alert-danger shadow-sm">{{ $message }}</p>
            @enderror
        </div>

        <button class="btn btn-info">Save Changes</button>
    </form>
</div>
