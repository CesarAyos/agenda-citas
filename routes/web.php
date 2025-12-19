<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\CitasController;

// 1. Página principal para el paciente
Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('/agendar', function () {
    return redirect('/');
});

// 2. Proceso de agendar (POST)
Route::post('/agendar', [CitasController::class, 'store'])->name('citas.store');

// 3. Descarga del ticket
Route::get('/descargar-ticket/{token}', [CitasController::class, 'downloadTicket'])->name('appointment.success');

// --- RUTAS PROTEGIDAS (ADMIN) ---
Route::middleware(['auth', 'verified'])->group(function () {
    // El Dashboard
    Route::get('/dashboard', [CitasController::class, 'index'])->name('dashboard');
});

// Nota: Eliminamos el require de auth.php porque tus rutas de autenticación 
// probablemente ya están cargadas por Breeze en otro lugar o no existen en esa ruta.
require __DIR__.'/settings.php';