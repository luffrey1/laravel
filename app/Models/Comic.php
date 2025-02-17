<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comic extends Model
{
     protected $fillable = ['isbn', 'titulo','precio','stock'];

     public function users()
    {
        return $this->belongsToMany(User::class, 'venta_comic')->withPivot('cantidad')->withTimestamps();
    }
 
     //Animal va a ser el propietario de la relación 1 a 1 con Owner
     public function ventas()
    {
        return $this->belongsToMany(Venta::class, 'venta_comic', 'comic_id', 'venta_id')
                    ->withPivot('quantity');  // Relación muchos a muchos con cantidad
    }
     
}
