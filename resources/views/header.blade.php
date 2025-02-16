
<header class="d-flex justify-content-center py-3">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
      <ul class="nav nav-pills">
        <li class="nav-item"><a href="/comics" class="nav-link active" aria-current="page">Comics</a></li>
        @if(!Auth::check())
        <li class="nav-item"><a href="/user/login" class="nav-link">Iniciar Sesion</a></li>
        <li class="nav-item"><a href="/user/register" class="nav-link">Registrarte</a></li>
        @endif
        @if(Auth::check())
        <form action="{{ route('logout') }}" method="POST" class="d-inline">
        @csrf
        <button type="submit" class="nav-link btn btn-link">Cerrar Sesión</button>
        </form>
        @endif
        @if(Auth::check() && Auth::user()->admin)
        <li class="nav-item"><a href="/users" class="nav-link text-danger">Admin</a></li>
        <li class="nav-item"><a href="/comics/create" class="nav-link text-danger">Crear Comics</a></li>
                        @endif
                        
      </ul>
      @if(Auth::check())
      <div class="d-flex align-items-center">
            <!-- Enlace al carrito -->
            <a href="{{ route('cart.index') }}" class="btn btn-link">
                <i class="fas fa-shopping-cart"></i>
                <span class="badge badge-pill badge-primary">
                    {{ session('cart') ? count(session('cart')) : 0 }}
                </span>
            </a>
        </div>
        @endif
     
    </header>