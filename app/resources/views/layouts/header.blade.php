<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
  <div class="container-fluid">
    <a class="navbar-brand" href="{{ url('/user_main') }}">自作サイト課題</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="{{ route('search.events') }}"></a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('search.events') }}"></a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('search.events') }}"></a></li>
      </ul>
    </div>
  </div>
</nav>