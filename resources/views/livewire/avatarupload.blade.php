<div>
    <form wire:submit.prevent="save" enctype="multipart/form-data">
        @csrf 
        <div class="mb-3">
            <input wire:model="avatar" wire:loading.attr="disabled" wire:target="avatar" type="file" name="avatar" required>
            @error('avatar')
            <p class="alert small alert-danger shadow-sm">{{ $message }}</p>
            @enderror
        </div>
        <button wire:loading.attr="disabled" wire:target="avatar" class="btn btn-primary">Save</button>
    </form>
</div>
