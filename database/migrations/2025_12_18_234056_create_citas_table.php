<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('citas', function (Blueprint $table) {
           $table->id();
            $table->string('token_unique')->unique(); // Para el ticket PDF
            $table->string('nombre');
            $table->string('apellido');
            $table->integer('edad');
            $table->string('cedula')->index(); 
            $table->string('departamento');
            $table->dateTime('cita_hora'); // Fecha y hora de la cita
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};
