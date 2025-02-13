<header class="d-flex justify-content-center py-3">
      <ul class="nav nav-pills">
        <li class="nav-item"><a href="/comics" class="nav-link active" aria-current="page">Comics</a></li>
        <li class="nav-item"><a href="/user/login" class="nav-link">Iniciar Sesion</a></li>
        <li class="nav-item"><a href="/user/register" class="nav-link">Registrarte</a></li>
        @if(Auth::check() && Auth::user()->admin)
        <li class="nav-item"><a href="/users" class="nav-link">Admin</a></li>
                        @endif
   

      </ul>
    </header>