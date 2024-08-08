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

            $table->boolean("visto")->default(0);

            $table->unsignedBigInteger("idEvento")->nullable();
            $table->unsignedBigInteger("idReservacion")->nullable();
            $table->unsignedBigInteger("idUsuario")->nullable();
            $table->unsignedBigInteger("idEstado");
            $table->unsignedBigInteger("idTipoAviso");
            
            $table->foreign('idEvento')->references('id')->on('eventos')->cascadeOnDelete();
            $table->foreign('idReservacion')->references('id')->on('reservaciones')->cascadeOnDelete();
            $table->foreign('idUsuario')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('idEstado')->references('id')->on('estados')->cascadeOnDelete();
            $table->foreign('idTipoAviso')->references('id')->on('tiposavisos')->cascadeOnDelete();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('avisos');
    }
};
