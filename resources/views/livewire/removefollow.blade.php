<div>
    <form class="ml-2 d-inline">
        @csrf
        <button class="btn btn-danger btn-sm">Stop Following <i class="fas fa-user-times"></i></button>
        @if(Auth::user()->username == $sharedData['username'])
        <a href="/manage-avatar" class="btn btn-secondary btn-sm">Manage Avatar</a>
        @endif
    </form>
</div>
