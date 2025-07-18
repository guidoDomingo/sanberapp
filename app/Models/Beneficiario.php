<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beneficiario extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'cedula',
        'nombre',
        'apellido',
        'carrera',
        'institucion',
        'barrio',
        'celular',
        'rendicion',
        'adjudicado'
    ];
}
