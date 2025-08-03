<a href="/post/{{$post->id}}" class="list-group-item list-group-item-action">
  <span class="flex gap-2 items-center">
    <img class="avatar-tiny shadow-md border-2! border-pink-600!" src="{{$post->user->avatar}}" />
    <strong class="text-pink-600">{{$post->title}}</strong> 
    <span class="text-muted small">
      @if(!isset($hideAuthor))
      by {{$post->user->username}} 
      @endif
      on {{$post->created_at->format('/n/j/Y')}}
    </span>
  </span>
</a>