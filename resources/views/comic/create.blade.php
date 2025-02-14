<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Cómic</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
@include('header');
    <!-- Contenedor principal -->
    <div class="container mt-5">
        <h1 class="text-center mb-4">Registrar Cómic por ISBN</h1>

        <!-- Mensajes de éxito y error -->
        @if(session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <!-- Formulario de registro de cómic -->
        <form action="{{ route('comics.store') }}" method="POST">
            @csrf

            <!-- Campo ISBN -->
            <div class="mb-3">
                <label for="isbn" class="form-label">ISBN:</label>
                <input type="text" id="isbn" name="isbn" class="form-control" required>
            </div>

            <!-- Campo Cantidad -->
            <div class="mb-3">
                <label for="stock" class="form-label">Cantidad a Añadir:</label>
                <input type="number" id="stock" name="stock" class="form-control" required min="1">
            </div>

            <!-- Campo Precio -->
            <div class="mb-3">
                <label for="precio" class="form-label">Precio (€):</label>
                <input type="number" step="0.01" id="precio" name="precio" class="form-control" required min="0">
            </div>

            <!-- Botón de envío -->
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">Registrar Cómic</button>
            </div>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
