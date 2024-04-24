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
            $table->text("razonCalificacionAtencion");
            $table->integer("calificacionComunicacion");
            $table->text("mejorasApoyo");
            $table->integer("calificacionEspacio");
            $table->text("problemasEspacio");
            $table->integer("calificacionCentroComputo");
            $table->text("razonCalificacionCentroComputo");
            $table->integer("calificacionRecursos");
            $table->text("razonCalificacionRecursos");
            $table->text("problemasRecursos");
            $table->text("mejorasRecursos");
            $table->text("adicional");

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
