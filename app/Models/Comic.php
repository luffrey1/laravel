<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comic extends Model
{
     protected $fillable = ['isbn', 'id', 'genero', 'anio', 'titulo','precio', 'descripcion','stock'];

     public function user()
     {
         return $this->hasOne(User::class);
     }
 
     //Animal va a ser el propietario de la relación 1 a 1 con Owner
     public function ventas()
     {
         return $this->hasMany(Venta::class);
     }
}
