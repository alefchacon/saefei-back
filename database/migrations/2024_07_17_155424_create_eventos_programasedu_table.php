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
        Schema::create('eventos_programasedu', function (Blueprint $table) {
            $table->unsignedBigInteger('idEvento');
            $table->unsignedBigInteger('idProgramaEducativo');
            $table->timestamps();

            $table->foreign('idEvento')->references('id')->on('eventos');
            $table->foreign('idProgramaEducativo')->references('id')->on('programa_educativos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eventos_programasedu');
    }
};