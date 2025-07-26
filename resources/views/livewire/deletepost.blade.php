<form wire:submit="delete" class="delete-post-form d-inline">
    @csrf
    @method('DELETE')
    <button 
        class="delete-post-button text-danger" 
        data-toggle="tooltip" 
        data-placement="top" 
        title="Delete"
    >
        <i class="fas fa-trash"></i>
    </button>
</form>