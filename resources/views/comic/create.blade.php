<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Cómic</title>
</head>
<body>
    <h1>Registrar Cómic por ISBN</h1>

    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    @if(session('error'))
        <p style="color: red;">{{ session('error') }}</p>
    @endif

    <form action="{{ route('comics.store') }}" method="POST">
        @csrf
        
        <label for="isbn">ISBN:</label>
        <input type="text" id="isbn" name="isbn" required>

        <label for="stock">Cantidad a Añadir:</label>
        <input type="number" id="stock" name="stock" required min="1">

        <label for="precio">Precio (€):</label>
        <input type="number" step="0.01" id="precio" name="precio" required min="0">

        <button type="submit">Registrar Cómic</button>
    </form>
</body>
</html>
