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
        Schema::create('solicitud_espacios', function (Blueprint $table) {
            $table->id();
            $table->string('respuesta');
            $table->timestamps(false);

            $table->unsignedBigInteger('idEspacio');
            $table->unsignedBigInteger('idEstado');
            $table->unsignedBigInteger('idHorario');
            
            $table->foreign('idEspacio')->references('id')->on('espacios');
            $table->foreign('idEstado')->references('id')->on('estados');
            $table->foreign('idHorario')->references('id')->on('horarios');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitud_espacios');
    }
};
