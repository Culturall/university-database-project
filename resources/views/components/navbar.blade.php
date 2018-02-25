<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="#">
    DB-project
  </a>
  <ul class="navbar-nav">
    <li class="nav-item <?=$route == 0 ? 'active':''?>">
      <a class="nav-link" href="/">Home<span class="sr-only">(current)</span></a>
    </li>
    <li class="nav-item <?=$route == 1 ? 'active':''?>">
      <a class="nav-link" href="/explore">Explore</a>
    </li>
    @auth
      <li class="nav-item <?=$route == 2 ? 'active':''?>">
        <a class="nav-link" href="#">Profile</a>
      </li>
    @endauth
    @guest
      <li class="nav-item <?=$route == 3 ? 'active':''?>">
        <a class="nav-link" href="/sign">Login/Register</a>
      </li>
    @endguest
  </ul>
  @auth
  <ul class="navbar-nav" style="margin-left: auto;">
      <li class="nav-item">
        <a class="nav-link" href="#">Logout</a>
      </li>
  </ul>
  @endauth
</nav>