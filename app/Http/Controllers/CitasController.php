<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\citas; 
use Carbon\Carbon;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Inertia\Inertia;

class CitasController extends Controller
{
   
public function index()
{
    // 1. Lista de Citas (con su número de historia vinculado)
    $citas = \App\Models\citas::all()->map(function ($cita) {
        $historia = \App\Models\historias::where('cedula', $cita->cedula)->first();
        $cita->numero_ficha = $historia ? $historia->numero_historia : 'Sin asignar';
        return $cita;
    });

    // 2. Lista de todos los Pacientes registrados (la base de datos maestra)
    $pacientesMaster = \App\Models\historias::all();

    return \Inertia\Inertia::render('Dashboard', [
        'citas' => $citas,
        'pacientesMaster' => \App\Models\historias::all(),
    ]);
}

    /**
     * STORE: Procesa el formulario del paciente
     */
    public function store(Request $request) 
    {
        $request->validate([
            'nombre' => 'required|string',
            'apellido' => 'required|string',
            'edad' => 'required|integer',
            'cedula' => 'required|string',
            'cita_hora' => 'required|date',
        ]);

        $fechaCita = Carbon::parse($request->cita_hora);
        $hoy = Carbon::now('America/Caracas');

        // REGLA: Bloqueo hoy después de las 10:00 AM
        if ($fechaCita->isToday() && $hoy->hour >= 10) {
            return back()->withErrors(['error' => 'Ya no se permiten citas para hoy después de las 10:00 AM.']);
        }

        // REGLA: Máximo 20 cupos
        $cuposUsados = citas::whereDate('cita_hora', $fechaCita->toDateString())->count();
        if ($cuposUsados >= 20) {
            return back()->withErrors(['error' => 'Lo sentimos, no hay cupos disponibles para esta fecha.']);
        }

        // REGLA: Intervalo 30 min
        $choque = citas::whereBetween('cita_hora', [
            $fechaCita->copy()->subMinutes(29),
            $fechaCita->copy()->addMinutes(29)
        ])->exists();

        if ($choque) {
            return back()->withErrors(['error' => 'Esta hora ya está ocupada.']);
        }

        // GUARDAR
        $citaNueva = citas::create([
            'token_unique' => strtolower(Str::random(10)), 
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'edad' => $request->edad,
            'cedula' => $request->cedula,
            'cita_hora' => $fechaCita,
        ]);

        // LA CLAVE: Redirigir a "/" enviando el token en la sesión (Flash data)
        // Esto evita el error de "Method GET not supported"
        return redirect('/')->with('successToken', $citaNueva->token_unique);
    }

    /**
     * PDF: Descarga del ticket
     */
    public function downloadTicket($token)
    {
        $cita = citas::where('token_unique', $token)->firstOrFail();
        $pdf = Pdf::loadView('pdf.ticket', compact('cita'));
        
        return $pdf->download('ticket-cita-'.$cita->cedula.'.pdf');
    }


    public function registrarPaciente(Request $request)
{
    // 1. Validamos, pero quitamos la regla 'unique' para que no rebote
    $request->validate([
        'cedula' => 'required|string',
        'nombre_completo' => 'required|string',
        'numero_historia' => 'required|string',
    ]);

    // 2. Buscamos al paciente por cédula. Si existe, lo actualiza. Si no, lo crea.
    $paciente = \App\Models\historias::updateOrCreate(
        ['cedula' => $request->cedula], // Condición de búsqueda
        [
            'nombre_completo' => $request->nombre_completo,
            'numero_historia' => $request->numero_historia,
            // Agrega otros campos necesarios aquí
        ]
    );


    return back()->with('message', 'Paciente procesado correctamente');
}
}