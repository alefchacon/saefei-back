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
            $table->id()->primary()->unsigned()->unique();
            $table->string('respuesta')->nullable();
            $table->dateTime('inicio');
            $table->dateTime('fin');

            $table->boolean("avisarAdministrador")->default(0);
            $table->boolean("avisarUsuario")->default(0);

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
        Schema::dropIfExists('solicitud_espacios');
    }
};
