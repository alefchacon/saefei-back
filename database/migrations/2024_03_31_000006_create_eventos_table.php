<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rules\Unique;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        
        Schema::create('eventos', function (Blueprint $table) {

            $table->id()->primary()->unsigned()->unique();

            $table->string("nombre");
            $table->string("descripcion");
            $table->string("pagina")->nullable()->default("uv.mx/fei");
            $table->string("ambito");
            $table->string("audiencias");
            $table->string("eje");
            $table->string("tematicas");
            $table->string("plataformas")->nullable();
            $table->integer("numParticipantes");
            $table->string("requisitosCentroComputo");
            $table->integer("numParticipantesExternos");
            $table->boolean("requiereEstacionamiento");
            $table->boolean("requiereFinDeSemana");
            $table->boolean("requiereMaestroDeObra");
            $table->boolean("requiereNotificarPrensaUV");
            $table->string("adicional")->nullable();
            $table->string("respuesta")->nullable();
            $table->date("inicio");
            $table->date("fin");

            $table->unsignedBigInteger("idUsuario");
            $table->unsignedBigInteger("idModalidad");
            $table->unsignedBigInteger("idEstado");
            $table->unsignedBigInteger("idTipo");

            $table->foreign("idUsuario")->references("id")->on("users");
            $table->foreign("idModalidad")->references("id")->on("modalidads");
            $table->foreign("idEstado")->references("id")->on("estados");
            $table->foreign("idTipo")->references("id")->on("tipos");
            $table->timestamps(false);
        });
        // DB::statement("ALTER TABLE eventos ADD cronograma MEDIUMBLOB NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        
        Schema::dropIfExists('eventos');
        
    }
};
