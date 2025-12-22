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
        Schema::create('historias', function (Blueprint $table) {
            $table->id();
            // Definimos la cédula como única para evitar duplicados a nivel de DB
            $table->string('cedula')->unique();
            $table->string('nombre_completo');
            $table->string('numero_historia')->unique();
            
            // Usamos nullable() para que no de error si el campo va vacío
            $table->text('observaciones')->nullable(); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historias');
    }
};