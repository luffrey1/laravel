<?php
namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Http;    
use App\Models\Comic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ComicApiController extends Controller
{
    // Obtener todos los cómics (API)
    public function index()
    {
        $comics = Comic::all();
        return response()->json($comics);
    }

    // Obtener un cómic por ID (API)
    public function show($id)
    {
        $comic = Comic::find($id);
        
        if (!$comic) {
            return response()->json(['error' => 'Cómic no encontrado'], 404);
        }
        
        return response()->json($comic);
    }

    // Crear un nuevo cómic (API)
    public function store(Request $request)
    {
        $request->validate([
            'isbn' => 'required|string|min:3',
            'precio' => 'required|numeric|min:1',
            'stock' => 'required|integer|min:1',
        ]);

        $isbn = $request->input('isbn');
        $stock = $request->input('stock');
        $precio = $request->input('precio');

        $comic = Comic::where('isbn', $isbn)->first();

        if ($comic) {
            // Si el cómic ya existe, aumentamos el stock
            $comic->increment('stock', $stock);
            return response()->json(['success' => "Stock actualizado. Nuevo stock: {$comic->stock}"]);
        }

        // Si el cómic no existe, obtenemos el título desde la API externa
        $apiUrl = "https://openlibrary.org/api/books?bibkeys=ISBN:{$isbn}&format=json&jscmd=data";
        $response = Http::withOptions(['verify' => false])->get($apiUrl);
        $data = $response->json();

        if (!isset($data["ISBN:$isbn"])) {
            return response()->json(['error' => 'No se encontraron datos para este ISBN'], 400);
        }

        $titulo = $data["ISBN:$isbn"]['title'] ?? 'Título Desconocido';

        // Crear el nuevo cómic en la base de datos
        $newComic = Comic::create([
            'isbn' => $isbn,
            'titulo' => $titulo,
            'precio' => $precio,
            'stock' => $stock,
        ]);

        return response()->json($newComic, 201);
    }

    // Actualizar un cómic (API)
    public function update(Request $request, $id)
    {
        $comic = Comic::find($id);

        if (!$comic) {
            return response()->json(['error' => 'Cómic no encontrado'], 404);
        }

        $comic->update($request->only(['isbn', 'titulo', 'precio', 'stock']));

        return response()->json($comic);
    }

    // Eliminar un cómic (API)
    public function destroy($id)
    {
        $comic = Comic::find($id);

        if (!$comic) {
            return response()->json(['error' => 'Cómic no encontrado'], 404);
        }

        $comic->delete();
        return response()->json(['success' => 'Cómic eliminado con éxito']);
    }
}
