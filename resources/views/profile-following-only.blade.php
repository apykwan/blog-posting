<div class="list-group">
  @foreach($following as $follow)
  <a href="/profile/{{$follow->userBeingFollowed->username}}" class="list-group-item list-group-item-action">
    <span class="flex gap-2">
      <img class="avatar-tiny border-2! border-amber-500!" src="{{$follow->userBeingFollowed->avatar}}" />
      {{strtoupper($follow->userBeingFollowed->username)}}
    </span>
  </a>
  @endforeach
</div>