<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Comic;
class ComicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $comics = Comic::all(); // Obtener los cómics almacenados en la BD

        foreach ($comics as $comic) {
            $isbn = $comic->isbn;
            $apiUrl = "https://openlibrary.org/api/books?bibkeys=ISBN:$isbn&format=json&jscmd=data";
            
            $response = Http::withOptions([
                'verify' => false,  // Esto en windows me daba error de seguridad por el ssl asi que se desactiva
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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $comics = Comic::all();
        if (!Auth::check() || !Auth::user()->admin) {
            return redirect('/')->with('error', 'No tienes permisos de administrador.');
        }
        //Se lo envío a la vista:
        return view('comic.create', compact('comics'));
    }

    /**
     * Store a newly created resource in storage.
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

        // Si no existe, obtener el título desde la API
        $apiUrl = "https://openlibrary.org/api/books?bibkeys=ISBN:{$isbn}&format=json&jscmd=data";
        $response = Http::withOptions([
            'verify' => false,  // Esto en windows me daba error de seguridad por el ssl asi que se desactiva
        ])->get($apiUrl);
        $data = $response->json();

        if (!isset($data["ISBN:$isbn"])) {
            return redirect()->route('comics.create')
                ->with('error', 'No se encontraron datos para este ISBN');
        }

        $titulo = $data["ISBN:$isbn"]['title'] ?? 'Título Desconocido';

        // Crear nuevo registro en la base de datos
        Comic::create([
            'isbn' => $isbn,
            'titulo' => $titulo,
            'precio' => $precio, // Precio ingresado manualmente
            'stock' => $stock,
        ]);

        return redirect()->route('comics.create')
            ->with('success', "Cómic '{$titulo}' registrado con éxito. Stock: {$stock}");
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
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
