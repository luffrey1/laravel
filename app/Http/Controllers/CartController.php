<?php 
// CartController.php
namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Comic;
use App\Models\Venta;
use Illuminate\Support\Facades\Auth;
use Session;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

class CartController extends Controller
{
    /**
     * Mostrar el carrito.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('cart.index');
    }

     /**
     * Mostrar vista de exito al comprar(recomendable api stripe en un futuro...).
     *
     * @return \Illuminate\View\View
     */
    public function success()
{
    return view('cart.success');
}


    /**
     * Añadir un cómic al carrito.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addToCart($id)
    {
        if (Auth::check()) {
            // Obtener el cómic por su ID
            $comic = Comic::find($id);
            
            // Verificar si el cómic existe
            if (!$comic) {
                return redirect()->route('comics.index')->with('error', 'Cómic no encontrado.');
            }

            // Obtener el carrito actual de la sesión
            $cart = session()->get('cart', []);

            // Verificar si el cómic ya está en el carrito
            if (isset($cart[$id])) {
                // Aumentar la cantidad
                $cart[$id]['quantity']++;
            } else {
                // Añadir el cómic al carrito
                $cart[$id] = [
                    'isbn' => $comic->isbn,
                    'name' => $comic->titulo,
                    'price' => $comic->precio,
                    'quantity' => 1,
                ];
            }

            // Guardar el carrito actualizado en la sesión
            session()->put('cart', $cart);

            // Redirigir al usuario
            return redirect()->back()->with('success', 'Cómic añadido al carrito.');
        } else {
            return redirect()->back()->with('error', 'Debes iniciar sesión para añadir cómics al carrito.');
        }
    }

    /**
     * Eliminar un cómic del carrito.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function removeFromCart($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return response()->json(['message' => 'Cómic retirado del carrito.']);
    }

    /**
     * Realizar el pago del carrito y registrar la venta.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function checkout()
    {
        $cart = session()->get('cart', []);

        // Crear la venta
        $venta = Venta::create([
            'user_id' => Auth::id(),
            'fecha' => Carbon::now(),
        ]);

        // Recorrer cada cómic en el carrito
        foreach ($cart as $id => $item) {
            $comic = Comic::where('isbn', $item['isbn'])->first();

            if ($comic) {
                // Verificar si hay suficiente stock
                if ($comic->stock >= $item['quantity']) {
                    $comic->stock -= $item['quantity'];
                    $comic->save();

                    // Relacionar la venta con el cómic
                    $venta->comics()->attach($comic->id, [
                        'cantidad' => $item['quantity'],
                    ]);
                } else {
                    return redirect()->route('cart.index')->with('error', "No hay suficiente stock de '{$item['name']}'.");
                }
            }
        }

        // Vaciar el carrito
        session()->forget('cart');

        return redirect()->route('cart.success')->with('success', 'Compra realizada con éxito.');
    }


    /**
     * Obtener la ubicación del usuario (API Geolocation).
     *
     * @return array
     */
    private function getUserLocation()
    {
        $ip = request()->ip();
        $apiKey = env('IPGEOLOCATION_API_KEY');
        $url = "https://api.ipgeolocation.io/ipgeo?apiKey=$apiKey&ip=$ip";
        $response = file_get_contents($url);
        return json_decode($response, true);
    }
}
