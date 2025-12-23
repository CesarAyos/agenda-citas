<?php

namespace App\Http\Controllers;

use App\Models\Historia;
use App\Models\Cita;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HistoriasController extends Controller
{
    // app/Http/Controllers/HistoriasController.php

public function show($cedula)
{
    $historia = Historia::where('cedula', $cedula)->first();

    if (!$historia) {
        $cita = Cita::where('cedula', $cedula)->first();
        
        $historia = Historia::create([
            'cedula' => $cedula,
            'nombre_completo' => request('nombre_completo') ?? 'Nombre Temporal',
            'numero_historia' => 'H-' . time(),
            'observaciones'   => 'Creada automáticamente al consultar',
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
    $historia = Historia::findOrFail($request->id);

    // Actualizamos AMBOS campos
    $historia->update([
        'numero_historia' => $request->numero_historia,
        'observaciones'    => $request->observations ?? $historia->observaciones,
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

    Historia::create([
        'cedula' => $request->cedula,
        'nombre_completo' => $request->nombre_completo,
        'numero_historia' => $request->numero_historia,
        'observaciones' => 'Paciente pre-registrado por el administrador.'
    ]);

    return back()->with('message', 'Paciente registrado exitosamente en la base de datos.');
}




}