<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre', 
        'correo', 
        'cargo', 
        'ubicacion', 
        'tipografia_favorita',
        'avatar'
    ];
}


