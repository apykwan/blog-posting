<x-profile :sharedData="$sharedData" doctitle="who {{$sharedData['username']}}'s Following">
  <div class="list-group">
    @foreach($following as $follow)
    <a href="/profile/{{$follow->userBeingFollowed->username}}" class="list-group-item list-group-item-action">
      <img class="avatar-tiny" src="{{$follow->userBeingFollowed->avatar}}" />
      {{strtoupper($follow->userBeingFollowed->username)}}
    </a>
    @endforeach
  </div>
</x-profile>