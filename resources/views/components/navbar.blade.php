<nav class="navbar navbar-expand-lg navbar-light">
  <a class="navbar-brand" href="{{ url('/') }}">
    DB-project
  </a>
  <ul class="navbar-nav">
    <li class="nav-item <?=$route == 0 ? 'active':''?>">
      <a class="nav-link" href="{{ url('/') }}">Home<span class="sr-only">(current)</span></a>
    </li>
    <li class="nav-item <?=$route == 1 ? 'active':''?>">
      <a class="nav-link" href="{{ url('/') }}/explore">Explore</a>
    </li>
    @auth
      <li class="nav-item <?=$route == 2 ? 'active':''?>">
        <a class="nav-link" href="{{ url('/') }}/profile/{{Auth::user()->id}}">Profile</a>
      </li>
    @endauth
  </ul>
  <ul class="navbar-nav" style="margin-left: auto;">
    @guest
      <li class="nav-item">
        <a class="nav-link" href="{{ url('/') }}/login">Login</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ url('/') }}/register">Register</a>
      </li>
    @else
      <li class="nav-item">
        <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
      </li>
      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
          @csrf
      </form>
    @endguest
  </ul>
</nav>