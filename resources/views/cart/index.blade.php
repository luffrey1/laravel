<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<body>
@include('header');
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
                <td>{{ $item['name'] ?? 'No disponible' }}</td>  <!-- Añadido manejo de null -->
                <td>{{ $item['isbn'] ?? 'No disponible' }}</td>  <!-- Añadido manejo de null -->
                <td>{{ $item['quantity'] }}</td>
                <td>€{{ number_format($item['price'] ?? 0, 2) }}</td>  <!-- Añadido manejo de null -->
                <td>€{{ number_format($item['price'] * $item['quantity'], 2) }}</td>
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

</body>
</html>
