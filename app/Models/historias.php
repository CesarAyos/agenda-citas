<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class historias extends Model // <--- Debe coincidir con el nombre del archivo
{
    protected $fillable = ['cedula', 'nombre_completo', 'observaciones'];
}