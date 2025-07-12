<x-profile :sharedData="$sharedData">
  <div class="list-group">
    @foreach($followers as $follow)
    <a href="/profile/{{$follow->userDoingtheFollowing->username}}" class="list-group-item list-group-item-action">
      <img class="avatar-tiny" src="{{$follow->userDoingtheFollowing->avatar}}" />
      {{strtoupper($follow->userDoingtheFollowing->username)}}
    </a>
    @endforeach
  </div>
</x-profile>