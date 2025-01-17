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
        Schema::create('reservaciones', function (Blueprint $table) {
            $table->id()->primary()->unsigned()->unique();
            $table->string('motivo', 1000);
            $table->date('fecha');
            $table->time('inicio');
            $table->time('fin');
            $table->string('respuesta', 1000)->nullable();

            $table->unsignedBigInteger('idUsuario');
            $table->unsignedBigInteger('idEspacio');
            $table->unsignedBigInteger('idEstado')->default(1);
            $table->unsignedBigInteger('idEvento')->nullable();
            
            $table->foreign('idUsuario')->references('id')->on('users');
            $table->foreign('idEspacio')->references('id')->on('espacios');
            $table->foreign('idEstado')->references('id')->on('estados');
            $table->foreign('idEvento')->references('id')->on('eventos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservaciones');
    }
};
