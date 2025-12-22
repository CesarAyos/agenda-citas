<?php

namespace App\Http\Controllers;

use App\Models\historias; // <--- Cambiado a 'historias'
use App\Models\citas;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HistoriasController extends Controller
{
    // app/Http/Controllers/HistoriasController.php

public function show($cedula)
{
    $historia = \App\Models\historias::where('cedula', $cedula)->first();

    if (!$historia) {
        $cita = \App\Models\citas::where('cedula', $cedula)->first();
        
        $historia = \App\Models\historias::create([
            'cedula' => $cedula,
            'nombre_completo' => $cita ? ($cita->nombre . ' ' . $cita->apellido) : 'Paciente Sin Nombre',
            'observations' => '--- Inicio de Historia Médica ---',
            'numero_historia' => null // Se asignará manualmente desde el Dashboard/Ficha
        ]);
    }

    // ESTA LÍNEA ES LA QUE CAUSA EL ERROR:
    // Asegúrate de que coincida con el archivo en resources/js/Pages/Admin/
    return \Inertia\Inertia::render('Admin/MedicalHistoria', [ 
        'historia' => $historia
    ]);
}

   public function update(Request $request)
{
    // Validamos que el ID exista
    $historia = \App\Models\historias::findOrFail($request->id);

    // Actualizamos AMBOS campos
    $historia->update([
        'numero_historia' => $request->numero_historia,
        'observations'    => $request->observations,
    ]);

    // Redireccionamos de vuelta con un mensaje de éxito
    return redirect()->route('dashboard')->with('message', 'Historia actualizada y guardada.');
}

public function store(Request $request)
{
    // Validamos que la cédula no esté repetida en la tabla historias
    $request->validate([
        'cedula' => 'required|unique:historias,cedula',
        'nombre_completo' => 'required',
        'numero_historia' => 'required|unique:historias,numero_historia'
    ]);

    \App\Models\historias::create([
        'cedula' => $request->cedula,
        'nombre_completo' => $request->nombre_completo,
        'numero_historia' => $request->numero_historia,
        'observations' => 'Paciente pre-registrado por el administrador.'
    ]);

    return back()->with('message', 'Paciente registrado exitosamente en la base de datos.');
}




}