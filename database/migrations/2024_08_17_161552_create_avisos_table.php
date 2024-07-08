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
        Schema::create('avisos', function (Blueprint $table) {
            $table->id()->primary()->unsigned()->unique();

            $table->boolean("avisarUsuario")->default(0);
            $table->boolean("avisarStaff")->default(0);

            $table->unsignedBigInteger("idUsuario");
            $table->unsignedBigInteger("idEvento")->nullable();
            $table->unsignedBigInteger("idSolicitudEspacio")->nullable();
            
            

            $table->foreign('idUsuario')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('idEvento')->references('id')->on('eventos')->cascadeOnDelete();
            $table->foreign('idSolicitudEspacio')->references('id')->on('solicitud_espacios')->cascadeOnDelete();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notificaciones');
    }
};
