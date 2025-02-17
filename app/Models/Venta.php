<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $fillable = ['fecha', 'direccion_envio', 'estado_envio,','metodo_pago'];

    public function comics()
    {
        return $this->belongsToMany(Comic::class, 'venta_comic', 'venta_id', 'comic_id')
                    ->withPivot('quantity');  // Relación muchos a muchos con cantidad
    }

    public function users()
    {
        return $this->belongsTo(User::class);
    }
}
