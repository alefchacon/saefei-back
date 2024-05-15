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
        Schema::create('evaluaciones', function (Blueprint $table) {
            
            $table->id()->primary()->unsigned()->unique();

            $table->integer("calificacionAtencion");
            $table->string("razonCalificacionAtencion");
            $table->integer("calificacionComunicacion");
            $table->string("mejorasApoyo")->nullable()->default("Sin comentarios.");
            $table->integer("calificacionEspacio");
            $table->string("problemasEspacio")->nullable()->default("Sin comentarios.");
            $table->integer("calificacionCentroComputo");
            $table->string("razonCalificacionCentroComputo");
            $table->integer("calificacionRecursos");
            $table->string("razonCalificacionRecursos");
            $table->string("mejorasRecursos")->nullable()->default("Sin comentarios.");
            $table->string("adicional")->nullable()->default("Sin comentarios.");

            $table->integer("idEvento");

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluaciones');
    }
};
