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
    /**
     * DASHBOARD: Muestra la lista de citas al Administrador
     */
// app/Http/Controllers/CitasController.php

public function index()
{
    // Obtenemos las citas y les adjuntamos el numero_historia buscando en la tabla historias
    $citas = citas::all()->map(function ($cita) {
        $historia = \App\Models\historias::where('cedula', $cita->cedula)->first();
        
        // Añadimos una propiedad extra al objeto cita para la vista
        $cita->numero_ficha = $historia ? $historia->numero_historia : 'Sin asignar';
        return $cita;
    });

    return Inertia::render('Dashboard', [
        'citas' => $citas
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
}