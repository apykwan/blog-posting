<x-profile :sharedData="$sharedData" doctitle="who {{$sharedData['username']}}'s Following">
  @include('profile-following-only')
</x-profile>