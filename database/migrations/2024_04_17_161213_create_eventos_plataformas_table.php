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
        Schema::create('eventos_plataformas', function (Blueprint $table) {
            $table->unsignedBigInteger('idEvento');
            $table->unsignedBigInteger('idPlataforma');
            $table->timestamps();

            $table->foreign('idEvento')->references('id')->on('eventos');
            $table->foreign('idPlataforma')->references('id')->on('plataformas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eventos_plataformas');
    }
};