<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <title>Document</title>
</head>
<body>
@include('header')
<div class="container mt-5">

    @if($comics->isEmpty())
        <div class="alert alert-warning text-center">
            No hay cómics registrados en la base de datos.
        </div>
    @else
        <div class="row row-cols-1 row-cols-md-3 g-4">
            @foreach($comics as $comic)
                <div class="col">
                    <div class="card h-100 shadow-lg border-0">
                        <img src="{{ $comic->imagen }}" class="card-img-top p-3" alt="Imagen de {{ $comic->titulo }}" style="height: 750px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title text-primary">{{ $comic->titulo }}</h5>
                            <p class="card-text"><strong>ISBN:</strong> {{ $comic->isbn }}</p>
                            <p class="card-text"><strong>Autores:</strong> {{ $comic->autores }}</p>
                            <p class="card-text text-success fs-5"><strong>{{ number_format($comic->precio, 2) }} €</strong></p>
                            <p class="card-text"><strong>Stock:</strong> {{ $comic->stock }} unidades</p>
                         
                        </div>
                        <div class="card-footer bg-white text-center">
                            <form action="{{ route('cart.add', $comic->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary">Añadir al carrito</button>
                            </form>
                                @if(Auth::check() && Auth::user()->admin)
                                    <form action="{{ route('comics.delete', $comic->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" style="margin-top:5px;">Eliminar</button>
                                    </form>
                                @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>




</body>
</html>