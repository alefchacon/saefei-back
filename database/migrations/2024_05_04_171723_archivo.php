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
        Schema::create('archivos', function (Blueprint $table) {
            
            $table->id()->primary()->unsigned()->unique();
            $table->timestamps();
            $table->string("nombre");

            $table->unsignedBigInteger('idEvento');
            $table->unsignedBigInteger('idTipoArchivo');

            $table->foreign('idEvento')->references('id')->on('eventos');
            $table->foreign('idTipoArchivo')->references('id')->on('tiposarchivos');

        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('archivos');
    }
};
