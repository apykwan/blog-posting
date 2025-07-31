<a href="/post/{{$post->id}}" class="list-group-item list-group-item-action">
  <img class="avatar-tiny shadow-md" src="{{$post->user->avatar}}" />
  <strong class="text-pink-600">{{$post->title}}</strong> 
    <span class="text-muted small">
      @if(!isset($hideAuthor))
      by {{$post->user->username}} 
      @endif
      on {{$post->created_at->format('/n/j/Y')}}
    </span>
</a>