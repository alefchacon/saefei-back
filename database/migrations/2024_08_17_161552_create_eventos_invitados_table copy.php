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
        Schema::create('eventos_invitados', function (Blueprint $table) {
            $table->unsignedBigInteger('idEvento');
            $table->unsignedBigInteger('idInvitado');

            $table->foreign('idEvento')->references('id')->on('eventos');
            $table->foreign('idInvitado')->references('id')->on('invitados');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eventos_invitados');
    }
};
