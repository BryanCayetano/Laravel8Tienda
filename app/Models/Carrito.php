<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrito extends Model
{
    use HasFactory;

    protected $table = "carrito";
    
    protected $fillable = [
        'id_usuario',
        'id_producto',
        'id_categoria',
        'nombre_producto',
        'nombre_categoria',
        'precio',
        'descripcion',
        'stock'
    ];
}

