<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class citas extends Model
{
    protected $table = 'citas';

    protected $fillable = [
        'token_unique',
        'nombre',
        'apellido',
        'edad',
        'cedula',
        'departamento',
        'cita_hora',
    ];

}
