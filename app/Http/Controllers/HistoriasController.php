<?php

namespace App\Http\Controllers;

use App\Models\historias; // <--- Cambiado a 'historias'
use App\Models\citas;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HistoriasController extends Controller
{
    public function show($cedula)
    {
        // Usamos el nombre exacto de tu modelo
        $historia = historias::where('cedula', $cedula)->first();

        if (!$historia) {
            $cita = citas::where('cedula', $cedula)->first();
            
            $historia = historias::create([
                'cedula' => $cedula,
                'nombre_completo' => $cita ? ($cita->nombre . ' ' . $cita->apellido) : 'Paciente Sin Nombre',
                'observaciones' => '--- Inicio de Historia Médica ---'
            ]);
        }

        return Inertia::render('Admin/historia', [
            'historia' => $historia
        ]);
    }

    public function update(Request $request)
    {
        $historia = historias::findOrFail($request->id);
        $historia->update([
            'observaciones' => $request->observaciones
        ]);

        return back()->with('message', 'Historia actualizada correctamente');
    }
}