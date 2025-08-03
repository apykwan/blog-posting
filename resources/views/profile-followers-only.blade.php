<div class="list-group">
  @foreach($followers as $follow)
  <a href="/profile/{{$follow->userDoingtheFollowing->username}}" class="list-group-item list-group-item-action">
    <span class="flex gap-2">
      <img class="avatar-tiny border-2! border-teal-500!" src="{{$follow->userDoingtheFollowing->avatar}}" />
      {{strtoupper($follow->userDoingtheFollowing->username)}}
    </span>
  </a>
  @endforeach
</div>