<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Historia extends Model
{
    protected $table = 'historias';

    protected $fillable = ['cedula', 'nombre_completo', 'numero_historia', 'observaciones'];
}
