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
      // Solo un admin puede acceder
      if (!Auth::check() || !Auth::user()->admin) {
        return redirect('/')->with('error', 'No tienes permisos de administrador.');
    }

    // Obtener todos los usuarios menos el admin actual
    $users = User::where('id', '!=', Auth::id())->get();

    return view('user.index', compact('users'));
    }
    public function toggleAdmin($id)
    {
        // Solo un admin puede modificar roles
        if (!Auth::check() || !Auth::user()->admin) {
            return redirect('/')->with('error', 'No tienes permisos de administrador.');
        }

        // Encontrar usuario y cambiar su rol
        $user = User::findOrFail($id);
        $user->admin = !$user->admin; // Cambia de admin a no admin y viceversa
        $user->save();

        return redirect()->route('user.index')->with('success', 'Rol de usuario actualizado.');
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
            'name' => 'required|min:3|unique:users,name',
            'email' => 'required|unique:users,email|email',
            'password' => [
                'required',
                'min:8',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ],
            'direccion_envio' => 'nullable|string',
            'tlf' => 'nullable|string',
            'metodo_pago' => 'nullable|string',
        ]);
    
        // Obtener ubicación del usuario
        $location = $this->getUserLocation();
    
        // Crear usuario con los datos de geolocalización
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Hashear la contraseña
            'direccion_envio' => $request->direccion_envio,
            'ciudad' => $location['ciudad'],  // Se obtiene de la API
            'pais' => $location['pais'],  // Se obtiene de la API
            'moneda' => $location['moneda'],
            'tlf' => $request->tlf,
            'metodo_pago' => $request->metodo_pago,
        ]);
    
        Auth::login($user);
    
        return redirect()->route('login')->with('success', 'Registro exitoso. Ubicación guardada.');
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
            return redirect()->route('comics.index')->with('success', 'Inicio de sesión exitoso.');
        }

        return back()->withErrors(['email' => 'Las credenciales proporcionadas no son correctas.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/comics')->with('success', 'Sesión cerrada correctamente.');
    }
    public function getUserLocation()
    {
       // $ip = request()->ip(); // Obtener la IP del usuario
       $ip = '8.8.8.8'; // aqui pones ip personalizada porque utiliza la de localhost y no es valida

        $apiKey = env('IPGEOLOCATION_API_KEY'); // API Key del .env
        $url = "https://api.ipgeolocation.io/ipgeo?apiKey=$apiKey&ip=$ip";
    
        try {
            $response = file_get_contents($url);
            $data = json_decode($response, true);
    
            return [
                'pais' => $data['country_name'] ?? 'Desconocido',
                'ciudad' => $data['country_capital'] ?? 'Desconocido',
                'moneda' => $data['currency']['name'] ?? 'EUR'
            ];
        } catch (\Exception $e) {
            return [
                'pais' => 'Desconocido',
                'ciudad' => 'Desconocido',
                'moneda' => 'EUR'
            ];
        }
    }
    

    

    
}