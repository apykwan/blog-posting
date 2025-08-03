<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    @viteReactRefresh
    <title>
      @isset($doctitle)
      {{$doctitle}} | Lara Post
      @else
      Lara post
      @endisset
    </title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous" />
    <script defer src="https://use.fontawesome.com/releases/v5.5.0/js/all.js" integrity="sha384-GqVMZRt5Gn7tB9D9q7ONtcp4gtHIUEW/yG7h98J7IpE3kpi+srfFyyB/04OV6pG0" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet" />
    <script src="https://cdn.socket.io/4.7.2/socket.io.min.js"></script>
    @vite(['resources/css/app.css'])
    @livewireStyles
  </head>
  <body>
    <header class="mb-3  bg-linear-to-t from-pink-500 to-pink-700">
      <div class="container d-flex flex-column flex-md-row align-items-center p-3">
        <h4 class="my-0 mr-md-auto font-weight-normal">
          <a href="/" class="text-white hover:text-gray-200! hover:no-underline! font-bold uppercase">Lara Post</a>
        </h4>

        @auth
        <div class="d-flex flex-row flex-nowrap align-items-center gap-2">
      
          {{-- <div id="react-search"></div> --}}
   
          <livewire:search />
          <div id="react-chat-btn" class="text-white mx-2 header-chat-icon" style="cursor: pointer;"></div>

          {{-- <span class="text-white mr-2 header-chat-icon" title="Chat" data-toggle="tooltip" data-placement="bottom"><i class="fas fa-comment"></i></span> --}}
          <a href="/profile/{{Auth::user()->username}}" class="mr-2">
            <img title="My Profile" data-toggle="tooltip" data-placement="bottom" style="width: 32px; height: 32px; border-radius: 16px" src="{{Auth::user()->avatar}}" />
          </a>
          <a href="/create-post" class="btn btn-sm btn-light text-pink-600! font-bold! mr-2">Create Post</a>
          <form action="/logout" method="POST" class="d-inline">
            @csrf
            <button class="btn btn-sm btn-light text-teal-600! font-bold!">Sign Out</button>
          </form>
        </div>
        @else
        <form action="/login" method="POST" class="mb-0 pt-2 pt-md-0">
          @csrf
          <div class="row align-items-center">
            <div class="col-md mr-0 pr-md-0 mb-3 mb-md-0">
              <input name="loginusername" class="form-control form-control-sm input-dark" type="text" placeholder="Username" autocomplete="off" />
            </div>
            <div class="col-md mr-0 pr-md-0 mb-3 mb-md-0">
              <input name="loginpassword" class="form-control form-control-sm input-dark" type="password" placeholder="Password" />
            </div>
            <div class="col-md-auto">
              <button class="btn btn-info btn-sm">Sign In</button>
            </div>
          </div>
        </form>
        @endauth
       
      </div>
    </header>
    </header>
    <!-- header ends here -->
    @if (session()->has('success'))
    <div class="container container--narrow">
      <div class="alert alert-success text-center">
        {{ session('success') }}
      </div>
    </div>
    @elseif (session()->has('failure'))
    <div class="container container--narrow">
      <div class="alert alert-danger text-center">
        {{ session('failure') }}
      </div>
    </div>
    @endif

    {{$slot}}

    <!-- footer begins -->
    <footer class="border-top text-center small text-muted py-3">
      <p class="m-0">Copyright &copy; <span id="year"></span> <a href="/" class="text-muted">Lara Post</a>. All rights reserved.</p>
    </footer>

    @auth
    <div 
      data-username="{{ Auth::user()->username }}"
      id="react-chat-wrapper" 
    >
      <div id="react-chat-root"></div>
    </div>
    {{-- <div 
      data-username="{{Auth::user()->username}}"
      data-avatar="{{Auth::user()->avatar}}" 
      id="chat-wrapper" 
      class="chat-wrapper shadow border-top border-left border-right"
    ></div>  --}}
    @endauth

    @livewireScripts
    @vite(['resources/js/app.js'])
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script>
      $('[data-toggle="tooltip"]').tooltip()

      document.getElementById('year').textContent = new Date().getFullYear()
      window.Laravel = {
        jwtToken: @json($jwtToken)
      }
    </script>
  </body>
</html>
    