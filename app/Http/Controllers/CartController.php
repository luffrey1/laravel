<?php 
// CartController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comic;
use Session;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

class CartController extends Controller
{
    // Mostrar el carrito
    public function index()
    {
        return view('cart.index');
    }

    // Añadir productos al carrito
   // CartController.php
   // CartController.php
   public function addToCart($id)
   {
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
           // Si el cómic ya está en el carrito, aumentar la cantidad
           $cart[$id]['quantity']++;
       } else {
           // Si el cómic no está en el carrito, añadirlo con todos sus detalles
           $cart[$id] = [
               'isbn' => $comic->isbn,  // Asegúrate de que 'isbn' está presente en la base de datos
               'name' => $comic->titulo, // Asegúrate de que 'titulo' está presente en la base de datos
               'price' => $comic->precio, // Asegúrate de que 'precio' está presente en la base de datos
               'quantity' => 1,
           ];
       }
   
       // Guardar el carrito actualizado en la sesión
       session()->put('cart', $cart);
   
       // Redirigir al carrito
       return redirect()->route('cart.index')->with('success', 'Cómic añadido al carrito.');
   }
   



    // Eliminar producto del carrito
    public function removeFromCart($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index');
    }

    // Pagar
    public function checkout(Request $request)
    {
        // Verificar la ubicación del usuario (utiliza la API de geolocalización o lo que necesites)
        $location = $this->getUserLocation(); // Este método llamaría a tu API

        // Calcular el costo de envío según la ubicación
        $shippingCost = $this->calculateShipping($location);

        // Calcular el total
        $cart = session()->get('cart', []);
        $total = array_reduce($cart, function ($carry, $item) {
            return $carry + ($item['price'] * $item['quantity']);
        }, 0);

        $total += $shippingCost; // Añadir costo de envío

        // Crear una sesión de Stripe para el pago
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $checkoutSession = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => array_map(function ($item) {
                return [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => $item['name'],
                        ],
                        'unit_amount' => $item['price'] * 100,
                    ],
                    'quantity' => $item['quantity'],
                ];
            }, $cart),
            'shipping_address_collection' => [
                'allowed_countries' => ['US'],
            ],
            'mode' => 'payment',
            'success_url' => route('cart.success'),
            'cancel_url' => route('cart.index'),
        ]);

        return redirect()->away($checkoutSession->url);
    }

    // Página de éxito
    public function success()
    {
        session()->forget('cart');
        return view('cart.success');
    }

    // Método para obtener la ubicación del usuario (API Geolocation)
    private function getUserLocation()
    {
        // Puedes usar la API de geolocalización que prefieras, ejemplo con IP Geolocation
        $ip = request()->ip();
        $apiKey = env('IPGEOLOCATION_API_KEY');
        $url = "https://api.ipgeolocation.io/ipgeo?apiKey=$apiKey&ip=$ip";
        $response = file_get_contents($url);
        return json_decode($response, true);
    }

    // Método para calcular el coste de envío
    private function calculateShipping($location)
    {
        // Aquí iría el cálculo del coste según la ubicación (usando alguna API de envíos)
        return 10; // Ejemplo de coste fijo para el envío
    }
}
