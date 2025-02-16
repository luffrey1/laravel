<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <title>Carrito</title>
    
</head>
<body>
    @include('header')

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="container mt-4">
        <h2>Tu carrito</h2>

        @if(session()->has('cart') && count(session('cart')) > 0)
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>ISBN</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Total</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(session('cart') as $id => $item)
                        <tr>
                            <td>{{ $item['name'] ?? 'No disponible' }}</td>
                            <td>{{ $item['isbn'] ?? 'No disponible' }}</td>
                            <td>{{ $item['quantity'] }}</td>
                            <td>€{{ number_format($item['price'] ?? 0, 2) }}</td>
                            <td>€{{ number_format(($item['price'] ?? 0) * $item['quantity'], 2) }}</td>
                            <td>
                                <form action="{{ route('cart.remove', $id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="text-end mt-3">
                <form action="{{ route('cart.checkout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-warning">Pagar Artículos</button>
                </form>
            </div>

            @else
            <!-- Mensaje si el carrito está vacío -->
            <div class="container mt-5 d-flex justify-content-center">
                <div class="card text-center shadow-lg p-4" style="max-width: 500px;">
                    <div class="card-body">
                        <h4 class="card-title text-primary">Tu carrito está vacío</h4>
                        <p class="card-text text-muted">Explora nuestra tienda y añade productos a tu carrito.</p>
                        <a href="{{ route('comics.index') }}" class="btn btn-primary">Ir a la tienda</a>
                    </div>
                </div>
            </div>
@endif
    </div>

</body>
</html>
