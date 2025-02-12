<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use App\Models\User;
use App\Models\Comic;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    return view('user.create');

    }
    public function login()
    {
        return view('user.login');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|min:3|unique:users,name',
             'email' => 'required|unique:users,email|email',
            'password' => [
            'required',
            'min:8',
            'confirmed',
            Password::min(8) // Sin barra invertida
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()
                    ],
            'direccion_envio'=>'',
            'ciudad'=>'',
            'pais'=>'',
            'tlf'=>'',
            'metodo_pago'=> ''
        ]);
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Hashear la contraseña
            'direccion_envio' => $request->direccion_envio,
            'ciudad' => $request->ciudad,
            'pais' => $request->pais,
            'tlf' => $request->tlf,
            'metodo_pago' => $request->metodo_pago,
        ]);
        return redirect()->route('login')->with('success', value: "Inicia sesión por primera vez:)");
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
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('comic.index')->with('success', 'Inicio de sesión exitoso.');
        }

        return back()->withErrors(['email' => 'Las credenciales proporcionadas no son correctas.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Cierre de sesión exitoso.');
    }
}
