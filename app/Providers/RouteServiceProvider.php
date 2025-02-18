<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// app/Providers/RouteServiceProvider.php

use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    protected $namespace = 'App\\Http\\Controllers';  // Usar el espacio de nombres adecuado

    public function boot()
    {
        $this->routes(function () {
            Route::prefix('api')
                 ->middleware('api')
                 ->namespace($this->namespace . '\\Api')  // Asegúrate de que apunte a 'Api'
                 ->group(base_path('routes/api.php'));

            Route::middleware('web')
                 ->namespace($this->namespace)
                 ->group(base_path('routes/web.php'));
        });
    }

    public function register()
    {
        //
    }
}
