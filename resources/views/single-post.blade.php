<x-layout :doctitle="$post->title">
  <div class="container py-md-5 container--narrow h-[80vh]">
    <div class="d-flex justify-content-between">
      @can('update', $post)
      <h2>{{ $post->title }}</h2>
      <span class="pt-2">
        <a href="/post/{{$post->id}}/edit" class="text-primary mr-2" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fas fa-edit"></i></a>
        <form class="delete-post-form d-inline" action="/post/{{$post->id}}" method="POST">
          @csrf
          @method('DELETE')
          <button class="delete-post-button text-danger" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fas fa-trash"></i></button>
        </form>
        {{-- <livewire:deletepost :post="$post" /> --}}
      </span>
      @endcan
    </div>

    <p class="text-muted small mb-4">
      <a href="#"><img class="avatar-tiny" src="{{$post->user->avatar}}" /></a>
      Posted by <a href="/profile/{{$post->user->username}}">{{ $post->user->username }}</a> {{ $post->created_at }}
    </p>

    <div class="body-content">
      <p>{!! $post->body !!}<p>
    </div>
  </div>
</x-layout>