<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comic;
class ComicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $comics = Comics::all(); // Recupera todos los registros de la tabla animals
    return view('comics.index', compact('comics')); 
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $comics = Comics::all();

        //Se lo envío a la vista:
        return view('comics.create', compact('comics'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'isbn'=>'required|min:3', 
            'id'=>'required|integer',
            'genero'=>'required|integer',
            'anio'=>'required',
            'titulo'=>'required',
            'precio'=>'required',
            'descripcion'=>'required',
            'stock'=> '',
        ]);
        Animal::create($request->all());
        return redirect()->route('vet.index')->with('success', value: "Veterinario insertado");
    }
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
    public function destroy(string $id)
    {
        //
    }
}
