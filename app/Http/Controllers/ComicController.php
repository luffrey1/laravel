<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Comic;

class ComicController extends Controller
{
    /**
     * Mostrar todos los cómics.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $comics = Comic::all(); // Obtener los cómics almacenados en la BD

        foreach ($comics as $comic) {
            $isbn = $comic->isbn;
            $apiUrl = "https://openlibrary.org/api/books?bibkeys=ISBN:$isbn&format=json&jscmd=data";
            
            $response = Http::withOptions([
                'verify' => false,  // Desactivar SSL en windows
            ])->get($apiUrl);
            $data = $response->json();
    
            if (isset($data["ISBN:$isbn"])) {
                $bookData = $data["ISBN:$isbn"];
    
                // Añadir información obtenida de la API
                $comic->titulo = $bookData['title'] ?? $comic->titulo;
                $comic->autores = isset($bookData['authors']) 
                    ? implode(', ', array_column($bookData['authors'], 'name')) 
                    : 'Desconocido';
                $comic->imagen = $bookData['cover']['large'] ?? 'https://via.placeholder.com/200x300?text=Sin+Imagen';
            }
        }
    
        return view('comic.index', compact('comics'));
    }

    /**
     * Mostrar el formulario para crear un nuevo cómic.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $comics = Comic::all();
        if (!Auth::check() || !Auth::user()->admin) {
            return redirect('/')->with('error', 'No tienes permisos de administrador.');
        }

        return view('comic.create', compact('comics'));
    }

    /**
     * Almacenar un nuevo cómic en la base de datos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        if (!Auth::check() || !Auth::user()->admin) {
            return redirect('/')->with('error', 'No tienes permisos de administrador.');
        }
        $request->validate([
            'isbn'=>'required|string|min:3',
            'precio'=>'required|numeric|min:1',
            'stock'=> 'required|integer|min:1',
        ]);

        $isbn = $request->input('isbn');
        $stock = $request->input('stock');
        $precio = $request->input('precio');
        
        $comic = Comic::where('isbn', $isbn)->first();

        if ($comic) {
            // Si existe, aumentar stock y mantener el precio
            $comic->increment('stock', $stock);
            return redirect()->route('comics.create')
                ->with('success', "Stock actualizado. Nuevo stock: {$comic->stock}");
        }

        // Obtener el título desde la API si no existe
        $apiUrl = "https://openlibrary.org/api/books?bibkeys=ISBN:{$isbn}&format=json&jscmd=data";
        $response = Http::withOptions([
            'verify' => false,  // Desactivar SSL en windows
        ])->get($apiUrl);
        $data = $response->json();

        if (!isset($data["ISBN:$isbn"])) {
            return redirect()->route('comics.create')
                ->with('error', 'No se encontraron datos para este ISBN');
        }

        $titulo = $data["ISBN:$isbn"]['title'] ?? 'Título Desconocido';

        // Crear nuevo cómic en la base de datos
        Comic::create([
            'isbn' => $isbn,
            'titulo' => $titulo,
            'precio' => $precio,
            'stock' => $stock,
        ]);

        return redirect()->route('comics.create')
            ->with('success', "Cómic '{$titulo}' registrado con éxito. Stock: {$stock}");
    }

    /**
     * Eliminar un cómic específico de la base de datos.
     *
     * @param  string  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $comic = Comic::find($id);
        
        if ($comic) {
            $comic->delete();
            return redirect()->route('comics.index')->with('success', 'Cómic eliminado con éxito');
        }
    
        return redirect()->route('comics.index')->with('error', 'Cómic no encontrado');
    }
}
